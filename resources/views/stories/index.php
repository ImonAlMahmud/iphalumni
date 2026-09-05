<?php
/**
 * Success Stories Index View
 * Variables: $stories
 */
?>
<div class="max-w-6xl mx-auto px-6 py-14">
  <!-- Header -->
  <div class="mb-10">
    <span class="font-mono text-[11px] tracking-widest text-[#2F8863] block mb-2 uppercase"><?= __('ব্লগ ও আর্টিকেল', 'BLOGS & ARTICLES') ?></span>
    <h1 class="font-serif text-[clamp(28px,4vw,40px)] font-semibold text-[#101820] mb-2"><?= __('Blogs & Articles', 'Blogs & Articles') ?></h1>
    <p class="text-[14px] text-[#6B7178]"><?= __('আইপিএইচ অ্যালামনাইদের জ্ঞানগর্ভ লেখা, গবেষণামূলক প্রবন্ধ ও অনুপ্রেরণামূলক গল্পসমূহ।', 'Insights, research articles, and inspiring stories shared by our alumni community.') ?></p>
  </div>

  <?php if (empty($stories)): ?>
  <div class="py-20 text-center glass">
    <p class="text-[#6B7178]"><?= __('এখনো কোনো ব্লগ বা আর্টিকেল প্রকাশ করা হয়নি।', 'No blogs or articles have been published yet.') ?></p>
  </div>
  <?php else: ?>
  <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
    <?php foreach ($stories as $story): ?>
    <div class="rounded-2xl overflow-hidden flex flex-col hover-lift"
         style="background:rgba(255,255,255,0.72);border:1px solid rgba(16,24,32,0.08);backdrop-filter:blur(14px);box-shadow:0 6px 24px -10px rgba(16,24,32,0.08);">
      
      <?php if (!empty($story['cover_image'])): ?>
      <img src="<?= asset('storage/stories/' . e($story['cover_image'])) ?>" alt="" class="h-48 w-full object-cover">
      <?php endif; ?>
      
      <div class="p-6 flex-1 flex flex-col">
        <?php if (!empty($story['batch_year']) && $story['batch_year'] !== '0000'): ?>
        <span class="font-mono text-[11px] text-[#A22638]"><?= e(str_starts_with(strtoupper($story['batch_year']), 'BATCH') ? $story['batch_year'] : ('Batch ' . $story['batch_year'])) ?></span>
        <?php endif; ?>
        <h3 class="font-serif text-[18px] font-semibold text-[#101820] mt-2 mb-3"><?= e($story['title']) ?></h3>
        <p class="text-[13.5px] text-[#6B7178] leading-relaxed mb-6">
          <?= e($story['excerpt'] ?? mb_strimwidth(strip_tags($story['content']), 0, 120, '…')) ?>
        </p>
        <a href="<?= url('/stories/' . e($story['slug'])) ?>" class="mt-auto inline-flex items-center gap-1.5 text-[13.5px] font-semibold text-[#800020] hover:underline">
          <?= __('আর্টিকেলটি পড়ুন →', 'Read Article →') ?>
        </a>
      </div>
    </div>
    <?php endforeach; ?>
  </div>
  <?php endif; ?>
</div>
