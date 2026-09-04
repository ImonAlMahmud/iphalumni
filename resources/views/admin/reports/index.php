<?php
/**
 * Admin Reports Panel View
 */
?>
<div class="mb-6">
  <h3 class="text-[16px] font-semibold text-white">Reports Panel</h3>
  <p class="text-[13px] text-white/50">Export association database logs and audit statements in CSV or view HTML reports.</p>
</div>

<div class="grid grid-cols-1 md:grid-cols-3 gap-6">
  <div class="p-6 rounded-3xl space-y-4" style="background:rgba(255,255,255,0.04);border:1px solid rgba(255,255,255,0.08);">
    <h4 class="font-serif text-[18px] font-semibold text-[#A22638]">Alumni Directory</h4>
    <p class="text-[12.5px] text-white/50">Get all registered and verified/pending alumni details with current contact information.</p>
    <div class="flex gap-2">
      <a href="<?= url('/admin/reports/alumni') ?>" class="px-4 py-2 rounded-xl text-[12px] bg-white/5 border border-white/10 hover:bg-white/10">View HTML</a>
      <a href="<?= url('/admin/reports/alumni?format=csv') ?>" class="px-4 py-2 rounded-xl text-[12px] bg-[#A22638] text-white font-semibold">Export CSV</a>
    </div>
  </div>

  <div class="p-6 rounded-3xl space-y-4" style="background:rgba(255,255,255,0.04);border:1px solid rgba(255,255,255,0.08);">
    <h4 class="font-serif text-[18px] font-semibold text-[#4E9C81]">Memberships Log</h4>
    <p class="text-[12.5px] text-white/50">Log of all active, pending, and expired memberships along with validity timelines.</p>
    <div class="flex gap-2">
      <a href="<?= url('/admin/reports/membership') ?>" class="px-4 py-2 rounded-xl text-[12px] bg-white/5 border border-white/10 hover:bg-white/10">View HTML</a>
      <a href="<?= url('/admin/reports/membership?format=csv') ?>" class="px-4 py-2 rounded-xl text-[12px] bg-[#4E9C81] text-white font-semibold">Export CSV</a>
    </div>
  </div>

  <div class="p-6 rounded-3xl space-y-4" style="background:rgba(255,255,255,0.04);border:1px solid rgba(255,255,255,0.08);">
    <h4 class="font-serif text-[18px] font-semibold text-[#8b5cf6]">Donations Strip</h4>
    <p class="text-[12.5px] text-white/50">Audit log of payments received for scholarships and general organization development fund.</p>
    <div class="flex gap-2">
      <a href="<?= url('/admin/reports/donations') ?>" class="px-4 py-2 rounded-xl text-[12px] bg-white/5 border border-white/10 hover:bg-white/10">View HTML</a>
    </div>
  </div>
</div>
