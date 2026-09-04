<?php
/**
 * Donation Page View
 */
?>
<div class="max-w-xl mx-auto px-6 py-14">
  <div class="mb-8 text-center">
    <span class="font-mono text-[11px] tracking-widest text-[#2F8863] block mb-2"><?= __('আইপিএইচ সহায়তা', 'SUPPORT IPH') ?></span>
    <h1 class="font-serif text-[28px] font-semibold text-[#101820] mb-2"><?= __('অনুদান দিন', 'Make a Donation') ?></h1>
    <p class="text-[13.5px] text-[#6B7178]"><?= __('আমাদের শিক্ষার্থী, স্কলারশিপ প্রোগ্রাম এবং ক্যাম্পাসের উন্নয়নে সাহায্য করুন।', 'Support our students, scholarship program, and campus developments.') ?></p>
  </div>

  <div class="p-8 rounded-3xl glass-strong">
    <form method="POST" action="<?= url('/donate') ?>" class="space-y-5">
      <?= csrf_field() ?>

      <?php if ($event): ?>
      <div class="p-4 rounded-2xl bg-[#2F8863]/10 border border-[#2F8863]/25 text-[13px] text-[#2F8863]">
        🎯 <?= __('আপনি অনুদান দিচ্ছেন:', 'You are donating to support:') ?> <strong><?= e($event['title']) ?></strong>
      </div>
      <input type="hidden" name="event_id" value="<?= (int)$event['id'] ?>">
      <?php endif; ?>

      <!-- Name -->
      <div>
        <label class="form-label" for="name"><?= __('পূর্ণ নাম', 'Full Name') ?></label>
        <input id="name" type="text" name="name" required class="form-input" placeholder="<?= __('ডা. প্রথম নাম শেষ নাম', 'Dr. Firstname Lastname') ?>">
      </div>

      <!-- Email -->
      <div>
        <label class="form-label" for="email"><?= __('ইমেইল ঠিকানা', 'Email Address') ?></label>
        <input id="email" type="email" name="email" required class="form-input" placeholder="you@example.com">
      </div>

      <!-- Amount -->
      <div>
        <label class="form-label" for="amount"><?= __('পরিমাণ (৳ BDT)', 'Amount (৳ BDT)') ?></label>
        <input id="amount" type="number" min="10" name="amount" required class="form-input" placeholder="<?= __('সর্বনিম্ন ৳১০', 'Minimum ৳10') ?>">
      </div>

      <!-- Purpose -->
      <div>
        <label class="form-label" for="purpose"><?= __('উদ্দেশ্য', 'Purpose') ?></label>
        <select id="purpose" name="purpose" class="form-input">
          <?php if ($event): ?>
          <option value="Event Campaign: <?= e($event['title']) ?>" selected><?= __('ইভেন্ট ক্যাম্পেইন', 'Event Campaign') ?>: <?= e($event['title']) ?></option>
          <?php endif; ?>
          <option value="General Fund" <?= !$event ? 'selected' : '' ?>><?= __('সাধারণ তহবিল', 'General Fund') ?></option>
          <option value="Student Scholarships"><?= __('শিক্ষার্থী স্কলারশিপ', 'Student Scholarships') ?></option>
          <option value="Research & Development"><?= __('গবেষণা ও উন্নয়ন', 'Research & Development') ?></option>
        </select>
      </div>

      <!-- Message -->
      <div>
        <label class="form-label" for="message"><?= __('বার্তা (ঐচ্ছিক)', 'Message (Optional)') ?></label>
        <textarea id="message" name="message" rows="3" class="form-input" placeholder="<?= __('আপনার শুভকামনা বা বিশেষ নোট...', 'Your blessings or special note...') ?>"></textarea>
      </div>

      <button type="submit" class="btn btn-gold w-full"><?= __('পেমেন্ট করুন', 'Proceed to Pay') ?></button>
    </form>
  </div>
</div>
