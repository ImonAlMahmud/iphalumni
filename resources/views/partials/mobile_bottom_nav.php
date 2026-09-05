<?php
/**
 * Mobile Bottom Navigation Dock & PWA App Install Banner
 */
$currentUri = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);
$currentUri = trim($currentUri, '/');
// Strip subdirectory if present
$appSubdir = trim(parse_url(env('APP_URL', ''), PHP_URL_PATH) ?? '', '/');
if ($appSubdir && str_starts_with($currentUri, $appSubdir)) {
    $currentUri = trim(substr($currentUri, strlen($appSubdir)), '/');
}

$isHome      = ($currentUri === '' || $currentUri === 'home');
$isDirectory = str_starts_with($currentUri, 'directory');
$isJobs       = str_starts_with($currentUri, 'jobs');
$isEvents     = str_starts_with($currentUri, 'events');
$isPortal     = str_starts_with($currentUri, 'portal') || str_starts_with($currentUri, 'login') || str_starts_with($currentUri, 'register');

$user = auth_user();
$userAvatar = !empty($user['avatar']) ? $user['avatar'] : null;
if (!$userAvatar && !empty($user['id'])) {
    $userAvatar = \Illuminate\Support\Facades\DB::table('alumni_profiles')->where('user_id', $user['id'])->value('avatar');
}
?>

<!-- PWA Mobile Install Banner (Triggers via JS beforeinstallprompt) -->
<div id="pwa-install-banner" class="hidden md:hidden fixed top-3 inset-x-3 z-[99] bg-[#101820] text-white p-3.5 rounded-2xl shadow-2xl border border-white/10 flex items-center justify-between gap-3 animate-fade-in">
  <div class="flex items-center gap-3">
    <div class="w-10 h-10 rounded-xl bg-white p-1.5 shrink-0 flex items-center justify-center">
      <img src="<?= asset('images/LOGO.png') ?>" alt="Logo" class="w-full h-full object-contain">
    </div>
    <div class="text-left">
      <div class="text-[13px] font-bold leading-snug">আইপিএইচ অ্যালামনাই অ্যাপ</div>
      <div class="text-[11px] text-gray-300">হোম স্ক্রিনে সহজে যোগ করুন</div>
    </div>
  </div>
  <div class="flex items-center gap-2">
    <button id="pwa-install-btn" class="px-3 py-1.5 rounded-xl text-[12px] font-semibold bg-[#800020] hover:bg-[#A22638] text-white shadow transition-all">
      ইনস্টল
    </button>
    <button id="pwa-dismiss-btn" class="w-7 h-7 rounded-full flex items-center justify-center text-gray-400 hover:text-white text-[14px]">
      ✕
    </button>
  </div>
</div>

<!-- Mobile Bottom Navigation Bar (Dock) -->
<nav class="md:hidden fixed bottom-0 inset-x-0 z-40 bg-white/95 backdrop-blur-xl border-t border-slate-200/80 shadow-[0_-4px_20px_rgba(0,0,0,0.06)] px-2 py-1.5 pb-safe">
  <div class="grid grid-cols-5 items-center justify-around text-center max-w-md mx-auto">
    
    <!-- Home -->
    <a href="<?= url('/') ?>" class="flex flex-col items-center py-1 px-1 transition-transform active:scale-95 <?= $isHome ? 'text-[#800020] font-bold' : 'text-slate-500 hover:text-slate-800' ?>">
      <div class="relative w-9 h-7 flex items-center justify-center rounded-xl mb-0.5 <?= $isHome ? 'bg-rose-50' : '' ?>">
        <i class="fa-solid fa-house text-[17px]"></i>
        <?php if ($isHome): ?><span class="absolute -bottom-1 w-1 h-1 rounded-full bg-[#800020]"></span><?php endif; ?>
      </div>
      <span class="text-[10px] leading-tight tracking-tight"><?= __('হোম', 'Home') ?></span>
    </a>

    <!-- Directory -->
    <a href="<?= url('/directory') ?>" class="flex flex-col items-center py-1 px-1 transition-transform active:scale-95 <?= $isDirectory ? 'text-[#800020] font-bold' : 'text-slate-500 hover:text-slate-800' ?>">
      <div class="relative w-9 h-7 flex items-center justify-center rounded-xl mb-0.5 <?= $isDirectory ? 'bg-rose-50' : '' ?>">
        <i class="fa-solid fa-users text-[17px]"></i>
        <?php if ($isDirectory): ?><span class="absolute -bottom-1 w-1 h-1 rounded-full bg-[#800020]"></span><?php endif; ?>
      </div>
      <span class="text-[10px] leading-tight tracking-tight"><?= __('ডিরেক্টরি', 'Directory') ?></span>
    </a>

    <!-- Jobs -->
    <a href="<?= url('/jobs') ?>" class="flex flex-col items-center py-1 px-1 transition-transform active:scale-95 <?= $isJobs ? 'text-[#800020] font-bold' : 'text-slate-500 hover:text-slate-800' ?>">
      <div class="relative w-9 h-7 flex items-center justify-center rounded-xl mb-0.5 <?= $isJobs ? 'bg-rose-50' : '' ?>">
        <i class="fa-solid fa-briefcase text-[17px]"></i>
        <?php if ($isJobs): ?><span class="absolute -bottom-1 w-1 h-1 rounded-full bg-[#800020]"></span><?php endif; ?>
      </div>
      <span class="text-[10px] leading-tight tracking-tight"><?= __('চাকরি', 'Jobs') ?></span>
    </a>

    <!-- Events -->
    <a href="<?= url('/events') ?>" class="flex flex-col items-center py-1 px-1 transition-transform active:scale-95 <?= $isEvents ? 'text-[#800020] font-bold' : 'text-slate-500 hover:text-slate-800' ?>">
      <div class="relative w-9 h-7 flex items-center justify-center rounded-xl mb-0.5 <?= $isEvents ? 'bg-rose-50' : '' ?>">
        <i class="fa-solid fa-calendar-days text-[17px]"></i>
        <?php if ($isEvents): ?><span class="absolute -bottom-1 w-1 h-1 rounded-full bg-[#800020]"></span><?php endif; ?>
      </div>
      <span class="text-[10px] leading-tight tracking-tight"><?= __('ইভেন্ট', 'Events') ?></span>
    </a>

    <!-- Portal / Profile -->
    <a href="<?= $user ? url('/portal') : url('/login') ?>" class="flex flex-col items-center py-1 px-1 transition-transform active:scale-95 <?= $isPortal ? 'text-[#800020] font-bold' : 'text-slate-500 hover:text-slate-800' ?>">
      <div class="relative w-9 h-7 flex items-center justify-center rounded-xl mb-0.5 <?= $isPortal ? 'bg-rose-50' : '' ?>">
        <?php if (!empty($userAvatar)): ?>
          <img src="<?= avatar_url($userAvatar) ?>" alt="Avatar" class="w-5 h-5 rounded-full object-cover">
        <?php else: ?>
          <i class="fa-solid fa-user text-[17px]"></i>
        <?php endif; ?>
        <?php if ($isPortal): ?><span class="absolute -bottom-1 w-1 h-1 rounded-full bg-[#800020]"></span><?php endif; ?>
      </div>
      <span class="text-[10px] leading-tight tracking-tight"><?= $user ? __('পোর্টাল', 'Portal') : __('লগইন', 'Login') ?></span>
    </a>

  </div>
</nav>

<!-- Safe bottom spacing for mobile browsers to avoid overlap -->
<div class="h-16 md:hidden"></div>

<script>
// PWA Service Worker Registration & Install Banner
let deferredPrompt = null;
window.addEventListener('beforeinstallprompt', (e) => {
  e.preventDefault();
  deferredPrompt = e;
  const isDismissed = localStorage.getItem('pwa_banner_dismissed');
  const banner = document.getElementById('pwa-install-banner');
  if (banner && !isDismissed) {
    banner.classList.remove('hidden');
  }
});

const installBtn = document.getElementById('pwa-install-btn');
if (installBtn) {
  installBtn.addEventListener('click', async () => {
    if (deferredPrompt) {
      deferredPrompt.prompt();
      const { outcome } = await deferredPrompt.userChoice;
      deferredPrompt = null;
      document.getElementById('pwa-install-banner')?.classList.add('hidden');
    }
  });
}

const dismissBtn = document.getElementById('pwa-dismiss-btn');
if (dismissBtn) {
  dismissBtn.addEventListener('click', () => {
    document.getElementById('pwa-install-banner')?.classList.add('hidden');
    localStorage.setItem('pwa_banner_dismissed', 'true');
  });
}

if ('serviceWorker' in navigator) {
  window.addEventListener('load', () => {
    navigator.serviceWorker.register('<?= asset('sw.js') ?>')
      .then((reg) => console.log('PWA Service Worker registered:', reg.scope))
      .catch((err) => console.warn('PWA Service Worker registration failed:', err));
  });
}
</script>
