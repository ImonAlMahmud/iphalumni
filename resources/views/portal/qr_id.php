<?php
/**
 * Alumni Portal QR ID Card View
 * Variables: $user, $profile, $membership
 */
$verifyUrl = url('/verify/' . $membership['qr_code']);
$qrServerUrl = "https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=" . urlencode($verifyUrl);
?>
<div class="max-w-md mx-auto py-6">
  
  <div class="mb-6 text-center">
    <a href="<?= url('/portal/membership') ?>" class="text-[13px] text-gray-500 hover:text-gray-800 inline-flex items-center gap-1">
      ← Back to Membership
    </a>
  </div>

  <!-- Pocket ID Card Mockup -->
  <div class="w-full rounded-[30px] overflow-hidden bg-[#0A1118] text-white shadow-2xl relative border border-white/5"
       style="box-shadow: 0 30px 70px -15px rgba(10,17,24,0.4);">
    
    <!-- Top Decorative Header bar -->
    <div class="h-3 bg-gradient-to-r from-[#800020] to-[#2F8863]"></div>

    <div class="p-8 text-center space-y-6">
      
      <!-- Logo & Association Name -->
      <div class="flex items-center justify-center gap-2">
        <img src="<?= asset('images/LOGO.png') ?>" alt="Logo" class="w-7 h-7 object-contain">
        <div class="text-left leading-none">
          <span class="font-serif text-[13.5px] font-bold tracking-tight text-white block">IPH Alumni</span>
          <span class="text-[9px] font-mono text-white/40 tracking-wider block uppercase">Association</span>
        </div>
      </div>

      <!-- User Avatar / Portrait Frame -->
      <div class="relative w-28 h-28 mx-auto">
        <div class="absolute -inset-1 rounded-full bg-gradient-to-r from-[#800020]/50 to-[#2F8863]/50 blur opacity-60"></div>
        <div class="relative w-full h-full rounded-full overflow-hidden border-2 border-white/10 bg-[#121E2A]">
          <?php if (!empty($profile['avatar'])): ?>
          <img src="<?= asset('storage/avatars/' . e($profile['avatar'])) ?>" alt="Avatar" class="w-full h-full object-cover">
          <?php else: ?>
          <div class="w-full h-full flex items-center justify-center font-serif text-[36px] text-white/80">
            <?= initials($user['name']) ?>
          </div>
          <?php endif; ?>
        </div>
      </div>

      <!-- User Name & Title -->
      <div>
        <h4 class="font-serif text-[18px] font-semibold text-white tracking-wide"><?= e($user['name']) ?></h4>
        <p class="text-[11.5px] text-[#E58E97] font-mono tracking-widest uppercase mt-1">Alumni Member</p>
      </div>

      <!-- Card Details Divider -->
      <hr class="border-white/5">

      <!-- Details List -->
      <div class="grid grid-cols-2 gap-4 text-left text-[12.5px] font-mono px-2">
        <div>
          <span class="text-white/30 text-[10px] block">MEMBER ID</span>
          <span class="text-white/80 font-medium"><?= e($membership['membership_number']) ?></span>
        </div>
        <div>
          <span class="text-white/30 text-[10px] block">BATCH</span>
          <span class="text-white/80 font-medium"><?= $profile['batch_year'] ? 'Batch ' . e($profile['batch_year']) : '—' ?></span>
        </div>
      </div>

      <!-- QR Code Block for Verification -->
      <div class="p-4 rounded-2xl bg-white mx-auto inline-block">
        <img src="<?= $qrServerUrl ?>" alt="Verification QR Code" class="w-36 h-36">
      </div>

      <!-- Card Footer Helper Info -->
      <p class="text-[10px] text-white/30 leading-relaxed max-w-[240px] mx-auto">
        Scan this QR code at events or desk checkpoints to verify your membership status instantly.
      </p>

    </div>

    <!-- Bottom Tech spec strip -->
    <div class="py-2.5 bg-white/[0.02] border-t border-white/5 text-center font-mono text-[9px] text-white/20">
      IPH ALUMNI ASSOCIATION · SECURE QR ID
    </div>

  </div>

</div>
