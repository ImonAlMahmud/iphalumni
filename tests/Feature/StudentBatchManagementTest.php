<?php

namespace Tests\Feature;

use App\Models\User;
use App\Services\StudentBatchImportService;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class StudentBatchManagementTest extends TestCase
{
    /**
     * Test that AlumniProfile getStats returns the count of distinct batches from students_reference.
     */
    public function test_stats_batch_count_matches_students_reference_dropdown(): void
    {
        $alumniModel = new \App\Models\AlumniProfile();
        $stats = $alumniModel->getStats();

        $expectedBatchesCount = DB::table('students_reference')
            ->whereNotNull('batch')
            ->where('batch', '!=', '')
            ->distinct()
            ->count('batch');

        $this->assertEquals($expectedBatchesCount, $stats['batches']);
        $this->assertGreaterThanOrEqual(14, $stats['batches']);
    }

    /**
     * Test sample template download.
     */
    public function test_sample_template_download(): void
    {
        $adminUser = User::first() ?? new User(['name' => 'Admin', 'email' => 'admin@test.com']);
        $adminUser->role = 'admin';
        $this->actingAs($adminUser);

        $response = $this->get('/admin/students/sample-template');
        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'text/csv; charset=UTF-8');
    }

    /**
     * Test adding a student with a new batch.
     */
    public function test_store_new_batch_and_student(): void
    {
        $adminUser = User::first() ?? new User(['name' => 'Admin', 'email' => 'admin@test.com']);
        $adminUser->role = 'admin';
        $this->actingAs($adminUser);

        $testBatch = 'L-TEST-99';

        $response = $this->post('/admin/students/store', [
            'batch'           => $testBatch,
            'session'         => '2026-27',
            'department'      => 'BSc in Health Technology (Laboratory)',
            'roll'            => '999',
            'name_english'    => 'Test User 99',
            'name_bangla'     => 'টেস্ট ইউজার',
            'mobile'          => '01799999999',
            'guardian_mobile' => '01899999999',
        ]);

        $response->assertRedirect('/admin/students?batch=' . urlencode($testBatch));

        $this->assertDatabaseHas('students_reference', [
            'batch'        => $testBatch,
            'roll'         => '999',
            'name_english' => 'Test User 99',
        ]);

        // Clean up
        DB::table('students_reference')->where('batch', $testBatch)->delete();
    }

    /**
     * Test batch import service with CSV.
     */
    public function test_csv_batch_import(): void
    {
        $service = new StudentBatchImportService();

        $csvContent = "\xEF\xBB\xBF" . "Roll,Name (English),Name (Bangla),Mobile,Guardian Mobile,Batch,Session,Department\n"
                    . "101,Imported Student 1,শিক্ষার্থী ১,01711111101,01811111101,L-TEST-IMPORT,2026-27,Laboratory\n"
                    . "102,Imported Student 2,শিক্ষার্থী ২,01711111102,01811111102,L-TEST-IMPORT,2026-27,Laboratory\n";

        $tempFile = tempnam(sys_get_temp_dir(), 'test_import_') . '.csv';
        file_put_contents($tempFile, $csvContent);

        $uploadedFile = new UploadedFile(
            $tempFile,
            'test_import.csv',
            'text/csv',
            null,
            true
        );

        $result = $service->import($uploadedFile, ['duplicate_action' => 'insert']);

        $this->assertTrue($result['success']);
        $this->assertEquals(2, $result['imported']);
        $this->assertContains('L-TEST-IMPORT', $result['batches']);

        // Clean up
        if (file_exists($tempFile)) unlink($tempFile);
        DB::table('students_reference')->where('batch', 'L-TEST-IMPORT')->delete();
    }

    /**
     * Test batch import service with XLSX file.
     */
    public function test_xlsx_batch_import(): void
    {
        $service = new StudentBatchImportService();
        $zip = new \ZipArchive();
        $tempXlsx = tempnam(sys_get_temp_dir(), 'test_xlsx_') . '.xlsx';

        $this->assertTrue($zip->open($tempXlsx, \ZipArchive::CREATE | \ZipArchive::OVERWRITE));

        // Shared strings
        $sharedXml = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
            . '<sst xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main" count="8" uniqueCount="8">'
            . '<si><t>Roll</t></si>'
            . '<si><t>Name (English)</t></si>'
            . '<si><t>Name (Bangla)</t></si>'
            . '<si><t>Mobile</t></si>'
            . '<si><t>Guardian Mobile</t></si>'
            . '<si><t>Batch</t></si>'
            . '<si><t>Session</t></si>'
            . '<si><t>Department</t></si>'
            . '<si><t>Xlsx Student</t></si>'
            . '<si><t>এক্সেল শিক্ষার্থী</t></si>'
            . '<si><t>01700000001</t></si>'
            . '<si><t>01800000001</t></si>'
            . '<si><t>L-TEST-XLSX</t></si>'
            . '<si><t>2026-27</t></si>'
            . '<si><t>Laboratory</t></si>'
            . '</sst>';

        // Sheet data
        $sheetXml = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
            . '<worksheet xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main">'
            . '<sheetData>'
            . '<row r="1">'
            . '<c r="A1" t="s"><v>0</v></c>'
            . '<c r="B1" t="s"><v>1</v></c>'
            . '<c r="C1" t="s"><v>2</v></c>'
            . '<c r="D1" t="s"><v>3</v></c>'
            . '<c r="E1" t="s"><v>4</v></c>'
            . '<c r="F1" t="s"><v>5</v></c>'
            . '<c r="G1" t="s"><v>6</v></c>'
            . '<c r="H1" t="s"><v>7</v></c>'
            . '</row>'
            . '<row r="2">'
            . '<c r="A2"><v>1</v></c>'
            . '<c r="B2" t="s"><v>8</v></c>'
            . '<c r="C2" t="s"><v>9</v></c>'
            . '<c r="D2" t="s"><v>10</v></c>'
            . '<c r="E2" t="s"><v>11</v></c>'
            . '<c r="F2" t="s"><v>12</v></c>'
            . '<c r="G2" t="s"><v>13</v></c>'
            . '<c r="H2" t="s"><v>14</v></c>'
            . '</row>'
            . '</sheetData>'
            . '</worksheet>';

        $zip->addFromString('xl/sharedStrings.xml', $sharedXml);
        $zip->addFromString('xl/worksheets/sheet1.xml', $sheetXml);
        $zip->close();

        $uploadedFile = new UploadedFile(
            $tempXlsx,
            'test_import.xlsx',
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            null,
            true
        );

        $result = $service->import($uploadedFile, ['duplicate_action' => 'insert']);

        $this->assertTrue($result['success']);
        $this->assertEquals(1, $result['imported']);
        $this->assertContains('L-TEST-XLSX', $result['batches']);

        // Clean up
        if (file_exists($tempXlsx)) unlink($tempXlsx);
        DB::table('students_reference')->where('batch', 'L-TEST-XLSX')->delete();
    }
}
