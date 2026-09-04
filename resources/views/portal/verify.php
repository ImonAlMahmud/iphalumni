<?php
/**
 * Public Membership QR Verification Page
 * Variables: $result (membership data or false)
 */
?>
<div class="max-w-md mx-auto py-16 px-6">
  
  <div class="text-center mb-8">
    <img src="<?= asset('images/LOGO.png') ?>" alt="Logo" class="w-12 h-12 mx-auto mb-3 object-contain">
    <h2 class="font-serif text-[22px] font-semibold text-gray-800">Membership Verification</h2>
    <p class="text-[13px] text-gray-500 mt-1">Institute of Public Health Alumni Association</p>
  </div>

  <?php if ($result && $result['status'] === 'active'): ?>
    
    <!-- Success: Verified -->
    <div class="p-8 rounded-3xl bg-white border border-emerald-100 shadow-xl shadow-emerald-500/5 text-center space-y-6">
      
      <!-- Verified Badge Icon -->
      <div class="w-16 h-16 bg-emerald-50 rounded-full flex items-center justify-center text-emerald-500 text-[26px] mx-auto">
        ✓
      </div>

      <div>
        <span class="px-3 py-1 rounded-full bg-emerald-100 text-emerald-700 text-[11px] font-mono font-semibold uppercase">
          Active Member
        </span>
        <h3 class="font-serif text-[19px] font-bold text-gray-800 mt-3"><?= e($result['member_name']) ?></h3>
        <p class="text-[12.5px] text-gray-400 mt-1 font-mono">ID: <?= e($result['membership_number']) ?></p>
      </div>

      <hr class="border-gray-100">

      <div class="grid grid-cols-2 gap-4 text-left text-[13px] font-mono">
        <div>
          <span class="text-gray-400 text-[10px] block">BATCH</span>
          <span class="text-gray-700 font-semibold"><?= $result['batch_year'] ? 'Batch ' . e($result['batch_year']) : '—' ?></span>
        </div>
        <div>
          <span class="text-gray-400 text-[10px] block">VERIFIED STATUS</span>
          <span class="text-emerald-600 font-semibold">VALID CARD</span>
        </div>
      </div>

      <p class="text-[11.5px] text-gray-400 leading-relaxed pt-2">
        This membership ID is officially registered and currently in active status.
      </p>

    </div>

  <?php else: ?>

    <!-- Error: Invalid Card -->
    <div class="p-8 rounded-3xl bg-white border border-red-100 shadow-xl shadow-red-500/5 text-center space-y-6">
      
      <div class="w-16 h-16 bg-red-50 rounded-full flex items-center justify-center text-red-500 text-[26px] mx-auto">
        ⚠
      </div>

      <div>
        <span class="px-3 py-1 rounded-full bg-red-100 text-red-700 text-[11px] font-mono font-semibold uppercase">
          Invalid Card
        </span>
        <h3 class="font-serif text-[18px] font-bold text-gray-800 mt-3">Verification Failed</h3>
        <p class="text-[12.5px] text-gray-400 mt-1">The scanned QR code is invalid, expired, or deactivated.</p>
      </div>

    </div>

  <?php endif; ?>

</div>
