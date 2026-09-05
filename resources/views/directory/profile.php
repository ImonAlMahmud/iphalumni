<?php
/**
 * Public Alumni Profile Details View
 * Variables: $alumni, $education, $employment
 */
$isLoggedIn = auth() !== null;
?>
<div class="max-w-7xl mx-auto py-10 px-6 space-y-8 font-['Kalpurush']">
  
  <div class="mb-2">
    <a href="<?= url('/directory') ?>" class="text-[13px] font-medium text-gray-500 hover:text-[#800020] inline-flex items-center gap-1.5 transition-colors">
      ← <?= __('ডিরেক্টরি ফিরে যান', 'Back to Directory') ?>
    </a>
  </div>

  <!-- Profile Card Header -->
  <div class="p-8 md:p-10 rounded-3xl bg-white border border-slate-200/80 shadow-sm flex flex-col md:flex-row items-center md:items-start gap-8">
    <div class="relative w-36 h-36 shrink-0">
      <div class="absolute -inset-1 rounded-full bg-gradient-to-r from-[#800020]/40 to-[#2F8863]/40 blur opacity-40"></div>
      <div class="relative w-full h-full rounded-full overflow-hidden border-4 border-white bg-gray-50 shadow-md">
        <?php if (!empty($alumni['avatar'])): ?>
        <img src="<?= avatar_url($alumni['avatar']) ?>" alt="Avatar" class="w-full h-full object-cover" onerror="this.onerror=null; this.parentElement.innerHTML='<div class=\'w-full h-full flex items-center justify-center font-serif text-[46px] text-white\' style=\'background:linear-gradient(135deg,#800020,#2F8863);\'>' + '<?= initials($alumni['name']) ?>' + '</div>';">
        <?php else: ?>
        <div class="w-full h-full flex items-center justify-center font-serif text-[46px] text-white" style="background:linear-gradient(135deg,#800020,#2F8863);">
          <?= initials($alumni['name']) ?>
        </div>
        <?php endif; ?>
      </div>
    </div>

    <div class="flex-1 text-center md:text-left space-y-3">
      <div class="flex flex-wrap items-center justify-center md:justify-start gap-3">
        <h2 class="font-serif text-[28px] font-bold text-gray-900"><?= e($alumni['name']) ?></h2>
        <?php if ($alumni['batch_year']): ?>
        <span class="px-3.5 py-1 rounded-full bg-[#800020]/10 text-[#800020] text-[12px] font-bold font-mono border border-[#800020]/20">
          <?= __('ব্যাচ', 'Batch') ?> <?= e($alumni['batch_year']) ?>
        </span>
        <?php endif; ?>
        <span class="px-3 py-1 rounded-full bg-emerald-100 text-emerald-800 text-[11px] font-bold font-mono">
          ✓ Verified Alumni
        </span>
      </div>

      <?php if (!empty($alumni['bio'])): ?>
      <p class="text-[15px] text-gray-600 leading-relaxed italic max-w-3xl">
        "<?= e($alumni['bio']) ?>"
      </p>
      <?php endif; ?>

      <div class="flex flex-wrap items-center justify-center md:justify-start gap-6 pt-2 text-[13.5px] text-gray-500">
        <?php if (($alumni['location_type'] ?? '') === 'abroad' || !empty($alumni['country'])): ?>
        <div class="flex items-center gap-1.5 font-semibold text-gray-700">✈️ <?= e($alumni['country'] ?? '') ?><?= !empty($alumni['province_city']) ? ' (' . e($alumni['province_city']) . ')' : '' ?></div>
        <?php elseif (!empty($alumni['current_location'])): ?>
        <div class="flex items-center gap-1.5 font-semibold text-gray-700">📍 <?= e($alumni['current_location']) ?><?= !empty($alumni['thana_upazila']) ? ', ' . e($alumni['thana_upazila']) : '' ?></div>
        <?php endif; ?>

        <?php if (!empty($alumni['website'])): ?>
        <a href="<?= e($alumni['website']) ?>" target="_blank" class="text-[#800020] font-medium hover:underline flex items-center gap-1">🌐 <?= __('ওয়েবসাইট', 'Website') ?></a>
        <?php endif; ?>

        <?php if (!empty($alumni['linkedin_url'])): ?>
        <a href="<?= e($alumni['linkedin_url']) ?>" target="_blank" class="text-blue-600 font-medium hover:underline flex items-center gap-1">🔗 LinkedIn</a>
        <?php endif; ?>

        <?php if (!empty($alumni['facebook_url'])): ?>
        <a href="<?= e($alumni['facebook_url']) ?>" target="_blank" class="text-blue-600 font-medium hover:underline flex items-center gap-1">🔗 Facebook</a>
        <?php endif; ?>

        <?php if (!empty($alumni['google_scholar_url'])): ?>
        <a href="<?= e($alumni['google_scholar_url']) ?>" target="_blank" class="text-blue-700 font-medium hover:underline flex items-center gap-1">🎓 Google Scholar</a>
        <?php endif; ?>

        <?php if (!empty($alumni['researchgate_url'])): ?>
        <a href="<?= e($alumni['researchgate_url']) ?>" target="_blank" class="text-emerald-700 font-medium hover:underline flex items-center gap-1">🔬 ResearchGate</a>
        <?php endif; ?>
      </div>
    </div>
  </div>

  <!-- Grid Layout utilizing full screen width -->
  <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
    
    <!-- Left Column: Contact & Quick Badges (4 cols) -->
    <div class="lg:col-span-4 space-y-6">
      
      <!-- Mentorship & Support Badges Card -->
      <?php if (!empty($alumni['willing_to_mentor']) || !empty($alumni['job_referral']) || !empty($alumni['blood_group'])): ?>
      <div class="p-6 rounded-3xl bg-emerald-50/80 border border-emerald-100 shadow-sm space-y-3">
        <h4 class="font-serif text-[16px] font-bold text-emerald-950 flex items-center gap-2">
          <span>🤝</span> Mentorship & Support (মেন্টরশিপ)
        </h4>
        <div class="space-y-2 text-[13px]">
          <?php if (!empty($alumni['blood_group'])): ?>
          <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-rose-100 text-rose-800 font-bold font-mono text-[12px] mr-2">
            🩸 Blood Group: <?= e($alumni['blood_group']) ?>
          </div>
          <?php endif; ?>

          <?php if (!empty($alumni['willing_to_mentor'])): ?>
          <div class="flex items-center gap-2 text-emerald-900 font-semibold bg-white/80 p-2.5 rounded-xl border border-emerald-200/60">
            <span>✅</span> <?= __('জুনিয়রদের মেন্টরশিপ দিতে ইচ্ছুক (Willing to Mentor)', 'Willing to Mentor Juniors') ?>
          </div>
          <?php endif; ?>

          <?php if (!empty($alumni['job_referral'])): ?>
          <div class="flex items-center gap-2 text-emerald-900 font-semibold bg-white/80 p-2.5 rounded-xl border border-emerald-200/60">
            <span>💼</span> <?= __('জব/ইন্টার্নশিপ রেফারেল প্রদানে আগ্রহী', 'Can Help with Job Referrals') ?>
          </div>
          <?php endif; ?>

          <?php if (!empty($alumni['contribution_areas'])): ?>
          <div class="text-[12.5px] text-emerald-800 pt-1">
            <strong><?= __('সাহায্যের ক্ষেত্রসমূহ:', 'Help Areas:') ?></strong> <?= e($alumni['contribution_areas']) ?>
          </div>
          <?php endif; ?>
        </div>
      </div>
      <?php endif; ?>

      <!-- Contact Details -->
      <div class="p-7 rounded-3xl bg-white border border-slate-200/80 shadow-sm space-y-6">
        <h3 class="font-serif text-[18px] font-bold text-gray-800 border-b border-gray-100 pb-3.5 flex items-center gap-2">
          <i class="fa-solid fa-address-card text-[#800020]"></i>
          <?= __('যোগাযোগের তথ্য', 'Contact Details') ?>
        </h3>
        
        <!-- Request Contact Button & Details -->
        <div class="pt-2 border-t border-gray-100 space-y-3">
          <button type="button" onclick="document.getElementById('contact_request_modal').classList.remove('hidden')"
                  class="w-full py-3.5 px-4 rounded-2xl bg-[#800020] hover:bg-[#66001a] text-white font-semibold text-[14px] shadow-md transition-all flex items-center justify-center gap-2">
            <span>💬</span> <?= __('যোগাযোগের অনুরোধ করুন (Request Contact)', 'Request Contact') ?>
          </button>
          <span class="text-[11.5px] text-gray-500 block text-center leading-normal">
            <?= __('অনুরোধ সাবমিট করলে অ্যালামনাই সদস্য অনুমতি (Accept) দিলে তার যোগাযোগের তথ্য আপনার ইমেইলে পৌছে যাবে।', 'Submitting a request sends your topic to the alumni. Once accepted, contact details will be emailed to you.') ?>
          </span>
        </div>

        <?php if ($isLoggedIn): ?>
          <div class="space-y-4 text-[14px]">
            <div class="p-3.5 rounded-2xl bg-gray-50/80 border border-gray-100">
              <span class="text-gray-400 text-[11px] font-mono uppercase block mb-1"><?= __('ইমেইল ঠিকানা', 'EMAIL ADDRESS') ?></span>
              <a href="mailto:<?= e($alumni['email']) ?>" class="text-blue-600 font-medium hover:underline break-all"><?= e($alumni['email']) ?></a>
            </div>
            <?php if (!empty($alumni['phone'])): ?>
            <div class="p-3.5 rounded-2xl bg-gray-50/80 border border-gray-100">
              <span class="text-gray-400 text-[11px] font-mono uppercase block mb-1"><?= __('ফোন নাম্বার', 'PHONE NUMBER') ?></span>
              <a href="tel:<?= e($alumni['phone']) ?>" class="text-gray-800 font-semibold font-mono hover:text-[#800020]"><?= e($alumni['phone']) ?></a>
            </div>
            <?php endif; ?>

            <?php if (!empty($alumni['permanent_district'])): ?>
            <div class="p-3.5 rounded-2xl bg-gray-50/80 border border-gray-100">
              <span class="text-gray-400 text-[11px] font-mono uppercase block mb-1"><?= __('স্থায়ী ঠিকানা (PERMANENT DISTRICT)', 'PERMANENT DISTRICT') ?></span>
              <span class="text-gray-800 font-medium">🏠 <?= e($alumni['permanent_district']) ?><?= !empty($alumni['permanent_upazila']) ? ', ' . e($alumni['permanent_upazila']) : '' ?></span>
            </div>
            <?php endif; ?>

            <?php if (!empty($alumni['emergency_contact_name'])): ?>
            <div class="p-3.5 rounded-2xl bg-rose-50/60 border border-rose-100">
              <span class="text-rose-700 text-[11px] font-mono font-bold uppercase block mb-1">🚨 <?= __('জরুরি যোগাযোগ (EMERGENCY CONTACT)', 'EMERGENCY CONTACT') ?></span>
              <span class="text-gray-800 font-medium block"><?= e($alumni['emergency_contact_name']) ?></span>
              <?php if (!empty($alumni['emergency_contact_phone'])): ?>
              <span class="text-gray-600 font-mono text-[12.5px]"><?= e($alumni['emergency_contact_phone']) ?></span>
              <?php endif; ?>
            </div>
            <?php endif; ?>
          </div>
        <?php else: ?>
          <div class="p-5 rounded-2xl bg-amber-50/80 border border-amber-200/60 text-center">
            <p class="text-[13px] text-amber-900 leading-relaxed">
              🔒 <?= __('যোগাযোগের তথ্য সীমাবদ্ধ করা হয়েছে। প্রাইভেট কন্টাক্ট ইনফো দেখতে দয়া করে', 'Contact information is restricted. Please') ?> <a href="<?= url('/login') ?>" class="text-[#800020] font-bold hover:underline"><?= __('লগইন করুন', 'Log in') ?></a> <?= __('।', '.') ?>
            </p>
          </div>
        <?php endif; ?>
      </div>
    </div>

  <!-- Contact Request Modal -->
  <div id="contact_request_modal" class="fixed inset-0 z-50 bg-black/60 backdrop-blur-sm flex items-center justify-center p-4 hidden">
    <div class="bg-white rounded-3xl max-w-lg w-full p-7 space-y-5 shadow-2xl relative animate-in fade-in zoom-in duration-200">
      <div class="flex items-center justify-between border-b border-gray-100 pb-3">
        <h3 class="font-serif text-[19px] font-bold text-gray-900 flex items-center gap-2">
          <span>💬</span> <?= __('যোগাযোগের অনুরোধ (Request Contact)', 'Request Contact') ?>
        </h3>
        <button type="button" onclick="document.getElementById('contact_request_modal').classList.add('hidden')" class="text-gray-400 hover:text-gray-600 text-xl font-bold">✕</button>
      </div>

      <p class="text-[13.5px] text-gray-600 leading-relaxed">
        <?= __('সদস্য', 'Alumni') ?> <strong class="text-[#800020]"><?= e($alumni['name']) ?></strong> <?= __('এর সাথে যোগাযোগের কারণ ও আলোচনার বিষয়সংক্ষেপ লিখুন। তিনি আপনার অনুরোধ গ্রহণ (Accept) করলে তার মোবাইল/ইমেইল আপনার ইমেইলে পাঠিয়ে দেওয়া হবে।', 'State your discussion topic. Once approved, contact details will be emailed to you.') ?>
      </p>

      <?php
        $num1 = rand(1, 9);
        $num2 = rand(1, 9);
        $_SESSION['captcha_answer'] = $num1 + $num2;
        $captchaQuestion = "{$num1} + {$num2} = ?";
      ?>

      <form action="<?= url('/directory/' . $alumni['id'] . '/request-contact') ?>" method="POST" class="space-y-4">
        <?= csrf_field() ?>

        <div>
          <label class="block text-[13px] font-semibold text-gray-800 mb-1" for="req_name"><?= __('আপনার নাম (Your Name)', 'Your Name') ?> *</label>
          <input type="text" id="req_name" name="requester_name" required placeholder="যেমন: ড. রফিকুল ইসলাম" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-[14px]">
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
          <div>
            <label class="block text-[13px] font-semibold text-gray-800 mb-1" for="req_email"><?= __('আপনার ইমেইল (Your Email)', 'Your Email') ?> *</label>
            <input type="email" id="req_email" name="requester_email" required placeholder="name@example.com" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-[14px]">
          </div>
          <div>
            <label class="block text-[13px] font-semibold text-gray-800 mb-1" for="req_phone"><?= __('আপনার ফোন নাম্বার (Optional)', 'Phone') ?></label>
            <input type="tel" id="req_phone" name="requester_phone" placeholder="017xxxxxxxx" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-[14px]">
          </div>
        </div>

        <div>
          <label class="block text-[13px] font-semibold text-gray-800 mb-1" for="req_topic"><?= __('আলোচনার বিষয় (Discussion Topic)', 'Discussion Topic') ?> *</label>
          <input type="text" id="req_topic" name="discussion_topic" required placeholder="যেমন: Public Health Research Collaboration / Higher Study Advice" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-[14px]">
        </div>

        <div>
          <label class="block text-[13px] font-semibold text-gray-800 mb-1" for="req_brief"><?= __('বার্তার সারসংক্ষেপ (Brief Message)', 'Brief Message') ?> *</label>
          <textarea id="req_brief" name="brief_message" rows="3" required placeholder="আপনার পরিচয় ও কী বিষয়ে আলোচনা করতে চান তা সংক্ষেপে লিখুন..." class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-[14px] resize-none"></textarea>
        </div>

        <!-- Captcha -->
        <div class="p-3.5 rounded-2xl bg-rose-50/70 border border-rose-100 flex items-center justify-between gap-3">
          <div>
            <label class="block text-[12.5px] font-bold text-[#800020]" for="req_captcha">
              🛡️ Security Question: <span class="font-mono text-[15px] text-[#800020] bg-white px-2.5 py-0.5 rounded-lg border border-rose-200 ml-1"><?= $captchaQuestion ?></span>
            </label>
          </div>
          <input type="number" id="req_captcha" name="captcha_input" required placeholder="?" class="w-20 px-3 py-2 rounded-xl text-center font-bold text-[15px] bg-white border border-rose-300">
        </div>

        <div class="flex items-center justify-end gap-3 pt-2">
          <button type="button" onclick="document.getElementById('contact_request_modal').classList.add('hidden')" class="px-5 py-2.5 rounded-xl border border-gray-200 text-[13.5px] font-medium text-gray-600 hover:bg-gray-50">Cancel</button>
          <button type="submit" class="px-6 py-2.5 rounded-xl bg-[#800020] hover:bg-[#66001a] text-white text-[13.5px] font-bold shadow-md">
            <?= __('অনুরোধ পাঠান (Submit Request)', 'Submit Request') ?>
          </button>
        </div>
      </form>
    </div>
  </div>

    <!-- Right Column: Education, Skills, Publications & Employment (8 cols) -->
    <div class="lg:col-span-8 space-y-8">
      
      <!-- Specialization & Skills Card -->
      <?php if (!empty($alumni['specialization']) || !empty($alumni['skills']) || !empty($alumni['experience_years']) || !empty($alumni['session_years'])): ?>
      <div class="p-8 rounded-3xl bg-white border border-slate-200/80 shadow-sm space-y-5">
        <h3 class="font-serif text-[20px] font-bold text-gray-800 flex items-center gap-2">
          <span>⚡</span> <?= __('বিশেষজ্ঞতা ও দক্ষতা', 'Specialization & Expertise') ?>
        </h3>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <?php if (!empty($alumni['specialization'])): ?>
          <div class="p-4 rounded-2xl bg-slate-50 border border-slate-100">
            <span class="text-[11px] font-mono text-gray-400 uppercase block mb-1">SPECIALIZATION</span>
            <span class="font-semibold text-gray-800 text-[14.5px]"><?= e($alumni['specialization']) ?></span>
          </div>
          <?php endif; ?>

          <?php if (!empty($alumni['experience_years'])): ?>
          <div class="p-4 rounded-2xl bg-slate-50 border border-slate-100">
            <span class="text-[11px] font-mono text-gray-400 uppercase block mb-1">TOTAL EXPERIENCE</span>
            <span class="font-bold text-[#800020] text-[15px] font-mono"><?= e($alumni['experience_years']) ?></span>
          </div>
          <?php endif; ?>

          <?php if (!empty($alumni['session_years'])): ?>
          <div class="p-4 rounded-2xl bg-slate-50 border border-slate-100">
            <span class="text-[11px] font-mono text-gray-400 uppercase block mb-1">ACADEMIC SESSION</span>
            <span class="font-mono font-semibold text-gray-800 text-[14px]"><?= e($alumni['session_years']) ?></span>
          </div>
          <?php endif; ?>
        </div>

        <?php if (!empty($alumni['skills'])): ?>
        <div class="pt-2">
          <span class="text-[12px] font-bold text-gray-700 block mb-2">KEY SKILLS & COMPETENCIES:</span>
          <div class="flex flex-wrap gap-2">
            <?php foreach (array_map('trim', explode(',', $alumni['skills'])) as $sk): if (!$sk) continue; ?>
            <span class="px-3 py-1 rounded-xl bg-[#800020]/5 text-[#800020] border border-[#800020]/15 font-mono text-[12px] font-semibold">
              #<?= e($sk) ?>
            </span>
            <?php endforeach; ?>
          </div>
        </div>
        <?php endif; ?>
      </div>
      <?php endif; ?>

      <!-- Education -->
      <div class="p-8 rounded-3xl bg-white border border-slate-200/80 shadow-sm">
        <h3 class="font-serif text-[20px] font-bold text-gray-800 mb-6 flex items-center gap-2">
          <span>🎓</span> <?= __('শিক্ষা ও ডিগ্রি', 'Education & Degrees') ?>
        </h3>
        <?php if (empty($education)): ?>
        <p class="text-[14px] text-gray-400 py-2"><?= __('কোনো শিক্ষার ইতিহাস রেকর্ড নেই।', 'No education history recorded.') ?></p>
        <?php else: ?>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
          <?php foreach ($education as $edu): ?>
          <div class="p-5 rounded-2xl bg-slate-50/80 border border-slate-100 flex items-start gap-4">
            <div class="w-10 h-10 rounded-xl bg-[#800020]/10 text-[#800020] flex items-center justify-center text-[18px] shrink-0 font-bold">🎓</div>
            <div>
              <h4 class="font-bold text-gray-900 text-[15px]"><?= e($edu['degree']) ?> <?= !empty($edu['field_of_study']) ? 'in ' . e($edu['field_of_study']) : '' ?></h4>
              <p class="text-[13.5px] text-gray-600 mt-0.5"><?= e($edu['institution']) ?></p>
              <span class="inline-block mt-2 text-[11px] font-mono text-gray-500 bg-white px-2.5 py-0.5 rounded-md border border-slate-200"><?= __('স্নাতক বছর:', 'Class of') ?> <?= e($edu['graduation_year']) ?></span>
            </div>
          </div>
          <?php endforeach; ?>
        </div>
        <?php endif; ?>
      </div>

      <!-- Publications & Achievements Card -->
      <?php if (!empty($alumni['publications']) || !empty($alumni['awards_recognition']) || !empty($alumni['association_roles'])): ?>
      <div class="p-8 rounded-3xl bg-white border border-slate-200/80 shadow-sm space-y-6">
        <h3 class="font-serif text-[20px] font-bold text-gray-800 flex items-center gap-2">
          <span>🏆</span> <?= __('গবেষণা, অর্জন ও অ্যাসোসিয়েশন ভূমিকা', 'Publications & Honors') ?>
        </h3>

        <?php if (!empty($alumni['publications'])): ?>
        <div class="p-5 rounded-2xl bg-slate-50 border border-slate-100 space-y-1">
          <span class="text-[11px] font-mono text-gray-400 font-bold uppercase block mb-1">📚 RESEARCH PUBLICATIONS (প্রকাশিত গবেষণা)</span>
          <p class="text-[14px] text-gray-700 whitespace-pre-line leading-relaxed"><?= e($alumni['publications']) ?></p>
        </div>
        <?php endif; ?>

        <?php if (!empty($alumni['awards_recognition'])): ?>
        <div class="p-5 rounded-2xl bg-amber-50/60 border border-amber-100 space-y-1">
          <span class="text-[11px] font-mono text-amber-800 font-bold uppercase block mb-1">🏅 AWARDS & RECOGNITION (অ্যাওয়ার্ড ও স্বীকৃতি)</span>
          <p class="text-[14px] text-gray-800 whitespace-pre-line leading-relaxed"><?= e($alumni['awards_recognition']) ?></p>
        </div>
        <?php endif; ?>

        <?php if (!empty($alumni['association_roles'])): ?>
        <div class="p-5 rounded-2xl bg-purple-50/60 border border-purple-100 space-y-1">
          <span class="text-[11px] font-mono text-purple-800 font-bold uppercase block mb-1">🏛️ IPH ASSOCIATION ROLES (অ্যাসোসিয়েশনে দায়িত্ব)</span>
          <p class="text-[14px] text-purple-950 font-semibold"><?= e($alumni['association_roles']) ?></p>
        </div>
        <?php endif; ?>
      </div>
      <?php endif; ?>

      <!-- Employment -->
      <div class="p-8 rounded-3xl bg-white border border-slate-200/80 shadow-sm">
        <h3 class="font-serif text-[20px] font-bold text-gray-800 mb-6 flex items-center gap-2">
          <span>💼</span> <?= __('পেশা ও কর্মসংস্থান', 'Career & Employment') ?>
        </h3>
        <?php if (empty($employment)): ?>
        <p class="text-[14px] text-gray-400 py-2"><?= __('কোনো কর্মসংস্থানের ইতিহাস রেকর্ড নেই।', 'No employment history recorded.') ?></p>
        <?php else: ?>
        <div class="space-y-5">
          <?php foreach ($employment as $emp): ?>
          <div class="p-5 rounded-2xl bg-slate-50/80 border border-slate-100 flex items-start gap-4">
            <div class="w-10 h-10 rounded-xl bg-[#2F8863]/10 text-[#2F8863] flex items-center justify-center text-[18px] shrink-0 font-bold">💼</div>
            <div class="flex-1">
              <div class="flex items-center justify-between gap-2 flex-wrap">
                <h4 class="font-bold text-gray-900 text-[15.5px]"><?= e($emp['job_title']) ?></h4>
                <?php if ($emp['is_current']): ?>
                <span class="px-2.5 py-0.5 rounded-full bg-emerald-100 text-emerald-800 text-[10px] font-mono font-bold uppercase"><?= __('বর্তমান কর্মস্থল', 'Current Work') ?></span>
                <?php endif; ?>
              </div>
              <p class="text-[14px] text-gray-600 font-medium mt-0.5"><?= e($emp['organization']) ?> <?= !empty($emp['department']) ? '· ' . e($emp['department']) : '' ?></p>
              <div class="text-[11.5px] font-mono text-gray-400 mt-1">
                <?= e($emp['start_year']) ?> — <?= $emp['is_current'] ? __('বর্তমান', 'Present') : e($emp['end_year']) ?>
              </div>
              <?php if (!empty($emp['description'])): ?>
              <p class="text-[13px] text-gray-500 mt-2 leading-relaxed bg-white p-3 rounded-xl border border-slate-100"><?= e($emp['description']) ?></p>
              <?php endif; ?>
            </div>
          </div>
          <?php endforeach; ?>
        </div>
        <?php endif; ?>
      </div>

    </div>

  </div>

</div>
