<?php
/**
 * Admin Committee Add/Edit Form View
 * Variables: $member, $alumni
 */
$action = $member ? url('/admin/committee/' . $member['id']) : url('/admin/committee');
?>
<div class="mb-6">
  <a href="<?= url('/admin/committee') ?>" class="text-[13px] text-white/50 hover:text-white inline-flex items-center gap-1">
    ← Back to Committee
  </a>
</div>

<div class="p-8 rounded-3xl" style="background:rgba(255,255,255,0.04);border:1px solid rgba(255,255,255,0.08);">
  <form method="POST" action="<?= $action ?>" class="space-y-5">
    <?= csrf_field() ?>

    <?php if (!$member): ?>
    <div>
      <label class="block text-[13px] font-medium text-white/70 mb-1.5" for="user_id">Select Alumni</label>
      <select id="user_id" name="user_id" required
              class="w-full px-4 py-2.5 rounded-xl text-[14px] text-white focus:outline-none"
              style="background:rgba(255,255,255,0.06);border:1px solid rgba(255,255,255,0.12);">
        <option value="">-- Select an alumni / অ্যালামনাই নির্বাচন করুন --</option>
        <?php foreach ($alumni as $a): ?>
        <option value="<?= $a['id'] ?>">
          <?= e($a['name']) ?> (<?= e($a['email']) ?>) <?= !empty($a['batch_year']) ? ' · Batch ' . e($a['batch_year']) : '' ?>
        </option>
        <?php endforeach; ?>
      </select>
    </div>
    <?php endif; ?>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
      <div>
        <label class="block text-[13px] font-medium text-white/70 mb-1.5" for="designation">Designation</label>
        <input id="designation" type="text" name="designation" value="<?= e($member['designation'] ?? '') ?>" required
               class="w-full px-4 py-2.5 rounded-xl text-[14px] text-white focus:outline-none"
               style="background:rgba(255,255,255,0.06);border:1px solid rgba(255,255,255,0.12);"
               placeholder="e.g. President, General Secretary">
      </div>
      <div>
        <label class="block text-[13px] font-medium text-white/70 mb-1.5" for="committee_type">Committee Type</label>
        <select id="committee_type" name="committee_type" class="w-full px-4 py-2.5 rounded-xl text-[14px] text-white focus:outline-none"
                style="background:rgba(255,255,255,0.06);border:1px solid rgba(255,255,255,0.12);">
          <option value="executive" <?= ($member['committee_type'] ?? '') === 'executive' ? 'selected' : '' ?>>Executive Committee</option>
          <option value="advisory" <?= ($member['committee_type'] ?? '') === 'advisory' ? 'selected' : '' ?>>Advisory Committee</option>
          <option value="special" <?= ($member['committee_type'] ?? '') === 'special' ? 'selected' : '' ?>>Special Committee</option>
        </select>
      </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
      <div>
        <label class="block text-[13px] font-medium text-white/70 mb-1.5" for="sort_order">Sort Order (Lower appears first)</label>
        <input id="sort_order" type="number" name="sort_order" value="<?= e($member['sort_order'] ?? '0') ?>"
               class="w-full px-4 py-2.5 rounded-xl text-[14px] text-white focus:outline-none"
               style="background:rgba(255,255,255,0.06);border:1px solid rgba(255,255,255,0.12);">
      </div>
      <div>
        <label class="block text-[13px] font-medium text-white/70 mb-1.5" for="from_date">From Date</label>
        <input id="from_date" type="date" name="from_date" value="<?= $member ? date('Y-m-d', strtotime($member['from_date'])) : '' ?>"
               class="w-full px-4 py-2.5 rounded-xl text-[14px] text-white focus:outline-none"
               style="background:rgba(255,255,255,0.06);border:1px solid rgba(255,255,255,0.12);">
      </div>
    </div>

    <div class="p-4 rounded-2xl bg-amber-500/10 border border-amber-500/20 space-y-2">
      <label class="flex items-center gap-3 cursor-pointer">
        <input type="checkbox" name="can_manage_finance" value="1" <?= !empty($member['can_manage_finance']) ? 'checked' : '' ?> class="w-4 h-4 rounded text-emerald-600 bg-black/40 border-white/20 focus:ring-emerald-500">
        <div>
          <span class="text-[13.5px] font-bold text-amber-300 block">💰 Grant Financial Feature Access (আর্থিক প্যানেল অ্যাক্সেস প্রদান করুন)</span>
          <span class="text-[12px] text-white/60 block">এই অপশনটি চেক করা থাকলে উক্ত সদস্য তার মেম্বার পোর্টালে "Financials" মেনু দেখতে পাবেন এবং বাজেট, তহবিল ও খরচের হিসাব পরিচালনা করতে পারবেন।</span>
        </div>
      </label>
    </div>

    <button type="submit" class="px-6 py-2.5 rounded-xl text-[14px] font-semibold text-white"
            style="background:linear-gradient(135deg,#A22638,#800020);">Save Member</button>
  </form>
</div>
