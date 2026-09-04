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

/* ── Hero entrance animations ── */
@keyframes heroFadeUp   { from{opacity:0;transform:translateY(28px);} to{opacity:1;transform:translateY(0);} }
@keyframes heroFadeLeft { from{opacity:0;transform:translateX(36px);} to{opacity:1;transform:translateX(0);} }
@keyframes heroBadge    { from{opacity:0;transform:scale(0.82);}      to{opacity:1;transform:scale(1);} }
@keyframes floatCard    { 0%,100%{transform:translateY(0) rotate(0deg);} 50%{transform:translateY(-10px) rotate(1deg);} }
@keyframes floatCardAlt { 0%,100%{transform:translateY(0) rotate(0deg);} 50%{transform:translateY(-7px) rotate(-1deg);} }
@keyframes blink        { 0%,100%{opacity:1;} 50%{opacity:0;} }
@keyframes scanline     { 0%{top:-4px;} 100%{top:100%;} }
@keyframes shimmer      { from{background-position:-200% 0;} to{background-position:200% 0;} }
@keyframes scrollBounce { 0%,100%{transform:translateY(0);} 50%{transform:translateY(4px);} }

.hero-badge  { animation: heroBadge    0.55s cubic-bezier(.34,1.56,.64,1) 0.1s  both; }
.hero-h1     { animation: heroFadeUp   0.7s ease 0.3s  both; }
.hero-sub    { animation: heroFadeUp   0.7s ease 0.45s both; }
.hero-btns   { animation: heroFadeUp   0.7s ease 0.6s  both; }
.hero-stats  { animation: heroFadeUp   0.7s ease 0.75s both; }
.hero-img    { animation: heroFadeLeft 0.8s ease 0.5s  both; }

.float-card-1 { animation: floatCard    4.5s ease-in-out         infinite; }
.float-card-2 { animation: floatCardAlt 5.5s ease-in-out 0.8s   infinite; }
.float-card-3 { animation: floatCard    5s   ease-in-out 1.5s   infinite; }

.tw-cursor::after { content:'|'; animation: blink 0.9s step-end infinite; color:#A22638; margin-left:1px; }

/* 3D tilt */
.tilt-frame { transform-style: preserve-3d; transition: transform 0.08s ease; will-change: transform; }
.tilt-shine {
  position:absolute;inset:0;border-radius:inherit;pointer-events:none;z-index:20;
  background: radial-gradient(circle at var(--mx,50%) var(--my,50%), rgba(255,255,255,0.13) 0%, transparent 58%);
  opacity:0; transition: opacity 0.3s;
}
.tilt-frame:hover .tilt-shine { opacity:1; }

/* Shimmer CTA */
.shimmer-btn {
  background-size:250% 100%;
  background-image: linear-gradient(120deg, #A22638 0%, #800020 35%, #C0394B 50%, #800020 65%, #A22638 100%);
  animation: shimmer 2.8s linear infinite;
}

.scroll-dot  { animation: scrollBounce 1.4s ease-in-out infinite; }
</style>
<section class="relative overflow-hidden -mt-[85px] pt-[95px]">

  <!-- Modern Mesh Gradient Background (Clean, Elegant & Premium - Image Removed) -->
  <div class="absolute inset-0 -z-10 pointer-events-none overflow-hidden select-none" style="background: radial-gradient(circle at 50% -20%, rgba(128,0,32,0.06) 0%, rgba(255,255,255,0.98) 70%);">
    
    <!-- Architectural Dot Grid Pattern -->
    <div class="absolute inset-0 opacity-[0.4]" 
         style="background-image: radial-gradient(rgba(128,0,32,0.12) 1px, transparent 1px); background-size: 28px 28px;"></div>

    <!-- Smooth Dynamic Mesh Gradient Orbs -->
    <div class="absolute -top-40 -left-40 w-[650px] h-[650px] rounded-full blur-[140px] opacity-30 animate-pulse"
         style="background:radial-gradient(circle,rgba(162,38,56,0.35),transparent 70%); animation-duration: 8s;"></div>
    <div class="absolute top-1/4 -right-32 w-[550px] h-[550px] rounded-full blur-[130px] opacity-25"
         style="background:radial-gradient(circle,rgba(47,136,99,0.3),transparent 70%);"></div>
    <div class="absolute -bottom-20 left-1/3 w-[600px] h-[300px] rounded-full blur-[120px] opacity-20"
         style="background:radial-gradient(circle,rgba(212,165,74,0.25),transparent 70%);"></div>

    <!-- Soft Top Light Accent Bar -->
    <div class="absolute top-0 inset-x-0 h-[2px] bg-gradient-to-r from-transparent via-[#800020]/20 to-transparent"></div>
    
    <!-- Animated Particle Canvas -->
    <canvas id="hero-canvas" class="relative z-10"></canvas>
  </div>

  <div class="max-w-7xl mx-auto px-6 pt-10 pb-6">
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-14 items-center">

      <!-- ── LEFT — COPY ── -->
      <div class="relative z-10 order-2 lg:order-1">

        <!-- Animated live badge -->
        <div class="hero-badge inline-flex items-center gap-2.5 px-4 py-2 rounded-full mb-7 cursor-default select-none"
             style="background:rgba(128,0,32,0.07);border:1px solid rgba(128,0,32,0.2);backdrop-filter:blur(8px);">
          <span class="relative flex h-2.5 w-2.5">
            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-[#2F8863] opacity-70"></span>
            <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-[#2F8863]"></span>
          </span>
          <span class="font-mono text-[11px] tracking-widest text-[#A22638]">
            <?= __('প্রতিষ্ঠিত ', 'Est. ') ?><?= $appFounded ?>
            &nbsp;·&nbsp;
            <?= __('ইনস্টিটিউট অব পাবলিক হেলথ', 'Institute of Public Health') ?>
          </span>
        </div>

        <!-- Headline (Fixed Static Text) -->
        <h1 class="hero-h1 font-serif text-[clamp(20px,2.7vw,34px)] leading-[1.25] font-semibold tracking-tight text-[#101820] mb-5">
          <span class="block whitespace-nowrap"><?= __($headlineLine1Bn, $headlineLine1En) ?></span>
          <em class="not-italic text-[#A22638] block"><?= __('এক অ্যালামনাই পরিবার।', 'One Alumni Family.') ?></em>
        </h1>

        <p class="hero-sub text-[16px] leading-[1.78] text-[#6B7178] max-w-[510px] mb-8">
          <?= __('বিশ্বজুড়ে ছড়িয়ে থাকা আইপিএইচ গ্র্যাজুয়েটদের সাথে যুক্ত হন — পুরনো বন্ধু খুঁজুন, আপনার পথচলার গল্প ভাগ করুন, এবং সেই নেটওয়ার্কের অংশ থাকুন যা আপনার ক্যারিয়ার গড়েছে।',
                'Connect with IPH graduates from around the world — find old batchmates, share your story, and stay part of the network that shaped your career.') ?>
        </p>

        <!-- CTA Buttons (Inline on Mobile) -->
        <div class="hero-btns flex flex-row items-center gap-2.5 sm:gap-3 mb-4">
          <a href="<?= url('/register') ?>"
             class="shimmer-btn inline-flex items-center justify-center gap-1.5 sm:gap-2.5 px-3.5 sm:px-7 py-3 sm:py-3.5 rounded-2xl text-[13px] sm:text-[15px] font-semibold text-white transition-all hover:-translate-y-1 hover:shadow-2xl active:scale-95 shrink-0"
             style="box-shadow:0 8px 26px -6px rgba(128,0,32,0.5);">
            <i class="fa-solid fa-user-plus text-[11px] sm:text-[13px]"></i>
            <span><?= __('অ্যালামনাই হিসেবে যোগ দিন', 'Join as Alumni') ?></span>
            <i class="fa-solid fa-arrow-right text-[10px] sm:text-[12px] hidden xs:inline-block"></i>
          </a>
          <a href="<?= url('/directory') ?>"
             class="inline-flex items-center justify-center gap-1.5 sm:gap-2.5 px-3.5 sm:px-7 py-3 sm:py-3.5 rounded-2xl text-[13px] sm:text-[15px] font-semibold text-[#101820] transition-all hover:-translate-y-1 hover:shadow-lg active:scale-95 shrink-0"
             style="background:rgba(255,255,255,0.85);border:1px solid rgba(16,24,32,0.1);backdrop-filter:blur(10px);">
            <i class="fa-solid fa-address-book text-[11px] sm:text-[13px] text-[#6B7178]"></i>
            <span><?= __('ডিরেক্টরি দেখুন', 'Browse Directory') ?></span>
          </a>
        </div>
      </div>

      <!-- ── RIGHT — 3D IMAGE + FLOATING CARDS ── -->
      <div class="hero-img relative z-10 flex justify-center lg:justify-end order-1 lg:order-2">
        <div id="hero-tilt" class="tilt-frame relative w-full max-w-[480px]" style="perspective:900px;">

          <!-- Ambient glow behind frame -->
          <div class="absolute -inset-8 rounded-[48px] blur-3xl opacity-35 pointer-events-none -z-10"
               style="background:linear-gradient(135deg,rgba(128,0,32,0.35),rgba(47,136,99,0.25));"></div>

          <!-- Glass outer frame -->
          <div class="p-4 rounded-[32px] shadow-2xl relative overflow-hidden"
               style="background:rgba(255,255,255,0.65);border:1.5px solid rgba(255,255,255,0.8);backdrop-filter:blur(18px);">

            <!-- Mouse-follow shine -->
            <div class="tilt-shine rounded-[26px]"></div>

            <!-- Scanline sweep -->
            <div class="absolute left-0 right-0 h-[3px] pointer-events-none z-20 opacity-15"
                 style="background:linear-gradient(90deg,transparent,rgba(128,0,32,0.9),transparent);animation:scanline 4s linear infinite;"></div>

            <!-- Image -->
            <div class="rounded-[22px] overflow-hidden border border-[#800020]/10 relative aspect-[4/3]">
              <img src="<?= asset('images/Campus-front-view.jpg') ?>"
                   alt="IPH Campus"
                   class="w-full h-full object-cover transition-transform duration-700 hover:scale-105">
              <div class="absolute inset-0 bg-gradient-to-t from-black/65 via-black/5 to-transparent"></div>

              <!-- Caption -->
              <div class="absolute bottom-5 left-5 right-5 text-white">
                <span class="font-mono text-[9px] tracking-widest uppercase text-[#E58E97] block mb-1">
                  <i class="fa-solid fa-location-dot mr-1"></i>
                  <?= __('আমাদের প্রিয় শিক্ষাপ্রতিষ্ঠান', 'Our Alma Mater') ?>
                </span>
                <h4 class="font-serif text-[17px] font-bold">
                  <?= __('ইনস্টিটিউট অব পাবলিক হেলথ', 'Institute of Public Health') ?>
                </h4>
              </div>

              <!-- Live badge on image -->
              <div class="absolute top-4 right-4 flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-mono text-white"
                   style="background:rgba(0,0,0,0.42);backdrop-filter:blur(6px);border:1px solid rgba(255,255,255,0.15);">
                <span class="w-1.5 h-1.5 rounded-full bg-[#2F8863] animate-pulse"></span>
                <?= __('লাইভ ডিরেক্টরি', 'Live Directory') ?>
              </div>
            </div>
          </div>



          <?php 
            $isBnLang = !session('locale') || session('locale') === 'bn' || session('lang') === 'bn';
            $memberCountDisplay  = $isBnLang ? to_bn_number($stats['total']) : number_format($stats['total']);
            $countryCountDisplay = $isBnLang ? to_bn_number($stats['countries']) : number_format($stats['countries']);
          ?>

          <!-- ★ Floating Card 2: Member count (Right side middle) -->
          <div class="float-card-2 absolute -right-6 top-1/3 hidden lg:flex flex-col items-center gap-1 px-5 py-4 rounded-2xl shadow-xl text-center"
               style="background:rgba(255,255,255,0.95);border:1px solid rgba(16,24,32,0.07);backdrop-filter:blur(16px);">
            <i class="fa-solid fa-users text-[#800020] text-[20px]"></i>
            <div class="font-serif text-[22px] font-bold text-[#101820] leading-tight"><?= $memberCountDisplay ?>+</div>
            <div class="text-[10px] text-[#9CA3AF] font-mono"><?= __('সদস্য', 'Members') ?></div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Scroll indicator -->
  <div class="flex justify-center pb-8 relative z-10">
    <a href="#directory" class="flex flex-col items-center gap-2 text-[#9CA3AF] hover:text-[#800020] transition-colors">
      <span class="text-[10px] font-mono tracking-widest"><?= __('নিচে দেখুন', 'SCROLL') ?></span>
      <div class="w-5 h-8 rounded-full border-2 border-current flex justify-center pt-1.5">
        <div class="w-1 h-2 rounded-full bg-current scroll-dot"></div>
      </div>
    </a>
  </div>
</section>

<!-- ── Hero JS: typewriter + count-up + 3D tilt + particle canvas ── -->
<script>
(function(){
  /* 1. COUNT-UP */
  const countEls=document.querySelectorAll('[data-count]');
  if('IntersectionObserver' in window){
    const obs=new IntersectionObserver(entries=>{
      entries.forEach(e=>{
        if(!e.isIntersecting)return; obs.unobserve(e.target);
        const el=e.target,target=parseInt(el.dataset.count,10),suf=el.dataset.suffix||'';
        const dur=1400,start=performance.now();
        function tick(now){
          const p=Math.min((now-start)/dur,1),ease=1-Math.pow(1-p,3);
          el.textContent=Math.floor(ease*target)+suf;
          if(p<1)requestAnimationFrame(tick); else el.textContent=target+suf;
        }
        requestAnimationFrame(tick);
      });
    },{threshold:0.5});
    countEls.forEach(el=>obs.observe(el));
  }

  /* 3. 3D TILT */
  const tilt=document.getElementById('hero-tilt');
  if(tilt){
    const shine=tilt.querySelector('.tilt-shine');
    tilt.addEventListener('mousemove',e=>{
      const r=tilt.getBoundingClientRect();
      const rx=((e.clientY-r.top-r.height/2)/(r.height/2))*-9;
      const ry=((e.clientX-r.left-r.width/2)/(r.width/2))*9;
      tilt.style.transform=`rotateX(${rx}deg) rotateY(${ry}deg)`;
      if(shine){
        const mx=((e.clientX-r.left)/r.width*100).toFixed(1);
        const my=((e.clientY-r.top)/r.height*100).toFixed(1);
        shine.style.setProperty('--mx',mx+'%');
        shine.style.setProperty('--my',my+'%');
      }
    });
    tilt.addEventListener('mouseleave',()=>{
      tilt.style.transform='rotateX(0deg) rotateY(0deg)';
      if(shine)shine.style.opacity='0';
    });
  }

  /* 4. PARTICLE CANVAS */
  const canvas=document.getElementById('hero-canvas');
  if(canvas){
    const ctx=canvas.getContext('2d'); let W,H,dots=[];
    function resize(){W=canvas.width=canvas.parentElement.offsetWidth;H=canvas.height=canvas.parentElement.offsetHeight;}
    window.addEventListener('resize',resize); resize();
    function Dot(){this.x=Math.random()*W;this.y=Math.random()*H;this.r=Math.random()*2+0.5;this.dx=(Math.random()-.5)*.28;this.dy=(Math.random()-.5)*.28;this.a=Math.random()*.35+.1;this.col=Math.random()<.55?'128,0,32':'47,136,99';}
    for(let n=0;n<58;n++)dots.push(new Dot());
    function draw(){
      ctx.clearRect(0,0,W,H);
      for(let i=0;i<dots.length;i++){
        const d=dots[i]; d.x+=d.dx; d.y+=d.dy;
        if(d.x<0||d.x>W)d.dx*=-1; if(d.y<0||d.y>H)d.dy*=-1;
        ctx.beginPath();ctx.arc(d.x,d.y,d.r,0,Math.PI*2);
        ctx.fillStyle=`rgba(${d.col},${d.a})`;ctx.fill();
        for(let j=i+1;j<dots.length;j++){
          const d2=dots[j],dx=d.x-d2.x,dy=d.y-d2.y,dist=Math.sqrt(dx*dx+dy*dy);
          if(dist<115){ctx.beginPath();ctx.moveTo(d.x,d.y);ctx.lineTo(d2.x,d2.y);ctx.strokeStyle=`rgba(128,0,32,${.065*(1-dist/115)})`;ctx.lineWidth=.5;ctx.stroke();}
        }
      }
      requestAnimationFrame(draw);
    }
    draw();
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

<!-- ════════════════════════ DIRECTORY PREVIEW ════════════════════════════ -->
<section id="directory" class="max-w-7xl mx-auto px-6 py-14">
  <div class="flex justify-between items-end gap-5 mb-8 flex-wrap">
    <div>
      <span class="font-mono text-[11px] tracking-widest text-[#2F8863] block mb-2 uppercase">
        <?= __('অ্যালামনাই ডিরেক্টরি', 'Alumni Directory') ?>
      </span>
      <h2 class="font-serif text-[clamp(24px,3vw,34px)] font-semibold text-[#101820]">
        <?= __('প্রতিটি ভেরিফাইড প্রোফাইল,<br>মাত্র এক ক্লিকে।', 'Every verified profile,<br>just one click away.') ?>
      </h2>
    </div>
    <p class="text-[14px] text-[#6B7178] max-w-[320px]">
      <?= __('ব্যাচ, পেশা ও অবস্থান অনুযায়ী সার্চ করুন — প্রতিটি প্রোফাইল অ্যাডমিন কর্তৃক অনুমোদিত।',
            'Search by batch, profession, or location — every profile is admin-approved.') ?>
    </p>
  </div>

  <?php if (empty($alumni_featured)): ?>
  <div class="text-center py-16 text-[#9CA3AF] text-[14px]">
    <?= __('অ্যালামনাই প্রোফাইলগুলো ভেরিফাই হওয়ার পর এখানে দেখা যাবে।', 'Alumni profiles will appear here once verified.') ?>
  </div>
  <?php else: ?>
  <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-4">
    <?php foreach ($alumni_featured as $alum): ?>
    <a href="<?= url('/directory/' . $alum['id']) ?>"
       class="p-5 rounded-2xl text-center block hover-lift"
       style="background:rgba(255,255,255,0.72);border:1px solid rgba(16,24,32,0.07);backdrop-filter:blur(14px);">
      <div class="w-14 h-14 rounded-full mx-auto mb-3 flex items-center justify-center font-serif font-semibold text-[18px] overflow-hidden"
           style="background:linear-gradient(135deg,#153548,#2F8863);color:#FAFAFA;border:2px solid rgba(16,24,32,0.08);">
        <?php if (!empty($alum['avatar'])): ?>
          <img src="<?= avatar_url($alum['avatar']) ?>" alt="<?= e($alum['name'] ?? '') ?>" class="w-full h-full object-cover" onerror="this.onerror=null; this.parentElement.innerHTML='<?= initials($alum['name'] ?? 'A') ?>';">
        <?php else: ?>
          <?= initials($alum['name'] ?? 'A') ?>
        <?php endif; ?>
      </div>
      <div class="font-semibold text-[14px] text-[#101820] leading-tight"><?= e($alum['name'] ?? '') ?></div>
      <div class="text-[11.5px] text-[#9CA3AF] mt-0.5">
        <?= $alum['batch_year'] ? __('ব্যাচ ', 'Batch ') . $alum['batch_year'] : '' ?>
        <?= !empty($alum['job_title']) ? ' · ' . e($alum['job_title']) : '' ?>
      </div>
      <span class="inline-flex items-center gap-1 font-mono text-[10px] text-[#2F8863] mt-2.5 px-2.5 py-1 rounded-full"
            style="background:rgba(47,136,99,0.1);border:1px solid rgba(47,136,99,0.2);">
        <span class="w-1.5 h-1.5 rounded-full bg-[#2F8863]"></span><?= __('ভেরিফাইড', 'Verified') ?>
      </span>
    </a>
    <?php endforeach; ?>
  </div>
  <?php endif; ?>

  <div class="text-center mt-8">
    <a href="<?= url('/directory') ?>"
       class="inline-flex items-center gap-2 px-7 py-3 rounded-2xl text-[14px] font-semibold text-[#800020] transition-all hover:-translate-y-0.5 hover:shadow-md"
       style="background:rgba(128,0,32,0.06);border:1px solid rgba(128,0,32,0.2);">
      <?= __('সম্পূর্ণ ডিরেক্টরি দেখুন', 'View Full Directory') ?>
      <i class="fa-solid fa-arrow-right text-[12px]"></i>
    </a>
  </div>
</section>

<!-- ══════════════════════ SUCCESS STORIES ════════════════════════════════ -->
<section id="stories" class="max-w-7xl mx-auto px-6 py-14">
  <!-- Soft divider line -->
  <div class="h-px mb-14 bg-gradient-to-r from-transparent via-[#800020]/20 to-transparent"></div>

  <div class="flex justify-between items-end gap-5 mb-8 flex-wrap">
    <div>
      <span class="font-mono text-[11px] tracking-widest text-[#2F8863] block mb-2 uppercase">
        <?= __('সফলতার গল্প', 'Success Stories') ?>
      </span>
      <h2 class="font-serif text-[clamp(24px,3vw,34px)] font-semibold text-[#101820]">
        <?= __('আইপিএইচের ভিত্তি থেকে<br>গড়ে ওঠা বাস্তব ক্যারিয়ার।', 'Real careers built<br>on IPH foundations.') ?>
      </h2>
    </div>
    <a href="<?= url('/stories') ?>" class="text-[14px] text-[#800020] font-semibold hover:underline flex items-center gap-1">
      <?= __('সব গল্প দেখুন', 'All Stories') ?>
      <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
    </a>
  </div>

  <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
    <?php
    $displayStories = !empty($successStories) ? $successStories : [
      ['batch_year' => '2010',
       'title'      => __('জাতীয় নীতিনির্ধারণে নেতৃত্ব', 'From district health office to national policy'),
       'excerpt'    => __('একটি জেলা স্বাস্থ্য অফিস থেকে জাতীয় পর্যায়ে নীতিনির্ধারণী ভূমিকায়।', 'Started at a district health office, now shapes national public health policy.'),
       'slug' => '#'],
      ['batch_year' => '2015',
       'title'      => __('WHO বুলেটিনে গবেষণা প্রকাশ', 'Research featured in the WHO Bulletin'),
       'excerpt'    => __('ঢাকায় একটি এপিডেমিওলজি ল্যাব পরিচালনা করছেন যার ফলাফল আন্তর্জাতিক মহলে স্বীকৃত।', 'Now leads an epidemiology lab in Dhaka whose findings have gained international recognition.'),
       'slug' => '#'],
      ['batch_year' => '2020',
       'title'      => __('বছরে ৩০০+ স্বাস্থ্যকর্মীর প্রশিক্ষক', 'Training 300+ health workers every year'),
       'excerpt'    => __('অ্যাসোসিয়েশন-অনুমোদিত সার্টিফিকেশন প্রোগ্রামের মাধ্যমে স্বাস্থ্য খাতে দক্ষ মানবসম্পদ গড়ছেন।', 'Runs the association-endorsed certification track, building skilled health workers across the country.'),
       'slug' => '#'],
    ];
    foreach ($displayStories as $story):
      $link = ($story['slug'] === '#') ? '#' : url('/stories/' . e($story['slug']));
    ?>
    <a href="<?= $link ?>"
       class="p-7 rounded-2xl block hover-lift group"
       style="background:rgba(255,255,255,0.72);border:1px solid rgba(16,24,32,0.07);backdrop-filter:blur(14px);">
      <span class="inline-block font-mono text-[10.5px] text-[#A22638] px-2.5 py-1 rounded-full mb-4"
            style="background:rgba(162,38,56,0.08);border:1px solid rgba(162,38,56,0.2);">
        <?= __('ব্যাচ ', 'Batch ') ?><?= e($story['batch_year']) ?>
      </span>
      <h4 class="font-serif text-[18px] font-semibold text-[#101820] mb-3 leading-snug group-hover:text-[#800020] transition-colors">
        <?= e($story['title']) ?>
      </h4>
      <p class="text-[13.5px] text-[#6B7178] leading-relaxed"><?= e($story['excerpt']) ?></p>
      <div class="mt-4 text-[13px] font-medium text-[#800020] flex items-center gap-1">
        <?= __('পড়ুন', 'Read more') ?>
        <svg class="w-3.5 h-3.5 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
      </div>
    </a>
    <?php endforeach; ?>
  </div>
</section>

<!-- ══════════════════════════ COMMITTEE ORGANOGRAM ══════════════════════ -->
<style>
  .laser-flow-line { animation: laser-flow 2s linear infinite; }
  @keyframes laser-flow { to { stroke-dashoffset: -40; } }
</style>
<section class="max-w-7xl mx-auto px-4 py-16">
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
        ['<i class="fa-solid fa-ticket" style="color:#A22638;font-size:18px;"></i>',          __('অগ্রাধিকার প্রবেশাধিকার', 'Priority Access'),       __('বার্ষিক মিলনমেলা, পুনর্মিলনী এবং বিশেষ ওয়ার্কশপে অগ্রাধিকার বুকিং ও আসন সুবিধা।', 'Priority booking and seating at the annual reunion, conferences, and special workshops.'), '#A22638'],
      ];
      foreach ($benefits as [$icon, $title, $desc, $accent]):
      ?>
      <div class="p-6 rounded-2xl hover-lift"
           style="background:rgba(255,255,255,0.65);border:1px solid rgba(16,24,32,0.06);backdrop-filter:blur(10px);">
        <div class="w-11 h-11 rounded-2xl flex items-center justify-center mb-4"
             style="background:<?= $accent ?>18;">
          <?= $icon ?>
        </div>
        <h4 class="font-semibold text-[15px] text-[#101820] mb-2"><?= $title ?></h4>
        <p class="text-[12.5px] text-[#6B7178] leading-relaxed"><?= $desc ?></p>
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
