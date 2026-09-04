<?php
/**
 * Alumni Portal — Association Financial Overview View
 */
$activeTab = 'overview';
?>
<div class="w-full space-y-6">
  <!-- Page Header -->
  <div class="mb-6 flex justify-between items-center flex-wrap gap-4">
    <div>
      <span class="font-mono text-[11px] tracking-widest text-[#800020] uppercase block mb-1">
        🔐 <?= __('অনুমোদিত কমিটি ফাইনান্সিয়াল প্যানেল', 'AUTHORIZED FINANCIAL MANAGEMENT') ?>
      </span>
      <h1 class="font-serif text-[28px] font-bold text-[#101820]"><?= __('আর্থিক সারসংক্ষেপ ড্যাশবোর্ড', 'Financial Overview Dashboard') ?></h1>
    </div>
    <span class="px-3 py-1.5 rounded-full text-[12px] font-mono font-semibold bg-[#2F8863]/10 text-[#2F8863] border border-[#2F8863]/30">
      ✓ <?= __('অনুমোদিত ফাইনান্সিয়াল অ্যাক্সেস', 'Approved Financial Access') ?>
    </span>
  </div>

  <!-- Sub Navigation Tabs -->
  <div class="flex items-center gap-2 mb-8 border-b border-gray-200 pb-3 overflow-x-auto">
    <a href="<?= url('/portal/financials') ?>" class="px-4 py-2 rounded-xl text-[13.5px] font-semibold transition-all bg-[#800020] text-white shadow">
      📊 <?= __('সারসংক্ষেপ (Overview)', 'Overview') ?>
    </a>
    <a href="<?= url('/portal/financials/funds') ?>" class="px-4 py-2 rounded-xl text-[13.5px] font-semibold transition-all bg-gray-100 text-gray-700 hover:bg-gray-200">
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
  <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-10">
    <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm flex flex-col justify-between">
      <div>
        <div class="text-[12px] font-mono text-gray-500 uppercase tracking-wider mb-1"><?= __('মোট সংগৃহীত তহবিল', 'Total Received Fund') ?></div>
        <div class="font-serif text-[24px] font-bold text-[#2F8863]">৳ <?= number_format($totalFunds, 2) ?></div>
      </div>
      <a href="<?= url('/portal/financials/funds') ?>" class="mt-4 text-[12.5px] text-[#800020] font-semibold hover:underline block">
        <?= __('তহবিলের বিস্তারিত দেখুন', 'View All Funds') ?> →
      </a>
    </div>

    <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm flex flex-col justify-between">
      <div>
        <div class="text-[12px] font-mono text-gray-500 uppercase tracking-wider mb-1"><?= __('মোট খরচের পরিমাণ', 'Total Expenses') ?></div>
        <div class="font-serif text-[24px] font-bold text-rose-700">৳ <?= number_format($totalExpenses, 2) ?></div>
      </div>
      <a href="<?= url('/portal/financials/expenses') ?>" class="mt-4 text-[12.5px] text-[#800020] font-semibold hover:underline block">
        <?= __('খরচের বিস্তারিত দেখুন', 'View All Expenses') ?> →
      </a>
    </div>

    <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm flex flex-col justify-between">
      <div>
        <div class="text-[12px] font-mono text-gray-500 uppercase tracking-wider mb-1"><?= __('অবশিষ্ট নিট ব্যালেন্স', 'Net Available Balance') ?></div>
        <div class="font-serif text-[24px] font-bold text-[#800020]">৳ <?= number_format($balance, 2) ?></div>
      </div>
      <div class="mt-4 text-[11.5px] text-gray-400 font-mono"><?= __('সংগৃহীত তহবিল - মোট খরচ', 'Funds - Expenses') ?></div>
    </div>

    <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm flex flex-col justify-between">
      <div>
        <div class="text-[12px] font-mono text-gray-500 uppercase tracking-wider mb-1"><?= __('বার্ষিক বাজেট (' . e($selectedYear) . ')', 'Yearly Budget (' . e($selectedYear) . ')') ?></div>
        <div class="font-serif text-[24px] font-bold text-indigo-900">৳ <?= number_format($totalAllocatedBudget, 2) ?></div>
      </div>
      <a href="<?= url('/portal/financials/budgets') ?>" class="mt-4 text-[12.5px] text-[#800020] font-semibold hover:underline block">
        <?= __('বাজেট বরাদ্দ দেখুন', 'View Budget Allocations') ?> →
      </a>
    </div>
  </div>

  <!-- Recent Activity Overview Feeds -->
  <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
    
    <!-- Recent Funds Deposit -->
    <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm">
      <div class="flex items-center justify-between mb-4 pb-3 border-b border-gray-100">
        <h3 class="font-serif text-[18px] font-bold text-[#101820] flex items-center gap-2">
          💰 <?= __('সাম্প্রতিক তহবিল জমা', 'Recent Funds Received') ?>
        </h3>
        <a href="<?= url('/portal/financials/funds') ?>" class="text-[12px] text-[#800020] font-semibold hover:underline">
          <?= __('সব দেখুন', 'View All') ?>
        </a>
      </div>
      <div class="space-y-3">
        <?php if (empty($recentFunds)): ?>
        <p class="text-gray-400 text-[13px] py-4 text-center">কোনো সাম্প্রতিক তহবিল রেকর্ড পাওয়া যায়নি।</p>
        <?php else: ?>
        <?php foreach ($recentFunds as $f): ?>
        <div class="flex items-center justify-between text-[13px] py-1 border-b border-gray-50 last:border-0">
          <div>
            <div class="font-medium text-gray-800"><?= e($f['title']) ?></div>
            <div class="text-[11px] text-gray-400"><?= date('d M, Y', strtotime($f['fund_date'])) ?> · <?= e($f['source']) ?></div>
          </div>
          <div class="font-semibold text-[#2F8863]">+ ৳ <?= number_format((float)$f['amount'], 2) ?></div>
        </div>
        <?php endforeach; ?>
        <?php endif; ?>
      </div>
    </div>

    <!-- Recent Expenses -->
    <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm">
      <div class="flex items-center justify-between mb-4 pb-3 border-b border-gray-100">
        <h3 class="font-serif text-[18px] font-bold text-[#101820] flex items-center gap-2">
          💸 <?= __('সাম্প্রতিক খরচসমূহ', 'Recent Expenses') ?>
        </h3>
        <a href="<?= url('/portal/financials/expenses') ?>" class="text-[12px] text-[#800020] font-semibold hover:underline">
          <?= __('সব দেখুন', 'View All') ?>
        </a>
      </div>
      <div class="space-y-3">
        <?php if (empty($recentExpenses)): ?>
        <p class="text-gray-400 text-[13px] py-4 text-center">কোনো সাম্প্রতিক খরচের হিসাব পাওয়া যায়নি।</p>
        <?php else: ?>
        <?php foreach ($recentExpenses as $ex): ?>
        <div class="flex items-center justify-between text-[13px] py-1 border-b border-gray-50 last:border-0">
          <div>
            <div class="font-medium text-gray-800"><?= e($ex['title']) ?></div>
            <div class="text-[11px] text-gray-400"><?= date('d M, Y', strtotime($ex['expense_date'])) ?> · <?= e($ex['category']) ?></div>
          </div>
          <div class="font-semibold text-rose-700">- ৳ <?= number_format((float)$ex['amount'], 2) ?></div>
        </div>
        <?php endforeach; ?>
        <?php endif; ?>
      </div>
    </div>

  </div>
</div>
