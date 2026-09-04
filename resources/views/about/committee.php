<?php
/**
 * Committee Members Public View - Official Notice Pad Format
 * Variables: $members, $byType
 */

$logoFilename = (new \App\Models\Setting())->get('site_logo', '');
$logoUrl = $logoFilename ? asset('storage/logos/' . $logoFilename) : asset('images/LOGO.png');
$siteName = (new \App\Models\Setting())->get('site_name', 'IPH Alumni Association');
$siteTagline = (new \App\Models\Setting())->get('site_tagline', 'Institute of Public Health — Alumni Network');
$siteAddress = (new \App\Models\Setting())->get('site_address', 'Mohakhali, Dhaka-1212, Bangladesh');
?>

<style>
@media print {
  @page {
    size: A4 portrait;
    margin: 8mm 8mm 8mm 8mm;
  }
  body {
    background: #fff !important;
    color: #000 !important;
    font-size: 15px !important;
    padding: 0 !important;
    margin: 0 !important;
  }
  header, footer, nav, .no-print {
    display: none !important;
  }
  .notice-outer-wrapper {
    max-width: 100% !important;
    padding: 0 !important;
    margin: 0 !important;
  }
  .notice-pad-container {
    box-shadow: none !important;
    border: none !important;
    margin: 0 !important;
    width: 100% !important;
    padding: 10px 0 !important;
    background: #fff !important;
    border-radius: 0 !important;
  }
  .top-decor-line {
    margin-left: 0 !important;
    margin-right: 0 !important;
    margin-top: 0 !important;
    margin-bottom: 15px !important;
  }
  .page-break-before {
    page-break-before: always !important;
    break-before: page !important;
  }
  .keep-together {
    page-break-inside: avoid !important;
    break-inside: avoid !important;
  }
  .print-table {
    width: 100% !important;
    max-width: 100% !important;
    table-layout: fixed !important;
    box-sizing: border-box !important;
  }
  .print-table td, .print-table th {
    padding-top: 6px !important;
    padding-bottom: 6px !important;
    padding-left: 8px !important;
    padding-right: 8px !important;
    font-size: 15px !important;
    word-break: break-word !important;
    overflow: hidden !important;
    box-sizing: border-box !important;
  }
  .print-col-sl { width: 10% !important; }
  .print-col-role { width: 30% !important; }
  .print-col-name { width: 35% !important; }
  .print-col-batch { width: 25% !important; }
}
</style>

<div class="max-w-4xl mx-auto px-4 py-10 font-['Kalpurush'] notice-outer-wrapper">
  
  <!-- Action Bar / Print Button -->
  <div class="mb-6 flex items-center justify-between no-print">
    <a href="<?= url('/') ?>" class="text-[13.5px] text-[#6B7178] hover:text-[#101820] inline-flex items-center gap-1.5 font-medium transition-colors">
      ← <?= __('হোমপেজে ফিরে যান', 'Back to Home') ?>
    </a>

    <button onclick="window.print()" class="px-5 py-2.5 rounded-xl text-[13.5px] font-bold text-white bg-[#800020] hover:bg-[#66001a] shadow-md transition-all inline-flex items-center gap-2">
      <i class="fa-solid fa-print"></i> <?= __('নোটিশ প্রিন্ট / PDF ডাউনলোড', 'Print / Download PDF') ?>
    </button>
  </div>

  <!-- Official Notice Letterhead Paper Container -->
  <div class="notice-pad-container bg-white rounded-3xl border border-slate-200/90 shadow-2xl overflow-hidden p-8 sm:p-14 space-y-8 relative">
    
    <!-- Top Decorative Line -->
    <div class="top-decor-line h-2.5 bg-gradient-to-r from-[#800020] via-[#D4A54A] to-[#800020] -mx-8 sm:-mx-14 -mt-8 sm:-mt-14 mb-8"></div>

    <!-- Official Header (Logo & Organization Details) -->
    <div class="border-b-2 border-[#800020] pb-6 flex flex-col sm:flex-row items-center justify-between gap-6 text-center sm:text-left">
      <div class="flex items-center gap-4">
        <img src="<?= $logoUrl ?>" alt="Logo" class="h-20 w-auto object-contain">
        <div>
          <h1 class="font-serif text-[26px] font-bold text-[#800020] leading-tight tracking-wide"><?= e($siteName) ?></h1>
          <p class="text-[13px] text-gray-600 font-medium"><?= e($siteTagline) ?></p>
          <p class="text-[11.5px] text-gray-400 font-mono mt-0.5"><?= e($siteAddress) ?></p>
        </div>
      </div>
      
      <div class="sm:text-right border-t sm:border-t-0 sm:border-l border-gray-200 pt-3 sm:pt-0 sm:pl-6 text-[12px] font-mono text-gray-500 space-y-1">
        <div><strong>স্মারক নং:</strong> IPH/ALUMNI/NOTICE/2025/01</div>
        <div><strong>তারিখ:</strong> 19 November, 2025</div>
        <div class="inline-block px-2.5 py-0.5 rounded bg-rose-50 text-[#800020] font-bold border border-rose-200 mt-1">অফিসিয়াল গ্যাজেট নোটিশ</div>
      </div>
    </div>

    <!-- Notice Subject Line -->
    <div class="text-center bg-slate-50 p-4 rounded-2xl border border-slate-200/80 my-4">
      <span class="text-[12px] font-mono font-bold uppercase text-[#800020] tracking-widest block mb-1">OFFICIAL ANNOUNCEMENT // অফিসিয়াল বিজ্ঞপ্তি</span>
      <h2 class="font-serif text-[22px] font-bold text-gray-900">
        <?= __('ইনস্টিটিউট অব পাবলিক হেলথ (IPH) অ্যালামনাই অ্যাসোসিয়েশন কার্যনির্বাহী ও পরিচালনা কমিটি (২০২৫-২০২৭)', 'Executive & Advisory Committee Panel (2025-2027)') ?>
      </h2>
    </div>

    <!-- Notice Body Intro -->
    <div class="text-[14.5px] text-gray-800 leading-relaxed space-y-4 text-justify">
      <p>
        ইনস্টিটিউট অব পাবলিক হেলথ আমাদের দেশের জনস্বাস্থ্য খাতে গুরুত্বপূর্ণ ভূমিকা পালনকারী একটি ঐতিহ্যবাহী প্রতিষ্ঠান। এই প্রতিষ্ঠানের প্রাক্তন শিক্ষার্থী হিসেবে আমরা শুধু জ্ঞান ও দক্ষতার বাহক নই— আমরা জনস্বাস্থ্য উন্নয়নের এক দায়িত্বশীল অংশীদার। সেই লক্ষ্যকে আরও সুসংহত করতে আমরা আজ ১৯/১১/২০২৫ ইং তারিখে এলামনাই এসোসিয়েশন কমিটি গঠন করার উদ্দেশ্যে জুম মিটিং এর মাধ্যমে সবাই একত্রিত হয়েছি।
      </p>
      <p>
        কমিটি গঠনের এই মুহূর্তে আমাদের প্রত্যাশা—এলামনাই এসোসিয়েশন হবে একটি সক্রিয়, স্বচ্ছ ও গতিশীল প্ল্যাটফর্ম, যেখানে প্রাক্তন শিক্ষার্থীরা অভিজ্ঞতা বিনিময়, পেশাগত উন্নয়ন, গবেষণা, এবং জনস্বাস্থ্যের সার্বিক উন্নয়নে একসাথে কাজ করবে। প্রতিষ্ঠান ও বর্তমান শিক্ষার্থীদের কল্যাণে আমাদের যৌথ উদ্যোগ আরও শক্তিশালী হবে—এই বিশ্বাস আমরা ধারণ করি।
      </p>
      <p>
        যারা নতুন কমিটির দায়িত্ব গ্রহণ করবেন, তারা নিষ্ঠা, সততা, নেতৃত্বগুণ এবং সহযোগিতার মনোভাব নিয়ে এসোসিয়েশনের কর্মকাণ্ডকে এগিয়ে নেবেন—এটাই আমাদের প্রত্যাশা। আমরা আশা করি, সকলের মতামত, পরামর্শ ও অংশগ্রহণের মাধ্যমে একটি কার্যকর ও সমন্বিত কমিটি গঠিত হবে।
      </p>
      <p>
        আসুন, আমরা ঐক্য, পেশাদারিত্ব ও জনস্বাস্থ্য উন্নয়নের অঙ্গীকার নিয়ে ইনস্টিটিউট অব পাবলিক হেলথ এলামনাই এসোসিয়েশনকে আরও শক্তিশালী এবং প্রাণবন্ত করে তুলতে হাতে হাত মেলাই।
      </p>
      <p>
        ইনস্টিটিউট অব পাবলিক হেলথ এলামনাই এসোসিয়েশন কমিটি গঠনের লক্ষ্যে আজকের এই জুম মিটিং এ উপস্থিত প্রাক্তন শিক্ষার্থীদের মতামতের ভিত্তিতে নিম্ন উল্লেখিত ৩ (তিন) সদস্য বিশিষ্ট উপদেষ্টা পরিষদ ও ২৭ (সাতাশ) সদস্য বিশিষ্ট ইনস্টিটিউট অব পাবলিক হেলথ এলামনাই এসোসিয়েশন কমিটি গঠন করা হয়।
      </p>
    </div>

    <!-- Advisory Panel Section -->
    <div class="border border-amber-200 rounded-2xl overflow-hidden bg-amber-50/50 shadow-sm p-5 space-y-3">
      <h3 class="font-serif font-bold text-[17px] text-[#800020] border-b border-amber-200 pb-2 flex items-center gap-2">
        <span>🏛️ ৩ (তিন) সদস্য বিশিষ্ট উপদেষ্টা পরিষদ (Advisory Council)</span>
      </h3>
      <ul class="space-y-2 text-[14px] text-gray-800">
        <li class="flex items-start gap-2">
          <span class="font-bold text-[#800020] shrink-0">প্রধান উপদেষ্টা :</span>
          <span>পরিচালক, ইনস্টিটিউট অব পাবলিক হেলথ, মহাখালী, ঢাকা (পদাধিকার বলে)।</span>
        </li>
        <li class="flex items-start gap-2">
          <span class="font-bold text-[#800020] shrink-0">উপদেষ্টা :</span>
          <span>হেড, একাডেমি উইং, ইনস্টিটিউট অব পাবলিক হেলথ, মহাখালী, ঢাকা (পদাধিকার বলে)।</span>
        </li>
        <li class="flex items-start gap-2">
          <span class="font-bold text-[#800020] shrink-0">উপদেষ্টা :</span>
          <span>শিক্ষক, একাডেমি উইং, ইনস্টিটিউট অব পাবলিক হেলথ, মহাখালী, ঢাকা (এলামনাই এসোসিয়েশন কমিটি কর্তৃক মনোনীত)।</span>
        </li>
      </ul>
    </div>

    <p class="text-[14.5px] text-gray-800 font-semibold leading-relaxed">
      সংগঠনের সার্বিক কার্যক্রম পরিচালনা, ভবিষ্যৎ কর্মপরিকল্পনা প্রণয়ন এবং অ্যালামনাই নেটওয়ার্ক সুসংগঠিত করার লক্ষ্যে নিম্নলিখিত সদস্যগণের সমন্বয়ে অনুমোদনকৃত কমিটি গঠন করা হলো:
    </p>

    <!-- Committee Members Official Notice List -->
    <div class="space-y-8 pt-2">
      
      <!-- Executive Organogram / Structure List -->
      <div class="border border-slate-200 rounded-2xl overflow-hidden shadow-sm">
        <div class="bg-[#800020] text-white px-5 py-3 font-serif font-bold text-[16px] flex items-center justify-between">
          <span>📋 কার্যনির্বাহী ও বিভাগীয় কমিটি প্যানেল (Executive Panel)</span>
          <span class="font-mono text-[11px] font-normal opacity-80">Ref: Sec-18 Committee Act</span>
        </div>

        <table class="w-full text-[13.5px] text-left border-collapse print-table">
          <thead>
            <tr class="bg-slate-100 text-gray-700 font-bold border-b border-slate-200 font-mono text-[11.5px] uppercase">
              <th class="p-2.5 border-r border-slate-200 text-center w-[10%] print-col-sl">ক্রমিক</th>
              <th class="p-2.5 pl-3 border-r border-slate-200 w-[30%] print-col-role">পদবি</th>
              <th class="p-2.5 pl-3 border-r border-slate-200 w-[35%] print-col-name">সদস্যের নাম</th>
              <th class="p-2.5 px-3 w-[25%] print-col-batch">ব্যাচ নম্বর</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-slate-200 text-gray-800">
            <?php
            $noticeList = [
              ['role' => 'সভাপতি', 'name' => 'মো. জহুরুল ইসলাম', 'batch' => 'L-1'],
              ['role' => 'সিনিয়র সহ-সভাপতি', 'name' => 'মোহাম্মদ রেজাউল করিম', 'batch' => 'L-1'],
              ['role' => 'সহ-সভাপতি', 'name' => 'আখতারুজ্জামান তালুকদার সবুজ', 'batch' => 'L-1'],
              ['role' => 'সহ-সভাপতি', 'name' => 'ওয়াসিম খান', 'batch' => 'L-1'],
              ['role' => 'সহ-সভাপতি', 'name' => 'রাশেদুর রহমান', 'batch' => 'L-1'],
              ['role' => 'সহ-সভাপতি', 'name' => 'মজিবুর রহমান উজ্জল রহমান', 'batch' => 'F-1'],
              ['role' => 'সাধারণ সম্পাদক', 'name' => 'রাজিবুল হাসান রাজা', 'batch' => 'L-2'],
              ['role' => 'যুগ্ম সাধারণ সম্পাদক', 'name' => 'তুষার সিংহ', 'batch' => 'L-2'],
              ['role' => 'যুগ্ম সাধারণ সম্পাদক', 'name' => 'আব্দুল্লা খান', 'batch' => 'F-1'],
              ['role' => 'সাংগঠনিক সম্পাদক', 'name' => 'মো হাসানুজ্জামান', 'batch' => 'L-3'],
              ['role' => 'সাংগঠনিক সম্পাদক', 'name' => 'মেহেদী হাসান রাব্বি', 'batch' => 'L-3'],
              ['role' => 'কোষাধ্যক্ষ', 'name' => 'মোহাম্মদ শফিউল আলম ফিরোজ', 'batch' => 'L-4'],
              ['role' => 'দপ্তর সম্পাদক', 'name' => 'আব্দুল্লাহ আল তাহসিন', 'batch' => 'L-3'],
              ['role' => 'উপ-দপ্তর সম্পাদক', 'name' => 'মাহমুদুর রহমান ইমন', 'batch' => 'L-4'],
              ['role' => 'শিক্ষা ও গবেষণা সম্পাদক', 'name' => 'মো শাহীন আলম', 'batch' => 'L-2'],
              ['role' => 'সংস্কৃতি ও ক্রীড়া সম্পাদক', 'name' => 'সৈকত সরকার', 'batch' => 'L-3'],
              ['role' => 'ধর্ম বিষয়ক সম্পাদক', 'name' => 'আনোয়ার হোসেন', 'batch' => 'L-3'],
              ['role' => 'প্রচার ও জনসংযোগ সম্পাদক', 'name' => 'যুবায়ের হাসান সরকার', 'batch' => 'L-3'],
              ['role' => 'নারী বিষয়ক সম্পাদক', 'name' => 'ফেরদৌসি আক্তার', 'batch' => 'L-3'],
              ['role' => 'কার্যনির্বাহী সদস্য', 'name' => 'শিলা আক্তার', 'batch' => 'L-2'],
              ['role' => 'কার্যনির্বাহী সদস্য', 'name' => 'কেয়া পাপিয়া', 'batch' => 'L-2'],
              ['role' => 'কার্যনির্বাহী সদস্য', 'name' => 'তানিয়া আফরোজ', 'batch' => 'L-3'],
              ['role' => 'কার্যনির্বাহী সদস্য', 'name' => 'পৃথু চাকমা', 'batch' => 'L-3'],
              ['role' => 'কার্যনির্বাহী সদস্য', 'name' => 'আব্দুর রহমান দিপলু', 'batch' => 'L-4'],
              ['role' => 'কার্যনির্বাহী সদস্য', 'name' => 'জুনায়েদ আহমেদ', 'batch' => 'L-4'],
              ['role' => 'কার্যনির্বাহী সদস্য', 'name' => 'সালমুন সাজ্জাদ', 'batch' => 'F-1'],
              ['role' => 'কার্যনির্বাহী সদস্য', 'name' => 'কণা আক্তার', 'batch' => 'F-1'],
            ];

            // Overwrite with DB committee if available
            if (!empty($members)) {
              $noticeList = [];
              foreach ($members as $m) {
                $noticeList[] = [
                  'role' => $m['designation'] ?? 'Member',
                  'name' => $m['name'],
                  'batch' => !empty($m['batch_year']) ? $m['batch_year'] : 'IPH Alumni'
                ];
              }
            }

            foreach ($noticeList as $idx => $item):
              $isHead = str_contains($item['role'], 'President') || (str_contains($item['role'], 'General Secretary') && !str_contains($item['role'], 'Joint'));
            ?>
            <tr class="<?= $isHead ? 'bg-amber-50/70 font-bold' : ($idx % 2 === 0 ? 'bg-white' : 'bg-slate-50/50') ?>">
              <td class="p-2 border-r border-slate-200 text-center font-mono font-bold text-gray-500 text-[12.5px]"><?= $idx + 1 ?></td>
              <td class="p-2 pl-3 border-r border-slate-200 text-[#800020] font-semibold"><?= e($item['role']) ?></td>
              <td class="p-2 pl-3 border-r border-slate-200 text-gray-900 font-medium"><?= e($item['name']) ?></td>
              <td class="p-2 px-3 font-mono font-bold text-[13px] text-gray-700"><?= e($item['batch']) ?></td>
            </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      <!-- Committee Closing Declaration -->
      <div class="p-4 bg-slate-50 border border-slate-200 rounded-2xl text-[14.5px] text-gray-800 leading-relaxed space-y-2 mt-6">
        <p>উক্ত কমিটি আগামী ২ (দুই) বছরের জন্য অনুমোদন করা হলো।</p>
        <p>উক্ত কমিটি আগামী ৭ (সাত) কর্মদিবসের মধ্যে গঠনতন্ত্র প্রণয়ন করবে এবং সকল কার্যক্রম ও কমিটির মেয়াদ শেষে গঠনতন্ত্র অনুযায়ী নতুন কমিটির কাছে দায়িত্ব ও সকল দাপ্তরিক, আর্থিক ও অন্যান্য নথি - তথ্য হস্তান্তর করবে।</p>
      </div>

    </div>

    <!-- Official Signatures & Seal Section -->
    <div class="keep-together pt-8 border-t border-gray-200 space-y-6">
      <div class="grid grid-cols-2 gap-8 items-end text-center">
        <div class="flex flex-col items-center">
          <img src="<?= asset('images/GS-sign.jpg') ?>" alt="General Secretary Signature" class="h-16 w-auto object-contain mb-1 mix-blend-multiply">
          <div class="border-t border-gray-400 w-[200px] pt-1.5 font-bold text-gray-900 text-[14px]">রাজিবুল হাসান রাজা</div>
          <div class="text-[12px] text-gray-600 font-medium">সাধারণ সম্পাদক</div>
          <div class="text-[11px] text-gray-400 font-mono">IPH Alumni Association</div>
        </div>

        <div class="flex flex-col items-center">
          <img src="<?= asset('images/president-sign.jpg') ?>" alt="President Signature" class="h-16 w-auto object-contain mb-1 mix-blend-multiply">
          <div class="border-t border-gray-400 w-[200px] pt-1.5 font-bold text-gray-900 text-[14px]">মো. জহুরুল ইসলাম</div>
          <div class="text-[12px] text-[#800020] font-bold">সভাপতি</div>
          <div class="text-[11px] text-gray-400 font-mono">IPH Alumni Association</div>
        </div>
      </div>

      <!-- Footer Note -->
      <div class="border-t border-gray-100 pt-3 flex items-center justify-between text-[11px] font-mono text-gray-400">
        <div>IPH Alumni Association — Official Notice Registry</div>
        <div>Page 2 of 2</div>
      </div>
    </div>

  </div>
</div>
