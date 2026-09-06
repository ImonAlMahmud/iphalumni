<?php
/**
 * Single News Article View
 * Variables: $n (news article)
 */
?>
<div class="max-w-4xl mx-auto px-6 py-14">
  <div class="mb-6 flex items-center justify-between">
    <a href="<?= url('/news') ?>" class="text-[13px] text-[#6B7178] hover:text-[#101820] inline-flex items-center gap-1.5 font-medium transition-colors">
      <i class="fa-solid fa-arrow-left text-[11px]"></i> <?= __('খবরে ফিরে যান', 'Back to Notices & News') ?>
    </a>

    <?php
      $cat = $n['category'] ?? 'news';
      $catBadges = [
        'news' => ['General News / সংবাদ', 'bg-indigo-50 text-indigo-700 border-indigo-200', 'fa-solid fa-newspaper'],
        'press_release' => ['Press Release / প্রেস বিজ্ঞপ্তি', 'bg-amber-50 text-amber-800 border-amber-300', 'fa-solid fa-bullhorn'],
        'notice' => ['Official Notice / নোটিশ', 'bg-rose-50 text-rose-700 border-rose-200', 'fa-solid fa-thumbtack'],
        'resolution' => ['Meeting Resolution / মিটিং রেজুলেশন', 'bg-emerald-50 text-emerald-800 border-emerald-300', 'fa-solid fa-scroll'],
      ];
      $b = $catBadges[$cat] ?? $catBadges['news'];
    ?>
    <span class="px-3 py-1 rounded-full text-[12px] font-semibold border inline-flex items-center gap-1.5 <?= $b[1] ?>">
      <i class="<?= $b[2] ?> text-[11px]"></i> <?= $b[0] ?>
    </span>
  </div>

  <?php if (!empty($refNo)): ?>
  <!-- Official Verified Document Banner -->
  <div class="mb-6 p-4 rounded-2xl bg-emerald-50 border border-emerald-200/90 flex items-center justify-between flex-wrap gap-3 shadow-sm">
    <div class="flex items-center gap-3">
      <div class="w-10 h-10 rounded-xl bg-emerald-600 text-white flex items-center justify-center text-lg shadow-sm shrink-0">
        <i class="fa-solid fa-circle-check"></i>
      </div>
      <div>
        <div class="font-bold text-[14px] text-emerald-950 flex items-center gap-1.5">
          <?= __('ভেরিফাইড অফিসিয়াল নোটিশ', 'Verified Official Notice') ?>
          <span class="px-2 py-0.5 rounded-full bg-emerald-200/70 text-emerald-800 text-[10px] font-mono font-bold tracking-wider">OFFICIAL</span>
        </div>
        <div class="font-mono text-[12px] text-emerald-800 mt-0.5">
          স্মারক নং / Ref: <strong><?= e($refNo) ?></strong>
        </div>
      </div>
    </div>
    <a href="<?= url('/news/' . $n['id'] . '/pdf?autoprint=1') ?>" target="_blank" class="px-4 py-2 rounded-xl bg-emerald-700 hover:bg-emerald-800 text-white text-[12.5px] font-semibold transition-all inline-flex items-center gap-1.5 shadow-sm">
      <i class="fa-solid fa-print text-[11.5px]"></i> <?= __('অফিসিয়াল নোটিশ প্রিন্ট / PDF', 'Print Official Notice') ?>
    </a>
  </div>
  <?php endif; ?>

  <span class="font-mono text-[12px] text-[#6B7178] block mb-2">
    <i class="fa-solid fa-calendar-days text-[11px] mr-1 text-[#6B7178]"></i> Published: <?= date('d F Y', strtotime($n['published_at'] ?? $n['created_at'])) ?>
  </span>
  <h1 class="font-serif text-[clamp(26px,4vw,36px)] font-semibold text-[#101820] mb-6 leading-tight"><?= e($n['title']) ?></h1>

  <?php if (!empty($n['cover_image'])): ?>
  <div class="rounded-3xl overflow-hidden mb-8 shadow-md">
    <img src="<?= asset('storage/news/' . e($n['cover_image'])) ?>" alt="" class="w-full object-cover max-h-[400px]">
  </div>
  <?php endif; ?>

  <article class="prose max-w-none text-[15.5px] text-[#12181F] leading-relaxed space-y-6 bg-white/70 p-6 rounded-2xl border border-black/5 shadow-sm">
    <?= nl2br($n['content']) ?>
  </article>

  <!-- Action Bar: Download Official Letterhead Pad PDF -->
  <div class="mt-8 p-5 rounded-2xl bg-[#800020]/5 border border-[#800020]/20 flex flex-wrap items-center justify-between gap-4">
    <div class="flex items-center gap-3">
      <div class="w-12 h-12 rounded-2xl bg-[#800020] text-white flex items-center justify-center text-xl shrink-0 shadow-md">
        <i class="fa-solid fa-file-invoice"></i>
      </div>
      <div>
        <div class="text-[15px] font-bold text-[#101820]">Official Letterhead Pad Notice PDF</div>
        <div class="text-[12.5px] text-[#6B7178]">অফিসিয়াল লেটারহেড প্যাড, QR কোড ও ডিজিটাল সিগনেচার সহ নোটিশ ডাউনলোড করুন।</div>
      </div>
    </div>
    <a href="<?= url('/news/' . $n['id'] . '/pdf?autoprint=1') ?>" target="_blank"
       class="px-6 py-3 rounded-xl text-[13.5px] font-semibold text-white bg-gradient-to-r from-[#800020] to-[#A22638] hover:shadow-lg transition-all inline-flex items-center gap-2">
      <i class="fa-solid fa-print"></i> Download Official Notice PDF
    </a>
  </div>

  <!-- Signatories Block (if any) -->
  <?php if (!empty($signatories)): ?>
  <div class="mt-10 pt-8 border-t border-black/10">
    <h3 class="text-[14px] font-mono text-[#6B7178] uppercase tracking-wider mb-6 text-center">Approved Signatories / অনুমোদনকারী কমিটি মেম্বারবৃন্দ</h3>
    <div class="grid grid-cols-2 md:grid-cols-4 gap-6 text-center">
      <?php foreach ($signatories as $sig): ?>
      <div class="p-4 rounded-2xl bg-white border border-gray-100 shadow-sm space-y-2">
        <div class="h-14 flex items-end justify-center">
          <?php if (!empty($sig['signature_image'])): ?>
          <img src="<?= asset('storage/signatures/' . e($sig['signature_image'])) ?>" alt="Signature" class="max-h-12 object-contain">
          <?php else: ?>
          <span class="text-[11px] text-gray-400 italic">(Signature Uploaded)</span>
          <?php endif; ?>
        </div>
        <div class="border-t border-gray-200 pt-2">
          <div class="text-[13px] font-bold text-[#101820]"><?= e($sig['name']) ?></div>
          <div class="text-[11.5px] text-gray-500 font-medium"><?= e($sig['designation_title'] ?: $sig['default_designation'] ?: 'Committee Member') ?></div>
          <div class="text-[10px] text-gray-400 mt-0.5">IPH Alumni Association</div>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
  <?php endif; ?>

  <!-- Official Attachment Download Box -->
  <?php if (!empty($n['attachment_file'])): ?>
  <div class="mt-8 p-5 rounded-2xl bg-amber-50/80 border border-amber-200 flex flex-wrap items-center justify-between gap-4">
    <div class="flex items-center gap-3">
      <div class="w-12 h-12 rounded-xl bg-amber-100 text-amber-800 flex items-center justify-center text-xl shrink-0">
        <i class="fa-solid fa-file-pdf"></i>
      </div>
      <div>
        <div class="text-[14px] font-bold text-[#101820]">সংযুক্ত ফাইল / Press Release Document</div>
        <div class="text-[12px] text-[#6B7178] font-mono"><?= e($n['attachment_file']) ?></div>
      </div>
    </div>
    <a href="<?= asset('storage/news/' . e($n['attachment_file'])) ?>" target="_blank" download
       class="px-5 py-2.5 rounded-xl text-[13px] font-semibold text-white bg-[#800020] hover:bg-[#A22638] transition-all inline-flex items-center gap-2 shadow-sm">
      <i class="fa-solid fa-download"></i> <?= __('ডাউনলোড করুন (Download File)', 'Download Official Attachment') ?>
    </a>
  </div>
  <?php endif; ?>
</div>
