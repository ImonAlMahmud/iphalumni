<?php
/**
 * Alumni Portal — Association Funds Deposit Page
 * Variables: $totalFunds, $membershipFunds, $otherFunds, $funds
 */
$activeTab = 'funds';
?>
<div class="w-full space-y-6">
  <!-- Page Header -->
  <div class="mb-6 flex justify-between items-center flex-wrap gap-4">
    <div>
      <span class="font-mono text-[11px] tracking-widest text-[#800020] uppercase block mb-1">
        💰 <?= __('অ্যাসোসিয়েশন ফান্ড ম্যানেজমেন্ট', 'ASSOCIATION FUNDS DEPOSIT') ?>
      </span>
      <h1 class="font-serif text-[28px] font-bold text-[#101820]"><?= __('সংগৃহীত তহবিল ও জমা ব্যবস্থাপনা', 'Funds & Deposits Management') ?></h1>
    </div>
    
    <!-- Report Action Buttons -->
    <div class="flex items-center gap-3">
      <a href="<?= url('/portal/financials/funds/export/excel') ?>" 
         class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl text-[13px] font-semibold text-emerald-800 bg-emerald-50 border border-emerald-200 hover:bg-emerald-100 transition-all">
        📥 <?= __('Excel (CSV) রিপোর্ট', 'Excel Report') ?>
      </a>
      <a href="<?= url('/portal/financials/funds/export/pdf') ?>" target="_blank"
         class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl text-[13px] font-semibold text-white transition-all shadow"
         style="background: linear-gradient(135deg, #800020, #580F1A);">
        🖨️ <?= __('PDF রিপোর্ট প্রিন্ট', 'PDF Print Report') ?>
      </a>
    </div>
  </div>

  <!-- Sub Navigation Tabs -->
  <div class="flex items-center gap-2 mb-8 border-b border-gray-200 pb-3 overflow-x-auto">
    <a href="<?= url('/portal/financials') ?>" class="px-4 py-2 rounded-xl text-[13.5px] font-semibold transition-all bg-gray-100 text-gray-700 hover:bg-gray-200">
      📊 <?= __('সারসংক্ষেপ (Overview)', 'Overview') ?>
    </a>
    <a href="<?= url('/portal/financials/funds') ?>" class="px-4 py-2 rounded-xl text-[13.5px] font-semibold transition-all bg-[#800020] text-white shadow">
      💰 <?= __('তহবিল জমা (Funds Deposit)', 'Funds Deposit') ?>
    </a>
    <a href="<?= url('/portal/financials/expenses') ?>" class="px-4 py-2 rounded-xl text-[13.5px] font-semibold transition-all bg-gray-100 text-gray-700 hover:bg-gray-200">
      💸 <?= __('ব্যয় ও খরচ (Expenses)', 'Expenses') ?>
    </a>
    <a href="<?= url('/portal/financials/budgets') ?>" class="px-4 py-2 rounded-xl text-[13.5px] font-semibold transition-all bg-gray-100 text-gray-700 hover:bg-gray-200">
      📅 <?= __('বার্ষিক বাজেট (Yearly Budget)', 'Yearly Budget') ?>
    </a>
  </div>

  <!-- Stat Summary Cards -->
  <div class="grid grid-cols-1 sm:grid-cols-3 gap-5 mb-8">
    <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm">
      <div class="text-[12px] font-mono text-gray-500 uppercase tracking-wider mb-1"><?= __('সর্বমোট সংগৃহীত তহবিল', 'Total Received Fund') ?></div>
      <div class="font-serif text-[26px] font-bold text-[#2F8863]">৳ <?= number_format($totalFunds, 2) ?></div>
    </div>
    <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm">
      <div class="text-[12px] font-mono text-gray-500 uppercase tracking-wider mb-1"><?= __('মেম্বারশিপ ফি থেকে সংগৃহীত', 'Membership Collections') ?></div>
      <div class="font-serif text-[24px] font-semibold text-gray-800">৳ <?= number_format($membershipFunds, 2) ?></div>
    </div>
    <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm">
      <div class="text-[12px] font-mono text-gray-500 uppercase tracking-wider mb-1"><?= __('অন্যান্য ডিপোজিট ও অনুদান', 'Donations & Other Funds') ?></div>
      <div class="font-serif text-[24px] font-semibold text-gray-800">৳ <?= number_format($otherFunds, 2) ?></div>
    </div>
  </div>

  <!-- Add Fund Form -->
  <div class="bg-white p-7 rounded-3xl border border-gray-100 shadow-sm mb-10">
    <h3 class="font-serif text-[18px] font-bold text-[#101820] mb-4"><?= __('নতুন তহবিল জমা এনট্রি করুন', 'Add New Fund Deposit') ?></h3>
    <form action="<?= url('/portal/financials/fund') ?>" method="POST" class="grid grid-cols-1 sm:grid-cols-3 lg:grid-cols-6 gap-4">
      <?= csrf_field() ?>
      <div class="sm:col-span-2">
        <label class="block text-[12px] font-semibold text-gray-700 mb-1"><?= __('তহবিলের বিবরণ/খাত', 'Fund Title') ?> *</label>
        <input type="text" name="title" required placeholder="e.g. Annual Alumni Donation" class="w-full px-3 py-2 text-[13.5px] rounded-xl border border-gray-300 focus:outline-none focus:ring-2 focus:ring-[#800020]/40">
      </div>
      <div>
        <label class="block text-[12px] font-semibold text-gray-700 mb-1"><?= __('উৎস / ক্যাটাগরি', 'Source') ?></label>
        <input type="text" name="source" placeholder="e.g. Grant / Donation" class="w-full px-3 py-2 text-[13.5px] rounded-xl border border-gray-300 focus:outline-none focus:ring-2 focus:ring-[#800020]/40">
      </div>
      <div>
        <label class="block text-[12px] font-semibold text-gray-700 mb-1"><?= __('টাকার পরিমাণ (৳)', 'Amount (BDT)') ?> *</label>
        <input type="number" step="0.01" name="amount" required placeholder="0.00" class="w-full px-3 py-2 text-[13.5px] rounded-xl border border-gray-300 focus:outline-none focus:ring-2 focus:ring-[#800020]/40">
      </div>
      <div>
        <label class="block text-[12px] font-semibold text-gray-700 mb-1"><?= __('তারিখ', 'Date') ?></label>
        <input type="date" name="fund_date" value="<?= date('Y-m-d') ?>" class="w-full px-3 py-2 text-[13.5px] rounded-xl border border-gray-300 focus:outline-none focus:ring-2 focus:ring-[#800020]/40">
      </div>
      <div class="flex items-end">
        <button type="submit" class="w-full py-2.5 px-4 rounded-xl text-[13.5px] font-semibold text-white transition-all shadow hover:-translate-y-0.5" style="background: linear-gradient(135deg, #2F8863, #153548);">
          + <?= __('তহবিল জমা', 'Add Deposit') ?>
        </button>
      </div>
    </form>
  </div>

  <!-- Funds Table -->
  <div class="bg-white p-7 rounded-3xl border border-gray-100 shadow-sm">
    <div class="flex items-center justify-between mb-4 pb-3 border-b border-gray-100">
      <h3 class="font-serif text-[18px] font-bold text-[#101820]"><?= __('সকল সংগৃহীত তহবিলের তালিকা', 'All Fund Transactions') ?></h3>
      <span class="text-[12px] text-gray-400 font-mono">Total <?= count($funds) ?> Entries</span>
    </div>

    <div class="overflow-x-auto">
      <table class="w-full text-[13.5px]">
        <thead>
          <tr class="text-left text-gray-400 font-mono border-b border-gray-100 text-[11px] uppercase">
            <th class="pb-3">তারিখ</th>
            <th class="pb-3">বিবরণ</th>
            <th class="pb-3">উৎস</th>
            <th class="pb-3">রেফারেন্স নং</th>
            <th class="pb-3 text-right">পরিমাণ (৳)</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
          <?php if (empty($funds)): ?>
          <tr><td colspan="5" class="py-6 text-center text-gray-400">কোনো তহবিল রেকর্ড পাওয়া যায়নি।</td></tr>
          <?php else: ?>
          <?php foreach ($funds as $f): ?>
          <tr class="hover:bg-gray-50/60">
            <td class="py-3.5 text-gray-500 font-mono"><?= date('d M, Y', strtotime($f['fund_date'])) ?></td>
            <td class="py-3.5 font-semibold text-gray-800"><?= e($f['title']) ?></td>
            <td class="py-3.5 text-gray-600">
              <span class="px-2.5 py-0.5 rounded-full text-[11px] font-mono bg-gray-100 text-gray-700"><?= e($f['source']) ?></span>
            </td>
            <td class="py-3.5 text-gray-400 font-mono text-[12px]"><?= e($f['reference_no'] ?: '—') ?></td>
            <td class="py-3.5 text-right font-bold text-[#2F8863]">৳ <?= number_format((float)$f['amount'], 2) ?></td>
          </tr>
          <?php endforeach; ?>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>
