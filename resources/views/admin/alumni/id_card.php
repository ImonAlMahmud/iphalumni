<?php
/**
 * Admin Alumni ID Card View — Front & Back
 * Variables: $profile, $membership, $refData, $lastEdu
 */

$rawId = !empty($profile['id']) ? $profile['id'] : ($profile['user_id'] ?? 1);
$rawMemberNo = !empty($membership['membership_number']) ? $membership['membership_number'] : '';
$memberNo = (!empty($rawMemberNo) && str_starts_with($rawMemberNo, 'IPHAA-')) ? $rawMemberNo : ('IPHAA-' . str_pad((string)$rawId, 5, '0', STR_PAD_LEFT));

$latestDegree = '';
if (!empty($lastEdu->degree)) {
    $latestDegree = $lastEdu->degree;
    if (!empty($lastEdu->field_of_study)) {
        $latestDegree .= ' in ' . $lastEdu->field_of_study;
    }
}
$degree     = !empty($latestDegree) ? $latestDegree : (!empty($profile['degree']) ? $profile['degree'] : ($refData['department'] ?? 'Public Health Graduate'));
$batch      = !empty($refData['batch']) ? $refData['batch'] : (!empty($refData['session']) ? $refData['session'] : (!empty($profile['batch_year']) ? $profile['batch_year'] : 'N/A'));
$phone      = !empty($profile['phone']) ? $profile['phone'] : ($refData['mobile'] ?? 'N/A');
$nidNumber  = !empty($profile['nid_number']) ? $profile['nid_number'] : 'N/A';
$bloodGroup = !empty($profile['blood_group']) ? $profile['blood_group'] : 'N/A';
$issueDate  = !empty($profile['created_at']) ? date('d M Y', strtotime($profile['created_at'])) : date('d M Y');

$verificationUrl = url('/directory/' . $profile['id']);
$qrUrl = 'https://api.qrserver.com/v1/create-qr-code/?size=200x200&margin=0&data=' . urlencode($verificationUrl);

// Present Location
$presentParts = [];
if (($profile['location_type'] ?? 'bangladesh') === 'abroad') {
    if (!empty($profile['province_city'])) $presentParts[] = trim((string)$profile['province_city']);
    if (!empty($profile['country']) && strtolower(trim((string)$profile['country'])) !== 'bangladesh') {
        $presentParts[] = trim((string)$profile['country']);
    }
} else {
    if (!empty($profile['thana_upazila'])) $presentParts[] = trim((string)$profile['thana_upazila']);
    if (!empty($profile['current_location'])) $presentParts[] = trim((string)$profile['current_location']);
}
$fullPresentLocation = !empty($presentParts) ? implode(', ', $presentParts) : 'Mohakhali, Dhaka';
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

<div class="max-w-4xl mx-auto py-6 font-['Kalpurush','Inter',sans-serif]">

  <!-- Header -->
  <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8 no-print">
    <div>
      <div class="flex items-center gap-2 mb-1">
        <a href="<?= url('/admin/alumni/' . $profile['id']) ?>" class="text-[12px] font-mono text-white/50 hover:text-white transition-colors">
          <i class="fa-solid fa-arrow-left mr-1"></i> <?= e($profile['name']) ?>'s Profile
        </a>
        <span class="text-white/30 text-[10px]">/</span>
        <span class="text-[11px] font-mono font-bold text-sky-400 uppercase tracking-wider">
          MEMBER ALUMNI ID CARD
        </span>
      </div>
      <h1 class="font-serif text-[26px] font-bold text-white tracking-tight flex items-center gap-2.5">
        <i class="fa-solid fa-id-card text-sky-400"></i>
        <?= __('ডিজিটাল মেম্বার আইডি কার্ড', 'Digital Member ID Card') ?>: <?= e($profile['name']) ?>
      </h1>
      <p class="text-[13px] text-white/60 mt-0.5">
        অফিসিয়াল সদস্য আইডি কার্ডের ফ্রন্ট ও ব্যাক সাইড প্রিভিউ, ডাউনলোড ও প্রিন্ট অপশন।
      </p>
    </div>

    <div class="flex items-center gap-2.5">
      <a href="<?= url('/admin/alumni/' . $profile['id'] . '/membership-card') ?>" class="px-3.5 py-2 rounded-xl bg-emerald-500/20 hover:bg-emerald-500/30 text-emerald-300 border border-emerald-500/30 text-[12.5px] font-semibold transition-all flex items-center gap-1.5" title="View Membership Card / QR Pass">
        <i class="fa-solid fa-qrcode text-[12px]"></i> Membership Pass
      </a>
      <a href="<?= url('/admin/alumni/' . $profile['id'] . '/card-svg/zip') ?>" class="px-3.5 py-2 rounded-xl bg-amber-500/20 hover:bg-amber-500/30 text-amber-300 border border-amber-500/30 text-[12.5px] font-semibold transition-all flex items-center gap-1.5" title="Download SVG ZIP">
        <i class="fa-solid fa-download text-[11px]"></i> Download SVG
      </a>
      <button onclick="window.print()" class="px-4 py-2 rounded-xl bg-[#800020] hover:bg-[#990026] text-white text-[12.5px] font-bold transition-all shadow-md flex items-center gap-1.5">
        <i class="fa-solid fa-print text-[11px]"></i> Print Card
      </button>
    </div>
  </div>

  <!-- Physical ID Cards Container (Front & Back) -->
  <div class="flex flex-col md:flex-row items-center justify-center gap-8 mb-12" id="id-card-print-area">
    
    <!-- FRONT SIDE CARD -->
    <div class="id-card-item w-[420px] h-[260px] rounded-3xl p-5 text-white relative overflow-hidden shadow-2xl flex flex-col justify-between"
         style="background: linear-gradient(135deg, #0F172A 0%, #1E1B4B 50%, #800020 100%); border: 1.5px solid rgba(255,255,255,0.25);">
      
      <!-- Ambient Orbs -->
      <div class="absolute -top-12 -right-12 w-48 h-48 rounded-full bg-white/10 blur-2xl pointer-events-none"></div>
      <div class="absolute -bottom-12 -left-12 w-48 h-48 rounded-full bg-[#2F8863]/30 blur-2xl pointer-events-none"></div>

      <!-- Header -->
      <div class="relative z-10 border-b border-white/10 pb-2">
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

        <div class="mt-1.5 pt-1 border-t border-white/5 flex items-center justify-between">
          <div class="font-mono text-[9px] font-bold text-amber-300 tracking-wider uppercase">
            IPH Alumni Association Member Card
          </div>
        </div>
      </div>

      <!-- Card Body Details -->
      <div class="flex items-center gap-4 my-auto relative z-10 pt-1">
        <!-- Profile Photo -->
        <div class="w-20 h-24 rounded-2xl overflow-hidden border-2 border-white/30 shadow-md shrink-0 bg-slate-800">
          <?php 
          $rawPhoto = !empty($profile['avatar']) ? $profile['avatar'] : (!empty($profile['user_avatar']) ? $profile['user_avatar'] : '');
          ?>
          <?php if (!empty($rawPhoto)): ?>
            <img src="<?= asset('storage/avatars/' . e($rawPhoto)) ?>" alt="Photo" class="w-full h-full object-cover">
          <?php else: ?>
            <div class="w-full h-full flex items-center justify-center text-white font-bold text-[22px] bg-gradient-to-br from-[#800020] to-[#2F8863]">
              <?= initials($profile['name'] ?? 'A') ?>
            </div>
          <?php endif; ?>
        </div>

        <!-- Alumni Info Grid -->
        <div class="min-w-0 flex-1 space-y-0.5">
          <h3 class="font-bold text-[15.5px] text-white truncate leading-tight"><?= e($profile['name']) ?></h3>
          <div class="text-[10px] sm:text-[10.5px] font-semibold text-rose-200 leading-[1.25] line-clamp-2" style="display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;word-break:break-word;" title="<?= e($degree) ?>">
            <?= e($degree) ?>
          </div>
          
          <div class="grid grid-cols-2 gap-x-2 gap-y-0.5 font-mono text-[9.5px] text-slate-300 pt-0.5">
            <div><span class="text-slate-400">ID NO:</span> <span class="text-white font-bold"><?= e($memberNo) ?></span></div>
            <div><span class="text-slate-400">BATCH:</span> <span class="text-amber-300 font-bold"><?= e($batch) ?></span></div>
            <div class="col-span-2 truncate"><span class="text-slate-400">EMAIL:</span> <?= e($profile['email']) ?></div>
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

        <div class="p-2.5 rounded-xl bg-white/5 border border-white/10 space-y-1">
          <span class="text-slate-400 block font-mono text-[9px]">PRESENT ADDRESS</span>
          <div class="text-[11px] text-white leading-tight font-medium"><?= e($fullPresentLocation) ?></div>
        </div>

        <div class="text-[9.5px] text-slate-400 italic pt-1 leading-snug">
          * This card is official property of IPH Alumni Association. If found, please return to IPH Campus, Mohakhali, Dhaka-1212.
        </div>
      </div>

      <!-- Back Footer Signatures -->
      <div class="flex justify-between items-end border-t border-white/10 pt-2 font-mono text-[8.5px]">
        <div class="text-center">
          <div class="border-b border-white/40 pb-0.5 mb-0.5 text-white/80 font-serif italic">General Secretary</div>
          <span class="text-slate-400">Authorized Signature</span>
        </div>
        <div class="text-center">
          <div class="border-b border-white/40 pb-0.5 mb-0.5 text-white/80 font-serif italic">President</div>
          <span class="text-slate-400">IPH Alumni Association</span>
        </div>
      </div>
    </div>

  </div>

  <!-- Bottom Action Bar -->
  <div class="flex items-center justify-between p-4 rounded-2xl bg-white/5 border border-white/10 no-print">
    <a href="<?= url('/admin/alumni/' . $profile['id']) ?>" class="text-[13px] text-white/60 hover:text-white flex items-center gap-1.5 transition-colors">
      <i class="fa-solid fa-arrow-left text-[11px]"></i> Return to Alumni Profile
    </a>
    <a href="<?= url('/admin/alumni/' . $profile['id'] . '/edit') ?>" class="text-[13px] text-amber-300 hover:underline flex items-center gap-1.5 font-medium">
      <i class="fa-solid fa-user-pen"></i> Edit Member Info
    </a>
  </div>

</div>
