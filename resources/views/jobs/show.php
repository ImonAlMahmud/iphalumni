<?php
/**
 * Single Job Details & Application Form View
 * Variables: $job, $isLoggedIn, $isVerifiedStudent, $studentInfo, $hasApplied, $user
 */
$user              = $user ?? (auth() ?? []);
$isLoggedIn        = $isLoggedIn ?? (!empty($user));
$hasApplied        = $hasApplied ?? false;
$isVerifiedStudent = $isVerifiedStudent ?? false;
$studentInfo       = $studentInfo ?? null;
$captchaQuestion   = $captchaQuestion ?? '3 + 4';
?>
<div class="max-w-4xl mx-auto px-6 py-14">

  <!-- Back Link -->
  <a href="<?= url('/jobs') ?>" class="inline-flex items-center gap-2 text-[13.5px] font-medium text-[#6B7178] hover:text-[#800020] transition-colors mb-6">
    <i class="fa-solid fa-arrow-left text-[12px]"></i>
    <?= __('সকল জব সার্কুলারে ফিরুন', 'Back to all jobs') ?>
  </a>

  <!-- Header Card -->
  <div class="p-8 rounded-3xl mb-8 relative overflow-hidden"
       style="background:rgba(255,255,255,0.9);border:1px solid rgba(16,24,32,0.08);backdrop-filter:blur(18px);box-shadow:0 10px 30px -10px rgba(16,24,32,0.08);">
    
    <div class="flex flex-col md:flex-row md:items-start justify-between gap-6">
      <div>
        <div class="flex items-center gap-2 flex-wrap mb-3">
          <span class="font-mono text-[11px] font-semibold text-[#800020] px-3 py-1 rounded-full"
                style="background:rgba(128,0,32,0.08);border:1px solid rgba(128,0,32,0.2);">
            <?= e($job['job_type']) ?>
          </span>

          <?php if ($job['visibility'] === 'public'): ?>
          <span class="font-mono text-[11px] text-[#2F8863] px-3 py-1 rounded-full border border-[#2F8863]/30 bg-[#2F8863]/10">
            🌐 <?= __('পাবলিক (Student Reference Verified Only)', 'Public (Verified Student Only)') ?>
          </span>
          <?php else: ?>
          <span class="font-mono text-[11px] text-[#800020] px-3 py-1 rounded-full border border-[#800020]/30 bg-[#800020]/10">
            🔒 <?= __('মেম্বার অনলি', 'Members Only') ?>
          </span>
          <?php endif; ?>
        </div>

        <h1 class="font-serif text-[clamp(24px,3vw,34px)] font-bold text-[#101820] leading-snug">
          <?= e($job['title']) ?>
        </h1>

        <div class="text-[16px] text-[#6B7178] font-medium mt-1">
          <i class="fa-solid fa-building mr-1.5 text-[#800020]"></i><?= e($job['company_name']) ?>
        </div>
      </div>

      <!-- Posted By Card & Edit Action -->
      <div class="flex items-center gap-3 shrink-0">
        <?php 
        $canEditJob = false;
        if (!empty($user)) {
            $isAdmin = (($user['role'] ?? '') === 'admin' || ($user['role'] ?? '') === 'super_admin');
            $isCreator = (int)$job['user_id'] === (int)$user['id'];
            $canEditJob = ($isCreator || $isAdmin);
        }
        if ($canEditJob):
        ?>
        <a href="<?= url('/portal/jobs/' . $job['id'] . '/edit') ?>"
           class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl text-[13px] font-semibold text-[#101820] bg-gray-100 hover:bg-gray-200 transition-colors border border-gray-200">
          <i class="fa-solid fa-pen-to-square text-[12px]"></i>
          <?= __('সম্পাদনা করুন', 'Edit Job') ?>
        </a>
        <?php endif; ?>

        <div class="p-3.5 rounded-2xl flex items-center gap-3 bg-white/80 border border-slate-200/80 shadow-sm">
          <?php if (!empty($job['poster_avatar'])): ?>
            <img src="<?= asset('storage/avatars/' . e($job['poster_avatar'])) ?>"
                 alt="<?= e($job['poster_name'] ?? 'Alumni') ?>"
                 class="w-10 h-10 rounded-full object-cover border border-[#800020]/20 shadow-sm shrink-0">
          <?php else: ?>
            <div class="w-10 h-10 rounded-full flex items-center justify-center font-serif font-semibold text-[14px] text-white shrink-0 shadow-sm"
                 style="background:linear-gradient(135deg,#800020,#2F8863);">
              <?= initials($job['poster_name'] ?? 'A') ?>
            </div>
          <?php endif; ?>
          <div>
            <div class="text-[10px] font-mono text-[#9CA3AF] uppercase font-semibold"><?= __('সার্কুলারদাতা অ্যালামনাই', 'Posted By Alumni') ?></div>
            <div class="text-[13.5px] font-bold text-[#101820]"><?= e($job['poster_name'] ?? 'Alumni') ?></div>
          </div>
        </div>
      </div>
    </div>

    <!-- Quick Meta -->
    <div class="grid grid-cols-2 md:grid-cols-3 gap-4 mt-6 pt-6 border-t border-gray-100 text-[13.5px]">
      <?php if (!empty($job['location'])): ?>
      <div>
        <div class="text-[11px] text-[#9CA3AF] font-mono uppercase mb-0.5"><?= __('অবস্থান', 'Location') ?></div>
        <div class="font-medium text-[#101820]"><i class="fa-solid fa-location-dot mr-1 text-[#800020]"></i><?= e($job['location']) ?></div>
      </div>
      <?php endif; ?>

      <?php if (!empty($job['salary_range'])): ?>
      <div>
        <div class="text-[11px] text-[#9CA3AF] font-mono uppercase mb-0.5"><?= __('বেতন / স্কেল', 'Salary Range') ?></div>
        <div class="font-medium text-[#101820]"><i class="fa-solid fa-money-bill-wave mr-1 text-[#2F8863]"></i><?= e($job['salary_range']) ?></div>
      </div>
      <?php endif; ?>

      <?php if (!empty($job['deadline'])): ?>
      <div>
        <div class="text-[11px] text-[#9CA3AF] font-mono uppercase mb-0.5"><?= __('আবেদনের শেষ তারিখ', 'Deadline') ?></div>
        <div class="font-medium text-[#A22638]"><i class="fa-regular fa-clock mr-1"></i><?= date('d F, Y', strtotime($job['deadline'])) ?></div>
      </div>
      <?php endif; ?>
    </div>
  </div>

  <!-- Job Description Body -->
  <div class="p-8 rounded-3xl mb-8 space-y-6"
       style="background:rgba(255,255,255,0.9);border:1px solid rgba(16,24,32,0.08);backdrop-filter:blur(18px);">
    <div>
      <h3 class="font-serif text-[18px] font-bold text-[#101820] mb-3 pb-2 border-b border-gray-100">
        <?= __('জব বিবরণী (Job Description)', 'Job Description') ?>
      </h3>
      <div class="text-[14.5px] text-[#4A5568] leading-relaxed whitespace-pre-line"><?= e($job['description']) ?></div>
    </div>

    <?php if (!empty($job['requirements'])): ?>
    <div>
      <h3 class="font-serif text-[18px] font-bold text-[#101820] mb-3 pb-2 border-b border-gray-100">
        <?= __('প্রয়োজনীয় যোগ্যতা (Requirements)', 'Requirements') ?>
      </h3>
      <div class="text-[14.5px] text-[#4A5568] leading-relaxed whitespace-pre-line"><?= e($job['requirements']) ?></div>
    </div>
    <?php endif; ?>

    <?php if (!empty($job['how_to_apply'])): ?>
    <div>
      <h3 class="font-serif text-[18px] font-bold text-[#101820] mb-3 pb-2 border-b border-gray-100">
        <?= __('আবেদন পদ্ধতি (How to Apply)', 'How to Apply') ?>
      </h3>
      <div class="text-[14.5px] text-[#4A5568] leading-relaxed whitespace-pre-line"><?= e($job['how_to_apply']) ?></div>
    </div>
    <?php endif; ?>
  </div>

  <!-- ── Application Section ── -->
  <div class="p-8 rounded-3xl"
       style="background:rgba(255,255,255,0.95);border:1.5px solid rgba(128,0,32,0.15);backdrop-filter:blur(18px);box-shadow:0 12px 36px -12px rgba(128,0,32,0.15);">
    <h3 class="font-serif text-[20px] font-bold text-[#101820] mb-4">
      <i class="fa-solid fa-paper-plane mr-2 text-[#800020]"></i><?= __('আবেদন করুন (Apply Now)', 'Apply for this position') ?>
    </h3>

    <?php if (($job['apply_type'] ?? 'portal') === 'external_link' && !empty($job['apply_link'])): ?>
      <!-- 🔗 External Website Link -->
      <div class="p-6 rounded-2xl bg-indigo-50/70 border border-indigo-200/80 text-[14px]">
        <div class="flex items-start gap-4">
          <div class="w-12 h-12 rounded-2xl bg-[#800020] text-white flex items-center justify-center text-[20px] shrink-0 shadow-md">
            🔗
          </div>
          <div class="flex-1">
            <h4 class="font-bold text-[16px] text-[#101820] mb-1"><?= __('বাহ্যিক ওয়েবসাইটে আবেদন', 'Apply via External Website') ?></h4>
            <p class="text-[13.5px] text-[#6B7178] mb-4 leading-relaxed">
              <?= __('প্রতিষ্ঠানের নিজস্ব ক্যারিয়ার পোর্টালে আবেদনের জন্য নিচের বাটনে ক্লিক করে সরাসরি ওয়েবসাইটে যান।',
                    'Click the button below to be redirected to the organization’s official career portal.') ?>
            </p>
            <a href="<?= e($job['apply_link']) ?>" target="_blank" rel="noopener noreferrer"
               class="inline-flex items-center gap-2 px-6 py-3 rounded-xl text-[14px] font-semibold text-white transition-all shadow-lg hover:-translate-y-0.5"
               style="background:linear-gradient(135deg,#A22638,#800020);">
              <?= __('ওয়েবসাইটে গিয়া আবেদন করুন', 'Apply on Company Website') ?>
              <i class="fa-solid fa-arrow-up-right-from-square text-[12px]"></i>
            </a>
          </div>
        </div>
      </div>

    <?php elseif (($job['apply_type'] ?? 'portal') === 'email' && !empty($job['apply_email'])): ?>
      <!-- ✉️ Direct HR Email -->
      <div class="p-6 rounded-2xl bg-emerald-50/70 border border-emerald-200/80 text-[14px]">
        <div class="flex items-start gap-4">
          <div class="w-12 h-12 rounded-2xl bg-[#2F8863] text-white flex items-center justify-center text-[20px] shrink-0 shadow-md">
            ✉️
          </div>
          <div class="flex-1">
            <h4 class="font-bold text-[16px] text-[#101820] mb-1"><?= __('সরাসরি ইমেইলে সিভি পাঠান', 'Apply via Direct HR Email') ?></h4>
            <p class="text-[13.5px] text-[#6B7178] mb-3 leading-relaxed">
              <?= __('আপনার সিভি ও কভার লেটার নিচে উল্লেখিত ইমেইল ঠিকানায় সরাসরি মেইল করুন:',
                    'Please send your CV and Cover Letter directly to the following HR email address:') ?>
            </p>
            <div class="inline-flex items-center gap-3 p-3.5 rounded-xl bg-white border border-emerald-300 font-mono text-[15px] font-bold text-[#101820] mb-3">
              <i class="fa-solid fa-envelope text-[#2F8863]"></i>
              <a href="mailto:<?= e($job['apply_email']) ?>?subject=Application%20for%20<?= urlencode($job['title']) ?>" class="hover:underline text-[#800020]">
                <?= e($job['apply_email']) ?>
              </a>
            </div>
            <div class="text-[12.5px] text-[#6B7178]">
              * <?= __('ইমেলের সাবজেক্ট লাইনে পদের নাম (<strong>'.e($job['title']).'</strong>) উল্লেখ করার পরামর্শ দেয়া যাচ্ছে।', 'Recommendation: Mention the job title in the email subject line.') ?>
            </div>
          </div>
        </div>
      </div>

    <?php else: ?>
      <!-- 📌 Default IPH Portal Form Application -->
      <?php if (!$isLoggedIn && ($job['visibility'] ?? 'members') === 'members'): ?>
        <!-- Guest Notice (Only for Members-Only Jobs) -->
        <div class="p-5 rounded-2xl bg-amber-50 border border-amber-200 text-amber-900 text-[14px]">
          <p class="font-semibold mb-1">🔒 <?= __('মেম্বার-অনলি পদে আবেদন করতে লগইন প্রয়োজন', 'Login required for member-only jobs') ?></p>
          <p class="text-[13px] text-amber-800 mb-4">
            <?= __('এই পদে শুধুমাত্র রেজিস্টার্ড ও ভেরিফাইড আইপিএইচ অ্যালামনাই সদস্যরা আবেদন করতে পারেন। আবেদন করতে অনুগ্রহ করে আপনার অ্যাকাউন্টে লগইন করুন।', 'Only registered & verified IPH alumni members can apply for this member-only job. Please log in to proceed.') ?>
          </p>
          <a href="<?= url('/login') ?>" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl font-semibold text-white text-[13.5px]" style="background:#800020;">
            <i class="fa-solid fa-right-to-bracket text-[12px]"></i>
            <?= __('লগইন করুন', 'Log in Now') ?>
          </a>
        </div>

      <?php elseif ($hasApplied): ?>
        <!-- Already Applied Notice -->
        <div class="p-5 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-900 text-[14px] flex items-center gap-3">
          <i class="fa-solid fa-circle-check text-[20px] text-[#2F8863]"></i>
          <div>
            <div class="font-semibold"><?= __('আবেদন সফলভাবে জমা হয়েছে', 'Application Already Submitted') ?></div>
            <div class="text-[13px] text-emerald-700"><?= __('আপনি ইতিমধ্যে এই জব পোস্টটিতে সফলভাবে আবেদন করেছেন।', 'You have already applied for this job posting.') ?></div>
          </div>
        </div>

      <?php else: ?>
        <?php if ($job['visibility'] === 'public'): ?>
        <!-- Public Job Verification Notice -->
        <div class="mb-6 p-5 rounded-2xl bg-amber-50/70 border border-amber-200/80 text-[13.5px] text-amber-900">
          <div class="flex items-start gap-3">
            <i class="fa-solid fa-user-shield text-[20px] text-[#800020] mt-0.5 shrink-0"></i>
            <div>
              <div class="font-bold text-[14.5px] text-[#101820] mb-1">
                🌐 <?= __('পাবলিক জব ভেরিফিকেশন নির্দেশিকা (Student Reference Verification)', 'Public Job Verification Guidelines') ?>
              </div>
              <p class="text-[#6B7178] leading-relaxed mb-2">
                <?= __('এই পাবলিক জবে আইপিএইচ এর সাবেক/রানিং ছাত্র-ছাত্রীরা সরাসরি আবেদন করতে পারবেন (লগইন করা বাধ্যতামূলক নয়)। ভর্তি ফর্ম অনুযায়ী আপনার <strong>সঠিক নামের বানান (Name)</strong> এবং <strong>নিজের মোবাইল / অভিভাবকের মোবাইল (Mobile / Guardian Mobile)</strong> প্রদান করুন। ডাটাবেজের তথ্যের সাথে মিললেই আপনার আবেদন সফলভাবে গৃহীত হবে।',
                      'Students/graduates can apply directly to this public job without login. Enter your Name and Mobile/Guardian Mobile number as given during admission.') ?>
              </p>
            </div>
          </div>
        </div>
        <?php endif; ?>

        <!-- Application Form -->
        <form method="POST" action="<?= url('/jobs/apply') ?>" enctype="multipart/form-data" class="space-y-4">
          <?= csrf_field() ?>
          <input type="hidden" name="job_id" value="<?= $job['id'] ?>">

          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
              <label class="block text-[12.5px] font-medium text-[#101820] mb-1.5">
                <?= __('আবেদনকারীর নাম (IPH ভর্তি ফর্মের সঠিক বানান)', 'Applicant Name (Proper Admission Spelling)') ?> <span class="text-red-500">*</span>
              </label>
              <input type="text" name="applicant_name" value="<?= e($user['name'] ?? '') ?>" required
                     placeholder="e.g. Mahmudur Rahman Imon"
                     class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-[13.5px] focus:outline-none focus:border-[#800020]">
            </div>
            <div>
              <label class="block text-[12.5px] font-medium text-[#101820] mb-1.5"><?= __('ইমেইল অ্যাড্রেস', 'Email Address') ?> <span class="text-red-500">*</span></label>
              <input type="email" name="applicant_email" value="<?= e($user['email'] ?? '') ?>" required
                     placeholder="e.g. example@domain.com"
                     class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-[13.5px] bg-gray-50 focus:bg-white focus:outline-none focus:border-[#800020]">
            </div>
          </div>

          <div>
            <label class="block text-[12.5px] font-medium text-[#101820] mb-1.5">
              <?= __('মোবাইল / গার্ডিয়ান মোবাইল নম্বর (IPH ভর্তির সময় প্রদত্ত)', 'Mobile / Guardian Mobile Number (As in Admission)') ?> <span class="text-red-500">*</span>
            </label>
            <input type="text" name="applicant_phone" value="<?= e($user['phone'] ?? '') ?>" required placeholder="017xxxxxxxx"
                   class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-[13.5px] focus:outline-none focus:border-[#800020]">
            <span class="text-[11.5px] text-[#6B7178] mt-1 block">
              * <?= __('ভর্তি ফর্মে ব্যবহৃত আপনার নিজস্ব ফোন নম্বর অথবা অভিভাবকের ফোন নম্বর দিন।', 'Use your personal mobile or guardian mobile number provided during admission.') ?>
            </span>
          </div>

          <div>
            <label class="block text-[12.5px] font-medium text-[#101820] mb-1.5"><?= __('আপনার রিজুমি / সিভি (PDF/DOCX)', 'Resume / CV File') ?></label>
            <input type="file" name="resume" accept=".pdf,.doc,.docx" required
                   class="w-full px-3 py-2 rounded-xl border border-gray-200 text-[13px] bg-white file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-[12.5px] file:font-semibold file:bg-[#800020]/10 file:text-[#800020]">
          </div>

          <div>
            <label class="block text-[12.5px] font-medium text-[#101820] mb-1.5"><?= __('কভার লেটার / সংক্ষিপ্ত বার্তা', 'Cover Letter / Short Message') ?></label>
            <textarea name="cover_letter" rows="3" placeholder="<?= __('ক্যানডিডেট হিসেবে আপনার অভিজ্ঞতা ও আবেদনের উদ্দেশ্য সংক্ষেপে লিখুন...', 'Briefly explain your suitability and motivation...') ?>"
                      class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-[13.5px] focus:outline-none focus:border-[#800020]"></textarea>
          </div>

          <?php if (!$isLoggedIn): ?>
          <!-- Math Captcha for Guest Applicants -->
          <div class="p-4 rounded-2xl border border-amber-200/80 bg-amber-50/50 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
            <div class="flex items-center gap-3">
              <div class="w-9 h-9 rounded-xl bg-[#800020]/10 text-[#800020] flex items-center justify-center text-[16px] shrink-0">
                🧮
              </div>
              <div>
                <label for="captcha_answer" class="block text-[13px] font-bold text-[#101820]">
                  <?= __('হিউম্যান ভেরিফিকেশন (Math Captcha)', 'Human Security Verification') ?> <span class="text-red-500">*</span>
                </label>
                <div class="text-[12px] text-[#6B7178] mt-0.5">
                  <?= __('যোগফলটি লিখুন:', 'Calculate:') ?> <strong class="font-mono text-[#800020] text-[14px] bg-white px-2 py-0.5 rounded-lg border border-gray-200 ml-1"><?= e($captchaQuestion) ?> = ?</strong>
                </div>
              </div>
            </div>
            <div class="w-full sm:w-32">
              <input type="number" name="captcha_answer" id="captcha_answer" required placeholder="উত্তর দিন"
                     class="w-full px-3.5 py-2 rounded-xl border border-gray-300 text-[14px] font-bold text-center focus:outline-none focus:border-[#800020] bg-white">
            </div>
          </div>
          <?php endif; ?>

          <button type="submit" class="w-full py-3.5 rounded-2xl font-semibold text-white text-[14.5px] transition-all hover:shadow-xl"
                  style="background:linear-gradient(135deg,#A22638,#800020);">
            <i class="fa-solid fa-paper-plane mr-2"></i>
            <?= __('আবেদন সাবমিট করুন', 'Submit Application') ?>
          </button>
        </form>
      <?php endif; ?>
    <?php endif; ?>
  </div>

  <!-- Job Alert Mini Subscription Card -->
  <div class="mt-8 p-6 rounded-3xl border border-gray-200/80 bg-white/90 shadow-sm flex flex-col md:flex-row items-center justify-between gap-6">
    <div class="flex items-center gap-4">
      <div class="w-12 h-12 rounded-2xl bg-rose-50 text-[#800020] border border-rose-100 flex items-center justify-center text-xl shrink-0">
        <i class="fa-solid fa-bell"></i>
      </div>
      <div>
        <h4 class="font-serif font-bold text-[16px] text-[#101820]"><?= __('অনুরূপ চাকরির বিজ্ঞপ্তি ইমেইলে পেতে চান?', 'Looking for similar opportunities?') ?></h4>
        <p class="text-[13px] text-[#6B7178]"><?= __('আইপিএইচ সার্কুলার এলার্ট সাবস্ক্রাইব করুন এবং নতুন সার্কুলার পোস্ট হলেই সরাসরি নোটিফিকেশন পান।', 'Subscribe to job alerts and receive fresh openings directly.') ?></p>
      </div>
    </div>
    <form method="POST" action="<?= url('/jobs/subscribe') ?>" class="flex items-center gap-2 w-full md:w-auto">
      <?= csrf_field() ?>
      <input type="email" name="email" required
             value="<?= e(auth_user()['email'] ?? '') ?>"
             placeholder="<?= __('আপনার ইমেইল...', 'Your email...') ?>"
             class="px-4 py-2 rounded-xl border border-gray-300 text-[13px] text-[#101820] focus:outline-none focus:border-[#800020] bg-white w-full sm:w-64">
      <button type="submit"
              class="px-5 py-2 rounded-xl text-[13px] font-semibold text-white bg-[#800020] hover:bg-[#A22638] transition-all shadow shrink-0">
        <?= __('এলার্ট পান', 'Get Alerts') ?>
      </button>
    </form>
  </div>

</div>
