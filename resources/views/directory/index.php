<?php
/**
 * Directory Index View
 * Variables: $result (items, total, current_page, per_page, last_page), $batches, $filters
 */
$bdDistricts = [
  'Bagerhat','Bandarban','Barguna','Barishal','Bhola','Bogura','Brahmanbaria','Chandpur','Chattogram','Chuadanga',
  'Cox\'s Bazar','Cumilla','Dhaka','Dinajpur','Faridpur','Feni','Gaibandha','Gazipur','Gopalganj','Habiganj',
  'Jamalpur','Jashore','Jhalokati','Jhenaidah','Joypurhat','Khagrachhari','Khulna','Kishoreganj','Kurigram','Kushtia',
  'Lakshmipur','Lalmonirhat','Madaripur','Magura','Manikganj','Meherpur','Moulvibazar','Munshiganj','Mymensingh','Naogaon',
  'Narail','Narayanganj','Narsingdi','Natore','Netrokona','Nilphamari','Noakhali','Pabna','Panchagarh','Patuakhali',
  'Pirojpur','Rajbari','Rajshahi','Rangamati','Rangpur','Satkhira','Shariatpur','Sherpur','Sirajganj','Sunamganj',
  'Sylhet','Tangail','Thakurgaon'
];

$fQ           = $filters['q'] ?? '';
$fBatch       = $filters['batch'] ?? '';
$fUni         = $filters['university'] ?? '';
$fProg        = $filters['programme'] ?? '';
$fPhone       = $filters['phone'] ?? '';
$fDesig       = $filters['designation'] ?? '';
$fOrg         = $filters['organization'] ?? '';
$fLoc         = $filters['location'] ?? '';
$fCountry     = $filters['country'] ?? '';
$fLocType     = $filters['location_type'] ?? '';
$fFeatured    = !empty($filters['is_featured']);

$hasSearch = ($fQ || $fBatch || $fUni || $fProg || $fPhone || $fDesig || $fOrg || $fLoc || $fCountry || $fLocType || $fFeatured);
?>
<div class="max-w-7xl mx-auto px-6 py-14">

  <!-- Header -->
  <div class="mb-10">
    <span class="font-mono text-[11px] tracking-widest text-[#2F8863] block mb-3 uppercase"><?= __('অ্যালামনাই ডিরেক্টরি', 'Alumni Directory') ?></span>
    <h1 class="font-serif text-[clamp(28px,4vw,44px)] font-semibold text-[#101820] mb-3 leading-tight">
      <?= __('প্রতিটি ভেরিফাইড প্রোফাইল,<br>মাত্র এক ক্লিকে।', 'Every verified profile,<br>one search away.') ?>
    </h1>
    <p class="text-[15px] text-[#6B7178] max-w-xl">
      <?= __('ব্যাচ, পেশা, বিশ্ববিদ্যালয় বা অবস্থান দিয়ে সার্চ করুন — অ্যাডমিন কর্তৃক অনুমোদিত প্রতিটি প্রোফাইল।',
            'Search by batch, profession, university, or location — every profile is personally verified by our admin team.') ?>
    </p>
  </div>

  <!-- Multi-Field Search & Filter Box -->
  <form method="GET" action="<?= url('/directory') ?>" 
        x-data="{ 
          showEdu: <?= (!empty($fUni) || !empty($fProg)) ? 'true' : 'false' ?>,
          showWork: <?= (!empty($fDesig) || !empty($fOrg)) ? 'true' : 'false' ?>,
          showLoc: <?= (!empty($fLoc) || !empty($fCountry) || !empty($fLocType)) ? 'true' : 'false' ?>,
          showContact: <?= (!empty($fPhone) || !empty($fBatch)) ? 'true' : 'false' ?>
        }"
        class="bg-white/90 backdrop-blur-md p-6 rounded-3xl border border-gray-100 shadow-sm mb-10 space-y-4">
    
    <!-- Top Row: General Query Search -->
    <div class="flex flex-wrap gap-3">
      <div class="flex-1 min-w-[240px] relative">
        <svg class="absolute left-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-[#6B7178]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
        </svg>
        <input type="text" name="q" value="<?= e($fQ) ?>" placeholder="<?= __('নাম, বিবরণ বা যেকোনো শব্দ দিয়ে সার্চ...', 'Search name, details...') ?>"
               class="w-full pl-10 pr-4 py-2.5 rounded-xl text-[14px] text-[#101820] focus:outline-none focus:ring-2 focus:ring-[#800020]/30 border border-gray-200">
      </div>
      <button type="submit" class="px-8 py-2.5 rounded-xl text-[14px] font-semibold text-white transition-all shadow hover:-translate-y-0.5"
              style="background:linear-gradient(135deg,#A22638,#800020);">
        🔍 <?= __('অনুসন্ধান করুন', 'Search Alumni') ?>
      </button>
      <?php if ($hasSearch): ?>
      <a href="<?= url('/directory') ?>" class="px-5 py-2.5 rounded-xl text-[14px] font-medium text-gray-600 bg-gray-100 hover:bg-gray-200 transition-all">
        <?= __('ফিল্টার রিসেট', 'Clear Filters') ?>
      </a>
      <?php endif; ?>
    </div>

    <!-- Category Toggle Badges (On/Off Controls) -->
    <div class="pt-3 border-t border-gray-100 flex items-center flex-wrap gap-2 text-[12.5px]">
      <span class="text-gray-400 font-medium mr-1 text-[11.5px] uppercase font-mono">🔍 <?= __('ফিল্টার ক্যাটাগরি বেছে নিন:', 'Search Criteria:') ?></span>
      
      <!-- Education Toggle -->
      <button type="button" @click="showEdu = !showEdu" 
              :class="showEdu ? 'bg-[#800020] text-white border-[#800020] shadow-sm' : 'bg-gray-50 text-gray-600 border-gray-200 hover:bg-gray-100'" 
              class="px-3.5 py-1.5 rounded-xl font-medium border transition-all flex items-center gap-1.5 cursor-pointer">
        <span>🎓 Education / বিশ্ববিদ্যালয়</span>
        <span x-text="showEdu ? '✓' : '+'" class="font-bold text-[11px]"></span>
      </button>

      <!-- Work Toggle -->
      <button type="button" @click="showWork = !showWork" 
              :class="showWork ? 'bg-[#800020] text-white border-[#800020] shadow-sm' : 'bg-gray-50 text-gray-600 border-gray-200 hover:bg-gray-100'" 
              class="px-3.5 py-1.5 rounded-xl font-medium border transition-all flex items-center gap-1.5 cursor-pointer">
        <span>💼 Work & Designation / পদবি ও প্রতিষ্ঠান</span>
        <span x-text="showWork ? '✓' : '+'" class="font-bold text-[11px]"></span>
      </button>

      <!-- Location Toggle -->
      <button type="button" @click="showLoc = !showLoc" 
              :class="showLoc ? 'bg-[#800020] text-white border-[#800020] shadow-sm' : 'bg-gray-50 text-gray-600 border-gray-200 hover:bg-gray-100'" 
              class="px-3.5 py-1.5 rounded-xl font-medium border transition-all flex items-center gap-1.5 cursor-pointer">
        <span>📍 Location & Country / জেলা ও দেশ</span>
        <span x-text="showLoc ? '✓' : '+'" class="font-bold text-[11px]"></span>
      </button>

      <!-- Contact & Batch Toggle -->
      <button type="button" @click="showContact = !showContact" 
              :class="showContact ? 'bg-[#800020] text-white border-[#800020] shadow-sm' : 'bg-gray-50 text-gray-600 border-gray-200 hover:bg-gray-100'" 
              class="px-3.5 py-1.5 rounded-xl font-medium border transition-all flex items-center gap-1.5 cursor-pointer">
        <span>📞 Phone & Batch / ফোন ও ব্যাচ</span>
        <span x-text="showContact ? '✓' : '+'" class="font-bold text-[11px]"></span>
      </button>

      <!-- Featured Alumni Toggle -->
      <a href="<?= url('/directory?' . http_build_query(array_filter(array_merge($filters, ['is_featured' => $fFeatured ? '' : '1'])))) ?>"
         class="px-3.5 py-1.5 rounded-xl font-medium border transition-all flex items-center gap-1.5 cursor-pointer <?= $fFeatured ? 'bg-amber-500 text-white border-amber-600 shadow-sm' : 'bg-amber-50/80 text-amber-800 border-amber-200 hover:bg-amber-100' ?>">
        <span>⭐ <?= __('বিশিষ্ট অ্যালামনাই (Featured)', 'Featured Alumni') ?></span>
        <span class="font-bold text-[11px]"><?= $fFeatured ? '✓' : '+' ?></span>
      </a>
    </div>

    <?php if ($fFeatured): ?>
    <input type="hidden" name="is_featured" value="1">
    <?php endif; ?>

    <!-- Dynamic Category Sections -->
    <div class="space-y-4 pt-1" x-show="showEdu || showWork || showLoc || showContact" x-transition>
      
      <!-- EDUCATION SECTION -->
      <div x-show="showEdu" x-transition class="p-4 bg-blue-50/60 border border-blue-100 rounded-2xl">
        <h4 class="text-[12px] font-mono font-semibold text-blue-900 mb-3 flex items-center gap-1.5">🎓 Education Criteria (শিক্ষাগত তথ্য)</h4>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-[13px]">
          <div>
            <label class="block text-[11px] font-mono text-gray-500 uppercase mb-1">🎓 <?= __('বিশ্ববিদ্যালয় (University)', 'University Name') ?></label>
            <input type="text" name="university" value="<?= e($fUni) ?>" placeholder="e.g. Dhaka University / Harvard" class="w-full px-3 py-2 rounded-xl border border-gray-200 bg-white focus:outline-none focus:ring-2 focus:ring-[#800020]/30">
          </div>
          <div>
            <label class="block text-[11px] font-mono text-gray-500 uppercase mb-1">📜 <?= __('প্রোগ্রাম / ডিগ্রী (Programme)', 'Programme') ?></label>
            <input type="text" name="programme" value="<?= e($fProg) ?>" placeholder="e.g. M.Sc. / Ph.D." class="w-full px-3 py-2 rounded-xl border border-gray-200 bg-white focus:outline-none focus:ring-2 focus:ring-[#800020]/30">
          </div>
        </div>
      </div>

      <!-- WORK SECTION -->
      <div x-show="showWork" x-transition class="p-4 bg-emerald-50/60 border border-emerald-100 rounded-2xl">
        <h4 class="text-[12px] font-mono font-semibold text-emerald-900 mb-3 flex items-center gap-1.5">💼 Career & Employment Criteria (কর্মক্ষেত্রের তথ্য)</h4>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-[13px]">
          <div>
            <label class="block text-[11px] font-mono text-gray-500 uppercase mb-1">💼 <?= __('পদবি (Designation)', 'Designation') ?></label>
            <input type="text" name="designation" value="<?= e($fDesig) ?>" placeholder="e.g. Specialist / Researcher" class="w-full px-3 py-2 rounded-xl border border-gray-200 bg-white focus:outline-none focus:ring-2 focus:ring-[#800020]/30">
          </div>
          <div>
            <label class="block text-[11px] font-mono text-gray-500 uppercase mb-1">🏢 <?= __('কর্মক্ষেত্র / প্রতিষ্ঠান (Organization)', 'Workplace / Company') ?></label>
            <input type="text" name="organization" value="<?= e($fOrg) ?>" placeholder="e.g. Institute of Public Health / WHO" class="w-full px-3 py-2 rounded-xl border border-gray-200 bg-white focus:outline-none focus:ring-2 focus:ring-[#800020]/30">
          </div>
        </div>
      </div>

      <!-- LOCATION SECTION -->
      <div x-show="showLoc" x-transition class="p-4 bg-amber-50/60 border border-amber-100 rounded-2xl">
        <h4 class="text-[12px] font-mono font-semibold text-amber-900 mb-3 flex items-center gap-1.5">📍 Location & Residence Criteria (অবস্থান ও বাসস্থান)</h4>
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 text-[13px]">
          <div>
            <label class="block text-[11px] font-mono text-gray-500 uppercase mb-1">📍 <?= __('বাংলাদেশ অবস্থান (District/Thana)', 'Current Location') ?></label>
            <input type="text" name="location" value="<?= e($fLoc) ?>" placeholder="e.g. Dhaka / Savar" class="w-full px-3 py-2 rounded-xl border border-gray-200 bg-white focus:outline-none focus:ring-2 focus:ring-[#800020]/30">
          </div>
          <div>
            <label class="block text-[11px] font-mono text-gray-500 uppercase mb-1">✈️ <?= __('বিদেশ / দেশ (Abroad / Country)', 'Country') ?></label>
            <input type="text" name="country" value="<?= e($fCountry) ?>" placeholder="e.g. USA / Canada / London" class="w-full px-3 py-2 rounded-xl border border-gray-200 bg-white focus:outline-none focus:ring-2 focus:ring-[#800020]/30">
          </div>
          <div>
            <label class="block text-[11px] font-mono text-gray-500 uppercase mb-1">🌐 <?= __('অবস্থান টাইপ (Residence)', 'Residence') ?></label>
            <select name="location_type" class="w-full px-3 py-2 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-[#800020]/30 bg-white">
              <option value=""><?= __('সকল অবস্থান (All Locations)', 'All Locations') ?></option>
              <option value="bangladesh" <?= $fLocType === 'bangladesh' ? 'selected' : '' ?>>🇧🇩 Bangladesh Only</option>
              <option value="abroad" <?= $fLocType === 'abroad' ? 'selected' : '' ?>>✈️ Abroad Only</option>
            </select>
          </div>
        </div>
      </div>

      <!-- CONTACT & BATCH SECTION -->
      <div x-show="showContact" x-transition class="p-4 bg-purple-50/60 border border-purple-100 rounded-2xl">
        <h4 class="text-[12px] font-mono font-semibold text-purple-900 mb-3 flex items-center gap-1.5">📞 Contact & Batch Criteria (যোগাযোগ ও ব্যাচ)</h4>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-[13px]">
          <div>
            <label class="block text-[11px] font-mono text-gray-500 uppercase mb-1">📞 <?= __('ফোন নম্বর (Phone)', 'Phone Number') ?></label>
            <input type="text" name="phone" value="<?= e($fPhone) ?>" placeholder="e.g. +88017..." class="w-full px-3 py-2 rounded-xl border border-gray-200 bg-white focus:outline-none focus:ring-2 focus:ring-[#800020]/30">
          </div>
          <div>
            <label class="block text-[11px] font-mono text-gray-500 uppercase mb-1">🎓 <?= __('ব্যাচ (Batch)', 'Batch') ?></label>
            <select name="batch" class="w-full px-3 py-2 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-[#800020]/30 bg-white">
              <option value=""><?= __('সকল ব্যাচ (All Batches)', 'All Batches') ?></option>
              <?php foreach ($batches as $b): ?>
              <option value="<?= $b ?>" <?= (string)$fBatch === (string)$b ? 'selected' : '' ?>><?= e(str_starts_with(strtoupper((string)$b), 'BATCH') ? $b : ('Batch ' . $b)) ?></option>
              <?php endforeach; ?>
            </select>
          </div>
        </div>
      </div>

    </div>
  </form>

  <!-- Results count -->
  <div class="flex items-center justify-between mb-6">
    <div class="flex items-center gap-2 text-[13px] text-[#6B7178] font-mono">
      <span class="font-semibold text-[#101820] text-[16px] font-serif"><?= number_format($result['total']) ?></span>
      <?= __('জন অ্যালামনাই পাওয়া গেছে', 'alumni found') ?>
    </div>
    <?php if ($hasSearch): ?>
    <a href="<?= url('/directory') ?>" class="text-[12.5px] text-[#800020] font-medium hover:underline">
      <?= __('সার্চ মুছুন ×', 'Clear search ×') ?>
    </a>
    <?php endif; ?>
  </div>

  <!-- Grid -->
  <?php if (empty($result['items'])): ?>
  <div class="py-24 text-center rounded-3xl" style="background:rgba(255,255,255,0.6);border:1px solid rgba(16,24,32,0.07);">
    <div class="text-[52px] mb-4">🔍</div>
    <div class="font-serif text-[20px] font-semibold text-[#101820] mb-2"><?= __('কোনো অ্যালামনাই পাওয়া যায়নি', 'No alumni found') ?></div>
    <p class="text-[14px] text-[#6B7178] mb-5 max-w-xs mx-auto"><?= __('ভিন্ন কিছু দিয়ে সার্চ করে দেখুন।', 'Try adjusting your search filters.') ?></p>
    <a href="<?= url('/directory') ?>" class="inline-flex items-center gap-1.5 px-6 py-2.5 rounded-xl text-[14px] font-semibold text-[#800020]"
       style="background:rgba(128,0,32,0.06);border:1px solid rgba(128,0,32,0.2);"><?= __('সার্চ রিসেট করুন', 'Reset Search') ?></a>
  </div>
  <?php else: ?>
  <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-5">
    <?php foreach ($result['items'] as $alum): ?>
    <a href="<?= url('/directory/' . $alum['id']) ?>"
       class="block p-5 rounded-2xl group transition-all duration-200 hover:-translate-y-1 hover:shadow-lg relative"
       style="background:rgba(255,255,255,0.82);border:1px solid rgba(16,24,32,0.07);backdrop-filter:blur(14px);">
      <?php if (!empty($alum['is_featured'])): ?>
      <span class="absolute top-3.5 right-3.5 px-2 py-0.5 rounded-full text-[10px] font-bold bg-amber-500/15 text-amber-700 border border-amber-500/30 flex items-center gap-1">
        ★ <?= __('Featured', 'Featured') ?>
      </span>
      <?php endif; ?>
      <!-- Avatar -->
      <div class="w-16 h-16 rounded-full mx-auto mb-4 flex items-center justify-center font-serif font-semibold text-[20px] overflow-hidden border-2 border-white shadow-md"
           style="background:linear-gradient(135deg,#153548,#2F8863);color:#FAFAFA;">
        <?php if (!empty($alum['avatar'])): ?>
          <img src="<?= avatar_url($alum['avatar']) ?>" alt="<?= e($alum['name'] ?? '') ?>" class="w-16 h-16 rounded-full object-cover" onerror="this.onerror=null; this.parentElement.innerHTML='<?= initials($alum['name'] ?? 'A') ?>';">
        <?php else: ?>
          <?= initials($alum['name'] ?? 'A') ?>
        <?php endif; ?>
      </div>

      <div class="text-center">
        <div class="font-semibold text-[15px] text-[#101820] truncate group-hover:text-[#800020] transition-colors"><?= e($alum['name'] ?? '') ?></div>

        <?php if (!empty($alum['job_title'])): ?>
        <div class="text-[12px] font-medium text-[#800020] mt-0.5 truncate"><?= e($alum['job_title']) ?></div>
        <?php endif; ?>

        <?php if (!empty($alum['organization'])): ?>
        <div class="text-[11.5px] text-[#9CA3AF] truncate mt-0.5"><?= e($alum['organization']) ?></div>
        <?php endif; ?>

        <?php if (!empty($alum['institution']) || !empty($alum['degree'])): ?>
        <div class="text-[11px] text-[#6B7178] mt-1.5 truncate">🎓 <?= e($alum['institution'] ?: $alum['degree']) ?></div>
        <?php endif; ?>

        <div class="text-[11px] text-[#9CA3AF] mt-2 flex items-center justify-center gap-1.5 flex-wrap">
          <?php if (($alum['location_type'] ?? '') === 'abroad' || !empty($alum['country'])): ?>
            <span>✈️ <?= e($alum['country'] ?: $alum['province_city']) ?></span>
          <?php elseif (!empty($alum['current_location'])): ?>
            <span>📍 <?= e($alum['current_location']) ?></span>
          <?php endif; ?>

          <?php if (!empty($alum['batch_year'])): ?>
            <span class="ml-1 font-mono text-gray-600 font-semibold">(Batch <?= e($alum['batch_year']) ?>)</span>
          <?php endif; ?>
        </div>

        <span class="inline-flex items-center gap-1 font-mono text-[10px] text-[#2F8863] mt-3 px-2.5 py-0.5 rounded-full bg-[#2F8863]/10 border border-[#2F8863]/20">
          ✓ Verified Alumni
        </span>
      </div>
    </a>
    <?php endforeach; ?>
  </div>
  <?php endif; ?>

</div>
