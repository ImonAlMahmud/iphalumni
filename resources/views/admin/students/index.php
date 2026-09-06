<?php
/**
 * Admin Student Reference Database View
 * Variables: $students, $pagination, $batches, $sessions, $depts, $batch, $session, $dept, $search, $missingInfo
 */
$missingInfo = $_GET['missing_info'] ?? '';
?>
<!-- Flash Messages -->
<?php if (has_flash('success')): ?>
  <div class="mb-5 p-4 rounded-xl bg-emerald-500/20 border border-emerald-500/30 text-emerald-300 text-[13.5px] flex items-center justify-between">
    <span><?= get_flash('success') ?></span>
    <button onclick="this.parentElement.remove()" class="text-emerald-300 hover:text-white">&times;</button>
  </div>
<?php endif; ?>

<?php if (has_flash('error')): ?>
  <div class="mb-5 p-4 rounded-xl bg-rose-500/20 border border-rose-500/30 text-rose-300 text-[13.5px] flex items-center justify-between">
    <span><?= get_flash('error') ?></span>
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
      <option value="">All Batches</option>
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
          <span class="px-2 py-0.5 rounded text-[11px]" style="background:rgba(162,38,56,0.15);color:#A22638;">
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

<!-- EDIT STUDENT MODAL -->
<div id="editStudentModal" class="fixed inset-0 bg-black/80 backdrop-blur-sm z-50 hidden flex items-center justify-center p-4">
  <div class="bg-[#18191c] border border-white/10 rounded-2xl w-full max-w-xl p-6 shadow-2xl space-y-5">
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
          <input type="text" name="batch" id="edit_batch" required placeholder="e.g. L-01"
                 class="w-full px-3.5 py-2 bg-white/5 border border-white/10 rounded-xl text-white text-[13px] focus:outline-none focus:border-[#A22638]">
        </div>

        <!-- Session -->
        <div>
          <label class="block text-[12px] font-mono text-white/60 mb-1">Session <span class="text-rose-400">*</span></label>
          <input type="text" name="session" id="edit_session" required placeholder="e.g. 2018-2019"
                 class="w-full px-3.5 py-2 bg-white/5 border border-white/10 rounded-xl text-white text-[13px] focus:outline-none focus:border-[#A22638]">
        </div>

        <!-- Department -->
        <div>
          <label class="block text-[12px] font-mono text-white/60 mb-1">Department <span class="text-rose-400">*</span></label>
          <input type="text" name="department" id="edit_department" required placeholder="e.g. Epidemiology"
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
</script>
