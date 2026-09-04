<?php
/**
 * Admin Alumni Mapping View — Student Reference Database Mapping
 */
?>
<div class="max-w-7xl mx-auto py-6 font-['Kalpurush']">

  <!-- Header -->
  <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8">
    <div>
      <span class="font-mono text-[11px] font-bold text-[#E58E97] uppercase tracking-wider block mb-1">
        <i class="fa-solid fa-link mr-1"></i> VERIFICATION & DATA MATCHING
      </span>
      <h1 class="font-serif text-[28px] font-bold text-white"><?= __('অ্যালামনাই ও স্টুডেন্ট রেফারেন্স ম্যাপিং', 'Alumni Student Reference Mapping') ?></h1>
    </div>
    <div class="flex items-center gap-2">
      <a href="<?= url('/admin/students') ?>" class="px-4 py-2 rounded-xl bg-white/10 text-white text-[13px] hover:bg-white/20 transition-all">
        <i class="fa-solid fa-database mr-1"></i> Reference Database
      </a>
    </div>
  </div>

  <!-- Filter & Search Bar -->
  <div class="p-5 rounded-2xl bg-white/5 border border-white/10 mb-8 flex flex-col md:flex-row justify-between items-center gap-4">
    <div class="flex items-center gap-2">
      <a href="<?= url('/admin/alumni/mapping?filter=all') ?>" 
         class="px-4 py-2 rounded-xl text-[13px] font-semibold transition-all <?= ($filter === 'all') ? 'bg-[#800020] text-white shadow-sm' : 'bg-white/5 text-white/70 hover:bg-white/10' ?>">
        সকল মেম্বার (All)
      </a>
      <a href="<?= url('/admin/alumni/mapping?filter=unmapped') ?>" 
         class="px-4 py-2 rounded-xl text-[13px] font-semibold transition-all <?= ($filter === 'unmapped') ? 'bg-amber-600 text-white shadow-sm' : 'bg-white/5 text-white/70 hover:bg-white/10' ?>">
        ⚠️ Unmapped Members Only
      </a>
      <a href="<?= url('/admin/alumni/mapping?filter=mapped') ?>" 
         class="px-4 py-2 rounded-xl text-[13px] font-semibold transition-all <?= ($filter === 'mapped') ? 'bg-emerald-600 text-white shadow-sm' : 'bg-white/5 text-white/70 hover:bg-white/10' ?>">
        ✓ Mapped Members Only
      </a>
    </div>

    <form method="GET" action="<?= url('/admin/alumni/mapping') ?>" class="w-full md:w-auto flex items-center gap-2">
      <input type="hidden" name="filter" value="<?= e($filter) ?>">
      <input type="text" name="q" value="<?= e($search) ?>" placeholder="মেম্বার নাম, ইমেইল বা ফোন দিয়ে খোঁজ..."
             class="px-4 py-2 rounded-xl bg-black/40 border border-white/10 text-white text-[13.5px] focus:outline-none focus:border-[#E58E97] w-64">
      <button type="submit" class="px-4 py-2 rounded-xl bg-[#800020] text-white text-[13px] font-semibold">খুঁজুন</button>
    </form>
  </div>

  <!-- Mapping Table -->
  <div class="rounded-3xl bg-white/5 border border-white/10 overflow-hidden shadow-xl">
    <div class="overflow-x-auto">
      <table class="w-full text-left border-collapse text-[13.5px]">
        <thead>
          <tr class="border-b border-white/10 bg-black/40 text-white/60 font-mono text-[11px] uppercase tracking-wider">
            <th class="p-4">Alumni Member</th>
            <th class="p-4">Batch & Contact</th>
            <th class="p-4">Mapping Status</th>
            <th class="p-4">Matched Reference Record</th>
            <th class="p-4 text-right">Action / Manual Mapping</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-white/5 text-slate-200">
          <?php if (empty($alumniList)): ?>
          <tr>
            <td colspan="5" class="p-8 text-center text-white/40">কোনো অ্যালামনাই মেম্বার তথ্য পাওয়া যায়নি।</td>
          </tr>
          <?php else: ?>
            <?php foreach ($alumniList as $row): 
              $isMapped = !empty($row['student_reference_id']);
            ?>
            <tr class="hover:bg-white/[0.02] transition-colors">
              <td class="p-4">
                <div class="font-bold text-white text-[14.5px]"><?= e($row['name']) ?></div>
                <div class="text-[12px] text-white/50"><?= e($row['email']) ?></div>
              </td>

              <td class="p-4">
                <div class="font-mono text-[12px] text-[#E58E97] font-semibold">Batch: <?= e($row['batch_year'] ?: 'N/A') ?></div>
                <div class="text-[12px] text-white/60"><?= e($row['phone'] ?: 'No Phone') ?></div>
              </td>

              <td class="p-4">
                <?php if ($isMapped): ?>
                  <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-emerald-500/20 text-emerald-300 border border-emerald-500/30 text-[11px] font-bold font-mono">
                    <i class="fa-solid fa-link text-[10px]"></i> MAPPED
                  </span>
                <?php else: ?>
                  <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-amber-500/20 text-amber-300 border border-amber-500/30 text-[11px] font-bold font-mono">
                    <i class="fa-solid fa-link-slash text-[10px]"></i> UNMAPPED
                  </span>
                <?php endif; ?>
              </td>

              <td class="p-4">
                <?php if ($isMapped): ?>
                  <div class="text-[13px] text-white font-semibold"><?= e($row['ref_name_en']) ?> (<?= e($row['ref_name_bn']) ?>)</div>
                  <div class="text-[11.5px] font-mono text-emerald-400">Roll: <?= e($row['ref_roll']) ?> | Batch: <?= e($row['ref_batch']) ?> | Mobile: <?= e($row['ref_mobile']) ?></div>
                <?php else: ?>
                  <span class="text-white/40 text-[12.5px] italic">রেফারেন্স ডাটাবেসে ম্যাপ করা হয়নি</span>
                <?php endif; ?>
              </td>

              <td class="p-4 text-right">
                <form action="<?= url('/admin/alumni/map-student') ?>" method="POST" class="inline-flex items-center gap-2">
                  <?= csrf_field() ?>
                  <input type="hidden" name="profile_id" value="<?= $row['id'] ?>">

                  <?php if ($isMapped): ?>
                    <input type="hidden" name="student_reference_id" value="">
                    <button type="submit" class="px-3 py-1.5 rounded-xl text-[12px] font-medium bg-red-950/60 text-red-300 border border-red-800/50 hover:bg-red-900 transition-all">
                      <i class="fa-solid fa-unlink mr-1"></i> Unmap
                    </button>
                  <?php else: ?>
                    <select name="student_reference_id" required class="px-3 py-1.5 rounded-xl bg-black/60 border border-white/20 text-white text-[12px] focus:outline-none max-w-[200px]">
                      <option value="">-- মেম্বার সিলেক্ট করুন --</option>
                      <?php foreach ($unmappedStudents as $st): ?>
                        <option value="<?= $st['id'] ?>">
                          [Roll: <?= e($st['roll']) ?>] <?= e($st['name_english']) ?> (<?= e($st['batch']) ?>)
                        </option>
                      <?php endforeach; ?>
                    </select>
                    <button type="submit" class="px-3 py-1.5 rounded-xl text-[12px] font-semibold bg-emerald-700 text-white hover:bg-emerald-600 transition-all">
                      <i class="fa-solid fa-link mr-1"></i> Map & Autofill
                    </button>
                  <?php endif; ?>
                </form>
              </td>
            </tr>
            <?php endforeach; ?>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>

</div>
