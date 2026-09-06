<?php
/**
 * Admin Student Reference Database View
 * Variables: $students, $pagination, $batches, $sessions, $depts, $batch, $session, $dept, $search, $missingInfo
 */
$missingInfo = $_GET['missing_info'] ?? '';
?>

<!-- Header & Quick Actions -->
<div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4 mb-6">
  <div>
    <h1 class="text-[20px] font-bold text-white flex items-center gap-2.5">
      <i class="fa-solid fa-database text-[#E58E97]"></i> Student Reference Database (শিক্ষার্থী রেফারেন্স ডাটাবেজ)
    </h1>
    <p class="text-[12.5px] text-white/50 mt-1 flex items-center flex-wrap gap-2">
      <span>অ্যালামনাই ভেরিফিকেশনের জন্য সর্বমোট</span>
      <span class="px-2 py-0.5 rounded-md bg-[#A22638]/20 text-[#E58E97] font-mono font-semibold border border-[#A22638]/30"><?= count($batches) ?> টি ব্যাচ</span>
      <span>এবং</span>
      <span class="px-2 py-0.5 rounded-md bg-white/10 text-white font-mono font-semibold border border-white/10"><?= number_format($pagination['total']) ?> জন শিক্ষার্থী</span>
      <span>সংরক্ষিত রয়েছে।</span>
    </p>
  </div>

  <div class="flex flex-wrap items-center gap-2.5">
    <!-- Add Student / New Batch Button -->
    <button onclick="openAddModal()" 
            class="px-4 py-2 bg-[#A22638] hover:bg-[#800020] text-white rounded-xl text-[13px] font-semibold transition-all shadow-lg shadow-[#A22638]/20 flex items-center gap-2">
      <i class="fa-solid fa-user-plus text-[12px]"></i> নতুন ব্যাচ / শিক্ষার্থী যোগ
    </button>

    <!-- Import Excel / CSV Button -->
    <button onclick="openImportModal()" 
            class="px-4 py-2 bg-emerald-600/80 hover:bg-emerald-600 text-white rounded-xl text-[13px] font-semibold transition-all border border-emerald-500/30 shadow-lg shadow-emerald-950/30 flex items-center gap-2">
      <i class="fa-solid fa-file-import text-[12px]"></i> এক্সেল / সিএসভি ইমপোর্ট
    </button>

    <!-- Sample Template Download -->
    <a href="<?= url('/admin/students/sample-template') ?>" 
       class="px-3.5 py-2 bg-white/10 hover:bg-white/20 text-white border border-white/10 rounded-xl text-[12.5px] font-medium transition-colors flex items-center gap-1.5"
       title="Download Sample Excel/CSV Template">
      <i class="fa-solid fa-file-arrow-down text-amber-400 text-[12px]"></i> স্যাম্পল টেমপ্লেট
    </a>
  </div>
</div>

<!-- Flash Messages -->
<?php if (has_flash('success')): ?>
  <div class="mb-5 p-4 rounded-xl bg-emerald-500/20 border border-emerald-500/30 text-emerald-300 text-[13.5px] flex items-center justify-between">
    <div class="flex items-center gap-2">
      <i class="fa-solid fa-circle-check text-emerald-400"></i>
      <span><?= get_flash('success') ?></span>
    </div>
    <button onclick="this.parentElement.remove()" class="text-emerald-300 hover:text-white">&times;</button>
  </div>
<?php endif; ?>

<?php if (has_flash('error')): ?>
  <div class="mb-5 p-4 rounded-xl bg-rose-500/20 border border-rose-500/30 text-rose-300 text-[13.5px] flex items-center justify-between">
    <div class="flex items-center gap-2">
      <i class="fa-solid fa-triangle-exclamation text-rose-400"></i>
      <span><?= get_flash('error') ?></span>
    </div>
    <button onclick="this.parentElement.remove()" class="text-rose-300 hover:text-white">&times;</button>
  </div>
<?php endif; ?>

<!-- Filters -->
<div class="flex flex-wrap gap-3 mb-6 items-center">
  <form method="GET" action="<?= url('/admin/students') ?>" class="flex flex-wrap gap-3 items-center">
    <input type="text" name="q" value="<?= e($search) ?>" placeholder="Search by name, roll or phone..."
           class="px-4 py-2 rounded-xl text-[13px] text-white focus:outline-none focus:ring-2 focus:ring-[#A22638]/40"
           style="background:rgba(255,255,255,0.06);border:1px solid rgba(255,255,255,0.1);min-width:240px;">
    
    <select name="batch" class="px-4 py-2 rounded-xl text-[13px] text-white focus:outline-none"
            style="background:rgba(255,255,255,0.06);border:1px solid rgba(255,255,255,0.1);">
      <option value="">All Batches (<?= count($batches) ?>)</option>
      <?php foreach ($batches as $b): ?>
      <option value="<?= e($b) ?>" <?= $batch === $b ? 'selected' : '' ?>><?= e($b) ?></option>
      <?php endforeach; ?>
    </select>

    <select name="session" class="px-4 py-2 rounded-xl text-[13px] text-white focus:outline-none"
            style="background:rgba(255,255,255,0.06);border:1px solid rgba(255,255,255,0.1);">
      <option value="">All Sessions</option>
      <?php foreach ($sessions as $s): ?>
      <option value="<?= e($s) ?>" <?= $session === $s ? 'selected' : '' ?>><?= e($s) ?></option>
      <?php endforeach; ?>
    </select>

    <select name="dept" class="px-4 py-2 rounded-xl text-[13px] text-white focus:outline-none"
            style="background:rgba(255,255,255,0.06);border:1px solid rgba(255,255,255,0.1);max-width:200px;">
      <option value="">All Departments</option>
      <?php foreach ($depts as $d): ?>
      <option value="<?= e($d) ?>" <?= $dept === $d ? 'selected' : '' ?>><?= e($d) ?></option>
      <?php endforeach; ?>
    </select>

    <!-- Missing Info Filter Button -->
    <a href="<?= url('/admin/students?' . http_build_query(array_merge($_GET, ['missing_info' => $missingInfo === '1' ? '' : '1', 'page' => 1]))) ?>" 
       class="px-4 py-2 rounded-xl text-[13px] font-semibold transition-all flex items-center gap-1.5 <?= $missingInfo === '1' ? 'bg-[#800020] text-white font-bold shadow-lg ring-2 ring-[#E58E97]' : 'bg-white/5 text-white/70 border border-white/10 hover:bg-white/10 hover:text-white' ?>">
      <i class="fa-solid fa-triangle-exclamation text-[11px] <?= $missingInfo === '1' ? 'text-white' : 'text-[#E58E97]' ?>"></i>
      <?= $missingInfo === '1' ? 'Showing Missing Info' : 'Filter Missing Info' ?>
    </a>

    <button type="submit" class="px-5 py-2 rounded-xl text-[13px] font-semibold text-white"
            style="background:linear-gradient(135deg,#A22638,#800020);">Filter</button>
    <a href="<?= url('/admin/students') ?>" class="px-5 py-2 rounded-xl text-[13px] text-white" style="background:rgba(255,255,255,0.06);border:1px solid rgba(255,255,255,0.1);">Reset</a>
  </form>

  <div class="ml-auto flex items-center gap-2">
    <?php if ($batch !== ''): ?>
      <!-- Delete batch action if single batch filtered -->
      <form method="POST" action="<?= url('/admin/students/batch/delete') ?>" 
            onsubmit="return confirm('সতর্কতা: আপনি কি নিশ্চিত যে ব্যাচ \'<?= e(addslashes($batch)) ?>\'-এর সকল শিক্ষার্থীর রেকর্ড মুছে ফেলতে চান? এই অ্যাকশন আনডু করা যাবে না!');"
            class="inline">
        <?= csrf_field() ?>
        <input type="hidden" name="batch" value="<?= e($batch) ?>">
        <button type="submit" class="px-3 py-2 bg-rose-950/40 hover:bg-rose-900 text-rose-300 border border-rose-800/40 rounded-xl text-[12.5px] font-medium transition-colors flex items-center gap-1.5" title="Delete entire batch">
          <i class="fa-solid fa-trash-can text-[11px]"></i> ব্যাচ '<?= e($batch) ?>' মুছুন
        </button>
      </form>
    <?php endif; ?>

    <a href="<?= url('/admin/students/export/csv?' . http_build_query($_GET)) ?>" class="px-3.5 py-2 bg-white/10 hover:bg-white/20 text-white border border-white/10 rounded-xl text-[12.5px] font-medium transition-colors flex items-center gap-1.5">
      <i class="fa-solid fa-file-excel text-[#E58E97] text-[12px]"></i> Export Excel
    </a>
    <a href="<?= url('/admin/students/export/print?' . http_build_query($_GET)) ?>" target="_blank" class="px-3.5 py-2 bg-white/10 hover:bg-white/20 text-white border border-white/10 rounded-xl text-[12.5px] font-medium transition-colors flex items-center gap-1.5">
      <i class="fa-solid fa-file-pdf text-[#E58E97] text-[12px]"></i> Export PDF
    </a>
    <div class="text-[12.5px] text-white/40 ml-2">
      <?= number_format($pagination['total']) ?> total records
    </div>
  </div>
</div>

<!-- Table -->
<div class="rounded-2xl overflow-hidden shadow-xl" style="background:rgba(255,255,255,0.03);border:1px solid rgba(255,255,255,0.08);">
  <table class="w-full text-[13px]">
    <thead>
      <tr style="border-bottom:1px solid rgba(255,255,255,0.08);">
        <?php foreach (['Roll', 'Name (English)', 'Name (Bangla)', 'Mobile', 'Guardian Mobile', 'Batch', 'Session', 'Department', 'Actions'] as $h): ?>
        <th class="text-left px-4 py-3.5 font-medium font-mono text-[11px] tracking-wider <?= $h === 'Actions' ? 'text-right pr-6' : '' ?>" style="color:rgba(255,255,255,0.35);"><?= $h ?></th>
        <?php endforeach; ?>
      </tr>
    </thead>
    <tbody class="divide-y" style="border-color:rgba(255,255,255,0.05);">
      <?php if (empty($students)): ?>
      <tr><td colspan="9" class="px-5 py-8 text-center" style="color:rgba(255,255,255,0.35);">No students found matching current filters.</td></tr>
      <?php else: ?>
      <?php foreach ($students as $s): ?>
      <?php 
        $hasMissing = empty($s['roll']) || empty($s['name_english']) || empty($s['name_bangla']) || empty($s['mobile']) || empty($s['guardian_mobile']) || empty($s['batch']) || empty($s['session']) || empty($s['department']);
      ?>
      <tr class="hover:bg-white/[0.02] transition-colors <?= $hasMissing ? 'bg-amber-500/[0.03]' : '' ?>">
        <!-- Roll -->
        <td class="px-4 py-3.5 font-mono text-white">
          <?php if (empty($s['roll'])): ?>
            <span class="px-2 py-0.5 rounded text-[11px] bg-amber-500/20 text-amber-300 font-sans">Missing</span>
          <?php else: ?>
            <?= e($s['roll']) ?>
          <?php endif; ?>
        </td>

        <!-- Name English -->
        <td class="px-4 py-3.5 font-semibold text-white">
          <?= e($s['name_english']) ?>
        </td>

        <!-- Name Bangla -->
        <td class="px-4 py-3.5" style="color:rgba(255,255,255,0.75);">
          <?php if (empty($s['name_bangla'])): ?>
            <span class="px-2 py-0.5 rounded text-[11px] bg-amber-500/20 text-amber-300">Missing</span>
          <?php else: ?>
            <?= e($s['name_bangla']) ?>
          <?php endif; ?>
        </td>

        <!-- Mobile -->
        <td class="px-4 py-3.5 font-mono" style="color:rgba(255,255,255,0.65);">
          <?php if (empty($s['mobile'])): ?>
            <span class="px-2 py-0.5 rounded text-[11px] bg-amber-500/20 text-amber-300 font-sans">Missing</span>
          <?php else: ?>
            <?= e($s['mobile']) ?>
          <?php endif; ?>
        </td>

        <!-- Guardian Mobile -->
        <td class="px-4 py-3.5 font-mono" style="color:rgba(255,255,255,0.65);">
          <?php if (empty($s['guardian_mobile'])): ?>
            <span class="px-2 py-0.5 rounded text-[11px] bg-amber-500/20 text-amber-300 font-sans">Missing</span>
          <?php else: ?>
            <?= e($s['guardian_mobile']) ?>
          <?php endif; ?>
        </td>

        <!-- Batch -->
        <td class="px-4 py-3.5 font-mono">
          <span class="px-2.5 py-0.5 rounded-full text-[11.5px] font-semibold font-mono" style="background:rgba(162,38,56,0.15);color:#E58E97;border:1px solid rgba(162,38,56,0.3);">
            <?= e($s['batch']) ?>
          </span>
        </td>

        <!-- Session -->
        <td class="px-4 py-3.5 font-mono" style="color:rgba(255,255,255,0.55);"><?= e($s['session']) ?></td>

        <!-- Department -->
        <td class="px-4 py-3.5 text-xs truncate max-w-[180px]" style="color:rgba(255,255,255,0.55);"><?= e($s['department']) ?></td>

        <!-- Actions -->
        <td class="px-4 py-3.5 text-right pr-6">
          <div class="flex items-center justify-end gap-2">
            <!-- Edit Button -->
            <button onclick="openEditModal(<?= htmlspecialchars(json_encode($s), ENT_QUOTES, 'UTF-8') ?>)"
                    class="px-2.5 py-1 bg-white/10 hover:bg-white/20 text-white border border-white/10 rounded-lg text-[12px] font-medium transition-colors flex items-center gap-1"
                    title="Edit Record">
              <i class="fa-solid fa-pen text-[10px] text-[#E58E97]"></i> Edit
            </button>
            
            <!-- Delete Form -->
            <form method="POST" action="<?= url('/admin/students/' . $s['id'] . '/delete') ?>" 
                  onsubmit="return confirm('Are you sure you want to delete <?= e(addslashes($s['name_english'])) ?> from the student reference database? This action cannot be undone.');">
              <?= csrf_field() ?>
              <button type="submit" 
                      class="px-2.5 py-1 bg-rose-950/40 hover:bg-rose-900 text-rose-300 border border-rose-800/40 rounded-lg text-[12px] font-medium transition-colors flex items-center gap-1"
                      title="Delete Record">
                <i class="fa-solid fa-trash-can text-[10px]"></i> Delete
              </button>
            </form>
          </div>
        </td>
      </tr>
      <?php endforeach; ?>
      <?php endif; ?>
    </tbody>
  </table>
</div>

<!-- Pagination -->
<?php if ($pagination['last_page'] > 1): ?>
<div class="flex items-center gap-2 mt-5 justify-center">
  <?php for ($p = 1; $p <= $pagination['last_page']; $p++): ?>
  <a href="<?= url('/admin/students?page=' . $p . '&batch=' . e($batch) . '&session=' . e($session) . '&dept=' . e($dept) . '&q=' . e($search) . '&missing_info=' . e($missingInfo)) ?>"
     class="w-8 h-8 rounded-lg flex items-center justify-center text-[13px] transition-colors"
     style="<?= $p === $pagination['current_page'] ? 'background:rgba(162,38,56,0.3);color:#E58E97;' : 'background:rgba(255,255,255,0.05);color:rgba(255,255,255,0.45);' ?>">
    <?= $p ?>
  </a>
  <?php endfor; ?>
</div>
<?php endif; ?>

<!-- ADD STUDENT / NEW BATCH MODAL -->
<div id="addStudentModal" class="fixed inset-0 bg-black/80 backdrop-blur-sm z-50 hidden flex items-center justify-center p-4 overflow-y-auto">
  <div class="bg-[#18191c] border border-white/10 rounded-2xl w-full max-w-xl p-6 shadow-2xl space-y-5 my-8">
    <div class="flex items-center justify-between border-b border-white/10 pb-4">
      <div>
        <h3 class="text-[17px] font-bold text-white flex items-center gap-2">
          <i class="fa-solid fa-user-plus text-[#E58E97]"></i> নতুন শিক্ষার্থী ও ব্যাচ যোগ করুন
        </h3>
        <p class="text-[12px] text-white/50 mt-0.5">নতুন ব্যাচের নাম লিখলে তা স্বয়ংক্রিয়ভাবে ব্যাচ তালিকায় যুক্ত হবে।</p>
      </div>
      <button onclick="closeAddModal()" class="text-white/50 hover:text-white text-xl font-bold">&times;</button>
    </div>

    <form method="POST" action="<?= url('/admin/students/store') ?>" class="space-y-4">
      <?= csrf_field() ?>
      <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <!-- Batch (Select existing or type new) -->
        <div class="md:col-span-2 bg-white/[0.02] border border-white/10 rounded-xl p-3.5 space-y-2">
          <div class="flex items-center justify-between">
            <label class="block text-[12px] font-mono font-semibold text-[#E58E97]">
              <i class="fa-solid fa-layer-group"></i> ব্যাচ নির্ধারণ (Batch) <span class="text-rose-400">*</span>
            </label>
            <span class="text-[11px] text-white/40">তালিকা থেকে বাছুন বা নতুন লিখুন</span>
          </div>
          <input type="text" name="batch" id="add_batch" list="batch_datalist" required placeholder="যেমন: L-10, F-6 অথবা ড্রপডাউন থেকে বাছুন"
                 class="w-full px-3.5 py-2.5 bg-white/5 border border-white/10 rounded-xl text-white text-[13.5px] font-mono focus:outline-none focus:border-[#A22638] focus:ring-1 focus:ring-[#A22638]">
          <datalist id="batch_datalist">
            <?php foreach ($batches as $b): ?>
            <option value="<?= e($b) ?>"><?= e($b) ?> (Existing Batch)</option>
            <?php endforeach; ?>
          </datalist>
          <div class="flex flex-wrap gap-1.5 pt-1">
            <span class="text-[11px] text-white/40 self-center">কুইক সিলেক্ট:</span>
            <?php foreach (array_slice($batches, 0, 8) as $qb): ?>
            <button type="button" onclick="document.getElementById('add_batch').value='<?= e($qb) ?>'"
                    class="px-2 py-0.5 bg-white/5 hover:bg-white/10 text-white/70 hover:text-white text-[11px] font-mono rounded border border-white/10 transition-colors">
              <?= e($qb) ?>
            </button>
            <?php endforeach; ?>
          </div>
        </div>

        <!-- Session -->
        <div>
          <label class="block text-[12px] font-mono text-white/60 mb-1">Session (সেশন) <span class="text-rose-400">*</span></label>
          <input type="text" name="session" list="session_datalist" required placeholder="যেমন: 2026-27"
                 class="w-full px-3.5 py-2 bg-white/5 border border-white/10 rounded-xl text-white text-[13px] focus:outline-none focus:border-[#A22638]">
          <datalist id="session_datalist">
            <?php foreach ($sessions as $s): ?>
            <option value="<?= e($s) ?>">
            <?php endforeach; ?>
          </datalist>
        </div>

        <!-- Department -->
        <div>
          <label class="block text-[12px] font-mono text-white/60 mb-1">Department (বিভাগ) <span class="text-rose-400">*</span></label>
          <input type="text" name="department" list="dept_datalist" required placeholder="যেমন: BSc in Health Technology (Laboratory)"
                 class="w-full px-3.5 py-2 bg-white/5 border border-white/10 rounded-xl text-white text-[13px] focus:outline-none focus:border-[#A22638]">
          <datalist id="dept_datalist">
            <?php foreach ($depts as $d): ?>
            <option value="<?= e($d) ?>">
            <?php endforeach; ?>
          </datalist>
        </div>

        <!-- Roll -->
        <div>
          <label class="block text-[12px] font-mono text-white/60 mb-1">Roll / Class ID (ক্লাস রোল)</label>
          <input type="text" name="roll" placeholder="যেমন: 1"
                 class="w-full px-3.5 py-2 bg-white/5 border border-white/10 rounded-xl text-white text-[13px] font-mono focus:outline-none focus:border-[#A22638]">
        </div>

        <!-- Name English -->
        <div>
          <label class="block text-[12px] font-mono text-white/60 mb-1">Name (English) <span class="text-rose-400">*</span></label>
          <input type="text" name="name_english" required placeholder="Full Name in English"
                 class="w-full px-3.5 py-2 bg-white/5 border border-white/10 rounded-xl text-white text-[13px] focus:outline-none focus:border-[#A22638]">
        </div>

        <!-- Name Bangla -->
        <div>
          <label class="block text-[12px] font-mono text-white/60 mb-1">Name (Bangla)</label>
          <input type="text" name="name_bangla" placeholder="বাংলা নাম (ঐচ্ছিক)"
                 class="w-full px-3.5 py-2 bg-white/5 border border-white/10 rounded-xl text-white text-[13px] focus:outline-none focus:border-[#A22638]">
        </div>

        <!-- Mobile -->
        <div>
          <label class="block text-[12px] font-mono text-white/60 mb-1">Mobile Number</label>
          <input type="text" name="mobile" placeholder="01711..."
                 class="w-full px-3.5 py-2 bg-white/5 border border-white/10 rounded-xl text-white text-[13px] font-mono focus:outline-none focus:border-[#A22638]">
        </div>

        <!-- Guardian Mobile -->
        <div class="md:col-span-2">
          <label class="block text-[12px] font-mono text-white/60 mb-1">Guardian Mobile (অভিভাবকের মোবাইল)</label>
          <input type="text" name="guardian_mobile" placeholder="01811..."
                 class="w-full px-3.5 py-2 bg-white/5 border border-white/10 rounded-xl text-white text-[13px] font-mono focus:outline-none focus:border-[#A22638]">
        </div>
      </div>

      <div class="flex items-center justify-end gap-3 pt-4 border-t border-white/10">
        <button type="button" onclick="closeAddModal()" class="px-5 py-2 bg-white/10 hover:bg-white/20 text-white rounded-xl text-[13px] font-semibold transition-colors">
          Cancel
        </button>
        <button type="submit" class="px-6 py-2 bg-[#A22638] hover:bg-[#800020] text-white rounded-xl text-[13px] font-semibold transition-colors shadow-lg shadow-[#A22638]/20 flex items-center gap-2">
          <i class="fa-solid fa-check"></i> সংরক্ষণ করুন (Save)
        </button>
      </div>
    </form>
  </div>
</div>

<!-- IMPORT EXCEL / CSV MODAL -->
<div id="importModal" class="fixed inset-0 bg-black/80 backdrop-blur-sm z-50 hidden flex items-center justify-center p-4 overflow-y-auto">
  <div class="bg-[#18191c] border border-white/10 rounded-2xl w-full max-w-2xl p-6 shadow-2xl space-y-5 my-8">
    <div class="flex items-center justify-between border-b border-white/10 pb-4">
      <div>
        <h3 class="text-[17px] font-bold text-white flex items-center gap-2">
          <i class="fa-solid fa-file-import text-emerald-400"></i> এক্সেল / সিএসভি থেকে ব্যাচ ডাটা ইমপোর্ট
        </h3>
        <p class="text-[12px] text-white/50 mt-0.5">নতুন ব্যাচের সম্পূর্ণ শিক্ষার্থী তালিকা এক ক্লিকেই ইমপোর্ট করুন।</p>
      </div>
      <button onclick="closeImportModal()" class="text-white/50 hover:text-white text-xl font-bold">&times;</button>
    </div>

    <!-- Instructions Banner -->
    <div class="p-4 rounded-xl bg-emerald-500/10 border border-emerald-500/20 text-[12.5px] text-emerald-200/90 space-y-2">
      <div class="font-semibold flex items-center justify-between">
        <span class="flex items-center gap-1.5"><i class="fa-solid fa-circle-info text-emerald-400"></i> ফাইলের কলাম ফরম্যাট নির্দেশনা:</span>
        <a href="<?= url('/admin/students/sample-template') ?>" class="inline-flex items-center gap-1 text-[11.5px] text-amber-300 hover:text-white bg-amber-500/20 px-2.5 py-1 rounded-lg border border-amber-500/30">
          <i class="fa-solid fa-download text-[10px]"></i> স্যাম্পল টেমপ্লেট ডাউনলোড
        </a>
      </div>
      <p class="text-white/70">
        সাপোর্টেড ফরম্যাট: <strong class="text-white">.xlsx, .xls, .csv</strong>। ফাইলে নিম্নের কলামসমূহ থাকতে পারে:
      </p>
      <div class="font-mono text-[11px] bg-black/30 p-2.5 rounded-lg border border-white/5 text-white/80 overflow-x-auto">
        Roll, Name (English), Name (Bangla), Mobile, Guardian Mobile, Batch, Session, Department
      </div>
      <p class="text-[11.5px] text-white/50">
        * ফাইলে Batch, Session বা Department কলাম না থাকলে নিচের ডিফল্ট ইনপুট থেকে স্বয়ংক্রিয়ভাবে সংযুক্ত হবে।
      </p>
    </div>

    <form method="POST" action="<?= url('/admin/students/import') ?>" enctype="multipart/form-data" id="importForm" class="space-y-4">
      <?= csrf_field() ?>

      <!-- File Picker Dropzone -->
      <div class="border-2 border-dashed border-white/20 hover:border-emerald-500/50 rounded-2xl p-6 text-center transition-colors bg-white/[0.02]" id="dropzoneBox">
        <i class="fa-solid fa-cloud-arrow-up text-3xl text-emerald-400 mb-2"></i>
        <div class="text-[13.5px] text-white font-medium mb-1">এক্সেল বা সিএসভি ফাইল সিলেক্ট করুন</div>
        <div class="text-[11.5px] text-white/40 mb-4">.xlsx, .csv ফাইল ড্র্যাগ করে এখানে ছাড়ুন বা ব্রাউজ করুন</div>
        <label class="inline-block px-4 py-2 bg-white/10 hover:bg-white/20 text-white rounded-xl text-[12.5px] font-medium cursor-pointer transition-colors border border-white/10">
          <i class="fa-solid fa-folder-open mr-1"></i> ফাইল বাছাই করুন
          <input type="file" name="file" id="import_file_input" accept=".xlsx,.xls,.csv,.txt" required class="hidden" onchange="handleFileSelected(this)">
        </label>
        <div id="selected_file_info" class="mt-3 text-[12.5px] text-emerald-300 hidden font-mono"></div>
      </div>

      <!-- Optional Batch / Session / Department Defaults -->
      <div class="p-4 rounded-xl bg-white/[0.02] border border-white/10 space-y-3">
        <div class="text-[12px] font-mono font-semibold text-white/80 flex items-center gap-1.5">
          <i class="fa-solid fa-sliders text-[#E58E97]"></i> ডিফল্ট তথ্য (যদি ফাইলে এই কলামগুলো না থাকে বা নির্দিষ্ট করতে চান):
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
          <div>
            <label class="block text-[11.5px] font-mono text-white/50 mb-1">ব্যাচ (Default Batch)</label>
            <input type="text" name="default_batch" list="batch_datalist" placeholder="যেমন: L-10"
                   class="w-full px-3 py-1.5 bg-white/5 border border-white/10 rounded-lg text-white text-[12.5px] font-mono focus:outline-none focus:border-[#A22638]">
          </div>
          <div>
            <label class="block text-[11.5px] font-mono text-white/50 mb-1">সেশন (Default Session)</label>
            <input type="text" name="default_session" list="session_datalist" placeholder="যেমন: 2026-27"
                   class="w-full px-3 py-1.5 bg-white/5 border border-white/10 rounded-lg text-white text-[12.5px] focus:outline-none focus:border-[#A22638]">
          </div>
          <div>
            <label class="block text-[11.5px] font-mono text-white/50 mb-1">বিভাগ (Department)</label>
            <input type="text" name="default_department" list="dept_datalist" placeholder="যেমন: Laboratory"
                   class="w-full px-3 py-1.5 bg-white/5 border border-white/10 rounded-lg text-white text-[12.5px] focus:outline-none focus:border-[#A22638]">
          </div>
        </div>
      </div>

      <!-- Duplicate Handling -->
      <div class="p-4 rounded-xl bg-white/[0.02] border border-white/10 space-y-2">
        <label class="block text-[12px] font-mono font-semibold text-white/80">
          <i class="fa-solid fa-clone text-amber-400"></i> ডুপ্লিকেট রেকর্ড সমাধান (Duplicate Handling):
        </label>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-3 text-[12px]">
          <label class="flex items-center gap-2 p-2.5 rounded-lg bg-white/5 border border-white/10 cursor-pointer hover:bg-white/10">
            <input type="radio" name="duplicate_action" value="skip" checked class="text-[#A22638] focus:ring-0">
            <span class="text-white">ডুপ্লিকেট বাদ দিন (Skip)</span>
          </label>
          <label class="flex items-center gap-2 p-2.5 rounded-lg bg-white/5 border border-white/10 cursor-pointer hover:bg-white/10">
            <input type="radio" name="duplicate_action" value="update" class="text-[#A22638] focus:ring-0">
            <span class="text-white">তথ্য আপডেট করুন (Update)</span>
          </label>
          <label class="flex items-center gap-2 p-2.5 rounded-lg bg-white/5 border border-white/10 cursor-pointer hover:bg-white/10">
            <input type="radio" name="duplicate_action" value="insert" class="text-[#A22638] focus:ring-0">
            <span class="text-white">সব ইনসার্ট করুন (Insert)</span>
          </label>
        </div>
      </div>

      <div class="flex items-center justify-end gap-3 pt-4 border-t border-white/10">
        <button type="button" onclick="closeImportModal()" class="px-5 py-2 bg-white/10 hover:bg-white/20 text-white rounded-xl text-[13px] font-semibold transition-colors">
          Cancel
        </button>
        <button type="submit" id="importSubmitBtn" class="px-6 py-2 bg-emerald-600 hover:bg-emerald-500 text-white rounded-xl text-[13px] font-semibold transition-colors shadow-lg shadow-emerald-950/40 flex items-center gap-2">
          <i class="fa-solid fa-cloud-arrow-up"></i> ইমপোর্ট শুরু করুন (Start Import)
        </button>
      </div>
    </form>
  </div>
</div>

<!-- EDIT STUDENT MODAL -->
<div id="editStudentModal" class="fixed inset-0 bg-black/80 backdrop-blur-sm z-50 hidden flex items-center justify-center p-4 overflow-y-auto">
  <div class="bg-[#18191c] border border-white/10 rounded-2xl w-full max-w-xl p-6 shadow-2xl space-y-5 my-8">
    <div class="flex items-center justify-between border-b border-white/10 pb-4">
      <h3 class="text-[17px] font-bold text-white flex items-center gap-2">
        <i class="fa-solid fa-user-pen text-[#E58E97]"></i> Edit Student Record (তথ্য ম্যানুয়ালি সংশোধন)
      </h3>
      <button onclick="closeEditModal()" class="text-white/50 hover:text-white text-xl font-bold">&times;</button>
    </div>

    <form id="editStudentForm" method="POST" action="" class="space-y-4">
      <?= csrf_field() ?>
      <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <!-- Roll -->
        <div>
          <label class="block text-[12px] font-mono text-white/60 mb-1">Roll / Class ID</label>
          <input type="text" name="roll" id="edit_roll" placeholder="e.g. 101"
                 class="w-full px-3.5 py-2 bg-white/5 border border-white/10 rounded-xl text-white text-[13px] focus:outline-none focus:border-[#A22638]">
        </div>

        <!-- Name English -->
        <div>
          <label class="block text-[12px] font-mono text-white/60 mb-1">Name (English) <span class="text-rose-400">*</span></label>
          <input type="text" name="name_english" id="edit_name_english" required
                 class="w-full px-3.5 py-2 bg-white/5 border border-white/10 rounded-xl text-white text-[13px] focus:outline-none focus:border-[#A22638]">
        </div>

        <!-- Name Bangla -->
        <div>
          <label class="block text-[12px] font-mono text-white/60 mb-1">Name (Bangla)</label>
          <input type="text" name="name_bangla" id="edit_name_bangla" placeholder="বাংলা নাম"
                 class="w-full px-3.5 py-2 bg-white/5 border border-white/10 rounded-xl text-white text-[13px] focus:outline-none focus:border-[#A22638]">
        </div>

        <!-- Mobile -->
        <div>
          <label class="block text-[12px] font-mono text-white/60 mb-1">Mobile Number</label>
          <input type="text" name="mobile" id="edit_mobile" placeholder="01711..."
                 class="w-full px-3.5 py-2 bg-white/5 border border-white/10 rounded-xl text-white text-[13px] focus:outline-none focus:border-[#A22638]">
        </div>

        <!-- Guardian Mobile -->
        <div>
          <label class="block text-[12px] font-mono text-white/60 mb-1">Guardian Mobile</label>
          <input type="text" name="guardian_mobile" id="edit_guardian_mobile" placeholder="01811..."
                 class="w-full px-3.5 py-2 bg-white/5 border border-white/10 rounded-xl text-white text-[13px] focus:outline-none focus:border-[#A22638]">
        </div>

        <!-- Batch -->
        <div>
          <label class="block text-[12px] font-mono text-white/60 mb-1">Batch <span class="text-rose-400">*</span></label>
          <input type="text" name="batch" id="edit_batch" list="batch_datalist" required placeholder="e.g. L-01"
                 class="w-full px-3.5 py-2 bg-white/5 border border-white/10 rounded-xl text-white text-[13px] focus:outline-none focus:border-[#A22638]">
        </div>

        <!-- Session -->
        <div>
          <label class="block text-[12px] font-mono text-white/60 mb-1">Session <span class="text-rose-400">*</span></label>
          <input type="text" name="session" id="edit_session" list="session_datalist" required placeholder="e.g. 2018-2019"
                 class="w-full px-3.5 py-2 bg-white/5 border border-white/10 rounded-xl text-white text-[13px] focus:outline-none focus:border-[#A22638]">
        </div>

        <!-- Department -->
        <div>
          <label class="block text-[12px] font-mono text-white/60 mb-1">Department <span class="text-rose-400">*</span></label>
          <input type="text" name="department" id="edit_department" list="dept_datalist" required placeholder="e.g. Epidemiology"
                 class="w-full px-3.5 py-2 bg-white/5 border border-white/10 rounded-xl text-white text-[13px] focus:outline-none focus:border-[#A22638]">
        </div>
      </div>

      <div class="flex items-center justify-end gap-3 pt-4 border-t border-white/10">
        <button type="button" onclick="closeEditModal()" class="px-5 py-2 bg-white/10 hover:bg-white/20 text-white rounded-xl text-[13px] font-semibold transition-colors">
          Cancel
        </button>
        <button type="submit" class="px-6 py-2 bg-[#A22638] hover:bg-[#800020] text-white rounded-xl text-[13px] font-semibold transition-colors shadow-lg shadow-[#A22638]/20">
          Save Changes (সংরক্ষণ করুন)
        </button>
      </div>
    </form>
  </div>
</div>

<script>
// Open and close Add Modal
function openAddModal() {
  document.getElementById('addStudentModal').classList.remove('hidden');
}
function closeAddModal() {
  document.getElementById('addStudentModal').classList.add('hidden');
}

// Open and close Import Modal
function openImportModal() {
  document.getElementById('importModal').classList.remove('hidden');
}
function closeImportModal() {
  document.getElementById('importModal').classList.add('hidden');
}

// File selected preview
function handleFileSelected(input) {
  const infoEl = document.getElementById('selected_file_info');
  if (input.files && input.files[0]) {
    const file = input.files[0];
    const sizeKB = (file.size / 1024).toFixed(1);
    infoEl.innerHTML = '<i class="fa-solid fa-file-circle-check"></i> ' + file.name + ' (' + sizeKB + ' KB)';
    infoEl.classList.remove('hidden');
  } else {
    infoEl.classList.add('hidden');
  }
}

// Open and close Edit Modal
function openEditModal(student) {
  document.getElementById('editStudentForm').action = '<?= url('/admin/students/') ?>' + student.id + '/update';
  document.getElementById('edit_roll').value = student.roll || '';
  document.getElementById('edit_name_english').value = student.name_english || '';
  document.getElementById('edit_name_bangla').value = student.name_bangla || '';
  document.getElementById('edit_mobile').value = student.mobile || '';
  document.getElementById('edit_guardian_mobile').value = student.guardian_mobile || '';
  document.getElementById('edit_batch').value = student.batch || '';
  document.getElementById('edit_session').value = student.session || '';
  document.getElementById('edit_department').value = student.department || '';
  
  document.getElementById('editStudentModal').classList.remove('hidden');
}
function closeEditModal() {
  document.getElementById('editStudentModal').classList.add('hidden');
}

// Loading state on form submit
document.getElementById('importForm').addEventListener('submit', function() {
  const btn = document.getElementById('importSubmitBtn');
  btn.disabled = true;
  btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> ইমপোর্ট হচ্ছে, অনুগ্রহ করে অপেক্ষা করুন...';
});
</script>
