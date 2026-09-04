<?php
/**
 * Admin Dashboard View
 * Variables: $totalAlumni, $pendingAlumni, $activeMembers, $totalEvents, $totalRevenue,
 *            $recentRegistrations, $pendingVerifications, $recentNews
 */
?>
<!-- Stats Grid -->
<div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4 mb-8">
  <?php
  $cards = [
    ['Total Alumni',   $totalAlumni,   '#A22638'],
    ['Pending Review', $pendingAlumni, '#ef4444'],
    ['Active Members', $activeMembers, '#4E9C81'],
    ['Events',         $totalEvents,   '#6366f1'],
    ['Membership Rev', '৳' . number_format($totalRevenue), '#A22638'],
    ['Donations',      '৳' . number_format($totalDonations), '#8b5cf6'],
  ];
  foreach ($cards as [$label, $val, $color]):
  ?>
  <div class="p-5 rounded-2xl" style="background:rgba(255,255,255,0.05);border:1px solid rgba(255,255,255,0.08);">
    <div class="font-serif text-[26px] font-semibold" style="color:<?= $color ?>"><?= $val ?></div>
    <div class="text-[12px] mt-1" style="color:rgba(255,255,255,0.45);"><?= $label ?></div>
  </div>
  <?php endforeach; ?>
</div>

<!-- Two columns -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

  <!-- Pending Verifications -->
  <div class="rounded-2xl overflow-hidden" style="background:rgba(255,255,255,0.04);border:1px solid rgba(255,255,255,0.08);">
    <div class="px-5 py-4 flex justify-between items-center border-b" style="border-color:rgba(255,255,255,0.08);">
      <h3 class="text-[14px] font-semibold text-white">Pending Verifications</h3>
      <a href="<?= url('/admin/alumni?status=pending') ?>" class="font-mono text-[11px] text-[#A22638] hover:underline">View all →</a>
    </div>
    <?php if (empty($pendingVerifications)): ?>
    <div class="p-5 text-[13px]" style="color:rgba(255,255,255,0.35);">No pending verifications. 🎉</div>
    <?php else: ?>
    <div class="divide-y" style="--tw-divide-opacity:0.06;border-color:rgba(255,255,255,0.06);">
      <?php foreach ($pendingVerifications as $p): ?>
      <div class="px-5 py-3.5 flex items-center justify-between gap-3">
        <div class="flex items-center gap-3 min-w-0">
          <div class="w-8 h-8 rounded-full flex items-center justify-center text-[11px] font-bold shrink-0"
               style="background:rgba(162,38,56,0.2);color:#A22638;">
            <?= initials($p['name']) ?>
          </div>
          <div class="min-w-0">
            <div class="text-[13px] font-medium text-white truncate"><?= e($p['name']) ?></div>
            <div class="text-[11.5px] truncate" style="color:rgba(255,255,255,0.4);"><?= e($p['email']) ?> · Batch <?= $p['batch_year'] ?></div>
          </div>
        </div>
        <a href="<?= url('/admin/alumni/' . $p['id']) ?>" class="shrink-0 px-3 py-1.5 rounded-lg text-[12px] font-medium transition-colors"
           style="background:rgba(162,38,56,0.15);border:1px solid rgba(162,38,56,0.3);color:#E58E97;">
          Review →
        </a>
      </div>
      <?php endforeach; ?>
    </div>
    <?php endif; ?>
  </div>

  <!-- Recent Registrations -->
  <div class="rounded-2xl overflow-hidden" style="background:rgba(255,255,255,0.04);border:1px solid rgba(255,255,255,0.08);">
    <div class="px-5 py-4 flex justify-between items-center border-b" style="border-color:rgba(255,255,255,0.08);">
      <h3 class="text-[14px] font-semibold text-white">Recent Registrations</h3>
      <a href="<?= url('/admin/alumni') ?>" class="font-mono text-[11px] text-[#A22638] hover:underline">View all →</a>
    </div>
    <div class="divide-y" style="border-color:rgba(255,255,255,0.06);">
      <?php foreach ($recentRegistrations as $r): ?>
      <div class="px-5 py-3 flex items-center justify-between gap-3">
        <div class="min-w-0">
          <div class="text-[13px] font-medium text-white truncate"><?= e($r['name']) ?></div>
          <div class="text-[11.5px]" style="color:rgba(255,255,255,0.4);">Batch <?= $r['batch_year'] ?> · <?= date('d M Y', strtotime($r['registered_at'])) ?></div>
        </div>
        <span class="shrink-0 px-2.5 py-1 rounded-full font-mono text-[10px] font-medium"
              style="background:<?= $r['status'] === 'approved' ? 'rgba(78,156,129,0.2)' : 'rgba(162,38,56,0.15)' ?>;color:<?= $r['status'] === 'approved' ? '#4E9C81' : '#A22638' ?>;">
          <?= strtoupper($r['status']) ?>
        </span>
      </div>
      <?php endforeach; ?>
    </div>
  </div>

  <!-- Recent News -->
  <div class="rounded-2xl overflow-hidden" style="background:rgba(255,255,255,0.04);border:1px solid rgba(255,255,255,0.08);">
    <div class="px-5 py-4 flex justify-between items-center border-b" style="border-color:rgba(255,255,255,0.08);">
      <h3 class="text-[14px] font-semibold text-white">Recent News</h3>
      <a href="<?= url('/admin/news/create') ?>" class="font-mono text-[11px] text-[#4E9C81] hover:underline">+ New Article</a>
    </div>
    <?php if (empty($recentNews)): ?>
    <div class="p-5 text-[13px]" style="color:rgba(255,255,255,0.35);">No news articles yet.</div>
    <?php else: ?>
    <div class="divide-y" style="border-color:rgba(255,255,255,0.06);">
      <?php foreach ($recentNews as $n): ?>
      <div class="px-5 py-3 flex items-center justify-between gap-3">
        <div class="min-w-0">
          <div class="text-[13px] font-medium text-white truncate"><?= e($n['title']) ?></div>
          <div class="text-[11px]" style="color:rgba(255,255,255,0.4);"><?= date('d M Y', strtotime($n['created_at'])) ?></div>
        </div>
        <span class="px-2.5 py-1 rounded-full font-mono text-[10px]"
              style="background:<?= $n['status'] === 'published' ? 'rgba(78,156,129,0.15)' : 'rgba(255,255,255,0.05)' ?>;color:<?= $n['status'] === 'published' ? '#4E9C81' : 'rgba(255,255,255,0.4)' ?>;">
          <?= strtoupper($n['status']) ?>
        </span>
      </div>
      <?php endforeach; ?>
    </div>
    <?php endif; ?>
  </div>

  <!-- Quick Links -->
  <div class="rounded-2xl p-5" style="background:rgba(255,255,255,0.04);border:1px solid rgba(255,255,255,0.08);">
    <h3 class="text-[14px] font-semibold text-white mb-4">Quick Actions</h3>
    <div class="grid grid-cols-2 gap-2">
      <?php
      $links = [
        ['/admin/alumni?status=pending', 'Verify Alumni',    '#A22638'],
        ['/admin/news/create',           'Write News',       '#4E9C81'],
        ['/admin/events/create',         'Add Event',        '#6366f1'],
        ['/admin/membership',            'Memberships',      '#A22638'],
        ['/admin/gallery',               'Gallery',          '#4E9C81'],
        ['/admin/settings',              'Settings',         'rgba(255,255,255,0.4)'],
      ];
      foreach ($links as [$path, $label, $color]):
      ?>
      <a href="<?= url($path) ?>" class="px-4 py-3 rounded-xl text-[13px] font-medium transition-all hover:-translate-y-0.5"
         style="background:rgba(255,255,255,0.04);border:1px solid rgba(255,255,255,0.08);color:<?= $color ?>;">
        <?= $label ?> →
      </a>
      <?php endforeach; ?>
    </div>
  </div>
</div>
