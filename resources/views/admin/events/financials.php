<?php
/**
 * Admin Event Financials & Budget View
 * Variables: $event, $registrationsCount, $registrationsRevenue, $donationsRevenue, $expenses, $totalExpenses, $netBalance
 */
?>
<div class="mb-6 flex justify-between items-center">
  <a href="<?= url('/admin/events') ?>" class="text-[13px] text-white/50 hover:text-white inline-flex items-center gap-1">
    ← Back to Events
  </a>
</div>

<!-- Financial Summary Strip -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
  <div class="p-5 rounded-2xl bg-white/5 border border-white/8">
    <div class="font-serif text-[24px] font-semibold text-white">৳<?= number_format($registrationsRevenue) ?></div>
    <div class="text-[12px] text-white/40 mt-1">Registrations Revenue (<?= $registrationsCount ?> attendees)</div>
  </div>
  
  <div class="p-5 rounded-2xl bg-white/5 border border-white/8">
    <div class="font-serif text-[24px] font-semibold text-[#8b5cf6]">৳<?= number_format($donationsRevenue) ?></div>
    <div class="text-[12px] text-white/40 mt-1">
      Crowdfunded Raised 
      <?php if ($event['is_crowdfunding'] && $event['crowdfunding_goal'] > 0): ?>
      (Goal: ৳<?= number_format((float)$event['crowdfunding_goal']) ?>)
      <?php endif; ?>
    </div>
  </div>

  <div class="p-5 rounded-2xl bg-white/5 border border-white/8">
    <div class="font-serif text-[24px] font-semibold text-red-400">৳<?= number_format($totalExpenses) ?></div>
    <div class="text-[12px] text-white/40 mt-1">Total Spent / Expenses</div>
  </div>

  <div class="p-5 rounded-2xl bg-white/5 border border-white/8">
    <div class="font-serif text-[24px] font-semibold <?= $netBalance >= 0 ? 'text-emerald-400' : 'text-red-400' ?>">
      ৳<?= number_format($netBalance) ?>
    </div>
    <div class="text-[12px] text-white/40 mt-1">Net Event Balance</div>
  </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

  <!-- Left/Center: Expenses List -->
  <div class="lg:col-span-2 p-6 rounded-3xl bg-white/4 border border-white/8 space-y-4">
    <h3 class="text-[15px] font-semibold text-white border-b border-white/5 pb-3">Expenses Log</h3>

    <?php if (empty($expenses)): ?>
    <div class="text-center text-white/30 text-[13px] py-8">No expenses logged for this event.</div>
    <?php else: ?>
    <table class="w-full text-[13px]">
      <thead>
        <tr class="border-b border-white/5 text-white/40">
          <th class="text-left py-2 font-mono text-[11px]">Title</th>
          <th class="text-left py-2 font-mono text-[11px]">Spent On</th>
          <th class="text-right py-2 font-mono text-[11px]">Amount</th>
        </tr>
      </thead>
      <tbody class="divide-y divide-white/5 text-white/80">
        <?php foreach ($expenses as $exp): ?>
        <tr>
          <td class="py-3">
            <div class="font-medium text-white"><?= e($exp['title']) ?></div>
            <?php if (!empty($exp['description'])): ?>
            <div class="text-[11.5px] text-white/40 mt-0.5"><?= e($exp['description']) ?></div>
            <?php endif; ?>
          </td>
          <td class="py-3 text-white/50"><?= date('d M Y', strtotime($exp['spent_at'])) ?></td>
          <td class="py-3 text-right font-mono text-red-400 font-semibold">৳<?= number_format((float)$exp['amount']) ?></td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
    <?php endif; ?>
  </div>

  <!-- Right: Add Expense Form -->
  <div class="p-6 rounded-3xl bg-white/4 border border-white/8 h-fit space-y-4">
    <h3 class="text-[15px] font-semibold text-white border-b border-white/5 pb-3">Log Event Expense</h3>

    <form method="POST" action="<?= url('/admin/events/' . $event['id'] . '/expenses') ?>" class="space-y-4">
      <?= csrf_field() ?>

      <div>
        <label class="block text-[12px] font-medium text-white/60 mb-1" for="expense_title">Title</label>
        <input id="expense_title" type="text" name="title" required placeholder="e.g. Stage Decoration"
               class="w-full px-3.5 py-2 rounded-xl text-[13px] text-white focus:outline-none"
               style="background:rgba(255,255,255,0.06);border:1px solid rgba(255,255,255,0.12);">
      </div>

      <div>
        <label class="block text-[12px] font-medium text-white/60 mb-1" for="expense_amount">Amount (৳)</label>
        <input id="expense_amount" type="number" step="0.01" name="amount" required placeholder="e.g. 5000"
               class="w-full px-3.5 py-2 rounded-xl text-[13px] text-white focus:outline-none"
               style="background:rgba(255,255,255,0.06);border:1px solid rgba(255,255,255,0.12);">
      </div>

      <div>
        <label class="block text-[12px] font-medium text-white/60 mb-1" for="expense_date">Spent Date</label>
        <input id="expense_date" type="date" name="spent_at" value="<?= date('Y-m-d') ?>" required
               class="w-full px-3.5 py-2 rounded-xl text-[13px] text-white focus:outline-none"
               style="background:rgba(255,255,255,0.06);border:1px solid rgba(255,255,255,0.12);">
      </div>

      <div>
        <label class="block text-[12px] font-medium text-white/60 mb-1" for="expense_desc">Description (Optional)</label>
        <textarea id="expense_desc" name="description" rows="2" placeholder="Notes/vendor details..."
                  class="w-full px-3.5 py-2 rounded-xl text-[13px] text-white focus:outline-none"
                  style="background:rgba(255,255,255,0.06);border:1px solid rgba(255,255,255,0.12);"></textarea>
      </div>

      <button type="submit" class="w-full py-2.5 rounded-xl text-[13px] font-semibold text-white"
              style="background:linear-gradient(135deg,#A22638,#800020);">Record Expense</button>
    </form>
  </div>

</div>
