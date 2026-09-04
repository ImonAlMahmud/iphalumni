<?php
/**
 * Alumni Portal — Yearly Budget Allocation Page
 * Variables: $fiscalYears, $selectedYear, $budgets, $totalAllocatedBudget
 */
$activeTab = 'budgets';
?>
<div class="max-w-6xl mx-auto px-4 py-8">
  <!-- Page Header -->
  <div class="mb-6 flex justify-between items-center flex-wrap gap-4">
    <div>
      <span class="font-mono text-[11px] tracking-widest text-[#800020] uppercase block mb-1">
        📅 <?= __('অর্থবছরের বাজেট ব্যবস্থাপনা', 'YEARLY BUDGET ALLOCATION') ?>
      </span>
      <h1 class="font-serif text-[28px] font-bold text-[#101820]"><?= __('বার্ষিক বাজেট বরাদ্দ পরিকল্পনা', 'Yearly Budget Plan') ?></h1>
    </div>
    
    <!-- Report Action Buttons -->
    <div class="flex items-center gap-3">
      <a href="<?= url('/portal/financials/budgets/export/excel?fiscal_year=' . urlencode($selectedYear)) ?>" 
         class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl text-[13px] font-semibold text-emerald-800 bg-emerald-50 border border-emerald-200 hover:bg-emerald-100 transition-all">
        📥 <?= __('Excel (CSV) রিপোর্ট', 'Excel Report') ?>
      </a>
      <a href="<?= url('/portal/financials/budgets/export/pdf?fiscal_year=' . urlencode($selectedYear)) ?>" target="_blank"
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
    <a href="<?= url('/portal/financials/expenses') ?>" class="px-4 py-2 rounded-xl text-[13.5px] font-semibold transition-all bg-gray-100 text-gray-700 hover:bg-gray-200">
      💸 <?= __('ব্যয় ও খরচ (Expenses)', 'Expenses') ?>
    </a>
    <a href="<?= url('/portal/financials/budgets') ?>" class="px-4 py-2 rounded-xl text-[13.5px] font-semibold transition-all bg-[#800020] text-white shadow">
      📅 <?= __('বার্ষিক বাজেট (Yearly Budget)', 'Yearly Budget') ?>
    </a>
  </div>

  <!-- Fiscal Year Filter & Overview Stat -->
  <div class="grid grid-cols-1 md:grid-cols-3 gap-5 mb-8">
    <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm md:col-span-2 flex items-center justify-between flex-wrap gap-4">
      <div>
        <label class="block text-[12px] font-mono text-gray-500 uppercase tracking-wider mb-1"><?= __('অর্থবছর ফিল্টার নির্বাচন করুন', 'Select Fiscal Year') ?></label>
        <form method="GET" action="<?= url('/portal/financials/budgets') ?>" class="flex items-center gap-3">
          <select name="fiscal_year" onchange="this.form.submit()" class="px-4 py-2 text-[14px] font-semibold rounded-xl border border-gray-300 focus:outline-none focus:ring-2 focus:ring-[#800020]/40">
            <?php foreach ($fiscalYears as $fy): ?>
            <option value="<?= e($fy) ?>" <?= $fy === $selectedYear ? 'selected' : '' ?>><?= e($fy) ?> Fiscal Year</option>
            <?php endforeach; ?>
          </select>
        </form>
      </div>
      <div class="text-right">
        <div class="text-[12px] font-mono text-gray-400"><?= __('মোট বাজেট খাত সংখ্যা', 'Budget Categories') ?></div>
        <div class="text-[20px] font-bold text-gray-800"><?= count($budgets) ?> <?= __('টি খাত', 'Categories') ?></div>
      </div>
    </div>

    <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm">
      <div class="text-[12px] font-mono text-gray-500 uppercase tracking-wider mb-1"><?= __('মোট বাজেট বরাদ্দ (' . e($selectedYear) . ')', 'Total Allocated Budget') ?></div>
      <div class="font-serif text-[26px] font-bold text-indigo-900">৳ <?= number_format($totalAllocatedBudget, 2) ?></div>
    </div>
  </div>

  <!-- Add Budget Allocation Form -->
  <div class="bg-white p-7 rounded-3xl border border-gray-100 shadow-sm mb-10">
    <h3 class="font-serif text-[18px] font-bold text-[#101820] mb-4"><?= __('নতুন বাজেট খাত ও বরাদ্দ যুক্ত করুন', 'Add Budget Allocation') ?></h3>
    <form action="<?= url('/portal/financials/budget') ?>" method="POST" class="grid grid-cols-1 sm:grid-cols-3 lg:grid-cols-5 gap-4">
      <?= csrf_field() ?>
      <div>
        <label class="block text-[12px] font-semibold text-gray-700 mb-1"><?= __('অর্থবছর (Fiscal Year)', 'Fiscal Year') ?> *</label>
        <input type="text" name="fiscal_year" value="<?= e($selectedYear) ?>" required placeholder="e.g. 2025-2026" class="w-full px-3 py-2 text-[13.5px] rounded-xl border border-gray-300 focus:outline-none focus:ring-2 focus:ring-[#800020]/40">
      </div>
      <div class="sm:col-span-2">
        <label class="block text-[12px] font-semibold text-gray-700 mb-1"><?= __('বাজেটের খাত/বিবরণ', 'Category / Details') ?> *</label>
        <input type="text" name="category" required placeholder="e.g. Annual Reunion / Research Grant / IT Setup" class="w-full px-3 py-2 text-[13.5px] rounded-xl border border-gray-300 focus:outline-none focus:ring-2 focus:ring-[#800020]/40">
      </div>
      <div>
        <label class="block text-[12px] font-semibold text-gray-700 mb-1"><?= __('বরাদ্দকৃত টাকা (৳)', 'Allocated Amount') ?> *</label>
        <input type="number" step="0.01" name="allocated_amount" required placeholder="0.00" class="w-full px-3 py-2 text-[13.5px] rounded-xl border border-gray-300 focus:outline-none focus:ring-2 focus:ring-[#800020]/40">
      </div>
      <div class="flex items-end">
        <button type="submit" class="w-full py-2.5 px-4 rounded-xl text-[13.5px] font-semibold text-white transition-all shadow hover:-translate-y-0.5" style="background: linear-gradient(135deg, #153548, #0F2A3D);">
          + <?= __('বাজেট সেট', 'Save Budget') ?>
        </button>
      </div>
    </form>
  </div>

  <!-- Budget Table -->
  <div class="bg-white p-7 rounded-3xl border border-gray-100 shadow-sm">
    <div class="flex items-center justify-between mb-4 pb-3 border-b border-gray-100">
      <h3 class="font-serif text-[18px] font-bold text-[#101820]"><?= e($selectedYear) ?> <?= __('অর্থবছরের খাতওয়ারি বরাদ্দ তালিকা', 'Category Allocations') ?></h3>
      <span class="text-[12px] text-gray-400 font-mono">Total <?= count($budgets) ?> Categories</span>
    </div>

    <div class="overflow-x-auto">
      <table class="w-full text-[13.5px]">
        <thead>
          <tr class="text-left text-gray-400 font-mono border-b border-gray-100 text-[11px] uppercase">
            <th class="pb-3">অর্থবছর</th>
            <th class="pb-3">বাজেট খাত (Category)</th>
            <th class="pb-3 text-right">বরাদ্দকৃত পরিমাণ (৳)</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
          <?php if (empty($budgets)): ?>
          <tr><td colspan="3" class="py-6 text-center text-gray-400">এই অর্থবছরের জন্য কোনো বাজেট বরাদ্দ তালিকা পাওয়া যায়নি।</td></tr>
          <?php else: ?>
          <?php foreach ($budgets as $b): ?>
          <tr class="hover:bg-gray-50/60">
            <td class="py-3.5 text-gray-500 font-mono font-medium"><?= e($b['fiscal_year']) ?></td>
            <td class="py-3.5 font-semibold text-gray-800"><?= e($b['category']) ?></td>
            <td class="py-3.5 text-right font-bold text-indigo-900">৳ <?= number_format((float)$b['allocated_amount'], 2) ?></td>
          </tr>
          <?php endforeach; ?>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>
