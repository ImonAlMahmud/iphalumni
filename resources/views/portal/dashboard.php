<?php
/**
 * Portal Dashboard View
 * Variables: $user, $profile, $membership, $upcomingEvents, $completion, $notifCount
 */
?>
<!-- Header -->
<div class="mb-8">
  <h1 class="font-serif text-[26px] font-semibold text-[#101820]">Welcome back, <?= e(explode(' ', $user['name'])[0] ?? 'Alumni') ?>.</h1>
  <p class="text-[14px] text-[#6B7178] mt-1">Here's an overview of your alumni profile and activity.</p>
</div>

<!-- Status Banner -->
<?php if (!$profile || ($profile['status'] ?? '') === 'pending'): ?>
<div class="mb-6 px-5 py-4 rounded-2xl flex items-start gap-4"
     style="background:rgba(128,0,32,0.08);border:1px solid rgba(128,0,32,0.25);">
  <svg class="w-5 h-5 text-[#800020] shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
  </svg>
  <div>
    <div class="text-[13.5px] font-semibold text-[#101820]">Profile under review</div>
    <div class="text-[13px] text-[#6B7178] mt-0.5">Your registration is being verified by the committee. You'll be notified once approved (usually within 48 hours).</div>
  </div>
</div>
<?php elseif (($profile['status'] ?? '') === 'approved'): ?>
<div class="mb-6 px-5 py-4 rounded-2xl flex items-center gap-4"
     style="background:rgba(47,136,99,0.08);border:1px solid rgba(47,136,99,0.25);">
  <svg class="w-5 h-5 text-[#2F8863] shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
  </svg>
  <div class="text-[13.5px] font-semibold text-[#101820]">Profile verified ✓ You're a verified IPH alumni.</div>
</div>
<?php endif; ?>

<!-- Quick Stats -->
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
  <?php
  $qStats = [
    ['Profile Complete', $completion . '%', '#800020'],
    ['Membership', ucfirst($membership['type_name'] ?? 'None'), '#2F8863'],
    ['Notifications', $notifCount, '#153548'],
    ['Status', ucfirst($profile['status'] ?? 'pending'), ($profile['status'] ?? '') === 'approved' ? '#2F8863' : '#800020'],
  ];
  foreach ($qStats as [$label, $val, $color]):
  ?>
  <div class="p-5 rounded-2xl" style="background:rgba(255,255,255,0.85);border:1px solid rgba(16,24,32,0.08);box-shadow:0 2px 12px -4px rgba(16,24,32,0.08);">
    <div class="font-serif text-[22px] font-semibold" style="color:<?= $color ?>"><?= e((string)$val) ?></div>
    <div class="text-[12px] text-[#6B7178] mt-1"><?= $label ?></div>
  </div>
  <?php endforeach; ?>
</div>

<!-- Profile Completion -->
<div class="mb-8 p-6 rounded-2xl" style="background:rgba(255,255,255,0.85);border:1px solid rgba(16,24,32,0.08);box-shadow:0 2px 12px -4px rgba(16,24,32,0.08);">
  <div class="flex items-center justify-between mb-3">
    <div>
      <div class="text-[14px] font-semibold text-[#101820]">Profile Completion</div>
      <div class="text-[12.5px] text-[#6B7178]">Complete your profile to appear in the directory</div>
    </div>
    <span class="font-serif text-[20px] font-semibold text-[#800020]"><?= $completion ?>%</span>
  </div>
  <div class="h-2 rounded-full overflow-hidden" style="background:rgba(16,24,32,0.08);">
    <div class="h-2 rounded-full transition-all duration-1000" style="width:<?= $completion ?>%;background:linear-gradient(90deg,#800020,#2F8863);"></div>
  </div>
  <div class="flex gap-3 mt-4 flex-wrap">
    <a href="<?= url('/portal/profile') ?>" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl text-[13px] font-medium text-[#101820] transition-all hover:-translate-y-0.5"
       style="background:rgba(255,255,255,0.9);border:1px solid rgba(16,24,32,0.1);">
      Edit Profile →
    </a>
    <?php if (!$membership || $membership['status'] !== 'active'): ?>
    <a href="<?= url('/portal/membership') ?>" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl text-[13px] font-semibold text-white transition-all hover:-translate-y-0.5"
       style="background:linear-gradient(135deg,#A22638,#800020);">
      Get Membership →
    </a>
    <?php else: ?>
    <a href="<?= url('/portal/membership/qr') ?>" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl text-[13px] font-medium text-[#101820] transition-all hover:-translate-y-0.5"
       style="background:rgba(255,255,255,0.9);border:1px solid rgba(16,24,32,0.1);">
      View QR ID →
    </a>
    <?php endif; ?>
  </div>
</div>

<!-- Two cols: upcoming events + quick actions -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
  
  <!-- Upcoming Events -->
  <div class="p-6 rounded-2xl" style="background:rgba(255,255,255,0.85);border:1px solid rgba(16,24,32,0.08);box-shadow:0 2px 12px -4px rgba(16,24,32,0.08);">
    <div class="flex items-center justify-between mb-4">
      <div class="text-[14px] font-semibold text-[#101820]">Upcoming Events</div>
      <a href="<?= url('/events') ?>" class="text-[12.5px] text-[#800020] hover:underline">View all</a>
    </div>
    <?php if (empty($upcomingEvents)): ?>
    <p class="text-[13px] text-[#6B7178]">No upcoming events. Check back soon!</p>
    <?php else: ?>
    <div class="space-y-3">
      <?php foreach ($upcomingEvents as $ev): ?>
      <div class="flex items-start gap-3 py-2.5 border-b last:border-0" style="border-color:rgba(16,24,32,0.06);">
        <div class="w-10 h-10 rounded-xl flex flex-col items-center justify-center shrink-0" style="background:rgba(128,0,32,0.1);border:1px solid rgba(128,0,32,0.2);">
          <span class="font-serif text-[14px] font-semibold text-[#800020] leading-none"><?= date('d', strtotime($ev['event_date'])) ?></span>
          <span class="font-mono text-[9px] text-[#6B7178] uppercase"><?= date('M', strtotime($ev['event_date'])) ?></span>
        </div>
        <div class="min-w-0">
          <div class="text-[13.5px] font-medium text-[#101820] truncate"><?= e($ev['title']) ?></div>
          <div class="text-[12px] text-[#6B7178]"><?= e($ev['venue'] ?? 'TBA') ?></div>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
    <?php endif; ?>
  </div>

  <!-- Quick Actions -->
  <div class="p-6 rounded-2xl" style="background:rgba(255,255,255,0.85);border:1px solid rgba(16,24,32,0.08);box-shadow:0 2px 12px -4px rgba(16,24,32,0.08);">
    <div class="text-[14px] font-semibold text-[#101820] mb-4">Quick Actions</div>
    <div class="space-y-2">
      <?php
      $actions = [
        ['/portal/profile',           'Update Profile',        'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z'],
        ['/portal/profile/education', 'Add Education',         'M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z'],
        ['/portal/profile/employment','Add Employment',        'M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z'],
        ['/portal/membership',        'View Membership',       'M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0'],
        ['/directory',                'Browse Directory',      'M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z'],
        ['/donate',                   'Make a Donation',       'M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z'],
      ];
      foreach ($actions as [$path, $label, $icon]):
      ?>
      <a href="<?= url($path) ?>" class="flex items-center gap-3 px-4 py-3 rounded-xl text-[13.5px] text-[#6B7178] hover:text-[#101820] hover:bg-[rgba(16,24,32,0.04)] transition-all group">
        <svg class="w-4 h-4 shrink-0 group-hover:text-[#800020] transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="<?= $icon ?>"/>
        </svg>
        <?= $label ?>
        <svg class="w-3.5 h-3.5 ml-auto opacity-0 group-hover:opacity-100 transition-opacity" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
        </svg>
      </a>
      <?php endforeach; ?>
    </div>
  </div>
</div>
