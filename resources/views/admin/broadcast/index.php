<?php
/**
 * Admin Mass Email Broadcast View
 */
$pdo = \App\Services\Database::connection();
$broadcasts = $pdo->query("SELECT eb.*, u.name as sender_name 
                           FROM email_broadcasts eb 
                           JOIN users u ON eb.sender_id = u.id 
                           ORDER BY eb.id DESC")->fetchAll();
?>
<div class="max-w-6xl mx-auto py-6 font-['Kalpurush']">

  <!-- Header -->
  <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8">
    <div>
      <span class="font-mono text-[11px] font-bold text-[#E58E97] uppercase tracking-wider block mb-1">
        <i class="fa-solid fa-paper-plane mr-1"></i> MASS EMAIL ANNOUNCEMENTS
      </span>
      <h1 class="font-serif text-[28px] font-bold text-white"><?= __('ইমেইল ব্রডকাস্ট ও নোটিফিকেশন', 'Email Broadcast & Reminders') ?></h1>
    </div>
  </div>

  <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
    
    <!-- Dispatch Form (Left 7 cols) -->
    <div class="lg:col-span-7 p-8 rounded-3xl bg-white/5 border border-white/10 backdrop-blur-md">
      <h3 class="font-serif text-[20px] font-bold text-white mb-4"><?= __('নতুন ইমেইল ব্রডকাস্ট পাঠান', 'Send Mass Email Announcement') ?></h3>
      
      <form action="<?= url('/admin/broadcast/send') ?>" method="POST" class="space-y-5">
        <?= csrf_field() ?>

        <div>
          <label class="block text-[13px] font-mono text-white/70 mb-1.5"><?= __('প্রাপক গ্রুপ (Recipient Group)', 'Recipient Group') ?></label>
          <select name="recipient_group" class="w-full px-4 py-3 rounded-2xl bg-black/40 border border-white/10 text-white focus:outline-none focus:border-[#E58E97]">
            <option value="all">সকল অ্যালামনাই সদস্যগণ (All Alumni Members)</option>
            <option value="active">শুধুমাত্র সক্রিয় সদস্যগণ (Active Paid Members Only)</option>
            <option value="mentors">সকল মেনটরগণ (Mentors Only)</option>
          </select>
        </div>

        <div>
          <label class="block text-[13px] font-mono text-white/70 mb-1.5"><?= __('ইমেইল বিষয় (Subject)', 'Email Subject') ?> *</label>
          <input type="text" name="subject" required placeholder="যেমন: বার্ষিক পুনর্মিলনী ও জেনারেল মিটিং নোটিশ"
                 class="w-full px-4 py-3 rounded-2xl bg-black/40 border border-white/10 text-white focus:outline-none focus:border-[#E58E97]">
        </div>

        <div>
          <label class="block text-[13px] font-mono text-white/70 mb-1.5"><?= __('ইমেইল বার্তা (Body)', 'Email Message Body') ?> *</label>
          <textarea name="body" rows="6" required placeholder="এখানে ইমেইলের মূল বিষয় বস্তু সুন্দরভাবে লিখুন..."
                    class="w-full px-4 py-3 rounded-2xl bg-black/40 border border-white/10 text-white focus:outline-none focus:border-[#E58E97] resize-none"></textarea>
        </div>

        <button type="submit" class="w-full py-4 rounded-2xl font-bold text-white bg-gradient-to-r from-[#800020] to-[#A22638] hover:opacity-95 transition-all shadow-lg">
          <i class="fa-solid fa-paper-plane mr-2"></i> <?= __('সকলকে ইমেইল পাঠান (Broadcast Email)', 'Send Mass Email Broadcast') ?>
        </button>
      </form>
    </div>

    <!-- History List (Right 5 cols) -->
    <div class="lg:col-span-5 space-y-4">
      <h3 class="font-serif text-[18px] font-bold text-white mb-2"><?= __('সাম্প্রতিক ব্রডকাস্ট হিস্ট্রি', 'Recent Broadcast History') ?></h3>

      <?php if (empty($broadcasts)): ?>
        <div class="p-6 rounded-2xl bg-white/5 border border-white/10 text-white/50 text-[13px]">
          পূর্ববর্তী কোনো ব্রডকাস্ট ইমেইল হিস্ট্রি পাওয়া যায়নি।
        </div>
      <?php else: ?>
        <?php foreach ($broadcasts as $b): ?>
        <div class="p-5 rounded-2xl bg-white/5 border border-white/10">
          <div class="flex justify-between items-start mb-2">
            <h4 class="font-bold text-[15px] text-white"><?= e($b['subject']) ?></h4>
            <span class="text-[10px] font-mono px-2 py-0.5 rounded bg-white/10 text-[#E58E97]"><?= e($b['recipient_group']) ?></span>
          </div>
          <p class="text-[13px] text-white/70 line-clamp-2 mb-3"><?= e($b['body']) ?></p>
          <div class="text-[11px] font-mono text-white/40 flex justify-between">
            <span>প্রেরক: <?= e($b['sender_name']) ?></span>
            <span><?= date('d M Y, h:i A', strtotime($b['created_at'])) ?></span>
          </div>
        </div>
        <?php endforeach; ?>
      <?php endif; ?>
    </div>

  </div>
</div>
