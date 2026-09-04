<?php
/**
 * Create Job Posting View
 */
?>
<!-- Header -->
<div class="mb-8">
  <a href="<?= url('/portal/jobs') ?>" class="inline-flex items-center gap-1.5 text-[13px] font-medium text-[#6B7178] hover:text-[#800020] transition-colors mb-3">
    <i class="fa-solid fa-arrow-left text-[11px]"></i> Back to Job Management
  </a>
  <h1 class="font-serif text-[26px] font-semibold text-[#101820]">Post a Job Opening</h1>
  <p class="text-[14px] text-[#6B7178] mt-1">Share job opportunities in your company or network with IPH alumni and students.</p>
</div>

<!-- Form Card -->
<div class="p-8 rounded-2xl w-full" style="background:rgba(255,255,255,0.9);border:1px solid rgba(16,24,32,0.08);box-shadow:0 4px 20px -6px rgba(16,24,32,0.08);">
  <form method="POST" action="<?= url('/portal/jobs') ?>" class="space-y-6">

    <!-- Job Title & Company -->
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
      <div>
        <label class="block text-[13px] font-semibold text-[#101820] mb-2">Job Title <span class="text-red-500">*</span></label>
        <input type="text" name="title" required placeholder="e.g. Senior Medical Officer / Research Analyst"
               class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-[13.5px] focus:outline-none focus:border-[#800020]">
      </div>
      <div>
        <label class="block text-[13px] font-semibold text-[#101820] mb-2">Company / Organization <span class="text-red-500">*</span></label>
        <input type="text" name="company_name" required placeholder="e.g. ICDDR,B / WHO / Square Hospital"
               class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-[13.5px] focus:outline-none focus:border-[#800020]">
      </div>
    </div>

    <!-- Visibility Option (KEY REQUIREMENT) -->
    <div class="p-5 rounded-xl border-2 border-[#800020]/20" style="background:rgba(128,0,32,0.03);">
      <label class="block text-[14px] font-bold text-[#800020] mb-2">
        <i class="fa-solid fa-eye mr-1"></i> Job Circular Visibility <span class="text-red-500">*</span>
      </label>
      
      <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mt-3">
        <label class="p-4 rounded-xl border border-gray-200 bg-white flex items-start gap-3 cursor-pointer hover:border-[#800020] transition-colors">
          <input type="radio" name="visibility" value="members" checked class="mt-1 accent-[#800020]">
          <div>
            <div class="font-semibold text-[13.5px] text-[#101820]">🔒 Members Only</div>
            <div class="text-[12px] text-[#6B7178] mt-0.5">শুধুমাত্র লগইন করা IPH অ্যালামনাই সদস্যরা দেখতে ও আবেদন করতে পারবেন।</div>
          </div>
        </label>

        <label class="p-4 rounded-xl border border-gray-200 bg-white flex items-start gap-3 cursor-pointer hover:border-[#2F8863] transition-colors">
          <input type="radio" name="visibility" value="public" class="mt-1 accent-[#2F8863]">
          <div>
            <div class="font-semibold text-[13.5px] text-[#2F8863]">🌐 Public (Student Reference Verified)</div>
            <div class="text-[12px] text-[#6B7178] mt-0.5">সবাই দেখতে পারবে। তবে শুধু "Student Reference Database" এর Verified Student রা আবেদন করতে পারবেন।</div>
          </div>
        </label>
      </div>
    </div>

    <!-- Job Type & Location -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
      <div>
        <label class="block text-[13px] font-semibold text-[#101820] mb-2">Job Type</label>
        <select name="job_type" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-[13.5px] bg-white focus:outline-none focus:border-[#800020]">
          <option value="Full-time">Full-time</option>
          <option value="Part-time">Part-time</option>
          <option value="Contract">Contract</option>
          <option value="Remote">Remote</option>
          <option value="Internship">Internship</option>
        </select>
      </div>

      <div>
        <label class="block text-[13px] font-semibold text-[#101820] mb-2">Location</label>
        <input type="text" name="location" placeholder="Dhaka / Remote / Overseas"
               class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-[13.5px] focus:outline-none focus:border-[#800020]">
      </div>

      <div>
        <label class="block text-[13px] font-semibold text-[#101820] mb-2">Salary Range (Optional)</label>
        <input type="text" name="salary_range" placeholder="e.g. ৳50,000 - ৳70,000"
               class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-[13.5px] focus:outline-none focus:border-[#800020]">
      </div>
    </div>

    <!-- Deadline -->
    <div>
      <label class="block text-[13px] font-semibold text-[#101820] mb-2">Application Deadline</label>
      <input type="date" name="deadline"
             class="w-full sm:w-1/2 px-4 py-2.5 rounded-xl border border-gray-200 text-[13.5px] focus:outline-none focus:border-[#800020]">
    </div>

    <!-- Description -->
    <div>
      <label class="block text-[13px] font-semibold text-[#101820] mb-2">Job Description <span class="text-red-500">*</span></label>
      <textarea name="description" rows="5" required placeholder="Describe responsibilities, role overview, and team..."
                class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-[13.5px] focus:outline-none focus:border-[#800020]"></textarea>
    </div>

    <!-- Requirements -->
    <div>
      <label class="block text-[13px] font-semibold text-[#101820] mb-2">Requirements (Optional)</label>
      <textarea name="requirements" rows="4" placeholder="Educational background, experience, required skills..."
                class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-[13.5px] focus:outline-none focus:border-[#800020]"></textarea>
    </div>

    <!-- Application Method Selection -->
    <div x-data="{ applyType: 'portal' }" class="p-5 rounded-xl border-2 border-indigo-900/15" style="background:rgba(16,24,32,0.02);">
      <label class="block text-[14px] font-bold text-[#101820] mb-2">
        <i class="fa-solid fa-paper-plane mr-1 text-[#800020]"></i> Application Method (প্রার্থীরা কীভাবে আবেদন করবেন) <span class="text-red-500">*</span>
      </label>

      <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 mt-3">
        <label class="p-3.5 rounded-xl border border-gray-200 bg-white flex items-start gap-2.5 cursor-pointer hover:border-[#800020] transition-colors">
          <input type="radio" name="apply_type" value="portal" x-model="applyType" class="mt-1 accent-[#800020]">
          <div>
            <div class="font-semibold text-[13px] text-[#101820]">📌 IPH Portal Form</div>
            <div class="text-[11.5px] text-[#6B7178] mt-0.5">প্রার্থীরা এই প্ল্যাটফর্মে সরাসরি রিজুমি ও কভার লেটার দিয়ে আবেদন করবেন।</div>
          </div>
        </label>

        <label class="p-3.5 rounded-xl border border-gray-200 bg-white flex items-start gap-2.5 cursor-pointer hover:border-[#800020] transition-colors">
          <input type="radio" name="apply_type" value="external_link" x-model="applyType" class="mt-1 accent-[#800020]">
          <div>
            <div class="font-semibold text-[13px] text-[#101820]">🔗 External Website / Link</div>
            <div class="text-[11.5px] text-[#6B7178] mt-0.5">কোম্পানির নিজস্ব ক্যারিয়ার ওয়েবসাইট বা গুগল ফর্মে রিডাইরেক্ট হবে।</div>
          </div>
        </label>

        <label class="p-3.5 rounded-xl border border-gray-200 bg-white flex items-start gap-2.5 cursor-pointer hover:border-[#800020] transition-colors">
          <input type="radio" name="apply_type" value="email" x-model="applyType" class="mt-1 accent-[#800020]">
          <div>
            <div class="font-semibold text-[13px] text-[#101820]">✉️ Direct HR Email</div>
            <div class="text-[11.5px] text-[#6B7178] mt-0.5">প্রার্থীরা নির্দিষ্ট ইমেইল ঠিকানায় সরাসরি তাদের সিভি পাঠাবেন।</div>
          </div>
        </label>
      </div>

      <!-- External Link Input -->
      <div x-show="applyType === 'external_link'" x-transition class="mt-4">
        <label class="block text-[12.5px] font-semibold text-[#101820] mb-1.5">Application Website Link (URL) <span class="text-red-500">*</span></label>
        <input type="url" name="apply_link" placeholder="https://careers.company.com/apply/123"
               class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-[13.5px] bg-white focus:outline-none focus:border-[#800020]">
      </div>

      <!-- Email Input -->
      <div x-show="applyType === 'email'" x-transition class="mt-4">
        <label class="block text-[12.5px] font-semibold text-[#101820] mb-1.5">HR / Application Email Address <span class="text-red-500">*</span></label>
        <input type="email" name="apply_email" placeholder="hr@company.com"
               class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-[13.5px] bg-white focus:outline-none focus:border-[#800020]">
      </div>
    </div>

    <!-- How to Apply Instructions -->
    <div>
      <label class="block text-[13px] font-semibold text-[#101820] mb-2">Additional Instructions / How to Apply (Optional)</label>
      <textarea name="how_to_apply" rows="3" placeholder="আবেদনকারীদের জন্য অতিরিক্ত কোনো নির্দেশাবলী থাকলে লিখুন (যেমন: সাবজেক্ট লাইনে জব কোড উল্লেখ করুন)..."
                class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-[13.5px] focus:outline-none focus:border-[#800020]"></textarea>
    </div>

    <!-- Submit Button -->
    <div class="pt-4 border-t border-gray-100 flex items-center justify-end gap-3">
      <a href="<?= url('/portal/jobs') ?>" class="px-5 py-2.5 rounded-xl text-[13.5px] font-medium text-[#6B7178] hover:bg-gray-100 transition-colors">
        Cancel
      </a>
      <button type="submit" class="px-7 py-2.5 rounded-xl text-[14px] font-semibold text-white transition-all shadow-md hover:-translate-y-0.5"
              style="background:linear-gradient(135deg,#A22638,#800020);">
        <i class="fa-solid fa-paper-plane mr-2"></i> Post Job Circular
      </button>
    </div>
  </form>
</div>
