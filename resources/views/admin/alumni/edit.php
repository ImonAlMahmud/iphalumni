<?php
/**
 * Admin Alumni Profile Edit View
 * Variables: $alumni, $education, $employment, $primaryEdu, $currentEmp, $allUniversities
 */
?>
<div class="max-w-5xl mx-auto py-6 font-['Kalpurush','Inter',sans-serif]">

  <!-- Header & Back Navigation -->
  <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
    <div>
      <div class="flex items-center gap-2 mb-1">
        <a href="<?= url('/admin/alumni/' . $alumni['id']) ?>" class="text-[12px] font-mono text-white/50 hover:text-white transition-colors">
          <i class="fa-solid fa-arrow-left mr-1"></i> <?= e($alumni['name']) ?>'s Profile
        </a>
        <span class="text-white/30 text-[10px]">/</span>
        <span class="text-[11px] font-mono font-bold text-amber-400 uppercase tracking-wider">
          EDIT MEMBER PROFILE
        </span>
      </div>
      <h1 class="font-serif text-[26px] font-bold text-white tracking-tight flex items-center gap-2.5">
        <i class="fa-solid fa-user-pen text-amber-400"></i>
        <?= __('মেম্বারের প্রোফাইল তথ্য এডিট করুন', 'Edit Alumni Profile') ?>
      </h1>
      <p class="text-[13px] text-white/60 mt-0.5">
        অ্যাডমিন প্যানেল থেকে এই সদস্যের অ্যাকাউন্ট, ব্যক্তিগত, শিক্ষাগত ও পেশাগত তথ্য সংশোধন করুন।
      </p>
    </div>

    <div class="flex items-center gap-2">
      <a href="<?= url('/admin/alumni/' . $alumni['id'] . '/id-card') ?>" target="_blank" class="px-3.5 py-2 rounded-xl bg-sky-500/20 hover:bg-sky-500/30 text-sky-300 border border-sky-500/30 text-[12.5px] font-semibold transition-all flex items-center gap-1.5">
        <i class="fa-solid fa-id-card text-[11px]"></i> ID Card
      </a>
      <a href="<?= url('/admin/alumni/' . $alumni['id'] . '/membership-card') ?>" target="_blank" class="px-3.5 py-2 rounded-xl bg-emerald-500/20 hover:bg-emerald-500/30 text-emerald-300 border border-emerald-500/30 text-[12.5px] font-semibold transition-all flex items-center gap-1.5">
        <i class="fa-solid fa-qrcode text-[11px]"></i> Membership Pass
      </a>
      <a href="<?= url('/admin/alumni/' . $alumni['id']) ?>" class="px-3.5 py-2 rounded-xl bg-white/10 hover:bg-white/20 text-white text-[12.5px] font-medium transition-all flex items-center gap-1.5">
        View Profile
      </a>
    </div>
  </div>

  <form method="POST" action="<?= url('/admin/alumni/' . $alumni['id'] . '/edit') ?>" enctype="multipart/form-data" class="space-y-6">
    <?= csrf_field() ?>

    <!-- Section 1: Account, Avatar & Verification Status -->
    <div class="p-6 rounded-3xl bg-white/5 border border-white/10 shadow-xl space-y-6">
      <h3 class="text-[16px] font-bold text-white flex items-center gap-2 pb-3 border-b border-white/10">
        <i class="fa-solid fa-user-shield text-[#E58E97]"></i> ১. অ্যাকাউন্ট ও স্ট্যাটাস তথ্য (Account & Status)
      </h3>

      <div class="grid grid-cols-1 md:grid-cols-2 gap-6 p-4 rounded-2xl bg-white/[0.03] border border-white/10">
        <!-- Current Avatar Preview & Upload -->
        <div class="flex items-center gap-4">
          <div class="w-20 h-20 rounded-2xl overflow-hidden bg-gradient-to-br from-[#800020] to-[#2F8863] flex items-center justify-center font-bold text-[24px] text-white shrink-0 shadow-lg border-2 border-white/20">
            <?php if (!empty($alumni['avatar'])): ?>
              <img src="<?= asset('storage/avatars/' . e($alumni['avatar'])) ?>" alt="Avatar" class="w-full h-full object-cover">
            <?php else: ?>
              <?= initials($alumni['name'] ?? 'M') ?>
            <?php endif; ?>
          </div>
          <div class="flex-1 min-w-0">
            <label class="block text-[12px] font-semibold text-white/80 mb-1">
              <i class="fa-solid fa-camera text-[#E58E97] mr-1"></i> প্রোফাইল ছবি (Avatar)
            </label>
            <input type="file" name="avatar" accept="image/*" class="text-[12px] text-white/60 file:mr-3 file:py-1.5 file:px-3 file:rounded-xl file:border-0 file:text-[11.5px] file:font-semibold file:bg-white/10 file:text-white hover:file:bg-white/20 w-full cursor-pointer">
            <p class="text-[11px] text-white/40 mt-1">JPG, PNG বা WebP ফরম্যাট (সর্বোচ্চ 2MB)</p>
          </div>
        </div>

        <!-- Member Digital Signature Preview & Upload -->
        <div class="flex items-center gap-4 md:border-l md:border-white/10 md:pl-6 pt-4 md:pt-0 border-t md:border-t-0 border-white/10">
          <div class="w-28 h-20 rounded-2xl overflow-hidden bg-white/95 flex items-center justify-center shrink-0 shadow-md border-2 border-white/20 p-1.5">
            <?php if (!empty($alumni['signature_image'])): ?>
              <img src="<?= asset('storage/signatures/' . e($alumni['signature_image'])) ?>" alt="Signature" class="max-h-full max-w-full object-contain filter contrast-125">
            <?php else: ?>
              <div class="text-center text-slate-400 text-[10.5px] leading-tight">
                <i class="fa-solid fa-signature text-[18px] mb-0.5 block text-slate-300"></i> No Sign
              </div>
            <?php endif; ?>
          </div>
          <div class="flex-1 min-w-0">
            <label class="block text-[12px] font-semibold text-emerald-300 mb-1">
              <i class="fa-solid fa-file-signature mr-1"></i> ডিজিটাল স্বাক্ষর (Digital Signature)
            </label>
            <input type="file" name="signature" accept="image/png,image/jpeg,image/webp" class="text-[12px] text-white/60 file:mr-3 file:py-1.5 file:px-3 file:rounded-xl file:border-0 file:text-[11.5px] file:font-semibold file:bg-emerald-600/30 file:text-emerald-200 hover:file:bg-emerald-600/50 w-full cursor-pointer">
            <div class="flex items-center justify-between mt-1">
              <p class="text-[11px] text-white/40">স্বচ্ছ ব্যাকগ্রাউন্ড (PNG) সর্বোচ্চ 2MB</p>
              <?php if (!empty($alumni['signature_image'])): ?>
                <label class="inline-flex items-center gap-1.5 text-[11px] text-rose-400 hover:text-rose-300 cursor-pointer ml-2">
                  <input type="checkbox" name="remove_signature" value="1" class="rounded border-white/20 bg-black/40 text-rose-500 focus:ring-0">
                  স্বাক্ষর মুছে ফেলুন
                </label>
              <?php endif; ?>
            </div>
          </div>
        </div>
      </div>

      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 text-[13px]">
        <!-- Full Name -->
        <div>
          <label class="block text-[11px] font-mono text-white/60 mb-1">সদস্যের পুরো নাম (Full Name) <span class="text-rose-400">*</span></label>
          <input type="text" name="name" required value="<?= e($alumni['name'] ?? '') ?>" class="w-full px-3.5 py-2.5 rounded-xl bg-black/50 border border-white/15 text-white focus:outline-none focus:border-[#E58E97]">
        </div>

        <!-- Primary Email -->
        <div>
          <label class="block text-[11px] font-mono text-white/60 mb-1">প্রাইমারি ইমেইল (Primary Email) <span class="text-rose-400">*</span></label>
          <input type="email" name="email" required value="<?= e($alumni['email'] ?? '') ?>" class="w-full px-3.5 py-2.5 rounded-xl bg-black/50 border border-white/15 text-white focus:outline-none focus:border-[#E58E97]">
        </div>

        <!-- Secondary Email -->
        <div>
          <label class="block text-[11px] font-mono text-white/60 mb-1">বিকল্প ইমেইল (Secondary Email)</label>
          <input type="email" name="secondary_email" value="<?= e($alumni['secondary_email'] ?? '') ?>" placeholder="optional@example.com" class="w-full px-3.5 py-2.5 rounded-xl bg-black/50 border border-white/15 text-white focus:outline-none focus:border-[#E58E97]">
        </div>

        <!-- Phone Number -->
        <div>
          <label class="block text-[11px] font-mono text-white/60 mb-1">মোবাইল নম্বর (Phone Number)</label>
          <input type="text" name="phone" value="<?= e($alumni['phone'] ?? '') ?>" placeholder="+8801..." class="w-full px-3.5 py-2.5 rounded-xl bg-black/50 border border-white/15 text-white focus:outline-none focus:border-[#E58E97]">
        </div>

        <!-- Profile Status -->
        <div>
          <label class="block text-[11px] font-mono text-white/60 mb-1">প্রোফাইল স্ট্যাটাস (Verification Status)</label>
          <select name="status" class="w-full px-3.5 py-2.5 rounded-xl bg-black/50 border border-white/15 text-white focus:outline-none focus:border-[#E58E97]">
            <option value="approved" <?= ($alumni['status'] ?? '') === 'approved' ? 'selected' : '' ?>>Approved (অনুমোদিত)</option>
            <option value="verified" <?= ($alumni['status'] ?? '') === 'verified' ? 'selected' : '' ?>>Verified (যাচাইকৃত)</option>
            <option value="pending" <?= ($alumni['status'] ?? '') === 'pending' ? 'selected' : '' ?>>Pending (অপেক্ষমান)</option>
            <option value="under_review" <?= ($alumni['status'] ?? '') === 'under_review' ? 'selected' : '' ?>>Under Review (পর্যালোচনাধীন)</option>
            <option value="rejected" <?= ($alumni['status'] ?? '') === 'rejected' ? 'selected' : '' ?>>Rejected (বাতিল)</option>
          </select>
        </div>

        <!-- Role -->
        <div>
          <label class="block text-[11px] font-mono text-white/60 mb-1">ব্যবহারকারীর রোল (User Role)</label>
          <select name="role" class="w-full px-3.5 py-2.5 rounded-xl bg-black/50 border border-white/15 text-white focus:outline-none focus:border-[#E58E97]">
            <option value="alumni" <?= ($alumni['role'] ?? '') === 'alumni' ? 'selected' : '' ?>>Alumni Member (সাধারণ সদস্য)</option>
            <option value="admin" <?= ($alumni['role'] ?? '') === 'admin' ? 'selected' : '' ?>>Admin (প্রশাসক)</option>
            <option value="editor" <?= ($alumni['role'] ?? '') === 'editor' ? 'selected' : '' ?>>Editor (সম্পাদক)</option>
            <option value="super_admin" <?= ($alumni['role'] ?? '') === 'super_admin' ? 'selected' : '' ?>>Super Admin</option>
          </select>
        </div>
      </div>
    </div>

    <!-- Section 2: Personal & Identity Details -->
    <div class="p-6 rounded-3xl bg-white/5 border border-white/10 shadow-xl space-y-4">
      <h3 class="text-[16px] font-bold text-white flex items-center gap-2 pb-3 border-b border-white/10">
        <i class="fa-solid fa-address-card text-emerald-400"></i> ২. ব্যক্তিগত ও নাগরিক তথ্য (Personal & Identity)
      </h3>

      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 text-[13px]">
        <div>
          <label class="block text-[11px] font-mono text-white/60 mb-1">NID নম্বর (National ID)</label>
          <input type="text" name="nid_number" value="<?= e($alumni['nid_number'] ?? '') ?>" placeholder="জাতীয় পরিচয়পত্র নম্বর" class="w-full px-3.5 py-2.5 rounded-xl bg-black/50 border border-white/15 text-white focus:outline-none focus:border-emerald-400">
        </div>

        <div>
          <label class="block text-[11px] font-mono text-white/60 mb-1">জন্ম তারিখ (Date of Birth)</label>
          <input type="date" name="dob" value="<?= !empty($alumni['dob']) ? date('Y-m-d', strtotime($alumni['dob'])) : '' ?>" class="w-full px-3.5 py-2.5 rounded-xl bg-black/50 border border-white/15 text-white focus:outline-none focus:border-emerald-400">
        </div>

        <div>
          <label class="block text-[11px] font-mono text-white/60 mb-1">লিঙ্গ (Gender)</label>
          <select name="gender" class="w-full px-3.5 py-2.5 rounded-xl bg-black/50 border border-white/15 text-white focus:outline-none focus:border-emerald-400">
            <option value="">-- নির্বাচন করুন --</option>
            <option value="male" <?= strtolower($alumni['gender'] ?? '') === 'male' ? 'selected' : '' ?>>Male (পুরুষ)</option>
            <option value="female" <?= strtolower($alumni['gender'] ?? '') === 'female' ? 'selected' : '' ?>>Female (মহিলা)</option>
            <option value="other" <?= strtolower($alumni['gender'] ?? '') === 'other' ? 'selected' : '' ?>>Other (অন্যান্য)</option>
          </select>
        </div>

        <div>
          <label class="block text-[11px] font-mono text-white/60 mb-1">রক্তের গ্রুপ (Blood Group)</label>
          <select name="blood_group" class="w-full px-3.5 py-2.5 rounded-xl bg-black/50 border border-white/15 text-white focus:outline-none focus:border-emerald-400">
            <option value="">-- নির্বাচন করুন --</option>
            <?php foreach (['A+', 'A-', 'B+', 'B-', 'O+', 'O-', 'AB+', 'AB-'] as $bg): ?>
            <option value="<?= $bg ?>" <?= ($alumni['blood_group'] ?? '') === $bg ? 'selected' : '' ?>><?= $bg ?></option>
            <?php endforeach; ?>
          </select>
        </div>
      </div>
    </div>

    <!-- Section 3: Academic Records -->
    <div class="p-6 rounded-3xl bg-white/5 border border-white/10 shadow-xl space-y-4">
      <h3 class="text-[16px] font-bold text-white flex items-center gap-2 pb-3 border-b border-white/10">
        <i class="fa-solid fa-graduation-cap text-sky-400"></i> ৩. শিক্ষাগত ও ইনস্টিটিউট তথ্য (Academic Info)
      </h3>

      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 text-[13px]">
        <div>
          <label class="block text-[11px] font-mono text-white/60 mb-1">ব্যাচ বছর (Batch Year)</label>
          <input type="text" name="batch_year" value="<?= e($alumni['batch_year'] ?? '') ?>" placeholder="যেমন: 2018 বা Batch-01" class="w-full px-3.5 py-2.5 rounded-xl bg-black/50 border border-white/15 text-white focus:outline-none focus:border-sky-400">
        </div>

        <div>
          <label class="block text-[11px] font-mono text-white/60 mb-1">স্টুডেন্ট / রেজিঃ নম্বর (Student / DU Reg ID)</label>
          <input type="text" name="student_id" value="<?= e($alumni['student_id'] ?? '') ?>" placeholder="যেমন: 2018-001" class="w-full px-3.5 py-2.5 rounded-xl bg-black/50 border border-white/15 text-white focus:outline-none focus:border-sky-400">
        </div>

        <div>
          <label class="block text-[11px] font-mono text-white/60 mb-1">সেশন (Session Years)</label>
          <input type="text" name="session_years" value="<?= e($alumni['session_years'] ?? '') ?>" placeholder="যেমন: 2017-2018" class="w-full px-3.5 py-2.5 rounded-xl bg-black/50 border border-white/15 text-white focus:outline-none focus:border-sky-400">
        </div>

        <div>
          <label class="block text-[11px] font-mono text-white/60 mb-1">ডিগ্রী / কোর্স (Degree / Course)</label>
          <input type="text" name="degree" value="<?= e($primaryEdu['degree'] ?? '') ?>" placeholder="যেমন: Master of Public Health (MPH)" class="w-full px-3.5 py-2.5 rounded-xl bg-black/50 border border-white/15 text-white focus:outline-none focus:border-sky-400">
        </div>

        <div>
          <label class="block text-[11px] font-mono text-white/60 mb-1">বিষয় / বিভাগ (Field of Study)</label>
          <input type="text" name="field_of_study" value="<?= e($primaryEdu['field_of_study'] ?? '') ?>" placeholder="যেমন: Epidemiology / Community Medicine" class="w-full px-3.5 py-2.5 rounded-xl bg-black/50 border border-white/15 text-white focus:outline-none focus:border-sky-400">
        </div>

        <div>
          <label class="block text-[11px] font-mono text-white/60 mb-1">পাস করার বছর (Graduation Year)</label>
          <input type="text" name="graduation_year" value="<?= e($primaryEdu['graduation_year'] ?? '') ?>" placeholder="যেমন: 2020" class="w-full px-3.5 py-2.5 rounded-xl bg-black/50 border border-white/15 text-white focus:outline-none focus:border-sky-400">
        </div>

        <div class="sm:col-span-2 lg:col-span-3">
          <label class="block text-[11px] font-mono text-white/60 mb-1">প্রতিষ্ঠান / বিশ্ববিদ্যালয় (Institution / University)</label>
          <input type="text" name="institution" value="<?= e($primaryEdu['institution'] ?? 'Institute of Public Health (IPH)') ?>" class="w-full px-3.5 py-2.5 rounded-xl bg-black/50 border border-white/15 text-white focus:outline-none focus:border-sky-400">
        </div>
      </div>
    </div>

    <!-- Section 4: Professional & Employment Details -->
    <div class="p-6 rounded-3xl bg-white/5 border border-white/10 shadow-xl space-y-4">
      <h3 class="text-[16px] font-bold text-white flex items-center gap-2 pb-3 border-b border-white/10">
        <i class="fa-solid fa-briefcase text-purple-400"></i> ৪. পেশাগত ও কর্মসংস্থান তথ্য (Professional & Career)
      </h3>

      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 text-[13px]">
        <div>
          <label class="block text-[11px] font-mono text-white/60 mb-1">বর্তমান প্রতিষ্ঠান (Organization / Hospital / Workplace)</label>
          <input type="text" name="organization" value="<?= e($currentEmp['organization'] ?? '') ?>" placeholder="কর্মস্থলের নাম" class="w-full px-3.5 py-2.5 rounded-xl bg-black/50 border border-white/15 text-white focus:outline-none focus:border-purple-400">
        </div>

        <div>
          <label class="block text-[11px] font-mono text-white/60 mb-1">পদবী (Job Title / Designation)</label>
          <input type="text" name="designation" value="<?= e($currentEmp['job_title'] ?? ($currentEmp['designation'] ?? '')) ?>" placeholder="যেমন: Medical Officer / Consultant" class="w-full px-3.5 py-2.5 rounded-xl bg-black/50 border border-white/15 text-white focus:outline-none focus:border-purple-400">
        </div>

        <div>
          <label class="block text-[11px] font-mono text-white/60 mb-1">বিভাগ / ইউনিট (Department / Unit)</label>
          <input type="text" name="department" value="<?= e($currentEmp['department'] ?? '') ?>" placeholder="যেমন: Dept of Health Services" class="w-full px-3.5 py-2.5 rounded-xl bg-black/50 border border-white/15 text-white focus:outline-none focus:border-purple-400">
        </div>

        <div>
          <label class="block text-[11px] font-mono text-white/60 mb-1">বর্তমান কার্যধারা (Activity Type)</label>
          <select name="activity_type" class="w-full px-3.5 py-2.5 rounded-xl bg-black/50 border border-white/15 text-white focus:outline-none focus:border-purple-400">
            <option value="work" <?= ($alumni['activity_type'] ?? '') === 'work' ? 'selected' : '' ?>>Job / Employment (চাকরি)</option>
            <option value="study" <?= ($alumni['activity_type'] ?? '') === 'study' ? 'selected' : '' ?>>Higher Studies (উচ্চশিক্ষা)</option>
            <option value="research" <?= ($alumni['activity_type'] ?? '') === 'research' ? 'selected' : '' ?>>Research (গবেষণা)</option>
            <option value="business" <?= ($alumni['activity_type'] ?? '') === 'business' ? 'selected' : '' ?>>Business / Practice (ব্যবসা/প্র্যাকটিস)</option>
            <option value="other" <?= ($alumni['activity_type'] ?? '') === 'other' ? 'selected' : '' ?>>Other (অন্যান্য)</option>
          </select>
        </div>

        <div>
          <label class="block text-[11px] font-mono text-white/60 mb-1">অভিজ্ঞতা (Years of Experience)</label>
          <input type="text" name="experience_years" value="<?= e($alumni['experience_years'] ?? '') ?>" placeholder="যেমন: 5 Years" class="w-full px-3.5 py-2.5 rounded-xl bg-black/50 border border-white/15 text-white focus:outline-none focus:border-purple-400">
        </div>

        <div>
          <label class="block text-[11px] font-mono text-white/60 mb-1">স্পেশালাইজেশন (Specialization)</label>
          <input type="text" name="specialization" value="<?= e($alumni['specialization'] ?? '') ?>" placeholder="যেমন: Public Health, Cardiology..." class="w-full px-3.5 py-2.5 rounded-xl bg-black/50 border border-white/15 text-white focus:outline-none focus:border-purple-400">
        </div>
      </div>
    </div>

    <!-- Section 5: Location & Address -->
    <div class="p-6 rounded-3xl bg-white/5 border border-white/10 shadow-xl space-y-4">
      <h3 class="text-[16px] font-bold text-white flex items-center gap-2 pb-3 border-b border-white/10">
        <i class="fa-solid fa-location-dot text-rose-400"></i> ৫. বর্তমান ও স্থায়ী ঠিকানা (Location & Address)
      </h3>

      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 text-[13px]">
        <div>
          <label class="block text-[11px] font-mono text-white/60 mb-1">অবস্থানের ধরন (Location Type)</label>
          <select name="location_type" class="w-full px-3.5 py-2.5 rounded-xl bg-black/50 border border-white/15 text-white focus:outline-none focus:border-rose-400">
            <option value="bangladesh" <?= ($alumni['location_type'] ?? 'bangladesh') === 'bangladesh' ? 'selected' : '' ?>>Bangladesh (বাংলাদেশ)</option>
            <option value="abroad" <?= ($alumni['location_type'] ?? '') === 'abroad' ? 'selected' : '' ?>>Abroad (প্রবাসী / প্রবাসী সদস্য)</option>
          </select>
        </div>

        <div>
          <label class="block text-[11px] font-mono text-white/60 mb-1">বর্তমান শহর / জেলা (Current Location)</label>
          <input type="text" name="current_location" value="<?= e($alumni['current_location'] ?? '') ?>" placeholder="যেমন: Dhaka" class="w-full px-3.5 py-2.5 rounded-xl bg-black/50 border border-white/15 text-white focus:outline-none focus:border-rose-400">
        </div>

        <div>
          <label class="block text-[11px] font-mono text-white/60 mb-1">থানা / উপজেলা (Thana / Upazila)</label>
          <input type="text" name="thana_upazila" value="<?= e($alumni['thana_upazila'] ?? '') ?>" placeholder="যেমন: Dhanmondi" class="w-full px-3.5 py-2.5 rounded-xl bg-black/50 border border-white/15 text-white focus:outline-none focus:border-rose-400">
        </div>

        <div>
          <label class="block text-[11px] font-mono text-white/60 mb-1">দেশ (Country)</label>
          <input type="text" name="country" value="<?= e($alumni['country'] ?? 'Bangladesh') ?>" class="w-full px-3.5 py-2.5 rounded-xl bg-black/50 border border-white/15 text-white focus:outline-none focus:border-rose-400">
        </div>

        <div>
          <label class="block text-[11px] font-mono text-white/60 mb-1">স্থায়ী জেলা (Permanent District)</label>
          <input type="text" name="permanent_district" value="<?= e($alumni['permanent_district'] ?? '') ?>" placeholder="স্থায়ী জেলা" class="w-full px-3.5 py-2.5 rounded-xl bg-black/50 border border-white/15 text-white focus:outline-none focus:border-rose-400">
        </div>

        <div>
          <label class="block text-[11px] font-mono text-white/60 mb-1">স্থায়ী ঠিকানা (Permanent Address)</label>
          <input type="text" name="permanent_location" value="<?= e($alumni['permanent_location'] ?? '') ?>" placeholder="গ্রাম/রোড/এলাকা" class="w-full px-3.5 py-2.5 rounded-xl bg-black/50 border border-white/15 text-white focus:outline-none focus:border-rose-400">
        </div>
      </div>
    </div>

    <!-- Section 6: Bio & Social Profile Links -->
    <div class="p-6 rounded-3xl bg-white/5 border border-white/10 shadow-xl space-y-4">
      <h3 class="text-[16px] font-bold text-white flex items-center gap-2 pb-3 border-b border-white/10">
        <i class="fa-solid fa-share-nodes text-indigo-400"></i> ৬. বায়ো ও সোশ্যাল লিংক (Bio & Web Links)
      </h3>

      <div class="space-y-4 text-[13px]">
        <div>
          <label class="block text-[11px] font-mono text-white/60 mb-1">সংক্ষিপ্ত পরিচিতি / বায়ো (About / Bio)</label>
          <textarea name="bio" rows="3" placeholder="সদস্যের কর্মজীবন ও সাধারণ পরিচিতি..." class="w-full px-3.5 py-2.5 rounded-xl bg-black/50 border border-white/15 text-white focus:outline-none focus:border-indigo-400"><?= e($alumni['bio'] ?? '') ?></textarea>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
          <div>
            <label class="block text-[11px] font-mono text-white/60 mb-1"><i class="fa-brands fa-linkedin text-sky-400 mr-1"></i> LinkedIn Profile URL</label>
            <input type="url" name="linkedin_url" value="<?= e($alumni['linkedin_url'] ?? '') ?>" placeholder="https://linkedin.com/in/..." class="w-full px-3.5 py-2.5 rounded-xl bg-black/50 border border-white/15 text-white focus:outline-none focus:border-indigo-400">
          </div>

          <div>
            <label class="block text-[11px] font-mono text-white/60 mb-1"><i class="fa-brands fa-facebook text-blue-400 mr-1"></i> Facebook Profile URL</label>
            <input type="url" name="facebook_url" value="<?= e($alumni['facebook_url'] ?? '') ?>" placeholder="https://facebook.com/..." class="w-full px-3.5 py-2.5 rounded-xl bg-black/50 border border-white/15 text-white focus:outline-none focus:border-indigo-400">
          </div>

          <div>
            <label class="block text-[11px] font-mono text-white/60 mb-1"><i class="fa-solid fa-globe text-emerald-400 mr-1"></i> Personal Website</label>
            <input type="url" name="website" value="<?= e($alumni['website'] ?? '') ?>" placeholder="https://..." class="w-full px-3.5 py-2.5 rounded-xl bg-black/50 border border-white/15 text-white focus:outline-none focus:border-indigo-400">
          </div>
        </div>
      </div>
    </div>

    <!-- Submit Action Bar -->
    <div class="flex items-center justify-between p-5 rounded-2xl bg-black/40 border border-white/10">
      <a href="<?= url('/admin/alumni/' . $alumni['id']) ?>" class="px-5 py-2.5 rounded-xl bg-white/10 hover:bg-white/20 text-white font-medium text-[13px] transition-all">
        বাতিল করুন (Cancel)
      </a>

      <button type="submit" class="px-7 py-2.5 rounded-xl bg-gradient-to-r from-[#800020] via-[#A22638] to-[#800020] hover:brightness-110 text-white font-bold text-[14px] shadow-lg shadow-[#800020]/30 transition-all flex items-center gap-2">
        <i class="fa-solid fa-floppy-disk"></i> পরিবর্তনসমূহ সংরক্ষণ করুন (Save Changes)
      </button>
    </div>

  </form>

</div>
