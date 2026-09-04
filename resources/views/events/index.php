<?php
/**
 * Public Events Index View
 * Variables: $upcoming, $past
 */
?>
<div class="max-w-6xl mx-auto px-6 py-14">
  <div class="mb-10">
    <span class="font-mono text-[11px] tracking-widest text-[#2F8863] block mb-2"><?= __('ইভেন্টসমূহ', 'EVENTS') ?></span>
    <h1 class="font-serif text-[clamp(28px,4vw,40px)] font-semibold text-[#101820] mb-2"><?= __('আইপিএইচ অ্যালামনাই ইভেন্টসমূহ', 'IPH Alumni Events') ?></h1>
    <p class="text-[14px] text-[#6B7178]"><?= __('আমাদের পরবর্তী মিলনমেলা, পাবলিক হেলথ সেমিনার এবং পুনর্মিলনী অনুষ্ঠানে অংশ নিন।', 'Join our upcoming meets, public health seminars, and reunion programs.') ?></p>
  </div>

  <!-- Upcoming Events -->
  <div class="mb-14">
    <h2 class="font-serif text-[22px] font-semibold text-[#101820] mb-6"><?= __('পরবর্তী ইভেন্টসমূহ', 'Upcoming Events') ?></h2>
    <?php if (empty($upcoming)): ?>
    <p class="text-[#6B7178] py-8 glass text-center"><?= __('এই মুহূর্তে কোনো ইভেন্ট নির্ধারিত নেই।', 'No upcoming events scheduled at the moment.') ?></p>
    <?php else: ?>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
      <?php foreach ($upcoming as $ev): ?>
      <div class="rounded-2xl overflow-hidden flex flex-col hover-lift"
           style="background:rgba(255,255,255,0.72);border:1px solid rgba(16,24,32,0.08);backdrop-filter:blur(14px);box-shadow:0 6px 24px -10px rgba(16,24,32,0.08);">
        <div class="px-6 py-4 flex justify-between items-center border-b" style="border-color:rgba(16,24,32,0.07);">
          <span class="font-serif text-[24px] font-semibold text-[#A22638]"><?= date('d', strtotime($ev['event_date'])) ?></span>
          <span class="font-mono text-[11px] text-[#6B7178] uppercase"><?= date('M · Y', strtotime($ev['event_date'])) ?></span>
        </div>
        <div class="p-6 flex-1 flex flex-col">
          <h3 class="text-[16px] font-semibold text-[#101820] mb-2"><?= e($ev['title']) ?></h3>
          <p class="text-[13px] text-[#6B7178] mb-4"><?= e(mb_strimwidth(strip_tags($ev['description'] ?? ''), 0, 100, '…')) ?></p>
          <a href="<?= url('/events/' . e($ev['slug'])) ?>" class="mt-auto inline-flex items-center gap-1.5 text-[13px] font-semibold text-[#800020] hover:underline">
            <?= __('বিস্তারিত দেখুন →', 'View Details →') ?>
          </a>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
    <?php endif; ?>
  </div>

  <!-- Past Events -->
  <div>
    <h2 class="font-serif text-[22px] font-semibold text-[#101820] mb-6"><?= __('অতীত ইভেন্টসমূহ', 'Past Events') ?></h2>
    <?php if (empty($past)): ?>
    <p class="text-[#6B7178]"><?= __('কোনো অতীত ইভেন্ট রেকর্ড নেই।', 'No past events recorded.') ?></p>
    <?php else: ?>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
      <?php foreach ($past as $ev): ?>
      <div class="p-6 rounded-2xl glass opacity-75">
        <span class="font-mono text-[11px] text-[#6B7178] block mb-2"><?= date('d M Y', strtotime($ev['event_date'])) ?></span>
        <h3 class="text-[15px] font-semibold text-[#101820] mb-1"><?= e($ev['title']) ?></h3>
        <p class="text-[12.5px] text-[#6B7178]"><?= e($ev['venue'] ?? __('অনলাইন', 'Online')) ?></p>
      </div>
      <?php endforeach; ?>
    </div>
    <?php endif; ?>
  </div>
</div>
