<?php
/**
 * Admin Story Create/Edit Form View
 * Variables: $story
 */
$isEdit = !empty($story);
$actionUrl = $isEdit ? url('/admin/stories/' . $story['id']) : url('/admin/stories');
?>
<div class="mb-6">
  <a href="<?= url('/admin/stories') ?>" class="text-[13px] text-white/50 hover:text-white inline-flex items-center gap-1">
    ← Back to Stories Management
  </a>
</div>

<div class="p-8 rounded-3xl" style="background:rgba(255,255,255,0.04);border:1px solid rgba(255,255,255,0.08);">
  <form method="POST" action="<?= $actionUrl ?>" enctype="multipart/form-data" class="space-y-5">
    <?= csrf_field() ?>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
      <div class="md:col-span-2">
        <label class="block text-[13px] font-medium text-white/70 mb-1.5" for="title">Story Title / Headline</label>
        <input id="title" type="text" name="title" value="<?= e($story['title'] ?? '') ?>" required
               class="w-full px-4 py-2.5 rounded-xl text-[14px] text-white focus:outline-none"
               style="background:rgba(255,255,255,0.06);border:1px solid rgba(255,255,255,0.12);"
               placeholder="e.g. Leading public health initiatives across Bangladesh">
      </div>
      <div>
        <label class="block text-[13px] font-medium text-white/70 mb-1.5" for="batch_year">Associated Batch</label>
        <select id="batch_year" name="batch_year"
                class="w-full px-4 py-2.5 rounded-xl text-[14px] text-white focus:outline-none bg-black/60 border border-white/10">
          <option value="">-- Select Batch --</option>
          <optgroup label="L-Series (L1 to L9)">
            <?php for ($i = 1; $i <= 9; $i++): $b = 'L-' . $i; ?>
              <option value="<?= $b ?>" <?= (($story['batch_year'] ?? '') === $b) ? 'selected' : '' ?>><?= $b ?></option>
            <?php endfor; ?>
          </optgroup>
          <optgroup label="F-Series (F1 to F5)">
            <?php for ($i = 1; $i <= 5; $i++): $b = 'F-' . $i; ?>
              <option value="<?= $b ?>" <?= (($story['batch_year'] ?? '') === $b) ? 'selected' : '' ?>><?= $b ?></option>
            <?php endfor; ?>
          </optgroup>
        </select>
      </div>
    </div>

    <div>
      <label class="block text-[13px] font-medium text-white/70 mb-1.5" for="excerpt">Excerpt / Short Summary</label>
      <textarea id="excerpt" name="excerpt" rows="2" required
                class="w-full px-4 py-2.5 rounded-xl text-[14px] text-white focus:outline-none"
                style="background:rgba(255,255,255,0.06);border:1px solid rgba(255,255,255,0.12);"
                placeholder="A brief teaser shown on the home page / story lists..."><?= e($story['excerpt'] ?? '') ?></textarea>
    </div>

    <div>
      <label class="block text-[13px] font-medium text-white/70 mb-1.5" for="content">Full Story Content</label>
      <textarea id="content" name="content" rows="10" required
                class="w-full px-4 py-2.5 rounded-xl text-[14px] text-white focus:outline-none font-sans"
                style="background:rgba(255,255,255,0.06);border:1px solid rgba(255,255,255,0.12);"
                placeholder="Write the full success story details here..."><?= e($story['content'] ?? '') ?></textarea>
    <div class="mb-4">
      <label class="block text-[13px] font-medium text-white/70 mb-1.5" for="cover_image">Cover Image (Optional)</label>
      <?php if ($isEdit && !empty($story['cover_image'])): ?>
      <div class="mb-3 flex items-center gap-3">
        <img src="<?= asset('storage/stories/' . e($story['cover_image'])) ?>" class="h-16 w-24 object-cover rounded-lg border border-white/10" alt="">
        <span class="text-[12px] text-white/40 font-mono"><?= e($story['cover_image']) ?></span>
      </div>
      <?php endif; ?>
      <input id="cover_image" type="file" name="cover_image" accept="image/*"
             class="w-full text-[13px] text-white/50 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-[13px] file:font-semibold file:bg-white/10 file:text-white hover:file:bg-white/20">
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
      <div>
        <label class="block text-[13px] font-medium text-white/70 mb-1.5" for="status">Publish Status</label>
        <select id="status" name="status" class="w-full px-4 py-2.5 rounded-xl text-[14px] text-white focus:outline-none"
                style="background:rgba(255,255,255,0.06);border:1px solid rgba(255,255,255,0.12);">
          <option value="pending" <?= ($story['status'] ?? '') === 'pending' ? 'selected' : '' ?>>Pending Review</option>
          <option value="draft" <?= ($story['status'] ?? '') === 'draft' ? 'selected' : '' ?>>Draft</option>
          <option value="published" <?= ($story['status'] ?? '') === 'published' ? 'selected' : '' ?>>Published</option>
          <option value="rejected" <?= ($story['status'] ?? '') === 'rejected' ? 'selected' : '' ?>>Rejected</option>
        </select>
      </div>

      <div>
        <label class="block text-[13px] font-medium text-white/70 mb-1.5" for="is_featured">Featured on Home Page</label>
        <select id="is_featured" name="is_featured" class="w-full px-4 py-2.5 rounded-xl text-[14px] text-white focus:outline-none"
                style="background:rgba(255,255,255,0.06);border:1px solid rgba(255,255,255,0.12);">
          <option value="0" <?= (int)($story['is_featured'] ?? 0) === 0 ? 'selected' : '' ?>>No (Normal List)</option>
          <option value="1" <?= (int)($story['is_featured'] ?? 0) === 1 ? 'selected' : '' ?>>Yes (Show on Homepage)</option>
        </select>
      </div>
    </div>

    <button type="submit" class="px-6 py-2.5 rounded-xl text-[14px] font-semibold text-white"
            style="background:linear-gradient(135deg,#A22638,#800020);">
      <?= $isEdit ? 'Update Story' : 'Save Story' ?>
    </button>
  </form>
</div>
