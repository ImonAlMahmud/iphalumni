<?php
/**
 * Admin Events Index View
 * Variables: $events
 */
?>
<div class="flex justify-end mb-6">
  <a href="<?= url('/admin/events/create') ?>" class="px-4 py-2 rounded-xl text-[13px] font-semibold text-white"
     style="background:linear-gradient(135deg,#A22638,#800020);">+ Add Event</a>
</div>

<div class="rounded-2xl overflow-hidden" style="background:rgba(255,255,255,0.04);border:1px solid rgba(255,255,255,0.08);">
  <table class="w-full text-[13px]">
    <thead>
      <tr class="border-b border-white/5">
        <th class="text-left px-5 py-3.5 text-white/35 font-mono text-[11px]">Title</th>
        <th class="text-left px-5 py-3.5 text-white/35 font-mono text-[11px]">Date</th>
        <th class="text-left px-5 py-3.5 text-white/35 font-mono text-[11px]">Venue</th>
        <th class="text-left px-5 py-3.5 text-white/35 font-mono text-[11px]">Status</th>
        <th class="text-left px-5 py-3.5 text-white/35 font-mono text-[11px]">Actions</th>
      </tr>
    </thead>
    <tbody class="divide-y divide-white/5">
      <?php if (empty($events)): ?>
      <tr><td colspan="5" class="px-5 py-8 text-center text-white/40">No events found.</td></tr>
      <?php else: ?>
      <?php foreach ($events as $ev): ?>
      <tr>
        <td class="px-5 py-3.5 font-medium text-white"><?= e($ev['title']) ?></td>
        <td class="px-5 py-3.5 text-white/70 font-mono text-[12.5px]"><?= date('d M Y H:i', strtotime($ev['event_date'])) ?></td>
        <td class="px-5 py-3.5 text-white/70"><?= e($ev['venue'] ?? '—') ?></td>
        <td class="px-5 py-3.5">
          <span class="px-2.5 py-0.5 rounded-full text-[10.5px] font-mono"
                style="background:<?= $ev['status'] === 'published' ? 'rgba(78,156,129,0.2)' : 'rgba(255,255,255,0.05)' ?>;color:<?= $ev['status'] === 'published' ? '#4E9C81' : 'rgba(255,255,255,0.4)' ?>;">
            <?= strtoupper($ev['status']) ?>
          </span>
        </td>
        <td class="px-5 py-3.5 space-x-3">
          <a href="<?= url('/admin/events/' . $ev['id'] . '/financials') ?>" class="text-blue-400 hover:underline">Financials</a>
          <a href="<?= url('/admin/events/' . $ev['id'] . '/edit') ?>" class="text-[#A22638] hover:underline">Edit</a>
          <form method="POST" action="<?= url('/admin/events/' . $ev['id'] . '/delete') ?>" class="inline" onsubmit="return confirm('Are you sure?')">
            <?= csrf_field() ?>
            <button type="submit" class="text-red-400 hover:underline">Delete</button>
          </form>
        </td>
      </tr>
      <?php endforeach; ?>
      <?php endif; ?>
    </tbody>
  </table>
</div>
