<?php
/**
 * Admin Stories Index View
 * Variables: $stories
 */
?>
<div class="flex justify-end mb-6">
  <a href="<?= url('/admin/stories/create') ?>" class="px-4 py-2 rounded-xl text-[13px] font-semibold text-white"
     style="background:linear-gradient(135deg,#A22638,#800020);">+ Add Story</a>
</div>

<div class="rounded-2xl overflow-hidden" style="background:rgba(255,255,255,0.04);border:1px solid rgba(255,255,255,0.08);">
  <table class="w-full text-[13px]">
    <thead>
      <tr class="border-b border-white/5">
        <th class="text-left px-5 py-3.5 text-white/35 font-mono text-[11px]">Title</th>
        <th class="text-left px-5 py-3.5 text-white/35 font-mono text-[11px]">Batch</th>
        <th class="text-left px-5 py-3.5 text-white/35 font-mono text-[11px]">Featured</th>
        <th class="text-left px-5 py-3.5 text-white/35 font-mono text-[11px]">Status</th>
        <th class="text-left px-5 py-3.5 text-white/35 font-mono text-[11px]">Actions</th>
      </tr>
    </thead>
    <tbody class="divide-y divide-white/5">
      <?php if (empty($stories)): ?>
      <tr><td colspan="5" class="px-5 py-8 text-center text-white/40">No success stories found.</td></tr>
      <?php else: ?>
      <?php foreach ($stories as $s): ?>
      <tr>
        <td class="px-5 py-3.5 font-medium text-white"><?= e($s['title']) ?></td>
        <td class="px-5 py-3.5 text-white/70 font-mono"><?= e($s['batch_year']) ?></td>
        <td class="px-5 py-3.5">
          <form method="POST" action="<?= url('/admin/stories/' . $s['id'] . '/toggle-featured') ?>" class="inline">
            <?= csrf_field() ?>
            <button type="submit" class="px-2 py-0.5 rounded text-[10px] font-semibold uppercase transition-all shadow-sm <?= $s['is_featured'] ? 'bg-yellow-500/20 text-yellow-400 border border-yellow-500/40 hover:bg-yellow-500/30' : 'bg-white/5 text-white/40 border border-white/10 hover:bg-white/10 hover:text-white' ?>" title="Click to toggle featured status">
              <?= $s['is_featured'] ? '★ Featured' : '☆ Make Featured' ?>
            </button>
          </form>
        </td>
        <td class="px-5 py-3.5">
          <span class="px-2.5 py-0.5 rounded-full text-[10.5px] font-mono"
                style="background:<?= $s['status'] === 'published' ? 'rgba(78,156,129,0.2)' : 'rgba(255,255,255,0.05)' ?>;color:<?= $s['status'] === 'published' ? '#4E9C81' : 'rgba(255,255,255,0.4)' ?>;">
            <?= strtoupper($s['status']) ?>
          </span>
        </td>
        <td class="px-5 py-3.5 space-x-2">
          <a href="<?= url('/admin/stories/' . $s['id'] . '/preview') ?>" class="px-2.5 py-1 rounded bg-blue-600/80 text-white font-semibold text-[11px] hover:bg-blue-500 shadow inline-block">
            👁 Preview
          </a>
          <?php if ($s['status'] === 'pending'): ?>
          <form method="POST" action="<?= url('/admin/stories/' . $s['id'] . '/approve') ?>" class="inline" onsubmit="return confirm('অনুমোদন করলে এটি সাইটে প্রকাশিত হবে এবং সকল অ্যালামনাই সদস্যকে ইমেইল নোটিফিকেশন অ্যালার্ট পাঠানো হবে। আপনি কি নিশ্চিত?')">
            <?= csrf_field() ?>
            <button type="submit" class="px-2.5 py-1 rounded bg-emerald-600 text-white font-semibold text-[11px] hover:bg-emerald-500 shadow">✓ Approve & Alert Alumni</button>
          </form>
          <form method="POST" action="<?= url('/admin/stories/' . $s['id'] . '/reject') ?>" class="inline">
            <?= csrf_field() ?>
            <button type="submit" class="px-2.5 py-1 rounded bg-rose-900/60 text-rose-300 font-semibold text-[11px] hover:bg-rose-800">✕ Reject</button>
          </form>
          <?php endif; ?>
          <a href="<?= url('/admin/stories/' . $s['id'] . '/edit') ?>" class="text-[#E58E97] hover:underline">Edit</a>
          <form method="POST" action="<?= url('/admin/stories/' . $s['id'] . '/delete') ?>" class="inline" onsubmit="return confirm('Are you sure you want to delete this story?')">
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
