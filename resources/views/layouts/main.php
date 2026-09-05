<?php
/**
 * Main Public Layout
 * Usage: require this file with $title, $description, $content vars set
 */
$appName   = __('আইপিএইচ অ্যালামনাই অ্যাসোসিয়েশন', env('APP_NAME', 'IPH Alumni Association'));
$appUrl    = env('APP_URL', 'http://localhost/alumni/public');
$title     = isset($title) ? e($title) . ' — ' . $appName : $appName;
$desc      = $description ?? 'IPH Alumni Association — Institute of Public Health Alumni Network';
$bodyClass = $bodyClass ?? '';

$reqUri = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);
$appUrlPath = parse_url(env('APP_URL', 'http://localhost/alumni/public'), PHP_URL_PATH);
if ($appUrlPath && $appUrlPath !== '/') {
    $basePath = rtrim($appUrlPath, '/');
    if (str_starts_with($reqUri, $basePath)) {
        $reqUri = substr($reqUri, strlen($basePath));
    }
}
$isHome = ($reqUri === '' || $reqUri === '/' || str_ends_with($reqUri, '/public') || str_ends_with($reqUri, '/public/index.php'));
if ($isHome) {
    $bodyClass .= ' is-home-page';
    if (!empty($_COOKIE['iph_hero_intro_seen'])) {
        $bodyClass .= ' home-intro-revealed';
    } else {
        $bodyClass .= ' home-intro-playing';
    }
}
?>
<?php
$currentLocale = $_SESSION['lang'] ?? 'bn';
?>
<!DOCTYPE html>
<html lang="<?= $currentLocale ?>">
<head>
<meta charset="UTF-8">
<script>
  try {
    if (localStorage.getItem('iph_hero_intro_seen') === '1') {
      document.documentElement.classList.add('hero-intro-seen');
    }
  } catch(e) {}
</script>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= $title ?></title>
<meta name="theme-color" content="#800020">
<link rel="manifest" href="<?= asset('manifest.webmanifest') ?>">
<meta name="mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
<meta name="apple-mobile-web-app-title" content="IPH Alumni">
<link rel="icon" type="image/png" href="<?= asset('images/LOGO.png') ?>">
<link rel="apple-touch-icon" href="<?= asset('images/LOGO.png') ?>">

<!-- Fonts -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,400;9..144,500;9..144,600;9..144,700&family=Inter:wght@300;400;500;600;700&family=IBM+Plex+Mono:wght@400;500&display=swap" rel="stylesheet">
<link href="https://fonts.maateen.me/kalpurush/font.css" rel="stylesheet">

<!-- Tailwind CSS CDN -->
<script src="https://cdn.tailwindcss.com"></script>
<script>
tailwind.config = {
  theme: {
    extend: {
      fontFamily: {
        sans: ['Kalpurush', 'Inter', 'system-ui', 'sans-serif'],
        serif: ['Kalpurush', 'Fraunces', 'Georgia', 'serif'],
        mono: ['IBM Plex Mono', 'Consolas', 'monospace'],
      },
      colors: {
        ink: '#101820',
        teal: { deep: '#0F2A3D', mid: '#153548' },
        gold: { DEFAULT: '#800020', soft: '#A22638', light: '#C0394B', pale: '#E58E97' },
        sage: { DEFAULT: '#2F8863', light: '#4E9C81' },
        ivory: { DEFAULT: '#12181F', dim: '#6B7178' },
      },
    }
  }
}
</script>

<!-- App CSS -->
<link rel="stylesheet" href="<?= asset('css/app.css') ?>">

<!-- Font Awesome 6 Free -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer">

<!-- Alpine.js -->
<script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

<!-- htmx -->
<script src="https://unpkg.com/htmx.org@1.9.10"></script>

<?php
$canonicalUrl = current_url();
$metaImage = $ogImage ?? asset('images/LOGO.png');
$keywordsStr = $metaKeywords ?? 'IPH Alumni Association, Institute of Public Health, IPH Graduates, Alumni Directory, Public Health Bangladesh, IPH Mohakhali';
?>
<!-- Primary SEO Meta Tags -->
<meta name="description" content="<?= e($desc) ?>">
<meta name="keywords" content="<?= e($keywordsStr) ?>">
<meta name="author" content="IPH Alumni Association">
<meta name="robots" content="index, follow, max-image-preview:large, max-snippet:-1, max-video-preview:-1">
<link rel="canonical" href="<?= e($canonicalUrl) ?>">

<!-- Open Graph / Facebook / WhatsApp SEO -->
<meta property="og:site_name" content="IPH Alumni Association">
<meta property="og:type" content="<?= $ogType ?? 'website' ?>">
<meta property="og:url" content="<?= e($canonicalUrl) ?>">
<meta property="og:title" content="<?= $title ?>">
<meta property="og:description" content="<?= e($desc) ?>">
<meta property="og:image" content="<?= e($metaImage) ?>">
<meta property="og:image:width" content="1200">
<meta property="og:image:height" content="630">
<meta property="og:locale" content="<?= $currentLocale === 'bn' ? 'bn_BD' : 'en_US' ?>">

<!-- Twitter / X Card SEO -->
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:url" content="<?= e($canonicalUrl) ?>">
<meta name="twitter:title" content="<?= $title ?>">
<meta name="twitter:description" content="<?= e($desc) ?>">
<meta name="twitter:image" content="<?= e($metaImage) ?>">

<!-- AI Crawling & Geo Targeting Metadata -->
<meta name="geo.region" content="BD-13">
<meta name="geo.placename" content="Mohakhali, Dhaka, Bangladesh">
<meta name="geo.position" content="23.777176;90.405423">
<meta name="ICBM" content="23.777176, 90.405423">

<!-- JSON-LD Structured Data (Schema.org Organization & WebSite) -->
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@graph": [
    {
      "@type": "EducationalOrganization",
      "@id": "<?= e(url('/')) ?>/#organization",
      "name": "IPH Alumni Association",
      "alternateName": "Institute of Public Health Alumni Network",
      "url": "<?= e(url('/')) ?>",
      "logo": {
        "@type": "ImageObject",
        "url": "<?= e(asset('images/LOGO.png')) ?>"
      },
      "foundingDate": "2015",
      "address": {
        "@type": "PostalAddress",
        "streetAddress": "Institute of Public Health, Mohakhali",
        "addressLocality": "Dhaka",
        "postalCode": "1212",
        "addressCountry": "BD"
      },
      "contactPoint": {
        "@type": "ContactPoint",
        "telephone": "+8801811332204",
        "contactType": "customer service",
        "email": "info@iphalumni.org"
      },
      "sameAs": [
        "https://facebook.com",
        "https://linkedin.com"
      ]
    },
    {
      "@type": "WebSite",
      "@id": "<?= e(url('/')) ?>/#website",
      "url": "<?= e(url('/')) ?>",
      "name": "IPH Alumni Association",
      "publisher": { "@id": "<?= e(url('/')) ?>/#organization" },
      "inLanguage": "<?= $currentLocale ?>"
    }
    <?php if (isset($jsonLdSchema)): ?>,
    <?= is_array($jsonLdSchema) ? json_encode($jsonLdSchema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) : $jsonLdSchema ?>
    <?php endif; ?>
  ]
}
</script>
</head>
<body class="<?= $bodyClass ?>" style="background: radial-gradient(1100px 560px at 12% -8%, rgba(128,0,32,0.08), transparent 60%), radial-gradient(900px 520px at 102% 4%, rgba(47,136,99,0.07), transparent 55%), #FAFAFA; color: #12181F; font-family: <?= $currentLocale === 'bn' ? "'Kalpurush', sans-serif" : "'Inter', sans-serif" ?>; min-height: 100vh; overflow-x: hidden;">

<!-- Preloader -->
<?php require view_path('partials/preloader.php'); ?>

<!-- Flash Messages -->
<?php $error = flash('error'); $success = flash('success'); ?>
<?php if ($error): ?>
<div id="flash-msg" class="fixed top-4 right-4 z-[100] bg-red-50 border border-red-200 text-red-800 px-5 py-3 rounded-xl shadow-lg text-sm font-medium flex items-center gap-3 max-w-sm">
  <svg class="w-4 h-4 shrink-0 text-red-500" fill="currentColor" viewBox="0 0 20 20"><circle cx="10" cy="10" r="10" fill="#fee2e2"/><path d="M10 6v4m0 4h.01" stroke="#ef4444" stroke-width="1.5" stroke-linecap="round"/></svg>
  <?= e($error) ?>
  <button onclick="document.getElementById('flash-msg').remove()" class="ml-auto text-red-400 hover:text-red-600">✕</button>
</div>
<?php endif; ?>
<?php if ($success): ?>
<div id="flash-msg" class="fixed top-4 right-4 z-[100] bg-emerald-50 border border-emerald-200 text-emerald-800 px-5 py-3 rounded-xl shadow-lg text-sm font-medium flex items-center gap-3 max-w-sm">
  <svg class="w-4 h-4 shrink-0 text-emerald-500" fill="none" viewBox="0 0 20 20"><circle cx="10" cy="10" r="9" stroke="#10b981" stroke-width="1.5"/><path d="M6 10l3 3 5-5" stroke="#10b981" stroke-width="1.5" stroke-linecap="round"/></svg>
  <?= e($success) ?>
  <button onclick="document.getElementById('flash-msg').remove()" class="ml-auto text-emerald-400 hover:text-emerald-600">✕</button>
</div>
<?php endif; ?>

<!-- Navbar -->
<?php require view_path('partials/navbar.php'); ?>

<!-- Main Content -->
<main>
<?= $content ?? '' ?>
</main>

<!-- Footer -->
<?php require view_path('partials/footer.php'); ?>

<!-- Google Translate Partial (Top bar hidden, clean toggle) -->
<?php require view_path('partials/google_translate.php'); ?>

<!-- Mobile Bottom Navigation Dock & PWA Controller -->
<?php require view_path('partials/mobile_bottom_nav.php'); ?>

<script>
// Auto-dismiss flash after 4s
setTimeout(() => {
  const f = document.getElementById('flash-msg');
  if (f) f.style.cssText = 'opacity:0;transform:translateX(20px);transition:.3s';
  setTimeout(() => f?.remove(), 300);
}, 4000);
</script>
</body>
</html>
