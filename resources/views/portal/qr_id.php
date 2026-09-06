<?php
/**
 * Alumni Portal QR ID Card View — Redesigned Executive Smart Pass
 * Variables: $user, $profile, $membership, $membershipType, $committeeMember, $refData, $lastEdu
 */
$verifyUrl = url('/verify/' . $membership['qr_code']);
$qrServerUrl = "https://api.qrserver.com/v1/create-qr-code/?size=240x240&margin=0&data=" . urlencode($verifyUrl);

$memberName    = $user['name'] ?? 'Alumni Member';
$memberBnName  = !empty($profile['name_bangla']) ? $profile['name_bangla'] : (!empty($refData['name_bangla']) ? $refData['name_bangla'] : '');
$memberAvatar  = !empty($profile['avatar']) ? asset('storage/avatars/' . e($profile['avatar'])) : (!empty($user['avatar']) ? asset('storage/avatars/' . e($user['avatar'])) : null);

$isCommittee   = !empty($committeeMember);
$committeePost = $isCommittee ? ($committeeMember->designation ?? 'Committee Member') : null;
$committeeType = $isCommittee ? (!empty($committeeMember->committee_name) ? $committeeMember->committee_name : (ucfirst($committeeMember->committee_type ?? 'Executive') . ' Committee')) : null;

$membershipName = !empty($membershipType->name) ? $membershipType->name : 'Active Member';
$memberNo       = !empty($membership['membership_number']) ? $membership['membership_number'] : ('IPHAA-' . str_pad((string)($profile['id'] ?? $user['id']), 5, '0', STR_PAD_LEFT));

$batch = !empty($refData['batch']) ? $refData['batch'] : (!empty($profile['batch_year']) ? $profile['batch_year'] : '—');
$roll  = !empty($refData['roll']) ? $refData['roll'] : (!empty($profile['student_id']) ? $profile['student_id'] : '');
$blood = !empty($profile['blood_group']) ? $profile['blood_group'] : '—';

$degree = '';
if (!empty($lastEdu->degree)) {
    $degree = $lastEdu->degree;
    if (!empty($lastEdu->field_of_study)) {
        $degree .= ' (' . $lastEdu->field_of_study . ')';
    }
} elseif (!empty($refData['department'])) {
    $degree = $refData['department'];
} elseif (!empty($profile['degree'])) {
    $degree = $profile['degree'];
} else {
    $degree = 'Public Health Graduate';
}

$issueDate = !empty($membership['start_date']) ? date('d M Y', strtotime($membership['start_date'])) : (!empty($membership['created_at']) ? date('d M Y', strtotime($membership['created_at'])) : date('d M Y'));
$validity  = !empty($membership['end_date']) ? date('d M Y', strtotime($membership['end_date'])) : 'Lifetime';
?>

<!-- Print Style Optimization -->
<style>
@media print {
  body * {
    visibility: hidden;
  }
  #printable-pass-container, #printable-pass-container * {
    visibility: visible;
  }
  #printable-pass-container {
    position: fixed;
    left: 50%;
    top: 50%;
    transform: translate(-50%, -50%);
    width: 380px !important;
    margin: 0 !important;
    padding: 0 !important;
    box-shadow: none !important;
    border: 1px solid #ddd !important;
    -webkit-print-color-adjust: exact !important;
    print-color-adjust: exact !important;
  }
  .no-print {
    display: none !important;
  }
}
</style>

<div class="max-w-xl mx-auto py-4 px-3 sm:px-0 font-['Kalpurush','Inter',sans-serif]" x-data="{ copied: false }">
  
  <!-- Navigation and Actions Bar -->
  <div class="mb-5 flex flex-wrap items-center justify-between gap-3 no-print">
    <a href="<?= url('/portal/membership') ?>" 
       class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-white border border-slate-200 text-slate-700 hover:text-slate-900 hover:bg-slate-50 text-[13px] font-medium shadow-sm transition-all">
      <i class="fa-solid fa-arrow-left text-[11px]"></i>
      <span><?= __('মেম্বারশিপ ড্যাশবোর্ড', 'Back to Membership') ?></span>
    </a>

    <div class="flex items-center gap-2">
      <!-- Copy Link Button -->
      <button type="button" 
              @click="navigator.clipboard.writeText('<?= $verifyUrl ?>'); copied = true; setTimeout(() => copied = false, 2500)"
              class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl bg-white border border-slate-200 text-slate-700 hover:text-[#800020] hover:border-[#800020]/30 text-[13px] font-medium shadow-sm transition-all cursor-pointer">
        <i class="fa-solid" :class="copied ? 'fa-check text-emerald-600' : 'fa-copy text-slate-400'"></i>
        <span x-text="copied ? 'Link Copied!' : 'Copy Verify URL'"></span>
      </button>

      <!-- Print Pass Button -->
      <button type="button" 
              onclick="window.print()" 
              class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-[#800020] hover:bg-[#990026] text-white text-[13px] font-semibold shadow-md shadow-[#800020]/20 transition-all cursor-pointer">
        <i class="fa-solid fa-print text-[12px]"></i>
        <span><?= __('কার্ড প্রিন্ট করুন', 'Print Pass') ?></span>
      </button>
    </div>
  </div>

  <!-- Main Executive Smart Pass Card -->
  <div id="printable-pass-container" 
       class="relative w-full max-w-[420px] mx-auto rounded-[32px] overflow-hidden text-white shadow-2xl border border-white/10"
       style="background: radial-gradient(circle at 50% 0%, #171d2b 0%, #0b0e14 70%, #06080c 100%); box-shadow: 0 30px 80px -15px rgba(0,0,0,0.85), 0 0 50px -10px rgba(128,0,32,0.25);">

    <!-- Top Decorative Signature Bar -->
    <div class="h-2.5 bg-gradient-to-r from-[#800020] via-amber-500 to-[#2F8863]"></div>

    <!-- Background Subtle Holographic Watermark -->
    <div class="absolute inset-0 pointer-events-none opacity-[0.03] overflow-hidden flex items-center justify-center">
      <i class="fa-solid fa-shield-halved text-[420px]"></i>
    </div>

    <!-- Card Content -->
    <div class="p-6 sm:p-7 space-y-5 relative z-10">

      <!-- Card Top Header: Organization & Chip -->
      <div class="flex items-center justify-between pb-3 border-b border-white/10">
        <div class="flex items-center gap-3">
          <img src="<?= asset('images/LOGO.png') ?>" alt="IPH Logo" class="w-10 h-10 object-contain drop-shadow-md">
          <div class="leading-tight">
            <span class="font-mono text-[9px] uppercase tracking-widest text-[#E58E97] font-semibold block">
              INSTITUTE OF PUBLIC HEALTH
            </span>
            <span class="font-serif text-[15px] font-bold tracking-tight text-white block">
              Alumni Association
            </span>
            <span class="text-[9.5px] text-white/50 block font-['Inter']">
              Dhaka, Bangladesh · Est. 2026
            </span>
          </div>
        </div>

        <!-- Electronic Contactless Chip Icon -->
        <div class="flex flex-col items-center justify-center opacity-80" title="Secure Smart Pass">
          <div class="w-8 h-6 rounded-md bg-gradient-to-br from-amber-200 via-amber-400 to-amber-600 p-[1.5px] shadow-sm">
            <div class="w-full h-full rounded-[4px] bg-black/40 grid grid-cols-2 gap-0.5 p-0.5">
              <div class="border-b border-r border-amber-300/40"></div>
              <div class="border-b border-amber-300/40"></div>
              <div class="border-r border-amber-300/40"></div>
              <div></div>
            </div>
          </div>
          <i class="fa-solid fa-wifi text-[9px] text-amber-300 rotate-90 mt-0.5"></i>
        </div>
      </div>

      <!-- User Portrait & Distinction Ribbon -->
      <div class="text-center pt-1">
        
        <!-- Avatar Ring -->
        <div class="relative w-28 h-28 mx-auto mb-3">
          <!-- Outer Ambient Glow -->
          <div class="absolute -inset-1.5 rounded-full <?= $isCommittee ? 'bg-gradient-to-r from-amber-400/50 via-[#800020]/60 to-amber-500/50 blur' : 'bg-gradient-to-r from-[#800020]/50 to-[#2F8863]/50 blur' ?> opacity-75"></div>
          
          <!-- Photo Ring -->
          <div class="relative w-full h-full rounded-full p-[3px] <?= $isCommittee ? 'bg-gradient-to-tr from-amber-300 via-amber-500 to-[#800020]' : 'bg-gradient-to-tr from-[#800020] via-emerald-400 to-[#2F8863]' ?> shadow-xl">
            <div class="w-full h-full rounded-full overflow-hidden bg-[#111722] border-2 border-black/40 flex items-center justify-center">
              <?php if (!empty($memberAvatar)): ?>
                <img src="<?= $memberAvatar ?>" alt="<?= e($memberName) ?>" class="w-full h-full object-cover">
              <?php else: ?>
                <div class="w-full h-full flex items-center justify-center font-serif text-[34px] font-bold text-white/90 bg-gradient-to-br from-[#800020] to-[#1a2332]">
                  <?= initials($memberName) ?>
                </div>
              <?php endif; ?>
            </div>
          </div>

          <!-- Medal Icon Badge for Committee Members -->
          <?php if ($isCommittee): ?>
          <div class="absolute -bottom-1 -right-1 w-8 h-8 rounded-full bg-gradient-to-br from-amber-300 via-amber-500 to-amber-700 p-0.5 shadow-lg flex items-center justify-center border-2 border-[#0B0E14]" title="<?= e($committeePost) ?>">
            <i class="fa-solid fa-crown text-[#800020] text-[13px]"></i>
          </div>
          <?php else: ?>
          <div class="absolute -bottom-1 -right-1 w-7 h-7 rounded-full bg-emerald-500 p-0.5 shadow-md flex items-center justify-center border-2 border-[#0B0E14]" title="Verified Active Member">
            <i class="fa-solid fa-check text-white text-[11px]"></i>
          </div>
          <?php endif; ?>
        </div>

        <!-- MEMBER NAME -->
        <h2 class="font-serif text-[21px] font-bold text-white tracking-wide leading-snug">
          <?= e($memberName) ?>
        </h2>
        <?php if (!empty($memberBnName)): ?>
        <p class="text-[13px] text-white/60 font-['Kalpurush'] mt-0.5">
          <?= e($memberBnName) ?>
        </p>
        <?php endif; ?>

        <!-- COMMITTEE POSITION BADGE OR REGULAR MEMBERSHIP BADGE -->
        <div class="mt-2.5">
          <?php if ($isCommittee): ?>
            <!-- Prestigious Committee Member Tag -->
            <div class="inline-flex flex-col items-center">
              <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-gradient-to-r from-amber-500/25 via-[#800020]/50 to-amber-500/25 border border-amber-400/50 text-amber-200 shadow-lg shadow-amber-950/40">
                <i class="fa-solid fa-crown text-amber-400 text-[12px] animate-pulse"></i>
                <span class="font-bold text-[13px] tracking-wide text-amber-100 uppercase">
                  <?= e($committeePost) ?>
                </span>
                <i class="fa-solid fa-star text-amber-400 text-[10px]"></i>
              </div>
              <span class="text-[10.5px] font-mono font-semibold text-amber-300/80 tracking-widest uppercase mt-1">
                <?= e($committeeType) ?>
              </span>
            </div>
          <?php else: ?>
            <!-- Regular Member Tag -->
            <div class="inline-flex items-center gap-1.5 px-3.5 py-1 rounded-full bg-emerald-500/15 border border-emerald-500/30 text-emerald-300">
              <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
              <span class="font-mono text-[11px] font-bold tracking-wider uppercase">
                <?= e($membershipName) ?>
              </span>
            </div>
            <span class="block text-[10.5px] font-mono text-white/40 tracking-widest uppercase mt-0.5">
              IPH Alumni Member
            </span>
          <?php endif; ?>
        </div>

      </div>

      <!-- Member Details Specifications Panel -->
      <div class="rounded-2xl p-3.5 bg-white/[0.03] border border-white/10 space-y-2.5 text-[12.5px] font-mono">
        
        <div class="grid grid-cols-2 gap-3 pb-2.5 border-b border-white/5">
          <div>
            <span class="text-[9.5px] uppercase tracking-wider text-white/40 block">MEMBER ID</span>
            <span class="text-white font-bold text-[13.5px] text-[#E58E97] tracking-wider"><?= e($memberNo) ?></span>
          </div>
          <div>
            <span class="text-[9.5px] uppercase tracking-wider text-white/40 block">BATCH & ROLL</span>
            <span class="text-white/90 font-medium">
              <?= e($batch) ?><?= !empty($roll) ? ' (Roll: ' . e($roll) . ')' : '' ?>
            </span>
          </div>
        </div>

        <div class="grid grid-cols-2 gap-3 pb-2.5 border-b border-white/5">
          <div>
            <span class="text-[9.5px] uppercase tracking-wider text-white/40 block">BLOOD GROUP</span>
            <span class="text-rose-400 font-bold tracking-wide">
              <i class="fa-solid fa-droplet text-[10px] mr-0.5"></i> <?= e($blood) ?>
            </span>
          </div>
          <div>
            <span class="text-[9.5px] uppercase tracking-wider text-white/40 block">CARD STATUS</span>
            <span class="text-emerald-400 font-semibold inline-flex items-center gap-1">
              <i class="fa-solid fa-circle-check text-[10px]"></i> Active & Verified
            </span>
          </div>
        </div>

        <div>
          <span class="text-[9.5px] uppercase tracking-wider text-white/40 block">DEGREE / DEPARTMENT</span>
          <span class="text-white/90 text-[11.5px] block font-sans font-medium leading-[1.3] line-clamp-2" style="display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;word-break:break-word;" title="<?= e($degree) ?>">
            <?= e($degree) ?>
          </span>
        </div>

      </div>

      <!-- QR Code Authentication Box -->
      <div class="text-center pt-1">
        <div class="p-3.5 rounded-2xl bg-white text-slate-900 mx-auto inline-block shadow-xl relative group">
          <img src="<?= $qrServerUrl ?>" 
               alt="Membership Verification QR Code" 
               class="w-36 h-36 mx-auto block object-contain">
          
          <!-- Fine corner guides for scanner aesthetic -->
          <div class="absolute top-1 left-1 w-3 h-3 border-t-2 border-l-2 border-[#800020]"></div>
          <div class="absolute top-1 right-1 w-3 h-3 border-t-2 border-r-2 border-[#800020]"></div>
          <div class="absolute bottom-1 left-1 w-3 h-3 border-b-2 border-l-2 border-[#800020]"></div>
          <div class="absolute bottom-1 right-1 w-3 h-3 border-b-2 border-r-2 border-[#800020]"></div>
        </div>

        <div class="mt-2.5">
          <div class="inline-flex items-center gap-1 text-[10.5px] font-mono text-emerald-400 tracking-wider uppercase font-semibold">
            <i class="fa-solid fa-shield-halved text-[10px]"></i>
            <span>Official Cryptographic Verification</span>
          </div>
          <p class="text-[10px] text-white/40 font-['Inter'] mt-0.5">
            Scan with any camera or scanner to verify official membership & position instantly.
          </p>
        </div>
      </div>

    </div>

    <!-- Card Bottom Strip / Tech Spec -->
    <div class="py-2.5 px-4 bg-black/50 border-t border-white/5 flex items-center justify-between text-[9.5px] font-mono text-white/30">
      <span>ISSUED: <?= e($issueDate) ?></span>
      <span class="text-[#E58E97]/70">VALIDITY: <?= e($validity) ?></span>
      <span>IPHAA PASS</span>
    </div>

  </div>

  <!-- Verification Link Direct Bar -->
  <div class="mt-6 text-center text-[12px] text-slate-500 no-print">
    <span class="block text-[11px] uppercase tracking-wider text-slate-400 font-mono mb-1">Direct Verification Web URL:</span>
    <a href="<?= $verifyUrl ?>" target="_blank" class="font-mono text-[#800020] hover:underline break-all bg-white px-3 py-1.5 rounded-xl border border-slate-200 inline-block shadow-sm">
      <?= $verifyUrl ?> <i class="fa-solid fa-arrow-up-right-from-square text-[10px] ml-1"></i>
    </a>
  </div>

</div>
