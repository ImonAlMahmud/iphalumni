<?php
declare(strict_types=1);

namespace App\Services;

use Exception;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use ZipArchive;

class StudentBatchImportService
{
    /**
     * Parse CSV or XLSX file and import into students_reference table.
     *
     * @param UploadedFile $file
     * @param array $defaults ['batch' => '', 'session' => '', 'department' => '', 'duplicate_action' => 'skip']
     * @return array ['success' => bool, 'imported' => int, 'updated' => int, 'skipped' => int, 'batches' => array, 'errors' => array]
     */
    public function import(UploadedFile $file, array $defaults = []): array
    {
        $ext = strtolower($file->getClientOriginalExtension());
        $path = $file->getRealPath();

        $rows = [];
        if (in_array($ext, ['csv', 'txt'])) {
            $rows = $this->parseCsv($path);
        } elseif (in_array($ext, ['xlsx', 'xlsm'])) {
            $rows = $this->parseXlsx($path);
        } else {
            // Try CSV parser as fallback if extension is generic
            $rows = $this->parseCsv($path);
        }

        if (empty($rows)) {
            return [
                'success'  => false,
                'imported' => 0,
                'updated'  => 0,
                'skipped'  => 0,
                'batches'  => [],
                'errors'   => ['ফাইলটিতে কোনো ডাটা বা সঠিক কলাম পাওয়া যায়নি।'],
            ];
        }

        return $this->processRows($rows, $defaults);
    }

    /**
     * Parse CSV file with auto delimiter detection and UTF-8 BOM handling.
     */
    public function parseCsv(string $filePath): array
    {
        $content = file_get_contents($filePath);
        if ($content === false || trim($content) === '') {
            return [];
        }

        // Remove UTF-8 BOM if present
        if (str_starts_with($content, "\xEF\xBB\xBF")) {
            $content = substr($content, 3);
        }

        // Detect delimiter (comma, semicolon, tab)
        $firstLine = strtok($content, "\r\n");
        $delimiters = [',', ';', "\t"];
        $bestDelimiter = ',';
        $maxCount = 0;
        foreach ($delimiters as $d) {
            $c = substr_count($firstLine, $d);
            if ($c > $maxCount) {
                $maxCount = $c;
                $bestDelimiter = $d;
            }
        }

        $lines = preg_split("/\r\n|\n|\r/", trim($content));
        if (empty($lines)) {
            return [];
        }

        $header = null;
        $data = [];

        foreach ($lines as $line) {
            $line = trim($line);
            if ($line === '') continue;

            $row = str_getcsv($line, $bestDelimiter);
            if (!$header) {
                $header = array_map(fn($h) => trim((string)$h), $row);
                continue;
            }

            // Fill row to match header length
            $rowAssoc = [];
            foreach ($header as $i => $hKey) {
                $rowAssoc[$hKey] = isset($row[$i]) ? trim((string)$row[$i]) : '';
            }
            $data[] = $rowAssoc;
        }

        return $data;
    }

    /**
     * Parse XLSX file using ZipArchive and SimpleXML (pure PHP, zero ext-gd dependencies).
     */
    public function parseXlsx(string $filePath): array
    {
        $zip = new ZipArchive();
        if ($zip->open($filePath) !== true) {
            return [];
        }

        // 1. Read shared strings
        $sharedStrings = [];
        $sharedXmlContent = $zip->getFromName('xl/sharedStrings.xml');
        if ($sharedXmlContent !== false) {
            $xmlObj = @simplexml_load_string($sharedXmlContent);
            if ($xmlObj && isset($xmlObj->si)) {
                foreach ($xmlObj->si as $si) {
                    if (isset($si->t)) {
                        $sharedStrings[] = (string)$si->t;
                    } elseif (isset($si->r)) {
                        $str = '';
                        foreach ($si->r as $r) {
                            $str .= (string)$r->t;
                        }
                        $sharedStrings[] = $str;
                    } else {
                        $sharedStrings[] = '';
                    }
                }
            }
        }

        // 2. Find first sheet
        $sheetXmlContent = $zip->getFromName('xl/worksheets/sheet1.xml');
        if ($sheetXmlContent === false) {
            // Find any sheet XML
            for ($i = 0; $i < $zip->numFiles; $i++) {
                $name = $zip->getNameIndex($i);
                if (preg_match('#xl/worksheets/sheet\d+\.xml#i', $name)) {
                    $sheetXmlContent = $zip->getFromIndex($i);
                    break;
                }
            }
        }
        $zip->close();

        if ($sheetXmlContent === false) {
            return [];
        }

        $sheetXml = @simplexml_load_string($sheetXmlContent);
        if (!$sheetXml || !isset($sheetXml->sheetData->row)) {
            return [];
        }

        $rawGrid = [];
        foreach ($sheetXml->sheetData->row as $rowNode) {
            $rowNum = (int)$rowNode['r'];
            $rowCells = [];

            foreach ($rowNode->c as $cNode) {
                $cellRef = (string)$cNode['r']; // e.g. A1, B2
                $colLetters = preg_replace('/[0-9]/', '', $cellRef);
                $colIndex = $this->colLetterToIndex($colLetters);

                $valType = (string)$cNode['t'];
                $val = '';

                if ($valType === 's') { // shared string index
                    $idx = (int)$cNode->v;
                    $val = $sharedStrings[$idx] ?? '';
                } elseif ($valType === 'inlineStr' && isset($cNode->is->t)) {
                    $val = (string)$cNode->is->t;
                } elseif (isset($cNode->v)) {
                    $val = (string)$cNode->v;
                }

                $rowCells[$colIndex] = trim($val);
            }

            if (!empty($rowCells)) {
                // Ensure array has values for all columns up to the max index
                $maxIndex = max(array_keys($rowCells));
                $filled = [];
                for ($ci = 0; $ci <= $maxIndex; $ci++) {
                    $filled[$ci] = $rowCells[$ci] ?? '';
                }
                $rawGrid[] = $filled;
            }
        }

        if (empty($rawGrid)) {
            return [];
        }

        $headerRow = array_shift($rawGrid);
        $result = [];

        foreach ($rawGrid as $row) {
            $assoc = [];
            $hasAnyData = false;
            foreach ($headerRow as $idx => $headerName) {
                $val = $row[$idx] ?? '';
                $assoc[$headerName] = $val;
                if ($val !== '') $hasAnyData = true;
            }
            if ($hasAnyData) {
                $result[] = $assoc;
            }
        }

        return $result;
    }

    /**
     * Convert Excel column letters (A, B, ..., Z, AA, AB...) to 0-based integer index.
     */
    private function colLetterToIndex(string $letters): int
    {
        $letters = strtoupper($letters);
        $index = 0;
        $len = strlen($letters);
        for ($i = 0; $i < $len; $i++) {
            $index = $index * 26 + (ord($letters[$i]) - ord('A') + 1);
        }
        return $index - 1;
    }

    /**
     * Map arbitrary row headers to standard database columns.
     */
    private function mapColumns(array $sampleRow): array
    {
        $mapping = [];

        foreach ($sampleRow as $colName => $val) {
            $normalized = strtolower(trim((string)$colName));
            $normalized = preg_replace('/[\s_\-\.\(\)]+/', '', $normalized);

            if (in_array($normalized, ['roll', 'classroll', 'rollno', 'id', 'studentid', 'studentroll', 'রোল'])) {
                $mapping['roll'] = $colName;
            } elseif (in_array($normalized, ['nameenglish', 'nameen', 'englishname', 'fullname', 'name', 'studentname', 'নামইংরেজি', 'নাম'])) {
                $mapping['name_english'] = $colName;
            } elseif (in_array($normalized, ['namebangla', 'namebn', 'banglaname', 'bengaliname', 'নামবাংলা', 'বাংলানাম'])) {
                $mapping['name_bangla'] = $colName;
            } elseif (in_array($normalized, ['mobile', 'phone', 'phonenumber', 'contact', 'cell', 'mobileno', 'মোবাইল', 'ফোন'])) {
                $mapping['mobile'] = $colName;
            } elseif (in_array($normalized, ['guardianmobile', 'guardianphone', 'parentphone', 'parentmobile', 'guardiansmobile', 'অভিভাবকেরমোবাইল'])) {
                $mapping['guardian_mobile'] = $colName;
            } elseif (in_array($normalized, ['batch', 'batchname', 'batchyear', 'ব্যাচ'])) {
                $mapping['batch'] = $colName;
            } elseif (in_array($normalized, ['session', 'academicsession', 'year', 'academicyear', 'সেশন'])) {
                $mapping['session'] = $colName;
            } elseif (in_array($normalized, ['department', 'dept', 'discipline', 'course', 'বিভাগ'])) {
                $mapping['department'] = $colName;
            }
        }

        return $mapping;
    }

    /**
     * Process parsed rows and insert/update database.
     */
    private function processRows(array $rows, array $defaults): array
    {
        if (empty($rows)) {
            return [
                'success'  => false,
                'imported' => 0,
                'updated'  => 0,
                'skipped'  => 0,
                'batches'  => [],
                'errors'   => ['কোনো ডাটা পাওয়া যায়নি।'],
            ];
        }

        $colMap = $this->mapColumns($rows[0]);

        $defaultBatch   = trim($defaults['batch'] ?? '');
        $defaultSession = trim($defaults['session'] ?? '');
        $defaultDept    = trim($defaults['department'] ?? '');
        $duplicateAction = $defaults['duplicate_action'] ?? 'skip'; // skip | update | insert

        $importedCount = 0;
        $updatedCount  = 0;
        $skippedCount  = 0;
        $batchesFound  = [];
        $errors        = [];

        DB::beginTransaction();
        try {
            foreach ($rows as $lineIdx => $row) {
                $roll = isset($colMap['roll']) ? trim((string)$row[$colMap['roll']]) : '';
                $nameEnglish = isset($colMap['name_english']) ? trim((string)$row[$colMap['name_english']]) : '';
                $nameBangla = isset($colMap['name_bangla']) ? trim((string)$row[$colMap['name_bangla']]) : '';
                $mobile = isset($colMap['mobile']) ? trim((string)$row[$colMap['mobile']]) : '';
                $guardianMobile = isset($colMap['guardian_mobile']) ? trim((string)$row[$colMap['guardian_mobile']]) : '';
                
                $batch = isset($colMap['batch']) && trim((string)$row[$colMap['batch']]) !== '' 
                    ? trim((string)$row[$colMap['batch']]) 
                    : $defaultBatch;

                $session = isset($colMap['session']) && trim((string)$row[$colMap['session']]) !== '' 
                    ? trim((string)$row[$colMap['session']]) 
                    : $defaultSession;

                $department = isset($colMap['department']) && trim((string)$row[$colMap['department']]) !== '' 
                    ? trim((string)$row[$colMap['department']]) 
                    : $defaultDept;

                // If no name_english but name_bangla exists, use bangla for english too
                if ($nameEnglish === '' && $nameBangla !== '') {
                    $nameEnglish = $nameBangla;
                }

                // Minimum required: name or roll
                if ($nameEnglish === '' && $roll === '') {
                    $skippedCount++;
                    continue;
                }

                // Batch must be present either from row or default
                if ($batch === '') {
                    $skippedCount++;
                    $errors[] = "Row #" . ($lineIdx + 2) . ": ব্যাচ নির্ধারণ করা নেই (Batch missing)";
                    continue;
                }

                $batchesFound[$batch] = true;

                // Check for existing duplicate by (batch + roll) or (batch + name_english)
                $existing = null;
                if ($roll !== '') {
                    $existing = DB::table('students_reference')
                        ->where('batch', $batch)
                        ->where('roll', $roll)
                        ->first();
                }

                if (!$existing && $mobile !== '') {
                    $existing = DB::table('students_reference')
                        ->where('batch', $batch)
                        ->where('mobile', $mobile)
                        ->first();
                }

                if ($existing) {
                    if ($duplicateAction === 'skip') {
                        $skippedCount++;
                        continue;
                    } elseif ($duplicateAction === 'update') {
                        DB::table('students_reference')
                            ->where('id', $existing->id)
                            ->update([
                                'roll'            => $roll !== '' ? $roll : $existing->roll,
                                'name_english'    => $nameEnglish !== '' ? $nameEnglish : $existing->name_english,
                                'name_bangla'     => $nameBangla !== '' ? $nameBangla : $existing->name_bangla,
                                'mobile'          => $mobile !== '' ? $mobile : $existing->mobile,
                                'guardian_mobile' => $guardianMobile !== '' ? $guardianMobile : $existing->guardian_mobile,
                                'session'         => $session !== '' ? $session : $existing->session,
                                'department'      => $department !== '' ? $department : $existing->department,
                            ]);
                        $updatedCount++;
                        continue;
                    }
                    // if 'insert', proceed to insert a new row
                }

                DB::table('students_reference')->insert([
                    'roll'            => $roll !== '' ? $roll : null,
                    'name_english'    => $nameEnglish,
                    'name_bangla'     => $nameBangla !== '' ? $nameBangla : null,
                    'mobile'          => $mobile !== '' ? $mobile : null,
                    'guardian_mobile' => $guardianMobile !== '' ? $guardianMobile : null,
                    'batch'           => $batch,
                    'session'         => $session !== '' ? $session : 'N/A',
                    'department'      => $department !== '' ? $department : 'N/A',
                    'created_at'      => now(),
                ]);
                $importedCount++;
            }

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            return [
                'success'  => false,
                'imported' => 0,
                'updated'  => 0,
                'skipped'  => 0,
                'batches'  => [],
                'errors'   => ['ইমপোর্ট করার সময় ত্রুটি ঘটেছে: ' . $e->getMessage()],
            ];
        }

        return [
            'success'  => true,
            'imported' => $importedCount,
            'updated'  => $updatedCount,
            'skipped'  => $skippedCount,
            'batches'  => array_keys($batchesFound),
            'errors'   => array_slice($errors, 0, 5),
        ];
    }
}
