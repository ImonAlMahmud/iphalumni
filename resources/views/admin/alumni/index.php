<?php
/**
 * Admin Alumni List View
 * Variables: $alumni, $pagination, $status, $search
 */
?>
<!-- Filters & Export Bar -->
<div class="flex flex-wrap items-center justify-between gap-4 mb-6">
  <form method="GET" action="<?= url('/admin/alumni') ?>" class="flex flex-wrap gap-3">
    <input type="text" name="q" value="<?= e($search) ?>" placeholder="Search by name or email..."
           class="px-4 py-2 rounded-xl text-[13px] text-white focus:outline-none focus:ring-2 focus:ring-[#A22638]/40"
           style="background:rgba(255,255,255,0.06);border:1px solid rgba(255,255,255,0.1);min-width:220px;">
    <select name="status" class="px-4 py-2 rounded-xl text-[13px] text-white focus:outline-none bg-[#101820]"
            style="background:rgba(255,255,255,0.06);border:1px solid rgba(255,255,255,0.1);">
      <option value="">All Status</option>
      <?php foreach (['pending','under_review','verified','approved','rejected'] as $s): ?>
      <option value="<?= $s ?>" <?= $status === $s ? 'selected' : '' ?>><?= ucwords(str_replace('_',' ',$s)) ?></option>
      <?php endforeach; ?>
    </select>
    <button type="submit" class="px-5 py-2 rounded-xl text-[13px] font-semibold text-white"
            style="background:linear-gradient(135deg,#A22638,#800020);">Filter</button>
    <a href="<?= url('/admin/alumni') ?>" class="px-5 py-2 rounded-xl text-[13px] text-white" style="background:rgba(255,255,255,0.06);border:1px solid rgba(255,255,255,0.1);">Reset</a>
  </form>

  <div class="flex items-center gap-3">
    <a href="<?= url('/admin/alumni/export/excel?q=' . urlencode($search) . '&status=' . urlencode($status)) ?>"
       class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-[13px] font-semibold text-emerald-300 bg-emerald-950/60 border border-emerald-800/80 hover:bg-emerald-900 transition-colors">
      <i class="fa-solid fa-file-excel"></i> Export Excel
    </a>

    <a href="<?= url('/admin/alumni/export/pdf?q=' . urlencode($search) . '&status=' . urlencode($status) . '&autoprint=1') ?>" target="_blank"
       class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-[13px] font-semibold text-rose-300 bg-rose-950/60 border border-rose-800/80 hover:bg-rose-900 transition-colors">
      <i class="fa-solid fa-file-pdf"></i> Export PDF / Print
    </a>

    <a href="<?= url('/admin/alumni/export/cards-svg?q=' . urlencode($search) . '&status=' . urlencode($status)) ?>"
       class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-[13px] font-semibold text-amber-300 bg-amber-950/60 border border-amber-800/80 hover:bg-amber-900 transition-colors"
       title="সকল মেম্বার কার্ড ফোল্ডারভিত্তিক SVG প্রিন্ট ফরম্যাটে ডাউনলোড করুন">
      <i class="fa-solid fa-id-card"></i> Export Cards (SVG ZIP)
    </a>

    <div class="text-[12.5px] font-mono text-white/50 border-l border-white/10 pl-3">
      <?= number_format($pagination['total']) ?> records
    </div>
  </div>
</div>

<!-- Table -->
<div class="rounded-2xl overflow-hidden" style="background:rgba(255,255,255,0.03);border:1px solid rgba(255,255,255,0.08);">
  <table class="w-full text-[13px]">
    <thead>
      <tr style="border-bottom:1px solid rgba(255,255,255,0.08);">
        <?php foreach (['Name', 'Email', 'Batch', 'Status', 'Featured', 'Registered', 'Actions'] as $h): ?>
        <th class="text-left px-5 py-3.5 font-medium font-mono text-[11px] tracking-wider" style="color:rgba(255,255,255,0.35);"><?= $h ?></th>
        <?php endforeach; ?>
      </tr>
    </thead>
    <tbody class="divide-y" style="border-color:rgba(255,255,255,0.05);">
      <?php if (empty($alumni)): ?>
      <tr><td colspan="7" class="px-5 py-8 text-center" style="color:rgba(255,255,255,0.35);">No alumni found.</td></tr>
      <?php else: ?>
      <?php foreach ($alumni as $a):
        $statusColors = [
          'approved'     => ['rgba(78,156,129,0.2)', '#4E9C81'],
          'pending'      => ['rgba(162,38,56,0.15)', '#A22638'],
          'under_review' => ['rgba(99,102,241,0.2)', '#818cf8'],
          'rejected'     => ['rgba(239,68,68,0.15)', '#f87171'],
          'verified'     => ['rgba(78,156,129,0.15)', '#6ee7b7'],
        ];
        [$bg, $clr] = $statusColors[$a['status']] ?? ['rgba(255,255,255,0.05)', 'rgba(255,255,255,0.4)'];
      ?>
      <tr class="hover:bg-white/[0.02] transition-colors">
        <td class="px-5 py-3.5">
          <div class="flex items-center gap-2.5">
            <div class="w-7 h-7 rounded-full flex items-center justify-center text-[11px] font-bold shrink-0"
                 style="background:rgba(162,38,56,0.2);color:#A22638;"><?= initials($a['name']) ?></div>
            <span class="font-medium text-white truncate max-w-[160px]"><?= e($a['name']) ?></span>
          </div>
        </td>
        <td class="px-5 py-3.5" style="color:rgba(255,255,255,0.55);"><?= e($a['email']) ?></td>
        <td class="px-5 py-3.5" style="color:rgba(255,255,255,0.55);"><?= $a['batch_year'] ?? '—' ?></td>
        <td class="px-5 py-3.5">
          <span class="px-2.5 py-1 rounded-full font-mono text-[10.5px]" style="background:<?= $bg ?>;color:<?= $clr ?>;">
            <?= strtoupper(str_replace('_',' ', $a['status'])) ?>
          </span>
        </td>
        <td class="px-5 py-3.5">
          <form method="POST" action="<?= url('/admin/alumni/' . $a['id'] . '/toggle-featured') ?>" class="inline">
            <?= csrf_field() ?>
            <button type="submit" class="px-2.5 py-1 rounded text-[11px] font-semibold uppercase transition-all shadow-sm <?= !empty($a['is_featured']) ? 'bg-amber-500/20 text-amber-300 border border-amber-500/40 hover:bg-amber-500/30' : 'bg-white/5 text-white/40 border border-white/10 hover:bg-white/10 hover:text-white' ?>" title="হোমপেজে Featured হিসেবে দেখাতে ক্লিক করুন">
              <?= !empty($a['is_featured']) ? '★ Featured' : '☆ Feature' ?>
            </button>
          </form>
        </td>
        <td class="px-5 py-3.5 font-mono text-[11.5px]" style="color:rgba(255,255,255,0.4);">
          <?= date('d M Y', strtotime($a['registered_at'])) ?>
        </td>
        <td class="px-5 py-3.5">
          <div class="flex items-center gap-1.5">
            <a href="<?= url('/admin/alumni/' . $a['id']) ?>" class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-[12px] font-medium transition-colors bg-white/5 hover:bg-white/10 text-white/80 border border-white/10" title="View Full Profile">
              View
            </a>
            <a href="<?= url('/admin/alumni/' . $a['id'] . '/edit') ?>" class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-[12px] font-medium transition-colors bg-amber-500/15 hover:bg-amber-500/25 text-amber-300 border border-amber-500/30" title="Edit Member Profile">
              <i class="fa-solid fa-user-pen text-[10px]"></i> Edit
            </a>
            <a href="<?= url('/admin/alumni/' . $a['id'] . '/id-card') ?>" target="_blank" class="p-1 rounded-lg text-sky-400 hover:text-sky-300 hover:bg-white/5 transition-colors" title="View Member ID Card">
              <i class="fa-solid fa-id-card text-[12px]"></i>
            </a>
            <a href="<?= url('/admin/alumni/' . $a['id'] . '/membership-card') ?>" target="_blank" class="p-1 rounded-lg text-emerald-400 hover:text-emerald-300 hover:bg-white/5 transition-colors" title="View Membership Pass">
              <i class="fa-solid fa-qrcode text-[12px]"></i>
            </a>
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
  <a href="<?= url('/admin/alumni?page=' . $p . '&status=' . e($status) . '&q=' . e($search)) ?>"
     class="w-8 h-8 rounded-lg flex items-center justify-center text-[13px] transition-colors"
     style="<?= $p === $pagination['current_page'] ? 'background:rgba(162,38,56,0.3);color:#E58E97;' : 'background:rgba(255,255,255,0.05);color:rgba(255,255,255,0.45);' ?>">
    <?= $p ?>
  </a>
  <?php endfor; ?>
</div>
<?php endif; ?>
