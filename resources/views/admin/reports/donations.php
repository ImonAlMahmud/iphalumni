<?php
/**
 * Admin Donations Report View
 * Variables: $data, $total
 */
?>
<div class="mb-6 flex justify-between items-center no-print">
  <div class="text-[14px] text-white/50 font-mono">
    Total Collected: <span class="text-emerald-400 font-bold font-sans text-[16px]">৳<?= number_format($total) ?></span>
  </div>
  <button onclick="window.print()" class="px-4 py-2 bg-blue-600 text-white rounded-xl text-[12.5px] font-semibold">
    Export PDF
  </button>
</div>

<div class="rounded-2xl overflow-hidden print-area" style="background:rgba(255,255,255,0.04);border:1px solid rgba(255,255,255,0.08);">
  <table class="w-full text-[13px]">
    <thead>
      <tr class="border-b border-white/5">
        <th class="text-left px-5 py-3.5 text-white/35 font-mono text-[11px]">Donor Name</th>
        <th class="text-left px-5 py-3.5 text-white/35 font-mono text-[11px]">Email</th>
        <th class="text-left px-5 py-3.5 text-white/35 font-mono text-[11px]">Amount</th>
        <th class="text-left px-5 py-3.5 text-white/35 font-mono text-[11px]">Purpose</th>
        <th class="text-left px-5 py-3.5 text-white/35 font-mono text-[11px]">Status</th>
        <th class="text-left px-5 py-3.5 text-white/35 font-mono text-[11px]">Date</th>
      </tr>
    </thead>
    <tbody class="divide-y divide-white/5 text-white/80">
      <?php if (empty($data)): ?>
      <tr><td colspan="6" class="px-5 py-8 text-center text-white/40">No donation records found.</td></tr>
      <?php else: ?>
      <?php foreach ($data as $row): ?>
      <tr>
        <td class="px-5 py-3.5 font-medium text-white"><?= e($row['name'] ?: 'Anonymous') ?></td>
        <td class="px-5 py-3.5 text-white/60"><?= e($row['email'] ?: '—') ?></td>
        <td class="px-5 py-3.5 font-mono text-emerald-400">৳<?= number_format((float)$row['amount']) ?></td>
        <td class="px-5 py-3.5"><?= e($row['purpose']) ?></td>
        <td class="px-5 py-3.5 uppercase font-mono text-[11px]"><?= e($row['status']) ?></td>
        <td class="px-5 py-3.5 text-white/60"><?= date('d M Y H:i', strtotime($row['created_at'])) ?></td>
      </tr>
      <?php endforeach; ?>
      <?php endif; ?>
    </tbody>
  </table>
</div>

<style>
@media print {
  aside, header, nav, .no-print, button, a {
    display: none !important;
  }
  body, html, main {
    background: white !important;
    color: black !important;
    padding: 0 !important;
    margin: 0 !important;
  }
  .print-area {
    border: none !important;
    background: transparent !important;
    color: black !important;
    width: 100% !important;
  }
  table {
    width: 100% !important;
    border-collapse: collapse !important;
    color: black !important;
  }
  th {
    color: #333 !important;
    border-bottom: 2px solid #333 !important;
  }
  td {
    color: #444 !important;
    border-bottom: 1px solid #ddd !important;
  }
}
</style>
