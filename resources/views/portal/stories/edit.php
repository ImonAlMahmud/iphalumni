<?php
/**
 * Alumni Portal — Edit Blog View
 * Variables: $story
 */
?>
<div class="w-full max-w-3xl mx-auto space-y-6">
  <div class="flex justify-between items-center mb-6">
    <div>
      <span class="font-mono text-[11px] tracking-widest text-[#800020] uppercase block mb-1"><?= __('ব্লগ সম্পাদনা', 'EDIT BLOG POST') ?></span>
      <h1 class="font-serif text-[26px] font-bold text-[#101820]"><?= __('পোস্ট সংশোধন করুন', 'Modify Your Blog Post') ?></h1>
    </div>
    <a href="<?= url('/portal/stories') ?>" class="px-4 py-2 rounded-xl text-[13.5px] font-medium text-gray-600 bg-white border border-gray-200 hover:bg-gray-50 transition-all">
      ← <?= __('ফিরে যান', 'Back to Blogs') ?>
    </a>
  </div>

  <form method="POST" action="<?= url('/portal/stories/' . $story['id'] . '/update') ?>" enctype="multipart/form-data" class="bg-white p-8 rounded-3xl border border-gray-100 shadow-sm space-y-6">
    <?= csrf_field() ?>

    <div>
      <label class="block text-[13.5px] font-semibold text-[#101820] mb-2"><?= __('ব্লগ শিরোনাম', 'Blog Title') ?> *</label>
      <input type="text" name="title" value="<?= e($story['title']) ?>" required
             class="w-full px-4 py-3 rounded-xl border border-gray-200 text-[14.5px] focus:outline-none focus:ring-2 focus:ring-[#800020]/30">
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
      <div>
        <label class="block text-[13.5px] font-semibold text-[#101820] mb-2"><?= __('সম্পর্কিত ব্যাচ (Associated Batch)', 'Associated Batch') ?></label>
        <select name="batch_year" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-[14px] focus:outline-none focus:ring-2 focus:ring-[#800020]/30">
          <option value="">-- ব্যাচ নির্বাচন করুন (Select Batch) --</option>
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
      <div>
        <label class="block text-[13.5px] font-semibold text-[#101820] mb-2"><?= __('কভার ছবি পরিক্যাপ (Cover Image)', 'Cover Image') ?></label>
        <input type="file" name="cover_image" accept="image/*" class="w-full text-[13px] text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-[12px] file:font-semibold file:bg-[#800020]/10 file:text-[#800020]">
      </div>
    </div>

    <div>
      <label class="block text-[13.5px] font-semibold text-[#101820] mb-2"><?= __('সংক্ষিপ্ত সারাংশ (Excerpt)', 'Short Summary') ?></label>
      <textarea name="excerpt" rows="2" class="w-full p-4 rounded-xl border border-gray-200 text-[14px] focus:outline-none focus:ring-2 focus:ring-[#800020]/30"><?= e($story['excerpt']) ?></textarea>
    </div>

    <div>
      <label class="block text-[13.5px] font-semibold text-[#101820] mb-2"><?= __('বিস্তারিত বিষয়বস্তু / পোস্ট', 'Full Article Content') ?> *</label>
      <textarea name="content" rows="10" required class="w-full p-4 rounded-xl border border-gray-200 text-[14.5px] focus:outline-none focus:ring-2 focus:ring-[#800020]/30 leading-relaxed"><?= e($story['content']) ?></textarea>
    </div>

    <div class="flex justify-end gap-3 pt-4 border-t border-gray-100">
      <a href="<?= url('/portal/stories') ?>" class="px-5 py-2.5 rounded-xl text-[13.5px] text-gray-500 hover:bg-gray-100">Cancel</a>
      <button type="submit" class="px-6 py-2.5 rounded-xl text-[14px] font-semibold text-white bg-[#800020] hover:bg-[#66001a] transition-all shadow-md">
        ✏️ <?= __('সংরক্ষণ করুন', 'Save Changes') ?>
      </button>
    </div>
  </form>
</div>
