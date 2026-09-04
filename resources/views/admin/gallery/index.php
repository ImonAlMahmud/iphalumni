<?php
/**
 * Admin Gallery Albums Index View
 * Variables: $albums
 */
?>
<div class="flex justify-end mb-6">
  <a href="<?= url('/admin/gallery/create') ?>" class="px-4 py-2 rounded-xl text-[13px] font-semibold text-white"
     style="background:linear-gradient(135deg,#A22638,#800020);">+ Create Album</a>
</div>

<div class="rounded-2xl overflow-hidden" style="background:rgba(255,255,255,0.04);border:1px solid rgba(255,255,255,0.08);">
  <table class="w-full text-[13px]">
    <thead>
      <tr class="border-b border-white/5">
        <th class="text-left px-5 py-3.5 text-white/35 font-mono text-[11px]">Album Title</th>
        <th class="text-left px-5 py-3.5 text-white/35 font-mono text-[11px]">Photos Count</th>
        <th class="text-left px-5 py-3.5 text-white/35 font-mono text-[11px]">Date</th>
        <th class="text-left px-5 py-3.5 text-white/35 font-mono text-[11px]">Actions</th>
      </tr>
    </thead>
    <tbody class="divide-y divide-white/5">
      <?php if (empty($albums)): ?>
      <tr><td colspan="4" class="px-5 py-8 text-center text-white/40">No gallery albums created.</td></tr>
      <?php else: ?>
      <?php foreach ($albums as $album): ?>
      <tr>
        <td class="px-5 py-3.5 font-medium text-white"><?= e($album['title']) ?></td>
        <td class="px-5 py-3.5 text-white/70 font-mono"><?= $album['photo_count'] ?></td>
        <td class="px-5 py-3.5 text-white/50"><?= $album['album_date'] ? date('d M Y', strtotime($album['album_date'])) : '—' ?></td>
        <td class="px-5 py-3.5 space-x-3 flex items-center">
          <a href="<?= url('/admin/gallery/' . $album['id']) ?>" class="text-[#A22638] hover:underline">View & Add Photos</a>
          <a href="<?= url('/admin/gallery/' . $album['id'] . '/edit') ?>" class="text-blue-400 hover:underline">Edit</a>
          <form method="POST" action="<?= url('/admin/gallery/' . $album['id'] . '/delete') ?>" class="inline" onsubmit="return confirm('Are you sure you want to delete this album?')">
            <?= csrf_field() ?>
            <button type="submit" class="text-red-400 hover:underline">Delete</button>
          </form>
        </td>
      </tr>
      <?php endforeach; ?>
      <?php endif; ?>
    </tbody>
  </table>
</div>
