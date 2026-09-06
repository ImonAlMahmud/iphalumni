<?php
/**
 * Admin Membership Management View
 * Variables: $pending, $stats, $memberships
 */
?>
<!-- Page Header -->
<div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
  <div>
    <span class="font-mono text-[11px] font-bold text-[#E58E97] uppercase tracking-wider block mb-1">
      <i class="fa-solid fa-id-card-clip mr-1"></i> MEMBERSHIP ADMINISTRATION
    </span>
    <h1 class="font-serif text-[28px] font-bold text-white"><?= __('মেম্বারশিপ ও ফি ম্যানেজমেন্ট', 'Membership & Fee Management') ?></h1>
  </div>
  <a href="<?= url('/admin/membership/logs') ?>" class="px-4 py-2.5 rounded-xl bg-gradient-to-r from-[#800020] to-[#A22638] hover:brightness-110 text-white text-[13px] font-bold shadow-lg shadow-[#800020]/25 transition-all flex items-center gap-2">
    <i class="fa-solid fa-receipt"></i>
    <span><?= __('মেম্বারশিপ ও পেমেন্ট লগ দেখুন', 'Membership & Payment Log') ?> →</span>
  </a>
</div>

<!-- Stats Strip -->
<div class="grid grid-cols-2 lg:grid-cols-5 gap-4 mb-8">
  <?php
  $mStats = [
    ['Active Members', $stats['total'], '#4E9C81'],
    ['Annual Tiers',  $stats['annual'], '#A22638'],
    ['Lifetime Tiers',$stats['lifetime'], '#6366f1'],
    ['Honorary Tiers',$stats['honorary'], '#8b5cf6'],
    ['Total Payments (৳)', number_format($stats['revenue']), '#4E9C81'],
  ];
  foreach ($mStats as [$label, $val, $color]):
  ?>
  <div class="p-5 rounded-2xl bg-white/5 border border-white/10">
    <div class="font-serif text-[26px] font-semibold" style="color:<?= $color ?>"><?= $val ?></div>
    <div class="text-[12px] mt-1 text-white/50"><?= $label ?></div>
  </div>
  <?php endforeach; ?>
</div>

<!-- Grant Honorary Membership by Admin -->
<div class="mb-8 p-6 rounded-2xl bg-gradient-to-r from-purple-950/40 via-indigo-950/30 to-black border border-purple-800/40 font-['Kalpurush']">
  <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-4 mb-4">
    <div>
      <h3 class="text-[17px] font-bold text-white flex items-center gap-2">
        <i class="fa-solid fa-award text-purple-400"></i> Grant Honorary Membership (সম্মানসূচক সদস্যপদ প্রদান)
      </h3>
      <p class="text-[12.5px] text-white/60 mt-0.5">
        অনারারি মেম্বারশিপ সাধারণ সদস্যদের অ্যাপ্লিকেশনের জন্য উন্মুক্ত নয়। অ্যাডমিন এখান থেকে যেকোনো বিশিষ্ট অ্যালামনাইকে সরাসরি আজীবন অনারারি মেম্বারশিপ প্রদান করতে পারবেন।
      </p>
    </div>
  </div>

  <form method="POST" action="<?= url('/admin/membership/grant-honorary') ?>" class="flex flex-col sm:flex-row items-center gap-3">
    <?= csrf_field() ?>
    <div class="flex-1 w-full">
      <select name="alumni_profile_id" required class="w-full px-4 py-2.5 rounded-xl bg-black/60 border border-white/20 text-white text-[13.5px] focus:outline-none focus:border-purple-400">
        <option value="">-- সম্মানিত অ্যালামনাই সদস্য নির্বাচন করুন (Select Alumni) --</option>
        <?php foreach (($allAlumni ?? []) as $alum): ?>
        <option value="<?= $alum['id'] ?>">
          <?= e($alum['name']) ?> (<?= e($alum['email']) ?>) <?= !empty($alum['batch_year']) ? ' · Batch ' . $alum['batch_year'] : '' ?>
        </option>
        <?php endforeach; ?>
      </select>
    </div>
    <button type="submit" class="w-full sm:w-auto px-6 py-2.5 rounded-xl bg-gradient-to-r from-purple-700 to-indigo-700 hover:from-purple-600 hover:to-indigo-600 text-white font-bold text-[13.5px] transition-all shadow-lg flex items-center justify-center gap-2 shrink-0">
      <i class="fa-solid fa-certificate"></i> সম্মানসূচক সদস্যপদ দিন
    </button>
  </form>
</div>

<!-- Dynamic Membership Tiers Manager -->
<div class="mb-8 space-y-4 font-['Kalpurush']">
  <div class="flex items-center justify-between">
    <h3 class="text-[16px] font-bold text-white flex items-center gap-2">
      <span>💎</span> Dynamic Membership Tiers & Pricing Manager (সদস্যপদ প্ল্যান ও ফি ম্যানেজমেন্ট)
    </h3>
    <span class="text-[12px] text-white/40 font-mono">Changes reflect live on Homepage & Portal</span>
  </div>

  <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
    <?php foreach (($typesList ?? []) as $t): ?>
    <div class="p-6 rounded-2xl bg-white/5 border border-white/10 space-y-4 relative">
      <div class="flex items-center justify-between border-b border-white/10 pb-3">
        <h4 class="font-bold text-white text-[16px]"><?= e($t['name']) ?></h4>
        <span class="text-[11px] font-mono px-2.5 py-0.5 rounded-full <?= $t['is_active'] ? 'bg-emerald-500/20 text-emerald-300' : 'bg-rose-500/20 text-rose-300' ?>">
          <?= $t['is_active'] ? 'ACTIVE' : 'INACTIVE' ?>
        </span>
      </div>

      <form method="POST" action="<?= url('/admin/membership/tier/' . $t['id'] . '/update') ?>" class="space-y-3.5 text-[13px]">
        <?= csrf_field() ?>

        <div class="grid grid-cols-2 gap-3">
          <div>
            <label class="block text-[11px] font-mono text-white/50 mb-1">Fee Amount (৳)</label>
            <input type="number" step="0.01" name="fee" value="<?= (float)$t['fee'] ?>" class="w-full px-3 py-1.5 rounded-xl bg-black/40 border border-white/10 text-white font-mono font-bold">
          </div>
          <div>
            <label class="block text-[11px] font-mono text-white/50 mb-1">Badge Text</label>
            <input type="text" name="badge_text" value="<?= e($t['badge_text'] ?? '') ?>" placeholder="e.g. MOST POPULAR" class="w-full px-3 py-1.5 rounded-xl bg-black/40 border border-white/10 text-white">
          </div>
        </div>

        <div>
          <label class="block text-[11px] font-mono text-white/50 mb-1">Button Label (বাটনের নাম)</label>
          <input type="text" name="btn_text" value="<?= e($t['btn_text'] ?? '') ?>" placeholder="e.g. Start with Annual" class="w-full px-3 py-1.5 rounded-xl bg-black/40 border border-white/10 text-white">
        </div>

        <div>
          <label class="block text-[11px] font-mono text-white/50 mb-1">Features (সুবিধাসমূহ - প্রতি লাইনে একটি)</label>
          <textarea name="features" rows="4" class="w-full px-3 py-2 rounded-xl bg-black/40 border border-white/10 text-white/90 text-[12.5px] leading-relaxed resize-none" placeholder="এক লাইনে একটি ফিচার লিখুন..."><?= e($t['features'] ?? '') ?></textarea>
        </div>

        <div class="flex items-center gap-4 pt-1 text-[12px] text-white/70">
          <label class="flex items-center gap-1.5 cursor-pointer">
            <input type="checkbox" name="is_featured" value="1" <?= !empty($t['is_featured']) ? 'checked' : '' ?> class="rounded bg-black/40 border-white/20">
            <span>Highlight Tier</span>
          </label>
          <label class="flex items-center gap-1.5 cursor-pointer">
            <input type="checkbox" name="is_active" value="1" <?= !empty($t['is_active']) ? 'checked' : '' ?> class="rounded bg-black/40 border-white/20">
            <span>Active</span>
          </label>
        </div>

        <button type="submit" class="w-full py-2 rounded-xl bg-[#800020] hover:bg-[#66001a] text-white font-bold text-[12.5px] transition-all shadow">
          💾 Save Changes
        </button>
      </form>
    </div>
    <?php endforeach; ?>
  </div>
</div>

<!-- Pending Applications -->
<div class="rounded-2xl overflow-hidden mb-8" style="background:rgba(255,255,255,0.04);border:1px solid rgba(255,255,255,0.08);">
  <div class="px-5 py-4 border-b border-white/8">
    <h3 class="text-[14px] font-semibold text-white">Pending Applications</h3>
  </div>
  <?php if (empty($pending)): ?>
  <div class="p-5 text-[13px] text-white/40 text-center">No pending membership applications.</div>
  <?php else: ?>
  <table class="w-full text-[13px]">
    <thead>
      <tr class="border-b border-white/5">
        <th class="text-left px-5 py-3.5 text-white/35 font-mono text-[11px]">Member</th>
        <th class="text-left px-5 py-3.5 text-white/35 font-mono text-[11px]">Tier</th>
        <th class="text-left px-5 py-3.5 text-white/35 font-mono text-[11px]">Member ID</th>
        <th class="text-left px-5 py-3.5 text-white/35 font-mono text-[11px]">Applied On</th>
        <th class="text-left px-5 py-3.5 text-white/35 font-mono text-[11px]">Actions</th>
      </tr>
    </thead>
    <tbody class="divide-y divide-white/5">
      <?php foreach ($pending as $p): ?>
      <tr>
        <td class="px-5 py-3.5">
          <div class="font-medium text-white"><?= e($p['name']) ?></div>
          <div class="text-[11.5px] text-white/50"><?= e($p['email']) ?></div>
        </td>
        <td class="px-5 py-3.5">
          <div class="font-medium text-white/70"><?= e($p['type_name']) ?></div>
          <?php if (!empty($p['payment_method'])): ?>
          <div class="text-[11px] text-amber-400 font-mono mt-0.5">
            <?= strtoupper(e($p['payment_method'])) ?> · <?= e($p['transaction_id']) ?>
          </div>
          <?php endif; ?>
        </td>
        <td class="px-5 py-3.5 text-white/70 font-mono"><?= e($p['membership_number']) ?></td>
        <td class="px-5 py-3.5 text-white/50"><?= date('d M Y', strtotime($p['created_at'])) ?></td>
        <td class="px-5 py-3.5 space-x-3 flex items-center">
          <?php if (!empty($p['proof_document'])): ?>
          <a href="<?= asset('storage/documents/' . e($p['proof_document'])) ?>" target="_blank" class="text-blue-400 hover:underline text-[12px]">
            View Proof
          </a>
          <?php endif; ?>
          
          <?php if (!empty($p['payment_slip'])): ?>
          <a href="<?= asset('storage/documents/' . e($p['payment_slip'])) ?>" target="_blank" class="text-amber-400 hover:underline text-[12px]">
            View Slip
          </a>
          <?php endif; ?>

          <form method="POST" action="<?= url('/admin/membership/' . $p['id'] . '/approve') ?>" class="inline">
            <?= csrf_field() ?>
            <button type="submit" class="px-3 py-1.5 rounded-lg text-[12px] bg-emerald-600/20 text-emerald-400 border border-emerald-500/30">Approve</button>
          </form>
          <form method="POST" action="<?= url('/admin/membership/' . $p['id'] . '/reject') ?>" class="inline">
            <?= csrf_field() ?>
            <button type="submit" class="px-3 py-1.5 rounded-lg text-[12px] bg-red-600/20 text-red-400 border border-red-500/30">Reject</button>
          </form>
        </td>
      </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
  <?php endif; ?>
</div>

<!-- Membership Log Dedicated Page Callout Card -->
<div class="rounded-3xl p-6 sm:p-8 bg-gradient-to-br from-white/[0.06] via-black/40 to-white/[0.02] border border-white/10 shadow-xl flex flex-col md:flex-row items-start md:items-center justify-between gap-6 font-['Kalpurush']">
  <div class="space-y-2 max-w-2xl">
    <div class="flex items-center gap-2 text-[#E58E97] font-mono text-[11px] font-bold uppercase tracking-wider">
      <i class="fa-solid fa-receipt"></i> AUDIT & FINANCIAL LOGS
    </div>
    <h3 class="font-serif text-[21px] font-bold text-white tracking-tight">
      <?= __('সদস্যপদ ও পেমেন্ট হিস্ট্রি লগ (Membership & Payment Log)', 'Membership & Payment Log') ?>
    </h3>
    <p class="text-[13.5px] text-white/60 leading-relaxed">
      <?= __('সকল সদস্যের সদস্যপদ অ্যাক্টিভেশন, মেয়াদকাল, পেমেন্ট মেথড (bKash/Nagad/UddoktaPay), ট্রানজেকশন আইডি (TrxID) এবং পেমেন্ট স্লিপের সম্পূর্ণ অডিট রেকর্ড দেখতে আলাদা ডেডিকেটেড পেজ ব্যবহার করুন।', 'View complete audit history of member subscriptions, validity, payment methods, transaction IDs, and receipts on a dedicated page.') ?>
    </p>
  </div>

  <div class="flex items-center gap-3 shrink-0 w-full md:w-auto">
    <a href="<?= url('/admin/membership/logs') ?>" class="w-full md:w-auto px-6 py-3 rounded-2xl bg-gradient-to-r from-[#800020] via-[#A22638] to-[#800020] hover:brightness-110 text-white font-bold text-[13.5px] shadow-xl shadow-[#800020]/30 transition-all flex items-center justify-center gap-2">
      <i class="fa-solid fa-receipt"></i>
      <span><?= __('সম্পূর্ণ মেম্বারশিপ ও পেমেন্ট লগ দেখুন', 'View Full Membership & Payment Log') ?> →</span>
    </a>
  </div>
</div>
