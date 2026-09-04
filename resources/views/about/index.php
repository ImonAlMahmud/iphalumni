<?php
/**
 * Detailed About Us Page View — IPH Alumni Association
 */
$appName = function_exists('env') ? env('APP_NAME', 'IPH Alumni Association') : 'IPH Alumni Association';
?>
<div class="max-w-7xl mx-auto px-6 py-12 font-['Kalpurush']">

  <!-- Header Banner -->
  <div class="text-center max-w-3xl mx-auto mb-16">
    <span class="font-mono text-[11px] tracking-widest text-[#2F8863] uppercase block mb-2 font-bold">
      <?= __('আমাদের পরিচিতি ও মিশন', 'ABOUT US & OUR MISSION') ?>
    </span>
    <h1 class="font-serif text-[clamp(30px,4.5vw,44px)] font-bold text-[#101820] leading-tight mb-4">
      <?= __('ইনস্টিটিউট অব পাবলিক হেলথ অ্যালামনাই অ্যাসোসিয়েশন', 'Institute of Public Health Alumni Association') ?>
    </h1>
    <p class="text-[16px] text-[#6B7178] leading-relaxed">
      <?= __('জনস্বাস্থ্য ক্ষেত্রে শিক্ষা, গবেষণা এবং বৈশ্বিক সমাজ সেবায় নিবেদিত আইপিএইচ গ্র্যাজুয়েটদের ঐক্যবদ্ধ একটি ঐতিহ্যবাহী সংগঠন।',
            'A historic network of IPH graduates dedicated to education, research, and global community health service.') ?>
    </p>
  </div>

  <!-- Legacy & Impact Cards Grid -->
  <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-20">
    <div class="p-8 rounded-3xl bg-white border border-slate-200/80 shadow-sm hover:-translate-y-1 transition-transform">
      <div class="w-12 h-12 rounded-2xl bg-[#800020]/10 text-[#800020] flex items-center justify-center text-[20px] mb-6">
        <i class="fa-solid fa-graduation-cap"></i>
      </div>
      <h3 class="font-serif text-[20px] font-bold text-[#101820] mb-3">
        <?= __('আমাদের ইতিহাস ও ঐতিহ্য', 'Our Legacy') ?>
      </h3>
      <p class="text-[14px] text-[#6B7178] leading-relaxed">
        <?= __('মহাখালী, ঢাকায় অবস্থিত ঐতিহ্যবাহী ইনস্টিটিউট অব পাবলিক হেলথ (IPH) থেকে পাশ করা সকল গ্র্যাজুয়েটদের একত্রিত করতে ২০১৫ সালে পথচলা শুরু করে।',
              'Started its journey to bring together all graduates of the prestigious Institute of Public Health (IPH) located in Mohakhali, Dhaka.') ?>
      </p>
    </div>

    <div class="p-8 rounded-3xl bg-white border border-slate-200/80 shadow-sm hover:-translate-y-1 transition-transform">
      <div class="w-12 h-12 rounded-2xl bg-[#2F8863]/10 text-[#2F8863] flex items-center justify-center text-[20px] mb-6">
        <i class="fa-solid fa-bullseye"></i>
      </div>
      <h3 class="font-serif text-[20px] font-bold text-[#101820] mb-3">
        <?= __('লক্ষ্য ও ভিশন', 'Vision & Mission') ?>
      </h3>
      <p class="text-[14px] text-[#6B7178] leading-relaxed">
        <?= __('বিশ্বব্যাপী অবস্থানরত অ্যালামনাইদের মধ্যে নিবিড় নেটওয়ার্ক গড়ে তোলা, পেশাদার সহযোগিতা বৃদ্ধি এবং পাবলিক হেলথ গবেষণার মানোন্নয়নে অবদান রাখা।',
              'Building strong networks among global alumni, enhancing professional collaboration, and advancing public health research.') ?>
      </p>
    </div>

    <div class="p-8 rounded-3xl bg-white border border-slate-200/80 shadow-sm hover:-translate-y-1 transition-transform">
      <div class="w-12 h-12 rounded-2xl bg-[#D97706]/10 text-[#D97706] flex items-center justify-center text-[20px] mb-6">
        <i class="fa-solid fa-hand-holding-heart"></i>
      </div>
      <h3 class="font-serif text-[20px] font-bold text-[#101820] mb-3">
        <?= __('সামাজিক ও মানব কল্যাণ', 'Social Welfare') ?>
      </h3>
      <p class="text-[14px] text-[#6B7178] leading-relaxed">
        <?= __('মেধাবী শিক্ষার্থীদের স্কলারশিপ ও ফেলোশিপ প্রদান, পেশাগত কর্মসংস্থান তৈরি এবং দেশ-বিদেশের যেকোনো স্বাস্থ্য সংকটে মানব কল্যাণে এগিয়ে আসা।',
              'Providing scholarships to meritorious students, driving employment, and aiding human welfare during public health emergencies.') ?>
      </p>
    </div>
  </div>

  <!-- Detailed Core Objectives Section -->
  <div class="p-8 md:p-12 rounded-3xl bg-slate-900 text-white mb-20 relative overflow-hidden shadow-xl"
       style="background: radial-gradient(circle at 90% 10%, rgba(128,0,32,0.35), transparent 50%), radial-gradient(circle at 10% 90%, rgba(47,136,99,0.3), transparent 50%), #0F172A;">
    <div class="max-w-3xl mb-10">
      <span class="font-mono text-[11px] tracking-widest text-[#2F8863] uppercase block mb-2 font-bold">
        <?= __('সংগঠনের মূল স্তম্ভসমূহ', 'OUR CORE OBJECTIVES') ?>
      </span>
      <h2 class="font-serif text-[clamp(26px,3.5vw,36px)] font-bold text-white mb-4">
        <?= __('যে উদ্দেশ্যে আমরা কাজ করি', 'What Drives Our Association') ?>
      </h2>
      <p class="text-[15px] text-slate-300 leading-relaxed">
        <?= __('ইনস্টিটিউট অব পাবলিক হেলথ অ্যালামনাই অ্যাসোসিয়েশন শুধু একটি প্রাক্তন শিক্ষার্থীদের নেটওয়ার্ক নয়, এটি জনস্বাস্থ্য খাতের উন্নয়ন ও নতুন প্রজন্মকে অনুপ্রেরণা দেওয়ার অন্যতম চাবিকাঠি।',
              'The IPH Alumni Association is not just a former student network, it is a driving force behind public health development and inspiring new generations.') ?>
      </p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
      <div class="flex items-start gap-4 p-5 rounded-2xl bg-white/5 border border-white/10 backdrop-blur-sm">
        <div class="w-8 h-8 rounded-xl bg-[#800020] text-white flex items-center justify-center text-[13px] shrink-0 font-bold">১</div>
        <div>
          <h4 class="font-bold text-[16px] text-white mb-1"><?= __('গবেষণা ও সেমিনার আয়োজন', 'Research & Seminars') ?></h4>
          <p class="text-[13.5px] text-slate-300"><?= __('পাবলিক হেলথ বিষয়ক জাতীয় ও আন্তর্জাতিক কনফারেন্স, বৈজ্ঞানিক সেমিনার এবং জার্নাল প্রকাশনা পরিচালনা করা।', 'Hosting national and international conferences, scientific seminars, and publications.') ?></p>
        </div>
      </div>

      <div class="flex items-start gap-4 p-5 rounded-2xl bg-white/5 border border-white/10 backdrop-blur-sm">
        <div class="w-8 h-8 rounded-xl bg-[#2F8863] text-white flex items-center justify-center text-[13px] shrink-0 font-bold">২</div>
        <div>
          <h4 class="font-bold text-[16px] text-white mb-1"><?= __('ক্যারিয়ার গাইডেন্স ও জব সুযোগ', 'Career Guidance & Jobs') ?></h4>
          <p class="text-[13.5px] text-slate-300"><?= __('নতুন গ্র্যাজুয়েটদের জন্য ইন্টার্নশিপ, জব সার্কুলার প্রকাশ এবং দেশ-বিদেশে প্রফেশনাল মেন্টরশিপ দেওয়া।', 'Providing internships, job openings, and global professional mentorship for new graduates.') ?></p>
        </div>
      </div>

      <div class="flex items-start gap-4 p-5 rounded-2xl bg-white/5 border border-white/10 backdrop-blur-sm">
        <div class="w-8 h-8 rounded-xl bg-[#D97706] text-white flex items-center justify-center text-[13px] shrink-0 font-bold">৩</div>
        <div>
          <h4 class="font-bold text-[16px] text-white mb-1"><?= __('বৃত্তি ও আর্থিক সহায়তা', 'Scholarships & Grants') ?></h4>
          <p class="text-[13.5px] text-slate-300"><?= __('অসমর্থ ও মেধাবী শিক্ষার্থীদের উচ্চশিক্ষার পথ প্রশস্ত করতে নিয়মিত স্কলারশিপ ও রিসার্চ ফান্ড প্রদান করা।', 'Providing regular scholarships and research funds to needy and meritorious students.') ?></p>
        </div>
      </div>

      <div class="flex items-start gap-4 p-5 rounded-2xl bg-white/5 border border-white/10 backdrop-blur-sm">
        <div class="w-8 h-8 rounded-xl bg-[#800020] text-white flex items-center justify-center text-[13px] shrink-0 font-bold">৪</div>
        <div>
          <h4 class="font-bold text-[16px] text-white mb-1"><?= __('পুনর্মিলনী ও সাংস্কৃতিক মেলবন্ধন', 'Reunion & Cultural Bond') ?></h4>
          <p class="text-[13.5px] text-slate-300"><?= __('বার্ষিক রিইউনিয়ন, অ্যালামনাই মিট-আপ এবং সাংস্কৃতিক অনুষ্ঠানের মাধ্যমে পুরনো বন্ধুদের পুনর্মিলন।', 'Reconnecting batchmates through annual reunions, alumni meet-ups, and cultural events.') ?></p>
        </div>
      </div>
    </div>
  </div>

  <!-- Constitution Showcase Banner -->
  <div class="p-8 md:p-12 rounded-3xl relative overflow-hidden flex flex-col md:flex-row items-center justify-between gap-8 shadow-sm border border-slate-200/90"
       style="background: linear-gradient(135deg, rgba(128,0,32,0.06), rgba(47,136,99,0.05)), #FFFFFF;">
    <div class="space-y-3 max-w-2xl">
      <div class="inline-flex items-center gap-2 px-3.5 py-1 rounded-full bg-[#800020]/10 text-[#800020] font-mono text-[11px] font-semibold">
        <i class="fa-solid fa-scroll text-[11px]"></i>
        <?= __('অফিশিয়াল বিধিমালা ও পরিচালনা নীতি', 'Official Rules & Guidelines') ?>
      </div>
      <h3 class="font-serif text-[26px] font-bold text-[#101820]">
        <?= __('সংগঠনের গঠনতন্ত্র (IPHA Constitution)', 'Association Constitution') ?>
      </h3>
      <p class="text-[15px] text-[#6B7178] leading-relaxed">
        <?= __('অ্যাসোসিয়েশনের কার্যপ্রণালী, সদস্যপদের শর্তাবলী, ২৩-সদস্য বিশিষ্ট কার্যনির্বাহী পরিষদ গঠন এবং ১৮টি ধারা সমন্বিত অফিশিয়াল গঠনতন্ত্র বিস্তারিত দেখুন।',
              'Explore the official constitution featuring 18 articles, executive committee structures, and membership rules.') ?>
      </p>
    </div>
    <div class="shrink-0 flex flex-col sm:flex-row gap-3">
      <a href="<?= url('/constitution') ?>"
         class="inline-flex items-center gap-2.5 px-6 py-3.5 rounded-2xl text-[14.5px] font-semibold text-white bg-[#800020] hover:bg-[#66001a] transition-all shadow-md hover:-translate-y-0.5">
        <i class="fa-solid fa-book-bookmark text-[14px]"></i>
        <?= __('গঠনতন্ত্র বিস্তারিত পড়ুন', 'Read Full Constitution') ?>
        <i class="fa-solid fa-arrow-right text-[12px]"></i>
      </a>
      <a href="<?= url('/constitution/pdf?print=1') ?>" target="_blank"
         class="inline-flex items-center gap-2.5 px-6 py-3.5 rounded-2xl text-[14.5px] font-semibold text-[#101820] bg-white border border-slate-300 hover:bg-slate-50 transition-all shadow-sm">
        <i class="fa-solid fa-file-pdf text-[14px] text-[#800020]"></i>
        <?= __('PDF ডাউনলোড', 'PDF Download') ?>
      </a>
    </div>
  </div>

</div>
