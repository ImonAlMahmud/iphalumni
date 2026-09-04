<?php
/**
 * Admin News Create/Edit Form View
 * Variables: $news (null for create)
 */
$action = $news ? url('/admin/news/' . $news['id']) : url('/admin/news');
?>
<div class="mb-6">
  <a href="<?= url('/admin/news') ?>" class="text-[13px] text-white/50 hover:text-white inline-flex items-center gap-1">
    ← Back to News Management
  </a>
</div>

<div class="p-8 rounded-3xl" style="background:rgba(255,255,255,0.04);border:1px solid rgba(255,255,255,0.08);">
  <form method="POST" action="<?= $action ?>" enctype="multipart/form-data" class="space-y-5">
    <?= csrf_field() ?>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
      <div class="md:col-span-2">
        <label class="block text-[13px] font-medium text-white/70 mb-1.5" for="title">Title / Heading <span class="text-red-400">*</span></label>
        <input id="title" type="text" name="title" value="<?= e($news['title'] ?? '') ?>" required
               class="w-full px-4 py-2.5 rounded-xl text-[14px] text-white focus:outline-none"
               style="background:rgba(255,255,255,0.06);border:1px solid rgba(255,255,255,0.12);"
               placeholder="Enter title (e.g. Press Release on Annual Convocation / Emergency Notice)">
      </div>

      <div>
        <label class="block text-[13px] font-medium text-white/70 mb-1.5" for="category">Category (প্রকার) <span class="text-red-400">*</span></label>
        <select id="category" name="category" required class="w-full px-4 py-2.5 rounded-xl text-[14px] text-white focus:outline-none bg-[#101820]"
                style="background:rgba(255,255,255,0.06);border:1px solid rgba(255,255,255,0.12);">
          <option value="news" <?= ($news['category'] ?? 'news') === 'news' ? 'selected' : '' ?>>📰 General News & Articles (সাধারণ খবর)</option>
          <option value="press_release" <?= ($news['category'] ?? '') === 'press_release' ? 'selected' : '' ?>>📣 Press Release (প্রেস বিজ্ঞপ্তি)</option>
          <option value="notice" <?= ($news['category'] ?? '') === 'notice' ? 'selected' : '' ?>>📌 Official Notice (অফিসিয়াল নোটিশ)</option>
          <option value="resolution" <?= ($news['category'] ?? '') === 'resolution' ? 'selected' : '' ?>>📜 Meeting Resolution (মিটিং রেজুলেশন)</option>
        </select>
      </div>
    </div>

    <div>
      <label class="block text-[13px] font-medium text-white/70 mb-1.5" for="content">Content / Description <span class="text-red-400">*</span></label>
      <textarea id="content" name="content" rows="10" required
                class="w-full px-4 py-2.5 rounded-xl text-[14px] text-white focus:outline-none"
                style="background:rgba(255,255,255,0.06);border:1px solid rgba(255,255,255,0.12);"
                placeholder="Write news content or meeting resolution details..."><?= e($news['content'] ?? '') ?></textarea>
    </div>

    <!-- Official Document Attachment -->
    <div class="p-5 rounded-2xl border border-white/10 space-y-2" style="background:rgba(255,255,255,0.02);">
      <label class="block text-[13.5px] font-semibold text-amber-300">
        <i class="fa-solid fa-file-pdf mr-1"></i> Attachment File / Official Document (সংযুক্ত অফিসিয়াল ফাইল - Optional)
      </label>
      <?php if (!empty($news['attachment_file'])): ?>
      <div class="mb-2 text-[12px] text-emerald-400 font-mono flex items-center gap-2">
        <i class="fa-solid fa-paperclip"></i> Current Attachment: 
        <a href="<?= asset('storage/news/' . e($news['attachment_file'])) ?>" target="_blank" class="underline hover:text-white">
          <?= e($news['attachment_file']) ?>
        </a>
      </div>
      <?php endif; ?>
      <input type="file" name="attachment_file" accept=".pdf,.doc,.docx,.jpg,.png"
             class="w-full px-4 py-2 rounded-xl text-[13px] text-white/80 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-[12px] file:font-semibold file:bg-white/10 file:text-white hover:file:bg-white/20"
             style="background:rgba(255,255,255,0.06);border:1px solid rgba(255,255,255,0.12);">
      <span class="text-[11.5px] text-white/40 block">Attach official signed Press Release PDF, scanned Notice, or Meeting Resolution document.</span>
    </div>

    <!-- Official Signatories Section (Max 4 Signatories) -->
    <?php
      $pdoSign = \App\Services\Database::connection();
      $committeeMembers = $pdoSign->query(
          "SELECT cm.user_id, cm.designation, u.name, u.signature_image 
           FROM committee_members cm 
           JOIN users u ON u.id = cm.user_id 
           WHERE cm.is_active = 1 AND cm.deleted_at IS NULL 
           ORDER BY cm.sort_order ASC, u.name ASC"
      )->fetchAll();

      $existingSignatories = [];
      if (!empty($news['id'])) {
          $stmtExist = $pdoSign->prepare("SELECT user_id, designation_title FROM notice_signatories WHERE news_id = ? ORDER BY sort_order ASC");
          $stmtExist->execute([$news['id']]);
          $existingSignatories = $stmtExist->fetchAll();
      }
    ?>
    <div class="p-6 rounded-2xl border border-white/10 space-y-4" style="background:rgba(255,255,255,0.02);">
      <div>
        <h4 class="text-[14px] font-bold text-emerald-400 flex items-center gap-2">
          ✍️ Notice Signatories / অনুমোদনকারী কমিটি মেম্বারবৃন্দ (সর্বোচ্চ ৪ জন)
        </h4>
        <p class="text-[12px] text-white/50 mt-0.5">
          অফিসিয়াল নোটিশ বা রেজুলেশনের নিচে যে কমিটি মেম্বারদের স্বাক্ষর, নাম ও পদবী যুক্ত থাকবে তা নির্বাচন করুন।
        </p>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <?php for ($i = 0; $i < 4; $i++): 
          $selectedUid = $existingSignatories[$i]['user_id'] ?? '';
          $selectedTitle = $existingSignatories[$i]['designation_title'] ?? '';
        ?>
        <div class="p-4 rounded-xl border border-white/10 space-y-2.5" style="background:rgba(0,0,0,0.3);">
          <div class="text-[12px] font-mono text-amber-300 font-bold">Signatory #<?= $i + 1 ?></div>
          
          <div>
            <label class="block text-[11.5px] text-white/60 mb-1">Select Member</label>
            <select name="signatory_user_id[]" class="w-full px-3 py-2 rounded-xl text-[13px] text-white bg-[#101820] border border-white/10 focus:outline-none">
              <option value="">-- None (কোনো স্বাক্ষরকারী নেই) --</option>
              <?php foreach ($committeeMembers as $cm): ?>
              <option value="<?= $cm['user_id'] ?>" <?= (int)$selectedUid === (int)$cm['user_id'] ? 'selected' : '' ?>>
                <?= e($cm['name']) ?> (<?= e($cm['designation']) ?>) <?= empty($cm['signature_image']) ? '⚠️ No Sign Uploaded' : '✓ Sign Ready' ?>
              </option>
              <?php endforeach; ?>
            </select>
          </div>

          <div>
            <label class="block text-[11.5px] text-white/60 mb-1">Custom Designation Title (Optional override)</label>
            <input type="text" name="signatory_title[]" value="<?= e($selectedTitle) ?>" 
                   placeholder="e.g. President / General Secretary"
                   class="w-full px-3 py-1.5 rounded-xl text-[12.5px] text-white bg-white/5 border border-white/10 focus:outline-none">
          </div>
        </div>
        <?php endfor; ?>
      </div>
    </div>

    <div>
      <label class="block text-[13px] font-medium text-white/70 mb-1.5" for="status">Publication Status</label>
      <select id="status" name="status" class="w-full sm:w-1/3 px-4 py-2.5 rounded-xl text-[14px] text-white focus:outline-none bg-[#101820]"
              style="background:rgba(255,255,255,0.06);border:1px solid rgba(255,255,255,0.12);">
        <option value="draft" <?= ($news['status'] ?? '') === 'draft' ? 'selected' : '' ?>>Draft</option>
        <option value="published" <?= ($news['status'] ?? '') === 'published' ? 'selected' : '' ?>>Published (প্রকাশিত)</option>
      </select>
    </div>

    <button type="submit" class="px-7 py-2.5 rounded-xl text-[14px] font-semibold text-white transition-all hover:scale-105"
            style="background:linear-gradient(135deg,#A22638,#800020);">Save Publication</button>
  </form>
</div>
