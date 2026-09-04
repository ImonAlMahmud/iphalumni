<?php
/**
 * Alumni Portal — Write Blog Post Form
 */
?>
<div class="max-w-4xl mx-auto px-4 py-8">
  <div class="mb-6 flex items-center justify-between">
    <div>
      <span class="font-mono text-[11px] tracking-widest text-[#800020] uppercase block mb-1"><?= __('নতুন নিবন্ধ জমা দিন', 'SUBMIT NEW BLOG STORY') ?></span>
      <h1 class="font-serif text-[28px] font-bold text-[#101820]"><?= __('ব্লগ পোস্ট লিখুন', 'Write a Blog Post') ?></h1>
    </div>
    <a href="<?= url('/portal/stories') ?>" class="text-[13px] font-medium text-[#6B7178] hover:text-[#101820]">
      ← <?= __('আমার ব্লগে ফিরে যান', 'Back to My Blogs') ?>
    </a>
  </div>

  <div class="bg-white p-8 rounded-3xl border border-gray-100 shadow-sm">
    <form action="<?= url('/portal/stories') ?>" method="POST" enctype="multipart/form-data" class="space-y-6">
      <?= csrf_field() ?>

      <div>
        <label class="block text-[13.5px] font-semibold text-[#101820] mb-2"><?= __('ব্লগের শিরোনাম', 'Blog Title') ?> <span class="text-rose-500">*</span></label>
        <input type="text" name="title" required placeholder="<?= __('যেমন: জনস্বাস্থ্য গবেষণায় আমাদের অভিজ্ঞতা...', 'e.g. My Journey in Public Health Research...') ?>"
               class="w-full px-4 py-3 rounded-xl border border-gray-200 text-[14px] focus:outline-none focus:ring-2 focus:ring-[#800020]/40">
      </div>

      <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
        <div>
          <label class="block text-[13.5px] font-semibold text-[#101820] mb-2"><?= __('সম্পর্কিত ব্যাচ (Associated Batch)', 'Associated Batch') ?></label>
          <select name="batch_year" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-[14px] focus:outline-none focus:ring-2 focus:ring-[#800020]/40">
            <option value="">-- ব্যাচ নির্বাচন করুন (Select Batch) --</option>
            <optgroup label="L-Series (L1 to L9)">
              <?php for ($i = 1; $i <= 9; $i++): $b = 'L-' . $i; ?>
                <option value="<?= $b ?>" <?= (($profile['batch_year'] ?? '') === $b) ? 'selected' : '' ?>><?= $b ?></option>
              <?php endfor; ?>
            </optgroup>
            <optgroup label="F-Series (F1 to F5)">
              <?php for ($i = 1; $i <= 5; $i++): $b = 'F-' . $i; ?>
                <option value="<?= $b ?>" <?= (($profile['batch_year'] ?? '') === $b) ? 'selected' : '' ?>><?= $b ?></option>
              <?php endfor; ?>
            </optgroup>
          </select>
        </div>

        <div>
          <label class="block text-[13.5px] font-semibold text-[#101820] mb-2"><?= __('কভার ছবি (ঐচ্ছিক)', 'Cover Image (Optional)') ?></label>
          <input type="file" name="cover_image" accept="image/*"
                 class="w-full px-3 py-2 text-[13px] border border-gray-200 rounded-xl focus:outline-none">
        </div>
      </div>

      <div>
        <label class="block text-[13.5px] font-semibold text-[#101820] mb-2"><?= __('সংক্ষিপ্ত সারাংশ (Excerpt)', 'Short Summary / Excerpt') ?></label>
        <textarea name="excerpt" rows="2" placeholder="<?= __('ব্লগের ১-২ লাইনের সংক্ষিপ্ত ধারণা...', 'Brief 1-2 sentence overview of the article...') ?>"
                  class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-[14px] focus:outline-none focus:ring-2 focus:ring-[#800020]/40"></textarea>
      </div>

      <div>
        <label class="block text-[13.5px] font-semibold text-[#101820] mb-2"><?= __('মূল বিষয়বস্তু / বিস্তারিত বিবরণ', 'Full Content / Article Detail') ?> <span class="text-rose-500">*</span></label>
        <textarea name="content" rows="10" required placeholder="<?= __('এখানে আপনার পুরো ব্লগ পোস্ট বা অভিজ্ঞতার কথা লিখুন...', 'Write your full story or article detail here...') ?>"
                  class="w-full px-4 py-3 rounded-xl border border-gray-200 text-[14px] leading-relaxed focus:outline-none focus:ring-2 focus:ring-[#800020]/40"></textarea>
      </div>

      <div class="p-4 rounded-2xl bg-amber-50 border border-amber-200 text-[12.5px] text-amber-800 flex items-start gap-3">
        <span class="text-[18px]">ℹ️</span>
        <div>
          <strong><?= __('এডমিন পর্যবেক্ষণ সংক্রান্ত নোটিশ:', 'Admin Review Notice:') ?></strong>
          <?= __('আপনার জমা দেওয়া ব্লগ পোস্টটি সিস্টেম এডমিন পর্যবেক্ষণ করে অনুমোদন (Approve) করার পরপরই পাবলিক ওয়েবসাইটে প্রকাশিত হবে এবং সকল সদস্যের কাছে ইমেইল নোটিফিকেশন অ্যালার্ট চলে যাবে।', 'Once submitted, your post will be reviewed by an administrator. Upon approval, it will be published live and all alumni members will automatically receive an email alert.') ?>
        </div>
      </div>

      <div class="flex justify-end gap-4 pt-4 border-t border-gray-100">
        <a href="<?= url('/portal/stories') ?>" class="px-6 py-2.5 rounded-xl text-[14px] font-semibold text-gray-600 bg-gray-100 hover:bg-gray-200 transition-all">
          <?= __('বাতিল করুন', 'Cancel') ?>
        </a>
        <button type="submit" class="px-7 py-2.5 rounded-xl text-[14px] font-semibold text-white transition-all shadow-md hover:-translate-y-0.5"
                style="background: linear-gradient(135deg, #A22638, #800020);">
          🚀 <?= __('ব্লগ জমা দিন', 'Submit Blog Post') ?>
        </button>
      </div>
    </form>
  </div>
</div>
