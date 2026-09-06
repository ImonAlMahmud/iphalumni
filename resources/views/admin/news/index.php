<?php
/**
 * Admin News Index View
 * Variables: $news
 */
?>
<div class="flex items-center justify-between mb-6">
  <div class="text-[15px] font-semibold text-white">All News, Press Releases & Official Notices</div>
  <a href="<?= url('/admin/news/create') ?>" class="px-5 py-2.5 rounded-xl text-[13px] font-semibold text-white transition-all hover:scale-105"
     style="background:linear-gradient(135deg,#A22638,#800020);">+ Create Press Release / Notice / News</a>
</div>

<div class="rounded-2xl overflow-hidden" style="background:rgba(255,255,255,0.04);border:1px solid rgba(255,255,255,0.08);">
  <table class="w-full text-[13px]">
    <thead>
      <tr class="border-b border-white/5">
        <th class="text-left px-5 py-3.5 text-white/35 font-mono text-[11px]">Title</th>
        <th class="text-left px-5 py-3.5 text-white/35 font-mono text-[11px]">Category</th>
        <th class="text-left px-5 py-3.5 text-white/35 font-mono text-[11px]">Attachment</th>
        <th class="text-left px-5 py-3.5 text-white/35 font-mono text-[11px]">Status</th>
        <th class="text-left px-5 py-3.5 text-white/35 font-mono text-[11px]">Published At</th>
        <th class="text-left px-5 py-3.5 text-white/35 font-mono text-[11px]">Actions</th>
      </tr>
    </thead>
    <tbody class="divide-y divide-white/5">
      <?php if (empty($news)): ?>
      <tr><td colspan="6" class="px-5 py-8 text-center text-white/40">No news, press releases or notices found.</td></tr>
      <?php else: ?>
      <?php foreach ($news as $n):
        $cat = $n['category'] ?? 'news';
        $catBadges = [
          'news' => ['General News', 'rgba(99,102,241,0.15)', '#818cf8', 'fa-solid fa-newspaper'],
          'press_release' => ['Press Release', 'rgba(234,179,8,0.15)', '#fde047', 'fa-solid fa-bullhorn'],
          'notice' => ['Official Notice', 'rgba(239,68,68,0.15)', '#f87171', 'fa-solid fa-thumbtack'],
          'resolution' => ['Meeting Resolution', 'rgba(78,156,129,0.15)', '#6ee7b7', 'fa-solid fa-scroll'],
        ];
        $b = $catBadges[$cat] ?? $catBadges['news'];
      ?>
      <tr>
        <td class="px-5 py-3.5 font-medium text-white max-w-xs truncate"><?= e($n['title']) ?></td>
        <td class="px-5 py-3.5">
          <span class="px-2.5 py-1 rounded-lg text-[11px] font-medium inline-flex items-center gap-1.5"
                style="background:<?= $b[1] ?>;color:<?= $b[2] ?>;">
            <i class="<?= $b[3] ?> text-[10px]"></i> <span><?= $b[0] ?></span>
          </span>
        </td>
        <td class="px-5 py-3.5">
          <?php if (!empty($n['attachment_file'])): ?>
          <a href="<?= asset('storage/news/' . e($n['attachment_file'])) ?>" target="_blank" class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-[11px] bg-white/10 text-white/80 hover:text-white border border-white/10">
            <i class="fa-solid fa-paperclip text-[10px]"></i> View Doc
          </a>
          <?php else: ?>
          <span class="text-white/30 text-[11px]">—</span>
          <?php endif; ?>
        </td>
        <td class="px-5 py-3.5">
          <span class="px-2.5 py-0.5 rounded-full text-[10.5px] font-mono"
                style="background:<?= $n['status'] === 'published' ? 'rgba(78,156,129,0.2)' : 'rgba(255,255,255,0.05)' ?>;color:<?= $n['status'] === 'published' ? '#4E9C81' : 'rgba(255,255,255,0.4)' ?>;">
            <?= strtoupper($n['status']) ?>
          </span>
        </td>
        <td class="px-5 py-3.5 text-white/50"><?= $n['published_at'] ? date('d M Y H:i', strtotime($n['published_at'])) : '—' ?></td>
        <td class="px-5 py-3.5 space-x-2">
          <a href="<?= url('/news/' . e($n['slug'] ?: $n['id'])) ?>" target="_blank" class="text-white/70 hover:text-white inline-flex items-center gap-1">
            <i class="fa-solid fa-eye text-[10px]"></i> View
          </a>
          <a href="<?= url('/admin/news/' . $n['id'] . '/edit') ?>" class="text-[#E58E97] hover:underline inline-flex items-center gap-1">
            <i class="fa-solid fa-pen text-[10px]"></i> Edit
          </a>
          <form method="POST" action="<?= url('/admin/news/' . $n['id'] . '/delete') ?>" class="inline" onsubmit="return confirm('Are you sure you want to delete this publication?')">
            <?= csrf_field() ?>
            <button type="submit" class="text-red-400 hover:underline inline-flex items-center gap-1">
              <i class="fa-solid fa-trash-can text-[10px]"></i> Delete
            </button>
          </form>
        </td>
      </tr>
      <?php endforeach; ?>
      <?php endif; ?>
    </tbody>
  </table>
</div>
