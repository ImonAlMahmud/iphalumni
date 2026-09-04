<?php
/**
 * Alumni Portal — Association Expenses Page
 * Variables: $totalExpenses, $expenses
 */
$activeTab = 'expenses';
?>
<div class="max-w-6xl mx-auto px-4 py-8">
  <!-- Page Header -->
  <div class="mb-6 flex justify-between items-center flex-wrap gap-4">
    <div>
      <span class="font-mono text-[11px] tracking-widest text-[#800020] uppercase block mb-1">
        💸 <?= __('ব্যয় ও খরচ পরিচালনা', 'ASSOCIATION EXPENSES') ?>
      </span>
      <h1 class="font-serif text-[28px] font-bold text-[#101820]"><?= __('খরচ ও ব্যয়ের হিসাব ব্যবস্থাপনা', 'Expense Management') ?></h1>
    </div>
    
    <!-- Report Action Buttons -->
    <div class="flex items-center gap-3">
      <a href="<?= url('/portal/financials/expenses/export/excel') ?>" 
         class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl text-[13px] font-semibold text-emerald-800 bg-emerald-50 border border-emerald-200 hover:bg-emerald-100 transition-all">
        📥 <?= __('Excel (CSV) রিপোর্ট', 'Excel Report') ?>
      </a>
      <a href="<?= url('/portal/financials/expenses/export/pdf') ?>" target="_blank"
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
    <a href="<?= url('/portal/financials/funds') ?>" class="px-4 py-2 rounded-xl text-[13.5px] font-semibold transition-all bg-gray-100 text-gray-700 hover:bg-gray-200">
      💰 <?= __('তহবিল জমা (Funds Deposit)', 'Funds Deposit') ?>
    </a>
    <a href="<?= url('/portal/financials/expenses') ?>" class="px-4 py-2 rounded-xl text-[13.5px] font-semibold transition-all bg-[#800020] text-white shadow">
      💸 <?= __('ব্যয় ও খরচ (Expenses)', 'Expenses') ?>
    </a>
    <a href="<?= url('/portal/financials/budgets') ?>" class="px-4 py-2 rounded-xl text-[13.5px] font-semibold transition-all bg-gray-100 text-gray-700 hover:bg-gray-200">
      📅 <?= __('বার্ষিক বাজেট (Yearly Budget)', 'Yearly Budget') ?>
    </a>
  </div>

  <!-- Stat Summary Cards -->
  <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 mb-8">
    <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm">
      <div class="text-[12px] font-mono text-gray-500 uppercase tracking-wider mb-1"><?= __('মোট খরচের পরিমাণ', 'Total Expenses') ?></div>
      <div class="font-serif text-[26px] font-bold text-rose-700">৳ <?= number_format($totalExpenses, 2) ?></div>
    </div>
    <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm">
      <div class="text-[12px] font-mono text-gray-500 uppercase tracking-wider mb-1"><?= __('মোট ব্যয়ের ভাউচার সংখ্যা', 'Total Expense Records') ?></div>
      <div class="font-serif text-[26px] font-semibold text-gray-800"><?= count($expenses) ?> <?= __('টি এনট্রি', 'Records') ?></div>
    </div>
  </div>

  <!-- Add Expense Form -->
  <div class="bg-white p-7 rounded-3xl border border-gray-100 shadow-sm mb-10">
    <h3 class="font-serif text-[18px] font-bold text-[#101820] mb-4"><?= __('নতুন খরচের হিসাব এনট্রি করুন', 'Add New Expense Record') ?></h3>
    <form action="<?= url('/portal/financials/expense') ?>" method="POST" class="grid grid-cols-1 sm:grid-cols-3 lg:grid-cols-6 gap-4">
      <?= csrf_field() ?>
      <div class="sm:col-span-2">
        <label class="block text-[12px] font-semibold text-gray-700 mb-1"><?= __('ব্যয়ের শিরোনাম', 'Expense Title') ?> *</label>
        <input type="text" name="title" required placeholder="e.g. Office Stationery / Seminar Booking" class="w-full px-3 py-2 text-[13.5px] rounded-xl border border-gray-300 focus:outline-none focus:ring-2 focus:ring-[#800020]/40">
      </div>
      <div>
        <label class="block text-[12px] font-semibold text-gray-700 mb-1"><?= __('খাত / ক্যাটাগরি', 'Category') ?></label>
        <input type="text" name="category" placeholder="e.g. Event / Office / Print" class="w-full px-3 py-2 text-[13.5px] rounded-xl border border-gray-300 focus:outline-none focus:ring-2 focus:ring-[#800020]/40">
      </div>
      <div>
        <label class="block text-[12px] font-semibold text-gray-700 mb-1"><?= __('টাকার পরিমাণ (৳)', 'Amount (BDT)') ?> *</label>
        <input type="number" step="0.01" name="amount" required placeholder="0.00" class="w-full px-3 py-2 text-[13.5px] rounded-xl border border-gray-300 focus:outline-none focus:ring-2 focus:ring-[#800020]/40">
      </div>
      <div>
        <label class="block text-[12px] font-semibold text-gray-700 mb-1"><?= __('তারিখ', 'Date') ?></label>
        <input type="date" name="expense_date" value="<?= date('Y-m-d') ?>" class="w-full px-3 py-2 text-[13.5px] rounded-xl border border-gray-300 focus:outline-none focus:ring-2 focus:ring-[#800020]/40">
      </div>
      <div class="flex items-end">
        <button type="submit" class="w-full py-2.5 px-4 rounded-xl text-[13.5px] font-semibold text-white transition-all shadow hover:-translate-y-0.5" style="background: linear-gradient(135deg, #A22638, #800020);">
          + <?= __('খরচ এনট্রি', 'Add Expense') ?>
        </button>
      </div>
    </form>
  </div>

  <!-- Expenses Table -->
  <div class="bg-white p-7 rounded-3xl border border-gray-100 shadow-sm">
    <div class="flex items-center justify-between mb-4 pb-3 border-b border-gray-100">
      <h3 class="font-serif text-[18px] font-bold text-[#101820]"><?= __('সকল ব্যয়ের বিস্তারিত হিসাব', 'All Expense Records') ?></h3>
      <span class="text-[12px] text-gray-400 font-mono">Total <?= count($expenses) ?> Entries</span>
    </div>

    <div class="overflow-x-auto">
      <table class="w-full text-[13.5px]">
        <thead>
          <tr class="text-left text-gray-400 font-mono border-b border-gray-100 text-[11px] uppercase">
            <th class="pb-3">তারিখ</th>
            <th class="pb-3">বিবরণ</th>
            <th class="pb-3">খাত</th>
            <th class="pb-3">ভাউচার নং</th>
            <th class="pb-3 text-right">পরিমাণ (৳)</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
          <?php if (empty($expenses)): ?>
          <tr><td colspan="5" class="py-6 text-center text-gray-400">কোনো খরচের হিসাব পাওয়া যায়নি।</td></tr>
          <?php else: ?>
          <?php foreach ($expenses as $ex): ?>
          <tr class="hover:bg-gray-50/60">
            <td class="py-3.5 text-gray-500 font-mono"><?= date('d M, Y', strtotime($ex['expense_date'])) ?></td>
            <td class="py-3.5 font-semibold text-gray-800"><?= e($ex['title']) ?></td>
            <td class="py-3.5 text-gray-600">
              <span class="px-2.5 py-0.5 rounded-full text-[11px] font-mono bg-rose-50 text-rose-800 border border-rose-100"><?= e($ex['category']) ?></span>
            </td>
            <td class="py-3.5 text-gray-400 font-mono text-[12px]"><?= e($ex['voucher_no'] ?: '—') ?></td>
            <td class="py-3.5 text-right font-bold text-rose-700">- ৳ <?= number_format((float)$ex['amount'], 2) ?></td>
          </tr>
          <?php endforeach; ?>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>
