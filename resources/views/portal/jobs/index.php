<?php
/**
 * Member Portal Jobs Overview & Manage Postings
 * Variables: $myJobs
 */
?>
<!-- Header -->
<div class="flex items-center justify-between gap-4 mb-8">
  <div>
    <h1 class="font-serif text-[26px] font-semibold text-[#101820]">Job Circular Management</h1>
    <p class="text-[14px] text-[#6B7178] mt-1">Post new job openings and manage applications for your circulars.</p>
  </div>
  <a href="<?= url('/portal/jobs/create') ?>"
     class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl font-semibold text-white text-[13.5px] shadow-md transition-all hover:-translate-y-0.5"
     style="background:linear-gradient(135deg,#A22638,#800020);">
    <i class="fa-solid fa-plus text-[12px]"></i>
    Post New Job
  </a>
</div>

<!-- My Posted Jobs Table -->
<div class="p-6 rounded-2xl" style="background:rgba(255,255,255,0.85);border:1px solid rgba(16,24,32,0.08);box-shadow:0 2px 12px -4px rgba(16,24,32,0.08);">
  <h3 class="font-serif text-[18px] font-semibold text-[#101820] mb-5 pb-3 border-b border-gray-100 flex items-center justify-between">
    <span>My Job Postings</span>
    <span class="text-[12px] font-mono text-[#9CA3AF] font-normal"><?= count($myJobs) ?> Total Circulars</span>
  </h3>

  <?php if (empty($myJobs)): ?>
  <div class="py-12 text-center text-[#9CA3AF] text-[14px]">
    You haven't posted any job circulars yet.
    <div class="mt-3">
      <a href="<?= url('/portal/jobs/create') ?>" class="text-[#800020] font-semibold hover:underline">+ Create your first job posting</a>
    </div>
  </div>
  <?php else: ?>
  <div class="overflow-x-auto">
    <table class="w-full text-left text-[13.5px]">
      <thead>
        <tr class="border-b border-gray-100 text-[11px] font-mono text-[#9CA3AF] uppercase">
          <th class="py-3 px-4">Job Title & Company</th>
          <th class="py-3 px-4">Visibility</th>
          <th class="py-3 px-4">Deadline</th>
          <th class="py-3 px-4">Status</th>
          <th class="py-3 px-4 text-right">Actions</th>
        </tr>
      </thead>
      <tbody class="divide-y divide-gray-100">
        <?php foreach ($myJobs as $j): ?>
        <tr class="hover:bg-gray-50/50 transition-colors">
          <td class="py-4 px-4">
            <a href="<?= url('/jobs/' . $j['id']) ?>" class="font-semibold text-[#101820] hover:text-[#800020] transition-colors block">
              <?= e($j['title']) ?>
            </a>
            <div class="text-[12px] text-[#6B7178]"><?= e($j['company_name']) ?> &bull; <span class="text-[#800020]"><?= e($j['job_type']) ?></span></div>
          </td>

          <td class="py-4 px-4">
            <?php if ($j['visibility'] === 'public'): ?>
            <span class="inline-flex items-center gap-1 font-mono text-[10.5px] text-[#2F8863] px-2.5 py-0.5 rounded-full border border-[#2F8863]/30 bg-[#2F8863]/10">
              🌐 Public (Student)
            </span>
            <?php else: ?>
            <span class="inline-flex items-center gap-1 font-mono text-[10.5px] text-[#800020] px-2.5 py-0.5 rounded-full border border-[#800020]/30 bg-[#800020]/10">
              🔒 Members Only
            </span>
            <?php endif; ?>
          </td>

          <td class="py-4 px-4 text-[12.5px] font-mono text-[#6B7178]">
            <?= !empty($j['deadline']) ? date('d M, Y', strtotime($j['deadline'])) : 'N/A' ?>
          </td>

          <td class="py-4 px-4">
            <span class="inline-flex items-center gap-1.5 font-mono text-[11px] font-semibold px-2.5 py-0.5 rounded-full <?= $j['status']==='active'?'bg-emerald-100 text-emerald-800':'bg-gray-100 text-gray-600' ?>">
              <span class="w-1.5 h-1.5 rounded-full <?= $j['status']==='active'?'bg-emerald-600':'bg-gray-400' ?>"></span>
              <?= ucfirst($j['status']) ?>
            </span>
          </td>

          <td class="py-4 px-4 text-right space-x-2">
            <a href="<?= url('/portal/jobs/' . $j['id'] . '/edit') ?>"
               class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg text-[12px] font-semibold text-[#101820] bg-gray-100 hover:bg-gray-200 transition-colors">
              <i class="fa-solid fa-[#101820] fa-pen-to-square text-[11px]"></i>
              Edit
            </a>

            <a href="<?= url('/portal/jobs/' . $j['id'] . '/applications') ?>"
               class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg text-[12px] font-semibold text-[#800020] bg-[#800020]/10 hover:bg-[#800020]/20 transition-colors">
              <i class="fa-solid fa-users text-[11px]"></i>
              Applications
            </a>

            <form method="POST" action="<?= url('/portal/jobs/toggle-status') ?>" class="inline">
              <input type="hidden" name="job_id" value="<?= $j['id'] ?>">
              <button type="submit" class="px-3 py-1.5 rounded-lg text-[12px] font-medium text-[#6B7178] border border-gray-200 hover:bg-gray-100 transition-colors">
                <?= $j['status'] === 'active' ? 'Close Job' : 'Reopen' ?>
              </button>
            </form>
          </td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
  <?php endif; ?>
</div>
