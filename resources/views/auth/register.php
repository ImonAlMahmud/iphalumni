<?php $appName = function_exists('env') ? env('APP_NAME', 'IPH Alumni Association') : 'IPH Alumni Association'; ?>

<div class="min-h-screen py-10 flex items-center justify-center px-4" style="background:#F2F4F7;">
  <div class="w-full max-w-4xl" x-data="registerFlow()">

    <!-- Header / Steps Navigation Indicator -->
    <div class="mb-10">
      <div class="flex items-center justify-between relative">
        <!-- Progress bar line -->
        <div class="absolute left-0 right-0 top-1/2 -translate-y-1/2 h-1 bg-gray-200 z-0 rounded-full"></div>
        <div class="absolute left-0 top-1/2 -translate-y-1/2 h-1 bg-blue-600 z-0 rounded-full transition-all duration-300"
             :style="'width: ' + ((step - 1) / 6 * 100) + '%'"></div>

        <!-- Step items -->
        <template x-for="(sName, idx) in steps" :key="idx">
          <div class="flex flex-col items-center z-10 relative">
            <button type="button" 
                    @click="goToStep(idx + 1)"
                    :disabled="idx + 1 > maxStepReached"
                    class="w-10 h-10 rounded-full flex items-center justify-center text-[13px] font-semibold border-2 transition-all cursor-pointer"
                    :class="step === idx + 1 
                      ? 'bg-blue-600 border-blue-600 text-white shadow-lg shadow-blue-500/20' 
                      : (step > idx + 1 
                        ? 'bg-blue-100 border-blue-600 text-blue-600' 
                        : 'bg-white border-gray-200 text-gray-400')">
              <span x-show="step <= idx + 1" x-text="idx + 1"></span>
              <span x-show="step > idx + 1">✓</span>
            </button>
            <span class="text-[11px] font-medium mt-2 transition-colors duration-200"
                  :class="step === idx + 1 ? 'text-blue-600 font-semibold' : 'text-gray-400'"
                  x-text="sName"></span>
          </div>
        </template>
      </div>
    </div>

    <!-- Main Registration Card -->
    <div class="bg-white p-8 rounded-3xl shadow-xl shadow-gray-200/50 border border-gray-100 min-h-[480px] flex flex-col justify-between">
      
      <form method="POST" action="<?= url('/register') ?>" id="reg-form" enctype="multipart/form-data" @submit="submitForm">
        <?= csrf_field() ?>

        <!-- Hidden Form Fields for real POST submission always in DOM -->
        <input type="hidden" name="name" :value="formData.name">
        <input type="hidden" name="email" :value="formData.email">
        <input type="hidden" name="password" :value="formData.password">
        <input type="hidden" name="password_confirm" :value="formData.password_confirm">
        <input type="hidden" name="gender" :value="formData.gender">
        <input type="hidden" name="dob" :value="formData.dob">
        <input type="hidden" name="blood_group" :value="formData.blood_group">
        <input type="hidden" name="phone" :value="formData.phone">
        <input type="hidden" name="nid_number" :value="formData.nid_number">
        <input type="hidden" name="current_location" :value="formData.current_location">
        <input type="hidden" name="website" :value="formData.website">
        <input type="hidden" name="linkedin_url" :value="formData.linkedin_url">
        <input type="hidden" name="facebook_url" :value="formData.facebook_url">
        <input type="hidden" name="spouse_name" :value="formData.spouse_name">
        <input type="hidden" name="children_info" :value="formData.children_info">
        <input type="hidden" name="batch_year" :value="formData.batch_year">
        <input type="hidden" name="student_id" :value="formData.student_id">

        <!-- STEP 1: TERMS -->
        <div x-show="step === 1" x-transition class="space-y-4">
          <div class="text-center mb-6">
            <span class="text-blue-600 font-mono text-[20px]">📜</span>
            <h2 class="font-serif text-[22px] font-semibold text-gray-800 mt-2"><?= __('ব্যবহারের শর্তাবলী', 'Terms of Service') ?></h2>
            <p class="text-[13px] text-gray-500 mt-1"><?= __('আমাদের সদস্যপদ শর্তাবলী পর্যালোচনা এবং গ্রহণ করুন।', 'Please review and accept our membership conditions.') ?></p>
          </div>
          <div class="p-5 bg-gray-50 border border-gray-100 rounded-2xl text-[13px] text-gray-600 max-h-60 overflow-y-auto space-y-3 leading-relaxed">
            <p><strong><?= __('১. যোগ্যতা:', '1. Eligibility:') ?></strong> <?= __('এই অ্যাসোসিয়েশনটি শুধুমাত্র ইনস্টিটিউট অব পাবলিক হেলথ (IPH) এর স্নাতক শিক্ষার্থীদের জন্য।', 'This association is exclusively for graduates of the Institute of Public Health (IPH).') ?></p>
            <p><strong><?= __('২. যাচাইকরণ:', '2. Verification:') ?></strong> <?= __('সকল আবেদন কমিটি দ্বারা ম্যানুয়ালি যাচাই করা হবে। ভুল তথ্য প্রদান করলে স্থায়ীভাবে প্রত্যাখ্যান করা হবে।', 'All applications undergo manual verification by the committee. Providing false information will result in permanent rejection.') ?></p>
            <p><strong><?= __('৩. প্রোফাইল দৃশ্যমানতা:', '3. Profile Visibility:') ?></strong> <?= __('ডিফল্টরূপে, আপনার ভেরিফাইড প্রোফাইল ডিরেক্টরিতে অন্যান্য ভেরিফাইড সদস্যদের কাছে দৃশ্যমান হবে। আপনি পোর্টালে আপনার গোপনীয়তা সেটিংস পরিবর্তন করতে পারেন।', 'By default, your verified profile will be visible to other verified members in the directory. You can adjust your privacy settings in the portal.') ?></p>
            <p><strong><?= __('৪. আচরণবিধি:', '4. Code of Conduct:') ?></strong> <?= __('সদস্যদের কমিউনিটির মধ্যে পেশাদার সততা এবং শ্রদ্ধা বজায় রাখতে হবে।', 'Members are expected to maintain professional integrity and respect within the community.') ?></p>
          </div>
          <div class="flex items-center gap-3 pt-2">
            <input type="checkbox" id="accept-terms" x-model="formData.terms" class="w-4 h-4 rounded text-blue-600 focus:ring-blue-500 accent-blue-600">
            <label for="accept-terms" class="text-[13px] font-medium text-gray-700"><?= __('আমি সকল শর্তাবলী মেনে নিচ্ছি এবং নিশ্চিত করছি যে আমি IPH এর একজন স্নাতক।', 'I accept all terms and confirm I am a graduate of IPH.') ?></label>
          </div>
        </div>

        <!-- STEP 2: VERIFICATION -->
        <div x-show="step === 2" x-transition class="space-y-5">
          <div class="text-center">
            <div class="w-12 h-12 bg-blue-50 text-blue-600 rounded-2xl flex items-center justify-center mx-auto text-[22px]">✉</div>
            <h2 class="font-serif text-[22px] font-semibold text-gray-800 mt-3"><?= __('আপনার ইমেইল যাচাই করুন', 'Verify Your Email') ?></h2>
            <p class="text-[13px] text-gray-500 mt-1"><?= __('আপনার ইমেইল ঠিকানায় একটি ৬-ডিজিটের ভেরিফিকেশন কোড পাঠানো হবে।', 'A 6-digit verification code will be sent to your email address.') ?></p>
          </div>

          <!-- Email input & Send Code -->
          <div class="max-w-md mx-auto space-y-4" x-show="!codeSent">
            <div>
              <label class="form-label"><?= __('ইমেইল ঠিকানা', 'Email Address') ?> <span class="text-red-500">*</span></label>
              <input type="email"
                     x-model="formData.email"
                     class="form-input" 
                     placeholder="you@example.com">
            </div>
            <button type="button" @click="sendCode()" class="btn btn-gold w-full flex items-center justify-center gap-2" :disabled="!formData.email || isSendingCode">
              <span x-show="!isSendingCode">→ <?= __('যাচাইকরণ কোড পাঠান', 'Send Verification Code') ?></span>
              <span x-show="isSendingCode" class="flex items-center gap-2">
                <svg class="animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                  <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                  <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <?= __('কোড পাঠানো হচ্ছে...', 'Sending Code via SMTP...') ?>
              </span>
            </button>
          </div>

          <!-- Verification Code Entry -->
          <div class="max-w-md mx-auto space-y-4" x-show="codeSent && !codeVerified">
            <div class="text-center text-[13px] text-gray-500">
              <?= __('আমরা এখানে একটি ভেরিফিকেশন কোড পাঠিয়েছি:', 'We sent a verification code to') ?> <span class="font-semibold text-gray-700" x-text="formData.email"></span>
            </div>
            <div>
              <label class="form-label" for="verification_otp_input"><?= __('৬-ডিজিটের কোড লিখুন', 'Enter 6-Digit Code') ?></label>
              <input type="text"
                     id="verification_otp_input"
                     name="verification_otp_input"
                     maxlength="6"
                     inputmode="numeric"
                     pattern="[0-9]*"
                     autocomplete="one-time-code"
                     autocorrect="off"
                     autocapitalize="off"
                     spellcheck="false"
                     x-model="verificationCode"
                     class="form-input text-center text-[22px] tracking-[0.3em] font-bold"
                     placeholder="123456">
            </div>
            <button type="button" @click="verifyCode()" class="btn btn-gold w-full">
              <?= __('কোড নিশ্চিত করুন', 'Confirm Code') ?>
            </button>
            <div class="text-center">
              <button type="button" @click="codeSent = false; verificationCode = '';" class="text-[12px] text-blue-600 hover:underline"><?= __('ইমেইল/ফোন পরিবর্তন করুন', 'Change email/phone') ?></button>
            </div>
          </div>

          <!-- Verification Success -->
          <div class="max-w-md mx-auto text-center space-y-3 py-6" x-show="codeVerified">
            <div class="w-12 h-12 bg-emerald-100 text-emerald-600 rounded-full flex items-center justify-center mx-auto text-[20px]">✓</div>
            <h3 class="font-serif text-[18px] font-semibold text-gray-800"><?= __('যাচাইকরণ সফল হয়েছে!', 'Verification Success!') ?></h3>
            <p class="text-[13px] text-gray-500"><?= __('আপনার ইমেইল ভেরিফাইড হয়েছে। এবার আপনার অ্যাকাউন্টের জন্য একটি পাসওয়ার্ড তৈরি করুন।', "Your email has been verified. Now let's set a secure password for your portal account.") ?></p>
            
            <div class="text-left space-y-3 pt-3">
              <div>
                <label class="form-label"><?= __('পাসওয়ার্ড তৈরি করুন', 'Create Password') ?></label>
                <input type="password" x-model="formData.password" class="form-input" placeholder="<?= __('ন্যূনতম ৮ টি অক্ষর', 'Min. 8 characters') ?>">
              </div>
              <div>
                <label class="form-label"><?= __('পাসওয়ার্ড নিশ্চিত করুন', 'Confirm Password') ?></label>
                <input type="password" x-model="formData.password_confirm" class="form-input" placeholder="••••••••">
              </div>
            </div>
          </div>
        </div>

        <!-- STEP 3: PERSONAL INFO -->
        <div x-show="step === 3" x-transition class="space-y-4">
          <div class="text-center mb-6">
            <h2 class="font-serif text-[22px] font-semibold text-gray-800"><?= __('ব্যক্তিগত তথ্য', 'Personal Info') ?></h2>
            <p class="text-[13px] text-gray-500 mt-1"><?= __('আপনার নিজের সম্পর্কে কিছু তথ্য লিখুন।', 'Tell us a bit about yourself.') ?></p>
          </div>
          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
              <label class="form-label"><?= __('পূর্ণ নাম', 'Full Name') ?> <span class="text-red-500">*</span></label>
              <input type="text" x-model="formData.name" class="form-input" placeholder="Dr. Firstname Lastname">
            </div>
            <div>
              <label class="form-label"><?= __('লিঙ্গ', 'Gender') ?></label>
              <select x-model="formData.gender" class="form-input">
                <option value=""><?= __('লিঙ্গ নির্বাচন করুন', 'Select Gender') ?></option>
                <option value="male"><?= __('পুরুষ', 'Male') ?></option>
                <option value="female"><?= __('নারী', 'Female') ?></option>
                <option value="other"><?= __('অন্যান্য', 'Other') ?></option>
              </select>
            </div>
            <div>
              <label class="form-label"><?= __('জন্ম তারিখ', 'Date of Birth') ?></label>
              <input type="date" x-model="formData.dob" class="form-input">
            </div>
            <div>
              <label class="form-label"><?= __('রক্তের গ্রুপ', 'Blood Group') ?></label>
              <select x-model="formData.blood_group" class="form-input">
                <option value=""><?= __('রক্তের গ্রুপ নির্বাচন করুন', 'Select Blood Group') ?></option>
                <option value="A+">A+</option>
                <option value="A-">A-</option>
                <option value="B+">B+</option>
                <option value="B-">B-</option>
                <option value="O+">O+</option>
                <option value="O-">O-</option>
                <option value="AB+">AB+</option>
                <option value="AB-">AB-</option>
              </select>
            </div>
          </div>
        </div>

        <!-- STEP 4: CONTACT INFO -->
        <div x-show="step === 4" x-transition class="space-y-4">
          <div class="text-center mb-6">
            <h2 class="font-serif text-[22px] font-semibold text-gray-800"><?= __('যোগাযোগের তথ্য', 'Contact Info') ?></h2>
            <p class="text-[13px] text-gray-500 mt-1"><?= __('অন্যান্য সদস্যরা কীভাবে আপনার সাথে যোগাযোগ করবে?', 'Where can the network reach you?') ?></p>
          </div>
          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
              <label class="form-label"><?= __('ফোন নম্বর', 'Phone Number') ?></label>
              <input type="tel" x-model="formData.phone" class="form-input" placeholder="+880 1700 000000">
            </div>
            <div>
              <label class="form-label"><?= __('NID নম্বর (ঐচ্ছিক)', 'NID Number (Optional)') ?></label>
              <input type="text" x-model="formData.nid_number" class="form-input" placeholder="e.g. 1990123456789">
            </div>
            <div>
              <label class="form-label"><?= __('বর্তমান অবস্থান (শহর/দেশ)', 'Current Location (City/Country)') ?></label>
              <input type="text" x-model="formData.current_location" class="form-input" placeholder="Dhaka, Bangladesh">
            </div>
            <div>
              <label class="form-label"><?= __('ওয়েবসাইট', 'Website') ?></label>
              <input type="url" x-model="formData.website" class="form-input" placeholder="https://example.com">
            </div>
            <div>
              <label class="form-label">LinkedIn URL</label>
              <input type="url" x-model="formData.linkedin_url" class="form-input" placeholder="https://linkedin.com/in/username">
            </div>
            <div class="md:col-span-2">
              <label class="form-label">Facebook Profile URL</label>
              <input type="url" x-model="formData.facebook_url" class="form-input" placeholder="https://facebook.com/username">
            </div>
          </div>
        </div>

        <!-- STEP 5: FAMILY INFO -->
        <div x-show="step === 5" x-transition class="space-y-4">
          <div class="text-center mb-6">
            <h2 class="font-serif text-[22px] font-semibold text-gray-800"><?= __('পারিবারিক তথ্য', 'Family Info') ?></h2>
            <p class="text-[13px] text-gray-500 mt-1"><?= __('ঐচ্ছিক পারিবারিক বিবরণ (মিলনমেলার সুবিধার্থে)।', 'Optional family details (for events and reunion purposes).') ?></p>
          </div>
          <div class="space-y-4">
            <div>
              <label class="form-label"><?= __('স্বামী/স্ত্রীর নাম (ঐচ্ছিক)', "Spouse Name (Optional)") ?></label>
              <input type="text" x-model="formData.spouse_name" class="form-input" placeholder="<?= __('স্বামী/স্ত্রীর পূর্ণ নাম', "Spouse's Full Name") ?>">
            </div>
            <div>
              <label class="form-label"><?= __('সন্তানদের তথ্য (ঐচ্ছিক)', 'Children Details (Optional)') ?></label>
              <textarea x-model="formData.children_info" rows="3" class="form-input" placeholder="<?= __('সন্তানদের নাম এবং বয়স...', 'Names/details of children...') ?>"></textarea>
            </div>
          </div>
        </div>

        <!-- STEP 6: ACADEMICS -->
        <div x-show="step === 6" x-transition class="space-y-4">
          <div class="text-center mb-6">
            <h2 class="font-serif text-[22px] font-semibold text-gray-800"><?= __('শিক্ষাগত তথ্য', 'Academic Info') ?></h2>
            <p class="text-[13px] text-gray-500 mt-1"><?= __('আইপিএইচ-এ আপনার শিক্ষাগত প্রমাণাদি যাচাই করুন।', 'Verify your academic status at IPH.') ?></p>
          </div>
          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
              <label class="form-label"><?= __('ব্যাচ', 'Batch') ?> <span class="text-red-500">*</span></label>
              <select x-model="formData.batch_year" class="form-input">
                <option value=""><?= __('ব্যাচ নির্বাচন করুন', 'Select Batch') ?></option>
                <?php 
                  $lBatches = [];
                  $fBatches = [];
                  $otherBatches = [];
                  $allBatches = !empty($batches) ? $batches : ['L-1','L-2','L-3','L-4','L-5','L-6','L-7','L-8','L-9','F-1','F-2','F-3','F-4','F-5'];
                  foreach ($allBatches as $b) {
                      if (str_starts_with($b, 'L-')) $lBatches[] = $b;
                      elseif (str_starts_with($b, 'F-')) $fBatches[] = $b;
                      else $otherBatches[] = $b;
                  }
                ?>
                <?php if (!empty($lBatches)): ?>
                <optgroup label="L-Batches (Laboratory)">
                  <?php foreach ($lBatches as $b): ?>
                  <option value="<?= e($b) ?>"><?= e($b) ?></option>
                  <?php endforeach; ?>
                </optgroup>
                <?php endif; ?>

                <?php if (!empty($fBatches)): ?>
                <optgroup label="F-Batches (Food Safety)">
                  <?php foreach ($fBatches as $b): ?>
                  <option value="<?= e($b) ?>"><?= e($b) ?></option>
                  <?php endforeach; ?>
                </optgroup>
                <?php endif; ?>

                <?php if (!empty($otherBatches)): ?>
                <optgroup label="Other Batches">
                  <?php foreach ($otherBatches as $b): ?>
                  <option value="<?= e($b) ?>"><?= e($b) ?></option>
                  <?php endforeach; ?>
                </optgroup>
                <?php endif; ?>
              </select>
            </div>
            <div>
              <label class="form-label"><?= __('ঢাকা বিশ্ববিদ্যালয় রেজিস্ট্রেশন আইডি', 'Dhaka University Registration ID') ?> <span class="text-red-500">*</span></label>
              <input type="text" x-model="formData.student_id" required class="form-input" placeholder="e.g. 2018-123456">
            </div>
            <div class="md:col-span-2">
              <label class="form-label"><?= __('ছাত্রত্ব / গ্রাজুয়েশন প্রমাণপত্র', 'Proof of Studentship / Graduation') ?> <span class="text-red-500">*</span></label>
              <p class="text-[11.5px] text-gray-400 mb-2"><?= __('আপনার শিক্ষাগত শংসাপত্র বা ছাত্রত্বের প্রমাণপত্র আপলোড করুন (PDF, JPG, PNG - সর্বোচ্চ ৫ মেগাবাইট)।', 'Upload your Educational Certificate or proof of Studentship (PDF, JPG, PNG - Max 5MB).') ?></p>
              <input type="file" name="proof_document" required accept=".pdf,image/*" class="form-input"
                     @change="formData.has_proof = !!$el.files.length">
            </div>
          </div>
        </div>

        <!-- STEP 7: REVIEW -->
        <div x-show="step === 7" x-transition class="space-y-6">
          <div class="text-center">
            <h2 class="font-serif text-[22px] font-semibold text-gray-800"><?= __('আবেদন পর্যালোচনা', 'Review Application') ?></h2>
            <p class="text-[13px] text-gray-500 mt-1"><?= __('জমা দেওয়ার আগে সবকিছু ভালো করে চেক করুন।', 'Please double check everything before submitting.') ?></p>
          </div>

          <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-[13px] border border-gray-100 p-6 rounded-2xl bg-gray-50">
            <div>
              <div class="text-gray-400"><?= __('পূর্ণ নাম', 'Full Name') ?></div>
              <div class="font-semibold text-gray-800" x-text="formData.name || '—'"></div>
            </div>
            <div>
              <div class="text-gray-400"><?= __('ইমেইল ঠিকানা', 'Email Address') ?></div>
              <div class="font-semibold text-gray-800" x-text="formData.email || '—'"></div>
            </div>
            <div>
              <div class="text-gray-400"><?= __('ফোন নম্বর', 'Phone Number') ?></div>
              <div class="font-semibold text-gray-800" x-text="formData.phone || '—'"></div>
            </div>
            <div>
              <div class="text-gray-400"><?= __('ব্যাচ', 'Batch') ?></div>
              <div class="font-semibold text-gray-800" x-text="formData.batch_year || '—'"></div>
            </div>
            <div>
              <div class="text-gray-400"><?= __('অবস্থান', 'Location') ?></div>
              <div class="font-semibold text-gray-800" x-text="formData.current_location || '—'"></div>
            </div>
            <div>
              <div class="text-gray-400"><?= __('ঢাকা বিশ্ববিদ্যালয় রেজিস্ট্রেশন আইডি', 'DU Registration ID') ?></div>
              <div class="font-semibold text-gray-800" x-text="formData.student_id || '—'"></div>
            </div>
            <div>
              <div class="text-gray-400"><?= __('লিঙ্গ / রক্তের গ্রুপ', 'Gender / Blood Group') ?></div>
              <div class="font-semibold text-gray-800" x-text="(formData.gender || '—') + ' / ' + (formData.blood_group || '—')"></div>
            </div>
          </div>
        </div>
      </form>

      <!-- CARD ACTIONS / BUTTONS -->
      <div class="flex items-center justify-between border-t border-gray-100 pt-6 mt-8">
        <button type="button" 
                @click="prevStep()" 
                class="px-5 py-2.5 rounded-xl text-[13.5px] font-semibold text-gray-600 border border-gray-200 transition-all hover:bg-gray-50"
                x-show="step > 1">
          <?= __('পেছনে যান', 'Back') ?>
        </button>
        <div class="flex-1"></div>
        
        <!-- Next Button -->
        <button type="button" 
                @click="nextStep()" 
                class="px-6 py-2.5 rounded-xl text-[13.5px] font-semibold text-white transition-all shadow-md shadow-blue-500/20"
                style="background:linear-gradient(135deg,#2563EB,#1D4ED8);"
                x-show="step < 7"
                :disabled="!isStepValid()">
          <?= __('পরবর্তী ধাপ →', 'Next Step →') ?>
        </button>

        <!-- Submit Button -->
        <button type="submit" 
                form="reg-form"
                class="px-6 py-2.5 rounded-xl text-[13.5px] font-semibold text-white transition-all shadow-md hover:-translate-y-0.5"
                style="background:linear-gradient(135deg,#A22638,#800020);"
                x-show="step === 7">
          <?= __('আবেদন জমা দিন', 'Submit Application') ?>
        </button>
      </div>

    </div>

    <!-- Bottom link -->
    <div class="text-center mt-6">
      <a href="<?= url('/login') ?>" class="text-[13px] text-gray-500 hover:text-blue-600 transition-all">
        <?= __('ইতিমধ্যে আবেদন করেছেন?', 'Already applied?') ?> <span class="font-medium text-blue-600 hover:underline"><?= __('আপনার আবেদনের স্থিতি ট্র্যাক করুন →', 'Track your application status →') ?></span>
      </a>
    </div>

  </div>
</div>

<script>
function registerFlow() {
  return {
    step: 1,
    maxStepReached: 1,
    steps: [
      '<?= __('শর্তাবলী', 'Terms') ?>',
      '<?= __('যাচাইকরণ', 'Verify') ?>',
      '<?= __('ব্যক্তিগত তথ্য', 'Personal Info') ?>',
      '<?= __('যোগাযোগের তথ্য', 'Contact Info') ?>',
      '<?= __('পারিবারিক তথ্য', 'Family Info') ?>',
      '<?= __('শিক্ষাগত তথ্য', 'Academics') ?>',
      '<?= __('পর্যালোচনা', 'Review') ?>'
    ],
    verifyMethod: 'email',
    codeSent: false,
    codeVerified: false,
    verificationCode: '',
    generatedCode: '',
    formData: {
      terms: false,
      email: '',
      password: '',
      password_confirm: '',
      name: '',
      gender: '',
      dob: '',
      blood_group: '',
      phone: '',
      nid_number: '',
      current_location: '',
      website: '',
      linkedin_url: '',
      facebook_url: '',
      spouse_name: '',
      children_info: '',
      batch_year: '',
      student_id: '',
      has_proof: false
    },

    isStepValid() {
      if (this.step === 1) return this.formData.terms;
      if (this.step === 2) return this.codeVerified && this.formData.password.length >= 8 && this.formData.password === this.formData.password_confirm;
      if (this.step === 3) return this.formData.name.trim().length >= 2;
      if (this.step === 4) return true; // Optional fields
      if (this.step === 5) return true; // Optional fields
      if (this.step === 6) return this.formData.batch_year !== '' && this.formData.student_id.trim() !== '' && this.formData.has_proof;
      return true;
    },

    nextStep() {
      if (this.isStepValid() && this.step < 7) {
        this.step++;
        if (this.step > this.maxStepReached) {
          this.maxStepReached = this.step;
        }
      }
    },

    prevStep() {
      if (this.step > 1) {
        this.step--;
      }
    },

    goToStep(targetStep) {
      if (targetStep <= this.maxStepReached) {
        this.step = targetStep;
      }
    },

    isSendingCode: false,

    sendCode() {
      if (!this.formData.email) {
        alert('Please enter your email address first.');
        return;
      }
      this.verificationCode = '';
      this.isSendingCode = true;
      this.generatedCode = Math.floor(100000 + Math.random() * 900000).toString();

      const body = new URLSearchParams();
      body.append('email', this.formData.email);
      body.append('code', this.generatedCode);
      body.append('_token', '<?= csrf_token() ?>');

      fetch('<?= url('/send-verification-code') ?>', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/x-www-form-urlencoded',
          'X-CSRF-TOKEN': '<?= csrf_token() ?>',
          'Accept': 'application/json'
        },
        body: body.toString()
      })
      .then(async (res) => {
        const data = await res.json().catch(() => null);
        this.isSendingCode = false;
        if (data && data.success) {
          this.codeSent = true;
        } else {
          alert((data && data.message) ? data.message : 'Failed to send verification code. Please check SMTP configuration.');
        }
      })
      .catch(err => {
        this.isSendingCode = false;
        alert('Network error while sending verification email. Please try again.');
      });
    },

    verifyCode() {
      if (this.verificationCode === this.generatedCode) {
        this.codeVerified = true;
      } else {
        alert("Incorrect verification code!");
      }
    },

    submitForm(e) {
      if (!this.formData.terms || !this.formData.name || !this.formData.batch_year || !this.formData.student_id || !this.formData.has_proof) {
        e.preventDefault();
        alert('Please complete all required fields.');
      }
    }
  }
}
</script>
