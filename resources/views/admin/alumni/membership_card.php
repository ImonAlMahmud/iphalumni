<?php
/**
 * Admin Alumni Membership Card & QR Smart Pass View
 * Variables: $profile, $membership, $membershipType, $committeeMember, $refData, $lastEdu
 */

$hasMembership = !empty($membership);
$membershipStatus = strtolower($membership['status'] ?? 'none');

$verifyCode = !empty($membership['qr_code']) ? $membership['qr_code'] : ('IPHAA-VERIFY-' . ($profile['id'] ?? 1));
$verifyUrl = url('/verify/' . $verifyCode);
$qrServerUrl = "https://api.qrserver.com/v1/create-qr-code/?size=240x240&margin=0&data=" . urlencode($verifyUrl);

$memberName    = $profile['name'] ?? 'Alumni Member';
$memberAvatar  = !empty($profile['avatar']) ? asset('storage/avatars/' . e($profile['avatar'])) : (!empty($profile['user_avatar']) ? asset('storage/avatars/' . e($profile['user_avatar'])) : null);

$isCommittee   = !empty($committeeMember);
$committeePost = $isCommittee ? ($committeeMember->designation ?? 'Committee Member') : null;
$committeeType = $isCommittee ? (!empty($committeeMember->committee_name) ? $committeeMember->committee_name : (ucfirst($committeeMember->committee_type ?? 'Executive') . ' Committee')) : null;

$membershipName = !empty($membershipType->name) ? $membershipType->name : 'Active Member';
$memberNo       = !empty($membership['membership_number']) ? $membership['membership_number'] : ('IPHAA-' . str_pad((string)($profile['id'] ?? 1), 5, '0', STR_PAD_LEFT));

$batch = !empty($refData['batch']) ? $refData['batch'] : (!empty($profile['batch_year']) ? $profile['batch_year'] : '—');
$roll  = !empty($refData['roll']) ? $refData['roll'] : (!empty($profile['student_id']) ? $profile['student_id'] : '');
$blood = !empty($profile['blood_group']) ? $profile['blood_group'] : '—';

$degree = '';
if (!empty($lastEdu->degree)) {
    $degree = $lastEdu->degree;
    if (!empty($lastEdu->field_of_study)) {
        $degree .= ' in ' . $lastEdu->field_of_study;
    }
} elseif (!empty($profile['degree'])) {
    $degree = $profile['degree'];
} else {
    $degree = 'Public Health Graduate';
}

$issueDate = !empty($membership['start_date']) ? date('d M Y', strtotime($membership['start_date'])) : (!empty($profile['created_at']) ? date('d M Y', strtotime($profile['created_at'])) : date('d M Y'));
$validity  = !empty($membership['end_date']) ? date('d M Y', strtotime($membership['end_date'])) : 'Lifetime (আজীবন)';
?>
<style>
@media print {
  body * {
    visibility: hidden !important;
  }
  #printable-pass-container, #printable-pass-container * {
    visibility: visible !important;
  }
  #printable-pass-container {
    position: fixed !important;
    left: 50% !important;
    top: 50% !important;
    transform: translate(-50%, -50%) !important;
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

<div class="max-w-xl mx-auto py-6 px-3 sm:px-0 font-['Kalpurush','Inter',sans-serif]">

  <!-- Top Header Navigation & Action Bar -->
  <div class="mb-6 flex flex-wrap items-center justify-between gap-3 no-print">
    <div>
      <div class="flex items-center gap-2 mb-1">
        <a href="<?= url('/admin/alumni/' . $profile['id']) ?>" class="text-[12px] font-mono text-white/50 hover:text-white transition-colors">
          <i class="fa-solid fa-arrow-left mr-1"></i> <?= e($profile['name']) ?>'s Profile
        </a>
        <span class="text-white/30 text-[10px]">/</span>
        <span class="text-[11px] font-mono font-bold text-emerald-400 uppercase tracking-wider">
          MEMBERSHIP CARD & SMART PASS
        </span>
      </div>
      <h1 class="font-serif text-[24px] font-bold text-white tracking-tight flex items-center gap-2">
        <i class="fa-solid fa-qrcode text-emerald-400"></i>
        <?= __('ডিজিটাল মেম্বারশিপ স্মার্ট পাস', 'Digital Membership Smart Pass') ?>
      </h1>
    </div>

    <div class="flex items-center gap-2">
      <a href="<?= url('/admin/alumni/' . $profile['id'] . '/id-card') ?>" class="px-3 py-1.5 rounded-xl bg-sky-500/20 hover:bg-sky-500/30 text-sky-300 border border-sky-500/30 text-[12px] font-semibold transition-all flex items-center gap-1.5" title="View Member ID Card">
        <i class="fa-solid fa-id-card text-[11px]"></i> Member Card
      </a>
      <?php if ($hasMembership): ?>
      <button type="button" onclick="window.print()" class="px-3.5 py-1.5 rounded-xl bg-[#800020] hover:bg-[#990026] text-white text-[12px] font-bold transition-all shadow-md flex items-center gap-1.5">
        <i class="fa-solid fa-print text-[11px]"></i> Print Pass
      </button>
      <?php endif; ?>
    </div>
  </div>

  <?php if (!$hasMembership): ?>
  <!-- Notice if Alumni does NOT have an active membership -->
  <div class="p-8 rounded-3xl bg-white/5 border border-white/10 text-center space-y-4 mb-8">
    <div class="w-16 h-16 rounded-full bg-amber-500/20 border border-amber-500/30 text-amber-300 mx-auto flex items-center justify-center text-2xl">
      <i class="fa-solid fa-id-card-clip"></i>
    </div>
    <h3 class="font-serif text-[20px] font-bold text-white">মেম্বারশিপ সক্রিয় নেই</h3>
    <p class="text-[13px] text-white/60 max-w-md mx-auto leading-relaxed">
      <strong><?= e($profile['name']) ?></strong>-এর এখনো কোনো মেম্বারশিপ আবেদন বা সাবস্ক্রিপশন নেই। আপনি চাইলে সরাসরি অ্যাডমিন প্যানেল থেকে ওনাকে সম্মানসূচক (Honorary) আজীবন সদস্যপদ প্রদান করতে পারবেন।
    </p>

    <div class="pt-3 flex flex-wrap items-center justify-center gap-3">
      <form method="POST" action="<?= url('/admin/membership/grant-honorary') ?>" class="inline" onsubmit="return confirm('<?= e($profile['name']) ?>-কে সম্মানসূচক আজীবন মেম্বারশিপ প্রদান করতে চান?')">
        <?= csrf_field() ?>
        <input type="hidden" name="alumni_profile_id" value="<?= $profile['id'] ?>">
        <button type="submit" class="px-5 py-2.5 rounded-xl bg-gradient-to-r from-purple-700 to-indigo-700 hover:brightness-110 text-white font-bold text-[13px] shadow-lg shadow-purple-900/30 transition-all flex items-center gap-2">
          <i class="fa-solid fa-award"></i> Grant Honorary Membership
        </button>
      </form>
      <a href="<?= url('/admin/membership') ?>" class="px-5 py-2.5 rounded-xl bg-white/10 hover:bg-white/20 text-white font-medium text-[13px] transition-all">
        Membership Plans
      </a>
    </div>
  </div>

  <?php else: ?>

  <!-- Main Executive Smart Pass Card -->
  <div id="printable-pass-container" 
       class="relative w-full max-w-[420px] mx-auto rounded-[32px] overflow-hidden text-white shadow-2xl border border-white/15"
       style="background: radial-gradient(circle at 50% 0%, #171d2b 0%, #0b0e14 70%, #06080c 100%); box-shadow: 0 30px 80px -15px rgba(0,0,0,0.85), 0 0 50px -10px rgba(128,0,32,0.25);">

    <!-- Top Decorative Signature Bar -->
    <div class="h-2.5 bg-gradient-to-r from-[#800020] via-amber-500 to-[#2F8863]"></div>

    <!-- Background Watermark -->
    <div class="absolute inset-0 pointer-events-none opacity-[0.03] overflow-hidden flex items-center justify-center">
      <i class="fa-solid fa-shield-halved text-[420px]"></i>
    </div>

    <!-- Card Content -->
    <div class="p-6 sm:p-7 space-y-5 relative z-10">

      <!-- Top Header -->
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
          <div class="absolute -inset-1.5 rounded-full <?= $isCommittee ? 'bg-gradient-to-r from-amber-400/50 via-[#800020]/60 to-amber-500/50 blur' : 'bg-gradient-to-r from-[#800020]/50 to-[#2F8863]/50 blur' ?> opacity-75"></div>
          
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

          <?php if ($isCommittee): ?>
          <div class="absolute -bottom-1 -right-1 w-8 h-8 rounded-full bg-gradient-to-br from-amber-300 via-amber-500 to-amber-700 p-0.5 shadow-lg flex items-center justify-center border-2 border-[#0B0E14]" title="<?= e($committeePost) ?>">
            <i class="fa-solid fa-crown text-[#800020] text-[13px]"></i>
          </div>
          <?php else: ?>
          <div class="absolute -bottom-1 -right-1 w-7 h-7 rounded-full bg-emerald-500 p-0.5 shadow-md flex items-center justify-center border-2 border-[#0B0E14]" title="Verified Member">
            <i class="fa-solid fa-check text-white text-[11px]"></i>
          </div>
          <?php endif; ?>
        </div>

        <!-- MEMBER NAME -->
        <h2 class="font-serif text-[21px] font-bold text-white tracking-wide leading-snug">
          <?= e($memberName) ?>
        </h2>

        <!-- COMMITTEE BADGE OR MEMBERSHIP TIER BADGE -->
        <div class="mt-2.5">
          <?php if ($isCommittee): ?>
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

      <!-- Member Specifications Panel -->
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
            <span class="text-[9.5px] uppercase tracking-wider text-white/40 block">STATUS</span>
            <span class="text-emerald-400 font-semibold inline-flex items-center gap-1">
              <i class="fa-solid fa-circle-check text-[10px]"></i> <?= strtoupper($membershipStatus) ?>
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

      <!-- QR Code Box -->
      <div class="text-center pt-1">
        <div class="p-3.5 rounded-2xl bg-white text-slate-900 mx-auto inline-block shadow-xl relative group">
          <img src="<?= $qrServerUrl ?>" 
               alt="Membership Verification QR Code" 
               class="w-36 h-36 mx-auto block object-contain">
          
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
            Scan to verify official membership & committee post instantly.
          </p>
        </div>
      </div>

    </div>

    <!-- Bottom Strip -->
    <div class="py-2.5 px-4 bg-black/50 border-t border-white/5 flex items-center justify-between text-[9.5px] font-mono text-white/30">
      <span>ISSUED: <?= e($issueDate) ?></span>
      <span class="text-[#E58E97]/70">VALIDITY: <?= e($validity) ?></span>
      <span>IPHAA PASS</span>
    </div>

  </div>

  <!-- Direct URL -->
  <div class="mt-6 text-center text-[12px] text-white/50 no-print">
    <span class="block text-[11px] uppercase tracking-wider text-white/40 font-mono mb-1">Direct Verification Web URL:</span>
    <a href="<?= $verifyUrl ?>" target="_blank" class="font-mono text-[#E58E97] hover:underline break-all bg-white/5 px-3 py-1.5 rounded-xl border border-white/10 inline-block">
      <?= $verifyUrl ?> <i class="fa-solid fa-arrow-up-right-from-square text-[10px] ml-1"></i>
    </a>
  </div>

  <?php endif; ?>

  <!-- Return Link -->
  <div class="mt-6 flex items-center justify-between p-4 rounded-2xl bg-white/5 border border-white/10 no-print">
    <a href="<?= url('/admin/alumni/' . $profile['id']) ?>" class="text-[13px] text-white/60 hover:text-white flex items-center gap-1.5 transition-colors">
      ← Return to Alumni Profile
    </a>
    <a href="<?= url('/admin/alumni/' . $profile['id'] . '/edit') ?>" class="text-[13px] text-amber-300 hover:underline flex items-center gap-1.5 font-medium">
      <i class="fa-solid fa-user-pen"></i> Edit Member Info
    </a>
  </div>

</div>
