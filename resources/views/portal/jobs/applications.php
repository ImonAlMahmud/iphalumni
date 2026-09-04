<?php
/**
 * Applications List for a Job View
 * Variables: $job, $applications
 */
?>
<!-- Header -->
<div class="mb-8">
  <a href="<?= url('/portal/jobs') ?>" class="inline-flex items-center gap-1.5 text-[13px] font-medium text-[#6B7178] hover:text-[#800020] transition-colors mb-3">
    <i class="fa-solid fa-arrow-left text-[11px]"></i> Back to My Jobs
  </a>
  <h1 class="font-serif text-[26px] font-semibold text-[#101820]">Applications Received</h1>
  <p class="text-[14px] text-[#6B7178] mt-1">Review candidates who applied for <strong><?= e($job['title']) ?></strong> at <?= e($job['company_name']) ?>.</p>
</div>

<!-- Job Meta Bar -->
<div class="p-4 rounded-xl mb-6 flex items-center justify-between gap-4 text-[13px]"
     style="background:rgba(255,255,255,0.85);border:1px solid rgba(16,24,32,0.08);">
  <div class="flex items-center gap-3">
    <span class="font-mono text-[11px] font-semibold text-[#800020] px-2.5 py-0.5 rounded-full" style="background:rgba(128,0,32,0.08);">
      <?= e($job['job_type']) ?>
    </span>
    <span class="font-mono text-[11px] text-gray-500">
      Visibility: <?= ucfirst($job['visibility']) ?>
    </span>
  </div>
  <div class="font-mono text-[12px] text-[#2F8863] font-semibold">
    Total Applications: <?= count($applications) ?>
  </div>
</div>

<!-- Applications List -->
<div class="p-6 rounded-2xl" style="background:rgba(255,255,255,0.85);border:1px solid rgba(16,24,32,0.08);box-shadow:0 2px 12px -4px rgba(16,24,32,0.08);">
  <?php if (empty($applications)): ?>
  <div class="py-16 text-center text-[#9CA3AF] text-[14px]">
    No applications submitted yet for this job circular.
  </div>
  <?php else: ?>
  <div class="space-y-4">
    <?php foreach ($applications as $app): ?>
    <div class="p-5 rounded-xl border border-gray-100 bg-white/60 space-y-3">
      <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
        <div class="flex items-center gap-3">
          <div class="w-10 h-10 rounded-full flex items-center justify-center font-serif font-semibold text-[14px] text-white shrink-0"
               style="background:linear-gradient(135deg,#153548,#2F8863);">
            <?= initials($app['applicant_name']) ?>
          </div>
          <div>
            <div class="font-semibold text-[15px] text-[#101820]"><?= e($app['applicant_name']) ?></div>
            <div class="text-[12.5px] text-[#6B7178] flex items-center gap-3 flex-wrap">
              <span><i class="fa-solid fa-envelope mr-1 text-[#9CA3AF]"></i><?= e($app['applicant_email']) ?></span>
              <?php if (!empty($app['applicant_phone'])): ?>
              <span><i class="fa-solid fa-phone mr-1 text-[#9CA3AF]"></i><?= e($app['applicant_phone']) ?></span>
              <?php endif; ?>
            </div>
          </div>
        </div>

        <div class="flex items-center gap-3 shrink-0">
          <?php if (!empty($app['student_reference_id'])): ?>
          <span class="font-mono text-[10.5px] text-[#2F8863] px-2.5 py-1 rounded-full border border-[#2F8863]/30 bg-[#2F8863]/10">
            ✓ Verified Student
          </span>
          <?php endif; ?>

          <?php if (!empty($app['resume_path'])): ?>
          <a href="<?= asset($app['resume_path']) ?>" target="_blank"
             class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-lg text-[12.5px] font-semibold text-white transition-all shadow-sm"
             style="background:#800020;">
            <i class="fa-solid fa-file-pdf text-[12px]"></i> View Resume
          </a>
          <?php endif; ?>
        </div>
      </div>

      <?php if (!empty($app['cover_letter'])): ?>
      <div class="p-3 rounded-lg bg-gray-50 text-[13px] text-[#4A5568] whitespace-pre-line border border-gray-100">
        <strong class="text-[#101820] text-[12px] uppercase font-mono block mb-1">Cover Letter / Message:</strong>
        <?= e($app['cover_letter']) ?>
      </div>
      <?php endif; ?>

      <div class="text-[11px] text-[#9CA3AF] font-mono flex items-center justify-between pt-2 border-t border-gray-100">
        <span>Submitted on <?= date('d M, Y \a\t h:i A', strtotime($app['created_at'])) ?></span>
        <span class="text-[#2F8863] font-semibold">Status: <?= ucfirst($app['status']) ?></span>
      </div>
    </div>
    <?php endforeach; ?>
  </div>
  <?php endif; ?>
</div>
