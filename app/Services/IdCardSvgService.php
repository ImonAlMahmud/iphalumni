<?php
declare(strict_types=1);

namespace App\Services;

use App\Models\AlumniProfile;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use ZipArchive;

class IdCardSvgService
{
    /**
     * Gather all data required for rendering a member's ID card.
     */
    public function getCardData(int $alumniProfileId): ?array
    {
        $profile = DB::table('alumni_profiles as ap')
            ->join('users as u', 'u.id', '=', 'ap.user_id')
            ->select('ap.*', 'u.name', 'u.email', 'u.avatar as user_avatar')
            ->where('ap.id', $alumniProfileId)
            ->whereNull('ap.deleted_at')
            ->first();

        if (!$profile) {
            return null;
        }

        $profile = (array)$profile;

        // Student reference if mapped
        $refData = null;
        if (!empty($profile['student_reference_id'])) {
            $ref = DB::table('students_reference')->where('id', $profile['student_reference_id'])->first();
            if ($ref) $refData = (array)$ref;
        }

        // Latest Academic Degree
        $lastEdu = DB::table('alumni_education')
            ->where('alumni_profile_id', $alumniProfileId)
            ->orderByRaw('CAST(graduation_year AS UNSIGNED) DESC, id DESC')
            ->first();

        $latestDegree = '';
        if ($lastEdu) {
            $latestDegree = (string)($lastEdu->degree ?? '');
            if (!empty($lastEdu->field_of_study)) {
                $latestDegree .= ' in ' . $lastEdu->field_of_study;
            }
        }

        // Dynamic Committee Position
        $cm = DB::table('committee_members')
            ->where('user_id', $profile['user_id'])
            ->where('is_active', 1)
            ->whereNull('deleted_at')
            ->orderBy('sort_order', 'asc')
            ->orderBy('id', 'asc')
            ->first();

        $memberTitle = !empty($cm->designation) ? (string)$cm->designation : 'IPH Alumni Member';

        $rawId       = $profile['id'] ?? $profile['user_id'];
        $memberNo    = 'IPHAA-' . str_pad((string)$rawId, 5, '0', STR_PAD_LEFT);
        $degree      = !empty($latestDegree) ? $latestDegree : (!empty($profile['degree']) ? $profile['degree'] : ($refData['department'] ?? 'Public Health Graduate'));
        $batch       = !empty($refData['batch']) ? $refData['batch'] : (!empty($refData['session']) ? $refData['session'] : (!empty($profile['batch_year']) ? $profile['batch_year'] : 'N/A'));
        $phone       = !empty($profile['phone']) ? $profile['phone'] : ($refData['mobile'] ?? 'N/A');
        $nidNumber   = !empty($profile['nid_number']) ? $profile['nid_number'] : 'N/A';
        $bloodGroup  = !empty($profile['blood_group']) ? $profile['blood_group'] : 'N/A';
        $issueDate   = !empty($profile['created_at']) ? date('d M Y', strtotime($profile['created_at'])) : date('d M Y');

        // Build Full Present Location
        $presentParts = [];
        if (($profile['location_type'] ?? 'bangladesh') === 'abroad') {
            if (!empty($profile['province_city'])) $presentParts[] = trim((string)$profile['province_city']);
            if (!empty($profile['country']) && strtolower(trim((string)$profile['country'])) !== 'bangladesh') {
                $presentParts[] = trim((string)$profile['country']);
            }
            if (empty($presentParts) && !empty($profile['current_location'])) {
                $presentParts[] = trim((string)$profile['current_location']);
            }
        } else {
            if (!empty($profile['thana_upazila'])) {
                $presentParts[] = trim((string)$profile['thana_upazila']);
            }
            if (!empty($profile['current_location'])) {
                $curLoc = trim((string)$profile['current_location']);
                if (empty($presentParts) || !str_contains(strtolower($presentParts[0]), strtolower($curLoc))) {
                    $presentParts[] = $curLoc;
                }
            }
            if (empty($presentParts) && !empty($profile['country'])) {
                $presentParts[] = trim((string)$profile['country']);
            }
        }
        $presentLocation = !empty($presentParts) ? implode(', ', $presentParts) : 'N/A';

        // Build Full Permanent Location
        $permParts = [];
        if (!empty($profile['permanent_location'])) {
            $permParts[] = trim((string)$profile['permanent_location']);
        }
        if (!empty($profile['permanent_upazila'])) {
            $pUpazila = trim((string)$profile['permanent_upazila']);
            $existing = implode(' ', $permParts);
            if (!str_contains(strtolower($existing), strtolower($pUpazila))) {
                $permParts[] = $pUpazila;
            }
        }
        if (!empty($profile['permanent_district'])) {
            $pDist = trim((string)$profile['permanent_district']);
            $existing = implode(' ', $permParts);
            if (!str_contains(strtolower($existing), strtolower($pDist))) {
                $permParts[] = $pDist;
            }
        }
        $permLocation = !empty($permParts) ? implode(', ', $permParts) : 'N/A';

        $verificationUrl = url('/directory/' . ($profile['id'] ?? $profile['user_id']));

        // Resolve Photo Base64
        $rawPhoto = !empty($profile['avatar']) ? $profile['avatar'] : (!empty($profile['user_avatar']) ? $profile['user_avatar'] : '');
        $photoBase64 = $this->resolveImageBase64($rawPhoto, 'avatars');

        // Resolve Logo Base64
        $logoBase64 = $this->resolveFileBase64(public_path('images/LOGO.png'), 'image/png');

        // Resolve QR Code Base64
        $qrBase64 = $this->resolveQrCodeBase64($verificationUrl);

        return [
            'id'               => (int)$profile['id'],
            'user_id'          => (int)$profile['user_id'],
            'name'             => (string)$profile['name'],
            'email'            => (string)$profile['email'],
            'member_no'        => $memberNo,
            'degree'           => $degree,
            'batch'            => $batch,
            'phone'            => $phone,
            'nid_number'       => $nidNumber,
            'blood_group'      => $bloodGroup,
            'issue_date'       => $issueDate,
            'present_location' => $presentLocation,
            'perm_location'    => $permLocation,
            'member_title'     => $memberTitle,
            'verification_url' => $verificationUrl,
            'photo_base64'     => $photoBase64,
            'logo_base64'      => $logoBase64,
            'qr_base64'        => $qrBase64,
            'initials'         => initials($profile['name'] ?? 'A'),
        ];
    }

    /**
     * Render the Front side of the ID Card in high-resolution vector SVG (CR80 Standard).
     */
    public function renderFrontSvg(array $data): string
    {
        $name        = $this->xmlEscape($data['name']);
        $memberTitle = $this->xmlEscape($data['member_title']);
        $memberNo    = $this->xmlEscape($data['member_no']);
        $batch       = $this->xmlEscape((string)$data['batch']);
        $degree      = $this->xmlEscape($this->truncateString($data['degree'], 44));
        $bloodGroup  = $this->xmlEscape($data['blood_group']);
        $issueDate   = $this->xmlEscape($data['issue_date']);

        $logoHref    = $data['logo_base64'] ?? '';
        $qrHref      = $data['qr_base64'] ?? '';
        $photoHref   = $data['photo_base64'] ?? '';
        $initials    = $this->xmlEscape($data['initials']);

        $photoElement = '';
        if (!empty($photoHref)) {
            $photoElement = '<image href="' . $photoHref . '" x="40" y="178" width="160" height="192" preserveAspectRatio="xMidYMid slice" clip-path="url(#photo-clip)"/>';
        } else {
            $photoElement = '
                <rect x="40" y="178" width="160" height="192" rx="32" ry="32" fill="url(#initials-grad)"/>
                <text x="120" y="290" font-family="system-ui, -apple-system, sans-serif" font-size="52" font-weight="bold" fill="#FFFFFF" text-anchor="middle">' . $initials . '</text>';
        }

        return <<<SVG
<?xml version="1.0" encoding="UTF-8"?>
<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 840 520" width="85.6mm" height="53.98mm" style="background:transparent;">
  <defs>
    <!-- Background Gradient -->
    <linearGradient id="front-bg" x1="0%" y1="0%" x2="100%" y2="100%">
      <stop offset="0%" stop-color="#0F172A"/>
      <stop offset="50%" stop-color="#1E1B4B"/>
      <stop offset="100%" stop-color="#800020"/>
    </linearGradient>

    <!-- Photo Fallback Gradient -->
    <linearGradient id="initials-grad" x1="0%" y1="0%" x2="100%" y2="100%">
      <stop offset="0%" stop-color="#800020"/>
      <stop offset="100%" stop-color="#2F8863"/>
    </linearGradient>

    <!-- Ambient Glow Orbs -->
    <radialGradient id="ambient-white" cx="50%" cy="50%" r="50%">
      <stop offset="0%" stop-color="#FFFFFF" stop-opacity="0.10"/>
      <stop offset="100%" stop-color="#FFFFFF" stop-opacity="0"/>
    </radialGradient>
    <radialGradient id="ambient-emerald" cx="50%" cy="50%" r="50%">
      <stop offset="0%" stop-color="#2F8863" stop-opacity="0.30"/>
      <stop offset="100%" stop-color="#2F8863" stop-opacity="0"/>
    </radialGradient>

    <!-- Card Outer Clip -->
    <clipPath id="card-clip">
      <rect width="840" height="520" rx="48" ry="48"/>
    </clipPath>

    <!-- Photo Clip -->
    <clipPath id="photo-clip">
      <rect x="40" y="178" width="160" height="192" rx="32" ry="32"/>
    </clipPath>
  </defs>

  <!-- CARD MAIN CONTAINER -->
  <g clip-path="url(#card-clip)">
    <!-- Base Gradient Background -->
    <rect width="840" height="520" fill="url(#front-bg)"/>

    <!-- Ambient Lighting Glows -->
    <circle cx="800" cy="20" r="220" fill="url(#ambient-white)"/>
    <circle cx="40" cy="500" r="220" fill="url(#ambient-emerald)"/>

    <!-- Card Outer Border -->
    <rect width="840" height="520" rx="48" ry="48" fill="none" stroke="#FFFFFF" stroke-opacity="0.25" stroke-width="3"/>

    <!-- ── 1. HEADER SECTION ───────────────────────────── -->
    <!-- Organization Logo -->
    <image href="{$logoHref}" x="40" y="40" width="64" height="64" preserveAspectRatio="xMidYMid meet"/>

    <!-- Organization Bengali Title -->
    <text x="122" y="66" font-family="'Kalpurush', 'SolaimanLipi', 'Segoe UI', Arial, sans-serif" font-size="22" font-weight="bold" fill="#FFFFFF">ইন্সটিটিউট অব পাবলিক হেলথ এলামনাই অ্যাসোসিয়েশন</text>

    <!-- Organization English Subtitle -->
    <text x="122" y="90" font-family="'SFMono-Regular', Consolas, 'Liberation Mono', Menlo, monospace" font-size="14" font-weight="bold" fill="#FDA4AF" letter-spacing="1.2">INSTITUTE OF PUBLIC HEALTH ALUMNI ASSOCIATION</text>

    <!-- Verified Pill Badge -->
    <g transform="translate(674, 46)">
      <rect width="126" height="32" rx="16" ry="16" fill="#10B981" fill-opacity="0.2" stroke="#10B981" stroke-opacity="0.3" stroke-width="1.5"/>
      <circle cx="18" cy="16" r="4.5" fill="#34D399"/>
      <text x="32" y="21" font-family="'SFMono-Regular', Consolas, Menlo, monospace" font-size="15" font-weight="bold" fill="#6EE7B7" letter-spacing="1">VERIFIED</text>
    </g>

    <!-- Sub-header Divider -->
    <line x1="40" y1="116" x2="800" y2="116" stroke="#FFFFFF" stroke-opacity="0.08" stroke-width="1.5"/>

    <!-- Membership Card Title -->
    <text x="40" y="138" font-family="'SFMono-Regular', Consolas, Menlo, monospace" font-size="16" font-weight="bold" fill="#FCD34D" letter-spacing="1.5">IPH ALUMNI ASSOCIATION MEMBERSHIP CARD</text>

    <!-- Header Bottom Divider Line -->
    <line x1="40" y1="152" x2="800" y2="152" stroke="#FFFFFF" stroke-opacity="0.12" stroke-width="1.5"/>

    <!-- ── 2. CARD BODY DETAILS ────────────────────────── -->
    <!-- Member Photo Frame -->
    <rect x="40" y="178" width="160" height="192" rx="32" ry="32" fill="#0F172A" stroke="#FFFFFF" stroke-opacity="0.3" stroke-width="3"/>
    {$photoElement}

    <!-- Member Text Info -->
    <!-- Member Name -->
    <text x="224" y="210" font-family="system-ui, -apple-system, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif" font-size="30" font-weight="bold" fill="#FFFFFF">{$name}</text>

    <!-- Dynamic Position Title -->
    <text x="224" y="238" font-family="system-ui, -apple-system, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif" font-size="20" font-weight="600" fill="#FECDD3">{$memberTitle}</text>

    <!-- Metadata Grid -->
    <g font-family="'SFMono-Regular', Consolas, 'Liberation Mono', Menlo, monospace" font-size="19">
      <!-- Row 1: ID NO & BATCH -->
      <text x="224" y="280" fill="#94A3B8">ID NO: <tspan font-weight="bold" fill="#FFFFFF">{$memberNo}</tspan></text>
      <text x="515" y="280" fill="#94A3B8">BATCH: <tspan font-weight="bold" fill="#FCD34D">{$batch}</tspan></text>

      <!-- Row 2: DEGREE -->
      <text x="224" y="315" fill="#94A3B8">DEGREE: <tspan fill="#F1F5F9">{$degree}</tspan></text>

      <!-- Row 3: BLOOD & ISSUE DATE -->
      <text x="224" y="350" fill="#94A3B8">BLOOD: <tspan font-weight="bold" fill="#FB7185">{$bloodGroup}</tspan></text>
      <text x="515" y="350" fill="#94A3B8">ISSUE: <tspan fill="#E2E8F0">{$issueDate}</tspan></text>
    </g>

    <!-- ── 3. CARD FOOTER ──────────────────────────────── -->
    <!-- Footer Divider Line -->
    <line x1="40" y1="396" x2="800" y2="396" stroke="#FFFFFF" stroke-opacity="0.12" stroke-width="1.5"/>

    <!-- Left Footer Text -->
    <circle cx="46" cy="434" r="3.5" fill="#34D399"/>
    <text x="58" y="439" font-family="'SFMono-Regular', Consolas, Menlo, monospace" font-size="16" font-weight="bold" fill="#FCD34D" letter-spacing="1">ISSUED BY IPH ALUMNI ASSOCIATION</text>
    <text x="40" y="470" font-family="system-ui, -apple-system, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif" font-size="18" fill="#CBD5E1">Mohakhali, Dhaka-1212, Bangladesh</text>

    <!-- QR Code Container on Right -->
    <g transform="translate(704, 408)">
      <!-- Amber Outer Ring -->
      <rect x="-3" y="-3" width="94" height="94" rx="22" ry="22" fill="none" stroke="#FCD34D" stroke-opacity="0.3" stroke-width="2.5"/>
      <!-- White Frame -->
      <rect width="88" height="88" rx="18" ry="18" fill="#FFFFFF" stroke="#FFFFFF" stroke-opacity="0.9" stroke-width="1.5"/>
      <!-- QR Image inside -->
      <image href="{$qrHref}" x="6" y="6" width="76" height="76" preserveAspectRatio="xMidYMid meet"/>
      <!-- SCAN TO VERIFY Caption -->
      <text x="44" y="104" font-family="'SFMono-Regular', Consolas, Menlo, monospace" font-size="12" font-weight="bold" fill="#FCD34D" text-anchor="middle" letter-spacing="1.5">SCAN TO VERIFY</text>
    </g>
  </g>
</svg>
SVG;
    }

    /**
     * Render the Back side of the ID Card in high-resolution vector SVG (CR80 Standard).
     */
    public function renderBackSvg(array $data): string
    {
        $nidNumber       = $this->xmlEscape($data['nid_number']);
        $phone           = $this->xmlEscape($data['phone']);
        $email           = $this->xmlEscape($data['email']);

        $presLen         = mb_strlen($data['present_location'] ?? '');
        $permLen         = mb_strlen($data['perm_location'] ?? '');
        $presFontSize    = $presLen > 42 ? '15' : ($presLen > 28 ? '17' : '19');
        $permFontSize    = $permLen > 42 ? '15' : ($permLen > 28 ? '17' : '19');

        $presentLocation = $this->xmlEscape($this->truncateString($data['present_location'], 65));
        $permLocation    = $this->xmlEscape($this->truncateString($data['perm_location'], 65));

        return <<<SVG
<?xml version="1.0" encoding="UTF-8"?>
<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 840 520" width="85.6mm" height="53.98mm" style="background:transparent;">
  <defs>
    <!-- Background Gradient -->
    <linearGradient id="back-bg" x1="0%" y1="0%" x2="100%" y2="100%">
      <stop offset="0%" stop-color="#1E1B4B"/>
      <stop offset="50%" stop-color="#0F172A"/>
      <stop offset="100%" stop-color="#164E63"/>
    </linearGradient>

    <!-- Card Outer Clip -->
    <clipPath id="back-card-clip">
      <rect width="840" height="520" rx="48" ry="48"/>
    </clipPath>
  </defs>

  <g clip-path="url(#back-card-clip)">
    <!-- Base Gradient Background -->
    <rect width="840" height="520" fill="url(#back-bg)"/>

    <!-- Card Outer Border -->
    <rect width="840" height="520" rx="48" ry="48" fill="none" stroke="#FFFFFF" stroke-opacity="0.25" stroke-width="3"/>

    <!-- ── TOP BAR ─────────────────────────────────────── -->
    <text x="40" y="66" font-family="'SFMono-Regular', Consolas, Menlo, monospace" font-size="19" font-weight="bold" fill="#FCD34D" letter-spacing="1.5">MEMBER ADDITIONAL INFORMATION</text>
    <line x1="40" y1="80" x2="800" y2="80" stroke="#FFFFFF" stroke-opacity="0.12" stroke-width="1.5"/>

    <!-- ── TWO TOP DATA BOXES (NID & PHONE) ───────────── -->
    <!-- Box 1: NID -->
    <g transform="translate(40, 98)">
      <rect width="372" height="76" rx="20" ry="20" fill="#FFFFFF" fill-opacity="0.05" stroke="#FFFFFF" stroke-opacity="0.1" stroke-width="1.5"/>
      <text x="20" y="28" font-family="'SFMono-Regular', Consolas, Menlo, monospace" font-size="16" fill="#94A3B8" letter-spacing="1">NID NUMBER</text>
      <text x="20" y="58" font-family="'SFMono-Regular', Consolas, Menlo, monospace" font-size="21" font-weight="bold" fill="#FFFFFF">{$nidNumber}</text>
    </g>

    <!-- Box 2: PHONE -->
    <g transform="translate(428, 98)">
      <rect width="372" height="76" rx="20" ry="20" fill="#FFFFFF" fill-opacity="0.05" stroke="#FFFFFF" stroke-opacity="0.1" stroke-width="1.5"/>
      <text x="20" y="28" font-family="'SFMono-Regular', Consolas, Menlo, monospace" font-size="16" fill="#94A3B8" letter-spacing="1">PHONE NUMBER</text>
      <text x="20" y="58" font-family="'SFMono-Regular', Consolas, Menlo, monospace" font-size="21" font-weight="bold" fill="#FFFFFF">{$phone}</text>
    </g>

    <!-- ── MIDDLE CONTAINER (EMAIL & LOCATIONS) ───────── -->
    <g transform="translate(40, 190)">
      <rect width="760" height="156" rx="20" ry="20" fill="#FFFFFF" fill-opacity="0.05" stroke="#FFFFFF" stroke-opacity="0.1" stroke-width="1.5"/>

      <!-- EMAIL -->
      <text x="22" y="34" font-family="'SFMono-Regular', Consolas, Menlo, monospace" font-size="17" fill="#94A3B8">EMAIL:</text>
      <text x="738" y="34" font-family="'SFMono-Regular', Consolas, Menlo, monospace" font-size="18" font-weight="bold" fill="#FFFFFF" text-anchor="end">{$email}</text>
      <line x1="22" y1="48" x2="738" y2="48" stroke="#FFFFFF" stroke-opacity="0.06" stroke-width="1"/>

      <!-- PRESENT ADDRESS -->
      <text x="22" y="82" font-family="'SFMono-Regular', Consolas, Menlo, monospace" font-size="17" fill="#94A3B8">PRESENT ADDRESS:</text>
      <text x="738" y="82" font-family="system-ui, -apple-system, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif" font-size="{$presFontSize}" font-weight="bold" fill="#FFFFFF" text-anchor="end">{$presentLocation}</text>
      <line x1="22" y1="96" x2="738" y2="96" stroke="#FFFFFF" stroke-opacity="0.06" stroke-width="1"/>

      <!-- PERMANENT ADDRESS -->
      <text x="22" y="130" font-family="'SFMono-Regular', Consolas, Menlo, monospace" font-size="17" fill="#94A3B8">PERMANENT ADDRESS:</text>
      <text x="738" y="130" font-family="system-ui, -apple-system, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif" font-size="{$permFontSize}" font-weight="bold" fill="#FFFFFF" text-anchor="end">{$permLocation}</text>
    </g>

    <!-- ── PROPERTY & RETURN NOTICE ───────────────────── -->
    <text x="420" y="378" font-family="system-ui, -apple-system, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif" font-size="17" fill="#94A3B8" text-anchor="middle">This digital ID card is the official property of <tspan font-weight="bold" fill="#FFFFFF">IPH Alumni Association</tspan>.</text>
    <text x="420" y="404" font-family="system-ui, -apple-system, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif" font-size="17" fill="#94A3B8" text-anchor="middle">If found, please return to Institute of Public Health, Mohakhali, Dhaka-1212.</text>

    <!-- ── CARD BACK FOOTER ────────────────────────────── -->
    <line x1="40" y1="436" x2="800" y2="436" stroke="#FFFFFF" stroke-opacity="0.12" stroke-width="1.5"/>

    <!-- Website -->
    <text x="40" y="466" font-family="'SFMono-Regular', Consolas, Menlo, monospace" font-size="15" fill="#94A3B8" letter-spacing="1">OFFICIAL WEBSITE</text>
    <text x="40" y="494" font-family="system-ui, -apple-system, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif" font-size="19" font-weight="bold" fill="#FDA4AF">www.iphalumni.org</text>

    <!-- Contact & Support -->
    <text x="800" y="466" font-family="'SFMono-Regular', Consolas, Menlo, monospace" font-size="15" fill="#94A3B8" letter-spacing="1" text-anchor="end">CONTACT &amp; SUPPORT</text>
    <text x="800" y="494" font-family="'SFMono-Regular', Consolas, Menlo, monospace" font-size="18" fill="#E2E8F0" text-anchor="end">info@iphalumni.org</text>
  </g>
</svg>
SVG;
    }

    /**
     * Generate a ZIP archive of all given alumni profile cards, organized folder-wise.
     * Each member gets their own folder: e.g. "IPHAA-00001_Member_Name/" containing "front.svg" and "back.svg".
     */
    public function generateZipArchive(array $alumniProfileIds, ?string $zipFilePath = null): string
    {
        if ($zipFilePath === null) {
            $tempDir = storage_path('app/temp');
            if (!is_dir($tempDir)) {
                @mkdir($tempDir, 0755, true);
            }
            $zipFilePath = $tempDir . '/IPH_Alumni_Cards_SVG_' . date('Ymd_His') . '_' . uniqid() . '.zip';
        }

        $zip = new ZipArchive();
        if ($zip->open($zipFilePath, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
            throw new \RuntimeException("Unable to create zip file at: {$zipFilePath}");
        }

        // Add Print Specifications Readme
        $readme = "=========================================================================\n"
                . "    INSTITUTE OF PUBLIC HEALTH (IPH) ALUMNI ASSOCIATION ID CARDS        \n"
                . "=========================================================================\n\n"
                . "Specifications for Professional PVC Printing:\n"
                . "- Format: Scalable Vector Graphics (SVG)\n"
                . "- Card Standard: ISO/IEC 7810 ID-1 (CR80 Standard PVC Card)\n"
                . "- Physical Dimensions: 85.60 mm x 53.98 mm (3.375 in x 2.125 in)\n"
                . "- Aspect Ratio: 1.586 (Vector Scalable without resolution loss)\n"
                . "- Corner Radius: 3.18 mm (1/8 inch standard round corners)\n\n"
                . "Directory Structure:\n"
                . "Each alumni member has their own dedicated folder:\n"
                . "  [Member_ID]_[Member_Name]/\n"
                . "    |-- front.svg  (Front side membership card)\n"
                . "    \\-- back.svg   (Back side information card)\n\n"
                . "Generated on: " . date('Y-m-d H:i:s') . "\n"
                . "Official Website: https://iphalumni.dev.cv\n";
        $zip->addFromString('README_PRINTING_INSTRUCTIONS.txt', $readme);

        $processedCount = 0;
        foreach ($alumniProfileIds as $id) {
            $data = $this->getCardData((int)$id);
            if (!$data) continue;

            $slugName = preg_replace('/[^A-Za-z0-9_\-]/', '_', trim($data['name']));
            $slugName = preg_replace('/_+/', '_', (string)$slugName);
            $folderName = $data['member_no'] . '_' . $slugName;

            $frontSvg = $this->renderFrontSvg($data);
            $backSvg  = $this->renderBackSvg($data);

            $zip->addFromString("{$folderName}/front.svg", $frontSvg);
            $zip->addFromString("{$folderName}/back.svg", $backSvg);
            $processedCount++;
        }

        $zip->close();

        return $zipFilePath;
    }

    // ── Internal Helper Methods ──────────────────────────────────────────────

    private function xmlEscape(?string $value): string
    {
        return htmlspecialchars((string)($value ?? ''), ENT_XML1, 'UTF-8');
    }

    private function truncateString(string $str, int $limit): string
    {
        if (mb_strlen($str) <= $limit) return $str;
        return mb_substr($str, 0, $limit - 3) . '...';
    }

    private function resolveImageBase64(?string $filename, string $subDir): string
    {
        if (empty($filename)) return '';

        $possiblePaths = [
            public_path("storage/{$subDir}/{$filename}"),
            public_path("uploads/{$subDir}/{$filename}"),
            public_path("storage/{$filename}"),
            storage_path("app/public/{$subDir}/{$filename}"),
            storage_path("app/public/{$filename}"),
        ];

        foreach ($possiblePaths as $p) {
            if (file_exists($p) && is_file($p)) {
                $ext  = strtolower(pathinfo($p, PATHINFO_EXTENSION));
                $mime = ($ext === 'png') ? 'image/png' : (($ext === 'webp') ? 'image/webp' : 'image/jpeg');
                $data = @file_get_contents($p);
                if ($data !== false) {
                    return 'data:' . $mime . ';base64,' . base64_encode($data);
                }
            }
        }

        // Check if full URL
        if (str_starts_with($filename, 'http://') || str_starts_with($filename, 'https://')) {
            return $filename;
        }

        return '';
    }

    private function resolveFileBase64(string $path, string $mime): string
    {
        if (file_exists($path) && is_file($path)) {
            $content = @file_get_contents($path);
            if ($content !== false) {
                return 'data:' . $mime . ';base64,' . base64_encode($content);
            }
        }
        return '';
    }

    private function resolveQrCodeBase64(string $url): string
    {
        $qrApiUrl = 'https://api.qrserver.com/v1/create-qr-code/?size=200x200&margin=0&data=' . urlencode($url);

        // Attempt quick fetch to make SVG self-contained
        $ctx = stream_context_create([
            'http' => ['timeout' => 3],
            'ssl'  => ['verify_peer' => false, 'verify_peer_name' => false]
        ]);
        $img = @file_get_contents($qrApiUrl, false, $ctx);
        if ($img !== false && !empty($img)) {
            return 'data:image/png;base64,' . base64_encode($img);
        }

        return $qrApiUrl;
    }
}
