<?php
/**
 * FAQ Index View
 * Variables: $faqs
 */
$faqTranslations = [
    'Who can join the IPH Alumni Association?' => [
        'bn' => 'কে আইপিএইচ অ্যালামনাই অ্যাসোসিয়েশনে যোগ দিতে পারবেন?',
        'en' => 'Who can join the IPH Alumni Association?'
    ],
    'Any graduate of the Institute of Public Health (IPH) can register. Your identity will be verified by our committee.' => [
        'bn' => 'ইনস্টিটিউট অব পাবলিক হেলথ (IPH) এর যেকোনো স্নাতক নিবন্ধন করতে পারেন। আপনার পরিচয় আমাদের কমিটি দ্বারা যাচাই করা হবে।',
        'en' => 'Any graduate of the Institute of Public Health (IPH) can register. Your identity will be verified by our committee.'
    ],
    'How long does verification take?' => [
        'bn' => 'যাচাইকরণ প্রক্রিয়া সম্পূর্ণ হতে কতক্ষণ সময় লাগে?',
        'en' => 'How long does verification take?'
    ],
    'Usually within 48 hours of registration on business days.' => [
        'bn' => 'সাধারণত কর্মদিবসের মধ্যে নিবন্ধনের ৪৮ ঘণ্টার মধ্যে সম্পন্ন হয়।',
        'en' => 'Usually within 48 hours of registration on business days.'
    ],
    'What is the difference between Annual and Lifetime membership?' => [
        'bn' => 'বার্ষিক এবং আজীবন সদস্যপদের মধ্যে পার্থক্য কী?',
        'en' => 'What is the difference between Annual and Lifetime membership?'
    ],
    'Annual membership renews every year (৳500/yr). Lifetime membership is a one-time payment (৳5,000) with no renewal and includes additional privileges like voting rights.' => [
        'bn' => 'বার্ষিক সদস্যপদ প্রতি বছর নবায়ন করতে হয় (৳৫০০/বছর)। আজীবন সদস্যপদ হলো এককালীন অর্থপ্রদান (৳৫,০০০) কোনো নবায়ন ফি ছাড়াই এবং এতে ভোটাধিকারের মতো অতিরিক্ত সুবিধা রয়েছে।',
        'en' => 'Annual membership renews every year (৳500/yr). Lifetime membership is a one-time payment (৳5,000) with no renewal and includes additional privileges like voting rights.'
    ],
    'Is my profile visible to everyone?' => [
        'bn' => 'আমার প্রোফাইল কি সবার কাছে দৃশ্যমান হবে?',
        'en' => 'Is my profile visible to everyone?'
    ],
    'Only verified alumni profiles are visible in the public directory. You can control your privacy settings from your portal.' => [
        'bn' => 'শুধুমাত্র ভেরিফাইড অ্যালামনাই প্রোফাইলগুলো ডিরেক্টরিতে প্রদর্শিত হবে। আপনি আপনার পোর্টাল থেকে গোপনীয়তা সেটিংস নিয়ন্ত্রণ করতে পারেন।',
        'en' => 'Only verified alumni profiles are visible in the public directory. You can control your privacy settings from your portal.'
    ],
    'How do I update my profile information?' => [
        'bn' => 'আমি কীভাবে আমার প্রোফাইলের তথ্য পরিবর্তন করব?',
        'en' => 'How do I update my profile information?'
    ],
    'Log into your alumni portal and go to My Profile to edit your information.' => [
        'bn' => 'আপনার অ্যালামনাই পোর্টালে লগইন করুন এবং তথ্য পরিবর্তন করতে "My Profile"-এ যান।',
        'en' => 'Log into your alumni portal and go to My Profile to edit your information.'
    ],
    'How do I get a QR membership ID card?' => [
        'bn' => 'আমি কীভাবে একটি কিউআর মেম্বারশিপ আইডি কার্ড পাব?',
        'en' => 'How do I get a QR membership ID card?'
    ],
    'After your membership is approved, your QR ID is available in Portal → Membership → View QR ID.' => [
        'bn' => 'আপনার সদস্যপদ অনুমোদিত হওয়ার পরে, আপনার কিউআর আইডিটি Portal → Membership → View QR ID-তে পাওয়া যাবে।',
        'en' => 'After your membership is approved, your QR ID is available in Portal → Membership → View QR ID.'
    ],
];
?>
<div class="max-w-4xl mx-auto px-6 py-14" x-data="{ active: null }">
  <div class="mb-10 text-center">
    <span class="font-mono text-[11px] tracking-widest text-[#2F8863] block mb-2"><?= __('সহায়তা কেন্দ্র', 'HELP CENTER') ?></span>
    <h1 class="font-serif text-[clamp(28px,4vw,40px)] font-semibold text-[#101820] mb-2"><?= __('সচরাচর জিজ্ঞাস্য প্রশ্নাবলী', 'Frequently Asked Questions') ?></h1>
    <p class="text-[14px] text-[#6B7178]"><?= __('প্ল্যাটফর্ম, নিবন্ধন এবং সদস্যপদ সম্পর্কিত সাধারণ প্রশ্নের উত্তর এখানে খুঁজুন।', 'Find answers to common questions about the platform, registration, and membership.') ?></p>
  </div>

  <?php if (empty($faqs)): ?>
  <div class="py-20 text-center glass">
    <p class="text-[#6B7178]"><?= __('জিজ্ঞাস্য লোড হচ্ছে। শীঘ্রই আবার চেক করুন!', 'FAQs are loading. Check back soon!') ?></p>
  </div>
  <?php else: ?>
  <div class="space-y-4">
    <?php foreach ($faqs as $i => $faq): 
      $qText = $faqTranslations[$faq['question']]['bn'] ?? $faq['question'];
      $qTextEn = $faqTranslations[$faq['question']]['en'] ?? $faq['question'];
      $aText = $faqTranslations[$faq['answer']]['bn'] ?? $faq['answer'];
      $aTextEn = $faqTranslations[$faq['answer']]['en'] ?? $faq['answer'];
    ?>
    <div class="rounded-2xl overflow-hidden transition-all"
         style="background:rgba(255,255,255,0.72);border:1px solid rgba(16,24,32,0.08);backdrop-filter:blur(14px);">
      <button @click="active = active === <?= $i ?> ? null : <?= $i ?>"
              class="w-full px-6 py-4 flex items-center justify-between text-left focus:outline-none">
        <span class="font-serif text-[15.5px] font-semibold text-[#101820]"><?= __($qText, $qTextEn) ?></span>
        <svg class="w-4 h-4 text-[#A22638] transform transition-transform duration-200"
             :class="active === <?= $i ?> ? 'rotate-180' : ''"
             fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
        </svg>
      </button>
      <div x-show="active === <?= $i ?>" x-collapse
           class="px-6 pb-5 text-[14px] text-[#6B7178] leading-relaxed" style="display:none;">
        <?= nl2br(__($aText, $aTextEn)) ?>
      </div>
    </div>
    <?php endforeach; ?>
  </div>
  <?php endif; ?>
</div>
