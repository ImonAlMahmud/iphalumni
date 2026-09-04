<?php
/**
 * Admin Contact Requests Monitoring View
 * Variables: $requests
 */
?>
<div class="space-y-6 font-['Kalpurush']">
  <div class="flex items-center justify-between gap-4">
    <div>
      <h2 class="text-[18px] font-bold text-white mb-1">💬 Alumni Contact Requests Log (যোগাযোগের অনুরোধ মনিটরিং)</h2>
      <p class="text-[13px] text-white/50">পাবলিক ডিরেক্টরি থেকে কোন সদস্যের নিকট কোন বিষয়াধীনে যোগাযোগের অনুরোধ আসছে এবং সদস্য কী উত্তর দিচ্ছেন তা পর্যবেক্ষণ করুন।</p>
    </div>
    <div class="px-3 py-1.5 rounded-xl bg-white/10 text-white font-mono text-[12.5px] border border-white/10">
      Total Requests: <?= count($requests) ?>
    </div>
  </div>

  <div class="rounded-2xl overflow-hidden border border-white/10 bg-white/5 shadow-xl">
    <table class="w-full text-[13px]">
      <thead>
        <tr class="border-b border-white/10 bg-black/30 text-white/40 font-mono text-[11px] uppercase">
          <th class="text-left px-5 py-3.5">Target Alumni (সদস্য)</th>
          <th class="text-left px-5 py-3.5">Requester (অনুরোধকারী)</th>
          <th class="text-left px-5 py-3.5">Topic & Brief (বিষয় ও সংক্ষেপ)</th>
          <th class="text-left px-5 py-3.5">Status & Shared Info</th>
          <th class="text-left px-5 py-3.5">Requested Date</th>
        </tr>
      </thead>
      <tbody class="divide-y divide-white/5">
        <?php if (empty($requests)): ?>
        <tr><td colspan="5" class="px-5 py-8 text-center text-white/40">No contact requests recorded.</td></tr>
        <?php else: ?>
        <?php foreach ($requests as $r): ?>
        <tr class="hover:bg-white/[0.02]">
          <td class="px-5 py-4">
            <span class="font-bold text-white block text-[14px]"><?= e($r['alumni_name']) ?></span>
            <span class="text-[11.5px] text-rose-300 font-mono block"><?= e($r['alumni_email']) ?></span>
            <?php if (!empty($r['batch_year'])): ?>
            <span class="inline-block mt-1 text-[10px] font-mono px-2 py-0.5 rounded bg-white/10 text-white/70">Batch <?= e($r['batch_year']) ?></span>
            <?php endif; ?>
          </td>
          <td class="px-5 py-4">
            <span class="font-semibold text-white block"><?= e($r['requester_name']) ?></span>
            <span class="text-[12px] text-blue-300 font-mono block"><?= e($r['requester_email']) ?></span>
            <?php if (!empty($r['requester_phone'])): ?>
            <span class="text-[11.5px] text-white/50 font-mono block">📞 <?= e($r['requester_phone']) ?></span>
            <?php endif; ?>
          </td>
          <td class="px-5 py-4 max-w-xs">
            <span class="font-bold text-[#E58E97] block text-[13.5px] mb-1"><?= e($r['discussion_topic']) ?></span>
            <p class="text-[12px] text-white/70 bg-black/40 p-2.5 rounded-xl border border-white/5 line-clamp-3 leading-relaxed"><?= e($r['brief_message']) ?></p>
          </td>
          <td class="px-5 py-4">
            <span class="inline-block px-2.5 py-0.5 rounded-full text-[10.5px] font-mono font-bold uppercase mb-2 <?= $r['status'] === 'accepted' ? 'bg-emerald-500/20 text-emerald-300 border border-emerald-500/30' : ($r['status'] === 'rejected' ? 'bg-rose-500/20 text-rose-300 border border-rose-500/30' : 'bg-amber-500/20 text-amber-300 border border-amber-500/30') ?>">
              <?= strtoupper($r['status']) ?>
            </span>
            <?php if ($r['status'] === 'accepted'): ?>
            <div class="text-[11.5px] text-white/80 space-y-0.5 font-mono bg-emerald-950/40 p-2 rounded-lg border border-emerald-500/20">
              <div><strong class="text-emerald-400">Method:</strong> <?= e($r['accepted_contact_method']) ?></div>
              <div><strong class="text-emerald-400">Shared:</strong> <?= e($r['accepted_contact_details']) ?></div>
              <?php if (!empty($r['instruction_note'])): ?>
              <div class="text-[10.5px] text-white/60 italic">"<?= e($r['instruction_note']) ?>"</div>
              <?php endif; ?>
            </div>
            <?php endif; ?>
          </td>
          <td class="px-5 py-4 text-white/40 font-mono text-[11.5px]">
            <?= date('d M Y', strtotime($r['created_at'])) ?><br>
            <span class="text-[10px] text-white/30"><?= date('h:i A', strtotime($r['created_at'])) ?></span>
          </td>
        </tr>
        <?php endforeach; ?>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>
