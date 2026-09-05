<?php
/**
 * Single Success Story View
 * Variables: $story
 */
?>
<div class="max-w-4xl mx-auto px-6 py-14 font-['Kalpurush']">
  <div class="mb-6">
    <a href="<?= url('/stories') ?>" class="text-[13px] text-[#6B7178] hover:text-[#101820] inline-flex items-center gap-1 font-sans">
      ← <?= __('সকল আর্টিকেলে ফিরে যান', 'Back to Blogs & Articles') ?>
    </a>
  </div>

  <?php if (!empty($story['batch_year']) && $story['batch_year'] !== '0000'): ?>
  <span class="font-mono text-[11px] text-[#A22638] block mb-2">BATCH <?= e($story['batch_year']) ?></span>
  <?php endif; ?>
  <h1 class="font-serif text-[clamp(26px,4vw,36px)] font-bold text-[#101820] mb-6 leading-tight font-['Kalpurush']"><?= e($story['title']) ?></h1>

  <?php 
  $rawCover = $story['cover_image'] ?? '';
  $hasCover = false;
  $coverUrl = '';
  if (!empty($rawCover)) {
      if (str_starts_with($rawCover, 'http://') || str_starts_with($rawCover, 'https://')) {
          $hasCover = true;
          $coverUrl = $rawCover;
      } else {
          $cleanPath = ltrim($rawCover, '/');
          $candidates = [
              'storage/stories/' . $cleanPath,
              $cleanPath,
              'uploads/stories/' . $cleanPath,
              'uploads/' . $cleanPath,
              'storage/' . $cleanPath
          ];
          $publicDir = dirname(__DIR__, 2) . '/public';
          foreach ($candidates as $cand) {
              if (file_exists($publicDir . '/' . $cand)) {
                  $hasCover = true;
                  $coverUrl = asset($cand);
                  break;
              }
          }
      }
  }
  ?>
  <?php if ($hasCover): ?>
  <div class="rounded-3xl overflow-hidden mb-8 shadow-md">
    <img src="<?= $coverUrl ?>" alt="" class="w-full object-cover max-h-[400px]">
  </div>
  <?php endif; ?>

  <!-- Content -->
  <article class="prose max-w-none text-[16px] text-[#12181F] leading-relaxed space-y-6 font-['Kalpurush']">
    <?= nl2br(e($story['content'])) ?>
  </article>
</div>
