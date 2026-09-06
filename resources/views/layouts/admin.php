<?php
$appName     = env('APP_NAME', 'IPH Alumni Association');
$user        = is_array($u = auth()) ? $u : ($u && method_exists($u, 'toArray') ? $u->toArray() : (\Illuminate\Support\Facades\Auth::user() ? \Illuminate\Support\Facades\Auth::user()->toArray() : []));
$title       = isset($title) ? e($title) . ' — Admin · ' . $appName : 'Admin · ' . $appName;
$currentUri  = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);

// Helper function to accurately detect active admin menu
function isAdminNavActive(string $navPath, string $uri): bool {
    // Standardize paths by removing trailing slashes
    $navPath = rtrim($navPath, '/');
    $cleanUri = rtrim($uri, '/');
    
    // Normalize /public if present
    if (str_contains($cleanUri, '/public')) {
        $cleanUri = str_replace('/public', '', $cleanUri);
    }

    if ($cleanUri === $navPath) {
        return true;
    }

    if (str_ends_with($cleanUri, $navPath)) {
        return true;
    }

    // Prevents parent route /admin/alumni from matching child sub-routes that have their own sidebar item
    if ($navPath === '/admin/alumni') {
        if (str_contains($cleanUri, '/admin/alumni/contact-requests') || str_contains($cleanUri, '/admin/alumni/mapping')) {
            return false;
        }
    }

    if ($navPath === '/admin/membership') {
        if (str_contains($cleanUri, '/admin/membership/logs')) {
            return false;
        }
    }

    if (str_contains($cleanUri, $navPath . '/')) {
        return true;
    }

    return false;
}

$navGroups = [
    'CORE' => [
        ['/admin/dashboard', 'Dashboard', 'fa-solid fa-gauge-high'],
        ['/admin/alumni', 'Alumni Profiles', 'fa-solid fa-user-graduate'],
        ['/admin/alumni/contact-requests', 'Contact Requests', 'fa-solid fa-envelope-open-text'],
        ['/admin/alumni/mapping', 'User Data Mapping', 'fa-solid fa-diagram-project'],
        ['/admin/students', 'Student Database', 'fa-solid fa-database'],
        ['/admin/membership', 'Memberships', 'fa-solid fa-id-card-clip'],
        ['/admin/membership/logs', 'Membership & Payment Log', 'fa-solid fa-receipt'],
    ],
    'CONTENT' => [
        ['/admin/stories', 'Blogs & Articles', 'fa-solid fa-book-open-reader'],
        ['/admin/news', 'News & Articles', 'fa-solid fa-newspaper'],
        ['/admin/events', 'Events', 'fa-solid fa-calendar-days'],
        ['/admin/gallery', 'Gallery Albums', 'fa-solid fa-images'],
        ['/admin/committee', 'Committee', 'fa-solid fa-users-gear'],
    ],
    'SYSTEM' => [
        ['/admin/broadcast', 'Email Broadcast', 'fa-solid fa-bullhorn'],
        ['/admin/email-templates', 'Email Templates', 'fa-solid fa-envelope-circle-check'],
        ['/admin/reports', 'Reports', 'fa-solid fa-chart-pie'],
        ['/admin/settings', 'Settings', 'fa-solid fa-sliders'],
    ]
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= $title ?></title>
<link rel="icon" type="image/png" href="<?= asset('images/LOGO.png') ?>">
<link rel="apple-touch-icon" href="<?= asset('images/LOGO.png') ?>">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,600&family=Inter:wght@400;500;600;700&family=IBM+Plex+Mono:wght@400;500&display=swap" rel="stylesheet">
<link href="https://fonts.maateen.me/kalpurush/font.css" rel="stylesheet">
<script src="https://cdn.tailwindcss.com"></script>
<script>tailwind.config = { theme: { extend: { fontFamily: { sans:['Kalpurush','Inter','system-ui','sans-serif'], serif:['Kalpurush','Fraunces','Georgia','serif'], mono:['IBM Plex Mono','Consolas','monospace'] } } } }</script>
<link rel="stylesheet" href="<?= asset('css/app.css') ?>">
<!-- Font Awesome 6 -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer">
<script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
<script src="https://unpkg.com/htmx.org@1.9.10"></script>
</head>
<body style="background:#000000;color:#E8EBF0;font-family:'Inter',sans-serif;min-height:100vh;">

<?php $error = flash('error'); $success = flash('success'); ?>
<?php if ($error): ?><div id="flash-msg" class="fixed top-4 right-4 z-[100] bg-red-950 border border-red-800 text-red-200 px-5 py-3 rounded-xl shadow-lg text-sm font-medium flex items-center gap-3 max-w-sm"><i class="fa-solid fa-triangle-exclamation text-red-400"></i> <?= e($error) ?><button onclick="document.getElementById('flash-msg').remove()" class="ml-auto hover:text-white"><i class="fa-solid fa-xmark"></i></button></div><?php endif; ?>
<?php if ($success): ?><div id="flash-msg" class="fixed top-4 right-4 z-[100] bg-emerald-950 border border-emerald-800 text-emerald-200 px-5 py-3 rounded-xl shadow-lg text-sm font-medium flex items-center gap-3 max-w-sm"><i class="fa-solid fa-circle-check text-emerald-400"></i> <?= e($success) ?><button onclick="document.getElementById('flash-msg').remove()" class="ml-auto hover:text-white"><i class="fa-solid fa-xmark"></i></button></div><?php endif; ?>

<div class="flex min-h-screen bg-black" x-data="{ mobileSidebarOpen: false }">

  <!-- Mobile Sidebar (Slide-over) -->
  <div x-show="mobileSidebarOpen" class="fixed inset-0 z-50 flex lg:hidden" style="display: none;">
    <!-- Backdrop -->
    <div class="fixed inset-0 bg-black/80 backdrop-blur-sm" @click="mobileSidebarOpen = false"></div>
    
    <!-- Sidebar Content -->
    <aside class="relative flex flex-col w-64 max-w-xs h-full bg-black border-r border-white/10" x-transition:enter="transition ease-out duration-200 transform" x-transition:enter-start="-translate-x-full" x-transition:enter-end="translate-x-0" x-transition:leave="transition ease-in duration-150 transform" x-transition:leave-start="translate-x-0" x-transition:leave-end="-translate-x-full">
      <div class="px-5 py-5 border-b border-white/10 flex items-center justify-between">
        <div class="flex items-center gap-2.5 font-semibold text-[15px]">
          <img src="<?= asset('images/LOGO.png') ?>" alt="Logo" class="w-8 h-8 object-contain">
          <span class="text-white">Admin Panel</span>
        </div>
        <button @click="mobileSidebarOpen = false" class="text-white/60 hover:text-white"><i class="fa-solid fa-xmark text-lg"></i></button>
      </div>

      <!-- User info -->
      <div class="px-5 py-4 border-b border-white/10">
        <div class="flex items-center gap-2.5">
          <div class="w-8 h-8 rounded-full flex items-center justify-center text-[12px] font-bold"
               style="background:linear-gradient(135deg,#800020,#2F8863);color:#FFFFFF;">
            <?= initials($user['name'] ?? 'A') ?>
          </div>
          <div class="min-w-0">
            <div class="text-[12.5px] font-medium text-white truncate"><?= e($user['name'] ?? '') ?></div>
            <div class="text-[10.5px] font-mono text-[#E58E97]"><?= strtoupper($user['role'] ?? 'ADMIN') ?></div>
          </div>
        </div>
      </div>

      <!-- Nav -->
      <nav class="flex-1 px-3 py-4 space-y-0.5 overflow-y-auto bg-black">
        <?php foreach ($navGroups as $groupLabel => $items): ?>
        <div class="pt-3 pb-1 px-3 font-mono text-[9px] tracking-widest text-white/40"><?= $groupLabel ?></div>
        <?php foreach ($items as [$path, $label, $icon]): 
          $active = isAdminNavActive($path, $currentUri);
        ?>
        <a href="<?= url($path) ?>"
           class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-[13px] font-medium transition-all duration-150 relative <?= $active ? 'text-white shadow-md' : 'text-white/60 hover:text-white hover:bg-white/10' ?>"
           style="<?= $active ? 'background:linear-gradient(135deg,#800020,#A22638);border:1px solid rgba(229,142,151,0.4);' : '' ?>">
          <i class="<?= $icon ?> text-[14px] w-4 text-center shrink-0 <?= $active ? 'text-white' : 'text-white/50' ?>"></i>
          <span><?= $label ?></span>
          <?php if ($active): ?>
          <span class="ml-auto w-1.5 h-1.5 rounded-full bg-white shadow-sm"></span>
          <?php endif; ?>
        </a>
        <?php endforeach; ?>
        <?php endforeach; ?>
      </nav>

      <!-- Bottom -->
      <div class="px-3 py-4 border-t border-white/10 space-y-0.5 bg-black">
        <a href="<?= url('/') ?>" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-[13px] transition-all hover:bg-white/10 text-white/60">
          <i class="fa-solid fa-arrow-up-right-from-square w-4 text-center"></i>
          Public Site
        </a>
        <form id="logout-form-admin" action="<?= route('logout') ?>" method="POST" style="display:none;">
          <?= csrf_field() ?>
        </form>
        <button type="button" onclick="document.getElementById('logout-form-admin').submit();" class="w-full flex items-center gap-3 px-3 py-2.5 rounded-xl text-[13px] transition-all hover:bg-red-950 hover:text-red-400 text-white/60 cursor-pointer text-left">
          <i class="fa-solid fa-right-from-bracket w-4 text-center"></i>
          Logout
        </button>
      </div>
    </aside>
  </div>

  <!-- Sidebar (Desktop) -->
  <aside class="w-60 shrink-0 hidden lg:flex flex-col bg-black border-r border-white/10 sticky top-0 h-screen overflow-y-auto select-none">
    <div class="px-5 py-5 border-b border-white/10 shrink-0">
      <div class="flex items-center gap-2.5 font-semibold text-[15px]">
        <img src="<?= asset('images/LOGO.png') ?>" alt="Logo" class="w-8 h-8 object-contain">
        <span class="text-white">Admin Panel</span>
      </div>
      <div class="mt-1 font-mono text-[10px] tracking-widest text-white/40">IPH ALUMNI MGMT</div>
    </div>

    <!-- User -->
    <div class="px-5 py-4 border-b border-white/10 shrink-0">
      <div class="flex items-center gap-2.5">
        <div class="w-8 h-8 rounded-full flex items-center justify-center text-[12px] font-bold shrink-0"
             style="background:linear-gradient(135deg,#800020,#2F8863);color:#FFFFFF;">
          <?= initials($user['name'] ?? 'A') ?>
        </div>
        <div class="min-w-0">
          <div class="text-[12.5px] font-medium text-white truncate"><?= e($user['name'] ?? '') ?></div>
          <div class="text-[10.5px] font-mono text-[#E58E97]"><?= strtoupper($user['role'] ?? 'ADMIN') ?></div>
        </div>
      </div>
    </div>

    <!-- Nav -->
    <nav class="flex-1 px-3 py-4 space-y-0.5 bg-black overflow-y-auto custom-scrollbar">
      <?php foreach ($navGroups as $groupLabel => $items): ?>
      <div class="pt-3 pb-1 px-3 font-mono text-[9px] tracking-widest text-white/40"><?= $groupLabel ?></div>
      <?php foreach ($items as [$path, $label, $icon]):
        $active = isAdminNavActive($path, $currentUri);
      ?>
      <a href="<?= url($path) ?>"
         class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-[13px] font-medium transition-all duration-150 relative <?= $active ? 'text-white shadow-md' : 'text-white/60 hover:text-white hover:bg-white/10' ?>"
         style="<?= $active ? 'background:linear-gradient(135deg,#800020,#A22638);border:1px solid rgba(229,142,151,0.4);' : '' ?>">
        <i class="<?= $icon ?> text-[14px] w-4 text-center shrink-0 <?= $active ? 'text-white' : 'text-white/50' ?>"></i>
        <span><?= $label ?></span>
        <?php if ($active): ?>
        <span class="ml-auto w-1.5 h-1.5 rounded-full bg-white shadow-sm"></span>
        <?php endif; ?>
      </a>
      <?php endforeach; ?>
      <?php endforeach; ?>
    </nav>

    <!-- Bottom -->
    <div class="px-3 py-4 border-t border-white/10 space-y-0.5 bg-black shrink-0">
      <a href="<?= url('/') ?>" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-[13px] transition-all hover:bg-white/10 text-white/60">
        <i class="fa-solid fa-arrow-up-right-from-square w-4 text-center"></i>
        Public Site
      </a>
      <button type="button" onclick="document.getElementById('logout-form-admin').submit();" class="w-full flex items-center gap-3 px-3 py-2.5 rounded-xl text-[13px] transition-all hover:bg-red-950 hover:text-red-400 text-white/60 cursor-pointer text-left">
        <i class="fa-solid fa-right-from-bracket w-4 text-center"></i>
        Logout
      </button>
    </div>
  </aside>

  <!-- Main content -->
  <div class="flex-1 flex flex-col min-w-0 bg-black">
    <!-- Top bar -->
    <header class="px-6 py-4 flex items-center justify-between border-b border-white/10 bg-black">
      <div class="flex items-center gap-3">
        <button @click="mobileSidebarOpen = true" class="lg:hidden text-white/70 hover:text-white focus:outline-none">
          <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
        </button>
        <h2 class="font-serif text-[18px] font-semibold text-white truncate"><?= isset($pageTitle) ? e($pageTitle) : 'Admin Panel' ?></h2>
      </div>
      <div class="flex items-center gap-3 text-[13px]" style="color:rgba(255,255,255,0.45);">
        <button type="button" onclick="toggleGoogleTranslate()" class="notranslate text-[12px] font-semibold text-white/80 hover:text-white bg-white/10 hover:bg-white/20 px-2.5 py-1 rounded-lg border border-white/10 flex items-center gap-1.5 transition-all cursor-pointer" translate="no">
          <i class="fa-solid fa-language text-[#E58E97]"></i>
          <span class="gt-lang-label notranslate" translate="no">English</span>
        </button>
        <span class="font-mono text-[11px] hidden sm:inline"><?= date('D, d M Y') ?></span>
        <span class="font-semibold truncate max-w-[120px] sm:max-w-none" style="color:#E58E97;"><?= e($user['name'] ?? '') ?></span>
      </div>
    </header>
    <main class="flex-1 p-4 sm:p-6 lg:p-8"><?= $content ?? '' ?></main>
  </div>
</div>

<!-- Google Translate Partial (Top bar hidden, clean toggle) -->
<?php require view_path('partials/google_translate.php'); ?>

<script>
setTimeout(() => {
  const f = document.getElementById('flash-msg');
  if(f){f.style.cssText='opacity:0;transform:translateX(20px);transition:.3s';setTimeout(()=>f?.remove(),300);}
}, 4000);
</script>
</body>
</html>
