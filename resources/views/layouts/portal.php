<?php
$appName   = env('APP_NAME', 'IPH Alumni Association');
$user      = auth();
$title     = isset($title) ? e($title) . ' — Portal · ' . $appName : 'Portal · ' . $appName;
$currentPath = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);
function portalActive(string $path): string {
    global $currentPath;
    return str_starts_with($currentPath, $path) ? 'bg-white/80 text-[#101820] font-semibold shadow-sm' : 'text-[#6B7178] hover:text-[#101820] hover:bg-white/50';
}
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
<link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,400;9..144,600;9..144,700&family=Inter:wght@300;400;500;600;700&family=IBM+Plex+Mono:wght@400;500&display=swap" rel="stylesheet">
<link href="https://fonts.maateen.me/kalpurush/font.css" rel="stylesheet">
<script src="https://cdn.tailwindcss.com"></script>
<script>
tailwind.config = {
  theme: {
    extend: {
      fontFamily: {
        sans: ['Kalpurush','Inter','system-ui','sans-serif'],
        serif: ['Kalpurush','Fraunces','Georgia','serif'],
        mono: ['IBM Plex Mono','Consolas','monospace'],
      }
    }
  }
}
</script>
<link rel="stylesheet" href="<?= asset('css/app.css') ?>">
<!-- Font Awesome 6 -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer">
<script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body style="background:#F2F4F7;color:#12181F;font-family:'Kalpurush','Inter',sans-serif;min-height:100vh;">

<!-- Preloader -->
<?php require view_path('partials/preloader.php'); ?>

<?php $error = flash('error'); $success = flash('success'); ?>
<?php if ($error): ?>
<div id="flash-msg" class="fixed top-4 right-4 z-[100] bg-red-50 border border-red-200 text-red-800 px-5 py-3 rounded-xl shadow-lg text-sm font-medium flex items-center gap-3 max-w-sm"><i class="fa-solid fa-triangle-exclamation text-red-500"></i> <?= e($error) ?><button onclick="document.getElementById('flash-msg').remove()" class="ml-auto hover:text-red-950"><i class="fa-solid fa-xmark"></i></button></div>
<?php endif; ?>
<?php if ($success): ?>
<div id="flash-msg" class="fixed top-4 right-4 z-[100] bg-emerald-50 border border-emerald-200 text-emerald-800 px-5 py-3 rounded-xl shadow-lg text-sm font-medium flex items-center gap-3 max-w-sm"><i class="fa-solid fa-circle-check text-emerald-500"></i> <?= e($success) ?><button onclick="document.getElementById('flash-msg').remove()" class="ml-auto hover:text-emerald-950"><i class="fa-solid fa-xmark"></i></button></div>
<?php endif; ?>

<?php
$hasFinancePerm = false;
if (!empty($user['id'])) {
    $pdoPerm = \App\Services\Database::connection();
    $stmtPerm = $pdoPerm->prepare("SELECT COUNT(*) FROM committee_members WHERE user_id = ? AND is_active = 1 AND can_manage_finance = 1 AND deleted_at IS NULL");
    $stmtPerm->execute([$user['id']]);
    $hasFinancePerm = ((int)$stmtPerm->fetchColumn() > 0);
}

$navItems = [
  ['/portal',                  'Dashboard',        'fa-solid fa-gauge-high'],
  ['/portal/id-card',          'Digital ID Card',  'fa-solid fa-id-card'],
  ['/portal/contact-requests', 'Contact Requests', 'fa-solid fa-envelope-open-text'],
  ['/portal/jobs',             'Job Postings',     'fa-solid fa-briefcase'],
  ['/portal/profile',          'My Profile',       'fa-solid fa-user-circle'],
  ['/portal/membership',       'Membership',      'fa-solid fa-id-card-clip'],
  ['/portal/stories',          'My Blogs',        'fa-solid fa-pen-to-square'],
];

if ($hasFinancePerm) {
    $navItems[] = ['/portal/financials', 'Financials', 'fa-solid fa-money-bill-transfer'];
}

$navItems[] = ['/portal/notifications','Notifications','fa-solid fa-bell'];
$navItems[] = ['/portal/settings',    'Settings',     'fa-solid fa-gear'];
?>

<div class="flex min-h-screen" x-data="{ mobileSidebarOpen: false }">

  <!-- Mobile Sidebar (Slide-over) -->
  <div x-show="mobileSidebarOpen" class="fixed inset-0 z-50 flex lg:hidden" style="display: none;">
    <!-- Backdrop -->
    <div class="fixed inset-0 bg-black/60 backdrop-blur-sm" @click="mobileSidebarOpen = false"></div>
    
    <!-- Sidebar Content -->
    <aside class="relative flex flex-col w-64 max-w-xs h-full bg-white/95 border-r border-slate-200/80 shadow-2xl backdrop-blur-xl"
           x-transition:enter="transition ease-out duration-200 transform"
           x-transition:enter-start="-translate-x-full"
           x-transition:enter-end="translate-x-0"
           x-transition:leave="transition ease-in duration-150 transform"
           x-transition:leave-start="translate-x-0"
           x-transition:leave-end="-translate-x-full">
      <div class="px-5 py-4 border-b border-slate-100 flex items-center justify-between">
        <a href="<?= url('/') ?>" class="flex items-center gap-2.5 font-semibold text-[#101820] text-[14px]">
          <img src="<?= asset('images/LOGO.png') ?>" alt="Logo" class="w-8 h-8 object-contain">
          <span>IPH Alumni</span>
        </a>
        <button @click="mobileSidebarOpen = false" class="text-slate-400 hover:text-slate-700 p-1">
          <i class="fa-solid fa-xmark text-lg"></i>
        </button>
      </div>

      <!-- User info -->
      <div class="px-5 py-4 border-b border-slate-100">
        <div class="flex items-center gap-3">
          <div class="w-10 h-10 rounded-full flex items-center justify-center font-serif font-semibold text-[14px] shrink-0"
               style="background:linear-gradient(135deg,#153548,#2F8863);color:#FAFAFA;">
            <?= initials($user['name'] ?? 'A') ?>
          </div>
          <div class="min-w-0">
            <div class="text-[13.5px] font-semibold text-[#101820] truncate"><?= e($user['name'] ?? '') ?></div>
            <div class="text-[11.5px] text-[#6B7178] truncate"><?= e($user['email'] ?? '') ?></div>
          </div>
        </div>
      </div>

      <!-- Nav -->
      <nav class="flex-1 px-3 py-4 space-y-1 overflow-y-auto">
        <?php foreach ($navItems as [$path, $label, $icon]): 
          $scriptDir = dirname($_SERVER['SCRIPT_NAME'] ?? '/public/index.php');
          $cp = str_replace($scriptDir, '', $currentPath);
          $normPath = str_replace('/public', '', $path);
          if ($normPath === '/portal') {
              $active = ($cp === '/portal' || $cp === '/portal/');
          } else {
              $active = ($cp === $normPath || str_starts_with($cp, $normPath . '/'));
          }
        ?>
        <a href="<?= url($path) ?>" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-[13.5px] transition-all <?= $active ? 'bg-slate-100 text-[#800020] font-semibold shadow-sm' : 'text-[#6B7178] hover:text-[#101820] hover:bg-slate-50' ?>">
          <i class="<?= $icon ?> text-[14px] w-4 text-center shrink-0 <?= $active ? 'text-[#800020]' : 'text-slate-400' ?>"></i>
          <span><?= $label ?></span>
        </a>
        <?php endforeach; ?>
      </nav>

      <!-- Bottom links -->
      <div class="px-3 py-4 border-t border-slate-100 space-y-1">
        <a href="<?= url('/') ?>" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-[13px] text-[#6B7178] hover:text-[#101820] hover:bg-slate-50 transition-all">
          <i class="fa-solid fa-arrow-up-right-from-square w-4 text-center"></i>
          Public Site
        </a>
        <a href="<?= url('/logout') ?>" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-[13px] text-[#6B7178] hover:text-red-600 hover:bg-red-50 transition-all">
          <i class="fa-solid fa-right-from-bracket w-4 text-center"></i>
          Logout
        </a>
      </div>
    </aside>
  </div>

  <!-- Sidebar (Desktop) -->
  <aside class="w-64 shrink-0 hidden lg:flex flex-col sticky top-0 h-screen overflow-y-auto"
         style="background:rgba(255,255,255,0.72);border-right:1px solid rgba(16,24,32,0.08);backdrop-filter:blur(18px);">
    
    <!-- Brand -->
    <div class="px-5 py-5 border-b shrink-0" style="border-color:rgba(16,24,32,0.08);">
      <a href="<?= url('/') ?>" class="flex items-center gap-2.5 font-semibold text-[#101820] text-[14px]">
        <img src="<?= asset('images/LOGO.png') ?>" alt="Logo" class="w-8 h-8 object-contain">
        <span>IPH Alumni</span>
      </a>
    </div>

    <!-- User info -->
    <div class="px-5 py-4 border-b shrink-0" style="border-color:rgba(16,24,32,0.08);">
      <div class="flex items-center gap-3">
        <div class="w-10 h-10 rounded-full flex items-center justify-center font-serif font-semibold text-[14px] shrink-0"
             style="background:linear-gradient(135deg,#153548,#2F8863);color:#FAFAFA;">
          <?= initials($user['name'] ?? 'A') ?>
        </div>
        <div class="min-w-0">
          <div class="text-[13.5px] font-semibold text-[#101820] truncate"><?= e($user['name'] ?? '') ?></div>
          <div class="text-[11.5px] text-[#6B7178] truncate"><?= e($user['email'] ?? '') ?></div>
        </div>
      </div>
    </div>

    <!-- Nav -->
    <nav class="flex-1 px-3 py-4 space-y-1 overflow-y-auto">
      <?php foreach ($navItems as [$path, $label, $icon]): 
        $scriptDir = dirname($_SERVER['SCRIPT_NAME'] ?? '/public/index.php');
        $cp = str_replace($scriptDir, '', $currentPath);
        $normPath = str_replace('/public', '', $path);
        if ($normPath === '/portal') {
            $active = ($cp === '/portal' || $cp === '/portal/');
        } else {
            $active = ($cp === $normPath || str_starts_with($cp, $normPath . '/'));
        }
      ?>
      <a href="<?= url($path) ?>" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-[13.5px] transition-all <?= $active ? 'bg-white/90 text-[#101820] font-semibold shadow-sm' : 'text-[#6B7178] hover:text-[#101820] hover:bg-white/60' ?>">
        <i class="<?= $icon ?> text-[14px] w-4 text-center shrink-0 <?= $active ? 'text-[#800020]' : 'text-slate-400' ?>"></i>
        <span><?= $label ?></span>
      </a>
      <?php endforeach; ?>
    </nav>

    <!-- Bottom links -->
    <div class="px-3 py-4 border-t space-y-1 shrink-0" style="border-color:rgba(16,24,32,0.08);">
      <a href="<?= url('/') ?>" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-[13px] text-[#6B7178] hover:text-[#101820] hover:bg-white/60 transition-all">
        <i class="fa-solid fa-arrow-up-right-from-square w-4 text-center"></i>
        Public Site
      </a>
      <a href="<?= url('/logout') ?>" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-[13px] text-[#6B7178] hover:text-red-600 hover:bg-red-50 transition-all">
        <i class="fa-solid fa-right-from-bracket w-4 text-center"></i>
        Logout
      </a>
    </div>
  </aside>

  <!-- Main -->
  <div class="flex-1 flex flex-col min-w-0">
    
    <!-- Top bar (mobile) -->
    <header class="lg:hidden flex items-center justify-between px-4 py-3 border-b bg-white/95 backdrop-blur-md border-slate-200">
      <div class="flex items-center gap-3">
        <button @click="mobileSidebarOpen = true" class="p-2 rounded-xl border border-slate-200 text-slate-700 hover:bg-slate-100">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
        </button>
        <a href="<?= url('/') ?>" class="flex items-center gap-2 font-semibold text-[#101820] text-[14px]">
          <img src="<?= asset('images/LOGO.png') ?>" alt="Logo" class="w-7 h-7 object-contain">
          IPH Alumni
        </a>
      </div>
      <a href="<?= url('/logout') ?>" class="text-[13px] font-medium text-red-600 hover:underline">Logout</a>
    </header>

    <!-- Page content -->
    <main class="flex-1 p-6 lg:p-8 w-full">
      <?= $content ?? '' ?>
    </main>
  </div>
</div>

<script>
setTimeout(() => {
  const f = document.getElementById('flash-msg');
  if (f) { f.style.cssText = 'opacity:0;transform:translateX(20px);transition:.3s'; setTimeout(() => f?.remove(), 300); }
}, 4000);
</script>
</body>
</html>
