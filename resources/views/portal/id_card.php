<?php
/**
 * Digital Alumni ID Card View — Member Portal
 */
$user = auth();
$pdo = \App\Services\Database::connection();

$stmt = $pdo->prepare("SELECT ap.*, u.avatar as user_avatar, m.status as member_status, m.membership_number 
                       FROM alumni_profiles ap 
                       JOIN users u ON u.id = ap.user_id 
                       LEFT JOIN memberships m ON m.alumni_profile_id = ap.id AND m.deleted_at IS NULL
                       WHERE ap.user_id = ? ORDER BY m.created_at DESC LIMIT 1");
$stmt->execute([$user['id']]);
$profile = $stmt->fetch();

// Fetch accurate student reference data if mapped
$refData = null;
if (!empty($profile['student_reference_id'])) {
    $refStmt = $pdo->prepare("SELECT * FROM students_reference WHERE id = ?");
    $refStmt->execute([$profile['student_reference_id']]);
    $refData = $refStmt->fetch();
}

// Fetch latest Academic Degree from alumni_education table by graduation_year
$eduStmt = $pdo->prepare("SELECT degree, field_of_study FROM alumni_education WHERE alumni_profile_id = ? ORDER BY CAST(graduation_year AS UNSIGNED) DESC, id DESC LIMIT 1");
$eduStmt->execute([$profile['id']]);
$lastEdu = $eduStmt->fetch();

$latestDegree = '';
if (!empty($lastEdu['degree'])) {
    $latestDegree = $lastEdu['degree'];
    if (!empty($lastEdu['field_of_study'])) {
        $latestDegree .= ' in ' . $lastEdu['field_of_study'];
    }
}

$rawId = !empty($profile['id']) ? $profile['id'] : $user['id'];
$memberNo = !empty($profile['membership_number']) ? $profile['membership_number'] : (!empty($membership['membership_number']) ? $membership['membership_number'] : ('IPHAA-' . str_pad((string)$rawId, 5, '0', STR_PAD_LEFT)));
$degree      = !empty($latestDegree) ? $latestDegree : (!empty($profile['degree']) ? $profile['degree'] : ($refData['department'] ?? 'Public Health Graduate'));
$batch       = !empty($refData['batch']) ? $refData['batch'] : (!empty($refData['session']) ? $refData['session'] : (!empty($profile['batch_year']) ? $profile['batch_year'] : 'N/A'));
$phone       = !empty($profile['phone']) ? $profile['phone'] : ($refData['mobile'] ?? 'N/A');
$nidNumber   = !empty($profile['nid_number']) ? $profile['nid_number'] : 'N/A';
$bloodGroup  = !empty($profile['blood_group']) ? $profile['blood_group'] : 'N/A';

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

// Dynamic Committee Position: If member is in an active committee, show position; otherwise 'IPH Alumni Member'
$cmStmt = $pdo->prepare("SELECT designation FROM committee_members WHERE user_id = ? AND is_active = 1 AND deleted_at IS NULL ORDER BY sort_order ASC, id ASC LIMIT 1");
$cmStmt->execute([$user['id']]);
$cmMember = $cmStmt->fetch();
$memberTitle = !empty($cmMember['designation']) ? $cmMember['designation'] : 'IPH Alumni Member';

$issueDate   = !empty($profile['created_at']) ? date('d M Y', strtotime($profile['created_at'])) : date('d M Y');

$verificationUrl = url('/directory/' . ($profile['id'] ?? $user['id']));
$qrUrl = 'https://api.qrserver.com/v1/create-qr-code/?size=200x200&margin=0&data=' . urlencode($verificationUrl);
?>
<style>
@page {
  size: A4 portrait;
  margin: 12mm auto;
}

@media print {
  html, body {
    width: 100% !important;
    height: auto !important;
    margin: 0 !important;
    padding: 0 !important;
    background: #ffffff !important;
    -webkit-print-color-adjust: exact !important;
    print-color-adjust: exact !important;
  }

  body * {
    visibility: hidden !important;
  }

  header, nav, aside, footer, .no-print, #flash-msg {
    display: none !important;
  }

  #id-card-print-area, #id-card-print-area * {
    visibility: visible !important;
  }

  #id-card-print-area {
    position: relative !important;
    display: flex !important;
    flex-direction: column !important;
    align-items: center !important;
    justify-content: center !important;
    gap: 20px !important;
    margin: 20px auto !important;
    padding: 0 !important;
    page-break-inside: avoid !important;
    break-inside: avoid !important;
    width: 100% !important;
    max-width: 420px !important;
    box-shadow: none !important;
  }

  .id-card-item {
    page-break-inside: avoid !important;
    break-inside: avoid !important;
    margin: 0 auto !important;
    width: 410px !important;
    height: 250px !important;
    box-shadow: 0 4px 14px rgba(0,0,0,0.15) !important;
    -webkit-print-color-adjust: exact !important;
    print-color-adjust: exact !important;
  }
}
</style>
<div class="max-w-4xl mx-auto py-6 font-['Kalpurush']">

  <!-- Header -->
  <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8 no-print">
    <div>
      <span class="font-mono text-[11px] font-bold text-[#800020] uppercase tracking-wider block mb-1">
        <i class="fa-solid fa-id-card mr-1"></i> OFFICIAL DIGITAL MEMBERSHIP CARD
      </span>
      <h1 class="font-serif text-[28px] font-bold text-[#101820]"><?= __('ডিজিটাল অ্যালামনাই আইডি কার্ড', 'Digital Alumni ID Card') ?></h1>
    </div>

    <div class="flex items-center gap-3">
      <button onclick="window.print()" class="px-5 py-2.5 rounded-xl bg-[#800020] text-white text-[13.5px] font-semibold hover:bg-[#66001a] transition-all shadow-md flex items-center gap-2">
        <i class="fa-solid fa-print"></i> <?= __('প্রিন্ট / সেভ করুন', 'Print / Save Card') ?>
      </button>
    </div>
  </div>

  <!-- Physical ID Cards Container (Front & Back) -->
  <div class="flex flex-col md:flex-row items-center justify-center gap-8 mb-12" id="id-card-print-area">
    
    <!-- FRONT SIDE CARD -->
    <div class="id-card-item w-[420px] h-[260px] rounded-3xl p-5 text-white relative overflow-hidden shadow-2xl flex flex-col justify-between"
         style="background: linear-gradient(135deg, #0F172A 0%, #1E1B4B 50%, #800020 100%); border: 1.5px solid rgba(255,255,255,0.25);">
      
      <!-- Background Ambient Orbs -->
      <div class="absolute -top-12 -right-12 w-48 h-48 rounded-full bg-white/10 blur-2xl pointer-events-none"></div>
      <div class="absolute -bottom-12 -left-12 w-48 h-48 rounded-full bg-[#2F8863]/30 blur-2xl pointer-events-none"></div>

      <!-- Header -->
      <div class="relative z-10 border-b border-white/10 pb-2">
        <!-- Organization & Logo Line -->
        <div class="flex justify-between items-center">
          <div class="flex items-center gap-2.5">
            <img src="<?= asset('images/LOGO.png') ?>" alt="Logo" class="w-8 h-8 object-contain filter drop-shadow-md">
            <div>
              <div class="font-bold text-[11px] leading-snug text-white tracking-normal">
                <?= e(__('ইন্সটিটিউট অব পাবলিক হেলথ এলামনাই অ্যাসোসিয়েশন', 'IPH Alumni Association')) ?>
              </div>
              <div class="font-mono text-[7px] text-rose-300 tracking-wider uppercase">INSTITUTE OF PUBLIC HEALTH ALUMNI ASSOCIATION</div>
            </div>
          </div>

          <span class="px-2 py-0.5 rounded-full bg-emerald-500/20 text-emerald-300 border border-emerald-500/30 font-mono text-[8px] font-bold uppercase tracking-wider flex items-center gap-1">
            <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span> VERIFIED
          </span>
        </div>

        <!-- Membership Card Title (After Logo & Title) -->
        <div class="mt-1.5 pt-1 border-t border-white/5 flex items-center justify-between">
          <div class="font-mono text-[9px] font-bold text-amber-300 tracking-wider uppercase">
            IPH Alumni Association Membership Card
          </div>
        </div>
      </div>

      <!-- Card Body Details -->
      <div class="flex items-center gap-4 my-auto relative z-10 pt-1">
        <!-- Profile Photo -->
        <div class="w-20 h-24 rounded-2xl overflow-hidden border-2 border-white/30 shadow-md shrink-0 bg-slate-800">
          <?php 
          $rawPhoto = !empty($profile['avatar']) ? $profile['avatar'] : (!empty($profile['user_avatar']) ? $profile['user_avatar'] : (!empty($user['avatar']) ? $user['avatar'] : ''));
          ?>
          <?php if (!empty($rawPhoto)): ?>
            <img src="<?= avatar_url($rawPhoto) ?>" alt="Photo" class="w-full h-full object-cover">
          <?php else: ?>
            <div class="w-full h-full flex items-center justify-center text-white font-bold text-[22px] bg-gradient-to-br from-[#800020] to-[#2F8863]">
              <?= initials($user['name'] ?? 'A') ?>
            </div>
          <?php endif; ?>
        </div>

        <!-- Alumni Info Grid -->
        <div class="min-w-0 flex-1 space-y-0.5">
          <h3 class="font-bold text-[15.5px] text-white truncate leading-tight"><?= e($user['name']) ?></h3>
          <div class="text-[10.5px] font-semibold text-rose-200 leading-[1.25] line-clamp-2" style="display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;word-break:break-word;"><?= e($memberTitle) ?></div>
          
          <div class="grid grid-cols-2 gap-x-2 gap-y-0.5 font-mono text-[9.5px] text-slate-300 pt-0.5">
            <div><span class="text-slate-400">ID NO:</span> <span class="text-white font-bold"><?= e($memberNo) ?></span></div>
            <div><span class="text-slate-400">BATCH:</span> <span class="text-amber-300 font-bold"><?= e($batch) ?></span></div>
            <div class="col-span-2 line-clamp-2" style="display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;word-break:break-word;" title="<?= e($degree) ?>"><span class="text-slate-400">DEGREE:</span> <?= e($degree) ?></div>
            <div><span class="text-slate-400">BLOOD:</span> <span class="text-rose-400 font-bold"><?= e($bloodGroup) ?></span></div>
            <div><span class="text-slate-400">ISSUE:</span> <?= e($issueDate) ?></div>
          </div>
        </div>
      </div>

      <!-- Footer -->
      <div class="flex justify-between items-end relative z-10 border-t border-white/10 pt-1.5">
        <div class="space-y-0.5 pb-0.5">
          <div class="font-mono text-[8.5px] text-amber-300 font-bold uppercase tracking-wider flex items-center gap-1.5">
            <span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span>
            ISSUED BY IPH ALUMNI ASSOCIATION
          </div>
          <div class="text-[9.5px] text-slate-300">Mohakhali, Dhaka-1212, Bangladesh</div>
        </div>

        <div class="flex flex-col items-center">
          <div class="w-12 h-12 rounded-xl bg-white p-1 shadow-lg border border-white/80 ring-2 ring-amber-400/25 shrink-0 flex items-center justify-center">
            <img src="<?= $qrUrl ?>" alt="QR Code" class="w-full h-full object-contain rounded-md">
          </div>
          <span class="font-mono text-[6.5px] text-amber-300 font-bold uppercase tracking-widest mt-1">SCAN TO VERIFY</span>
        </div>
      </div>
    </div>

    <!-- BACK SIDE CARD -->
    <div class="id-card-item w-[420px] h-[260px] rounded-3xl p-5 text-white relative overflow-hidden shadow-2xl flex flex-col justify-between"
         style="background: linear-gradient(135deg, #1E1B4B 0%, #0F172A 50%, #164E63 100%); border: 1.5px solid rgba(255,255,255,0.25);">
      
      <!-- Top Bar -->
      <div class="border-b border-white/10 pb-2 flex justify-between items-center">
        <div class="font-mono text-[10px] font-bold text-amber-300 tracking-wider uppercase">MEMBER ADDITIONAL INFORMATION</div>
      </div>

      <!-- Back Info Content -->
      <div class="space-y-2 my-auto text-[11px] text-slate-200">
        <div class="grid grid-cols-2 gap-2 font-mono text-[10.5px]">
          <div class="p-2 rounded-xl bg-white/5 border border-white/10">
            <span class="text-slate-400 block text-[9px]">NID NUMBER</span>
            <span class="font-bold text-white"><?= e($nidNumber) ?></span>
          </div>
          <div class="p-2 rounded-xl bg-white/5 border border-white/10">
            <span class="text-slate-400 block text-[9px]">PHONE NUMBER</span>
            <span class="font-bold text-white"><?= e($phone) ?></span>
          </div>
        </div>

        <div class="p-2.5 rounded-xl bg-white/5 border border-white/10 space-y-1.5">
          <div class="flex justify-between items-center text-[10px] gap-2">
            <span class="text-slate-400 shrink-0 font-mono text-[9px]">EMAIL:</span>
            <span class="font-bold text-white truncate max-w-[240px] text-right"><?= e($user['email']) ?></span>
          </div>
          <div class="flex justify-between items-start text-[10px] gap-2">
            <span class="text-slate-400 shrink-0 font-mono text-[9px] pt-0.5">PRESENT ADDRESS:</span>
            <span class="font-bold text-white text-right leading-tight max-w-[250px]"><?= e($presentLocation) ?></span>
          </div>
          <div class="flex justify-between items-start text-[10px] gap-2">
            <span class="text-slate-400 shrink-0 font-mono text-[9px] pt-0.5">PERMANENT ADDRESS:</span>
            <span class="font-bold text-white text-right leading-tight max-w-[250px]"><?= e($permLocation) ?></span>
          </div>
        </div>

        <p class="text-[9.5px] text-slate-400 leading-tight text-center pt-1">
          This digital ID card is the official property of <strong>IPH Alumni Association</strong>. If found, please return to Institute of Public Health, Mohakhali, Dhaka-1212.
        </p>
      </div>

      <!-- Card Back Footer -->
      <div class="flex justify-between items-center border-t border-white/10 pt-2">
        <div>
          <div class="font-mono text-[8px] text-slate-400 uppercase">OFFICIAL WEBSITE</div>
          <div class="text-[10px] text-rose-300 font-bold">www.iphalumni.org</div>
        </div>

        <div class="text-right">
          <div class="font-mono text-[8px] text-slate-400 uppercase">CONTACT & SUPPORT</div>
          <div class="text-[10px] text-slate-200 font-mono">info@iphalumni.org</div>
        </div>
      </div>
    </div>

  </div>

  <!-- Instruction Box -->
  <div class="p-6 rounded-2xl bg-white border border-slate-200/80 shadow-sm text-[14px] text-[#6B7178] leading-relaxed">
    <h4 class="font-bold text-[#101820] text-[15px] mb-2 flex items-center gap-2">
      <i class="fa-solid fa-circle-info text-[#800020]"></i> কার্ড নির্দেশিকা:
    </h4>
    <ul class="list-disc list-inside space-y-1">
      <li>আইডি কার্ডের QR কোড স্ক্যান করে যেকোনো সময় অ্যাসোসিয়েশন সার্ভার থেকে আপনার সদস্যপদ যাচাই করা যাবে।</li>
      <li>প্রিন্ট বা পিডিএফে সেভ করতে "প্রিন্ট / সেভ করুন" বাটনে ক্লিক করুন।</li>
    </ul>
  </div>

</div>
