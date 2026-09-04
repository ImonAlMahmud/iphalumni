<?php
/**
 * Mentorship Directory View — Connect with Alumni Mentors
 */
$pdo = \App\Services\Database::connection();

// Fetch alumni profiles registered as mentors
$stmt = $pdo->query("SELECT ap.*, u.name, u.email 
                     FROM alumni_profiles ap 
                     JOIN users u ON ap.user_id = u.id 
                     WHERE ap.is_mentor = 1 
                     ORDER BY ap.id DESC");
$mentors = $stmt->fetchAll();
?>
<div class="max-w-7xl mx-auto px-6 py-12 font-['Kalpurush']">

  <!-- Header Banner -->
  <div class="text-center max-w-3xl mx-auto mb-14">
    <div class="inline-flex items-center gap-2 px-3.5 py-1 rounded-full bg-[#2F8863]/10 text-[#2F8863] font-mono text-[11px] font-bold mb-3 uppercase">
      <i class="fa-solid fa-user-graduate"></i> CAREER & ACADEMIC GUIDANCE
    </div>
    <h1 class="font-serif text-[clamp(30px,4vw,42px)] font-bold text-[#101820] mb-3">
      <?= __('অ্যালামনাই মেনটরশিপ কানেক্ট', 'Alumni Mentorship Connect') ?>
    </h1>
    <p class="text-[15.5px] text-[#6B7178] leading-relaxed">
      <?= __('ইনস্টিটিউট অব পাবলিক হেলথ-এর অভিজ্ঞ প্রফেশনাল ও গবেষকদের সাথে যুক্ত হোন। ক্যারিয়ার পরামর্শ, উচ্চশিক্ষা এবং গবেষণায় দিকনির্দেশনা গ্রহণ করুন।',
            'Connect with experienced IPH alumni mentors. Get guidance on public health careers, higher studies, and research opportunities.') ?>
    </p>
  </div>

  <?php if (empty($mentors)): ?>
  <div class="p-12 text-center bg-white rounded-3xl border border-slate-200 shadow-sm max-w-xl mx-auto">
    <div class="w-16 h-16 rounded-full bg-rose-50 text-[#800020] flex items-center justify-center text-[24px] mx-auto mb-4">
      <i class="fa-solid fa-user-tie"></i>
    </div>
    <h3 class="font-bold text-[18px] text-[#101820] mb-2">কোনো নিবন্ধিত মেনটর পাওয়া যায়নি</h3>
    <p class="text-[14px] text-[#6B7178] mb-6">আপনি কি একজন অভিজ্ঞ প্রফেশনাল? আপনার মেম্বার পোর্ফাইল থেকে মেনটর হিসেবে রেজিস্টার করুন।</p>
    <?php if (is_logged_in()): ?>
      <a href="<?= url('/portal/profile') ?>" class="inline-flex items-center gap-2 px-6 py-3 rounded-2xl bg-[#800020] text-white font-semibold text-[14px]">
        <i class="fa-solid fa-user-pen"></i> মেনটর প্রোফাইল অন করুন
      </a>
    <?php endif; ?>
  </div>
  <?php else: ?>

  <!-- Mentors Grid -->
  <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
    <?php foreach ($mentors as $m): ?>
    <div class="p-7 rounded-3xl bg-white border border-slate-200/80 shadow-sm flex flex-col justify-between hover:-translate-y-1 transition-all">
      <div>
        <div class="flex items-center gap-4 mb-5">
          <div class="w-16 h-16 rounded-2xl overflow-hidden bg-slate-100 border border-slate-200 shrink-0">
            <?php if (!empty($m['photo'])): ?>
              <img src="<?= asset($m['photo']) ?>" alt="<?= e($m['name']) ?>" class="w-full h-full object-cover">
            <?php else: ?>
              <div class="w-full h-full flex items-center justify-center text-white font-bold text-[20px] bg-gradient-to-br from-[#800020] to-[#2F8863]">
                <?= initials($m['name']) ?>
              </div>
            <?php endif; ?>
          </div>
          <div>
            <h3 class="font-bold text-[17px] text-[#101820] leading-tight mb-1"><?= e($m['name']) ?></h3>
            <p class="text-[13px] text-[#800020] font-semibold"><?= e($m['designation'] ?: 'Public Health Specialist') ?></p>
            <span class="text-[12px] text-[#6B7178] font-mono"><?= e($m['organization'] ?: 'IPH Network') ?></span>
          </div>
        </div>

        <div class="mb-4">
          <span class="text-[11px] font-mono font-bold text-emerald-700 uppercase tracking-wider block mb-1">অভিজ্ঞতার ক্ষেত্র:</span>
          <p class="text-[13.5px] text-[#101820] font-medium bg-emerald-50/70 border border-emerald-100 p-2.5 rounded-xl">
            <?= e($m['mentor_expertise'] ?: 'Public Health Research, Epidemiology') ?>
          </p>
        </div>

        <?php if (!empty($m['mentor_bio'])): ?>
        <p class="text-[13.5px] text-[#6B7178] leading-relaxed line-clamp-3 mb-6">
          <?= e($m['mentor_bio']) ?>
        </p>
        <?php endif; ?>
      </div>

      <div class="pt-4 border-t border-slate-100 flex items-center justify-between">
        <span class="text-[12px] font-mono text-[#6B7178]"><i class="fa-solid fa-graduation-cap text-[#2F8863] mr-1"></i> Batch <?= e($m['graduation_year'] ?: 'Alumni') ?></span>
        
        <?php if (is_logged_in()): ?>
          <a href="<?= url('/directory/' . $m['id']) ?>" class="px-4 py-2 rounded-xl text-[13px] font-semibold text-white bg-[#800020] hover:bg-[#66001a] transition-all">
            <i class="fa-solid fa-comments mr-1"></i> কথা বলুন
          </a>
        <?php else: ?>
          <a href="<?= url('/login') ?>" class="px-4 py-2 rounded-xl text-[13px] font-semibold text-[#800020] bg-rose-50 hover:bg-rose-100 transition-all">
            লগইন করুন
          </a>
        <?php endif; ?>
      </div>
    </div>
    <?php endforeach; ?>
  </div>

  <?php endif; ?>
</div>
