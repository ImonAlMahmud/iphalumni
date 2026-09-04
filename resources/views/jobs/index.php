<?php
/**
 * Public Job Circulars Index View
 * Variables: $result, $q, $type
 */
?>
<div class="max-w-7xl mx-auto px-6 py-14">

  <!-- Header -->
  <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 mb-10">
    <div>
      <span class="font-mono text-[11px] tracking-widest text-[#2F8863] block mb-2 uppercase">
        <i class="fa-solid fa-briefcase mr-1.5"></i><?= __('ক্যারিয়ার ও সুযোগ', 'Career Opportunities') ?>
      </span>
      <h1 class="font-serif text-[clamp(28px,3.8vw,42px)] font-bold text-[#101820] leading-tight">
        <?= __('জব সার্কুলার ও ক্যারিয়ার সুযোগ', 'Job Circulars & Career Opportunities') ?>
      </h1>
      <p class="text-[14.5px] text-[#6B7178] mt-2 max-w-xl">
        <?= __('আমাদের ভেরিফাইড অ্যালামনাইদের শেয়ার করা জব সার্কুলারসমূহ। সুযোগ নিন এবং নিজের ক্যারিয়ারকে এগিয়ে নিন।',
              'Explore job circulars shared by our verified alumni. Take the next step in your career.') ?>
      </p>
    </div>

    <?php if (is_logged_in()): ?>
    <a href="<?= url('/portal/jobs/create') ?>"
       class="inline-flex items-center gap-2 px-5 py-3 rounded-2xl text-[14px] font-semibold text-white transition-all hover:-translate-y-0.5 shadow-lg shrink-0"
       style="background:linear-gradient(135deg,#A22638,#800020);">
      <i class="fa-solid fa-plus text-[12px]"></i>
      <?= __('নতুন জব পোস্ট করুন', 'Post a Job') ?>
    </a>
    <?php endif; ?>
  </div>

  <!-- Search & Filter Bar -->
  <form method="GET" action="<?= url('/jobs') ?>" class="mb-8 p-4 rounded-2xl"
        style="background:rgba(255,255,255,0.85);border:1px solid rgba(16,24,32,0.08);backdrop-filter:blur(16px);">
    <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
      <div class="relative md:col-span-2">
        <i class="fa-solid fa-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-[#9CA3AF] text-[13px]"></i>
        <input type="text" name="q" value="<?= e($q) ?>"
               placeholder="<?= __('জব টাইটেল, কোম্পানি বা লোকেশন দিয়ে সার্চ...', 'Search job title, company or location...') ?>"
               class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-gray-200 text-[13.5px] focus:outline-none focus:border-[#800020] transition-colors"
               style="background:rgba(255,255,255,0.9);">
      </div>

      <div class="flex gap-2">
        <select name="job_type" class="flex-1 px-3 py-2.5 rounded-xl border border-gray-200 text-[13.5px] focus:outline-none focus:border-[#800020] bg-white">
          <option value=""><?= __('সকল টাইপ', 'All Types') ?></option>
          <option value="Full-time" <?= $type==='Full-time'?'selected':'' ?>>Full-time</option>
          <option value="Part-time" <?= $type==='Part-time'?'selected':'' ?>>Part-time</option>
          <option value="Contract" <?= $type==='Contract'?'selected':'' ?>>Contract</option>
          <option value="Remote" <?= $type==='Remote'?'selected':'' ?>>Remote</option>
          <option value="Internship" <?= $type==='Internship'?'selected':'' ?>>Internship</option>
        </select>

        <button type="submit" class="px-5 py-2.5 rounded-xl text-[13.5px] font-semibold text-white transition-all"
                style="background:#101820;">
          <?= __('ফিল্টার', 'Filter') ?>
        </button>
      </div>
    </div>
  </form>

  <!-- Job Cards Grid -->
  <?php if (empty($result['items'])): ?>
  <div class="py-20 text-center rounded-3xl" style="background:rgba(255,255,255,0.6);border:1px solid rgba(16,24,32,0.07);">
    <div class="text-[48px] mb-3">💼</div>
    <h3 class="font-serif text-[19px] font-semibold text-[#101820] mb-2"><?= __('কোনো জব সার্কুলার পাওয়া যায়নি', 'No Job Circulars Found') ?></h3>
    <p class="text-[14px] text-[#6B7178]"><?= __('নতুন জব পোস্টের জন্য পরে আবার দেখুন।', 'Check back later for new job postings.') ?></p>
  </div>
  <?php else: ?>
  <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
    <?php foreach ($result['items'] as $job): ?>
    <a href="<?= url('/jobs/' . $job['id']) ?>"
       class="block p-6 rounded-2xl group transition-all duration-200 hover:-translate-y-1 hover:shadow-xl"
       style="background:rgba(255,255,255,0.85);border:1px solid rgba(16,24,32,0.07);backdrop-filter:blur(14px);">
      
      <div class="flex items-start justify-between gap-3 mb-3">
        <div>
          <span class="inline-flex items-center gap-1 font-mono text-[10.5px] font-semibold text-[#800020] px-2.5 py-0.5 rounded-full mb-2"
                style="background:rgba(128,0,32,0.07);border:1px solid rgba(128,0,32,0.18);">
            <?= e($job['job_type']) ?>
          </span>
          <h3 class="font-semibold text-[17px] text-[#101820] group-hover:text-[#800020] transition-colors leading-snug">
            <?= e($job['title']) ?>
          </h3>
          <div class="text-[13.5px] text-[#6B7178] font-medium mt-0.5">
            <i class="fa-solid fa-building mr-1.5 opacity-60"></i><?= e($job['company_name']) ?>
          </div>
        </div>

        <!-- Visibility Badge -->
        <?php if ($job['visibility'] === 'public'): ?>
        <span class="inline-flex items-center gap-1 font-mono text-[10px] text-[#2F8863] px-2.5 py-1 rounded-full border border-[#2F8863]/30 bg-[#2F8863]/10 shrink-0"
              title="Public Job - Verified Students can apply">
          🌐 <?= __('পাবলিক (স্টুডেন্ট)', 'Public') ?>
        </span>
        <?php else: ?>
        <span class="inline-flex items-center gap-1 font-mono text-[10px] text-[#800020] px-2.5 py-1 rounded-full border border-[#800020]/30 bg-[#800020]/10 shrink-0"
              title="Only Members can view and apply">
          🔒 <?= __('মেম্বার অনলি', 'Members Only') ?>
        </span>
        <?php endif; ?>
      </div>

      <div class="flex flex-wrap items-center gap-4 text-[12.5px] text-[#6B7178] mt-4 pt-4 border-t border-gray-100">
        <?php if (!empty($job['location'])): ?>
        <span class="flex items-center gap-1.5">
          <i class="fa-solid fa-location-dot text-[#9CA3AF]"></i>
          <?= e($job['location']) ?>
        </span>
        <?php endif; ?>

        <?php if (!empty($job['salary_range'])): ?>
        <span class="flex items-center gap-1.5">
          <i class="fa-solid fa-money-bill-wave text-[#2F8863]"></i>
          <?= e($job['salary_range']) ?>
        </span>
        <?php endif; ?>

        <?php if (!empty($job['deadline'])): ?>
        <span class="flex items-center gap-1.5 ml-auto text-[11.5px] font-mono text-[#9CA3AF]">
          <i class="fa-regular fa-clock"></i>
          <?= __('ডেডলাইন:', 'Deadline:') ?> <?= date('d M Y', strtotime($job['deadline'])) ?>
        </span>
        <?php endif; ?>
      </div>

      <!-- Poster Info -->
      <div class="mt-4 pt-3 flex items-center justify-between text-[11.5px] text-[#9CA3AF]">
        <div class="flex items-center gap-2">
          <i class="fa-solid fa-circle-user text-[13px] text-[#6B7178]"></i>
          <span><?= __('শেয়ার করেছেন:', 'Posted by:') ?> <strong class="text-[#101820] font-normal"><?= e($job['poster_name'] ?? 'Alumni Member') ?></strong></span>
        </div>
        <span class="text-[#800020] font-medium group-hover:underline flex items-center gap-1">
          <?= __('বিস্তারিত দেখুন', 'View Details') ?> <i class="fa-solid fa-arrow-right text-[10px]"></i>
        </span>
      </div>
    </a>
    <?php endforeach; ?>
  </div>
  <?php endif; ?>

</div>
