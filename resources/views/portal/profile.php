<?php
/**
 * Alumni Portal Profile View
 * Variables: $user, $profile, $education, $employment, $primaryEdu, $currentEmp
 */
$bdDistricts = [
  'Bagerhat','Bandarban','Barguna','Barishal','Bhola','Bogura','Brahmanbaria','Chandpur','Chattogram','Chuadanga',
  'Cox\'s Bazar','Cumilla','Dhaka','Dinajpur','Faridpur','Feni','Gaibandha','Gazipur','Gopalganj','Habiganj',
  'Jamalpur','Jashore','Jhalokati','Jhenaidah','Joypurhat','Khagrachhari','Khulna','Kishoreganj','Kurigram','Kushtia',
  'Lakshmipur','Lalmonirhat','Madaripur','Magura','Manikganj','Meherpur','Moulvibazar','Munshiganj','Mymensingh','Naogaon',
  'Narail','Narayanganj','Narsingdi','Natore','Netrokona','Nilphamari','Noakhali','Pabna','Panchagarh','Patuakhali',
  'Pirojpur','Rajbari','Rajshahi','Rangamati','Rangpur','Satkhira','Shariatpur','Sherpur','Sirajganj','Sunamganj',
  'Sylhet','Tangail','Thakurgaon'
];

$locType = $profile['location_type'] ?? 'bangladesh';
$actType = $profile['activity_type'] ?? 'work';
?>
<div class="w-full max-w-[1600px] mx-auto space-y-8">
  <div class="flex flex-col lg:flex-row gap-8 items-start">
    
    <!-- Left Column: Avatar & Quick Info -->
    <?php require view_path('portal/partials/profile_sidebar.php'); ?>

    <!-- Right Column: Personal Information Form -->
    <div class="flex-1 p-8 rounded-3xl bg-white border border-gray-100 shadow-sm">
      <h3 class="font-serif text-[20px] font-semibold text-gray-800 mb-6">Personal & Location Information</h3>
      
      <form method="POST" action="<?= url('/portal/profile') ?>" class="space-y-6">
        <?= csrf_field() ?>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
          <div>
            <label class="form-label" for="name">Full Name (পূর্ণ নাম) *</label>
            <input id="name" type="text" name="name" value="<?= e($user['name'] ?? '') ?>" required class="form-input">
          </div>
          <div>
            <label class="form-label" for="email">Primary Email (প্রধান ইমেইল) *</label>
            <input id="email" type="email" name="email" value="<?= e($user['email'] ?? '') ?>" required class="form-input" placeholder="e.g. member@iphalumni.org">
          </div>
          <div>
            <label class="form-label" for="secondary_email">
              Secondary Email (বিকল্প ইমেইল)
              <span class="text-xs text-slate-400 font-normal ml-1">(Optional)</span>
            </label>
            <input id="secondary_email" type="email" name="secondary_email" value="<?= e($profile['secondary_email'] ?? ($user['secondary_email'] ?? '')) ?>" class="form-input" placeholder="e.g. personal@gmail.com">
          </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
          <div>
            <label class="form-label" for="phone">Phone Number (মোবাইল নম্বর)</label>
            <input id="phone" type="text" name="phone" value="<?= e($profile['phone'] ?? '') ?>" placeholder="+880 1xxx..." class="form-input">
          </div>
          <div>
            <label class="form-label" for="nid_number">NID Number (জাতীয় পরিচয়পত্র নম্বর)</label>
            <input id="nid_number" type="text" name="nid_number" value="<?= e($profile['nid_number'] ?? '') ?>" placeholder="e.g. 1990123456789" class="form-input">
          </div>
          <div>
            <label class="form-label" for="dob">Date of Birth</label>
            <input id="dob" type="date" name="dob" value="<?= $profile['dob'] ? date('Y-m-d', strtotime($profile['dob'])) : '' ?>" class="form-input">
          </div>
          <div>
            <label class="form-label" for="gender">Gender</label>
            <select id="gender" name="gender" class="form-input">
              <option value="">Select Gender</option>
              <option value="male" <?= ($profile['gender'] ?? '') === 'male' ? 'selected' : '' ?>>Male</option>
              <option value="female" <?= ($profile['gender'] ?? '') === 'female' ? 'selected' : '' ?>>Female</option>
              <option value="other" <?= ($profile['gender'] ?? '') === 'other' ? 'selected' : '' ?>>Other</option>
            </select>
          </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <div>
            <label class="form-label" for="blood_group">Blood Group</label>
            <select id="blood_group" name="blood_group" class="form-input">
              <option value="">Select Blood Group</option>
              <?php foreach (['A+','A-','B+','B-','AB+','AB-','O+','O-'] as $bg): ?>
              <option value="<?= $bg ?>" <?= ($profile['blood_group'] ?? '') === $bg ? 'selected' : '' ?>><?= $bg ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div>
            <label class="form-label" for="batch_year">Batch (ব্যাচ)</label>
            <input id="batch_year" type="text" name="batch_year" value="<?= e($profile['batch_year'] ?? '') ?>" placeholder="e.g. L-4, F-1, 2018-19" class="form-input">
          </div>
        </div>

        <style>
          .tab-slider-container {
            position: relative;
            display: flex;
            background-color: #f3f4f6;
            border-radius: 1.25rem;
            padding: 5px;
            border: 1px solid rgba(0, 0, 0, 0.06);
            box-shadow: inset 0 2px 4px rgba(0, 0, 0, 0.02);
          }
          .tab-slider-indicator {
            position: absolute;
            top: 5px;
            bottom: 5px;
            left: 5px;
            width: calc(50% - 5px);
            background: linear-gradient(135deg, #800020 0%, #580F1A 100%);
            border-radius: 1rem;
            box-shadow: 0 8px 20px -4px rgba(128, 0, 32, 0.4), 0 2px 6px rgba(0, 0, 0, 0.12);
            transition: transform 0.4s cubic-bezier(0.34, 1.35, 0.64, 1);
            z-index: 1;
          }
          .tab-slider-btn {
            position: relative;
            z-index: 2;
            flex: 1;
            padding: 11px 22px;
            font-size: 13.5px;
            font-weight: 600;
            border-radius: 1rem;
            transition: color 0.3s ease, transform 0.15s ease;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            cursor: pointer;
            border: none;
            outline: none;
          }
          .tab-slider-btn:active {
            transform: scale(0.97);
          }
          .tab-slider-btn.active {
            color: #ffffff !important;
          }
          .tab-slider-btn.inactive {
            color: #4b5563 !important;
          }
          .tab-slider-btn.inactive:hover {
            color: #111827 !important;
          }
          .tab-content-fade {
            animation: tabFadeSlide 0.4s cubic-bezier(0.16, 1, 0.3, 1) forwards;
          }
          @keyframes tabFadeSlide {
            0% {
              opacity: 0;
              transform: translateY(12px) scale(0.985);
            }
            100% {
              opacity: 1;
              transform: translateY(0) scale(1);
            }
          }
        </style>

        <div class="pt-4 border-t border-gray-100">
          <label class="form-label mb-2 block font-semibold text-gray-800">Current Residence / Location Type *</label>
          <input type="hidden" name="location_type" id="location_type_input" value="<?= e($locType) ?>">
          
          <div class="tab-slider-container w-full sm:w-auto max-w-md">
            <div id="location_pill_glider" class="tab-slider-indicator" style="transform: translateX(<?= $locType === 'abroad' ? '100%' : '0%' ?>);"></div>

            <button type="button" id="btn_loc_bd" onclick="switchLocationType('bangladesh')" 
                    class="tab-slider-btn <?= $locType === 'bangladesh' ? 'active' : 'inactive' ?>">
              <span>🇧🇩</span> Bangladesh
            </button>
            <button type="button" id="btn_loc_abroad" onclick="switchLocationType('abroad')" 
                    class="tab-slider-btn <?= $locType === 'abroad' ? 'active' : 'inactive' ?>">
              <span>✈️</span> Abroad / International
            </button>
          </div>
        </div>

        <!-- BANGLADESH LOCATION FIELDS -->
        <div id="block_bd_location" class="grid grid-cols-1 md:grid-cols-2 gap-4 bg-gray-50/70 p-5 rounded-2xl border border-gray-100 <?= $locType === 'abroad' ? 'hidden' : 'tab-content-fade' ?>">
          <div>
            <label class="form-label" for="current_location">Current City / District (বর্তমান জেলা) *</label>
            <select id="current_location" name="current_location" class="form-input">
              <option value="">-- Select District / জেলা নির্বাচন করুন --</option>
              <?php foreach ($bdDistricts as $d): ?>
              <option value="<?= e($d) ?>" <?= ($profile['current_location'] ?? '') === $d ? 'selected' : '' ?>><?= e($d) ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div>
            <label class="form-label" for="thana_upazila">Thana / Upazila (থানা / উপজেলা)</label>
            <input id="thana_upazila" type="text" name="thana_upazila" value="<?= e($profile['thana_upazila'] ?? '') ?>" placeholder="e.g. Dhanmondi / Savar" class="form-input">
          </div>
        </div>

        <!-- ABROAD LOCATION FIELDS -->
        <div id="block_abroad_location" class="grid grid-cols-1 md:grid-cols-2 gap-4 bg-gray-50/70 p-5 rounded-2xl border border-gray-100 <?= $locType === 'bangladesh' ? 'hidden' : 'tab-content-fade' ?>">
          
          <!-- SEARCHABLE COUNTRY DROPDOWN -->
          <div class="relative">
            <label class="form-label" for="country">Country (দেশ) *</label>
            <input type="hidden" id="country_hidden" name="country" value="<?= e($profile['country'] ?? '') ?>">
            
            <button type="button" id="country_btn" onclick="toggleCountryDropdown()" 
                    class="w-full flex items-center justify-between px-4 py-2.5 bg-white border border-gray-300 rounded-xl text-[13.5px] font-medium text-gray-800 focus:outline-none focus:ring-2 focus:ring-[#800020]/40">
              <span id="country_btn_label"><?= e($profile['country'] ?: 'Select Country / দেশ নির্বাচন করুন') ?></span>
              <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
            </button>

            <!-- Search Dropdown Box -->
            <div id="country_dropdown_menu" class="hidden absolute left-0 right-0 top-full mt-1 bg-white border border-gray-200 rounded-2xl shadow-xl z-50 p-2">
              <input type="text" id="country_search" oninput="filterCountryOptions(this.value)" placeholder="🔍 Search country..." 
                     class="w-full px-3 py-2 text-[13px] bg-gray-50 rounded-xl border border-gray-200 focus:outline-none mb-2">
              <ul id="country_options_list" class="max-h-56 overflow-y-auto space-y-0.5 text-[13.5px]">
                <!-- Options populated dynamically -->
              </ul>
            </div>
          </div>

          <!-- SEARCHABLE PROVINCE / CITY DROPDOWN -->
          <div class="relative">
            <label class="form-label" for="province_city">Province / State / City (প্রদেশ / শহর)</label>
            <input type="hidden" id="province_city_hidden" name="province_city" value="<?= e($profile['province_city'] ?? '') ?>">
            
            <button type="button" id="province_btn" onclick="toggleProvinceDropdown()" 
                    class="w-full flex items-center justify-between px-4 py-2.5 bg-white border border-gray-300 rounded-xl text-[13.5px] font-medium text-gray-800 focus:outline-none focus:ring-2 focus:ring-[#800020]/40">
              <span id="province_btn_label"><?= e($profile['province_city'] ?: 'Select Province / City / শহর নির্বাচন করুন') ?></span>
              <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
            </button>

            <!-- Search Dropdown Box -->
            <div id="province_dropdown_menu" class="hidden absolute left-0 right-0 top-full mt-1 bg-white border border-gray-200 rounded-2xl shadow-xl z-50 p-2">
              <input type="text" id="province_search" oninput="filterProvinceOptions(this.value)" placeholder="🔍 Search or type city name..." 
                     class="w-full px-3 py-2 text-[13px] bg-gray-50 rounded-xl border border-gray-200 focus:outline-none mb-2">
              <ul id="province_options_list" class="max-h-56 overflow-y-auto space-y-0.5 text-[13.5px]">
                <!-- Options populated dynamically based on selected country -->
              </ul>
            </div>
          </div>

        </div>

        <!-- ──────────────── STUDY / WORK DROPDOWN SELECTION ──────────────── -->
        <div class="pt-4 border-t border-gray-100">
          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
              <label class="form-label" for="activity_type">Primary Activity / Status (পড়াশোনা না কর্মক্ষেত্র?) *</label>
              <select id="activity_type" name="activity_type" onchange="toggleActivityFields(this.value)" class="form-input">
                <option value="work" <?= $actType === 'work' ? 'selected' : '' ?>>💼 Work / Employment (চাকরি / ব্যবসা)</option>
                <option value="study" <?= $actType === 'study' ? 'selected' : '' ?>>🎓 Higher Study / Education (উচ্চশিক্ষা / পড়াশোনা)</option>
                <option value="both" <?= $actType === 'both' ? 'selected' : '' ?>>🎓💼 Both Study & Work (পড়াশোনা ও চাকরি উভয়ই)</option>
              </select>
            </div>
          </div>
        </div>

        <!-- DYNAMIC STUDY FIELDS -->
        <div id="block_study_fields" class="bg-blue-50/50 p-5 rounded-2xl border border-blue-100/60 space-y-4 <?= ($actType === 'work') ? 'hidden' : 'tab-content-fade' ?>">
          <h4 class="font-serif text-[15px] font-semibold text-blue-900 flex items-center gap-2">
            🎓 Education / Study Details (শিক্ষাগত তথ্য)
          </h4>
          <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
              <label class="form-label" for="subject">Subject / Department (বিষয়)</label>
              <input id="subject" type="text" name="subject" value="<?= e($primaryEdu['field_of_study'] ?? '') ?>" placeholder="e.g. Public Health / Microbiology" class="form-input">
            </div>
            <div>
              <label class="form-label" for="programme">Programme / Degree (ডিগ্রী)</label>
              <input id="programme" type="text" name="programme" value="<?= e($primaryEdu['degree'] ?? '') ?>" placeholder="e.g. M.Sc. / Ph.D." class="form-input">
            </div>
            <!-- SEARCHABLE UNIVERSITY DROPDOWN -->
            <div class="relative">
              <label class="form-label" for="university">University / Institution (বিশ্ববিদ্যালয়)</label>
              <input type="hidden" id="university_hidden" name="university" value="<?= e($primaryEdu['institution'] ?? '') ?>">
              
              <button type="button" id="university_btn" onclick="toggleUniversityDropdown()" 
                      class="w-full flex items-center justify-between px-4 py-2.5 bg-white border border-gray-300 rounded-xl text-[13.5px] font-medium text-gray-800 focus:outline-none focus:ring-2 focus:ring-[#800020]/40">
                <span id="university_btn_label"><?= e($primaryEdu['institution'] ?: 'Select University / বিশ্ববিদ্যালয় নির্বাচন করুন') ?></span>
                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
              </button>

              <!-- Search Dropdown Box -->
              <div id="university_dropdown_menu" class="hidden absolute left-0 right-0 top-full mt-1 bg-white border border-gray-200 rounded-2xl shadow-xl z-50 p-2">
                <input type="text" id="university_search" oninput="filterUniversityOptions(this.value)" placeholder="🔍 Search or type new university..." 
                       class="w-full px-3 py-2 text-[13px] bg-gray-50 rounded-xl border border-gray-200 focus:outline-none mb-2">
                <ul id="university_options_list" class="max-h-56 overflow-y-auto space-y-0.5 text-[13.5px]">
                  <!-- Options populated dynamically -->
                </ul>
              </div>
            </div>
          </div>
        </div>

        <!-- DYNAMIC WORK FIELDS -->
        <div id="block_work_fields" class="bg-emerald-50/50 p-5 rounded-2xl border border-emerald-100/60 space-y-4 <?= ($actType === 'study') ? 'hidden' : 'tab-content-fade' ?>">
          <h4 class="font-serif text-[15px] font-semibold text-emerald-900 flex items-center gap-2">
            💼 Work / Employment Details (কর্মক্ষেত্রের তথ্য)
          </h4>
          <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
              <label class="form-label" for="department">Department / Division (ডিপার্টমেন্ট)</label>
              <input id="department" type="text" name="department" value="<?= e($currentEmp['department'] ?? '') ?>" placeholder="e.g. Research & Development" class="form-input">
            </div>
            <div>
              <label class="form-label" for="designation">Designation / Role (পদবি) *</label>
              <input id="designation" type="text" name="designation" value="<?= e($currentEmp['job_title'] ?? '') ?>" placeholder="e.g. Senior Researcher / Officer" class="form-input">
            </div>
            <div>
              <label class="form-label" for="organization">Organization / Company (প্রতিষ্ঠানের নাম)</label>
              <input id="organization" type="text" name="organization" value="<?= e($currentEmp['organization'] ?? '') ?>" placeholder="e.g. Institute of Public Health / WHO" class="form-input">
            </div>
          </div>
        </div>

        <div>
          <label class="form-label" for="bio">Bio / Short Description</label>
          <textarea id="bio" name="bio" rows="3" placeholder="Tell us about yourself..." class="form-input"><?= e($profile['bio'] ?? '') ?></textarea>
        </div>

        <h4 class="font-serif text-[15px] font-semibold text-gray-800 pt-3 border-t border-gray-100 mb-4">Web, Research & Social Links</h4>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
          <div>
            <label class="form-label" for="website">Personal Website</label>
            <input id="website" type="url" name="website" value="<?= e($profile['website'] ?? '') ?>" placeholder="https://example.com" class="form-input">
          </div>
          <div>
            <label class="form-label" for="linkedin_url">LinkedIn URL</label>
            <input id="linkedin_url" type="url" name="linkedin_url" value="<?= e($profile['linkedin_url'] ?? '') ?>" placeholder="https://linkedin.com/in/..." class="form-input">
          </div>
          <div>
            <label class="form-label" for="facebook_url">Facebook URL</label>
            <input id="facebook_url" type="url" name="facebook_url" value="<?= e($profile['facebook_url'] ?? '') ?>" placeholder="https://facebook.com/..." class="form-input">
          </div>
          <div>
            <label class="form-label" for="google_scholar_url">Google Scholar Link</label>
            <input id="google_scholar_url" type="url" name="google_scholar_url" value="<?= e($profile['google_scholar_url'] ?? '') ?>" placeholder="https://scholar.google.com/citations?..." class="form-input">
          </div>
          <div>
            <label class="form-label" for="researchgate_url">ResearchGate Link</label>
            <input id="researchgate_url" type="url" name="researchgate_url" value="<?= e($profile['researchgate_url'] ?? '') ?>" placeholder="https://researchgate.net/profile/..." class="form-input">
          </div>
        </div>

        <h4 class="font-serif text-[15px] font-semibold text-gray-800 pt-3 border-t border-gray-100 mb-4">🎓 Academic Details & IPH History</h4>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <div>
            <label class="form-label" for="session_years">Session / Academic Years (শিক্ষাবর্ষ)</label>
            <input id="session_years" type="text" name="session_years" value="<?= e($profile['session_years'] ?? '') ?>" placeholder="e.g. 2014-2015" class="form-input">
          </div>
        </div>

        <h4 class="font-serif text-[15px] font-semibold text-gray-800 pt-3 border-t border-gray-100 mb-4">💡 Expertise & Mentorship Opportunities</h4>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
          <div>
            <label class="form-label" for="specialization">Specialization / Expertise (বিশেষজ্ঞতার ক্ষেত্র)</label>
            <input id="specialization" type="text" name="specialization" value="<?= e($profile['specialization'] ?? '') ?>" placeholder="e.g. Virology, Epidemiology, PCR Analysis" class="form-input">
          </div>
          <div>
            <label class="form-label" for="skills">Key Skills (দক্ষতাসমূহ - কমা দিয়ে পৃথক করুন)</label>
            <input id="skills" type="text" name="skills" value="<?= e($profile['skills'] ?? '') ?>" placeholder="e.g. SPSS, Biosafety Level-3, Data Analysis" class="form-input">
          </div>
          <div>
            <label class="form-label" for="experience_years">Experience Years (কর্মজীবনের মোট অভিজ্ঞতা)</label>
            <input id="experience_years" type="text" name="experience_years" value="<?= e($profile['experience_years'] ?? '') ?>" placeholder="e.g. 8+ Years" class="form-input">
          </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 bg-emerald-50/60 p-4 rounded-2xl border border-emerald-100">
          <div>
            <label class="form-label font-bold text-emerald-900" for="willing_to_mentor">🤝 Willing to Mentor Juniors? (মেন্টরশিপ দিতে ইচ্ছুক?)</label>
            <select id="willing_to_mentor" name="willing_to_mentor" class="form-input bg-white">
              <option value="0" <?= (int)($profile['willing_to_mentor'] ?? 0) === 0 ? 'selected' : '' ?>>No (না)</option>
              <option value="1" <?= (int)($profile['willing_to_mentor'] ?? 0) === 1 ? 'selected' : '' ?>>Yes, Willing to Mentor Juniors (হ্যাঁ, মেন্টরশিপ দিতে ইচ্ছুক)</option>
            </select>
          </div>
          <div>
            <label class="form-label font-bold text-emerald-900" for="job_referral">💼 Can Provide Job/Internship Referral? (জব রেফারেল দেওয়া সম্ভব?)</label>
            <select id="job_referral" name="job_referral" class="form-input bg-white">
              <option value="0" <?= (int)($profile['job_referral'] ?? 0) === 0 ? 'selected' : '' ?>>No (না)</option>
              <option value="1" <?= (int)($profile['job_referral'] ?? 0) === 1 ? 'selected' : '' ?>>Yes, Can Help With Referrals (হ্যাঁ, সুযোগ থাকলে রেফার করব)</option>
            </select>
          </div>
          <div class="md:col-span-2">
            <label class="form-label font-semibold text-emerald-900" for="contribution_areas">Areas of Help / Guidance (কী কী বিষয়ে জুনিয়রদের সাহায্য করতে পারবেন)</label>
            <input id="contribution_areas" type="text" name="contribution_areas" value="<?= e($profile['contribution_areas'] ?? '') ?>" placeholder="e.g. Higher Study Abroad advice, Career Counseling, Research Guidance" class="form-input bg-white">
          </div>
        </div>

        <h4 class="font-serif text-[15px] font-semibold text-gray-800 pt-3 border-t border-gray-100 mb-4">🏠 Permanent Address & Emergency Contact</h4>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <div class="md:col-span-2">
            <label class="form-label" for="permanent_location">Permanent Address (স্থায়ী ঠিকানা)</label>
            <input id="permanent_location" type="text" name="permanent_location" value="<?= e($profile['permanent_location'] ?? '') ?>" placeholder="e.g. Mohakhali, Dhaka or Bogura Sadar, Bogura" class="form-input">
            <p class="text-[11px] text-gray-400 mt-1">এটি আপনার ডিজিটাল মেম্বারশিপ আইডি কার্ডের পেছনের অংশে "PERMANENT ADDRESS" হিসেবে প্রদর্শিত হবে।</p>
          </div>
          <div>
            <label class="form-label" for="permanent_district">Permanent District (স্থায়ী জেলা)</label>
            <select id="permanent_district" name="permanent_district" class="form-input">
              <option value="">-- Select Permanent District --</option>
              <?php foreach ($bdDistricts as $d): ?>
              <option value="<?= e($d) ?>" <?= ($profile['permanent_district'] ?? '') === $d ? 'selected' : '' ?>><?= e($d) ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div>
            <label class="form-label" for="permanent_upazila">Permanent Upazila / Thana (স্থায়ী উপজেলা/থানা)</label>
            <input id="permanent_upazila" type="text" name="permanent_upazila" value="<?= e($profile['permanent_upazila'] ?? '') ?>" placeholder="e.g. Savar / Sadar" class="form-input">
          </div>
          <div>
            <label class="form-label" for="emergency_contact_name">Emergency Contact Person Name (জরুরি যোগাযোগের ব্যক্তি)</label>
            <input id="emergency_contact_name" type="text" name="emergency_contact_name" value="<?= e($profile['emergency_contact_name'] ?? '') ?>" placeholder="e.g. Person Name & Relation" class="form-input">
          </div>
          <div>
            <label class="form-label" for="emergency_contact_phone">Emergency Contact Phone Number</label>
            <input id="emergency_contact_phone" type="text" name="emergency_contact_phone" value="<?= e($profile['emergency_contact_phone'] ?? '') ?>" placeholder="017xxxxxxxx" class="form-input">
          </div>
        </div>

        <h4 class="font-serif text-[15px] font-semibold text-gray-800 pt-3 border-t border-gray-100 mb-4">🏆 Publications & Honors</h4>
        <div class="grid grid-cols-1 gap-4">
          <div>
            <label class="form-label" for="publications">Key Research Publications (প্রকাশিত গবেষণা পত্রসমূহ)</label>
            <textarea id="publications" name="publications" rows="2" placeholder="List your published research papers or journal titles..." class="form-input"><?= e($profile['publications'] ?? '') ?></textarea>
          </div>
          <div>
            <label class="form-label" for="awards_recognition">Awards & Achievements (অ্যাওয়ার্ড ও স্বীকৃতি)</label>
            <textarea id="awards_recognition" name="awards_recognition" rows="2" placeholder="List any notable awards, honors or achievements..." class="form-input"><?= e($profile['awards_recognition'] ?? '') ?></textarea>
          </div>
        </div>

        <button type="submit" class="btn btn-gold text-white w-full md:w-auto px-8">Save Profile Info</button>
      </form>
    </div>

  </div>
</div>

<script>
const countryData = {
  "United States": ["California (Los Angeles/SF)", "New York", "Texas (Houston/Austin)", "Florida (Miami)", "Illinois (Chicago)", "Washington (Seattle)", "Massachusetts (Boston)", "Georgia (Atlanta)", "Pennsylvania", "Ohio", "North Carolina", "Virginia", "Michigan"],
  "United Kingdom": ["London", "Manchester", "Birmingham", "Edinburgh", "Glasgow", "Leeds", "Liverpool", "Bristol", "Cambridge", "Oxford"],
  "Canada": ["Ontario (Toronto/Ottawa)", "Quebec (Montreal)", "British Columbia (Vancouver)", "Alberta (Calgary)", "Manitoba", "Saskatchewan", "Nova Scotia"],
  "Australia": ["New South Wales (Sydney)", "Victoria (Melbourne)", "Queensland (Brisbane)", "Western Australia (Perth)", "South Australia (Adelaide)", "Tasmania", "ACT (Canberra)"],
  "Germany": ["Bavaria (Munich)", "North Rhine-Westphalia", "Baden-Württemberg", "Hesse (Frankfurt)", "Berlin", "Hamburg", "Lower Saxony", "Saxony"],
  "Japan": ["Tokyo", "Osaka", "Kanagawa (Yokohama)", "Aichi (Nagoya)", "Hokkaido (Sapporo)", "Hyogo (Kobe)", "Fukuoka", "Kyoto", "Saitama"],
  "United Arab Emirates": ["Dubai", "Abu Dhabi", "Sharjah", "Ajman", "Ras Al Khaimah", "Fujairah"],
  "Saudi Arabia": ["Riyadh", "Jeddah", "Mecca", "Medina", "Dammam", "Khobar", "Tabuk"],
  "Malaysia": ["Kuala Lumpur", "Selangor", "Penang", "Johor", "Sabah", "Sarawak", "Perak"],
  "Singapore": ["Central Region", "Jurong East", "Tampines", "Woodlands", "Bedok"],
  "Sweden": ["Stockholm", "Västra Götaland (Gothenburg)", "Skåne (Malmö)", "Uppsala"],
  "Finland": ["Uusimaa (Helsinki)", "Pirkanmaa (Tampere)", "Turku", "Oulu"],
  "Norway": ["Oslo", "Viken", "Vestland (Bergen)", "Trøndelag (Trondheim)"],
  "Denmark": ["Copenhagen", "Central Denmark (Aarhus)", "Odense"],
  "Netherlands": ["North Holland (Amsterdam)", "South Holland (Rotterdam/The Hague)", "Utrecht", "Eindhoven"],
  "France": ["Île-de-France (Paris)", "Auvergne-Rhône-Alpes (Lyon)", "Marseille", "Toulouse"],
  "Italy": ["Lombardy (Milan)", "Lazio (Rome)", "Campania (Naples)", "Venice", "Turin"],
  "Spain": ["Madrid", "Catalonia (Barcelona)", "Seville", "Valencia"],
  "Switzerland": ["Zurich", "Geneva", "Lausanne", "Bern", "Basel"],
  "Ireland": ["Dublin", "Cork", "Galway", "Limerick"],
  "South Korea": ["Seoul", "Gyeonggi", "Busan", "Incheon", "Daegu", "Daejeon"],
  "China": ["Beijing", "Shanghai", "Guangdong (Guangzhou/Shenzhen)", "Zhejiang (Hangzhou)", "Jiangsu"],
  "India": ["West Bengal (Kolkata)", "Maharashtra (Mumbai)", "Delhi NCR", "Karnataka (Bengaluru)", "Tamil Nadu (Chennai)"],
  "Pakistan": ["Punjab (Lahore)", "Sindh (Karachi)", "Khyber Pakhtunkhwa (Peshawar)", "Islamabad"],
  "Qatar": ["Doha", "Al Rayyan", "Al Wakrah"],
  "Kuwait": ["Kuwait City", "Al Ahmadi", "Hawalli"],
  "Oman": ["Muscat", "Salalah", "Sohar"],
  "Bahrain": ["Manama", "Riffa", "Muharraq"],
  "Turkey": ["Istanbul", "Ankara", "Izmir", "Antalya"],
  "New Zealand": ["Auckland", "Wellington", "Christchurch", "Hamilton"],
  "South Africa": ["Johannesburg", "Cape Town", "Durban", "Pretoria"],
  "Brazil": ["São Paulo", "Rio de Janeiro", "Brasília"],
  "Egypt": ["Cairo", "Alexandria", "Giza"]
};

const allCountries = [
  "United States", "United Kingdom", "Canada", "Australia", "Germany", "Japan", 
  "United Arab Emirates", "Saudi Arabia", "Malaysia", "Singapore", "Sweden", 
  "Finland", "Norway", "Denmark", "Netherlands", "France", "Italy", "Spain", 
  "Switzerland", "Ireland", "South Korea", "China", "India", "Pakistan", "Qatar", 
  "Kuwait", "Oman", "Bahrain", "Turkey", "New Zealand", "South Africa", "Brazil", 
  "Egypt", "Afghanistan", "Albania", "Algeria", "Argentina", "Austria", "Azerbaijan", 
  "Belgium", "Brunei", "Bulgaria", "Chile", "Colombia", "Czech Republic", "Greece", 
  "Hong Kong", "Hungary", "Indonesia", "Iran", "Iraq", "Israel", "Jordan", "Kazakhstan", 
  "Kenya", "Lebanon", "Maldives", "Mexico", "Morocco", "Nepal", "Nigeria", "Philippines", 
  "Poland", "Portugal", "Romania", "Russia", "Sri Lanka", "Thailand", "Vietnam"
];

function switchLocationType(type) {
  document.getElementById('location_type_input').value = type;
  const glider = document.getElementById('location_pill_glider');
  const btnBd = document.getElementById('btn_loc_bd');
  const btnAbroad = document.getElementById('btn_loc_abroad');
  const blockBd = document.getElementById('block_bd_location');
  const blockAbroad = document.getElementById('block_abroad_location');

  if (type === 'bangladesh') {
    glider.style.transform = 'translateX(0%)';
    btnBd.className = "tab-slider-btn active";
    btnAbroad.className = "tab-slider-btn inactive";

    blockAbroad.classList.add('hidden');
    blockBd.classList.remove('hidden');
    blockBd.classList.remove('tab-content-fade');
    void blockBd.offsetWidth;
    blockBd.classList.add('tab-content-fade');
    if (typeof filterUniversityOptions === 'function') filterUniversityOptions('');
  } else {
    glider.style.transform = 'translateX(100%)';
    btnAbroad.className = "tab-slider-btn active";
    btnBd.className = "tab-slider-btn inactive";

    blockBd.classList.add('hidden');
    blockAbroad.classList.remove('hidden');
    blockAbroad.classList.remove('tab-content-fade');
    void blockAbroad.offsetWidth;
    blockAbroad.classList.add('tab-content-fade');
    if (typeof filterUniversityOptions === 'function') filterUniversityOptions('');
  }
}

function toggleActivityFields(actType) {
  const studyBlock = document.getElementById('block_study_fields');
  const workBlock = document.getElementById('block_work_fields');

  if (actType === 'study') {
    studyBlock.classList.remove('hidden');
    workBlock.classList.add('hidden');
  } else if (actType === 'work') {
    workBlock.classList.remove('hidden');
    studyBlock.classList.add('hidden');
  } else { // 'both'
    studyBlock.classList.remove('hidden');
    workBlock.classList.remove('hidden');
  }
}

const initialUniversities = [
  { country: "Bangladesh", name: "University of Dhaka (DU)" },
  { country: "Bangladesh", name: "Bangladesh University of Engineering and Technology (BUET)" },
  { country: "Bangladesh", name: "Institute of Public Health (IPH)" },
  { country: "Bangladesh", name: "Dhaka Medical College (DMC)" },
  { country: "Bangladesh", name: "Chittagong Medical College (CMC)" },
  { country: "Bangladesh", name: "Jahangirnagar University (JU)" },
  { country: "Bangladesh", name: "Rajshahi University (RU)" },
  { country: "Bangladesh", name: "Shahjalal University of Science and Technology (SUST)" },
  { country: "Bangladesh", name: "Bangladesh Agricultural University (BAU)" },
  { country: "Bangladesh", name: "Bangabandhu Sheikh Mujib Medical University (BSMMU)" },
  { country: "Bangladesh", name: "National Institute of Preventive and Social Medicine (NIPSOM)" },
  { country: "Bangladesh", name: "North South University (NSU)" },
  { country: "Bangladesh", name: "BRAC University" },
  { country: "Bangladesh", name: "Independent University, Bangladesh (IUB)" },
  { country: "Bangladesh", name: "East West University (EWU)" },
  { country: "Bangladesh", name: "Ahsanullah University of Science and Technology (AUST)" },
  { country: "United States", name: "Harvard University" },
  { country: "United States", name: "Massachusetts Institute of Technology (MIT)" },
  { country: "United States", name: "Stanford University" },
  { country: "United States", name: "Columbia University" },
  { country: "United States", name: "University of California, Berkeley" },
  { country: "United States", name: "Johns Hopkins University" },
  { country: "United States", name: "Yale University" },
  { country: "United States", name: "New York University (NYU)" },
  { country: "United Kingdom", name: "University of Oxford" },
  { country: "United Kingdom", name: "University of Cambridge" },
  { country: "United Kingdom", name: "Imperial College London" },
  { country: "United Kingdom", name: "University College London (UCL)" },
  { country: "United Kingdom", name: "University of Manchester" },
  { country: "Canada", name: "University of Toronto" },
  { country: "Canada", name: "University of British Columbia (UBC)" },
  { country: "Canada", name: "McGill University" },
  { country: "Canada", name: "University of Waterloo" },
  { country: "Australia", name: "The University of Melbourne" },
  { country: "Australia", name: "The University of Sydney" },
  { country: "Australia", name: "Australian National University (ANU)" },
  { country: "Australia", name: "The University of Queensland" },
  { country: "Japan", name: "The University of Tokyo" },
  { country: "Japan", name: "Kyoto University" },
  { country: "Japan", name: "Osaka University" },
  { country: "Germany", name: "Technical University of Munich (TUM)" },
  { country: "Germany", name: "Ludwig Maximilian University of Munich (LMU)" },
  { country: "Germany", name: "Heidelberg University" }
];

const serverUniversities = <?= json_encode($allUniversities ?? []) ?>;
const dbUniversities = (serverUniversities && serverUniversities.length > 0) ? serverUniversities : initialUniversities;

// ── Searchable Country Dropdown Logic ─────────────────────────────────────────
function toggleCountryDropdown() {
  const menu = document.getElementById('country_dropdown_menu');
  if (!menu) return;
  const isHidden = menu.classList.contains('hidden');
  closeAllDropdowns();
  if (isHidden) {
    menu.classList.remove('hidden');
    filterCountryOptions(document.getElementById('country_search').value || '');
    document.getElementById('country_search').focus();
  }
}

function filterCountryOptions(query) {
  const list = document.getElementById('country_options_list');
  if (!list) return;
  const q = query.toLowerCase().trim();
  const filtered = allCountries.filter(c => c.toLowerCase().includes(q));

  let html = '';
  if (filtered.length === 0) {
    html = `<li class="p-2 text-gray-400 text-center">No matching country found</li>`;
  } else {
    filtered.forEach(c => {
      const safeName = c.replace(/'/g, "\\'");
      html += `<li onclick="selectCountry('${safeName}')" class="px-3 py-2 hover:bg-gray-100 rounded-xl cursor-pointer font-medium text-gray-800 transition-colors">${c}</li>`;
    });
  }
  list.innerHTML = html;
}

function selectCountry(countryName) {
  document.getElementById('country_hidden').value = countryName;
  document.getElementById('country_btn_label').innerText = countryName;
  document.getElementById('country_dropdown_menu').classList.add('hidden');

  // Reset & Populate Province Dropdown
  document.getElementById('province_city_hidden').value = '';
  document.getElementById('province_btn_label').innerText = 'Select Province / City / শহর নির্বাচন করুন';
  populateProvinceOptions(countryName);

  // Refresh University options for the selected country
  filterUniversityOptions(document.getElementById('university_search') ? document.getElementById('university_search').value : '');
}

// ── Searchable Province / City Dropdown Logic ────────────────────────────────
function toggleProvinceDropdown() {
  const menu = document.getElementById('province_dropdown_menu');
  if (!menu) return;
  const isHidden = menu.classList.contains('hidden');
  closeAllDropdowns();
  if (isHidden) {
    menu.classList.remove('hidden');
    const selectedCountry = document.getElementById('country_hidden').value;
    populateProvinceOptions(selectedCountry, document.getElementById('province_search').value || '');
    document.getElementById('province_search').focus();
  }
}

function populateProvinceOptions(countryName, query = '') {
  const list = document.getElementById('province_options_list');
  if (!list) return;
  const q = query.toLowerCase().trim();
  const cities = countryData[countryName] || ["Capital Region", "Main City", "Other Region"];

  const filtered = cities.filter(c => c.toLowerCase().includes(q));

  let html = '';
  if (q !== '' && !filtered.some(c => c.toLowerCase() === q)) {
    const safeQ = query.replace(/'/g, "\\'");
    html += `<li onclick="selectProvince('${safeQ}')" class="px-3 py-2 bg-blue-50 text-blue-700 hover:bg-blue-100 rounded-xl cursor-pointer font-semibold mb-1 transition-colors">+ Add Custom: "${query}"</li>`;
  }

  if (filtered.length === 0 && q === '') {
    html += `<li class="p-2 text-gray-400 text-center">Type custom city name...</li>`;
  } else {
    filtered.forEach(c => {
      const safeCity = c.replace(/'/g, "\\'");
      html += `<li onclick="selectProvince('${safeCity}')" class="px-3 py-2 hover:bg-gray-100 rounded-xl cursor-pointer font-medium text-gray-800 transition-colors">${c}</li>`;
    });
  }
  list.innerHTML = html;
}

function filterProvinceOptions(query) {
  const selectedCountry = document.getElementById('country_hidden').value;
  populateProvinceOptions(selectedCountry, query);
}

function selectProvince(cityName) {
  document.getElementById('province_city_hidden').value = cityName;
  document.getElementById('province_btn_label').innerText = cityName;
  document.getElementById('province_dropdown_menu').classList.add('hidden');
}

// ── Searchable University Dropdown Logic ──────────────────────────────────────
function toggleUniversityDropdown() {
  const menu = document.getElementById('university_dropdown_menu');
  if (!menu) return;
  const isHidden = menu.classList.contains('hidden');
  closeAllDropdowns();
  if (isHidden) {
    menu.classList.remove('hidden');
    filterUniversityOptions(document.getElementById('university_search').value || '');
    document.getElementById('university_search').focus();
  }
}

function filterUniversityOptions(query) {
  const list = document.getElementById('university_options_list');
  if (!list) return;
  const q = query.toLowerCase().trim();
  
  const locType = document.getElementById('location_type_input').value;
  const rawCountry = (locType === 'abroad' && document.getElementById('country_hidden').value) 
    ? document.getElementById('country_hidden').value.trim() 
    : 'Bangladesh';
  const selectedCountry = rawCountry.toLowerCase();

  let countryUnivs = dbUniversities.filter(u => u.country.toLowerCase() === selectedCountry);
  let otherUnivs   = dbUniversities.filter(u => u.country.toLowerCase() !== selectedCountry);

  let filtered = [];
  if (q !== '') {
    filtered = dbUniversities.filter(u => u.name.toLowerCase().includes(q) || u.country.toLowerCase().includes(q));
  } else {
    filtered = countryUnivs;
  }

  let html = '';
  if (q !== '' && !dbUniversities.some(u => u.name.toLowerCase() === q)) {
    const safeQ = query.replace(/'/g, "\\'");
    html += `<li onclick="selectUniversity('${safeQ}')" class="px-3 py-2 bg-blue-50 text-blue-700 hover:bg-blue-100 rounded-xl cursor-pointer font-semibold mb-2 transition-colors">+ Add & Save New: "${query}"</li>`;
  }

  if (filtered.length === 0 && q === '') {
    html += `<li class="p-4 text-center text-gray-500 text-[12.5px]">
      No pre-saved universities for <strong>${rawCountry}</strong> yet.<br>
      <span class="text-blue-600 font-semibold mt-1 block">Type university name in search above to add!</span>
    </li>`;
  } else if (filtered.length === 0) {
    html += `<li class="p-3 text-gray-400 text-center text-[12.5px]">No matching university found</li>`;
  } else {
    filtered.forEach(u => {
      const safeUniv = u.name.replace(/'/g, "\\'");
      const isSelected = u.country.toLowerCase() === selectedCountry;
      const bgBadge = isSelected ? 'bg-emerald-50 text-emerald-700 border-emerald-200 font-semibold' : 'bg-gray-100 text-gray-400';
      html += `<li onclick="selectUniversity('${safeUniv}')" class="px-3 py-2 hover:bg-gray-100 rounded-xl cursor-pointer font-medium text-gray-800 transition-colors flex items-center justify-between">
        <span>${u.name}</span>
        <span class="text-[10px] font-mono px-2 py-0.5 rounded-full border ${bgBadge}">${u.country}</span>
      </li>`;
    });
  }
  list.innerHTML = html;
}

function selectUniversity(univName) {
  document.getElementById('university_hidden').value = univName;
  document.getElementById('university_btn_label').innerText = univName;
  document.getElementById('university_dropdown_menu').classList.add('hidden');
}

function closeAllDropdowns() {
  const cMenu = document.getElementById('country_dropdown_menu');
  const pMenu = document.getElementById('province_dropdown_menu');
  const uMenu = document.getElementById('university_dropdown_menu');
  if (cMenu) cMenu.classList.add('hidden');
  if (pMenu) pMenu.classList.add('hidden');
  if (uMenu) uMenu.classList.add('hidden');
}

// Close dropdowns on outside click
document.addEventListener('click', function(e) {
  const cBtn = document.getElementById('country_btn');
  const cMenu = document.getElementById('country_dropdown_menu');
  const pBtn = document.getElementById('province_btn');
  const pMenu = document.getElementById('province_dropdown_menu');
  const uBtn = document.getElementById('university_btn');
  const uMenu = document.getElementById('university_dropdown_menu');

  if (cBtn && cMenu && !cBtn.contains(e.target) && !cMenu.contains(e.target)) {
    cMenu.classList.add('hidden');
  }
  if (pBtn && pMenu && !pBtn.contains(e.target) && !pMenu.contains(e.target)) {
    pMenu.classList.add('hidden');
  }
  if (uBtn && uMenu && !uBtn.contains(e.target) && !uMenu.contains(e.target)) {
    uMenu.classList.add('hidden');
  }
});
</script>
