<?php
/**
 * IPHA Constitution View - 100% Exact Match with docx document
 */
?>
<div class="max-w-7xl mx-auto px-6 py-10 font-['Kalpurush']">
  
  <!-- Breadcrumb -->
  <div class="flex items-center gap-2 text-[13px] text-[#6B7178] mb-6">
    <a href="<?= url('/') ?>" class="hover:text-[#800020] transition-colors"><?= __('হোম', 'Home') ?></a>
    <span>/</span>
    <a href="<?= url('/about') ?>" class="hover:text-[#800020] transition-colors"><?= __('পরিচিতি', 'About') ?></a>
    <span>/</span>
    <span class="text-[#101820] font-medium"><?= __('গঠনতন্ত্র', 'Constitution') ?></span>
  </div>

  <!-- Header Banner -->
  <div class="p-6 md:p-8 rounded-3xl mb-8 flex flex-col md:flex-row items-center justify-between gap-6 relative overflow-hidden shadow-sm font-['Kalpurush']"
       style="background: linear-gradient(135deg, rgba(128,0,32,0.06), rgba(47,136,99,0.05)), #FFFFFF; border:1px solid rgba(128,0,32,0.15);">
    <div class="text-left">
      <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-[#800020]/10 text-[#800020] font-mono text-[11px] font-semibold mb-2">
        <i class="fa-solid fa-scroll text-[11px]"></i>
        OFFICIAL CONSTITUTION DOCUMENT
      </div>
      <h1 class="font-serif text-[clamp(24px,3.2vw,32px)] font-bold text-[#101820] mb-1">
        ইন্সটিটিউট অব পাবলিক হেলথ এলামনাই অ্যাসোসিয়েশন
      </h1>
      <h2 class="text-[15px] font-serif text-[#800020] font-semibold mb-1">
        Institute of Public Health Alumni Association (IPHA) — গঠনতন্ত্র
      </h2>
      <p class="text-[13.5px] text-[#6B7178] font-medium flex items-center gap-2">
        <i class="fa-solid fa-location-dot text-[#2F8863]"></i>
        ইন্সটিটিউট অব পাবলিক হেলথ, মহাখালী, ঢাকা-১২১২।
      </p>
    </div>

    <div class="shrink-0">
      <a href="<?= url('/constitution/pdf?print=1') ?>" target="_blank"
         class="inline-flex items-center gap-2.5 px-6 py-3.5 rounded-2xl text-[14px] font-semibold text-white bg-[#800020] hover:bg-[#66001a] transition-all shadow-md hover:-translate-y-0.5">
        <i class="fa-solid fa-file-pdf text-[16px]"></i>
        <?= __('গঠনতন্ত্র PDF ডাউনলোড করুন', 'Download Constitution PDF') ?>
      </a>
    </div>
  </div>

  <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
    
    <!-- Quick Navigation Sticky Sidebar -->
    <div class="lg:col-span-3 sticky top-24 p-5 rounded-2xl bg-white shadow-sm border border-slate-100 font-['Kalpurush']" style="backdrop-filter:blur(10px);">
      <h3 class="text-[13px] font-bold text-[#101820] tracking-wider uppercase mb-4 pb-2 border-b border-[#800020]/20 flex items-center gap-2">
        <i class="fa-solid fa-list-ul text-[#800020]"></i>
        সূচিপত্র (Table of Contents)
      </h3>
      <nav class="space-y-1.5 text-[13px] max-h-[75vh] overflow-y-auto pr-1">
        <a href="#dhara-1" class="block px-3 py-1.5 rounded-xl text-[#6B7178] hover:text-[#800020] hover:bg-rose-50/60 transition-colors font-medium">১. সংগঠনের নাম</a>
        <a href="#dhara-2" class="block px-3 py-1.5 rounded-xl text-[#6B7178] hover:text-[#800020] hover:bg-rose-50/60 transition-colors font-medium">২. অবস্থান</a>
        <a href="#dhara-3" class="block px-3 py-1.5 rounded-xl text-[#6B7178] hover:text-[#800020] hover:bg-rose-50/60 transition-colors font-medium">৩. মনোগ্রাম</a>
        <a href="#dhara-4" class="block px-3 py-1.5 rounded-xl text-[#6B7178] hover:text-[#800020] hover:bg-rose-50/60 transition-colors font-medium">৪. প্রকৃতি ও বৈশিষ্ট্য</a>
        <a href="#dhara-5" class="block px-3 py-1.5 rounded-xl text-[#6B7178] hover:text-[#800020] hover:bg-rose-50/60 transition-colors font-medium">৫. উদ্দেশ্য ও লক্ষ্য</a>
        <a href="#dhara-6" class="block px-3 py-1.5 rounded-xl text-[#6B7178] hover:text-[#800020] hover:bg-rose-50/60 transition-colors font-medium">৬. কার্যাদি</a>
        <a href="#dhara-7" class="block px-3 py-1.5 rounded-xl text-[#6B7178] hover:text-[#800020] hover:bg-rose-50/60 transition-colors font-medium">৭. সদস্যপদ</a>
        <a href="#dhara-8" class="block px-3 py-1.5 rounded-xl text-[#6B7178] hover:text-[#800020] hover:bg-rose-50/60 transition-colors font-medium">৮. সদস্যপদ স্থগিত বা বাতিল</a>
        <a href="#dhara-9" class="block px-3 py-1.5 rounded-xl text-[#6B7178] hover:text-[#800020] hover:bg-rose-50/60 transition-colors font-medium">৯. সাংগঠনিক কাঠামো</a>
        <a href="#dhara-10a" class="block px-3 py-1.5 rounded-xl text-[#6B7178] hover:text-[#800020] hover:bg-rose-50/60 transition-colors font-medium">১০.(ক) উপদেষ্টা পরিষদ</a>
        <a href="#dhara-10b" class="block px-3 py-1.5 rounded-xl text-[#6B7178] hover:text-[#800020] hover:bg-rose-50/60 transition-colors font-medium">১০.(খ) কার্য নির্বাহী পরিষদ</a>
        <a href="#dhara-11" class="block px-3 py-1.5 rounded-xl text-[#6B7178] hover:text-[#800020] hover:bg-rose-50/60 transition-colors font-medium">১১. পরিষদ গঠন</a>
        <a href="#dhara-12" class="block px-3 py-1.5 rounded-xl text-[#6B7178] hover:text-[#800020] hover:bg-rose-50/60 transition-colors font-medium">১২. কর্মকর্তাদের দায়িত্ব ও কর্মপরিধি</a>
        <a href="#dhara-13" class="block px-3 py-1.5 rounded-xl text-[#6B7178] hover:text-[#800020] hover:bg-rose-50/60 transition-colors font-medium">১৩. পদত্যাগ ও শূন্য পদ</a>
        <a href="#dhara-14" class="block px-3 py-1.5 rounded-xl text-[#6B7178] hover:text-[#800020] hover:bg-rose-50/60 transition-colors font-medium">১৪. উপ-কমিটি</a>
        <a href="#dhara-15" class="block px-3 py-1.5 rounded-xl text-[#6B7178] hover:text-[#800020] hover:bg-rose-50/60 transition-colors font-medium">১৫. সভা</a>
        <a href="#dhara-16" class="block px-3 py-1.5 rounded-xl text-[#6B7178] hover:text-[#800020] hover:bg-rose-50/60 transition-colors font-medium">১৬. কোরাম ও সবার সিদ্ধান্ত গ্রহন</a>
        <a href="#dhara-17" class="block px-3 py-1.5 rounded-xl text-[#6B7178] hover:text-[#800020] hover:bg-rose-50/60 transition-colors font-medium">১৭. অর্থনীতি ও তহবিল</a>
        <a href="#dhara-18" class="block px-3 py-1.5 rounded-xl text-[#6B7178] hover:text-[#800020] hover:bg-rose-50/60 transition-colors font-medium">১৮. গঠনতন্ত্রের ব্যাখ্যা ও পরিবর্তন</a>
      </nav>
    </div>

    <!-- Main Constitution Text Content -->
    <div class="lg:col-span-9 space-y-9 bg-white p-7 md:p-11 rounded-3xl shadow-sm border border-slate-100 leading-relaxed text-[#2B303A] font-['Kalpurush']">

      <!-- Section 1 -->
      <section id="dhara-1" class="scroll-mt-28">
        <h3 class="font-serif text-[21px] font-bold text-[#800020] pb-2 border-b border-rose-100 flex items-center gap-2">
          <span class="w-8 h-8 rounded-lg bg-[#800020]/10 text-[#800020] text-[14px] flex items-center justify-center font-mono font-bold">১</span>
          সংগঠনের নামঃ
        </h3>
        <div class="mt-4 space-y-2 text-[15px]">
          <p><strong>বাংলা নাম:</strong> এই সংগঠনের নাম হবে <strong>"ইন্সটিটিউট অব পাবলিক হেলথ এলামনাই অ্যাসোসিয়েশন"</strong></p>
          <p><strong>ইংরেজী নাম:</strong> <strong>“Institute of Public Health Alumni Association (IPHA)”</strong></p>
        </div>
      </section>

      <!-- Section 2 -->
      <section id="dhara-2" class="scroll-mt-28">
        <h3 class="font-serif text-[21px] font-bold text-[#800020] pb-2 border-b border-rose-100 flex items-center gap-2">
          <span class="w-8 h-8 rounded-lg bg-[#800020]/10 text-[#800020] text-[14px] flex items-center justify-center font-mono font-bold">২</span>
          অবস্থানঃ
        </h3>
        <p class="mt-4 text-[15px]">এই সংগঠনের সদর দপ্তর এর অবস্থান হবে ইন্সটিটিউট অব পাবলিক হেলথ, মহাখালী, ঢাকা-১২১২।</p>
      </section>

      <!-- Section 3 & 4 -->
      <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <section id="dhara-3" class="scroll-mt-28 p-5 rounded-2xl bg-slate-50 border border-slate-100">
          <h3 class="font-serif text-[18px] font-bold text-[#101820] mb-2 flex items-center gap-2">
            <i class="fa-solid fa-stamp text-[#800020]"></i> ৩. মনোগ্রামঃ
          </h3>
          <p class="text-[14.5px]">এই সংগঠনের একটি মনোগ্রাম থাকবে।</p>
        </section>

        <section id="dhara-4" class="scroll-mt-28 p-5 rounded-2xl bg-slate-50 border border-slate-100">
          <h3 class="font-serif text-[18px] font-bold text-[#101820] mb-2 flex items-center gap-2">
            <i class="fa-solid fa-shield-heart text-[#2F8863]"></i> ৪. প্রকৃতি ও বৈশিষ্ট্যঃ
          </h3>
          <p class="text-[14.5px]">এই সংগঠন একটি অসাম্প্রদায়িক, অরাজনৈতিক এবং অলাভজনক, ক্যাম্পাসভিত্তিক সামাজিক সেবা সংস্থা।</p>
        </section>
      </div>

      <!-- Section 5 -->
      <section id="dhara-5" class="scroll-mt-28">
        <h3 class="font-serif text-[21px] font-bold text-[#800020] pb-2 border-b border-rose-100 flex items-center gap-2">
          <span class="w-8 h-8 rounded-lg bg-[#800020]/10 text-[#800020] text-[14px] flex items-center justify-center font-mono font-bold">৫</span>
          উদ্দেশ্য ও লক্ষ্যঃ
        </h3>
        <ul class="mt-4 space-y-3 text-[15px] text-slate-800">
          <li class="flex items-start gap-2">
            <span class="font-bold text-[#800020] shrink-0">ক).</span>
            <span>এই অ্যালামনাই অ্যাসোসিয়েশনের মূল লক্ষ্য হবে বিভিন্ন শিক্ষামূলক, সাংস্কৃতিক, সামাজিক ও কল্যাণমূলক কর্মসূচির মাধ্যমে দেশে-বিদেশে অবস্থানরত ইন্সটিটিউট অব পাবলিক হেলথ শিক্ষার্থীদের মধ্যে পারস্পরিক যোগাযোগ এবং সৌহার্দ্যপূর্ণ সম্পর্ক স্থাপন করা।</span>
          </li>
          <li class="flex items-start gap-2">
            <span class="font-bold text-[#800020] shrink-0">খ).</span>
            <span>ইন্সটিটিউট অব পাবলিক হেলথের পাঠ্যক্রম এবং গবেষণার মানোন্নয়নে ভূমিকা রাখা।</span>
          </li>
          <li class="flex items-start gap-2">
            <span class="font-bold text-[#800020] shrink-0">গ).</span>
            <span>বিভাগ সংশ্লিষ্ট প্রগতিশীল চিন্তা চেতনা বিকাশে যেকোনো বিষয়ের উপর গবেষণা, সম্মেলন, সেমিনার, সিম্পোজিয়াম, আলোচনা, প্রকাশনা, ইত্যাদির আয়োজন করা।</span>
          </li>
          <li class="flex items-start gap-2">
            <span class="font-bold text-[#800020] shrink-0">ঘ).</span>
            <span>ইন্সটিটিউট অব পাবলিক হেলথে শিক্ষা সহায়ক ও উন্নয়নমূলক কর্মকান্ডে সহযোগিতা প্রদান করা।</span>
          </li>
          <li class="flex items-start gap-2">
            <span class="font-bold text-[#800020] shrink-0">ঙ).</span>
            <span>বর্তমান শিক্ষার্থীদের জন্য কল্যাণমুখী কর্মসূচি গ্রহণ ও সহায়তা প্রদান করা।</span>
          </li>
          <li class="flex items-start gap-2">
            <span class="font-bold text-[#800020] shrink-0">চ).</span>
            <span>অ্যালামনাই অ্যাসোসিয়েশনের সদস্য এবং বিভাগ সংশ্লিষ্ট কোনো ব্যক্তির সংকটকালে বৃহৎ অর্থে সমাজ ও মানব কল্যাণে যে কোনো কর্মসূচি গ্রহণ করা এবং সহযোগিতা প্রদান করা।</span>
          </li>
          <li class="flex items-start gap-2">
            <span class="font-bold text-[#800020] shrink-0">ছ).</span>
            <span>বিভাগের গ্রাজুয়েটদের কর্মসংস্থান প্রসারের বিষয়ের কর্মসূচি গ্রহণ করা এবং ভূমিকা রাখা।</span>
          </li>
        </ul>
      </section>

      <!-- Section 6 -->
      <section id="dhara-6" class="scroll-mt-28">
        <h3 class="font-serif text-[21px] font-bold text-[#800020] pb-2 border-b border-rose-100 flex items-center gap-2">
          <span class="w-8 h-8 rounded-lg bg-[#800020]/10 text-[#800020] text-[14px] flex items-center justify-center font-mono font-bold">৬</span>
          কার্যাদিঃ
        </h3>
        <ul class="mt-4 space-y-3 text-[15px] text-slate-800">
          <li class="flex items-start gap-2"><span class="font-bold text-[#2F8863] shrink-0">ক).</span><span>ইন্সটিটিউট অব পাবলিক হেলথের বর্তমান এবং স্নাতকগনের জন্য বিভিন্ন কর্মসূচির ব্যবস্থা করা প্রশিক্ষণ কার্যক্রম পরিচালনা করা।</span></li>
          <li class="flex items-start gap-2"><span class="font-bold text-[#2F8863] shrink-0">খ).</span><span>প্রাক্তন সদস্যদের মধ্যে যোগাযোগের সুবিধার্থে পুর্নমিলনী এবং অন্যান্য অনুষ্ঠানের আয়োজন করা।</span></li>
          <li class="flex items-start gap-2"><span class="font-bold text-[#2F8863] shrink-0">গ).</span><span>তথ্য প্রচার ও প্রকাশের জন্য ম্যাগাজিন এবং নিউজলেটার প্রকাশ করা।</span></li>
          <li class="flex items-start gap-2"><span class="font-bold text-[#2F8863] shrink-0">ঘ).</span><span>সাহিত্য ও সাংস্কৃতিক বিকাশের জন্য কর্মসূচি পরিচালনা করা।</span></li>
          <li class="flex items-start gap-2"><span class="font-bold text-[#2F8863] shrink-0">ঙ).</span><span>দরিদ্র ও মেধাবী শিক্ষার্থীদের উচ্চ শিক্ষার সুযোগ প্রশস্ত করার জন্য উপবৃত্তি/বৃত্তি/ফেলোশিপ প্রদান।</span></li>
          <li class="flex items-start gap-2"><span class="font-bold text-[#2F8863] shrink-0">চ).</span><span>সংগঠনের যথাযথ পরিচালনার জন্য নীতি ও নির্দেশিকা প্রণয়ন।</span></li>
          <li class="flex items-start gap-2"><span class="font-bold text-[#2F8863] shrink-0">ছ).</span><span>বিভাগের গ্রাজুয়েটদের কর্মসংস্থান প্রসারের বিষয়ে কর্মসূচি গ্রহন করা এবং ভূমিকা রাখা।</span></li>
        </ul>
      </section>

      <!-- Section 7 -->
      <section id="dhara-7" class="scroll-mt-28">
        <h3 class="font-serif text-[21px] font-bold text-[#800020] pb-2 border-b border-rose-100 flex items-center gap-2">
          <span class="w-8 h-8 rounded-lg bg-[#800020]/10 text-[#800020] text-[14px] flex items-center justify-center font-mono font-bold">৭</span>
          সদস্যপদঃ
        </h3>
        <p class="mt-4 text-[15px] text-slate-800 leading-relaxed">
          ইন্সটিটিউট অব পাবলিক হেলথ থেকে স্নাতক ডিগ্রিধারী সকল ব্যক্তি নির্ধারিত আবেদনপত্র পূরণ ও ফি প্রদান করে ইন্সটিটিউট অব পাবলিক হেলথ এলামনাই এসোসিয়েনের সদস্য হবেন এবং এসোসিয়েশনের সকল অধিকার ও সুবিধা ভোগ করতে পারবেন।
        </p>
      </section>

      <!-- Section 8 -->
      <section id="dhara-8" class="scroll-mt-28 p-5 rounded-2xl bg-amber-50/70 border border-amber-200/90 space-y-2">
        <h3 class="font-serif text-[19px] font-bold text-amber-900 flex items-center gap-2">
          <span class="w-7 h-7 rounded-lg bg-amber-200/80 text-amber-950 text-[13px] flex items-center justify-center font-mono font-bold">৮</span>
          সদস্যপদ স্থগিত বা বাতিল:
        </h3>
        <p class="text-[14.5px] text-amber-900 font-semibold mb-2">নিচের যেকোনো কারণে সদস্যপদ স্থগিত বা বাতিল হতে পারে-</p>
        <ul class="space-y-1.5 text-[14.5px] text-amber-900 list-decimal list-inside pl-2">
          <li>সংবিধান ভঙ্গ বা সংগঠনের শৃঙ্খলা পরিপন্থী কার্যক্রম।</li>
          <li>সংগঠনের ভাবমূর্তি ক্ষুণ্ণ করে এমন কর্মকাণ্ড।</li>
          <li>আর্থিক অনিয়ম বা দুর্নীতি।</li>
          <li>নির্বাহী কমিটির সুপারিশে সাধারণ সভার অনুমোদন সাপেক্ষে।</li>
        </ul>
      </section>

      <!-- Section 9 -->
      <section id="dhara-9" class="scroll-mt-28">
        <h3 class="font-serif text-[21px] font-bold text-[#800020] pb-2 border-b border-rose-100 flex items-center gap-2">
          <span class="w-8 h-8 rounded-lg bg-[#800020]/10 text-[#800020] text-[14px] flex items-center justify-center font-mono font-bold">৯</span>
          সাংগঠনিক কাঠামোঃ
        </h3>
        <p class="mt-4 text-[15px] text-slate-800">
          ইন্সটিটিউট অব পাবলিক হেলথ অ্যালামনাই অ্যাসোসিয়েশনের ২ টি পরিষদ থাকবে।
        </p>
      </section>

      <!-- Section 10(a): Advisory Council -->
      <section id="dhara-10a" class="scroll-mt-28 p-5 rounded-2xl bg-slate-50 border border-slate-200 space-y-3">
        <h3 class="font-serif text-[19px] font-bold text-[#800020] flex items-center gap-2 border-b border-slate-200 pb-2">
          <span class="w-7 h-7 rounded-lg bg-[#800020]/10 text-[#800020] text-[13px] flex items-center justify-center font-mono font-bold">১০.(ক)</span>
          উপদেষ্টা পরিষদ
        </h3>
        <ol class="space-y-2 text-[14.5px] text-slate-800 list-decimal list-inside">
          <li>উপদেষ্টা পরিষদ সংগঠনের নীতিগত দিকনির্দেশনা, পরিকল্পনা ও গুরুত্বপূর্ণ সিদ্ধান্তে পরামর্শ প্রদান করবে।</li>
          <li>উপদেষ্টা পরিষদ ৩ জন সদস্যের সমন্বয়ে গঠিত হবে, যাদের মনোনীত করবে নির্বাহী কমিটি এবং অনুমোদন করবে সাধারণ সভা।</li>
          <li>উপদেষ্টা পরিষদের সদস্য হবেন ইন্সটিটিউট অব পাবলিক হেলথ-এর পরিচালক, একাডেমিং উইং এর হেড এবং শিক্ষক ও ইন্সটিটিউট অব পাবলিক হেলথ-এর বিশিষ্ট প্রাক্তন শিক্ষার্থীরা।</li>
          <li>উপদেষ্টা পরিষদ বার্ষিক বা প্রয়োজনানুসারে নির্বাহী কমিটির সাথে বৈঠক করতে পারবেন।</li>
          <li>উপদেষ্টা পরিষদের সদস্যরা কোনো নির্বাহী বা আর্থিক দায়িত্বে নিয়োজিত থাকবেন না, তবে প্রয়োজনে বিশেষ কমিটিতে পরামর্শমূলক ভূমিকা পালন করতে পারবেন।</li>
        </ol>
      </section>

      <!-- Section 10(b): Executive Committee Table -->
      <section id="dhara-10b" class="scroll-mt-28">
        <h3 class="font-serif text-[21px] font-bold text-[#800020] pb-2 border-b border-rose-100 flex items-center gap-2 mb-4">
          <span class="w-8 h-8 rounded-lg bg-[#800020]/10 text-[#800020] text-[14px] flex items-center justify-center font-mono font-bold">১০.(খ)</span>
          কার্য নির্বাহী পরিষদ (২৭ সদস্য বিশিষ্ট):
        </h3>
        <p class="text-[15px] text-slate-800 mb-4 leading-relaxed">
          ইন্সটিটিউট অব পাবলিক হেলথ অ্যালামনাই অ্যাসোসিয়েশনের সার্বিক কার্যাবলী পরিচালনার জন্য একটি নির্বাহী পরিষদ থাকবে। অ্যালামনাই অ্যাসোসিয়েশনের কার্যনির্বাহী পরিষদ প্রতিনিধি সভায় নির্বাচিত হবে এবং কমিটির মেয়াদ দুই বছর হবে তবে যৌক্তিক কারণে মেয়াদ বৃদ্ধি করা যেতে পারে। বর্তমানে অনুমোদিত কার্যনির্বাহী পরিষদ <strong>২৭ জন সদস্য</strong> সমন্বয়ে গঠিত। পদবি ভিত্তিক কাঠামোর বিবরণ নিচে দেওয়া হলো:
        </p>
        
        <div class="overflow-x-auto rounded-2xl border border-slate-200">
          <table class="w-full text-[14.5px] text-left border-collapse">
            <thead class="bg-slate-100 text-[#101820] font-bold border-b border-slate-200">
              <tr>
                <th class="py-3 px-5 border-r border-slate-200">পদবি</th>
                <th class="py-3 px-5 text-right">সংখ্যা</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-slate-200 text-slate-800">
              <tr><td class="py-2.5 px-5 font-semibold text-[#800020]">সভাপতি</td><td class="py-2.5 px-5 text-right font-mono font-bold">১ জন</td></tr>
              <tr><td class="py-2.5 px-5 font-semibold text-[#800020]">সিনিয়র সহ-সভাপতি</td><td class="py-2.5 px-5 text-right font-mono font-bold">১ জন</td></tr>
              <tr><td class="py-2.5 px-5 font-medium">সহ-সভাপতি</td><td class="py-2.5 px-5 text-right font-mono font-bold">৪ জন</td></tr>
              <tr><td class="py-2.5 px-5 font-semibold text-[#800020]">সাধারণ সম্পাদক</td><td class="py-2.5 px-5 text-right font-mono font-bold">১ জন</td></tr>
              <tr><td class="py-2.5 px-5 font-medium">যুগ্ম সাধারণ সম্পাদক</td><td class="py-2.5 px-5 text-right font-mono font-bold">২ জন</td></tr>
              <tr><td class="py-2.5 px-5 font-medium">সাংগঠনিক সম্পাদক</td><td class="py-2.5 px-5 text-right font-mono font-bold">২ জন</td></tr>
              <tr><td class="py-2.5 px-5 font-medium">কোষাধ্যক্ষ</td><td class="py-2.5 px-5 text-right font-mono font-bold">১ জন</td></tr>
              <tr><td class="py-2.5 px-5 font-medium">দপ্তর সম্পাদক</td><td class="py-2.5 px-5 text-right font-mono font-bold">১ জন</td></tr>
              <tr><td class="py-2.5 px-5 font-medium">উপ-দপ্তর সম্পাদক</td><td class="py-2.5 px-5 text-right font-mono font-bold">১ জন</td></tr>
              <tr><td class="py-2.5 px-5 font-medium">শিক্ষা ও গবেষণা বিষয়ক সম্পাদক</td><td class="py-2.5 px-5 text-right font-mono font-bold">১ জন</td></tr>
              <tr><td class="py-2.5 px-5 font-medium">সাংস্কৃতিক ও ক্রীড়া সম্পাদক</td><td class="py-2.5 px-5 text-right font-mono font-bold">১ জন</td></tr>
              <tr><td class="py-2.5 px-5 font-medium">ধর্ম বিষয়ক সম্পাদক</td><td class="py-2.5 px-5 text-right font-mono font-bold">১ জন</td></tr>
              <tr><td class="py-2.5 px-5 font-medium">প্রচার ও জনসংযোগ সম্পাদক</td><td class="py-2.5 px-5 text-right font-mono font-bold">১ জন</td></tr>
              <tr><td class="py-2.5 px-5 font-medium">নারী বিষয়ক সম্পাদক</td><td class="py-2.5 px-5 text-right font-mono font-bold">১ জন</td></tr>
              <tr><td class="py-2.5 px-5 font-medium">কার্যনির্বাহী সদস্য</td><td class="py-2.5 px-5 text-right font-mono font-bold">৮ জন</td></tr>
              <tr class="bg-rose-50/80 font-bold text-[#800020] text-[15px]">
                <td class="py-3.5 px-5 border-t border-rose-200">সর্বমোট সদস্য সংখ্যা</td>
                <td class="py-3.5 px-5 text-right font-mono border-t border-rose-200">২৭ জন</td>
              </tr>
            </tbody>
          </table>
        </div>
      </section>

      <!-- Section 11 -->
      <section id="dhara-11" class="scroll-mt-28">
        <h3 class="font-serif text-[21px] font-bold text-[#800020] pb-2 border-b border-rose-100 flex items-center gap-2">
          <span class="w-8 h-8 rounded-lg bg-[#800020]/10 text-[#800020] text-[14px] flex items-center justify-center font-mono font-bold">১১</span>
          কার্য নির্বাহী পরিষদ গঠনঃ
        </h3>
        <p class="mt-4 text-[15px] text-slate-800 leading-relaxed">
          কেবলমাত্র ইন্সটিটিউট অব পাবলিক হেলথ এলামনাই এসোসিয়েনের সদস্য বিভিন্ন পদে প্রার্থী হতে এবং ভোটাধিকার প্রয়োগ করতে পারবেন। সংগঠনের কার্য নির্বাহী সভায় নির্বাচনের মাধ্যমে এলামনাই এসোসিয়েনের সদস্যের মধ্য হতে নতুন কার্য নির্বাহী পরিষদ গঠিত হবে (অনলাইনে/অফলাইনে)। আরো উল্লেখ থাকে যে, এই নির্বাহী পরিষদের মেয়াদ হবে দুই (২) বছর। যৌক্তিক কারনে মেয়াদ বাড়তে পারে তবে বর্ধিত মেয়াদ কোন ক্রমেই ছয় (৬) মাসের বেশি হবে না।
        </p>
      </section>

      <!-- Section 12 -->
      <section id="dhara-12" class="scroll-mt-28">
        <h3 class="font-serif text-[21px] font-bold text-[#800020] pb-2 border-b border-rose-100 flex items-center gap-2 mb-4">
          <span class="w-8 h-8 rounded-lg bg-[#800020]/10 text-[#800020] text-[14px] flex items-center justify-center font-mono font-bold">১২</span>
          কার্য নির্বাহী পরিষদের কর্মকর্তাদের দায়িত্ব এবং কর্মপরিধিঃ
        </h3>
        <p class="text-[15px] text-slate-800 mb-4">
          কার্য নির্বাহী পরিষদ সংগঠনের উদ্দেশ্য ও লক্ষ্য বাস্তবায়নের জন্য দায়বদ্ধ এবং কর্মসূচি বাস্তবায়নের জন্য সিদ্ধান্ত গ্রহণ, বিধি ও পদ্ধতি জারি এবং কার্যকর করার কর্তৃত্ব/অধিকার সংরক্ষণ করবে। কার্যনির্বাহী কমিটি সংগঠনের কোন বিশেষ ক্রিয়াকলাপ বা কর্মসূচী নিয়ে কাজ করার জন্য উপ-কমিটি নিয়োগ করতে পারে। নির্বাহী পরিষদের সদস্যবৃন্দ গঠনতন্ত্র অনুযায়ী দায়িত্ব পালন করবে।
        </p>

        <div class="space-y-3.5 text-[14.5px] text-slate-800">
          <div class="p-4 rounded-2xl bg-slate-50 border border-slate-100 space-y-1">
            <h4 class="font-bold text-[#800020]">ক) সভাপতিঃ</h4>
            <p>সভাপতি হবেন সংগঠনের নিয়মতান্ত্রিক প্রধান। তিনি সাধারণ সম্পাদককে সভা আহ্বান এর জন্য পরামর্শ অথবা নির্দেশ দিতে পারবেন। ভোটে সমতা হলে তিনি কাস্টিং ভোট দিতে পারবেন।</p>
          </div>
          <div class="p-4 rounded-2xl bg-slate-50 border border-slate-100 space-y-1">
            <h4 class="font-bold text-[#800020]">খ) সিনিয়র সহ-সভাপতিঃ</h4>
            <p>সভাপতির অনুপস্থিতিতে দায়িত্ব পালন করবেন।</p>
          </div>
          <div class="p-4 rounded-2xl bg-slate-50 border border-slate-100 space-y-1">
            <h4 class="font-bold text-[#800020]">গ) সহ-সভাপতিঃ</h4>
            <p>সিনিয়র সভাপতির অনুপস্থিতিতে দায়িত্ব পালন করবেন।</p>
          </div>
          <div class="p-4 rounded-2xl bg-slate-50 border border-slate-100 space-y-1">
            <h4 class="font-bold text-[#800020]">ঘ) সাধারণ সম্পাদকঃ</h4>
            <p>সংগঠনের প্রধান নির্বাহী। সভাপতির পরামর্শক্রমে সভা আহবান, পরিচালনা এবং সিদ্ধান্ত বাস্তবায়নের পদক্ষেপ গ্রহণ করবেন। কার্যক্রম সমন্বয় ও বার্ষিক প্রতিবেদন পেশ করবেন।</p>
          </div>
          <div class="p-4 rounded-2xl bg-slate-50 border border-slate-100 space-y-1">
            <h4 class="font-bold text-[#800020]">ঙ) যুগ্ম সম্পাদকঃ</h4>
            <p>সাধারণ সম্পাদককে সহযোগিতা করবেন এবং তাঁর অনুপস্থিতিতে দায়িত্ব পালন করবেন।</p>
          </div>
          <div class="p-4 rounded-2xl bg-slate-50 border border-slate-100 space-y-1">
            <h4 class="font-bold text-[#800020]">চ) সাংগঠনিক সম্পাদকঃ</h4>
            <p>সাংগঠনিক ঐক্য ও গতিশীলতা বৃদ্ধি এবং কর্মসূচি বাস্তবায়নে সহযোগিতা করবেন।</p>
          </div>
          <div class="p-4 rounded-2xl bg-slate-50 border border-slate-100 space-y-1">
            <h4 class="font-bold text-[#800020]">ছ) কোষাধ্যক্ষঃ</h4>
            <p>যাবতীয় হিসাব সংরক্ষণ, বাজেট প্রণয়ন ও তহবিল সংগ্রহ করবেন। ব্যাংক হিসাব সভাপতি, সাধারণ সম্পাদক ও কোষাধ্যক্ষের মধ্যে যৌথভাবে পরিচালিত হবে (যে কোনো দুজনের স্বাক্ষরে টাকা তোলা যাবে)।</p>
          </div>
          <div class="p-4 rounded-2xl bg-slate-50 border border-slate-100 space-y-1">
            <h4 class="font-bold text-[#800020]">জ) দপ্তর সম্পাদকঃ</h4>
            <p>সদস্য তালিকা, সভার কার্যবিবরণী ও নথি সংরক্ষণ করবেন।</p>
          </div>
          <div class="p-4 rounded-2xl bg-slate-50 border border-slate-100 space-y-1">
            <h4 class="font-bold text-[#800020]">ঝ) সাংস্কৃতিক ও ক্রীড়া সম্পাদকঃ</h4>
            <p>সাংস্কৃতিক ও ক্রীড়া বিষয়ক কর্মসূচি প্রণয়ন ও আয়োজন করবেন।</p>
          </div>
          <div class="p-4 rounded-2xl bg-slate-50 border border-slate-100 space-y-1">
            <h4 class="font-bold text-[#800020]">ঞ) প্রচার ও জনসংযোগ সম্পাদকঃ</h4>
            <p>যোগাযোগ রক্ষা, প্রচার কার্যক্রম এবং ম্যাগাজিন/নিউজলেটার প্রকাশ করবেন।</p>
          </div>
          <div class="p-4 rounded-2xl bg-slate-50 border border-slate-100 space-y-1">
            <h4 class="font-bold text-[#800020]">চ) কার্য নির্বাহী সদস্যঃ</h4>
            <p>সভায় অংশগ্রহণ, মতামত প্রদান এবং অর্পিত দায়িত্ব পালন করবেন।</p>
          </div>
        </div>
      </section>

      <!-- Section 13 -->
      <section id="dhara-13" class="scroll-mt-28">
        <h3 class="font-serif text-[21px] font-bold text-[#800020] pb-2 border-b border-rose-100 flex items-center gap-2">
          <span class="w-8 h-8 rounded-lg bg-[#800020]/10 text-[#800020] text-[14px] flex items-center justify-center font-mono font-bold">১৩</span>
          পদত্যাগ ও শূন্য পদ:
        </h3>
        <p class="mt-4 text-[15px] text-slate-800 leading-relaxed">
          সভাপতি বা সাধারণ সম্পাদকের পদ শূন্য হলে যথাক্রমে সহ-সভাপতি বা যুগ্ম-সম্পাদক দায়িত্ব পালন করবেন। সভাপতি পদত্যাগ করতে চাইলে সিনিয়র সহ-সভাপতির কাছে এবং অন্য সদস্যরা সভাপতির কাছে পদত্যাগপত্র দেবেন।
        </p>
      </section>

      <!-- Section 14 -->
      <section id="dhara-14" class="scroll-mt-28">
        <h3 class="font-serif text-[21px] font-bold text-[#800020] pb-2 border-b border-rose-100 flex items-center gap-2">
          <span class="w-8 h-8 rounded-lg bg-[#800020]/10 text-[#800020] text-[14px] flex items-center justify-center font-mono font-bold">১৪</span>
          উপ-কমিটিঃ
        </h3>
        <p class="mt-4 text-[15px] text-slate-800 leading-relaxed">
          কার্যনির্বাহী পরিষদ বিশেষ প্রয়োজনে উপ-কমিটি গঠন করতে পারে, যা কাজ শেষে বিলুপ্ত হবে।
        </p>
      </section>

      <!-- Section 15 -->
      <section id="dhara-15" class="scroll-mt-28">
        <h3 class="font-serif text-[21px] font-bold text-[#800020] pb-2 border-b border-rose-100 flex items-center gap-2 mb-4">
          <span class="w-8 h-8 rounded-lg bg-[#800020]/10 text-[#800020] text-[14px] flex items-center justify-center font-mono font-bold">১৫</span>
          সভাঃ
        </h3>
        <div class="space-y-3 text-[15px] text-slate-800 leading-relaxed">
          <p><strong>ক) কার্যনির্বাহী পরিষদের সভাঃ</strong> বছরে অন্তত চারটি সভা হবে। সাধারণ সভার জন্য ৭-১৫ দিন এবং জরুরি সভার জন্য ৩ দিনের নোটিশ লাগবে। বিশেষ প্রয়োজনে ২ ঘণ্টার নোটিশেও সভা ডাকা যায়।</p>
          <p><strong>খ) সাধারণ সভাঃ</strong> বছরে একবার বার্ষিক সাধারণ সভা অনুষ্ঠিত হবে।</p>
        </div>
      </section>

      <!-- Section 16 -->
      <section id="dhara-16" class="scroll-mt-28">
        <h3 class="font-serif text-[21px] font-bold text-[#800020] pb-2 border-b border-rose-100 flex items-center gap-2">
          <span class="w-8 h-8 rounded-lg bg-[#800020]/10 text-[#800020] text-[14px] flex items-center justify-center font-mono font-bold">১৬</span>
          কোরাম ও সবার সিদ্ধান্ত গ্রহনঃ
        </h3>
        <p class="mt-4 text-[15px] text-slate-800 leading-relaxed">
          দুই-তৃতীয়াংশ সদস্যের উপস্থিতিতে কোরাম হবে এবং সংখ্যাগরিষ্ঠের মতে সিদ্ধান্ত হবে। বিশেষ ক্ষেত্রে লিখিত সম্মতিও গৃহীত হতে পারে।
        </p>
      </section>

      <!-- Section 17 -->
      <section id="dhara-17" class="scroll-mt-28 p-5 rounded-2xl bg-slate-50 border border-slate-100 space-y-2">
        <h3 class="font-serif text-[19px] font-bold text-slate-900 flex items-center gap-2">
          <span class="w-7 h-7 rounded-lg bg-slate-200 text-slate-900 text-[13px] flex items-center justify-center font-mono font-bold">১৭</span>
          অর্থনীতি ও তহবিল:
        </h3>
        <div class="space-y-2 text-[14.5px] text-slate-800">
          <p><strong>আয়ের উৎস:</strong> ফি, অনুদান ও কার্যক্রম থেকে আয়।</p>
          <p><strong>ব্যয়ের খাত:</strong> স্থায়ী খরচ, শিক্ষার্থীদের অনুদান, প্রশিক্ষণ ও উন্নয়নমূলক কাজ।</p>
          <p><strong>হিসাব নিরীক্ষা:</strong> বছরে দুইবার (৬ মাস অন্তর) আয়-ব্যয়ের হিসাব প্রকাশ ও নিরীক্ষা করা হবে।</p>
        </div>
      </section>

      <!-- Section 18 -->
      <section id="dhara-18" class="scroll-mt-28">
        <h3 class="font-serif text-[21px] font-bold text-[#800020] pb-2 border-b border-rose-100 flex items-center gap-2">
          <span class="w-8 h-8 rounded-lg bg-[#800020]/10 text-[#800020] text-[14px] flex items-center justify-center font-mono font-bold">১৮</span>
          গঠনতন্ত্রের ব্যাখ্যা ও পরিবর্তনঃ
        </h3>
        <p class="mt-4 text-[15px] text-slate-800 leading-relaxed">
          গঠনতন্ত্রের ব্যাখ্যায় নির্বাহী পরিষদের সিদ্ধান্তই চূড়ান্ত। বার্ষিক প্রতিনিধি সভার দুই-তৃতীয়াংশ সদস্যের সমর্থনে গঠনতন্ত্র সংশোধন করা যাবে।
        </p>
      </section>

    </div>
  </div>
</div>
