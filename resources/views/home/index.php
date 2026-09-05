<?php
/**
 * Homepage View — IPH Alumni Association
 * Variables: $stats, $alumni_featured, $events, $news, $successStories
 */
$appName    = function_exists('env') ? env('APP_NAME', 'IPH Alumni Association') : 'IPH Alumni Association';
$appFounded = function_exists('env') ? env('APP_FOUNDED', '2025') : '2025';

$currentYear = (int)date('Y');
$startYear   = 2015;
$diffYears   = max(1, $currentYear - $startYear);
$headlineLine1Bn = to_bn_number($diffYears) . ' বছরের শিক্ষার্থী সাফল্য (' . to_bn_number($startYear) . '–' . to_bn_number($currentYear) . ')।';
$headlineLine1En = $diffYears . ' Years of Student Legacy (' . $startYear . '–' . $currentYear . ').';
?>

<style>
/* ── Existing helpers ── */
.hover-lift { transition: transform 0.2s ease, box-shadow 0.2s ease; }
.hover-lift:hover { transform: translateY(-4px); box-shadow: 0 16px 40px -12px rgba(16,24,32,0.15); }
@keyframes laser-flow { to { stroke-dashoffset: -40; } }
.laser-anim { animation: laser-flow 2s linear infinite; }
@keyframes shimmer { from{background-position:-200% 0;} to{background-position:200% 0;} }
.shimmer-btn {
  background-size:250% 100%;
  background-image: linear-gradient(120deg, #A22638 0%, #800020 35%, #C0394B 50%, #800020 65%, #A22638 100%);
  animation: shimmer 2.8s linear infinite;
}

/* ── Typewriter Headline Styles ── */
@keyframes heroCursorBlink {
  0%, 100% { opacity: 1; }
  50% { opacity: 0; }
}
.hero-type-cursor {
  animation: heroCursorBlink 0.75s step-start infinite;
  display: inline-block;
  line-height: 1;
}
.hero-type-cursor.cursor-soft {
  animation: heroCursorBlink 1.4s ease-in-out infinite;
  opacity: 0.6;
}

/* ── Home Video Hero & Intro Animation Controller ── */
#main-header {
  opacity: 0 !important;
  transform: translateY(-100%) !important;
  pointer-events: none !important;
  transition: opacity 0.85s cubic-bezier(0.16, 1, 0.3, 1), transform 0.85s cubic-bezier(0.16, 1, 0.3, 1) !important;
}
html.hero-intro-seen #main-header,
body.home-intro-revealed #main-header {
  opacity: 1 !important;
  transform: translateY(0) !important;
  pointer-events: auto !important;
}

#hero-section {
  position: relative;
  width: 100%;
  height: 100vh;
  min-height: 640px;
  overflow: hidden;
  margin-top: -85px;
  padding-top: 85px;
  background-color: #0F172A;
}

#hero-bg-video {
  position: absolute;
  inset: 0;
  width: 100%;
  height: 100%;
  object-fit: cover;
  pointer-events: none;
  user-select: none;
  z-index: 0;
}

#hero-overlay {
  position: absolute;
  inset: 0;
  z-index: 1;
  pointer-events: none;
  transition: background 1.2s ease, backdrop-filter 1.2s ease, -webkit-backdrop-filter 1.2s ease;
}
body.home-intro-playing #hero-overlay {
  background: linear-gradient(180deg, rgba(15,23,42,0.2) 0%, rgba(15,23,42,0.1) 50%, rgba(15,23,42,0.35) 100%);
}
html.hero-intro-seen #hero-overlay,
body.home-intro-revealed #hero-overlay {
  background: linear-gradient(180deg, rgba(15,23,42,0.78) 0%, rgba(15,23,42,0.52) 42%, rgba(15,23,42,0.88) 100%);
  backdrop-filter: blur(2px);
  -webkit-backdrop-filter: blur(2px);
}

#hero-content-wrap {
  opacity: 0 !important;
  transform: translateY(35px) !important;
  pointer-events: none !important;
  transition: opacity 0.85s ease 0.15s, transform 0.9s cubic-bezier(0.16, 1, 0.3, 1) 0.15s;
}
html.hero-intro-seen #hero-content-wrap,
body.home-intro-revealed #hero-content-wrap {
  opacity: 1 !important;
  transform: translateY(0) !important;
  pointer-events: auto !important;
}

@keyframes heroItemFadeUp {
  from {
    opacity: 0;
    transform: translateY(28px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

body.home-intro-revealed .hero-anim-item {
  animation: heroItemFadeUp 0.85s cubic-bezier(0.16, 1, 0.3, 1) both;
}
html.hero-intro-seen .hero-anim-item {
  opacity: 1 !important;
  transform: translateY(0) !important;
  animation: none !important;
}
body.home-intro-revealed .hero-delay-1 { animation-delay: 0.1s; }
body.home-intro-revealed .hero-delay-2 { animation-delay: 0.25s; }
body.home-intro-revealed .hero-delay-3 { animation-delay: 0.4s; }
body.home-intro-revealed .hero-delay-4 { animation-delay: 0.55s; }
body.home-intro-revealed .hero-delay-5 { animation-delay: 0.7s; }
</style>

<script>
  try {
    const heroKey = 'iph_hero_intro_seen_v3';
    if (localStorage.getItem(heroKey) === '1' || document.cookie.indexOf(heroKey + '=1') !== -1) {
      document.documentElement.classList.add('hero-intro-seen');
      document.body.classList.add('home-intro-revealed');
    } else {
      document.body.classList.add('home-intro-playing');
    }
  } catch(e) {}
</script>

<!-- ══════════════════════════ FULL-VIEW VIDEO HERO SECTION ════════════════════════════════ -->
<section id="hero-section" class="flex flex-col justify-center items-center select-none">

  <!-- Background Video (No buttons, no navigation, muted, plays to end scene then pauses as image) -->
  <video id="hero-bg-video"
         autoplay
         muted
         playsinline
         webkit-playsinline
         x5-playsinline
         tabindex="-1"
         preload="auto"
         disablePictureInPicture>
    <source src="<?= asset('storage/Index_Hero_video.mp4') ?>?v=20260905" type="video/mp4">
    <source src="<?= asset('videos/Index_Hero_video.mp4') ?>?v=20260905" type="video/mp4">
  </video>

  <!-- Cinematic Dynamic Backdrop Overlay -->
  <div id="hero-overlay"></div>

  <!-- Hero Content (Animates in once video reaches the end frame) -->
  <div id="hero-content-wrap" class="relative z-10 max-w-5xl mx-auto px-6 text-center w-full py-10">

    <!-- Live Badge -->
    <div class="hero-anim-item hero-delay-1 inline-flex items-center gap-2.5 px-4 py-2 rounded-full mb-6 select-none border border-white/20 bg-black/35 backdrop-blur-md shadow-xl">
      <span class="relative flex h-2.5 w-2.5">
        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
        <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-emerald-400"></span>
      </span>
      <span class="font-mono text-[11px] sm:text-[12px] tracking-widest text-emerald-300 font-bold uppercase">
        <?= __('প্রতিষ্ঠিত ২০২৫', 'Est. 2025') ?> &nbsp;·&nbsp; <?= __('আইপিএইচ এলামনাই এসোসিয়েশন', 'IPH Alumni Association') ?>
      </span>
    </div>

    <!-- Main Headline -->
    <h1 class="hero-anim-item hero-delay-2 font-serif text-[clamp(28px,4.5vw,56px)] leading-[1.2] font-bold tracking-tight text-white mb-6 drop-shadow-2xl">
      <span class="block"><?= __($headlineLine1Bn, $headlineLine1En) ?></span>
      <span class="inline-flex items-center justify-center flex-wrap mt-1 min-h-[1.2em]">
        <span id="hero-typing-target" class="bg-gradient-to-r from-amber-300 via-rose-300 to-amber-200 bg-clip-text text-transparent inline-block" data-type-text="<?= htmlspecialchars(__('এক অ্যালামনাই পরিবার।', 'One Alumni Family.')) ?>"></span><span id="hero-typing-cursor" class="text-amber-300 hero-type-cursor font-light -ml-0.5 select-none inline-block">|</span>
      </span>
    </h1>

    <!-- Subtitle -->
    <p class="hero-anim-item hero-delay-3 text-[clamp(15px,1.3vw,18.5px)] leading-relaxed text-slate-200/95 max-w-2xl mx-auto mb-9 font-normal drop-shadow">
      <?= __('বিশ্বজুড়ে ছড়িয়ে থাকা আইপিএইচ গ্র্যাজুয়েটদের সাথে যুক্ত হন — পুরনো বন্ধু খুঁজুন, আপনার পথচলার গল্প ভাগ করুন, এবং সেই নেটওয়ার্কের অংশ থাকুন যা আপনার ক্যারিয়ার গড়েছে।',
            'Connect with IPH graduates from around the world — find old batchmates, share your story, and stay part of the network that shaped your career.') ?>
    </p>

    <!-- CTA Buttons -->
    <div class="hero-anim-item hero-delay-4 flex flex-row items-center justify-center gap-3 sm:gap-4 mb-10">
      <a href="<?= url('/register') ?>"
         class="shimmer-btn inline-flex items-center justify-center gap-2 px-6 sm:px-8 py-3.5 sm:py-4 rounded-2xl text-[14px] sm:text-[16px] font-semibold text-white transition-all hover:-translate-y-1 hover:shadow-2xl active:scale-95 shrink-0"
         style="box-shadow: 0 10px 30px -6px rgba(128,0,32,0.6);">
        <i class="fa-solid fa-user-plus text-[13px] sm:text-[14px]"></i>
        <span><?= __('অ্যালামনাই হিসেবে যোগ দিন', 'Join as Alumni') ?></span>
        <i class="fa-solid fa-arrow-right text-[12px] sm:text-[13px] hidden xs:inline-block"></i>
      </a>
      <a href="<?= url('/directory') ?>"
         class="inline-flex items-center justify-center gap-2 px-6 sm:px-8 py-3.5 sm:py-4 rounded-2xl text-[14px] sm:text-[16px] font-semibold text-white bg-white/10 hover:bg-white/20 border border-white/30 backdrop-blur-md transition-all hover:-translate-y-1 hover:shadow-lg active:scale-95 shrink-0">
        <i class="fa-solid fa-address-book text-[13px] sm:text-[14px] text-amber-300"></i>
        <span><?= __('ডিরেক্টরি দেখুন', 'Browse Directory') ?></span>
      </a>
    </div>

    <?php 
      $isBnLang = !session('locale') || session('locale') === 'bn' || session('lang') === 'bn';
      $memberCountDisplay  = $isBnLang ? to_bn_number($stats['total']) : number_format($stats['total']);
      $countryCountDisplay = $isBnLang ? to_bn_number($stats['countries']) : number_format($stats['countries']);
    ?>

    <!-- Floating Quick Stats Bar -->
    <div class="hero-anim-item hero-delay-5 inline-flex flex-wrap items-center justify-center gap-4 sm:gap-8 px-6 sm:px-8 py-3.5 rounded-2xl bg-black/40 border border-white/15 backdrop-blur-xl shadow-2xl text-white">
      <div class="flex items-center gap-2.5">
        <i class="fa-solid fa-users text-amber-400 text-[18px]"></i>
        <div class="text-left">
          <div class="font-bold text-[17px] sm:text-[19px] leading-tight font-mono text-white"><?= $memberCountDisplay ?>+</div>
          <div class="text-[10px] sm:text-[11px] text-slate-300 font-mono uppercase tracking-wider"><?= __('নিবন্ধিত সদস্য', 'Registered Members') ?></div>
        </div>
      </div>
      <div class="hidden sm:block w-px h-7 bg-white/20"></div>
      <div class="flex items-center gap-2.5">
        <i class="fa-solid fa-globe text-emerald-400 text-[18px]"></i>
        <div class="text-left">
          <div class="font-bold text-[17px] sm:text-[19px] leading-tight font-mono text-white"><?= $countryCountDisplay ?>+</div>
          <div class="text-[10px] sm:text-[11px] text-slate-300 font-mono uppercase tracking-wider"><?= __('দেশব্যাপী বিস্তার', 'Global Reach') ?></div>
        </div>
      </div>
      <div class="hidden sm:block w-px h-7 bg-white/20"></div>
      <div class="flex items-center gap-2.5">
        <i class="fa-solid fa-certificate text-rose-400 text-[18px]"></i>
        <div class="text-left">
          <div class="font-bold text-[17px] sm:text-[19px] leading-tight font-mono text-white"><?= __('১০০%', '100%') ?></div>
          <div class="text-[10px] sm:text-[11px] text-slate-300 font-mono uppercase tracking-wider"><?= __('ভেরিফাইড আইডি', 'Verified ID') ?></div>
        </div>
      </div>
    </div>

  </div>

</section>

<!-- ── Video Hero Controller & Statistics Observer ── -->
<script>
(function(){
  const heroVideo = document.getElementById('hero-bg-video');
  let introRevealed = false;

  // ── Typewriter Effect for "এক অ্যালামনাই পরিবার।" ──
  const typeTarget = document.getElementById('hero-typing-target');
  const typeCursor = document.getElementById('hero-typing-cursor');
  const textToType = typeTarget ? (typeTarget.getAttribute('data-type-text') || '<?= addslashes(__('এক অ্যালামনাই পরিবার।', 'One Alumni Family.')) ?>') : '';
  let typeStarted = false;

  function startTypewriter() {
    if (typeStarted || !typeTarget || !textToType) return;
    typeStarted = true;

    // Use Intl.Segmenter for flawless Bengali conjunct/diacritic cluster segmentation
    let graphemes;
    if (typeof Intl !== 'undefined' && Intl.Segmenter) {
      const segmenter = new Intl.Segmenter('bn', { granularity: 'grapheme' });
      graphemes = Array.from(segmenter.segment(textToType), s => s.segment);
    } else {
      graphemes = Array.from(textToType);
    }

    typeTarget.textContent = '';
    let idx = 0;
    const speed = 90; // ms per Bengali grapheme

    function step() {
      if (idx < graphemes.length) {
        typeTarget.textContent += graphemes[idx];
        idx++;
        setTimeout(step, speed);
      } else if (typeCursor) {
        setTimeout(function(){
          typeCursor.classList.add('cursor-soft');
        }, 3000);
      }
    }
    setTimeout(step, 180);
  }

  // Check if intro has already been seen on a previous visit
  const introKey = 'iph_hero_intro_seen_v3';
  let hasSeenIntro = false;
  let hasPlayed = false;
  try {
    hasSeenIntro = localStorage.getItem(introKey) === '1' || document.cookie.indexOf(introKey + '=1') !== -1;
  } catch(e) {}

  window.revealHeroIntro = function(isInstant, markAsSeen) {
    if (introRevealed) return;
    introRevealed = true;

    if (markAsSeen) {
      try {
        localStorage.setItem(introKey, '1');
        document.cookie = introKey + "=1; path=/; max-age=31536000; SameSite=Lax";
      } catch(e) {}
    }

    if (heroVideo) {
      try {
        heroVideo.pause();
      } catch(e) {}
    }

    document.body.classList.remove('home-intro-playing');
    document.body.classList.add('home-intro-revealed');
    document.documentElement.classList.add('hero-intro-seen');

    // Trigger typewriter animation once headline appears
    setTimeout(startTypewriter, isInstant ? 200 : 500);
  };

  if (hasSeenIntro) {
    // ── Returning Visitor: Intro already seen, hold last scene as background image ──
    window.revealHeroIntro(true, false);
    if (heroVideo) {
      heroVideo.removeAttribute('autoplay');
      heroVideo.pause();
      const setLastFrame = function() {
        try {
          if (heroVideo.duration > 0) {
            heroVideo.currentTime = Math.max(0, heroVideo.duration - 0.08);
          } else {
            heroVideo.currentTime = 7.92;
          }
          heroVideo.pause();
        } catch(e) {}
      };
      if (heroVideo.readyState >= 1) {
        setLastFrame();
      } else {
        heroVideo.addEventListener('loadedmetadata', setLastFrame, { once: true });
      }
    }
  } else {
    // ── First Time Visitor: Play full intro video once, then hold last scene as image ──
    document.body.classList.add('home-intro-playing');

    if (heroVideo) {
      heroVideo.muted = true;
      heroVideo.defaultMuted = true;
      heroVideo.playsInline = true;

      // 1. When video ends naturally
      heroVideo.addEventListener('ended', function() {
        window.revealHeroIntro(false, true);
      });

      // 2. Near end scene safeguard (pauses exactly on final frame)
      heroVideo.addEventListener('timeupdate', function() {
        if (heroVideo.currentTime > 0.4) hasPlayed = true;
        if (heroVideo.duration > 0 && heroVideo.currentTime >= heroVideo.duration - 0.12) {
          window.revealHeroIntro(false, true);
        }
      });

      // 3. Initiate playback smoothly across all mobile & desktop browsers
      function startVideoPlay() {
        heroVideo.muted = true;
        const playPromise = heroVideo.play();
        if (playPromise !== undefined) {
          playPromise.then(function() {
            hasPlayed = true;
          }).catch(function() {
            // Mobile browser restricted autoplay before first touch:
            const onFirstTouch = function() {
              heroVideo.play().then(function(){ hasPlayed = true; }).catch(function(){});
              window.removeEventListener('touchstart', onFirstTouch);
              window.removeEventListener('click', onFirstTouch);
            };
            window.addEventListener('touchstart', onFirstTouch, { passive: true, once: true });
            window.addEventListener('click', onFirstTouch, { once: true });
          });
        }
      }

      if (heroVideo.readyState >= 2) {
        startVideoPlay();
      } else {
        heroVideo.addEventListener('canplay', startVideoPlay, { once: true });
        heroVideo.addEventListener('loadeddata', startVideoPlay, { once: true });
      }

      heroVideo.addEventListener('error', function() {
        window.revealHeroIntro(false, false);
      });

      // Fallback only after 14s in case cellular network is very slow
      setTimeout(function() {
        if (!introRevealed) {
          window.revealHeroIntro(false, hasPlayed);
        }
      }, 14000);
    } else {
      window.revealHeroIntro(false, true);
    }
  }

  /* COUNT-UP OBSERVER (for stats across page) */
  const countEls = document.querySelectorAll('[data-count]');
  if ('IntersectionObserver' in window && countEls.length > 0) {
    const obs = new IntersectionObserver(entries => {
      entries.forEach(e => {
        if (!e.isIntersecting) return;
        obs.unobserve(e.target);
        const el = e.target, target = parseInt(el.dataset.count, 10), suf = el.dataset.suffix || '';
        const dur = 1400, start = performance.now();
        function tick(now) {
          const p = Math.min((now - start) / dur, 1), ease = 1 - Math.pow(1 - p, 3);
          el.textContent = Math.floor(ease * target) + suf;
          if (p < 1) requestAnimationFrame(tick); else el.textContent = target + suf;
        }
        requestAnimationFrame(tick);
      });
    }, { threshold: 0.5 });
    countEls.forEach(el => obs.observe(el));
  }
})();
</script>

<!-- ══════════════════════════ STAT CARDS ════════════════════════════════ -->
<div class="max-w-7xl mx-auto px-6 pb-12">
  <div class="grid grid-cols-2 lg:grid-cols-4 gap-3">
    <?php
    $upcomingCount = !empty($events) ? count($events) : 0;
    $statItems = [
      ['icon' => '<i class="fa-solid fa-user-check text-xl" style="color:#800020;"></i>',    'num' => $isBnLang ? to_bn_number($stats['total']) : number_format($stats['total']),  'label' => __('ভেরিফাইড অ্যালামনাই', 'Verified alumni'),       'accent' => '#800020', 'link' => url('/directory')],
      ['icon' => '<i class="fa-solid fa-earth-asia text-xl" style="color:#2F8863;"></i>',     'num' => ($isBnLang ? to_bn_number($stats['countries']) : $stats['countries']) . '+',       'label' => __('দেশে সক্রিয় সদস্য', 'Countries represented'), 'accent' => '#2F8863', 'link' => url('/directory')],
      ['icon' => '<i class="fa-solid fa-calendar-check text-xl" style="color:#A22638;"></i>', 'num' => ($isBnLang ? to_bn_number($upcomingCount) : $upcomingCount) . '+',             'label' => __('আসন্ন ইভেন্ট', 'Upcoming events'),         'accent' => '#A22638', 'link' => url('/events')],
      ['icon' => '<i class="fa-solid fa-graduation-cap text-xl" style="color:#800020;"></i>', 'num' => $isBnLang ? to_bn_number($stats['batches']) : $stats['batches'],               'label' => __('গ্র্যাজুয়েটিং ব্যাচ', 'Graduating batches'),  'accent' => '#800020', 'link' => url('/directory')],
    ];
    foreach ($statItems as $s):
    ?>
    <a href="<?= $s['link'] ?>" class="p-5 rounded-2xl group hover-lift cursor-pointer block"
       style="background:rgba(255,255,255,0.72);border:1px solid rgba(16,24,32,0.07);backdrop-filter:blur(14px);">
      <div class="mb-2"><?= $s['icon'] ?></div>
      <div class="font-serif text-[28px] font-semibold leading-none group-hover:scale-105 transition-transform" style="color:<?= $s['accent'] ?>;">
        <?= $s['num'] ?>
      </div>
      <div class="text-[12.5px] text-[#9CA3AF] mt-1.5"><?= $s['label'] ?></div>
    </a>
    <?php endforeach; ?>
  </div>
</div>

<!-- ════════════════════════ FEATURED ALUMNI ════════════════════════════ -->
<section id="featured-alumni" class="max-w-7xl mx-auto px-6 py-16">
  <div class="flex justify-between items-end gap-5 mb-10 flex-wrap">
    <div>
      <div class="inline-flex items-center gap-2 px-3.5 py-1 rounded-full text-[11px] font-mono font-semibold tracking-wider uppercase mb-3"
           style="background:rgba(212,165,74,0.12);color:#B45309;border:1px solid rgba(212,165,74,0.3);">
        <i class="fa-solid fa-star text-amber-500 text-[10px]"></i>
        <?= __('বিশিষ্ট অ্যালামনাই', 'Featured Alumni') ?>
      </div>
      <h2 class="font-serif text-[clamp(26px,3.5vw,36px)] font-semibold text-[#101820] leading-tight">
        <?= __('আমাদের গর্বিত ও অগ্রণী অ্যালামনাইবৃন্দ', 'Our Proud & Distinguished Alumni') ?>
      </h2>
      <p class="text-[14.5px] text-[#6B7178] mt-2 max-w-xl">
        <?= __('জনস্বাস্থ্য, চিকিৎসা ও বিভিন্ন ক্ষেত্রে সফলতার সাথে অবদান রাখা আইপিএইচ প্রাক্তন শিক্ষার্থী ও প্রথিতযশা পেশাজীবীগণ।',
              'Celebrating the legacy, leadership, and accomplishments of Institute of Public Health graduates.') ?>
      </p>
    </div>
    <div class="flex items-center gap-3">
      <a href="<?= url('/directory?is_featured=1') ?>"
         class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl text-[13px] font-semibold text-amber-900 bg-amber-50 border border-amber-200 hover:bg-amber-100 transition-all">
        <i class="fa-solid fa-star text-amber-500"></i>
        <?= __('সকল বিশিষ্ট অ্যালামনাই', 'View All Featured') ?>
      </a>
      <a href="<?= url('/directory') ?>"
         class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl text-[13px] font-semibold text-[#800020] bg-[#800020]/5 border border-[#800020]/20 hover:bg-[#800020]/10 transition-all">
        <?= __('সম্পূর্ণ ডিরেক্টরি', 'Full Directory') ?> →
      </a>
    </div>
  </div>

  <?php if (empty($alumni_featured)): ?>
  <div class="text-center py-16 text-[#9CA3AF] text-[14px] rounded-3xl border border-gray-100 bg-white/60">
    <?= __('অ্যালামনাই প্রোফাইলগুলো ভেরিফাই হওয়ার পর এখানে দেখা যাবে।', 'Featured alumni profiles will appear here.') ?>
  </div>
  <?php else: ?>
  <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
    <?php foreach ($alumni_featured as $alum): ?>
    <div class="relative p-6 rounded-3xl flex flex-col justify-between group transition-all duration-300 hover:-translate-y-1.5 hover:shadow-xl hover:border-amber-500/30"
         style="background:rgba(255,255,255,0.85);border:1px solid rgba(16,24,32,0.08);backdrop-filter:blur(16px);">
      
      <!-- Top Badges -->
      <div class="flex items-center justify-between gap-2 mb-4">
        <?php if (!empty($alum['is_featured'])): ?>
        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10.5px] font-bold bg-amber-500/15 text-amber-700 border border-amber-500/30">
          <i class="fa-solid fa-star text-[9px] text-amber-500"></i> <?= __('Featured', 'Featured') ?>
        </span>
        <?php else: ?>
        <span class="inline-flex items-center gap-1 font-mono text-[10px] text-[#2F8863] px-2.5 py-0.5 rounded-full bg-emerald-50 border border-emerald-200">
          <span class="w-1.5 h-1.5 rounded-full bg-[#2F8863]"></span><?= __('Verified', 'Verified') ?>
        </span>
        <?php endif; ?>

        <?php if (!empty($alum['batch_year'])): ?>
        <span class="text-[11px] font-mono px-2 py-0.5 rounded-md bg-gray-100 text-gray-600">
          <?= __('ব্যাচ ', 'Batch ') . $alum['batch_year'] ?>
        </span>
        <?php endif; ?>
      </div>

      <!-- Avatar & Info -->
      <div class="text-center mb-5">
        <div class="relative w-20 h-20 rounded-full mx-auto mb-3.5 p-1 border-2 <?= !empty($alum['is_featured']) ? 'border-amber-400/60 shadow-amber-500/20' : 'border-gray-100' ?> shadow-md">
          <div class="w-full h-full rounded-full overflow-hidden flex items-center justify-center font-serif font-bold text-[22px] text-white"
               style="background:linear-gradient(135deg,#153548,#2F8863);">
            <?php if (!empty($alum['avatar'])): ?>
              <img src="<?= avatar_url($alum['avatar']) ?>" alt="<?= e($alum['name'] ?? '') ?>" class="w-full h-full object-cover" onerror="this.onerror=null; this.parentElement.innerHTML='<?= initials($alum['name'] ?? 'A') ?>';">
            <?php else: ?>
              <?= initials($alum['name'] ?? 'A') ?>
            <?php endif; ?>
          </div>
        </div>

        <h3 class="font-serif font-semibold text-[17px] text-[#101820] group-hover:text-[#800020] transition-colors line-clamp-1">
          <?= e($alum['name'] ?? '') ?>
        </h3>

        <?php if (!empty($alum['job_title'])): ?>
        <p class="text-[13px] font-medium text-[#800020] mt-1 line-clamp-1">
          <?= e($alum['job_title']) ?>
        </p>
        <?php endif; ?>

        <?php if (!empty($alum['organization'])): ?>
        <p class="text-[12px] text-[#6B7178] mt-0.5 line-clamp-1">
          <i class="fa-solid fa-building text-[10px] text-gray-400 mr-1"></i>
          <?= e($alum['organization']) ?>
        </p>
        <?php endif; ?>
      </div>

      <!-- Bottom Actions -->
      <div class="pt-3 border-t border-gray-100 flex items-center gap-2">
        <a href="<?= url('/directory/' . $alum['id']) ?>"
           class="flex-1 py-2 rounded-xl text-center text-[12.5px] font-semibold text-[#800020] bg-[#800020]/5 hover:bg-[#800020] hover:text-white transition-all">
          <?= __('প্রোফাইল দেখুন', 'View Profile') ?>
        </a>
        <a href="<?= url('/directory/' . $alum['id']) ?>#contact-request"
           class="px-3 py-2 rounded-xl text-center text-[12px] font-medium text-gray-600 bg-gray-100 hover:bg-gray-200 transition-all"
           title="<?= __('যোগাযোগের রিকোয়েস্ট পাঠান', 'Request Contact') ?>">
          <i class="fa-regular fa-paper-plane"></i>
        </a>
      </div>

    </div>
    <?php endforeach; ?>
  </div>
  <?php endif; ?>
</section>

<!-- ══════════════════════ BLOGS & ARTICLES ════════════════════════════════ -->
<section id="stories" class="max-w-7xl mx-auto px-6 py-14">
  <!-- Soft divider line -->
  <div class="h-px mb-14 bg-gradient-to-r from-transparent via-[#800020]/20 to-transparent"></div>

  <div class="flex justify-between items-end gap-5 mb-8 flex-wrap">
    <div>
      <span class="font-mono text-[11px] tracking-widest text-[#2F8863] block mb-2 uppercase font-bold flex items-center gap-1.5">
        <span class="w-2 h-2 rounded-full bg-[#2F8863] inline-block animate-pulse"></span>
        <?= __('ব্লগ ও আর্টিকেল', 'Blogs & Articles') ?>
      </span>
      <h2 class="font-serif text-[clamp(24px,3vw,34px)] font-semibold text-[#101820]">
        <?= __('আইপিএইচ অ্যালামনাইদের জ্ঞানগর্ভ<br>লেখা, অভিজ্ঞতা ও সাম্প্রতিক আর্টিকেল।', 'Insights, research articles & stories<br>shared by our alumni.') ?>
      </h2>
    </div>
    <a href="<?= url('/stories') ?>" class="text-[14px] text-[#800020] font-semibold hover:underline flex items-center gap-1.5 group">
      <?= __('সব আর্টিকেল দেখুন', 'All Blogs & Articles') ?>
      <i class="fa-solid fa-arrow-right text-[12px] group-hover:translate-x-1 transition-transform"></i>
    </a>
  </div>

  <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
    <?php
    $displayStories = !empty($successStories) ? $successStories : [
      ['batch_year' => '2010',
       'title'      => __('জাতীয় নীতিনির্ধারণে জনস্বাস্থ্যের ভূমিকা', 'From district health office to national policy'),
       'excerpt'    => __('একটি জেলা স্বাস্থ্য অফিস থেকে জাতীয় পর্যায়ে নীতিনির্ধারণী ভূমিকা ও মাঠপর্যায়ের অভিজ্ঞতা।', 'Started at a district health office, now shapes national public health policy.'),
       'cover_image'=> '',
       'slug' => '#'],
      ['batch_year' => '2015',
       'title'      => __('WHO বুলেটিনে গবেষণা ও এপিডেমিওলজি পর্যালোচনা', 'Research featured in the WHO Bulletin'),
       'excerpt'    => __('ঢাকায় একটি এপিডেমিওলজি ল্যাব পরিচালনা এবং জনস্বাস্থ্য গবেষণার আন্তর্জাতিক স্বীকৃতি।', 'Now leads an epidemiology lab in Dhaka whose findings have gained international recognition.'),
       'cover_image'=> '',
       'slug' => '#'],
      ['batch_year' => '2020',
       'title'      => __('কমিউনিটি স্বাস্থ্যসেবা ও দক্ষ মানবসম্পদ উন্নয়ন', 'Training 300+ health workers every year'),
       'excerpt'    => __('অ্যাসোসিয়েশন-অনুমোদিত ট্রেনিং ট্র্যাকের মাধ্যমে তৃণমূল স্বাস্থ্য কর্মীদের দক্ষতা বৃদ্ধির বাস্তব গল্প।', 'Building skilled community health workers across the country through association tracks.'),
       'cover_image'=> '',
       'slug' => '#'],
    ];
    foreach ($displayStories as $story):
      $link = ($story['slug'] === '#') ? '#' : url('/stories/' . e($story['slug']));
    ?>
    <div class="rounded-2xl overflow-hidden flex flex-col hover-lift group"
         style="background:rgba(255,255,255,0.78);border:1px solid rgba(16,24,32,0.08);backdrop-filter:blur(14px);box-shadow:0 6px 24px -10px rgba(16,24,32,0.08);">
      
      <?php if (!empty($story['cover_image'])): ?>
      <div class="h-44 w-full overflow-hidden bg-gray-100">
        <img src="<?= asset('storage/stories/' . e($story['cover_image'])) ?>" alt="" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
      </div>
      <?php endif; ?>

      <div class="p-6 flex-1 flex flex-col justify-between">
        <div>
          <?php if (!empty($story['batch_year']) && $story['batch_year'] !== '0000'): ?>
          <span class="inline-block font-mono text-[10.5px] text-[#A22638] px-2.5 py-0.5 rounded-full mb-3"
                style="background:rgba(162,38,56,0.08);border:1px solid rgba(162,38,56,0.2);">
            <?= e(str_starts_with(strtoupper($story['batch_year']), 'BATCH') ? $story['batch_year'] : ('Batch ' . $story['batch_year'])) ?>
          </span>
          <?php endif; ?>
          <h4 class="font-serif text-[17.5px] font-semibold text-[#101820] mb-2.5 leading-snug group-hover:text-[#800020] transition-colors">
            <?= e($story['title']) ?>
          </h4>
          <p class="text-[13.5px] text-[#6B7178] leading-relaxed line-clamp-3">
            <?= e($story['excerpt'] ?? mb_strimwidth(strip_tags((string)($story['content'] ?? '')), 0, 120, '…')) ?>
          </p>
        </div>
        <div class="mt-5 pt-3 border-t border-gray-100">
          <a href="<?= $link ?>" class="text-[13.5px] font-semibold text-[#800020] hover:underline inline-flex items-center gap-1.5 group-hover:gap-2 transition-all">
            <?= __('আর্টিকেলটি পড়ুন', 'Read Article') ?> →
          </a>
        </div>
      </div>
    </div>
    <?php endforeach; ?>
  </div>
</section>

<!-- ══════════════════════════ COMMITTEE ORGANOGRAM (DESKTOP ONLY) ══════════════════════ -->
<style>
  .laser-flow-line { animation: laser-flow 2s linear infinite; }
  @keyframes laser-flow { to { stroke-dashoffset: -40; } }
</style>
<section class="hidden lg:block max-w-7xl mx-auto px-4 py-16">
  <div class="p-8 sm:p-14 rounded-[36px] relative overflow-hidden"
       style="background: linear-gradient(145deg, #1A070B 0%, #0F1923 50%, #150609 100%);
              border: 1px solid rgba(162,38,56,0.25);
              box-shadow: 0 30px 70px -15px rgba(0,0,0,0.7), 0 0 60px rgba(128,0,32,0.1);">

    <!-- Ambient orbs -->
    <div class="absolute w-[500px] h-[500px] bg-[#800020]/15 rounded-full blur-[160px] -top-24 -left-24 pointer-events-none z-0"></div>
    <div class="absolute w-[400px] h-[400px] bg-[#2F8863]/10 rounded-full blur-[140px] -bottom-20 -right-20 pointer-events-none z-0"></div>

    <!-- Header -->
    <div class="text-center mb-14 relative z-10">
      <span class="px-4 py-1.5 rounded-full text-[10.5px] font-mono font-medium tracking-widest text-[#E58E97] uppercase bg-[#800020]/20 border border-[#800020]/35 inline-flex items-center gap-2 mb-5">
        <span class="w-2 h-2 rounded-full bg-[#E58E97] animate-pulse"></span>
        <?= __('কার্যনির্বাহী কমিটি', 'Executive Committee') ?> 2025–2026
      </span>
      <h2 class="font-serif text-[clamp(26px,4vw,40px)] font-bold text-white leading-tight">
        <?= __('নেতৃত্বে যারা আছেন', 'Those at the helm') ?>
        <span class="block text-transparent bg-clip-text bg-gradient-to-r from-[#E58E97] to-[#800020] text-[clamp(18px,2.5vw,26px)] font-medium mt-1">
          <?= __('আইপিএইচ অ্যালামনাই অ্যাসোসিয়েশন', 'IPH Alumni Association') ?>
        </span>
      </h2>
    </div>

    <div class="space-y-10 relative z-10">

      <!-- SVG laser line connector -->
      <div class="hidden lg:block absolute inset-0 pointer-events-none z-0">
        <svg class="w-full h-full" xmlns="http://www.w3.org/2000/svg" style="opacity:0.6;">
          <line x1="50%" y1="80" x2="50%" y2="500" stroke="rgba(162,38,56,0.18)" stroke-width="2"/>
          <line x1="50%" y1="80" x2="50%" y2="500" stroke="#A22638" stroke-width="2" stroke-dasharray="8,14" class="laser-flow-line"/>
        </svg>
      </div>

      <!-- President -->
      <div class="flex justify-center relative z-10">
        <div class="absolute w-48 h-48 rounded-full border border-[#A22638]/20 animate-ping pointer-events-none opacity-20 -mt-3"></div>
        <div class="group relative max-w-[300px] w-full">
          <div class="absolute -inset-0.5 bg-gradient-to-r from-[#800020] via-[#A22638] to-[#2F8863] rounded-2xl blur opacity-35 group-hover:opacity-70 transition duration-500"></div>
          <div class="relative p-6 rounded-2xl text-center bg-[#15070A]/90 border border-[#800020]/40">
            <span class="px-3 py-1 rounded-full text-[10px] font-mono tracking-wider text-[#E58E97] bg-[#800020]/25 border border-[#800020]/45 inline-flex items-center gap-1.5 mb-4">
              👑 <?= __('সভাপতি', 'President') ?>
            </span>
            <h3 class="font-serif text-[20px] font-bold text-white tracking-tight"><?= __('মো. জহুরুল ইসলাম', 'Md. Jahurul Islam') ?></h3>
            <div class="text-[11.5px] text-[#E58E97] font-mono mt-2 bg-white/5 px-3 py-1 rounded-full inline-block"><?= __('ব্যাচ L-১', 'Batch L-1') ?></div>
          </div>
        </div>
      </div>

      <!-- Senior VP -->
      <div class="flex justify-center relative z-10">
        <div class="group max-w-[270px] w-full">
          <div class="p-5 rounded-2xl text-center bg-white/[0.04] border border-white/12 backdrop-blur-xl hover:border-[#A22638]/45 transition-all">
            <span class="text-[9.5px] font-mono tracking-wider text-white/60 bg-white/8 px-2.5 py-0.5 rounded-full border border-white/12 inline-block mb-3">
              <?= __('সহ-সভাপতি (সিনিয়র)', 'Senior Vice President') ?>
            </span>
            <h4 class="font-serif text-[17px] font-semibold text-white"><?= __('মোহাম্মদ রেজাউল করিম', 'Mohammad Rezaul Karim') ?></h4>
            <div class="text-[11px] text-white/45 mt-1.5 font-mono"><?= __('ব্যাচ L-১', 'Batch L-1') ?></div>
          </div>
        </div>
      </div>

      <!-- Vice Presidents -->
      <div class="max-w-4xl mx-auto relative z-10">
        <div class="text-center mb-5">
          <span class="text-[10.5px] font-mono tracking-widest text-[#E58E97]/60 uppercase px-3.5 py-1 rounded-full bg-white/4 border border-white/8 inline-block">
            <?= __('সহ-সভাপতিবৃন্দ', 'Vice Presidents') ?>
          </span>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
          <?php
          $vps = [
            ['role' => __('সিনিয়র সহ-সভাপতি', 'Senior VP'), 'name' => __('মোহাম্মদ রেজাউল করিম', 'Mohammad Rezaul Karim'), 'batch' => 'L-1'],
            ['role' => __('সহ-সভাপতি', 'Vice President'), 'name' => __('আখতারুজ্জামান তালুকদার সবুজ', 'Akhtaruzzaman Talukdar Sabuj'), 'batch' => 'L-1'],
            ['role' => __('সহ-সভাপতি', 'Vice President'), 'name' => __('ওয়াসিম খান', 'Wasim Khan'),                                     'batch' => 'L-1'],
            ['role' => __('সহ-সভাপতি', 'Vice President'), 'name' => __('রাশেদুর রহমান', 'Rashedur Rahman'),                              'batch' => 'L-1'],
            ['role' => __('সহ-সভাপতি', 'Vice President'), 'name' => __('মজিবুর রহমান উজ্জল রহমান', 'Mojibur Rahman Uzzal Rahman'), 'batch' => 'F-1'],
          ];
          foreach ($vps as $vp):
          ?>
          <div class="p-4 rounded-xl text-center bg-white/[0.03] border border-white/10 hover:-translate-y-1 hover:border-[#A22638]/40 transition-all duration-200">
            <span class="text-[9px] font-mono text-white/35 block mb-2"><?= $vp['role'] ?></span>
            <h4 class="text-[13.5px] font-semibold text-white leading-snug"><?= $vp['name'] ?></h4>
            <div class="text-[11px] text-[#E58E97]/70 mt-2 font-mono"><?= __('ব্যাচ ', 'Batch ') ?><?= $vp['batch'] ?></div>
          </div>
          <?php endforeach; ?>
        </div>
      </div>

      <!-- General Secretary -->
      <div class="flex justify-center relative z-10">
        <div class="group relative max-w-[280px] w-full">
          <div class="absolute -inset-0.5 bg-gradient-to-r from-[#2F8863] to-[#A22638] rounded-2xl blur opacity-28 group-hover:opacity-65 transition duration-500"></div>
          <div class="relative p-5 rounded-2xl text-center bg-[#0C161D]/90 border border-[#2F8863]/35">
            <span class="px-3 py-0.5 rounded-full text-[9.5px] font-mono tracking-wider text-[#2F8863] bg-[#2F8863]/12 border border-[#2F8863]/28 inline-flex items-center gap-1 mb-3">
              ⚡ <?= __('সাধারণ সম্পাদক', 'General Secretary') ?>
            </span>
            <h4 class="font-serif text-[18px] font-bold text-white"><?= __('রাজিবুল হাসান রাজা', 'Rajibul Hasan Raja') ?></h4>
            <div class="text-[11px] text-[#2F8863] mt-1.5 font-mono"><?= __('ব্যাচ L-২', 'Batch L-2') ?></div>
          </div>
        </div>
      </div>

      <!-- Org Secretaries -->
      <div class="grid grid-cols-1 sm:grid-cols-4 gap-4 max-w-4xl mx-auto relative z-10">
        <?php
        $orgs = [
          ['role' => __('যুগ্ম সাধারণ সম্পাদক', 'Joint Gen. Sec 1'),  'name' => __('তুষার সিংহ', 'Tushar Singha'),              'batch' => 'L-2', 'c' => '#2F8863'],
          ['role' => __('যুগ্ম সাধারণ সম্পাদক', 'Joint Gen. Sec 2'),  'name' => __('আব্দুল্লা খান', 'Abdulla Khan'),              'batch' => 'F-1', 'c' => '#2F8863'],
          ['role' => __('সাংগঠনিক সম্পাদক', 'Org. Secretary 1'), 'name' => __('মো হাসানুজ্জামান', 'Md. Hasanuzzaman'),     'batch' => 'L-3', 'c' => '#E58E97'],
          ['role' => __('সাংগঠনিক সম্পাদক', 'Org. Secretary 2'), 'name' => __('মেহেদী হাসান রাব্বি', 'Mehedi Hasan Rabbi'), 'batch' => 'L-3', 'c' => '#E58E97'],
        ];
        foreach ($orgs as $o):
        ?>
        <div class="p-4 rounded-xl text-center bg-white/[0.03] border border-white/10 hover:-translate-y-1 hover:border-[#800020]/40 transition-all duration-200">
          <span class="text-[9.5px] font-mono px-2 py-0.5 rounded border inline-block mb-2" style="color:<?= $o['c'] ?>;border-color:rgba(255,255,255,0.1);background:rgba(255,255,255,0.04);"><?= $o['role'] ?></span>
          <h4 class="text-[13.5px] font-semibold text-white"><?= $o['name'] ?></h4>
          <div class="text-[11px] text-white/35 mt-2 font-mono"><?= __('ব্যাচ ', 'Batch ') ?><?= $o['batch'] ?></div>
        </div>
        <?php endforeach; ?>
      </div>

      <!-- Section Secretaries -->
      <div class="relative z-10">
        <div class="text-center mb-5">
          <span class="text-[10.5px] font-mono tracking-widest text-[#E58E97]/60 uppercase px-3.5 py-1 rounded-full bg-white/4 border border-white/8 inline-block">
            <?= __('বিভাগীয় সম্পাদকবৃন্দ', 'Section Secretaries') ?>
          </span>
        </div>
        <div class="grid grid-cols-2 sm:grid-cols-4 lg:grid-cols-8 gap-3">
          <?php
          $secs = [
            ['role' => __('কোষাধ্যক্ষ', 'Treasurer'),           'name' => __('মোহাম্মদ শফিউল আলম ফিরোজ', 'M. Shafiul Alam Firoz'), 'batch' => 'L-4'],
            ['role' => __('দপ্তর সম্পাদক', 'Office Sec.'),       'name' => __('আব্দুল্লাহ আল তাহসিন', 'Abdullah Al Tahsin'),       'batch' => 'L-3'],
            ['role' => __('উপ-দপ্তর সম্পাদক', 'Asst. Office Sec.'),'name' => __('মাহমুদুর রহমান ইমন', 'Mahmudur Rahman Imon'),        'batch' => 'L-4'],
            ['role' => __('শিক্ষা ও গবেষণা', 'Education'),       'name' => __('মো শাহীন আলম', 'Md. Shahin Alam'),                  'batch' => 'L-2'],
            ['role' => __('সংস্কৃতি ও ক্রীড়া', 'Culture'),      'name' => __('সৈকত সরকার', 'Saikat Sarkar'),                        'batch' => 'L-3'],
            ['role' => __('ধর্ম বিষয়ক', 'Religious'),           'name' => __('আনোয়ার হোসেন', 'Anwar Hossain'),                    'batch' => 'L-3'],
            ['role' => __('প্রচার ও জনসংযোগ', 'Publicity'),     'name' => __('যুবায়ের হাসান', 'Zubayer Hasan Sarkar'),             'batch' => 'L-3'],
            ['role' => __('নারী বিষয়ক', "Women's Affairs"),     'name' => __('ফেরদৌসি আক্তার', 'Ferdousi Akhter'),                 'batch' => 'L-3'],
          ];
          foreach ($secs as $s):
          ?>
          <div class="p-3.5 rounded-xl text-center bg-white/[0.025] border border-white/8 hover:-translate-y-1 hover:border-[#800020]/35 transition-all duration-200">
            <span class="text-[8.5px] font-mono text-[#E58E97]/70 block mb-2 leading-tight"><?= $s['role'] ?></span>
            <h4 class="text-[12.5px] font-semibold text-white leading-tight"><?= $s['name'] ?></h4>
            <div class="text-[10.5px] text-white/35 mt-2 font-mono"><?= __('ব্যাচ ', 'Batch ') ?><?= $s['batch'] ?></div>
          </div>
          <?php endforeach; ?>
        </div>
      </div>

      <!-- Executive Members -->
      <div class="relative z-10">
        <div class="text-center mb-5">
          <span class="text-[10.5px] font-mono tracking-widest text-[#E58E97]/60 uppercase px-3.5 py-1 rounded-full bg-white/4 border border-white/8 inline-block">
            <?= __('কার্যনির্বাহী সদস্যবৃন্দ', 'Executive Members') ?>
          </span>
        </div>
        <div class="grid grid-cols-2 sm:grid-cols-4 lg:grid-cols-8 gap-3 max-w-6xl mx-auto">
          <?php
          $mems = [
            ['name' => __('শিলা আক্তার', 'Shila Akhter'),           'batch' => 'L-2'],
            ['name' => __('কেয়া পাপিয়া', 'Keya Papiya'),            'batch' => 'L-2'],
            ['name' => __('তানিয়া আফরোজ', 'Tania Afroz'),           'batch' => 'L-3'],
            ['name' => __('পৃথু চাকমা', 'Prithu Chakma'),            'batch' => 'L-3'],
            ['name' => __('আব্দুর রহমান দিপলু', 'Abdur Rahman Diplu'), 'batch' => 'L-4'],
            ['name' => __('জুনায়েদ আহমেদ', 'Junayed Ahmed'),         'batch' => 'L-4'],
            ['name' => __('সালমুন সাজ্জাদ', 'Salmun Sajjad'),         'batch' => 'F-1'],
            ['name' => __('কণা আক্তার', 'Kona Akhter'),            'batch' => 'F-1'],
          ];
          foreach ($mems as $m):
          ?>
          <div class="p-3 rounded-xl text-center bg-white/[0.02] border border-white/7 hover:-translate-y-1 hover:border-[#800020]/30 transition-all duration-200">
            <span class="text-[8.5px] font-mono text-white/25 block mb-1"><?= __('সদস্য', 'Member') ?></span>
            <h4 class="text-[12.5px] font-semibold text-white leading-tight"><?= $m['name'] ?></h4>
            <div class="text-[10.5px] text-white/30 mt-1.5 font-mono"><?= __('ব্যাচ ', 'Batch ') ?><?= $m['batch'] ?></div>
          </div>
          <?php endforeach; ?>
        </div>
      </div>

    </div>
  </div>
</section>

<!-- ══════════════════════════ MEMBERSHIP TIERS ════════════════════════════ -->
<section id="membership" class="max-w-7xl mx-auto px-6 py-14">
  <div class="h-px mb-14 bg-gradient-to-r from-transparent via-[#2F8863]/20 to-transparent"></div>

  <div class="flex justify-between items-end gap-5 mb-10 flex-wrap">
    <div>
      <span class="font-mono text-[11px] tracking-widest text-[#2F8863] block mb-2 uppercase"><?= __('মেম্বারশিপ', 'Membership') ?></span>
      <h2 class="font-serif text-[clamp(24px,3vw,34px)] font-semibold text-[#101820]">
        <?= __('সদস্যপদ বেছে নিন,<br>কার্ড সংগ্রহ করুন।', 'Choose your tier,<br>get your card.') ?>
      </h2>
    </div>
    <p class="text-[14px] text-[#6B7178] max-w-[340px]">
      <?= __('প্রতিটি সদস্যপদে QR-ভেরিফাইড ডিজিটাল আইডি কার্ড দেওয়া হয়, যা যেকোনো ইভেন্ট ও পার্টনার ডেস্কে ব্যবহার করা যাবে।',
            'Every tier comes with a QR-verified digital ID card, valid at events and partner desks.') ?>
    </p>
  </div>

  <div class="grid grid-cols-1 md:grid-cols-3 gap-6 items-stretch">
    <?php
    $typesList = !empty($membershipTypes) ? $membershipTypes : [
      ['name' => 'Annual Membership', 'fee' => 500, 'duration_months' => 12, 'is_featured' => 0, 'badge_text' => '', 'btn_text' => 'Start with Annual', 'features' => "Directory listing with verified badge\nQR-enabled member ID card\nEvent invitations & access"],
      ['name' => 'Lifetime Membership', 'fee' => 5000, 'duration_months' => null, 'is_featured' => 1, 'badge_text' => 'MOST POPULAR', 'btn_text' => 'Become a Lifetime Member', 'features' => "Everything in Annual\nLifetime membership certificate\nPriority seating at events\nVoting rights at general meetings"],
      ['name' => 'Honorary Membership', 'fee' => 0, 'duration_months' => null, 'is_featured' => 0, 'badge_text' => '', 'btn_text' => 'Nominate Someone', 'features' => "Nominated by the executive committee\nSpecial recognition on the About page\nNo annual renewal fee"]
    ];
    foreach ($typesList as $mt):
      $isFeatured = !empty($mt['is_featured']);
      $feeText = (float)($mt['fee'] ?? 0) > 0 ? '৳' . number_format((float)$mt['fee']) : __('আমন্ত্রণ সাপেক্ষে', 'By invitation');
      $periodText = (float)($mt['fee'] ?? 0) > 0 ? ($mt['duration_months'] ? '/ ' . __('বছর', 'year') : '/ ' . __('এককালীন', 'one-time')) : '';
      $featureLines = array_filter(array_map('trim', explode("\n", $mt['features'] ?? '')));
    ?>
    <div class="p-7 rounded-3xl flex flex-col gap-5 relative hover-lift transition-all duration-300 h-full <?= $isFeatured ? 'bg-gradient-to-br from-amber-50/70 via-white to-rose-50/30 border-2 border-amber-400/60 shadow-xl' : 'bg-white/80 border border-slate-200/80 shadow-sm' ?>">
      <?php if (!empty($mt['badge_text'])): ?>
      <span class="absolute -top-3.5 right-6 font-mono text-[10px] font-bold px-3 py-1 rounded-full bg-[#101820] text-[#E58E97] border border-amber-400/40 shadow-sm">
        <?= e($mt['badge_text']) ?>
      </span>
      <?php endif; ?>

      <div>
        <h3 class="font-serif text-[22px] font-bold text-[#101820] mb-1"><?= e($mt['name']) ?></h3>
        <div class="font-serif text-[32px] font-bold text-[#101820] flex items-baseline gap-1">
          <?= $feeText ?>
          <?php if ($periodText): ?>
          <span class="text-[13px] font-sans font-normal text-gray-500"><?= $periodText ?></span>
          <?php endif; ?>
        </div>
        <?php if (!empty($mt['description'])): ?>
        <p class="text-[12.5px] text-gray-500 mt-1"><?= e($mt['description']) ?></p>
        <?php endif; ?>
      </div>

      <ul class="flex flex-col gap-2.5 text-[13.5px] text-[#555] flex-1 my-2">
        <?php foreach ($featureLines as $feat): ?>
        <li class="flex gap-2 items-start">
          <span class="<?= $isFeatured ? 'text-[#A22638] font-bold' : 'text-[#2F8863] font-bold' ?> mt-0.5"><?= $isFeatured ? '✦' : '✓' ?></span>
          <span><?= e($feat) ?></span>
        </li>
        <?php endforeach; ?>
      </ul>

      <a href="<?= (float)($mt['fee'] ?? 0) > 0 ? url('/register') : url('/contact') ?>"
         class="inline-flex justify-center items-center px-5 py-3 rounded-2xl text-[14px] font-bold transition-all hover:-translate-y-0.5 <?= $isFeatured ? 'bg-gradient-to-r from-[#A22638] to-[#800020] text-white shadow-md hover:shadow-lg' : 'bg-white border border-gray-300 text-gray-800 hover:bg-gray-50 shadow-sm' ?>">
        <?= e($mt['btn_text'] ?? __('বেছে নিন', 'Select Tier')) ?>
      </a>
    </div>
    <?php endforeach; ?>
  </div>

  <!-- Benefits Grid -->
  <div class="mt-14 pt-12 border-t border-[#101820]/8">
    <h3 class="font-serif text-[22px] font-semibold text-[#101820] text-center mb-2"><?= __('সদস্যপদের সুবিধাসমূহ', 'Membership Benefits') ?></h3>
    <p class="text-[13.5px] text-[#6B7178] text-center max-w-md mx-auto mb-9">
      <?= __('আইপিএইচ অ্যালামনাই পরিবারের সক্রিয় সদস্য হিসেবে যা পাবেন:', 'What you unlock as an active IPH Alumni family member:') ?>
    </p>
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
      <?php
      $benefits = [
        ['<i class="fa-solid fa-earth-americas" style="color:#2F8863;font-size:18px;"></i>', __('গ্লোবাল নেটওয়ার্ক', 'Global Network'),           __('বিশ্বজুড়ে জনস্বাস্থ্য পেশাদার ও গবেষকদের বিশাল নেটওয়ার্কে সরাসরি যোগাযোগ করুন।', 'Connect directly with a global network of public health professionals and researchers.'), '#2F8863'],
        ['<i class="fa-solid fa-id-card" style="color:#A22638;font-size:18px;"></i>',         __('ডিজিটাল আইডি কার্ড', 'Digital ID Card'),          __('QR-কোড সম্বলিত ভেরিফাইড আইডি কার্ড — সেমিনার ও ইভেন্টে দ্রুত এবং নির্বিঘ্ন প্রবেশের নিশ্চয়তা।', 'A QR-enabled verified ID card for fast, seamless access to seminars and events.'), '#A22638'],
        ['<i class="fa-solid fa-flask" style="color:#2F8863;font-size:18px;"></i>',           __('গবেষণা ও বৃত্তি', 'Research & Grants'),            __('জনস্বাস্থ্য গবেষণা দল, আন্তর্জাতিক সেমিনার কোলাবোরেশন ও বৃত্তির আপডেট।', 'Updates on public health research groups, international collaborations, and scholarship opportunities.'), '#2F8863'],
        ['<i class="fa-solid fa-ticket" style="color:#A22638;font-size:18px;"></i>',          __('ইভেন্টে অগ্রাধিকার', 'Priority Access'),           __('বার্ষিক মিলনমেলা, পুনর্মিলনী এবং বিশেষ ওয়ার্কশপে অগ্রাধিকার বুকিং ও আসন সুবিধা।', 'Priority booking and seating at the annual reunion, conferences, and special workshops.'), '#A22638'],
      ];
      foreach ($benefits as [$bIcon, $bTitle, $bDesc, $bAccent]):
      ?>
      <div class="p-6 rounded-2xl hover-lift"
           style="background:rgba(255,255,255,0.65);border:1px solid rgba(16,24,32,0.06);backdrop-filter:blur(10px);">
        <div class="w-11 h-11 rounded-2xl flex items-center justify-center mb-4"
             style="background:<?= $bAccent ?>18;">
          <?= $bIcon ?>
        </div>
        <h4 class="font-semibold text-[15px] text-[#101820] mb-2"><?= $bTitle ?></h4>
        <p class="text-[12.5px] text-[#6B7178] leading-relaxed"><?= $bDesc ?></p>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- ════════════════════════ EVENTS ═══════════════════════════════════════ -->
<section id="events" class="max-w-7xl mx-auto px-6 py-14">
  <div class="h-px mb-14 bg-gradient-to-r from-transparent via-[#800020]/20 to-transparent"></div>
  <div class="flex justify-between items-end gap-5 mb-8 flex-wrap">
    <div>
      <span class="font-mono text-[11px] tracking-widest text-[#2F8863] block mb-2 uppercase"><?= __('আসন্ন ইভেন্ট', 'Upcoming Events') ?></span>
      <h2 class="font-serif text-[clamp(24px,3vw,34px)] font-semibold text-[#101820]">
        <?= __('পুনর্মিলনী, সেমিনার,<br>আর স্মরণীয় মুহূর্ত।', 'Reunions, seminars,<br>and moments that matter.') ?>
      </h2>
    </div>
    <a href="<?= url('/events') ?>" class="text-[14px] text-[#800020] font-semibold hover:underline flex items-center gap-1">
      <?= __('সব ইভেন্ট', 'All Events') ?>
      <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
    </a>
  </div>

  <?php if (empty($events)): ?>
  <div class="text-center py-16">
    <div class="text-4xl mb-4">📅</div>
    <p class="text-[#9CA3AF] text-[14px]"><?= __('এই মুহূর্তে কোনো ইভেন্ট নির্ধারিত নেই। শীঘ্রই আবার দেখুন!', 'No upcoming events scheduled. Check back soon!') ?></p>
  </div>
  <?php else: ?>
  <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
    <?php foreach ($events as $ev): ?>
    <a href="<?= url('/events/' . e($ev['slug'])) ?>"
       class="block rounded-2xl overflow-hidden hover-lift group"
       style="background:rgba(255,255,255,0.72);border:1px solid rgba(16,24,32,0.07);backdrop-filter:blur(14px);">
      <div class="px-5 py-4 flex items-center justify-between" style="border-bottom:1px solid rgba(16,24,32,0.06);">
        <div class="flex items-baseline gap-2">
          <span class="font-serif text-[26px] font-semibold text-[#800020]"><?= date('d', strtotime($ev['event_date'])) ?></span>
          <span class="font-mono text-[11px] text-[#9CA3AF] uppercase"><?= date('M · Y', strtotime($ev['event_date'])) ?></span>
        </div>
        <span class="text-[10.5px] font-mono text-[#2F8863] bg-[#2F8863]/10 px-2.5 py-1 rounded-full border border-[#2F8863]/20">
          <?= __('আসন্ন', 'Upcoming') ?>
        </span>
      </div>
      <div class="px-5 py-5">
        <h4 class="text-[15px] font-semibold text-[#101820] mb-2 group-hover:text-[#800020] transition-colors"><?= e($ev['title']) ?></h4>
        <p class="text-[12.5px] text-[#9CA3AF] leading-relaxed"><?= e(mb_strimwidth($ev['description'] ?? '', 0, 90, '…')) ?></p>
        <div class="mt-3 text-[13px] text-[#800020] font-medium flex items-center gap-1">
          <?= __('বিস্তারিত দেখুন', 'View details') ?>
          <svg class="w-3.5 h-3.5 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        </div>
      </div>
    </a>
    <?php endforeach; ?>
  </div>
  <?php endif; ?>
</section>

<!-- ════════════════════════ LATEST NEWS ═════════════════════════════════ -->
<?php if (!empty($news)): ?>
<section class="max-w-7xl mx-auto px-6 py-10">
  <div class="flex justify-between items-end gap-5 mb-8 flex-wrap">
    <div>
      <span class="font-mono text-[11px] tracking-widest text-[#2F8863] block mb-2 uppercase"><?= __('সাম্প্রতিক সংবাদ', 'Latest News') ?></span>
      <h2 class="font-serif text-[clamp(22px,2.8vw,30px)] font-semibold text-[#101820]">
        <?= __('অ্যাসোসিয়েশনের সর্বশেষ খবর।', 'What\'s happening in the association.') ?>
      </h2>
    </div>
    <a href="<?= url('/news') ?>" class="text-[14px] text-[#800020] font-semibold hover:underline flex items-center gap-1">
      <?= __('সব সংবাদ', 'All News') ?>
      <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
    </a>
  </div>
  <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
    <?php foreach ($news as $n): ?>
    <a href="<?= url('/news/' . e($n['slug'])) ?>"
       class="block p-6 rounded-2xl hover-lift group"
       style="background:rgba(255,255,255,0.72);border:1px solid rgba(16,24,32,0.07);backdrop-filter:blur(14px);">
      <span class="font-mono text-[10.5px] text-[#9CA3AF]"><?= date('d M Y', strtotime($n['published_at'] ?? $n['created_at'])) ?></span>
      <h4 class="font-serif text-[17px] font-semibold text-[#101820] mt-2 mb-2 group-hover:text-[#800020] transition-colors leading-snug"><?= e($n['title']) ?></h4>
      <p class="text-[13px] text-[#9CA3AF] leading-relaxed"><?= e(mb_strimwidth(strip_tags($n['content'] ?? ''), 0, 110, '…')) ?></p>
    </a>
    <?php endforeach; ?>
  </div>
</section>
<?php endif; ?>

<!-- ════════════════════════ CTA BANNER ══════════════════════════════════ -->
<section class="max-w-7xl mx-auto px-6 py-10 pb-20">
  <div class="relative p-12 rounded-3xl text-center overflow-hidden"
       style="background:linear-gradient(135deg,rgba(128,0,32,0.07),rgba(47,136,99,0.06));border:1px solid rgba(16,24,32,0.07);">
    <div class="absolute -top-20 -right-20 w-64 h-64 rounded-full opacity-15 pointer-events-none"
         style="background:radial-gradient(circle,#C0394B,transparent)"></div>
    <div class="absolute -bottom-20 -left-20 w-64 h-64 rounded-full opacity-10 pointer-events-none"
         style="background:radial-gradient(circle,#4E9C81,transparent)"></div>

    <div class="relative">
      <h2 class="font-serif text-[clamp(22px,3.2vw,32px)] font-semibold text-[#101820] mb-3">
        <?= __('আপনার গ্র্যাজুয়েশন ছিল কেবল শুরু।<br>আজই নেটওয়ার্কের অংশ হোন।',
              'Your graduation was only the beginning.<br>Join the network that keeps giving.') ?>
      </h2>
      <p class="text-[14px] text-[#6B7178] mb-7 max-w-md mx-auto">
        <?= __('নিবন্ধন করতে মাত্র ৫ মিনিট সময় লাগে। সাধারণত ৪৮ ঘণ্টার মধ্যে প্রোফাইল ভেরিফাই হয়ে যায়।',
              'Registration takes under 5 minutes. Profiles are usually verified within 48 hours.') ?>
      </p>
      <a href="<?= url('/register') ?>"
         class="inline-flex items-center gap-2 px-8 py-3.5 rounded-2xl text-[15px] font-semibold text-white transition-all hover:-translate-y-0.5 hover:shadow-2xl"
         style="background:linear-gradient(135deg,#A22638,#800020);box-shadow:0 8px 26px -8px rgba(128,0,32,0.5);">
        <?= __('অ্যালামনাই হিসেবে নিবন্ধন করুন', 'Register as Alumni') ?>
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
      </a>
    </div>
  </div>
</section>
