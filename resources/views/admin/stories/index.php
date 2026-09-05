<?php
/**
 * Admin Stories Index View
 * Variables: $stories
 */
?>
<div class="flex justify-between items-center mb-6">
  <div>
    <h2 class="font-serif text-[22px] font-bold text-white">ব্লগ ও আর্টিকেল ব্যবস্থাপনা (Blogs & Articles)</h2>
    <p class="text-[12.5px] text-white/50">সদস্যদের জমাকৃত এবং অ্যাডমিন কর্তৃক প্রকাশিত সকল ব্লগ পোস্ট পরিচালনা করুন</p>
  </div>
  <a href="<?= url('/admin/stories/create') ?>" class="px-5 py-2.5 rounded-xl text-[13px] font-semibold text-white transition-all shadow-md hover:-translate-y-0.5"
     style="background:linear-gradient(135deg,#A22638,#800020);">
    ✍️ + নতুন ব্লগ তৈরি করুন
  </a>
</div>

<div class="rounded-2xl overflow-hidden shadow-xl" style="background:rgba(255,255,255,0.04);border:1px solid rgba(255,255,255,0.08);">
  <table class="w-full text-[13px]">
    <thead>
      <tr class="border-b border-white/5 bg-white/[0.02]">
        <th class="text-left px-5 py-3.5 text-white/40 font-mono text-[11px] uppercase tracking-wider">শিরোনাম / পোস্ট</th>
        <th class="text-left px-5 py-3.5 text-white/40 font-mono text-[11px] uppercase tracking-wider">লেখক (Author)</th>
        <th class="text-left px-5 py-3.5 text-white/40 font-mono text-[11px] uppercase tracking-wider">ব্যাচ</th>
        <th class="text-left px-5 py-3.5 text-white/40 font-mono text-[11px] uppercase tracking-wider">Featured</th>
        <th class="text-left px-5 py-3.5 text-white/40 font-mono text-[11px] uppercase tracking-wider">স্ট্যাটাস</th>
        <th class="text-right px-5 py-3.5 text-white/40 font-mono text-[11px] uppercase tracking-wider">অ্যাকশন</th>
      </tr>
    </thead>
    <tbody class="divide-y divide-white/5">
      <?php if (empty($stories)): ?>
      <tr><td colspan="6" class="px-5 py-12 text-center text-white/40">কোনো ব্লগ পাওয়া যায়নি।</td></tr>
      <?php else: ?>
      <?php foreach ($stories as $s): ?>
      <tr class="hover:bg-white/[0.02] transition-colors">
        <td class="px-5 py-3.5">
          <div class="font-semibold text-white max-w-xs truncate"><?= e($s['title']) ?></div>
          <div class="text-[11px] text-white/40 font-mono mt-0.5"><?= date('d M, Y', strtotime($s['created_at'])) ?></div>
        </td>
        <td class="px-5 py-3.5">
          <?php if (!empty($s['author_name'])): ?>
            <div class="text-white/90 font-medium"><?= e($s['author_name']) ?></div>
            <div class="text-[11px] text-white/40"><?= e($s['author_email'] ?? '') ?></div>
          <?php else: ?>
            <span class="text-[11.5px] text-emerald-400/80 bg-emerald-950/40 border border-emerald-800/40 px-2 py-0.5 rounded">👑 Admin Post</span>
          <?php endif; ?>
        </td>
        <td class="px-5 py-3.5 text-white/70 font-mono"><?= e($s['batch_year'] ?: '—') ?></td>
        <td class="px-5 py-3.5">
          <form method="POST" action="<?= url('/admin/stories/' . $s['id'] . '/toggle-featured') ?>" class="inline">
            <?= csrf_field() ?>
            <button type="submit" class="px-2.5 py-1 rounded text-[10px] font-semibold uppercase transition-all shadow-sm <?= $s['is_featured'] ? 'bg-yellow-500/20 text-yellow-300 border border-yellow-500/40 hover:bg-yellow-500/30' : 'bg-white/5 text-white/40 border border-white/10 hover:bg-white/10 hover:text-white' ?>" title="হোমপেজে Featured হিসেবে দেখাতে ক্লিক করুন">
              <?= $s['is_featured'] ? '★ Featured' : '☆ Make Featured' ?>
            </button>
          </form>
        </td>
        <td class="px-5 py-3.5">
          <?php if ($s['status'] === 'published'): ?>
            <span class="px-2.5 py-0.5 rounded-full text-[10.5px] font-mono bg-emerald-900/40 text-emerald-300 border border-emerald-700/40">✓ PUBLISHED</span>
          <?php elseif ($s['status'] === 'pending'): ?>
            <span class="px-2.5 py-0.5 rounded-full text-[10.5px] font-mono bg-amber-900/40 text-amber-300 border border-amber-700/40 animate-pulse">⏳ PENDING</span>
          <?php elseif ($s['status'] === 'rejected'): ?>
            <span class="px-2.5 py-0.5 rounded-full text-[10.5px] font-mono bg-rose-900/40 text-rose-300 border border-rose-700/40">✕ REJECTED</span>
          <?php else: ?>
            <span class="px-2.5 py-0.5 rounded-full text-[10.5px] font-mono bg-white/10 text-white/60"><?= strtoupper(e($s['status'])) ?></span>
          <?php endif; ?>
        </td>
        <td class="px-5 py-3.5 text-right whitespace-nowrap">
          <div class="inline-flex items-center gap-1.5">
            <a href="<?= url('/admin/stories/' . $s['id'] . '/preview') ?>" class="px-2.5 py-1 rounded bg-blue-600/30 text-blue-300 border border-blue-500/30 font-semibold text-[11px] hover:bg-blue-600 hover:text-white transition-all">
              👁 প্রিভিউ
            </a>

            <?php if ($s['status'] === 'pending'): ?>
            <form method="POST" action="<?= url('/admin/stories/' . $s['id'] . '/approve') ?>" class="inline" onsubmit="return confirm('অনুমোদন করলে এটি ওয়েবসাইটে প্রকাশিত হবে এবং সকল অ্যালামনাই সদস্যকে ইমেইল অ্যালার্ট পাঠানো হবে। আপনি কি নিশ্চিত?')">
              <?= csrf_field() ?>
              <button type="submit" class="px-2.5 py-1 rounded bg-emerald-600/30 text-emerald-300 border border-emerald-500/30 font-semibold text-[11px] hover:bg-emerald-600 hover:text-white transition-all">✓ অনুমোদন</button>
            </form>
            <form method="POST" action="<?= url('/admin/stories/' . $s['id'] . '/reject') ?>" class="inline">
              <?= csrf_field() ?>
              <button type="submit" class="px-2 py-1 rounded bg-rose-900/40 text-rose-300 border border-rose-800/40 font-semibold text-[11px] hover:bg-rose-800 transition-all">✕ বাতিল</button>
            </form>
            <?php endif; ?>

            <a href="<?= url('/admin/stories/' . $s['id'] . '/edit') ?>" class="px-2.5 py-1 rounded bg-white/10 text-white/90 border border-white/10 font-semibold text-[11px] hover:bg-[#800020] hover:text-white hover:border-[#800020] transition-all">
              ✏️ এডিট
            </a>

            <form method="POST" action="<?= url('/admin/stories/' . $s['id'] . '/delete') ?>" class="inline" onsubmit="return confirm('আপনি কি নিশ্চিত যে এই ব্লগটি মুছে ফেলতে চান?')">
              <?= csrf_field() ?>
              <button type="submit" class="px-2.5 py-1 rounded bg-rose-950/40 text-rose-400 border border-rose-800/40 font-semibold text-[11px] hover:bg-rose-600 hover:text-white transition-all">
                🗑️ ডিলিট
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
