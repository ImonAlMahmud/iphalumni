<?php
/**
 * Admin Membership & Payment Log View
 * Variables: $memberships, $stats, $typesList, $search, $status, $typeId, $method, $pStatus, $page, $totalPages, $totalRecords
 */
?>
<div class="max-w-7xl mx-auto py-6 font-['Kalpurush','Inter',sans-serif]" x-data="{ proofModalOpen: false, proofUrl: '', proofTitle: '' }">

  <!-- Header & Breadcrumb -->
  <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
    <div>
      <div class="flex items-center gap-2 mb-1">
        <a href="<?= url('/admin/membership') ?>" class="text-[12px] font-mono text-white/50 hover:text-white transition-colors">
          <i class="fa-solid fa-arrow-left mr-1"></i> Membership Plans
        </a>
        <span class="text-white/30 text-[10px]">/</span>
        <span class="text-[11px] font-mono font-bold text-[#E58E97] uppercase tracking-wider">
          AUDIT & FINANCIAL LOGS
        </span>
      </div>
      <h1 class="font-serif text-[26px] font-bold text-white tracking-tight flex items-center gap-2.5">
        <i class="fa-solid fa-receipt text-[#E58E97]"></i>
        <?= __('মেম্বারশিপ ও পেমেন্ট হিস্ট্রি লগ', 'Membership & Payment Log') ?>
      </h1>
      <p class="text-[13px] text-white/60 mt-0.5">
        <?= __('সকল সদস্যের মেম্বারশিপ স্ট্যাটাস, লেনদেন আইডি ও পেমেন্ট সংক্রান্ত বিস্তারিত রেকর্ড।', 'Complete records of member subscriptions, transactions, and payment receipts.') ?>
      </p>
    </div>

    <div class="flex items-center gap-2 flex-wrap">
      <a href="<?= url('/admin/membership') ?>" class="px-3.5 py-2 rounded-xl bg-white/10 hover:bg-white/20 text-white text-[12.5px] font-medium transition-all flex items-center gap-1.5">
        <i class="fa-solid fa-sliders text-[11px]"></i> Plans & Pricing
      </a>
      <a href="<?= url('/admin/membership/logs/export/csv?' . http_build_query($_GET)) ?>" class="px-3.5 py-2 rounded-xl bg-white/10 hover:bg-white/20 text-white border border-white/10 text-[12.5px] font-medium transition-all shadow-sm flex items-center gap-1.5" title="Export filtered records to CSV / Excel">
        <i class="fa-solid fa-file-csv text-[13px] text-[#E58E97]"></i> Export CSV
      </a>
      <a href="<?= url('/admin/membership/logs/export/pdf?' . http_build_query($_GET)) ?>" target="_blank" class="px-3.5 py-2 rounded-xl bg-white/10 hover:bg-white/20 text-white border border-white/10 text-[12.5px] font-medium transition-all shadow-sm flex items-center gap-1.5" title="Printable report / Save as PDF">
        <i class="fa-solid fa-file-pdf text-[13px] text-[#E58E97]"></i> Export PDF
      </a>
      <button type="button" onclick="window.print()" class="px-3.5 py-2 rounded-xl bg-[#800020] hover:bg-[#990026] text-white text-[12.5px] font-semibold transition-all shadow-md flex items-center gap-1.5">
        <i class="fa-solid fa-print text-[11px]"></i> Print
      </button>
    </div>
  </div>

  <!-- Stats Grid -->
  <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    <div class="p-5 rounded-2xl bg-white/5 border border-white/10 relative overflow-hidden">
      <div class="text-[11px] font-mono uppercase tracking-wider text-white/50 mb-1">Total Memberships</div>
      <div class="font-serif text-[26px] font-bold text-white"><?= number_format($stats['total'] ?? 0) ?></div>
      <div class="text-[11px] text-white/40 mt-1">সকল নিবন্ধিত সদস্যপদ</div>
      <i class="fa-solid fa-id-card-clip absolute -right-2 -bottom-2 text-white/5 text-5xl pointer-events-none"></i>
    </div>

    <div class="p-5 rounded-2xl bg-white/5 border border-white/10 relative overflow-hidden">
      <div class="text-[11px] font-mono uppercase tracking-wider text-emerald-400 mb-1">Active Memberships</div>
      <div class="font-serif text-[26px] font-bold text-emerald-400"><?= number_format($stats['active'] ?? 0) ?></div>
      <div class="text-[11px] text-emerald-400/60 mt-1">সক্রিয় ভেরিফাইড মেম্বার</div>
      <i class="fa-solid fa-circle-check absolute -right-2 -bottom-2 text-emerald-500/5 text-5xl pointer-events-none"></i>
    </div>

    <div class="p-5 rounded-2xl bg-white/5 border border-white/10 relative overflow-hidden">
      <div class="text-[11px] font-mono uppercase tracking-wider text-[#E58E97] mb-1">Total Payments (৳)</div>
      <div class="font-serif text-[26px] font-bold text-[#E58E97]">৳ <?= number_format($stats['total_payments'] ?? 0) ?></div>
      <div class="text-[11px] text-white/40 mt-1">মোট সংগ্রহকৃত ফি</div>
      <i class="fa-solid fa-bangladeshi-taka-sign absolute -right-2 -bottom-2 text-rose-500/5 text-5xl pointer-events-none"></i>
    </div>

    <div class="p-5 rounded-2xl bg-white/5 border border-white/10 relative overflow-hidden">
      <div class="text-[11px] font-mono uppercase tracking-wider text-amber-400 mb-1">Pending Approvals</div>
      <div class="font-serif text-[26px] font-bold text-amber-400"><?= number_format($stats['pending'] ?? 0) ?></div>
      <div class="text-[11px] text-amber-400/60 mt-1">অনুমোদনের অপেক্ষায়</div>
      <i class="fa-solid fa-clock absolute -right-2 -bottom-2 text-amber-500/5 text-5xl pointer-events-none"></i>
    </div>
  </div>

  <!-- Filter & Search Bar -->
  <div class="p-5 rounded-2xl bg-white/5 border border-white/10 mb-6 font-['Kalpurush']">
    <form method="GET" action="<?= url('/admin/membership/logs') ?>" class="space-y-4">
      
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3">
        <!-- Search Input -->
        <div class="lg:col-span-2">
          <label class="block text-[11px] font-mono text-white/50 mb-1">সার্চ করুন (Search Member / TrxID / Email)</label>
          <div class="relative">
            <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-3 text-white/40 text-[12px]"></i>
            <input type="text" name="q" value="<?= e($search) ?>" 
                   placeholder="নাম, ইমেইল, ফোন, মেম্বার আইডি বা TrxID..."
                   class="w-full pl-9 pr-4 py-2 rounded-xl bg-black/50 border border-white/15 text-white text-[13px] focus:outline-none focus:border-[#E58E97]">
          </div>
        </div>

        <!-- Membership Status Filter -->
        <div>
          <label class="block text-[11px] font-mono text-white/50 mb-1">মেম্বারশিপ স্ট্যাটাস</label>
          <select name="status" class="w-full px-3 py-2 rounded-xl bg-black/50 border border-white/15 text-white text-[13px] focus:outline-none focus:border-[#E58E97]">
            <option value="all" <?= ($status === '' || $status === 'all') ? 'selected' : '' ?>>সকল স্ট্যাটাস (All Status)</option>
            <option value="active" <?= $status === 'active' ? 'selected' : '' ?>>Active (সক্রিয়)</option>
            <option value="pending" <?= $status === 'pending' ? 'selected' : '' ?>>Pending (অপেক্ষমান)</option>
            <option value="expired" <?= $status === 'expired' ? 'selected' : '' ?>>Expired (মেয়াদোত্তীর্ণ)</option>
            <option value="rejected" <?= $status === 'rejected' ? 'selected' : '' ?>>Rejected (বাতিলকৃত)</option>
            <option value="cancelled" <?= $status === 'cancelled' ? 'selected' : '' ?>>Cancelled</option>
          </select>
        </div>

        <!-- Membership Tier Filter -->
        <div>
          <label class="block text-[11px] font-mono text-white/50 mb-1">মেম্বারশিপ টাইয়ার (Tier)</label>
          <select name="type" class="w-full px-3 py-2 rounded-xl bg-black/50 border border-white/15 text-white text-[13px] focus:outline-none focus:border-[#E58E97]">
            <option value="0">সকল প্ল্যান (All Tiers)</option>
            <?php foreach ($typesList as $t): ?>
            <option value="<?= $t['id'] ?>" <?= $typeId === (int)$t['id'] ? 'selected' : '' ?>>
              <?= e($t['name']) ?> (৳ <?= number_format((float)$t['fee']) ?>)
            </option>
            <?php endforeach; ?>
          </select>
        </div>

        <!-- Payment Method Filter -->
        <div>
          <label class="block text-[11px] font-mono text-white/50 mb-1">পেমেন্ট মেথড (Payment)</label>
          <select name="method" class="w-full px-3 py-2 rounded-xl bg-black/50 border border-white/15 text-white text-[13px] focus:outline-none focus:border-[#E58E97]">
            <option value="all" <?= ($method === '' || $method === 'all') ? 'selected' : '' ?>>সকল মেথড (All)</option>
            <option value="uddoktapay" <?= $method === 'uddoktapay' ? 'selected' : '' ?>>UddoktaPay (Online)</option>
            <option value="bkash" <?= $method === 'bkash' ? 'selected' : '' ?>>bKash (বিকাশ)</option>
            <option value="nagad" <?= $method === 'nagad' ? 'selected' : '' ?>>Nagad (নগদ)</option>
            <option value="rocket" <?= $method === 'rocket' ? 'selected' : '' ?>>Rocket (রকেট)</option>
            <option value="bank" <?= $method === 'bank' ? 'selected' : '' ?>>Bank Transfer</option>
            <option value="free" <?= $method === 'free' ? 'selected' : '' ?>>Free / Admin Granted</option>
          </select>
        </div>
      </div>

      <!-- Action Buttons & Match count -->
      <div class="flex items-center justify-between pt-2 border-t border-white/5">
        <div class="text-[12px] text-white/50 font-mono">
          মোট <span class="text-[#E58E97] font-bold"><?= number_format($totalRecords) ?></span> টি মেম্বারশিপ রেকর্ড পাওয়া গেছে
        </div>
        <div class="flex items-center gap-2">
          <a href="<?= url('/admin/membership/logs') ?>" class="px-3.5 py-1.5 rounded-xl bg-white/5 hover:bg-white/10 text-white/70 text-[12.5px] transition-all">
            রিসেট (Reset)
          </a>
          <button type="submit" class="px-5 py-1.5 rounded-xl bg-[#800020] hover:bg-[#990026] text-white text-[12.5px] font-bold shadow-md transition-all flex items-center gap-1.5">
            <i class="fa-solid fa-filter text-[11px]"></i> ফিল্টার করুন
          </button>
        </div>
      </div>

    </form>
  </div>

  <!-- Membership & Payment Log Table -->
  <div class="rounded-3xl bg-white/5 border border-white/10 overflow-hidden shadow-xl">
    <div class="overflow-x-auto">
      <table class="w-full text-left border-collapse text-[13px]">
        <thead>
          <tr class="border-b border-white/10 bg-black/40 text-white/50 font-mono text-[11px] uppercase tracking-wider">
            <th class="p-4">Alumni Member</th>
            <th class="p-4">Membership & Tier</th>
            <th class="p-4">Status & Validity</th>
            <th class="p-4">Payment & Transaction</th>
            <th class="p-4 text-right">Actions</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-white/5 text-slate-200">
          <?php if (empty($memberships)): ?>
          <tr>
            <td colspan="5" class="p-12 text-center text-white/40">
              <i class="fa-solid fa-receipt text-3xl text-white/20 mb-2 block"></i>
              কোনো মেম্বারশিপ বা পেমেন্ট রেকর্ড পাওয়া যায়নি।
            </td>
          </tr>
          <?php else: ?>
            <?php foreach ($memberships as $m): 
              // Membership status badges
              $mStatus = strtolower($m['status'] ?? 'pending');
              $mStatusClasses = [
                'active'    => 'bg-emerald-500/20 text-emerald-300 border-emerald-500/30',
                'pending'   => 'bg-amber-500/20 text-amber-300 border-amber-500/30',
                'expired'   => 'bg-white/10 text-white/50 border-white/10',
                'rejected'  => 'bg-rose-500/20 text-rose-300 border-rose-500/30',
                'cancelled' => 'bg-rose-500/20 text-rose-300 border-rose-500/30',
              ];
              $statusBadge = $mStatusClasses[$mStatus] ?? 'bg-white/10 text-white/50 border-white/10';

              // Payment status badges
              $pStatus = strtolower($m['payment_status'] ?? '');
              $amount  = (float)($m['payment_amount'] ?? ($m['type_fee'] ?? 0));
              $currency = $m['payment_currency'] ?? 'BDT';
              $methodName = strtolower($m['payment_method'] ?? '');

              // Determine proof file
              $proofFile = !empty($m['payment_slip']) ? $m['payment_slip'] : (!empty($m['proof_document']) ? $m['proof_document'] : (!empty($m['profile_proof']) ? $m['profile_proof'] : null));
              $proofUrl  = $proofFile ? asset('storage/documents/' . $proofFile) : null;
              $memNo     = (!empty($m['membership_number']) && str_starts_with($m['membership_number'], 'IPHAA-')) ? $m['membership_number'] : ('IPHAA-' . str_pad((string)($m['alumni_profile_id'] ?? $m['id']), 5, '0', STR_PAD_LEFT));
            ?>
            <tr class="hover:bg-white/[0.02] transition-colors">
              
              <!-- 1. Alumni Member Info -->
              <td class="p-4 align-top">
                <div class="flex items-start gap-3">
                  <div class="w-9 h-9 rounded-full overflow-hidden bg-gradient-to-br from-[#800020] to-[#2F8863] flex items-center justify-center font-bold text-[12px] text-white shrink-0 mt-0.5 shadow-sm">
                    <?php if (!empty($m['profile_avatar'])): ?>
                      <img src="<?= asset('storage/avatars/' . e($m['profile_avatar'])) ?>" alt="Avatar" class="w-full h-full object-cover">
                    <?php elseif (!empty($m['user_avatar'])): ?>
                      <img src="<?= asset('storage/avatars/' . e($m['user_avatar'])) ?>" alt="Avatar" class="w-full h-full object-cover">
                    <?php else: ?>
                      <?= initials($m['name'] ?? 'M') ?>
                    <?php endif; ?>
                  </div>
                  <div>
                    <div class="font-bold text-white text-[14px]">
                      <a href="<?= url('/admin/alumni/' . $m['alumni_profile_id']) ?>" class="hover:text-[#E58E97] transition-colors">
                        <?= e($m['name']) ?>
                      </a>
                    </div>
                    <div class="text-[12px] text-white/60"><?= e($m['email']) ?></div>
                    <div class="text-[11px] font-mono text-white/40 mt-0.5">
                      <?= !empty($m['phone']) ? e($m['phone']) : 'No phone' ?>
                      <?= !empty($m['batch_year']) ? ' · Batch: ' . e($m['batch_year']) : '' ?>
                    </div>
                  </div>
                </div>
              </td>

              <!-- 2. Membership ID & Tier -->
              <td class="p-4 align-top">
                <div class="font-mono text-[13px] font-bold text-[#E58E97] tracking-wide flex items-center gap-1.5">
                  <span><?= e($memNo) ?></span>
                  <button type="button" 
                          onclick="navigator.clipboard.writeText('<?= e($memNo) ?>'); alert('Member ID Copied: <?= e($memNo) ?>')" 
                          title="Copy Member ID" 
                          class="text-white/30 hover:text-white text-[11px]">
                    <i class="fa-regular fa-copy"></i>
                  </button>
                </div>
                
                <div class="mt-1 flex items-center gap-2">
                  <?php if (stripos($m['type_name'], 'honorary') !== false): ?>
                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full bg-purple-500/20 text-purple-300 border border-purple-500/30 text-[11px] font-bold font-mono">
                      <i class="fa-solid fa-award text-[10px]"></i> <?= e($m['type_name']) ?>
                    </span>
                  <?php elseif (stripos($m['type_name'], 'lifetime') !== false): ?>
                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full bg-indigo-500/20 text-indigo-300 border border-indigo-500/30 text-[11px] font-bold font-mono">
                      <i class="fa-solid fa-crown text-[10px]"></i> <?= e($m['type_name']) ?>
                    </span>
                  <?php else: ?>
                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full bg-rose-500/20 text-rose-300 border border-rose-500/30 text-[11px] font-bold font-mono">
                      <i class="fa-solid fa-calendar text-[10px]"></i> <?= e($m['type_name']) ?>
                    </span>
                  <?php endif; ?>
                </div>

                <div class="text-[11px] text-white/40 font-mono mt-1">
                  Plan Fee: ৳ <?= number_format((float)($m['type_fee'] ?? 0)) ?>
                </div>
              </td>

              <!-- 3. Status & Validity Dates -->
              <td class="p-4 align-top">
                <div>
                  <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full border text-[11px] font-bold font-mono uppercase <?= $statusBadge ?>">
                    <span class="w-1.5 h-1.5 rounded-full <?= $mStatus === 'active' ? 'bg-emerald-400' : ($mStatus === 'pending' ? 'bg-amber-400 animate-pulse' : 'bg-rose-400') ?>"></span>
                    <?= e($m['status']) ?>
                  </span>
                </div>

                <div class="mt-2 text-[11.5px] font-mono text-white/70">
                  <div>
                    <span class="text-white/40">From:</span> <?= $m['start_date'] ? date('d M Y', strtotime($m['start_date'])) : '—' ?>
                  </div>
                  <div>
                    <span class="text-white/40">Thru:</span> 
                    <span class="<?= empty($m['end_date']) ? 'text-indigo-300 font-semibold' : '' ?>">
                      <?= $m['end_date'] ? date('d M Y', strtotime($m['end_date'])) : 'Lifetime (আজীবন)' ?>
                    </span>
                  </div>
                </div>

                <?php if (!empty($m['approved_at'])): ?>
                <div class="text-[10.5px] text-white/40 mt-1">
                  Approved: <?= date('d M Y', strtotime($m['approved_at'])) ?>
                </div>
                <?php endif; ?>
              </td>

              <!-- 4. Payment & Transaction Details -->
              <td class="p-4 align-top">
                <!-- Amount & Currency -->
                <div class="flex items-center gap-2">
                  <span class="font-bold text-[14px] text-white font-mono">
                    ৳ <?= number_format($amount) ?> <span class="text-[11px] text-white/50"><?= e($currency) ?></span>
                  </span>

                  <!-- Payment Status Badge -->
                  <?php if ($pStatus === 'paid' || $mStatus === 'active'): ?>
                    <span class="px-2 py-0.5 rounded bg-emerald-950/80 text-emerald-300 border border-emerald-800/60 font-mono text-[10px] font-bold">
                      <i class="fa-solid fa-check text-[9px] mr-0.5"></i> PAID
                    </span>
                  <?php elseif ($pStatus === 'pending' || $mStatus === 'pending'): ?>
                    <span class="px-2 py-0.5 rounded bg-amber-950/80 text-amber-300 border border-amber-800/60 font-mono text-[10px] font-bold">
                      <i class="fa-solid fa-clock text-[9px] mr-0.5"></i> PENDING
                    </span>
                  <?php else: ?>
                    <span class="px-2 py-0.5 rounded bg-white/10 text-white/50 border border-white/10 font-mono text-[10px]">
                      <?= strtoupper($pStatus ?: 'UNPAID') ?>
                    </span>
                  <?php endif; ?>
                </div>

                <!-- Payment Method Badge -->
                <div class="mt-1.5 flex items-center gap-1.5 flex-wrap">
                  <?php if ($methodName === 'uddoktapay'): ?>
                    <span class="px-2 py-0.5 rounded-md bg-sky-500/20 text-sky-300 border border-sky-500/30 font-mono text-[10.5px]">
                      <i class="fa-solid fa-bolt text-[9.5px]"></i> UddoktaPay
                    </span>
                  <?php elseif ($methodName === 'bkash'): ?>
                    <span class="px-2 py-0.5 rounded-md bg-pink-500/20 text-pink-300 border border-pink-500/30 font-mono text-[10.5px]">
                      bKash
                    </span>
                  <?php elseif ($methodName === 'nagad'): ?>
                    <span class="px-2 py-0.5 rounded-md bg-orange-500/20 text-orange-300 border border-orange-500/30 font-mono text-[10.5px]">
                      Nagad
                    </span>
                  <?php elseif ($methodName === 'rocket'): ?>
                    <span class="px-2 py-0.5 rounded-md bg-purple-500/20 text-purple-300 border border-purple-500/30 font-mono text-[10.5px]">
                      Rocket
                    </span>
                  <?php elseif ($methodName === 'bank'): ?>
                    <span class="px-2 py-0.5 rounded-md bg-emerald-500/20 text-emerald-300 border border-emerald-500/30 font-mono text-[10.5px]">
                      Bank Transfer
                    </span>
                  <?php elseif (empty($methodName) || $methodName === 'free'): ?>
                    <span class="px-2 py-0.5 rounded-md bg-white/10 text-white/60 font-mono text-[10.5px]">
                      <?= $amount > 0 ? 'Manual / Offline' : 'Admin / Free' ?>
                    </span>
                  <?php else: ?>
                    <span class="px-2 py-0.5 rounded-md bg-white/10 text-white/60 font-mono text-[10.5px]">
                      <?= e(ucfirst($methodName)) ?>
                    </span>
                  <?php endif; ?>

                  <!-- Payment Date if available -->
                  <?php if (!empty($m['payment_date'])): ?>
                    <span class="text-[10.5px] text-white/40 font-mono">
                      (<?= date('d M Y', strtotime($m['payment_date'])) ?>)
                    </span>
                  <?php endif; ?>
                </div>

                <!-- Transaction ID (TrxID) -->
                <?php if (!empty($m['transaction_id'])): ?>
                <div class="mt-1.5 font-mono text-[11.5px] text-emerald-400/90 flex items-center gap-1.5">
                  <span class="text-white/40">TrxID:</span>
                  <span class="font-bold select-all"><?= e($m['transaction_id']) ?></span>
                  <button type="button" 
                          onclick="navigator.clipboard.writeText('<?= e($m['transaction_id']) ?>'); alert('TrxID Copied: <?= e($m['transaction_id']) ?>')"
                          title="Copy Transaction ID" 
                          class="text-white/30 hover:text-white text-[10px]">
                    <i class="fa-regular fa-copy"></i>
                  </button>
                </div>
                <?php else: ?>
                <div class="mt-1 text-[11px] font-mono text-white/30 italic">
                  No TrxID recorded
                </div>
                <?php endif; ?>

                <!-- Document / Receipt Slip Link -->
                <?php if ($proofUrl): ?>
                <div class="mt-2">
                  <a href="<?= $proofUrl ?>" target="_blank" 
                     @click.prevent="proofUrl = '<?= $proofUrl ?>'; proofTitle = 'Receipt: <?= e($m['name']) ?> (<?= e($memNo) ?>)'; proofModalOpen = true"
                     class="inline-flex items-center gap-1 text-[11px] font-mono text-sky-400 hover:text-sky-300 hover:underline">
                    <i class="fa-solid fa-file-invoice text-[10px]"></i> View Receipt / Slip
                  </a>
                </div>
                <?php endif; ?>
              </td>

              <!-- 5. Actions -->
              <td class="p-4 align-top text-right space-y-2">
                
                <?php if ($mStatus === 'pending'): ?>
                  <!-- Approve Button Form -->
                  <form method="POST" action="<?= url('/admin/membership/' . $m['id'] . '/approve') ?>" class="inline-block" onsubmit="return confirm('Approve this membership and mark as active?')">
                    <?= csrf_field() ?>
                    <button type="submit" class="px-3 py-1.5 rounded-xl text-[12px] font-semibold bg-emerald-700 hover:bg-emerald-600 text-white transition-all flex items-center gap-1 shadow-sm">
                      <i class="fa-solid fa-check text-[10px]"></i> Approve
                    </button>
                  </form>

                  <!-- Reject Button Form -->
                  <form method="POST" action="<?= url('/admin/membership/' . $m['id'] . '/reject') ?>" class="inline-block" onsubmit="return confirm('Reject this membership application?')">
                    <?= csrf_field() ?>
                    <button type="submit" class="px-3 py-1.5 rounded-xl text-[12px] font-medium bg-rose-950/70 hover:bg-rose-900 text-rose-300 border border-rose-800/60 transition-all flex items-center gap-1">
                      <i class="fa-solid fa-xmark text-[10px]"></i> Reject
                    </button>
                  </form>
                <?php endif; ?>

                <?php if ($mStatus === 'active'): ?>
                  <!-- Revoke / Delete Form -->
                  <form method="POST" action="<?= url('/admin/membership/' . $m['id'] . '/delete') ?>" class="inline-block" onsubmit="return confirm('Are you sure you want to revoke/remove this active membership?')">
                    <?= csrf_field() ?>
                    <button type="submit" class="px-3 py-1.5 rounded-xl text-[11.5px] font-medium text-red-300 hover:text-red-200 bg-red-950/40 hover:bg-red-900/60 border border-red-800/40 transition-all flex items-center gap-1 ml-auto">
                      <i class="fa-solid fa-trash-can text-[10px]"></i> Revoke
                    </button>
                  </form>
                <?php endif; ?>

                <div class="pt-2 mt-1 border-t border-white/5 flex flex-col items-end gap-1.5 font-mono text-[11px]">
                  <a href="<?= url('/admin/alumni/' . $m['alumni_profile_id'] . '/edit') ?>" class="text-white/70 hover:text-white inline-flex items-center gap-1 font-medium transition-colors">
                    <i class="fa-solid fa-user-pen text-[10px] text-[#E58E97]"></i> Edit Profile
                  </a>
                  <a href="<?= url('/admin/alumni/' . $m['alumni_profile_id'] . '/id-card') ?>" target="_blank" class="text-white/70 hover:text-white inline-flex items-center gap-1 transition-colors">
                    <i class="fa-solid fa-id-card text-[10px] text-[#E58E97]"></i> Member Card
                  </a>
                  <a href="<?= url('/admin/alumni/' . $m['alumni_profile_id'] . '/membership-card') ?>" target="_blank" class="text-white/70 hover:text-white inline-flex items-center gap-1 transition-colors">
                    <i class="fa-solid fa-qrcode text-[10px] text-[#E58E97]"></i> Membership Card
                  </a>
                  <a href="<?= url('/admin/alumni/' . $m['alumni_profile_id']) ?>" class="text-white/40 hover:text-white inline-flex items-center gap-1 transition-colors">
                    Full Profile <i class="fa-solid fa-arrow-up-right-from-square text-[8.5px]"></i>
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
    <?php if ($totalPages > 1): ?>
    <div class="p-4 border-t border-white/10 bg-black/20 flex flex-col sm:flex-row items-center justify-between gap-3 text-[13px] font-mono">
      <div class="text-white/50 text-[12px]">
        Showing Page <span class="text-white font-bold"><?= $page ?></span> of <span class="text-white font-bold"><?= $totalPages ?></span>
      </div>

      <div class="flex items-center gap-1.5">
        <?php if ($page > 1): ?>
        <a href="<?= url('/admin/membership/logs?' . http_build_query(array_merge($_GET, ['page' => $page - 1]))) ?>" 
           class="px-3 py-1.5 rounded-xl bg-white/10 hover:bg-white/20 text-white text-[12px] transition-all flex items-center gap-1">
          <i class="fa-solid fa-arrow-left text-[10px]"></i> Prev
        </a>
        <?php endif; ?>

        <?php for ($p = max(1, $page - 2); $p <= min($totalPages, $page + 2); $p++): ?>
        <a href="<?= url('/admin/membership/logs?' . http_build_query(array_merge($_GET, ['page' => $p]))) ?>" 
           class="w-8 h-8 rounded-xl flex items-center justify-center text-[12px] font-bold transition-all <?= $p === $page ? 'bg-[#800020] text-white' : 'bg-white/5 hover:bg-white/10 text-white/70' ?>">
          <?= $p ?>
        </a>
        <?php endfor; ?>

        <?php if ($page < $totalPages): ?>
        <a href="<?= url('/admin/membership/logs?' . http_build_query(array_merge($_GET, ['page' => $page + 1]))) ?>" 
           class="px-3 py-1.5 rounded-xl bg-white/10 hover:bg-white/20 text-white text-[12px] transition-all flex items-center gap-1">
          Next <i class="fa-solid fa-arrow-right text-[10px]"></i>
        </a>
        <?php endif; ?>
      </div>
    </div>
    <?php endif; ?>

  </div>

  <!-- Modal for Document / Payment Slip Preview -->
  <div x-show="proofModalOpen" 
       x-transition:enter="transition ease-out duration-200"
       x-transition:enter-start="opacity-0"
       x-transition:enter-end="opacity-100"
       x-transition:leave="transition ease-in duration-150"
       x-transition:leave-start="opacity-100"
       x-transition:leave-end="opacity-0"
       class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/80 backdrop-blur-md"
       style="display: none;">
    
    <div class="relative w-full max-w-3xl rounded-3xl bg-[#111622] border border-white/20 shadow-2xl overflow-hidden flex flex-col max-h-[90vh]"
         @click.outside="proofModalOpen = false">
      
      <!-- Modal Header -->
      <div class="p-4 border-b border-white/10 flex items-center justify-between bg-white/[0.02]">
        <h3 class="text-white font-bold text-[14px]" x-text="proofTitle"></h3>
        <button type="button" @click="proofModalOpen = false" class="text-white/50 hover:text-white text-lg">
          <i class="fa-solid fa-xmark"></i>
        </button>
      </div>

      <!-- Modal Body (Image or Iframe) -->
      <div class="p-4 overflow-y-auto flex-1 flex items-center justify-center bg-black/40">
        <template x-if="proofUrl.endsWith('.pdf')">
          <iframe :src="proofUrl" class="w-full h-[500px] rounded-xl border border-white/10"></iframe>
        </template>
        <template x-if="!proofUrl.endsWith('.pdf')">
          <img :src="proofUrl" alt="Payment Proof" class="max-w-full max-h-[500px] object-contain rounded-xl border border-white/10 shadow-lg">
        </template>
      </div>

      <!-- Modal Footer -->
      <div class="p-3 border-t border-white/10 flex items-center justify-between text-[12px] bg-black/50">
        <a :href="proofUrl" target="_blank" class="text-[#E58E97] hover:underline font-mono">
          <i class="fa-solid fa-arrow-up-right-from-square mr-1"></i> Open Original in New Tab
        </a>
        <button type="button" @click="proofModalOpen = false" class="px-4 py-1.5 rounded-xl bg-white/10 hover:bg-white/20 text-white font-medium">
          Close
        </button>
      </div>
    </div>
  </div>

</div>
