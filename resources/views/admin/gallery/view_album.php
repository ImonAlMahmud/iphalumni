<?php
/**
 * Admin Gallery View Album (Add Photos) View
 * Variables: $album, $photos
 */
?>
<div class="mb-6">
  <a href="<?= url('/admin/gallery') ?>" class="text-[13px] text-white/50 hover:text-white inline-flex items-center gap-1">
    ← Back to Gallery Management
  </a>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
  <div class="lg:col-span-2 p-6 rounded-3xl" style="background:rgba(255,255,255,0.04);border:1px solid rgba(255,255,255,0.08);">
    <h3 class="text-[18px] font-semibold text-white mb-2"><?= e($album['title']) ?></h3>
    <p class="text-[13px] text-white/50"><?= e($album['description']) ?></p>
    <div class="text-[12.5px] mt-2 font-mono text-white/40">Date: <?= date('d M Y', strtotime($album['album_date'])) ?></div>
  </div>

  <div class="p-6 rounded-3xl" style="background:rgba(255,255,255,0.04);border:1px solid rgba(255,255,255,0.08);">
    <h4 class="text-[14px] font-semibold text-white mb-3">Upload Photos</h4>
    <form method="POST" action="<?= url('/admin/gallery/' . $album['id'] . '/photos') ?>" enctype="multipart/form-data" class="space-y-3">
      <?= csrf_field() ?>
      <input type="file" name="photos[]" multiple required
             class="w-full text-[13px] text-white/50 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-[13px] file:font-semibold file:bg-white/10 file:text-white hover:file:bg-white/20">
      <button type="submit" class="w-full py-2 rounded-xl text-[13px] font-semibold text-white"
              style="background:linear-gradient(135deg,#A22638,#800020);">Upload</button>
    </form>
  </div>
</div>

<!-- Photos Grid -->
<div class="p-6 rounded-3xl" style="background:rgba(255,255,255,0.04);border:1px solid rgba(255,255,255,0.08);">
  <h4 class="text-[14px] font-semibold text-white mb-4">Uploaded Photos (<?= count($photos) ?>)</h4>
  <?php if (empty($photos)): ?>
  <p class="text-[13px] text-white/40 text-center py-8">No photos uploaded to this album yet.</p>
  <?php else: ?>
  <div class="grid grid-cols-2 sm:grid-cols-4 lg:grid-cols-6 gap-3">
    <?php foreach ($photos as $photo): ?>
    <div class="aspect-square rounded-xl overflow-hidden bg-black/20 relative group">
      <img src="<?= asset('storage/gallery/' . $album['id'] . '/' . e($photo['filename'])) ?>" alt="" class="w-full h-full object-cover">
      <form method="POST" action="<?= url('/admin/gallery/photos/' . $photo['id'] . '/delete') ?>"
            class="absolute top-2 right-2 opacity-0 group-hover:opacity-100 transition-opacity"
            onsubmit="return confirm('Are you sure you want to delete this photo?')">
        <?= csrf_field() ?>
        <button type="submit" class="w-6 h-6 bg-red-600/90 hover:bg-red-600 text-white rounded-full flex items-center justify-center text-[11px] shadow-md transition-colors">
          ✕
        </button>
      </form>
    </div>
    <?php endforeach; ?>
  </div>
  <?php endif; ?>
</div>
