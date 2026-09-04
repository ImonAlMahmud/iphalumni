<?php
/**
 * Contact Page View — IPH Alumni Association
 */
$phone   = !empty($siteSettings['site_phone']) ? $siteSettings['site_phone'] : ($siteSettings['contact_phone'] ?? '+880 1811-332204');
$email   = !empty($siteSettings['site_email']) ? $siteSettings['site_email'] : ($siteSettings['contact_email'] ?? 'info@iphalumni.org');
$address = !empty($siteSettings['site_address']) ? $siteSettings['site_address'] : 'ইনস্টিটিউট অব পাবলিক হেলথ, মহাখালী, ঢাকা-১২১২, বাংলাদেশ।';
?>
<div class="max-w-7xl mx-auto px-6 py-12 font-['Kalpurush']">

  <!-- Header Banner -->
  <div class="text-center max-w-3xl mx-auto mb-14">
    <span class="font-mono text-[11px] tracking-widest text-[#2F8863] uppercase block mb-2 font-bold">
      <?= __('যোগাযোগ করুন', 'GET IN TOUCH') ?>
    </span>
    <h1 class="font-serif text-[clamp(30px,4vw,42px)] font-bold text-[#101820] mb-3">
      <?= __('আমাদের সাথে যুক্ত থাকুন', 'Contact IPH Alumni Association') ?>
    </h1>
    <p class="text-[15.5px] text-[#6B7178] leading-relaxed">
      <?= __('আপনার যেকোনো মন্তব্য, জিজ্ঞাসা বা সমর্থনের জন্য আমাদের সাথে সরাসরি যোগাযোগ করুন। আমাদের টিম দ্রুত আপনার উত্তর প্রদান করবে।',
            'Have questions, feedback, or need support? Reach out to us anytime and our team will get back to you promptly.') ?>
    </p>
  </div>

  <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 items-start">

    <!-- Contact Info & Location Cards (Left 5 Cols) -->
    <div class="lg:col-span-5 space-y-6">
      
      <!-- Office Address Card -->
      <div class="p-7 rounded-3xl bg-white border border-slate-200/80 shadow-sm flex items-start gap-4">
        <div class="w-12 h-12 rounded-2xl bg-[#800020]/10 text-[#800020] flex items-center justify-center text-[20px] shrink-0">
          <i class="fa-solid fa-location-dot"></i>
        </div>
        <div>
          <h3 class="font-bold text-[16.5px] text-[#101820] mb-1"><?= __('অফিস কার্যালয়', 'Office Location') ?></h3>
          <p class="text-[14px] text-[#6B7178] leading-relaxed">
            <?= e($address) ?>
          </p>
        </div>
      </div>

      <!-- Phone Card -->
      <div class="p-7 rounded-3xl bg-white border border-slate-200/80 shadow-sm flex items-start gap-4">
        <div class="w-12 h-12 rounded-2xl bg-[#2F8863]/10 text-[#2F8863] flex items-center justify-center text-[20px] shrink-0">
          <i class="fa-solid fa-phone"></i>
        </div>
        <div>
          <h3 class="font-bold text-[16.5px] text-[#101820] mb-1"><?= __('ফোন নাম্বার', 'Phone Helpline') ?></h3>
          <p class="text-[14.5px] text-[#101820] font-semibold font-mono mb-1">
            <a href="tel:<?= e($phone) ?>" class="hover:text-[#800020] transition-colors"><?= e($phone) ?></a>
          </p>
          <p class="text-[12.5px] text-[#9CA3AF]"><?= __('রবিবার - বৃহস্পতিবার (সকাল ৯:০০ - বিকাল ৫:০০)', 'Sun - Thu (9:00 AM - 5:00 PM)') ?></p>
        </div>
      </div>

      <!-- Email Card -->
      <div class="p-7 rounded-3xl bg-white border border-slate-200/80 shadow-sm flex items-start gap-4">
        <div class="w-12 h-12 rounded-2xl bg-[#D97706]/10 text-[#D97706] flex items-center justify-center text-[20px] shrink-0">
          <i class="fa-solid fa-envelope"></i>
        </div>
        <div>
          <h3 class="font-bold text-[16.5px] text-[#101820] mb-1"><?= __('ইমেইল অ্যাড্রেস', 'Email Support') ?></h3>
          <p class="text-[14.5px] text-[#101820] font-semibold mb-1">
            <a href="mailto:<?= e($email) ?>" class="hover:text-[#800020] transition-colors"><?= e($email) ?></a>
          </p>
          <p class="text-[12.5px] text-[#9CA3AF]"><?= __('২৪/৭ ডিজিটাল সাপোর্ট ইমেইল', '24/7 Digital Support Email') ?></p>
        </div>
      </div>

      <!-- Map View Card -->
      <div class="p-5 rounded-3xl bg-slate-900 text-white relative overflow-hidden shadow-md">
        <div class="flex items-center justify-between mb-3">
          <span class="text-[12px] font-mono text-emerald-400 font-semibold tracking-wider uppercase">
            <i class="fa-solid fa-map-pin mr-1"></i> IPH Campus Google Map
          </span>
          <span class="text-[11px] text-slate-400">Mohakhali, Dhaka</span>
        </div>
        <div class="w-full h-44 rounded-2xl overflow-hidden bg-slate-800 relative">
          <iframe class="w-full h-full border-0 filter grayscale opacity-85 hover:grayscale-0 transition-all duration-300"
                  src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3651.0487052951664!2d90.403234!3d23.777176!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3755c777d12f3849%3A0xb3cfbf11d13f9c65!2sInstitute%20of%20Public%20Health!5e0!3m2!1sen!2sbd!4v1700000000000"
                  loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
        </div>
      </div>

    </div>

    <!-- Contact Direct Form (Right 7 Cols) -->
    <div class="lg:col-span-7 bg-white p-8 md:p-10 rounded-3xl shadow-sm border border-slate-200/80" x-data="{ activeTab: 'general' }">
      
      <!-- Tab Controls -->
      <div class="flex items-center gap-2 p-1.5 rounded-2xl bg-slate-100 mb-8">
        <button @click="activeTab = 'general'"
                :class="activeTab === 'general' ? 'bg-white text-[#800020] shadow-sm font-bold' : 'text-slate-600 hover:text-slate-900 font-medium'"
                class="flex-1 py-3 px-4 rounded-xl text-[14px] transition-all flex items-center justify-center gap-2">
          <i class="fa-solid fa-paper-plane text-[13px]"></i>
          <span><?= __('সাধারণ বার্তা (General Inquiry)', 'General Inquiry') ?></span>
        </button>
        <button @click="activeTab = 'sponsor'"
                :class="activeTab === 'sponsor' ? 'bg-white text-[#800020] shadow-sm font-bold' : 'text-slate-600 hover:text-slate-900 font-medium'"
                class="flex-1 py-3 px-4 rounded-xl text-[14px] transition-all flex items-center justify-center gap-2">
          <i class="fa-solid fa-handshake-simple text-[14px] text-[#2F8863]"></i>
          <span><?= __('স্পন্সরশিপ ও পার্টনারশিপ', 'Sponsor Partnership') ?></span>
        </button>
      </div>

      <!-- General Inquiry Form -->
      <div x-show="activeTab === 'general'">
        <div class="mb-6">
          <h2 class="font-serif text-[22px] font-bold text-[#101820] mb-1">
            <?= __('সরাসরি বার্তা পাঠান', 'Send Us a Direct Message') ?>
          </h2>
          <p class="text-[13.5px] text-[#6B7178]">
            <?= __('মেম্বারশিপ, ইভেন্ট বা সাধারণ যেকোনো তথ্যের জন্য সরাসরি আমাদের লিখুন।', 'Reach out to us for membership, event, or general inquiries.') ?>
          </p>
        </div>

        <form action="<?= url('/contact') ?>" method="POST" class="space-y-5">
          <?= csrf_field() ?>
          <input type="hidden" name="inquiry_type" value="general">

          <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
            <div>
              <label class="block text-[13.5px] font-semibold text-[#101820] mb-1.5" for="name">
                <?= __('আপনার নাম', 'Your Name') ?> <span class="text-rose-600">*</span>
              </label>
              <input type="text" id="name" name="name" required placeholder="যেমন: ড. মাহমুদুর রহমান"
                     class="w-full px-4 py-3 rounded-2xl text-[14px] text-[#101820] bg-slate-50 border border-slate-200 focus:bg-white focus:outline-none focus:border-[#800020] transition-colors">
            </div>

            <div>
              <label class="block text-[13.5px] font-semibold text-[#101820] mb-1.5" for="email">
                <?= __('ইমেইল ঠিকানা', 'Email Address') ?> <span class="text-rose-600">*</span>
              </label>
              <input type="email" id="email" name="email" required placeholder="example@domain.com"
                     class="w-full px-4 py-3 rounded-2xl text-[14px] text-[#101820] bg-slate-50 border border-slate-200 focus:bg-white focus:outline-none focus:border-[#800020] transition-colors">
            </div>
          </div>

          <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
            <div>
              <label class="block text-[13.5px] font-semibold text-[#101820] mb-1.5" for="phone">
                <?= __('ফোন নাম্বার', 'Phone Number') ?>
              </label>
              <input type="tel" id="phone" name="phone" placeholder="01700-000000"
                     class="w-full px-4 py-3 rounded-2xl text-[14px] text-[#101820] bg-slate-50 border border-slate-200 focus:bg-white focus:outline-none focus:border-[#800020] transition-colors">
            </div>

            <div>
              <label class="block text-[13.5px] font-semibold text-[#101820] mb-1.5" for="subject">
                <?= __('বিষয় (Subject)', 'Subject') ?>
              </label>
              <input type="text" id="subject" name="subject" placeholder="যেমন: মেম্বারশিপ সংক্রান্ত প্রশ্ন"
                     class="w-full px-4 py-3 rounded-2xl text-[14px] text-[#101820] bg-slate-50 border border-slate-200 focus:bg-white focus:outline-none focus:border-[#800020] transition-colors">
            </div>
          </div>

          <div>
            <label class="block text-[13.5px] font-semibold text-[#101820] mb-1.5" for="message">
              <?= __('আপনার বার্তা (Message)', 'Your Message') ?> <span class="text-rose-600">*</span>
            </label>
            <textarea id="message" name="message" rows="4" required placeholder="এখানে বিস্তারিত বার্তা লিখুন..."
                      class="w-full px-4 py-3 rounded-2xl text-[14px] text-[#101820] bg-slate-50 border border-slate-200 focus:bg-white focus:outline-none focus:border-[#800020] transition-colors resize-none"></textarea>
          </div>

          <!-- Simple Single Digit Math Captcha -->
          <div class="p-4 rounded-2xl bg-rose-50/60 border border-rose-100/80 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3">
            <div>
              <label class="block text-[13.5px] font-bold text-[#800020]" for="captcha_input">
                🛡️ Security Question: <span class="font-mono text-[16px] text-[#800020] bg-white px-3 py-1 rounded-xl border border-rose-200 ml-1"><?= $captchaQuestion ?? '3 + 4 = ?' ?></span>
              </label>
              <span class="text-[12px] text-gray-500 block mt-0.5"><?= __('স্প্যাম রোবট প্রতিরক্ষায় সহজ যোগফলটির সঠিক উত্তর লিখুন', 'Answer the simple math question to submit') ?></span>
            </div>
            <input type="number" id="captcha_input" name="captcha_input" required placeholder="?"
                   class="w-24 px-4 py-2.5 rounded-xl text-center font-bold text-[16px] text-[#101820] bg-white border border-rose-300 focus:outline-none focus:ring-2 focus:ring-[#800020]/30">
          </div>

          <button type="submit"
                  class="w-full sm:w-auto inline-flex items-center justify-center gap-2.5 px-8 py-4 rounded-2xl text-[15px] font-semibold text-white bg-[#800020] hover:bg-[#66001a] transition-all shadow-md hover:-translate-y-0.5 active:scale-95">
            <i class="fa-solid fa-paper-plane text-[14px]"></i>
            <?= __('বার্তা পাঠান', 'Send Message') ?>
          </button>
        </form>
      </div>

      <!-- Sponsor Partnership Approach Form -->
      <div x-show="activeTab === 'sponsor'">
        <div class="mb-6">
          <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-[#2F8863]/10 text-[#2F8863] font-mono text-[11px] font-bold mb-2">
            <i class="fa-solid fa-handshake"></i> SPONSORSHIP & PROPOSAL
          </div>
          <h2 class="font-serif text-[22px] font-bold text-[#101820] mb-1">
            <?= __('স্পন্সরশিপ ও পার্টনারশিপ প্রস্তাবনা', 'Sponsor & Corporate Partnership') ?>
          </h2>
          <p class="text-[13.5px] text-[#6B7178]">
            <?= __('আইপিএইচ ইভেন্ট, স্কলারশিপ বা হেলথ কনফারেন্সে কর্পোরেট স্পন্সরশিপের জন্য আপনার প্রস্তাব পাঠান।', 'Submit your corporate sponsorship or event partnership proposal to IPH Alumni Association.') ?>
          </p>
        </div>

        <form action="<?= url('/contact') ?>" method="POST" class="space-y-5">
          <?= csrf_field() ?>
          <input type="hidden" name="inquiry_type" value="sponsor">

          <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
            <div>
              <label class="block text-[13.5px] font-semibold text-[#101820] mb-1.5" for="company_name">
                <?= __('প্রতিষ্ঠানের নাম / Company Name', 'Company Name') ?> <span class="text-rose-600">*</span>
              </label>
              <input type="text" id="company_name" name="company_name" required placeholder="যেমন: স্কয়ার ফার্মাসিউটিক্যালস লিমিটেড"
                     class="w-full px-4 py-3 rounded-2xl text-[14px] text-[#101820] bg-slate-50 border border-slate-200 focus:bg-white focus:outline-none focus:border-[#2F8863] transition-colors">
            </div>

            <div>
              <label class="block text-[13.5px] font-semibold text-[#101820] mb-1.5" for="name">
                <?= __('যোগাযোগকারীর নাম', 'Contact Person Name') ?> <span class="text-rose-600">*</span>
              </label>
              <input type="text" id="name" name="name" required placeholder="যেমন: তারিকুল ইসলাম (হেড অব মার্কেটিং)"
                     class="w-full px-4 py-3 rounded-2xl text-[14px] text-[#101820] bg-slate-50 border border-slate-200 focus:bg-white focus:outline-none focus:border-[#2F8863] transition-colors">
            </div>
          </div>

          <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
            <div>
              <label class="block text-[13.5px] font-semibold text-[#101820] mb-1.5" for="email">
                <?= __('অফিশিয়াল ইমেইল', 'Official Email') ?> <span class="text-rose-600">*</span>
              </label>
              <input type="email" id="email" name="email" required placeholder="sponsor@company.com"
                     class="w-full px-4 py-3 rounded-2xl text-[14px] text-[#101820] bg-slate-50 border border-slate-200 focus:bg-white focus:outline-none focus:border-[#2F8863] transition-colors">
            </div>

            <div>
              <label class="block text-[13.5px] font-semibold text-[#101820] mb-1.5" for="phone">
                <?= __('ফোন / মোবাইল নাম্বার', 'Contact Phone') ?> <span class="text-rose-600">*</span>
              </label>
              <input type="tel" id="phone" name="phone" required placeholder="01800-000000"
                     class="w-full px-4 py-3 rounded-2xl text-[14px] text-[#101820] bg-slate-50 border border-slate-200 focus:bg-white focus:outline-none focus:border-[#2F8863] transition-colors">
            </div>
          </div>

          <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
            <div>
              <label class="block text-[13.5px] font-semibold text-[#101820] mb-1.5" for="sponsor_category">
                <?= __('স্পন্সরশিপের ধরন', 'Sponsorship Type') ?>
              </label>
              <select id="sponsor_category" name="sponsor_category"
                      class="w-full px-4 py-3 rounded-2xl text-[14px] text-[#101820] bg-slate-50 border border-slate-200 focus:bg-white focus:outline-none focus:border-[#2F8863] transition-colors">
                <option value="Annual Title Sponsor">অ্যানুয়াল টাইটেল স্পন্সর (Annual Title Sponsor)</option>
                <option value="Event Sponsor">ইভেন্ট ও সেমিনার স্পন্সর (Event Sponsor)</option>
                <option value="Scholarship Sponsor">স্কলারশিপ ও রিসার্চ ফান্ড স্পন্সর (Scholarship Fund)</option>
                <option value="Publication & Media Sponsor">ম্যাগাজিন ও পাবলিকেশন স্পন্সর (Media Sponsor)</option>
                <option value="Other Partnership">অন্যান্য পার্টনারশিপ (Other)</option>
              </select>
            </div>

            <div>
              <label class="block text-[13.5px] font-semibold text-[#101820] mb-1.5" for="subject">
                <?= __('বিষয় (Proposal Title)', 'Proposal Title') ?>
              </label>
              <input type="text" id="subject" name="subject" value="IPH Alumni Sponsorship Proposal"
                     class="w-full px-4 py-3 rounded-2xl text-[14px] text-[#101820] bg-slate-50 border border-slate-200 focus:bg-white focus:outline-none focus:border-[#2F8863] transition-colors">
            </div>
          </div>

          <div>
            <label class="block text-[13.5px] font-semibold text-[#101820] mb-1.5" for="message">
              <?= __('প্রস্তাবনার বিস্তারিত (Proposal Details)', 'Proposal Details') ?> <span class="text-rose-600">*</span>
            </label>
            <textarea id="message" name="message" rows="4" required placeholder="আপনার প্রতিষ্ঠান এবং স্পন্সরশিপ প্রস্তাবনার বিবরণ লিখুন..."
                      class="w-full px-4 py-3 rounded-2xl text-[14px] text-[#101820] bg-slate-50 border border-slate-200 focus:bg-white focus:outline-none focus:border-[#2F8863] transition-colors resize-none"></textarea>
          </div>

          <!-- Simple Single Digit Math Captcha -->
          <div class="p-4 rounded-2xl bg-emerald-50/60 border border-emerald-100/80 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3">
            <div>
              <label class="block text-[13.5px] font-bold text-[#2F8863]" for="captcha_input_sponsor">
                🛡️ Security Question: <span class="font-mono text-[16px] text-[#2F8863] bg-white px-3 py-1 rounded-xl border border-emerald-200 ml-1"><?= $captchaQuestion ?? '3 + 4 = ?' ?></span>
              </label>
              <span class="text-[12px] text-gray-500 block mt-0.5"><?= __('স্প্যাম রোবট প্রতিরক্ষায় সহজ যোগফলটির সঠিক উত্তর লিখুন', 'Answer the simple math question to submit') ?></span>
            </div>
            <input type="number" id="captcha_input_sponsor" name="captcha_input" required placeholder="?"
                   class="w-24 px-4 py-2.5 rounded-xl text-center font-bold text-[16px] text-[#101820] bg-white border border-emerald-300 focus:outline-none focus:ring-2 focus:ring-[#2F8863]/30">
          </div>

          <button type="submit"
                  class="w-full sm:w-auto inline-flex items-center justify-center gap-2.5 px-8 py-4 rounded-2xl text-[15px] font-semibold text-white bg-[#2F8863] hover:bg-[#236b4d] transition-all shadow-md hover:-translate-y-0.5 active:scale-95">
            <i class="fa-solid fa-paper-plane text-[14px]"></i>
            <?= __('স্পন্সরশিপ প্রস্তাব পাঠান', 'Submit Sponsorship Proposal') ?>
          </button>
        </form>
      </div>

    </div>

  </div>
</div>
