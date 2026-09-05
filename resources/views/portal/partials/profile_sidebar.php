<?php
/**
 * Shared Profile Sidebar (User Name Card + Signature + Quick Nav)
 * Variables available: $user, $profile, $activeTab (optional)
 */
$currentUri = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);
$activeTab = $activeTab ?? '';
if (empty($activeTab)) {
    if (str_contains($currentUri, '/portal/profile/education')) {
        $activeTab = 'education';
    } elseif (str_contains($currentUri, '/portal/profile/employment')) {
        $activeTab = 'employment';
    } elseif (str_contains($currentUri, '/portal/settings')) {
        $activeTab = 'settings';
    } else {
        $activeTab = 'personal';
    }
}

$user = $user ?? auth();
$profile = $profile ?? [];
if (empty($profile) && !empty($user['id'])) {
    $profileModel = new \App\Models\AlumniProfile();
    $profile = $profileModel->getByUserId((int)$user['id']) ?: [];
}

$status = $profile['status'] ?? 'verified';
$statusBg = in_array($status, ['approved', 'verified', 'active']) ? 'rgba(47,136,99,0.1)' : 'rgba(128,0,32,0.1)';
$statusColor = in_array($status, ['approved', 'verified', 'active']) ? '#2F8863' : '#800020';

$avatarFile = !empty($profile['avatar']) ? $profile['avatar'] : (!empty($user['avatar']) ? $user['avatar'] : '');
?>
<!-- Left Column: Avatar, Signature & Quick Info -->
<div class="w-full lg:w-72 shrink-0 space-y-6">
  
  <!-- Profile Main Card -->
  <div class="p-6 rounded-3xl bg-white border border-gray-100 shadow-sm text-center">
    <div class="relative w-28 h-28 mx-auto mb-4 group">
      <?php if (!empty($avatarFile)): ?>
      <img src="<?= avatar_url($avatarFile) ?>" alt="Avatar" class="w-full h-full rounded-full object-cover shadow-md border-2 border-slate-100">
      <?php else: ?>
      <div class="w-full h-full rounded-full flex items-center justify-center font-serif text-[34px] text-white shadow-md" style="background:linear-gradient(135deg,#800020,#2F8863);">
        <?= initials($user['name'] ?? 'A') ?>
      </div>
      <?php endif; ?>
    </div>

    <h4 class="font-serif text-[17px] font-semibold text-gray-800 leading-tight"><?= e($user['name'] ?? '') ?></h4>
    <p class="text-[12px] text-gray-400 mt-1 truncate"><?= e($user['email'] ?? '') ?></p>
    <?php if (!empty($profile['secondary_email'])): ?>
    <p class="text-[11px] text-slate-500 mt-0.5 truncate" title="Secondary Email: <?= e($profile['secondary_email']) ?>">
      <i class="fa-regular fa-envelope text-[10px] mr-1 text-[#800020]"></i><?= e($profile['secondary_email']) ?>
    </p>
    <?php endif; ?>
    
    <span class="inline-flex items-center gap-1.5 mt-3 px-3 py-1 rounded-full text-[10.5px] font-mono font-semibold uppercase tracking-wider"
          style="background:<?= $statusBg ?>;color:<?= $statusColor ?>;">
      <span class="w-1.5 h-1.5 rounded-full" style="background:<?= $statusColor ?>;"></span>
      <?= e($status) ?>
    </span>

    <!-- Avatar Form -->
    <form method="POST" action="<?= url('/portal/profile/avatar') ?>" enctype="multipart/form-data" class="mt-5 pt-4 border-t border-gray-100">
      <?= csrf_field() ?>
      <label class="inline-block text-[12px] font-semibold text-[#800020] hover:text-[#A22638] cursor-pointer hover:underline">
        <i class="fa-solid fa-camera mr-1"></i> Change Photo
        <input type="file" name="avatar" accept="image/*" class="hidden" onchange="this.form.submit()">
      </label>
    </form>

    <!-- Digital Signature Upload Form -->
    <div class="mt-4 pt-4 border-t border-gray-100 text-left">
      <label class="block text-[12px] font-bold text-gray-700 mb-1.5 flex items-center justify-between">
        <span><i class="fa-solid fa-file-signature text-[#800020] mr-1"></i> Digital Signature (ডিজিটাল স্বাক্ষর)</span>
      </label>
      
      <?php 
        $sigFile = '';
        if (!empty($user['id'])) {
            $dbUser = \App\Services\Database::connection()->prepare("SELECT signature_image FROM users WHERE id=?");
            $dbUser->execute([$user['id']]);
            $sigFile = $dbUser->fetchColumn();
        }
      ?>

      <?php if (!empty($sigFile)): ?>
      <div class="mb-2 p-2 rounded-xl bg-gray-50 border border-gray-200 text-center">
        <img src="<?= signature_url($sigFile) ?>" alt="Signature" class="max-h-14 mx-auto object-contain">
        <span class="text-[10px] text-emerald-600 font-semibold block mt-1">✓ Signature Uploaded</span>
      </div>
      <?php endif; ?>

      <form method="POST" action="<?= url('/portal/profile/signature') ?>" enctype="multipart/form-data">
        <?= csrf_field() ?>
        <label class="block text-center py-2 px-3 rounded-xl border border-dashed border-gray-300 hover:border-[#800020] bg-gray-50/50 hover:bg-gray-50 text-[11.5px] font-semibold text-gray-600 cursor-pointer transition-all">
          <i class="fa-solid fa-upload mr-1 text-[#800020]"></i> <?= !empty($sigFile) ? 'Upload New Signature' : 'Upload Signature Image' ?>
          <input type="file" name="signature" accept="image/png,image/jpeg,image/webp" class="hidden" onchange="this.form.submit()">
        </label>
        <span class="text-[10px] text-gray-400 block mt-1 text-center">Transparent PNG recommended (Max 2MB)</span>
      </form>
    </div>
  </div>

  <!-- Quick Nav Links Tabs -->
  <div class="p-4 rounded-3xl bg-white border border-gray-100 shadow-sm space-y-1 text-[13.5px]">
    <a href="<?= url('/portal/profile') ?>" 
       class="flex items-center gap-2.5 px-3.5 py-2.5 rounded-xl font-medium transition-all <?= $activeTab === 'personal' ? 'bg-[#800020]/10 text-[#800020] font-semibold' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' ?>">
      <i class="fa-solid fa-user text-[13px] w-4 text-center <?= $activeTab === 'personal' ? 'text-[#800020]' : 'text-gray-400' ?>"></i>
      <span>Personal Info</span>
    </a>
    <a href="<?= url('/portal/profile/education') ?>" 
       class="flex items-center gap-2.5 px-3.5 py-2.5 rounded-xl font-medium transition-all <?= $activeTab === 'education' ? 'bg-[#800020]/10 text-[#800020] font-semibold' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' ?>">
      <i class="fa-solid fa-graduation-cap text-[13px] w-4 text-center <?= $activeTab === 'education' ? 'text-[#800020]' : 'text-gray-400' ?>"></i>
      <span>Education Details</span>
    </a>
    <a href="<?= url('/portal/profile/employment') ?>" 
       class="flex items-center gap-2.5 px-3.5 py-2.5 rounded-xl font-medium transition-all <?= $activeTab === 'employment' ? 'bg-[#800020]/10 text-[#800020] font-semibold' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' ?>">
      <i class="fa-solid fa-briefcase text-[13px] w-4 text-center <?= $activeTab === 'employment' ? 'text-[#800020]' : 'text-gray-400' ?>"></i>
      <span>Employment Details</span>
    </a>
    <a href="<?= url('/portal/settings') ?>" 
       class="flex items-center gap-2.5 px-3.5 py-2.5 rounded-xl font-medium transition-all <?= $activeTab === 'settings' ? 'bg-[#800020]/10 text-[#800020] font-semibold' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' ?>">
      <i class="fa-solid fa-gear text-[13px] w-4 text-center <?= $activeTab === 'settings' ? 'text-[#800020]' : 'text-gray-400' ?>"></i>
      <span>Settings</span>
    </a>
  </div>

</div>
