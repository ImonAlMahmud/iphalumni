<?php
/**
 * Admin Gallery Create Album Form View
 */
?>
<div class="mb-6">
  <a href="<?= url('/admin/gallery') ?>" class="text-[13px] text-white/50 hover:text-white inline-flex items-center gap-1">
    ← Back to Gallery Management
  </a>
</div>

<div class="p-8 rounded-3xl" style="background:rgba(255,255,255,0.04);border:1px solid rgba(255,255,255,0.08);">
  <form method="POST" action="<?= url('/admin/gallery') ?>" class="space-y-5">
    <?= csrf_field() ?>

    <div>
      <label class="block text-[13px] font-medium text-white/70 mb-1.5" for="title">Album Title</label>
      <input id="title" type="text" name="title" required
             class="w-full px-4 py-2.5 rounded-xl text-[14px] text-white focus:outline-none"
             style="background:rgba(255,255,255,0.06);border:1px solid rgba(255,255,255,0.12);"
             placeholder="Reunion 2025">
    </div>

    <div>
      <label class="block text-[13px] font-medium text-white/70 mb-1.5" for="description">Description</label>
      <textarea id="description" name="description" rows="3"
                class="w-full px-4 py-2.5 rounded-xl text-[14px] text-white focus:outline-none"
                style="background:rgba(255,255,255,0.06);border:1px solid rgba(255,255,255,0.12);"
                placeholder="Brief details about the album..."></textarea>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
      <div>
        <label class="block text-[13px] font-medium text-white/70 mb-1.5" for="album_date">Album Date</label>
        <input id="album_date" type="date" name="album_date" required
               class="w-full px-4 py-2.5 rounded-xl text-[14px] text-white focus:outline-none"
               style="background:rgba(255,255,255,0.06);border:1px solid rgba(255,255,255,0.12);">
      </div>
      <div>
        <label class="block text-[13px] font-medium text-white/70 mb-1.5" for="status">Status</label>
        <select id="status" name="status" class="w-full px-4 py-2.5 rounded-xl text-[14px] text-white focus:outline-none"
                style="background:rgba(255,255,255,0.06);border:1px solid rgba(255,255,255,0.12);">
          <option value="draft">Draft</option>
          <option value="published">Published</option>
        </select>
      </div>
    </div>

    <button type="submit" class="px-6 py-2.5 rounded-xl text-[14px] font-semibold text-white"
            style="background:linear-gradient(135deg,#A22638,#800020);">Create Album</button>
  </form>
</div>
