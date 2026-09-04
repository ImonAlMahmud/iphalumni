<?php
/**
 * Admin Committee List View
 * Variables: $members
 */
?>
<div class="flex justify-end mb-6">
  <a href="<?= url('/admin/committee/create') ?>" class="px-4 py-2 rounded-xl text-[13px] font-semibold text-white"
     style="background:linear-gradient(135deg,#A22638,#800020);">+ Add Member</a>
</div>

<div class="rounded-2xl overflow-hidden" style="background:rgba(255,255,255,0.04);border:1px solid rgba(255,255,255,0.08);">
  <table class="w-full text-[13px]">
    <thead>
      <tr class="border-b border-white/5">
        <th class="text-left px-5 py-3.5 text-white/35 font-mono text-[11px]">Member Name</th>
        <th class="text-left px-5 py-3.5 text-white/35 font-mono text-[11px]">Designation</th>
        <th class="text-left px-5 py-3.5 text-white/35 font-mono text-[11px]">Type</th>
        <th class="text-left px-5 py-3.5 text-white/35 font-mono text-[11px]">Finance Access</th>
        <th class="text-left px-5 py-3.5 text-white/35 font-mono text-[11px]">Actions</th>
      </tr>
    </thead>
    <tbody class="divide-y divide-white/5">
      <?php if (empty($members)): ?>
      <tr><td colspan="5" class="px-5 py-8 text-center text-white/40">No committee members added.</td></tr>
      <?php else: ?>
      <?php foreach ($members as $m): ?>
      <tr>
        <td class="px-5 py-3.5 font-medium text-white"><?= e($m['name']) ?></td>
        <td class="px-5 py-3.5 text-white/70"><?= e($m['designation']) ?></td>
        <td class="px-5 py-3.5 text-white/70 uppercase font-mono text-[11px]"><?= e($m['committee_type']) ?></td>
        <td class="px-5 py-3.5">
          <form method="POST" action="<?= url('/admin/committee/' . $m['id'] . '/toggle-finance') ?>" class="inline">
            <?= csrf_field() ?>
            <?php if (!empty($m['can_manage_finance'])): ?>
            <button type="submit" class="px-2.5 py-1 rounded bg-emerald-600/30 border border-emerald-500/50 text-emerald-300 font-semibold text-[11px] hover:bg-emerald-600/50">
              ✓ Authorized (Click to Revoke)
            </button>
            <?php else: ?>
            <button type="submit" class="px-2.5 py-1 rounded bg-white/5 border border-white/10 text-white/50 font-medium text-[11px] hover:bg-white/10 hover:text-white">
              + Assign Finance Access
            </button>
            <?php endif; ?>
          </form>
        </td>
        <td class="px-5 py-3.5 space-x-2">
          <a href="<?= url('/admin/committee/' . $m['id'] . '/edit') ?>" class="text-[#E58E97] hover:underline">Edit</a>
        </td>
      </tr>
      <?php endforeach; ?>
      <?php endif; ?>
    </tbody>
  </table>
</div>
