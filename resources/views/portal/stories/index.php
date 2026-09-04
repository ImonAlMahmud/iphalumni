<?php
/**
 * Alumni Portal — My Blogs Index View
 */
?>
<div class="w-full space-y-6">
  <div class="flex justify-between items-center mb-8 flex-wrap gap-4">
    <div>
      <span class="font-mono text-[11px] tracking-widest text-[#800020] uppercase block mb-1"><?= __('অ্যালামনাই পোস্টার সার্ভিস', 'ALUMNI BLOGGING PLATFORM') ?></span>
      <h1 class="font-serif text-[28px] font-bold text-[#101820]"><?= __('আমার ব্লগ ও গল্পসমূহ', 'My Blogs & Stories') ?></h1>
    </div>
    <a href="<?= url('/portal/stories/create') ?>" 
       class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl text-[14px] font-semibold text-white transition-all shadow-md hover:-translate-y-0.5"
       style="background: linear-gradient(135deg, #A22638, #800020);">
      ✍️ <?= __('নতুন ব্লগ লিখুন', 'Write New Blog') ?>
    </a>
  </div>

  <?php if (empty($stories)): ?>
  <div class="bg-white p-12 rounded-3xl text-center border border-gray-100 shadow-sm">
    <div class="w-16 h-16 rounded-2xl bg-[#800020]/10 text-[#800020] flex items-center justify-center text-[28px] mx-auto mb-4">📝</div>
    <h3 class="font-serif text-[20px] font-semibold text-[#101820] mb-2"><?= __('এখনো কোনো ব্লগ তৈরি করা হয়নি', 'No blogs submitted yet') ?></h3>
    <p class="text-[14px] text-[#6B7178] max-w-md mx-auto mb-6"><?= __('আপনার কাজের অভিজ্ঞতা, অর্জিত সাফল্য বা অ্যালমা ম্যাটার নিয়ে লেখা নিবন্ধ অ্যালামনাইদের সাথে শেয়ার করতে এখনই ব্লগ লিখুন।', 'Share your achievements, experiences, or stories with the alumni network by writing a blog post.') ?></p>
    <a href="<?= url('/portal/stories/create') ?>" 
       class="inline-flex items-center gap-2 px-6 py-2.5 rounded-xl text-[14px] font-semibold text-white transition-all shadow-md"
       style="background: linear-gradient(135deg, #A22638, #800020);">
      ✍️ <?= __('প্রথম ব্লগটি জমা দিন', 'Submit Your First Blog') ?>
    </a>
  </div>
  <?php else: ?>
  <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <?php foreach ($stories as $s): ?>
    <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm flex flex-col justify-between transition-all hover:shadow-md">
      <div>
        <div class="flex items-center justify-between gap-2 mb-3">
          <?php if (!empty($s['batch_year']) && $s['batch_year'] !== '0000'): ?>
          <span class="font-mono text-[11px] text-[#800020] font-medium bg-[#800020]/10 px-2.5 py-0.5 rounded-full">
            <?= e(str_starts_with(strtoupper($s['batch_year']), 'BATCH') ? $s['batch_year'] : ('Batch ' . $s['batch_year'])) ?>
          </span>
          <?php endif; ?>
          <?php if ($s['status'] === 'published'): ?>
          <span class="px-3 py-0.5 rounded-full text-[11px] font-semibold bg-emerald-100 text-emerald-800">
            ✓ <?= __('অনুমোদিত (পাবলিক)', 'Published') ?>
          </span>
          <?php elseif ($s['status'] === 'pending'): ?>
          <span class="px-3 py-0.5 rounded-full text-[11px] font-semibold bg-amber-100 text-amber-800 animate-pulse">
            ⏳ <?= __('পেন্ডিং (এডমিন রিভিউ)', 'Pending Review') ?>
          </span>
          <?php elseif ($s['status'] === 'rejected'): ?>
          <span class="px-3 py-0.5 rounded-full text-[11px] font-semibold bg-rose-100 text-rose-800">
            ✕ <?= __('প্রত্যাখ্যাত', 'Rejected') ?>
          </span>
          <?php else: ?>
          <span class="px-3 py-0.5 rounded-full text-[11px] font-semibold bg-gray-100 text-gray-700">
            <?= e($s['status']) ?>
          </span>
          <?php endif; ?>
        </div>

        <h3 class="font-serif text-[18px] font-bold text-[#101820] mb-2 leading-snug"><?= e($s['title']) ?></h3>
        <p class="text-[13px] text-[#6B7178] line-clamp-3 mb-4"><?= e($s['excerpt'] ?: mb_substr(strip_tags($s['content']), 0, 140)) ?></p>
      </div>

      <div class="pt-4 border-t border-gray-100 flex items-center justify-between text-[12px] text-gray-400">
        <span><?= date('d M, Y', strtotime($s['created_at'])) ?></span>
        <div class="flex items-center gap-3">
          <?php if ($s['status'] !== 'published'): ?>
          <a href="<?= url('/portal/stories/' . $s['id'] . '/edit') ?>" class="text-[#800020] bg-[#800020]/10 px-3 py-1 rounded-lg font-semibold hover:bg-[#800020] hover:text-white transition-all flex items-center gap-1">
            ✏️ <?= __('সম্পাদনা', 'Edit') ?>
          </a>
          <?php endif; ?>

          <?php if ($s['status'] === 'published'): ?>
          <a href="<?= url('/stories/' . e($s['slug'])) ?>" target="_blank" class="text-[#800020] font-semibold hover:underline flex items-center gap-1">
            <?= __('পাবলিক পেজে দেখুন', 'View Live') ?> ↗
          </a>
          <?php else: ?>
          <span class="text-amber-700 font-medium"><?= __('এডমিন অনুমোদনের অপেক্ষায়', 'Awaiting Approval') ?></span>
          <?php endif; ?>
        </div>
      </div>
    </div>
    <?php endforeach; ?>
  </div>
  <?php endif; ?>
</div>
