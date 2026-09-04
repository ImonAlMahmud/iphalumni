<?php
/**
 * Admin Story Preview View
 * Variables: $story
 */
?>
<div class="max-w-4xl mx-auto py-6 font-['Kalpurush']">
  <div class="flex items-center justify-between gap-4 mb-6">
    <a href="<?= url('/admin/stories') ?>" class="px-4 py-2 rounded-xl bg-white/10 text-white text-[13px] hover:bg-white/20 transition-all">
      ← Back to Stories
    </a>

    <div class="flex items-center gap-3">
      <?php if ($story['status'] === 'pending'): ?>
      <form method="POST" action="<?= url('/admin/stories/' . $story['id'] . '/approve') ?>" onsubmit="return confirm('অনুমোদন করলে এটি লাইভ সাইটে প্রকাশিত হবে এবং সকল অ্যালামনাই সদস্যকে ইমেইল নোটিফিকেশন অ্যালার্ট পাঠানো হবে। আপনি কি নিশ্চিত?')">
        <?= csrf_field() ?>
        <button type="submit" class="px-5 py-2.5 rounded-xl bg-emerald-600 text-white font-semibold text-[13px] hover:bg-emerald-500 shadow-lg flex items-center gap-2">
          ✓ Approve & Alert Alumni
        </button>
      </form>
      <form method="POST" action="<?= url('/admin/stories/' . $story['id'] . '/reject') ?>">
        <?= csrf_field() ?>
        <button type="submit" class="px-4 py-2.5 rounded-xl bg-rose-950 text-rose-300 border border-rose-800 font-semibold text-[13px] hover:bg-rose-900">
          ✕ Reject
        </button>
      </form>
      <?php endif; ?>
      <form method="POST" action="<?= url('/admin/stories/' . $story['id'] . '/toggle-featured') ?>">
        <?= csrf_field() ?>
        <button type="submit" class="px-4 py-2.5 rounded-xl font-semibold text-[13px] transition-all <?= $story['is_featured'] ? 'bg-amber-500/20 text-amber-300 border border-amber-500/40 hover:bg-amber-500/30' : 'bg-white/10 text-white hover:bg-white/20' ?>">
          <?= $story['is_featured'] ? '★ Featured Story' : '☆ Make Featured' ?>
        </button>
      </form>
      <a href="<?= url('/admin/stories/' . $story['id'] . '/edit') ?>" class="px-4 py-2.5 rounded-xl bg-white/10 text-white text-[13px]">
        Edit Story
      </a>
    </div>
  </div>

  <!-- Preview Card -->
  <div class="rounded-3xl bg-white/5 border border-white/10 overflow-hidden shadow-2xl p-8 space-y-6">
    <div class="flex flex-wrap items-center justify-between gap-3 border-b border-white/10 pb-4">
      <div>
        <span class="text-[12px] font-mono text-[#E58E97] uppercase tracking-wider block">Author: <?= e($story['author_name'] ?? 'Admin') ?> (<?= e($story['author_email'] ?? 'N/A') ?>)</span>
        <h1 class="font-serif text-[28px] font-bold text-white mt-1"><?= e($story['title']) ?></h1>
      </div>
      <div class="flex items-center gap-2">
        <?php if (!empty($story['batch_year']) && $story['batch_year'] !== '0000'): ?>
        <span class="px-3 py-1 rounded-full bg-white/10 text-rose-300 font-mono text-[12px]">
          Batch <?= e($story['batch_year']) ?>
        </span>
        <?php endif; ?>
        <span class="px-3 py-1 rounded-full text-[12px] font-mono <?= $story['status'] === 'published' ? 'bg-emerald-500/20 text-emerald-300 border border-emerald-500/30' : 'bg-amber-500/20 text-amber-300 border border-amber-500/30' ?>">
          <?= strtoupper($story['status']) ?>
        </span>
      </div>
    </div>

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
            $publicDir = dirname(__DIR__, 3) . '/public';
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
    <div class="rounded-2xl overflow-hidden max-h-[360px] bg-black/40">
      <img src="<?= $coverUrl ?>" alt="Cover" class="w-full h-full object-cover">
    </div>
    <?php endif; ?>

    <?php if (!empty($story['excerpt'])): ?>
    <div class="p-4 rounded-2xl bg-white/5 border border-white/10 text-[14.5px] italic text-white/80">
      "<?= e($story['excerpt']) ?>"
    </div>
    <?php endif; ?>

    <div class="text-white/90 text-[15px] leading-relaxed whitespace-pre-line font-normal">
      <?= nl2br(e($story['content'])) ?>
    </div>
  </div>
</div>
