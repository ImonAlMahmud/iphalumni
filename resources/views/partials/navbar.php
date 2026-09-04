<?php
$appName = env('APP_NAME', 'IPH Alumni Association');
$currentPath = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);

$appUrlPath = parse_url(env('APP_URL', 'http://localhost/alumni/public'), PHP_URL_PATH);
if ($appUrlPath && $appUrlPath !== '/') {
    $basePath = rtrim($appUrlPath, '/');
    if (str_starts_with($currentPath, $basePath)) {
        $currentPath = substr($currentPath, strlen($basePath));
    }
}

function isActive(string $path): bool {
    $uriPath = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);
    $uriPath = rtrim($uriPath, '/');
    if ($path === '/') {
        return ($uriPath === '' || str_ends_with($uriPath, '/public') || str_ends_with($uriPath, '/public/index.php'));
    }
    return str_contains($uriPath, $path);
}

$currentLocale = $_SESSION['lang'] ?? 'bn';
$toggleLocale  = $currentLocale === 'bn' ? 'en' : 'bn';
$toggleLabel   = $currentLocale === 'bn' ? 'English' : 'বাংলা';

// Primary Nav links
$primaryNavLinks = [
    ['/directory', __('ডিরেক্টরি', 'Directory'), 'fa-solid fa-address-book'],
];

// Explore / Network Submenu Items (Jobs, Stories, Events, News, Gallery, Mentorship)
$exploreSubmenu = [
    ['/jobs',        __('জব সার্কুলার', 'Jobs'),    'fa-solid fa-briefcase'],
    ['/mentorship',  __('মেনটরশিপ কানেক্ট', 'Mentorship'), 'fa-solid fa-user-graduate'],
    ['/stories',     __('সফলতার গল্প',  'Stories'), 'fa-solid fa-trophy'],
    ['/events',      __('ইভেন্ট',        'Events'),   'fa-solid fa-calendar-days'],
    ['/news',        __('সংবাদ',         'News'),     'fa-solid fa-newspaper'],
    ['/gallery',     __('গ্যালারি',      'Gallery'),  'fa-solid fa-images'],
];

// Check if any explore submenu item is active
$isExploreActive = false;
foreach ($exploreSubmenu as [$subHref]) {
    if (isActive($subHref)) {
        $isExploreActive = true;
        break;
    }
}
?>
<header class="sticky top-0 z-50 px-4 pt-3 pb-2">
  <div class="max-w-7xl mx-auto">
    <div class="flex items-center justify-between gap-4 px-5 py-2.5"
         style="background:rgba(255,255,255,0.9);border:1px solid rgba(16,24,32,0.07);backdrop-filter:blur(22px) saturate(160%);border-radius:18px;box-shadow:0 8px 32px -12px rgba(16,24,32,0.12),0 1px 0 rgba(255,255,255,0.8) inset;">

      <!-- ── Brand ── -->
      <a href="<?= url('/') ?>" class="flex items-center gap-3 shrink-0 group">
        <img src="<?= asset('images/LOGO.png') ?>" alt="IPH Logo"
             class="w-9 h-9 object-contain transition-transform duration-300 group-hover:scale-105">
        <div class="hidden sm:block">
          <div class="font-semibold text-[#101820] text-[14.5px] leading-tight tracking-tight">
            <?= e(__('আইপিএইচ অ্যালামনাই অ্যাসোসিয়েশন', $appName)) ?>
          </div>
          <div class="font-mono text-[10px] text-[#800020] tracking-widest">IPH ALUMNI</div>
        </div>
        <div class="sm:hidden font-serif text-[17px] font-bold text-[#800020]">IPH</div>
      </a>

      <!-- ── Desktop Nav ── -->
      <nav class="hidden lg:flex items-center gap-1 text-[13.5px]">
        <!-- Directory -->
        <a href="<?= url('/directory') ?>"
           class="flex items-center gap-1.5 px-3.5 py-1.5 rounded-xl font-medium transition-all duration-200 relative <?= isActive('/directory') ? 'text-[#800020] bg-[#800020]/[0.07]' : 'text-[#4A5568] hover:text-[#101820] hover:bg-[#101820]/[0.05]' ?>">
          <i class="fa-solid fa-address-book text-[11px] opacity-75"></i>
          <?= __('ডিরেক্টরি', 'Directory') ?>
          <?php if (isActive('/directory')): ?>
          <span class="absolute bottom-0.5 left-1/2 -translate-x-1/2 w-1 h-1 rounded-full bg-[#800020]"></span>
          <?php endif; ?>
        </a>

        <!-- Explore / Activities Dropdown Drawer Submenu -->
        <div x-data="{ open: false }" @click.away="open = false" class="relative">
          <button @click="open = !open"
                  class="flex items-center gap-1.5 px-3.5 py-1.5 rounded-xl font-medium transition-all duration-200 <?= $isExploreActive ? 'text-[#800020] bg-[#800020]/[0.07]' : 'text-[#4A5568] hover:text-[#101820] hover:bg-[#101820]/[0.05]' ?>">
            <i class="fa-solid fa-compass text-[12px] opacity-75"></i>
            <span><?= __('কার্যক্রম ও নেটওয়ার্ক', 'Explore') ?></span>
            <i class="fa-solid fa-chevron-down text-[10px] opacity-60 transition-transform duration-200" :class="{ 'rotate-180': open }"></i>
            <?php if ($isExploreActive): ?>
            <span class="absolute bottom-0.5 left-1/2 -translate-x-1/2 w-1 h-1 rounded-full bg-[#800020]"></span>
            <?php endif; ?>
          </button>

          <!-- Submenu Dropdown Card -->
          <div x-show="open"
               x-transition:enter="transition ease-out duration-150"
               x-transition:enter-start="opacity-0 scale-95 -translate-y-1"
               x-transition:enter-end="opacity-100 scale-100 translate-y-0"
               x-transition:leave="transition ease-in duration-100"
               x-transition:leave-start="opacity-100 scale-100 translate-y-0"
               x-transition:leave-end="opacity-0 scale-95 -translate-y-1"
               class="absolute top-full left-0 mt-2 w-52 p-2 rounded-2xl bg-white/95 backdrop-blur-xl shadow-xl border border-slate-200/80 z-50">
            <?php foreach ($exploreSubmenu as [$href, $label, $icon]): ?>
            <a href="<?= url($href) ?>"
               class="flex items-center gap-2.5 px-3 py-2 rounded-xl text-[13.5px] transition-all <?= isActive($href) ? 'text-[#800020] bg-[#800020]/[0.08] font-semibold' : 'text-[#4A5568] hover:text-[#101820] hover:bg-slate-100/70' ?>">
              <i class="<?= $icon ?> text-[12px] w-4 text-center <?= isActive($href) ? 'text-[#800020]' : 'text-slate-400' ?>"></i>
              <?= $label ?>
            </a>
            <?php endforeach; ?>
          </div>
        </div>

        <!-- About -->
        <a href="<?= url('/about') ?>"
           class="flex items-center gap-1.5 px-3.5 py-1.5 rounded-xl font-medium transition-all duration-200 relative <?= isActive('/about') ? 'text-[#800020] bg-[#800020]/[0.07]' : 'text-[#4A5568] hover:text-[#101820] hover:bg-[#101820]/[0.05]' ?>">
          <i class="fa-solid fa-circle-info text-[11px] opacity-75"></i>
          <?= __('পরিচিতি', 'About') ?>
          <?php if (isActive('/about')): ?>
          <span class="absolute bottom-0.5 left-1/2 -translate-x-1/2 w-1 h-1 rounded-full bg-[#800020]"></span>
          <?php endif; ?>
        </a>

        <!-- Contact -->
        <a href="<?= url('/contact') ?>"
           class="flex items-center gap-1.5 px-3.5 py-1.5 rounded-xl font-medium transition-all duration-200 relative <?= isActive('/contact') ? 'text-[#800020] bg-[#800020]/[0.07]' : 'text-[#4A5568] hover:text-[#101820] hover:bg-[#101820]/[0.05]' ?>">
          <i class="fa-solid fa-envelope text-[11px] opacity-75"></i>
          <?= __('যোগাযোগ', 'Contact') ?>
          <?php if (isActive('/contact')): ?>
          <span class="absolute bottom-0.5 left-1/2 -translate-x-1/2 w-1 h-1 rounded-full bg-[#800020]"></span>
          <?php endif; ?>
        </a>
      </nav>

      <!-- ── Right Actions ── -->
      <div class="flex items-center gap-2">

        <!-- Language toggle -->
        <a href="?lang=<?= $toggleLocale ?>"
           class="hidden sm:inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-[12.5px] font-medium text-[#6B7178] hover:text-[#101820] transition-all hover:bg-[#101820]/5"
           style="border:1px solid rgba(16,24,32,0.08);">
          <i class="fa-solid fa-language text-[13px]"></i>
          <?= $toggleLabel ?>
        </a>

        <?php if (is_logged_in()): ?>
          <?php if (is_admin()): ?>
          <a href="<?= url('/admin') ?>"
             class="hidden sm:inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-xl text-[13px] font-medium text-[#6B7178] hover:text-[#101820] transition-all"
             style="background:rgba(255,255,255,0.7);border:1px solid rgba(16,24,32,0.08);">
            <i class="fa-solid fa-gauge-high text-[11px]"></i>
            <?= __('অ্যাডমিন', 'Admin') ?>
          </a>
          <?php endif; ?>

          <a href="<?= url('/portal') ?>"
             class="hidden sm:inline-flex items-center gap-2 px-4 py-1.5 rounded-xl text-[13px] font-semibold text-[#101820] transition-all hover:-translate-y-px hover:shadow-md"
             style="background:rgba(255,255,255,0.8);border:1px solid rgba(16,24,32,0.1);">
            <span class="w-6 h-6 rounded-full flex items-center justify-center text-white text-[10px] font-bold shrink-0"
                  style="background:linear-gradient(135deg,#800020,#2F8863);">
              <?= initials(auth()['name']) ?>
            </span>
            <i class="fa-solid fa-table-columns text-[11px] opacity-70"></i>
            <?= __('পোর্টাল', 'Portal') ?>
          </a>

          <a href="<?= url('/logout') ?>"
             class="px-3 py-1.5 rounded-xl text-[13px] font-medium text-[#6B7178] hover:text-red-600 transition-colors"
             style="border:1px solid rgba(16,24,32,0.07);"
             title="<?= __('লগআউট', 'Logout') ?>">
            <i class="fa-solid fa-arrow-right-from-bracket"></i>
          </a>

        <?php else: ?>
          <a href="<?= url('/login') ?>"
             class="inline-flex items-center gap-1.5 px-4 py-1.5 rounded-xl text-[13px] font-medium text-[#4A5568] hover:text-[#101820] transition-all"
             style="border:1px solid rgba(16,24,32,0.08);">
            <i class="fa-solid fa-right-to-bracket text-[12px]"></i>
            <?= __('লগইন', 'Log in') ?>
          </a>
          <a href="<?= url('/register') ?>"
             class="inline-flex items-center gap-1.5 px-4 py-1.5 rounded-xl text-[13px] font-semibold text-white transition-all hover:-translate-y-px hover:shadow-lg"
             style="background:linear-gradient(135deg,#A22638,#800020);box-shadow:0 3px 10px -3px rgba(128,0,32,0.45);">
            <i class="fa-solid fa-user-plus text-[12px]"></i>
            <?= __('যোগ দিন', 'Join Alumni') ?>
          </a>
        <?php endif; ?>

        <!-- Mobile hamburger -->
        <button class="lg:hidden p-2 rounded-xl text-[#6B7178] hover:text-[#101820] transition-colors"
                style="background:rgba(255,255,255,0.7);border:1px solid rgba(16,24,32,0.08);"
                x-data x-on:click="$dispatch('toggle-menu')"
                aria-label="Toggle Menu">
          <i class="fa-solid fa-bars text-[16px]"></i>
        </button>
      </div>
    </div>

    <!-- ── Mobile Dropdown ── -->
    <div x-data="{ open: false }" @toggle-menu.window="open = !open"
         x-show="open"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 -translate-y-2"
         x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 translate-y-0"
         x-transition:leave-end="opacity-0 -translate-y-2"
         class="mt-2 px-4 py-4 rounded-2xl"
         style="background:rgba(255,255,255,0.97);border:1px solid rgba(16,24,32,0.07);backdrop-filter:blur(20px);box-shadow:0 12px 40px -12px rgba(16,24,32,0.15);">

      <nav class="flex flex-col gap-0.5">
        <!-- Directory -->
        <a href="<?= url('/directory') ?>"
           class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition-all text-[14px] <?= isActive('/directory') ? 'text-[#800020] bg-[#800020]/[0.07] font-semibold' : 'text-[#4A5568] hover:text-[#101820] hover:bg-gray-50' ?>">
          <i class="fa-solid fa-address-book w-4 text-center <?= isActive('/directory') ? 'text-[#800020]' : 'text-[#9CA3AF]' ?>"></i>
          <?= __('ডিরেক্টরি', 'Directory') ?>
        </a>

        <!-- Mobile Drawer Submenu Accordion -->
        <div x-data="{ subOpen: <?= $isExploreActive ? 'true' : 'false' ?> }">
          <button @click="subOpen = !subOpen"
                  class="w-full flex items-center justify-between px-3.5 py-2.5 rounded-xl transition-all text-[14px] <?= $isExploreActive ? 'text-[#800020] bg-[#800020]/[0.07] font-semibold' : 'text-[#4A5568] hover:text-[#101820] hover:bg-gray-50' ?>">
            <div class="flex items-center gap-3">
              <i class="fa-solid fa-compass w-4 text-center <?= $isExploreActive ? 'text-[#800020]' : 'text-[#9CA3AF]' ?>"></i>
              <span><?= __('কার্যক্রম ও নেটওয়ার্ক', 'Explore') ?></span>
            </div>
            <i class="fa-solid fa-chevron-down text-[11px] opacity-60 transition-transform duration-200" :class="{ 'rotate-180': subOpen }"></i>
          </button>

          <!-- Drawer Submenu Items -->
          <div x-show="subOpen" x-collapse class="pl-6 pr-2 py-1.5 space-y-1">
            <?php foreach ($exploreSubmenu as [$href, $label, $icon]): ?>
            <a href="<?= url($href) ?>"
               class="flex items-center gap-2.5 px-3 py-2 rounded-xl text-[13.5px] transition-all <?= isActive($href) ? 'text-[#800020] bg-[#800020]/[0.08] font-semibold' : 'text-[#4A5568] hover:text-[#101820] hover:bg-gray-50' ?>">
              <i class="<?= $icon ?> text-[12px] w-4 text-center <?= isActive($href) ? 'text-[#800020]' : 'text-slate-400' ?>"></i>
              <?= $label ?>
            </a>
            <?php endforeach; ?>
          </div>
        </div>

        <!-- About -->
        <a href="<?= url('/about') ?>"
           class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition-all text-[14px] <?= isActive('/about') ? 'text-[#800020] bg-[#800020]/[0.07] font-semibold' : 'text-[#4A5568] hover:text-[#101820] hover:bg-gray-50' ?>">
          <i class="fa-solid fa-circle-info w-4 text-center <?= isActive('/about') ? 'text-[#800020]' : 'text-[#9CA3AF]' ?>"></i>
          <?= __('পরিচিতি', 'About') ?>
        </a>

        <!-- Contact -->
        <a href="<?= url('/contact') ?>"
           class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition-all text-[14px] <?= isActive('/contact') ? 'text-[#800020] bg-[#800020]/[0.07] font-semibold' : 'text-[#4A5568] hover:text-[#101820] hover:bg-gray-50' ?>">
          <i class="fa-solid fa-envelope w-4 text-center <?= isActive('/contact') ? 'text-[#800020]' : 'text-[#9CA3AF]' ?>"></i>
          <?= __('যোগাযোগ', 'Contact') ?>
        </a>
      </nav>

      <div class="border-t border-gray-100 mt-3 pt-3 flex flex-col gap-1.5">
        <!-- Language -->
        <a href="?lang=<?= $toggleLocale ?>"
           class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-[14px] text-[#800020] hover:bg-red-50 transition-colors font-medium">
          <i class="fa-solid fa-language w-4 text-center text-[#800020]"></i>
          <?= $toggleLabel ?>
        </a>

        <?php if (!is_logged_in()): ?>
        <a href="<?= url('/login') ?>"
           class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-[14px] text-[#4A5568] hover:bg-gray-50 transition-colors">
          <i class="fa-solid fa-right-to-bracket w-4 text-center text-[#9CA3AF]"></i>
          <?= __('লগইন', 'Log in') ?>
        </a>
        <a href="<?= url('/register') ?>"
           class="flex items-center justify-center gap-2 px-3.5 py-2.5 rounded-xl font-semibold text-white text-[14px] mt-1 transition-all"
           style="background:linear-gradient(135deg,#A22638,#800020);">
          <i class="fa-solid fa-user-plus text-[13px]"></i>
          <?= __('অ্যালামনাই হিসেবে যোগ দিন', 'Join as Alumni') ?>
        </a>
        <?php else: ?>
        <a href="<?= url('/portal') ?>"
           class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-[14px] text-[#4A5568] hover:bg-gray-50 transition-colors">
          <i class="fa-solid fa-table-columns w-4 text-center text-[#9CA3AF]"></i>
          <?= __('আমার পোর্টাল', 'My Portal') ?>
        </a>
        <a href="<?= url('/logout') ?>"
           class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-[14px] text-red-500 hover:bg-red-50 transition-colors">
          <i class="fa-solid fa-arrow-right-from-bracket w-4 text-center"></i>
          <?= __('লগআউট', 'Logout') ?>
        </a>
        <?php endif; ?>
      </div>
    </div>

  </div>
</header>
