<?php
/**
 * Alumni Portal Notifications List View
 * Variables: $notifications
 */
?>
<div class="w-full space-y-6">
  
  <div class="flex justify-between items-center">
    <h3 class="font-serif text-[22px] font-semibold text-gray-800">Notifications</h3>
    <span class="text-[12px] text-gray-400 font-mono"><?= count($notifications) ?> messages</span>
  </div>

  <div class="p-6 rounded-3xl bg-white border border-gray-100 shadow-sm space-y-4">
    <?php if (empty($notifications)): ?>
      <p class="text-center text-gray-400 py-8 text-[13.5px]">No notifications yet.</p>
    <?php else: ?>
      <div class="divide-y divide-gray-100">
        <?php foreach ($notifications as $n): ?>
        <div class="py-4 first:pt-0 last:pb-0">
          <div class="flex justify-between items-start gap-3">
            <h4 class="font-semibold text-gray-800 text-[14px]"><?= e($n['title']) ?></h4>
            <span class="text-[10.5px] text-gray-400 font-mono"><?= date('d M, H:i', strtotime($n['created_at'])) ?></span>
          </div>
          <p class="text-[13px] text-gray-600 mt-1 leading-relaxed"><?= e($n['message']) ?></p>
        </div>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>
  </div>

</div>
