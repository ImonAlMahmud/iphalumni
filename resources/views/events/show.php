<?php
/**
 * Single Event View
 * Variables: $event
 */
?>
<div class="max-w-4xl mx-auto px-6 py-14">
  <div class="mb-6">
    <a href="<?= url('/events') ?>" class="text-[13px] text-[#6B7178] hover:text-[#101820] inline-flex items-center gap-1">
      ← <?= __('ইভেন্টসমূহে ফিরে যান', 'Back to Events') ?>
    </a>
  </div>

  <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <!-- Left: Event details -->
    <div class="lg:col-span-2">
      <span class="inline-flex items-center gap-1.5 font-mono text-[10.5px] text-[#A22638] px-2.5 py-1 rounded-full mb-3"
            style="background:rgba(128,0,32,0.1);border:1px solid rgba(128,0,32,0.2);">
        <?= __($event['status'] === 'upcoming' ? 'পরবর্তী' : 'অতীত', strtoupper($event['status'] ?? 'UPCOMING')) ?>
      </span>
      <h1 class="font-serif text-[clamp(26px,4vw,36px)] font-semibold text-[#101820] mb-6 leading-tight"><?= e($event['title']) ?></h1>

      <?php if (!empty($event['cover_image'])): ?>
      <div class="rounded-3xl overflow-hidden mb-8 shadow-md">
        <img src="<?= asset('storage/events/' . e($event['cover_image'])) ?>" alt="" class="w-full object-cover max-h-[350px]">
      </div>
      <?php endif; ?>

      <article class="prose max-w-none text-[15px] text-[#12181F] leading-relaxed space-y-6">
        <?= nl2br(e($event['description'])) ?>
      </article>
    </div>

    <!-- Right: Sticky registration card -->
    <div>
      <div class="p-6 rounded-3xl sticky top-8" style="background:rgba(255,255,255,0.85);border:1px solid rgba(16,24,32,0.12);box-shadow:0 12px 30px -10px rgba(16,24,32,0.08);">
        <h3 class="font-serif text-[18px] font-semibold text-[#101820] mb-4"><?= __('ইভেন্ট বিবরণ', 'Event details') ?></h3>
        
        <div class="space-y-4 mb-6 text-[13.5px]">
          <div>
            <div class="text-[#6B7178]"><?= __('তারিখ ও সময়', 'Date & Time') ?></div>
            <div class="font-medium text-[#101820]"><?= date('d F Y · h:i A', strtotime($event['event_date'])) ?></div>
          </div>
          <div>
            <div class="text-[#6B7178]"><?= __('স্থান', 'Venue') ?></div>
            <div class="font-medium text-[#101820]"><?= e($event['venue'] ?? __('নির্ধারিত হবে', 'TBA')) ?></div>
          </div>
          
          <div>
            <div class="text-[#6B7178]"><?= __('নিবন্ধন ফি & টাইপ', 'Registration Type') ?></div>
            <?php if (($event['registration_type'] ?? 'free') === 'paid'): ?>
              <div class="font-bold text-[#800020] text-[15px]">
                💳 <?= __('৳', '৳') ?><?= number_format((float)($event['ticket_fee'] ?? 0)) ?>
              </div>
            <?php else: ?>
              <div class="font-bold text-[#2F8863] text-[14px]">
                🎟️ <?= __('সম্পূর্ণ ফ্রী (Free Ticket Pass)', 'Free Event') ?>
              </div>
            <?php endif; ?>
          </div>
        </div>

        <?php 
        $user = auth();
        $isLoggedIn = !empty($user);
        $pdo = \App\Services\Database::connection();
        $userReg = null;
        if ($isLoggedIn) {
            $stmtReg = $pdo->prepare("SELECT * FROM event_registrations WHERE event_id = ? AND user_id = ? LIMIT 1");
            $stmtReg->execute([$event['id'], $user['id']]);
            $userReg = $stmtReg->fetch();
        }
        ?>

        <?php if (!$isLoggedIn): ?>
          <a href="<?= url('/login') ?>" class="btn btn-ghost w-full text-center block"><?= __('নিবন্ধন করতে লগইন করুন', 'Log in to Register') ?></a>
        
        <?php elseif ($userReg): ?>
          <!-- Pass Received Card -->
          <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-900 text-center">
            <div class="text-[12px] font-mono uppercase text-emerald-700 font-bold mb-1">
              ✅ <?= $userReg['payment_status'] === 'paid' ? 'Paid Ticket Pass' : 'Confirmed Free Pass' ?>
            </div>
            <div class="font-mono text-[14px] font-bold text-[#101820] tracking-wider my-1 bg-white py-1 px-3 rounded-lg border border-emerald-300 inline-block">
              <?= e($userReg['pass_code'] ?? 'PASS-1234') ?>
            </div>
            <p class="text-[11.5px] text-emerald-800 mt-1">
              ইভেন্ট পাসটি আপনার রেজিস্টার্ড ইমেইলে (<strong><?= e($user['email']) ?></strong>) পাঠানো হয়েছে।
            </p>
          </div>

        <?php else: ?>
          <?php if (($event['registration_type'] ?? 'free') === 'paid'): ?>
            <!-- Paid Registration Form -->
            <form method="POST" action="<?= url('/events/' . e($event['slug']) . '/register') ?>" class="space-y-3">
              <?= csrf_field() ?>
              <div class="p-3 rounded-xl bg-amber-50 border border-amber-200 text-[12px] text-amber-900">
                পেমেন্ট সম্পন্ন হলে আপনার পাস কোড জেনারেট হয়ে ইমেইলে চলে যাবে।
              </div>
              <button type="submit" class="btn btn-gold w-full py-3 text-[14px] font-bold flex items-center justify-center gap-2">
                <i class="fa-solid fa-credit-card"></i>
                <?= __('পেমেন্ট করুন (৳'.number_format((float)$event['ticket_fee']).')', 'Pay Ticket Fee & Register') ?>
              </button>
            </form>

          <?php else: ?>
            <!-- Free Event RSVP Response Form -->
            <form method="POST" action="<?= url('/events/' . e($event['slug']) . '/register') ?>" class="space-y-3">
              <?= csrf_field() ?>
              <button type="submit" class="btn btn-gold w-full py-3 text-[14px] font-bold flex items-center justify-center gap-2"
                      style="background:linear-gradient(135deg,#2F8863,#153548);">
                <i class="fa-solid fa-[#2F8863] fa-circle-check"></i>
                <?= __('ইভেন্টে আসব (Confirm RSVP Pass)', 'Confirm Attendance & Get Pass') ?>
              </button>
            </form>
          <?php endif; ?>
        <?php endif; ?>

        <?php if ($event['is_crowdfunding'] && ($event['crowdfunding_goal'] ?? 0) > 0): 
          $percent = min(100, (int)(($raisedAmount / $event['crowdfunding_goal']) * 100));
        ?>
        <div class="mt-6 pt-6 border-t border-gray-200/80 space-y-3">
          <h4 class="font-serif text-[15px] font-semibold text-[#101820]"><?= __('ক্রাউড ফান্ডিং লক্ষ্য', 'Crowd Funding Goal') ?></h4>
          <div class="flex justify-between text-[12px] text-[#6B7178]">
            <span><?= __('উত্তোলিত:', 'Raised:') ?> <strong><?= __('৳', '৳') ?><?= number_format($raisedAmount) ?></strong></span>
            <span><?= __('লক্ষ্য:', 'Target:') ?> <?= __('৳', '৳') ?><?= number_format((float)$event['crowdfunding_goal']) ?></span>
          </div>
          <div class="w-full h-2 rounded-full bg-gray-100 overflow-hidden">
            <div class="h-full rounded-full bg-gradient-to-r from-[#800020] to-[#2F8863]" style="width: <?= $percent ?>%"></div>
          </div>
          <div class="text-[11px] text-[#6B7178] text-right font-mono"><?= $percent ?>% <?= __('উত্তোলিত', 'Raised') ?></div>
          
          <a href="<?= url('/donate?event_id=' . $event['id']) ?>" class="btn btn-gold w-full text-center inline-block py-2.5 text-[13px] font-semibold">
            <?= __('অনুদান ও সহযোগিতা করুন ৳', 'Support & Donate ৳') ?>
          </a>
        </div>
        <?php endif; ?>
      </div>
    </div>
  </div>
</div>
