<?php
/**
 * Public News Index View
 * Variables: $news
 */
?>
<div class="max-w-6xl mx-auto px-6 py-14">
  <div class="mb-8">
    <span class="font-mono text-[11px] tracking-widest text-[#2F8863] block mb-2"><?= __('প্রেস রিলিজ, নোটিশ ও আপডেট', 'PRESS RELEASES, NOTICES & NEWS') ?></span>
    <h1 class="font-serif text-[clamp(28px,4vw,40px)] font-semibold text-[#101820] mb-2"><?= __('আইপিএইচ এর অফিসিয়াল প্রকাশনা', 'Official Press & Announcements') ?></h1>
    <p class="text-[14px] text-[#6B7178]"><?= __('অ্যাসোসিয়েশনের সাম্প্রতিক প্রেস বিজ্ঞপ্তি, অফিসিয়াল নোটিশ, মিটিং রেজুলেশন ও খবরের তালিকা।', 'Official press releases, notices, meeting resolutions, and news updates from IPH Alumni Association.') ?></p>
  </div>

  <?php if (empty($news['items'])): ?>
  <div class="py-20 text-center glass">
    <p class="text-[#6B7178]"><?= __('কোনো খবরের নিবন্ধ প্রকাশ করা হয়নি।', 'No publications found.') ?></p>
  </div>
  <?php else: ?>
  <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
    <?php foreach ($news['items'] as $n):
      $cat = $n['category'] ?? 'news';
      $catBadges = [
        'news' => ['General News / সংবাদ', 'bg-indigo-50 text-indigo-700 border-indigo-200', '📰'],
        'press_release' => ['Press Release / প্রেস বিজ্ঞপ্তি', 'bg-amber-50 text-amber-800 border-amber-300', '📣'],
        'notice' => ['Official Notice / নোটিশ', 'bg-rose-50 text-rose-700 border-rose-200', '📌'],
        'resolution' => ['Meeting Resolution / মিটিং রেজুলেশন', 'bg-emerald-50 text-emerald-800 border-emerald-300', '📜'],
      ];
      $b = $catBadges[$cat] ?? $catBadges['news'];
    ?>
    <a href="<?= url('/news/' . e($n['slug'])) ?>" class="flex flex-col justify-between p-6 rounded-2xl transition-all hover:-translate-y-1 group"
       style="background:rgba(255,255,255,0.85);border:1px solid rgba(16,24,32,0.08);backdrop-filter:blur(14px);box-shadow:0 6px 24px -10px rgba(16,24,32,0.08);">
      <div>
        <div class="flex items-center justify-between gap-2 mb-3">
          <span class="px-2.5 py-0.5 rounded-full text-[11px] font-medium border inline-flex items-center gap-1 <?= $b[1] ?>">
            <span><?= $b[2] ?></span> <?= explode('/', $b[0])[0] ?>
          </span>
          <span class="font-mono text-[11px] text-[#6B7178]"><?= date('d M Y', strtotime($n['published_at'] ?? $n['created_at'])) ?></span>
        </div>
        <h3 class="font-serif text-[18px] font-semibold text-[#101820] mb-2 group-hover:text-[#800020] transition-colors"><?= e($n['title']) ?></h3>
        <p class="text-[13px] text-[#6B7178] leading-relaxed mb-4">
          <?= e(mb_strimwidth(strip_tags($n['content']), 0, 120, '…')) ?>
        </p>
      </div>

      <?php if (!empty($n['attachment_file'])): ?>
      <div class="pt-3 border-t border-black/5 text-[11.5px] font-medium text-amber-800 flex items-center gap-1.5">
        <i class="fa-solid fa-paperclip text-amber-600"></i> Official Document Attached
      </div>
      <?php endif; ?>
    </a>
    <?php endforeach; ?>
  </div>

  <!-- Pagination -->
  <?php if ($news['last_page'] > 1): ?>
  <div class="flex justify-center gap-2 mt-10">
    <?php for ($p = 1; $p <= $news['last_page']; $p++): ?>
    <a href="<?= url('/news?page=' . $p) ?>"
       class="w-9 h-9 rounded-xl flex items-center justify-center text-[13.5px] transition-all"
       style="<?= $p === $news['current_page'] ? 'background:linear-gradient(135deg,#A22638,#800020);color:#ffffff;font-weight:600;' : 'background:rgba(255,255,255,0.8);border:1px solid rgba(16,24,32,0.1);color:#6B7178;' ?>">
      <?= $p ?>
    </a>
    <?php endfor; ?>
  </div>
  <?php endif; ?>
  <?php endif; ?>
</div>
