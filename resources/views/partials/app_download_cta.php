<?php
/**
 * Interactive Mobile App Download CTA Component
 * Used across Public Website (Homepage, Footer, etc.)
 */
use App\Models\Setting;

$settingModel = new Setting();
$playStoreUrl = (string)$settingModel->get('app_google_play_url', '');
$appStoreUrl  = (string)$settingModel->get('app_apple_store_url', '');
$apkUrl       = (string)$settingModel->get('app_apk_url', '');
$ctaEnabled   = (string)$settingModel->get('app_cta_enabled', '1') !== '0';

// If feature disabled or no links provided, do not render
if (!$ctaEnabled || (empty($playStoreUrl) && empty($appStoreUrl) && empty($apkUrl))) {
    return;
}

$activeLinkCount = (!empty($playStoreUrl) ? 1 : 0) + (!empty($appStoreUrl) ? 1 : 0) + (!empty($apkUrl) ? 1 : 0);
?>

<div class="relative overflow-hidden my-12 mx-auto max-w-7xl px-4 sm:px-6 font-['Kalpurush','Inter',sans-serif]">
  <div class="relative rounded-3xl p-6 sm:p-10 border border-amber-400/20 shadow-2xl backdrop-blur-xl overflow-hidden"
       style="background: radial-gradient(120% 120% at 85% 10%, rgba(212, 175, 55, 0.15) 0%, rgba(128, 0, 32, 0.45) 45%, rgba(15, 23, 42, 0.95) 100%);">
    
    <!-- Decorative Glowing Elements -->
    <div class="absolute -top-24 -right-24 w-72 h-72 rounded-full bg-amber-400/20 blur-3xl pointer-events-none animate-pulse"></div>
    <div class="absolute -bottom-24 -left-24 w-72 h-72 rounded-full bg-rose-600/20 blur-3xl pointer-events-none"></div>

    <div class="relative z-10 flex flex-col lg:flex-row items-center justify-between gap-8">
      
      <!-- Left Content / Pitch -->
      <div class="max-w-2xl text-center lg:text-left">
        <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-white/10 border border-white/20 text-amber-300 text-[12px] font-mono font-bold uppercase tracking-wider mb-4 shadow-sm backdrop-blur-md">
          <span class="w-2 h-2 rounded-full bg-emerald-400 animate-ping"></span>
          <i class="fa-solid fa-mobile-screen-button"></i>
          <span><?= __('অফিসিয়াল মোবাইল অ্যাপ্লিকেশন', 'Official Mobile App') ?></span>
        </div>

        <h3 class="text-[26px] sm:text-[34px] font-serif font-bold text-white leading-tight drop-shadow-md">
          <?= __('আইপিএইচ অ্যালামনাই নেটওয়ার্ক এখন আপনার হাতের মুঠোয়', 'IPH Alumni Network — Now in Your Pocket') ?>
        </h3>

        <p class="mt-3 text-[14px] sm:text-[16px] text-slate-200/90 leading-relaxed">
          <?= __('স্মার্ট মেম্বারশিপ ডিজিটাল আইডি কার্ড, গেটপাস কিউআর স্ক্যানার, জরুরী রক্তের গ্রুপ ডিরেক্টরি এবং ইভেন্ট রেজিস্ট্রেশনের সকল সুবিধা উপভোগ করতে এখনই আমাদের মোবাইল অ্যাপ ডাউনলোড করুন।', 
                'Access your Digital Membership Card, Event Gate Pass, Emergency Blood Directory, and Reunion Updates anywhere, anytime.') ?>
        </p>

        <!-- Feature Pills -->
        <div class="flex flex-wrap items-center justify-center lg:justify-start gap-2.5 mt-5">
          <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg bg-black/30 border border-white/10 text-white/80 text-[12px]">
            <i class="fa-solid fa-id-card text-amber-300"></i> ডিজিটাল আইডি কার্ড
          </span>
          <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg bg-black/30 border border-white/10 text-white/80 text-[12px]">
            <i class="fa-solid fa-qrcode text-emerald-300"></i> গেটপাস কিউআর
          </span>
          <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg bg-black/30 border border-white/10 text-white/80 text-[12px]">
            <i class="fa-solid fa-bell text-rose-300"></i> তাৎক্ষণিক নোটিশ
          </span>
          <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg bg-black/30 border border-white/10 text-white/80 text-[12px]">
            <i class="fa-solid fa-droplet text-red-400"></i> ব্লাড ডোনার ডিরেক্টরি
          </span>
        </div>
      </div>

      <!-- Right Side CTA Badges / Buttons -->
      <div class="flex flex-col sm:flex-row lg:flex-col items-center justify-center gap-3.5 shrink-0 w-full sm:w-auto">
        
        <?php if (!empty($playStoreUrl)): ?>
        <!-- Google Play Store Button -->
        <a href="<?= e($playStoreUrl) ?>" 
           target="_blank" 
           rel="noopener noreferrer"
           class="group relative inline-flex items-center gap-4 px-6 py-3.5 rounded-2xl bg-black/80 hover:bg-black border border-white/20 hover:border-emerald-400/60 shadow-xl hover:shadow-emerald-500/20 text-white transition-all transform hover:-translate-y-1 active:scale-95 w-full sm:w-64">
          <div class="text-[28px] text-emerald-400 group-hover:scale-110 transition-transform">
            <i class="fa-brands fa-google-play"></i>
          </div>
          <div class="text-left leading-tight">
            <div class="text-[10px] uppercase font-mono tracking-widest text-slate-400"><?= __('ডাউনলোড করুন', 'GET IT ON') ?></div>
            <div class="text-[17px] font-bold font-sans text-white tracking-wide">Google Play</div>
          </div>
          <div class="ml-auto text-emerald-400/50 group-hover:text-emerald-400 group-hover:translate-x-1 transition-all">
            <i class="fa-solid fa-arrow-right text-[13px]"></i>
          </div>
        </a>
        <?php endif; ?>

        <?php if (!empty($appStoreUrl)): ?>
        <!-- Apple App Store Button -->
        <a href="<?= e($appStoreUrl) ?>" 
           target="_blank" 
           rel="noopener noreferrer"
           class="group relative inline-flex items-center gap-4 px-6 py-3.5 rounded-2xl bg-black/80 hover:bg-black border border-white/20 hover:border-sky-400/60 shadow-xl hover:shadow-sky-500/20 text-white transition-all transform hover:-translate-y-1 active:scale-95 w-full sm:w-64">
          <div class="text-[30px] text-slate-200 group-hover:scale-110 transition-transform">
            <i class="fa-brands fa-apple"></i>
          </div>
          <div class="text-left leading-tight">
            <div class="text-[10px] uppercase font-mono tracking-widest text-slate-400"><?= __('ডাউনলোড করুন', 'Download on the') ?></div>
            <div class="text-[17px] font-bold font-sans text-white tracking-wide">App Store</div>
          </div>
          <div class="ml-auto text-sky-400/50 group-hover:text-sky-400 group-hover:translate-x-1 transition-all">
            <i class="fa-solid fa-arrow-right text-[13px]"></i>
          </div>
        </a>
        <?php endif; ?>

        <?php if (!empty($apkUrl)): ?>
        <!-- Direct APK Download Button -->
        <a href="<?= e($apkUrl) ?>" 
           target="_blank" 
           rel="noopener noreferrer"
           class="group relative inline-flex items-center gap-3 px-5 py-2.5 rounded-xl bg-white/10 hover:bg-white/20 border border-white/20 text-amber-300 text-[13px] font-semibold transition-all transform hover:-translate-y-0.5 w-full sm:w-64 justify-center">
          <i class="fa-solid fa-file-arrow-down text-[15px]"></i>
          <span><?= __('সরাসরি APK ডাউনলোড', 'Download Direct APK') ?></span>
        </a>
        <?php endif; ?>

      </div>

    </div>
  </div>
</div>
