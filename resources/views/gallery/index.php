<?php
/**
 * Gallery Albums Index View
 * Variables: $albums
 */
?>
<div class="max-w-6xl mx-auto px-6 py-14">
  <div class="mb-10 text-center">
    <span class="font-mono text-[11px] tracking-widest text-[#2F8863] block mb-2"><?= __('গ্যালারি', 'GALLERY') ?></span>
    <h1 class="font-serif text-[clamp(28px,4vw,40px)] font-semibold text-[#101820] mb-2"><?= __('স্মৃতির আঙিনা', 'Memory Lane') ?></h1>
    <p class="text-[14px] text-[#6B7178]"><?= __('পুনর্মিলনী, স্বাস্থ্য ক্যাম্পেইন এবং প্রাক্তন শিক্ষার্থীদের পুনর্মিলনীর কিছু মুহূর্ত।', 'Glimpses of reunions, health campaigns, and alumni meet-ups.') ?></p>
  </div>

  <?php if (empty($albums)): ?>
  <div class="py-20 text-center glass">
    <p class="text-[#6B7178]"><?= __('এখনো কোনো অ্যালবাম তৈরি করা হয়নি।', 'No albums have been created yet.') ?></p>
  </div>
  <?php else: ?>
  <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
    <?php foreach ($albums as $album): ?>
    <a href="<?= url('/gallery/' . $album['id']) ?>" class="block rounded-2xl overflow-hidden hover-lift"
       style="background:rgba(255,255,255,0.72);border:1px solid rgba(16,24,32,0.08);backdrop-filter:blur(14px);box-shadow:0 6px 24px -10px rgba(16,24,32,0.08);">
      
      <div class="aspect-[4/3] bg-[#0E1520]/80 relative overflow-hidden">
        <?php if (empty($album['photos'])): ?>
          <div class="w-full h-full flex items-center justify-center">
            <span class="text-white/20 text-[26px]">🖼️</span>
          </div>
        <?php elseif (count($album['photos']) === 1): ?>
          <img src="<?= asset('storage/gallery/' . $album['id'] . '/' . e($album['photos'][0])) ?>" alt="" class="w-full h-full object-cover">
        <?php elseif (count($album['photos']) === 2): ?>
          <div class="grid grid-cols-2 h-full gap-0.5">
            <img src="<?= asset('storage/gallery/' . $album['id'] . '/' . e($album['photos'][0])) ?>" alt="" class="w-full h-full object-cover">
            <img src="<?= asset('storage/gallery/' . $album['id'] . '/' . e($album['photos'][1])) ?>" alt="" class="w-full h-full object-cover">
          </div>
        <?php elseif (count($album['photos']) === 3): ?>
          <div class="grid grid-cols-3 h-full gap-0.5">
            <img src="<?= asset('storage/gallery/' . $album['id'] . '/' . e($album['photos'][0])) ?>" alt="" class="w-full h-full object-cover col-span-2">
            <div class="grid grid-rows-2 gap-0.5 h-full">
              <img src="<?= asset('storage/gallery/' . $album['id'] . '/' . e($album['photos'][1])) ?>" alt="" class="w-full h-full object-cover">
              <img src="<?= asset('storage/gallery/' . $album['id'] . '/' . e($album['photos'][2])) ?>" alt="" class="w-full h-full object-cover">
            </div>
          </div>
        <?php else: ?>
          <div class="grid grid-cols-2 grid-rows-2 h-full gap-0.5">
            <?php foreach ($album['photos'] as $p): ?>
              <img src="<?= asset('storage/gallery/' . $album['id'] . '/' . e($p)) ?>" alt="" class="w-full h-full object-cover">
            <?php endforeach; ?>
          </div>
        <?php endif; ?>
      </div>
      
      <div class="p-5">
        <h3 class="font-serif text-[17px] font-semibold text-[#101820] truncate"><?= e($album['title']) ?></h3>
        <p class="text-[12.5px] text-[#6B7178] mt-1"><?= date('d M Y', strtotime($album['album_date'])) ?></p>
      </div>
    </a>
    <?php endforeach; ?>
  </div>
  <?php endif; ?>
</div>
