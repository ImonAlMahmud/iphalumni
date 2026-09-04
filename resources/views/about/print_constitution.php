<!DOCTYPE html>
<html lang="bn">
<head>
<meta charset="UTF-8">
<title>IPHA Official Constitution Document PDF</title>
<link href="https://fonts.maateen.me/kalpurush/font.css" rel="stylesheet">
<style>
  @page {
    size: A4 portrait;
    margin: 12mm 15mm 15mm 15mm;
  }
  body {
    font-family: 'Kalpurush', sans-serif;
    color: #111111;
    font-size: 15px;
    line-height: 1.6;
    background: #ffffff;
    margin: 0;
    padding: 0;
  }
  
  .letterhead {
    display: flex;
    align-items: center;
    justify-content: space-between;
    border-bottom: 2.5px solid #800020;
    padding-bottom: 8px;
    margin-bottom: 15px;
  }
  .header-left {
    display: flex;
    align-items: center;
    gap: 12px;
  }
  .header-left img {
    width: 55px;
    height: 55px;
    object-fit: contain;
  }
  .header-titles h1 {
    font-size: 22px;
    color: #800020;
    margin: 0;
    font-weight: bold;
  }
  .header-titles h2 {
    font-size: 14.5px;
    color: #101820;
    margin: 2px 0 0 0;
  }
  .header-titles p {
    font-size: 12.5px;
    color: #666;
    margin: 1px 0 0 0;
  }
  
  .doc-title {
    text-align: center;
    font-size: 20px;
    font-weight: bold;
    color: #800020;
    margin-bottom: 18px;
    text-decoration: underline;
    text-underline-offset: 4px;
  }

  .dhara-box {
    margin-bottom: 15px;
  }
  .dhara-title {
    font-size: 16px;
    font-weight: bold;
    color: #800020;
    border-bottom: 1px solid #ddd;
    padding-bottom: 3px;
    margin-bottom: 6px;
  }
  
  table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 6px;
    font-size: 14.5px;
  }
  th, td {
    border: 1px solid #dddddd;
    padding: 7px 12px;
    text-align: left;
  }
  th {
    background: #f8f8f8;
    color: #800020;
    font-weight: bold;
  }

  .cover-page {
    height: 90vh;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    text-align: center;
    page-break-after: always;
    break-after: page;
    box-sizing: border-box;
    padding: 40px 20px;
    border: 2px solid rgba(128, 0, 32, 0.2);
    border-radius: 20px;
    margin-bottom: 30px;
    position: relative;
    background: linear-gradient(180deg, rgba(128, 0, 32, 0.03) 0%, rgba(255, 255, 255, 1) 100%);
  }
  .cover-logo {
    width: 170px;
    height: 170px;
    object-fit: contain;
    margin-bottom: 30px;
    filter: drop-shadow(0 10px 15px rgba(128, 0, 32, 0.15));
  }
  .cover-org-bn {
    font-size: 28px;
    font-weight: bold;
    color: #800020;
    margin-bottom: 6px;
    letter-spacing: 0.5px;
  }
  .cover-org-en {
    font-size: 17px;
    font-weight: 600;
    color: #2b303a;
    letter-spacing: 1px;
    margin-bottom: 35px;
    text-transform: uppercase;
  }
  .cover-divider {
    width: 140px;
    height: 4px;
    background: linear-gradient(90deg, #800020, #D4A54A, #800020);
    border-radius: 2px;
    margin: 0 auto 35px auto;
  }
  .cover-title-bn {
    font-size: 32px;
    font-weight: bold;
    color: #101820;
    margin-bottom: 8px;
  }
  .cover-title-en {
    font-size: 19px;
    font-weight: bold;
    color: #800020;
    letter-spacing: 2px;
    text-transform: uppercase;
    margin-bottom: 45px;
  }
  .cover-footer {
    margin-top: auto;
    font-size: 13.5px;
    color: #555555;
    line-height: 1.6;
    border-top: 1px solid rgba(128, 0, 32, 0.15);
    padding-top: 15px;
    width: 80%;
  }

  @media print {
    .no-print { display: none !important; }
    html, body {
      height: auto !important;
      overflow: visible !important;
    }
    .cover-page {
      height: 96vh !important;
      border: 3px double #800020 !important;
      margin: 0 !important;
    }
  }
</style>
</head>
<body>

<div class="no-print" style="background:#101820;color:#fff;padding:12px;text-align:center;position:sticky;top:0;z-index:9999;">
  <button onclick="window.print()" style="background:#800020;color:#fff;border:none;padding:8px 20px;border-radius:8px;font-size:14px;cursor:pointer;font-family:'Kalpurush',sans-serif;font-weight:bold;">
    🖨️ Download / Print Constitution PDF
  </button>
  <button onclick="window.close()" style="background:#444;color:#fff;border:none;padding:8px 16px;border-radius:8px;font-size:14px;cursor:pointer;margin-left:10px;font-family:'Kalpurush',sans-serif;">
    বন্ধ করুন (Close)
  </button>
</div>

<!-- COVER PAGE SECTION -->
<div class="cover-page">
  <img src="<?= asset('images/LOGO.png') ?>" alt="IPHA Logo" class="cover-logo">
  
  <div class="cover-org-bn">ইন্সটিটিউট অব পাবলিক হেলথ এলামনাই অ্যাসোসিয়েশন</div>
  <div class="cover-org-en">Institute of Public Health Alumni Association (IPHA)</div>
  
  <div class="cover-divider"></div>
  
  <div class="cover-title-bn">গঠনতন্ত্র</div>
  <div class="cover-title-en">CONSTITUTION</div>
  
  <div class="cover-footer">
    📍 ইন্সটিটিউট অব পাবলিক হেলথ, মহাখালী, ঢাকা-১২১২, বাংলাদেশ।<br>
    <span style="font-size:12px; color:#777;">অফিশিয়াল অনুমোদিত প্রকাশনা // Official Approved Publication</span>
  </div>
</div>

<div style="padding: 10px 15px; page-break-before: always;">
  <div class="letterhead">
    <div class="header-left">
      <img src="<?= asset('images/LOGO.png') ?>" alt="Logo">
      <div class="header-titles">
        <h1>ইন্সটিটিউট অব পাবলিক হেলথ এলামনাই অ্যাসোসিয়েশন</h1>
        <h2>INSTITUTE OF PUBLIC HEALTH ALUMNI ASSOCIATION (IPHA)</h2>
        <p>Institute of Public Health, Mohakhali, Dhaka-1212, Bangladesh</p>
      </div>
    </div>
  </div>

  <div class="doc-title">সংগঠনের গঠনতন্ত্র (Official Constitution)</div>

  <!-- 1 -->
  <div class="dhara-box">
    <div class="dhara-title">১. সংগঠনের নামঃ</div>
    <div><strong>বাংলা নাম:</strong> এই সংগঠনের নাম হবে <strong>"ইন্সটিটিউট অব পাবলিক হেলথ এলামনাই অ্যাসোসিয়েশন"</strong></div>
    <div><strong>ইংরেজী নাম:</strong> <strong>“Institute of Public Health Alumni Association (IPHA)”</strong></div>
  </div>

  <!-- 2 -->
  <div class="dhara-box">
    <div class="dhara-title">২. অবস্থানঃ</div>
    <div>এই সংগঠনের সদর দপ্তর এর অবস্থান হবে ইন্সটিটিউট অব পাবলিক হেলথ, মহাখালী, ঢাকা-১২১২।</div>
  </div>

  <!-- 3 -->
  <div class="dhara-box">
    <div class="dhara-title">৩. মনোগ্রামঃ</div>
    <div>এই সংগঠনের একটি মনোগ্রাম থাকবে।</div>
  </div>

  <!-- 4 -->
  <div class="dhara-box">
    <div class="dhara-title">৪. প্রকৃতি ও বৈশিষ্ট্যঃ</div>
    <div>এই সংগঠন একটি অসাম্প্রদায়িক, অরাজনৈতিক এবং অলাভজনক, ক্যাম্পাসভিত্তিক সামাজিক সেবা সংস্থা।</div>
  </div>

  <!-- 5 -->
  <div class="dhara-box">
    <div class="dhara-title">৫. উদ্দেশ্য ও লক্ষ্যঃ</div>
    <div><strong>ক).</strong> এই অ্যালামনাই অ্যাসোসিয়েশনের মূল লক্ষ্য হবে বিভিন্ন শিক্ষামূলক, সাংস্কৃতিক, সামাজিক ও কল্যাণমূলক কর্মসূচির মাধ্যমে দেশে-বিদেশে অবস্থানরত ইন্সটিটিউট অব পাবলিক হেলথ শিক্ষার্থীদের মধ্যে পারস্পরিক যোগাযোগ এবং সৌহার্দ্যপূর্ণ সম্পর্ক স্থাপন করা।</div>
    <div><strong>খ).</strong> ইন্সটিটিউট অব পাবলিক হেলথের পাঠ্যক্রম এবং গবেষণার মানোন্নয়নে ভূমিকা রাখা।</div>
    <div><strong>গ).</strong> বিভাগ সংশ্লিষ্ট প্রগতিশীল চিন্তা চেতনা বিকাশে যেকোনো বিষয়ের উপর গবেষণা, সম্মেলন, সেমিনার, সিম্পোজিয়াম, আলোচনা, প্রকাশনা, ইত্যাদির আয়োজন করা।</div>
    <div><strong>ঘ).</strong> ইন্সটিটিউট অব পাবলিক হেলথে শিক্ষা সহায়ক ও উন্নয়নমূলক কর্মকান্ডে সহযোগিতা প্রদান করা।</div>
    <div><strong>ঙ).</strong> বর্তমান শিক্ষার্থীদের জন্য কল্যাণমুখী কর্মসূচি গ্রহণ ও সহায়তা প্রদান করা।</div>
    <div><strong>চ).</strong> অ্যালামনাই অ্যাসোসিয়েশনের সদস্য এবং বিভাগ সংশ্লিষ্ট কোনো ব্যক্তির সংকটকালে বৃহৎ অর্থে সমাজ ও মানব কল্যাণে যে কোনো কর্মসূচি গ্রহণ করা এবং সহযোগিতা প্রদান করা।</div>
    <div><strong>ছ).</strong> বিভাগের গ্রাজুয়েটদের কর্মসংস্থান প্রসারের বিষয়ের কর্মসূচি গ্রহণ করা এবং ভূমিকা রাখা।</div>
  </div>

  <!-- 6 -->
  <div class="dhara-box">
    <div class="dhara-title">৬. কার্যাদিঃ</div>
    <div><strong>ক).</strong> ইন্সটিটিউট অব পাবলিক হেলথের বর্তমান এবং স্নাতকগনের জন্য বিভিন্ন কর্মসূচির ব্যবস্থা করা প্রশিক্ষণ কার্যক্রম পরিচালনা করা।</div>
    <div><strong>খ).</strong> প্রাক্তন সদস্যদের মধ্যে যোগাযোগের সুবিধার্থে পুর্নমিলনী এবং অন্যান্য অনুষ্ঠানের আয়োজন করা।</div>
    <div><strong>গ).</strong> তথ্য প্রচার ও প্রকাশের জন্য ম্যাগাজিন এবং নিউজলেটার প্রকাশ করা।</div>
    <div><strong>ঘ).</strong> সাহিত্য ও সাংস্কৃতিক বিকাশের জন্য কর্মসূচি পরিচালনা করা।</div>
    <div><strong>ঙ).</strong> দরিদ্র ও মেধাবী শিক্ষার্থীদের উচ্চ শিক্ষার সুযোগ প্রশস্ত করার জন্য উপবৃত্তি/বৃত্তি/ফেলোশিপ প্রদান।</div>
    <div><strong>চ).</strong> সংগঠনের যথাযথ পরিচালনার জন্য নীতি ও নির্দেশিকা প্রণয়ন।</div>
    <div><strong>ছ).</strong> বিভাগের গ্রাজুয়েটদের কর্মসংস্থান প্রসারের বিষয়ে কর্মসূচি গ্রহন করা এবং ভূমিকা রাখা।</div>
  </div>

  <!-- 7 -->
  <div class="dhara-box">
    <div class="dhara-title">৭. সদস্যপদঃ</div>
    <div>ইন্সটিটিউট অব পাবলিক হেলথ থেকে স্নাতক ডিগ্রিধারী সকল ব্যক্তি নির্ধারিত আবেদনপত্র পূরণ ও ফি প্রদান করে ইন্সটিটিউট অব পাবলিক হেলথ এলামনাই এসোসিয়েনের সদস্য হবেন এবং এসোসিয়েশনের সকল অধিকার ও সুবিধা ভোগ করতে পারবেন।</div>
  </div>

  <!-- 8 -->
  <div class="dhara-box">
    <div class="dhara-title">৮. সদস্যপদ স্থগিত বা বাতিল:</div>
    <div>নিচের যেকোনো কারণে সদস্যপদ স্থগিত বা বাতিল হতে পারে-</div>
    <div style="padding-left:15px; margin-top:4px;">
      <div>১. সংবিধান ভঙ্গ বা সংগঠনের শৃঙ্খলা পরিপন্থী কার্যক্রম।</div>
      <div>২. সংগঠনের ভাবমূর্তি ক্ষুণ্ণ করে এমন কর্মকাণ্ড।</div>
      <div>৩. আর্থিক অনিয়ম বা দুর্নীতি।</div>
      <div>৪. নির্বাহী কমিটির সুপারিশে সাধারণ সভার অনুমোদন সাপেক্ষে।</div>
    </div>
  </div>

  <!-- 9 -->
  <div class="dhara-box">
    <div class="dhara-title">৯. সাংগঠনিক কাঠামোঃ</div>
    <div>ইন্সটিটিউট অব পাবলিক হেলথ অ্যালামনাই অ্যাসোসিয়েশনের ২ টি পরিষদ থাকবে।</div>
  </div>

  <!-- 10a -->
  <div class="dhara-box">
    <div class="dhara-title">১০.(ক) উপদেষ্টা পরিষদ</div>
    <div style="padding-left:15px;">
      <div>১. উপদেষ্টা পরিষদ সংগঠনের নীতিগত দিকনির্দেশনা, পরিকল্পনা ও গুরুত্বপূর্ণ সিদ্ধান্তে পরামর্শ প্রদান করবে।</div>
      <div>২. উপদেষ্টা পরিষদ ৩ জন সদস্যের সমন্বয়ে গঠিত হবে, যাদের মনোনীত করবে নির্বাহী কমিটি এবং অনুমোদন করবে সাধারণ সভা।</div>
      <div>৩. উপদেষ্টা পরিষদের সদস্য হবেন ইন্সটিটিউট অব PUBLIC HEALTH-এর পরিচালক, একাডেমিং উইং এর হেড এবং শিক্ষক ও ইন্সটিটিউট অব পাবলিক হেলথ-এর বিশিষ্ট প্রাক্তন শিক্ষার্থীরা।</div>
      <div>৪. উপদেষ্টা পরিষদ বার্ষিক বা প্রয়োজনানুসারে নির্বাহী কমিটির সাথে বৈঠক করতে পারবেন।</div>
      <div>৫. উপদেষ্টা পরিষদের সদস্যরা কোনো নির্বাহী বা আর্থিক দায়িত্বে নিয়োজিত থাকবেন না, তবে প্রয়োজনে বিশেষ কমিটিতে পরামর্শমূলক ভূমিকা পালন করতে পারবেন।</div>
    </div>
  </div>

  <!-- 10b -->
  <div class="dhara-box">
    <div class="dhara-title">১০. (খ) কার্য নির্বাহী পরিষদ (২৭ সদস্য বিশিষ্ট):</div>
    <div>ইন্সটিটিউট অব পাবলিক হেলথ অ্যালামনাই অ্যাসোসিয়েশনের সার্বিক কার্যাবলী পরিচালনার জন্য একটি নির্বাহী পরিষদ থাকবে। অ্যালামনাই অ্যাসোসিয়েশনের কার্যনির্বাহী পরিষদ প্রতিনিধি সভায় নির্বাচিত হবে এবং কমিটির মেয়াদ দুই বছর হবে তবে যৌক্তিক কারণে মেয়াদ বৃদ্ধি করা যেতে পারে। বর্তমানে অনুমোদিত কার্যনির্বাহী পরিষদ <strong>২৭ জন সদস্য</strong> সমন্বয়ে গঠিত। পদবি ভিত্তিক কাঠামোর বিবরণ নিচে দেওয়া হলো:</div>
    <table>
      <thead>
        <tr><th>পদবি</th><th style="text-align:right;">সংখ্যা</th></tr>
      </thead>
      <tbody>
        <tr><td>সভাপতি</td><td style="text-align:right;">১ জন</td></tr>
        <tr><td>সিনিয়র সহ-সভাপতি</td><td style="text-align:right;">১ জন</td></tr>
        <tr><td>সহ-সভাপতি</td><td style="text-align:right;">৪ জন</td></tr>
        <tr><td>সাধারণ সম্পাদক</td><td style="text-align:right;">১ জন</td></tr>
        <tr><td>যুগ্ম সাধারণ সম্পাদক</td><td style="text-align:right;">২ জন</td></tr>
        <tr><td>সাংগঠনিক সম্পাদক</td><td style="text-align:right;">২ জন</td></tr>
        <tr><td>কোষাধ্যক্ষ</td><td style="text-align:right;">১ জন</td></tr>
        <tr><td>দপ্তর সম্পাদক</td><td style="text-align:right;">১ জন</td></tr>
        <tr><td>উপ-দপ্তর সম্পাদক</td><td style="text-align:right;">১ জন</td></tr>
        <tr><td>শিক্ষা ও গবেষণা বিষয়ক সম্পাদক</td><td style="text-align:right;">১ জন</td></tr>
        <tr><td>সাংস্কৃতিক ও ক্রীড়া সম্পাদক</td><td style="text-align:right;">১ জন</td></tr>
        <tr><td>ধর্ম বিষয়ক সম্পাদক</td><td style="text-align:right;">১ জন</td></tr>
        <tr><td>প্রচার ও জনসংযোগ সম্পাদক</td><td style="text-align:right;">১ জন</td></tr>
        <tr><td>নারী বিষয়ক সম্পাদক</td><td style="text-align:right;">১ জন</td></tr>
        <tr><td>কার্যনির্বাহী সদস্য</td><td style="text-align:right;">৮ জন</td></tr>
        <tr style="font-weight:bold;background:#f8f8f8;color:#800020;"><td>সর্বমোট সদস্য সংখ্যা</td><td style="text-align:right;">২৭ জন</td></tr>
      </tbody>
    </table>
  </div>

  <!-- 11 -->
  <div class="dhara-box">
    <div class="dhara-title">১১. কার্য নির্বাহী পরিষদ গঠনঃ</div>
    <div>কেবলমাত্র ইন্সটিটিউট অব পাবলিক হেলথ এলামনাই এসোসিয়েনের সদস্য বিভিন্ন পদে প্রার্থী হতে এবং ভোটাধিকার প্রয়োগ করতে পারবেন। সংগঠনের কার্য নির্বাহী সভায় নির্বাচনের মাধ্যমে এলামনাই এসোসিয়েনের সদস্যের মধ্য হতে নতুন কার্য নির্বাহী পরিষদ গঠিত হবে (অনলাইনে/অফলাইনে)। আরো উল্লেখ থাকে যে, এই নির্বাহী পরিষদের মেয়াদ হবে দুই (২) বছর। যৌক্তিক কারনে মেয়াদ বাড়তে পারে তবে বর্ধিত মেয়াদ কোন ক্রমেই ছয় (৬) মাসের বেশি হবে না।</div>
  </div>

  <!-- 12 -->
  <div class="dhara-box">
    <div class="dhara-title">১২. কার্য নির্বাহী পরিষদের কর্মকর্তাদের দায়িত্ব এবং কর্মপরিধিঃ</div>
    <div>কার্য নির্বাহী পরিষদ সংগঠনের উদ্দেশ্য ও লক্ষ্য বাস্তবায়নের জন্য দায়বদ্ধ এবং কর্মসূচি বাস্তবায়নের জন্য সিদ্ধান্ত গ্রহণ, বিধি ও পদ্ধতি জারি এবং কার্যকর করার কর্তৃত্ব/অধিকার সংরক্ষণ করবে। কার্যনির্বাহী কমিটি সংগঠনের কোন বিশেষ ক্রিয়াকলাপ বা কর্মসূচী নিয়ে কাজ করার জন্য উপ-কমিটি নিয়োগ করতে পারে। নির্বাহী পরিষদের সদস্যবৃন্দ গঠনতন্ত্র অনুযায়ী দায়িত্ব পালন করবে।</div>
    <div style="margin-top:6px;">
      <div><strong>ক) সভাপতিঃ</strong> সভাপতি হবেন সংগঠনের নিয়মতান্ত্রিক প্রধান। তিনি সাধারণ সম্পাদককে সভা আহ্বান এর জন্য পরামর্শ অথবা নির্দেশ দিতে পারবেন। ভোটে সমতা হলে তিনি কাস্টিং ভোট দিতে পারবেন।</div>
      <div><strong>খ) সিনিয়র সহ-সভাপতিঃ</strong> সভাপতির অনুপস্থিতিতে দায়িত্ব পালন করবেন।</div>
      <div><strong>গ) সহ-সভাপতিঃ</strong> সিনিয়র সভাপতির অনুপস্থিতিতে দায়িত্ব পালন করবেন।</div>
      <div><strong>ঘ) সাধারণ সম্পাদকঃ</strong> সংগঠনের প্রধান নির্বাহী। সভাপতির পরামর্শক্রমে সভা আহবান, পরিচালনা এবং সিদ্ধান্ত বাস্তবায়নের পদক্ষেপ গ্রহণ করবেন। কার্যক্রম সমন্বয় ও বার্ষিক প্রতিবেদন পেশ করবেন।</div>
      <div><strong>ঙ) যুগ্ম সম্পাদকঃ</strong> সাধারণ সম্পাদককে সহযোগিতা করবেন এবং তাঁর অনুপস্থিতিতে দায়িত্ব পালন করবেন।</div>
      <div><strong>চ) সাংগঠনিক সম্পাদকঃ</strong> সাংগঠনিক ঐক্য ও গতিশীলতা বৃদ্ধি এবং কর্মসূচি বাস্তবায়নে সহযোগিতা করবেন।</div>
      <div><strong>ছ) কোষাধ্যক্ষঃ</strong> যাবতীয় হিসাব সংরক্ষণ, বাজেট প্রণয়ন ও তহবিল সংগ্রহ করবেন। ব্যাংক হিসাব সভাপতি, সাধারণ সম্পাদক ও কোষাধ্যক্ষের মধ্যে যৌথভাবে পরিচালিত হবে (যে কোনো দুজনের স্বাক্ষরে টাকা তোলা যাবে)।</div>
      <div><strong>জ) দপ্তর সম্পাদকঃ</strong> সদস্য তালিকা, সভার কার্যবিবরণী ও নথি সংরক্ষণ করবেন।</div>
      <div><strong>ঝ) সাংস্কৃতিক ও ক্রীড়া সম্পাদকঃ</strong> সাংস্কৃতিক ও ক্রীড়া বিষয়ক কর্মসূচি প্রণয়ন ও আয়োজন করবেন।</div>
      <div><strong>ঞ) প্রচার ও জনসংযোগ সম্পাদকঃ</strong> যোগাযোগ রক্ষা, প্রচার কার্যক্রম এবং ম্যাগাজিন/নিউজলেটার প্রকাশ করবেন।</div>
      <div><strong>চ) কার্য নির্বাহী সদস্যঃ</strong> সভায় অংশগ্রহণ, মতামত প্রদান এবং অর্পিত দায়িত্ব পালন করবেন।</div>
    </div>
  </div>

  <!-- 13 -->
  <div class="dhara-box">
    <div class="dhara-title">১৩. পদত্যাগ ও শূন্য পদ:</div>
    <div>সভাপতি বা সাধারণ সম্পাদকের পদ শূন্য হলে যথাক্রমে সহ-সভাপতি বা যুগ্ম-সম্পাদক দায়িত্ব পালন করবেন। সভাপতি পদত্যাগ করতে চাইলে সিনিয়র সহ-সভাপতির কাছে এবং অন্য সদস্যরা সভাপতির কাছে পদত্যাগপত্র দেবেন।</div>
  </div>

  <!-- 14 -->
  <div class="dhara-box">
    <div class="dhara-title">১৪. উপ-কমিটিঃ</div>
    <div>কার্যনির্বাহী পরিষদ বিশেষ প্রয়োজনে উপ-কমিটি গঠন করতে পারে, যা কাজ শেষে বিলুপ্ত হবে।</div>
  </div>

  <!-- 15 -->
  <div class="dhara-box">
    <div class="dhara-title">১৫. সভাঃ</div>
    <div><strong>ক) কার্যনির্বাহী পরিষদের সভাঃ</strong> বছরে অন্তত চারটি সভা হবে। সাধারণ সভার জন্য ৭-১৫ দিন এবং জরুরি সভার জন্য ৩ দিনের নোটিশ লাগবে। বিশেষ প্রয়োজনে ২ ঘণ্টার নোটিশেও সভা ডাকা যায়।</div>
    <div><strong>খ) সাধারণ সভাঃ</strong> বছরে একবার বার্ষিক সাধারণ সভা অনুষ্ঠিত হবে।</div>
  </div>

  <!-- 16 -->
  <div class="dhara-box">
    <div class="dhara-title">১৬. কোরাম ও সবার সিদ্ধান্ত গ্রহনঃ</div>
    <div>দুই-তৃতীয়াংশ সদস্যের উপস্থিতিতে কোরাম হবে এবং সংখ্যাগরিষ্ঠের মতে সিদ্ধান্ত হবে। বিশেষ ক্ষেত্রে লিখিত সম্মতিও গৃহীত হতে পারে।</div>
  </div>

  <!-- 17 -->
  <div class="dhara-box">
    <div class="dhara-title">১৭. অর্থনীতি ও তহবিল:</div>
    <div><strong>আয়ের উৎস:</strong> ফি, অনুদান ও কার্যক্রম থেকে আয়।</div>
    <div><strong>ব্যয়ের খাত:</strong> স্থায়ী খরচ, শিক্ষার্থীদের অনুদান, প্রশিক্ষণ ও উন্নয়নমূলক কাজ।</div>
    <div><strong>হিসাব নিরীক্ষা:</strong> বছরে দুইবার (৬ মাস অন্তর) আয়-ব্যয়ের হিসাব প্রকাশ ও নিরীক্ষা করা হবে।</div>
  </div>

  <!-- 18 -->
  <div class="dhara-box">
    <div class="dhara-title">১৮. গঠনতন্ত্রের ব্যাখ্যা ও পরিবর্তনঃ</div>
    <div>গঠনতন্ত্রের ব্যাখ্যায় নির্বাহী পরিষদের সিদ্ধান্তই চূড়ান্ত। বার্ষিক প্রতিনিধি সভার দুই-তৃতীয়াংশ সদস্যের সমর্থনে গঠনতন্ত্র সংশোধন করা যাবে।</div>
  </div>
</div>

<script>
  window.onload = function() {
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.get('print') === '1') {
      window.print();
    }
  };
</script>
</body>
</html>
