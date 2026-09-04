<?php $appName = function_exists('env') ? env('APP_NAME', 'IPH Alumni Association') : 'IPH Alumni Association'; ?>

<div class="min-h-screen flex items-center justify-center px-4 py-16">
  <div class="w-full max-w-md">
    
    <!-- Logo -->
    <div class="text-center mb-8">
      <a href="<?= url('/') ?>" class="inline-flex items-center gap-2.5 font-semibold text-[#101820] text-[16px]">
        <img src="<?= asset('images/LOGO.png') ?>" alt="Logo" class="w-10 h-10 object-contain">
        <?= e($appName) ?>
      </a>
      <p class="mt-3 text-[14px] text-[#6B7178]"><?= __('আবারও স্বাগতম। আপনার অ্যালামনাই অ্যাকাউন্টে লগইন করুন।', 'Welcome back. Log in to your alumni account.') ?></p>
    </div>

    <!-- Card -->
    <div class="p-8 rounded-3xl" style="background:rgba(255,255,255,0.85);border:1px solid rgba(16,24,32,0.09);backdrop-filter:blur(20px);box-shadow:0 20px 60px -20px rgba(16,24,32,0.18);">
      
      <form method="POST" action="<?= url('/login') ?>" class="space-y-5">
        <?= csrf_field() ?>

        <!-- Email -->
        <div>
          <label class="block text-[13px] font-medium text-[#101820] mb-1.5" for="email"><?= __('ইমেইল ঠিকানা', 'Email address') ?></label>
          <input id="email" type="email" name="email" value="<?= old('email') ?>" required autocomplete="email"
                 class="w-full px-4 py-2.5 rounded-xl text-[14px] text-[#101820] transition-all focus:outline-none focus:ring-2 focus:ring-[#800020]/40"
                 style="background:rgba(255,255,255,0.9);border:1px solid rgba(16,24,32,0.15);"
                 placeholder="you@example.com">
        </div>

        <!-- Password -->
        <div x-data="{ show: false }">
          <div class="flex justify-between mb-1.5">
            <label class="text-[13px] font-medium text-[#101820]" for="password"><?= __('পাসওয়ার্ড', 'Password') ?></label>
            <a href="<?= url('/forgot-password') ?>" class="text-[12px] text-[#800020] hover:underline"><?= __('ভুলে গেছেন?', 'Forgot?') ?></a>
          </div>
          <div class="relative">
            <input id="password" :type="show ? 'text' : 'password'" name="password" required autocomplete="current-password"
                   class="w-full px-4 py-2.5 pr-11 rounded-xl text-[14px] text-[#101820] transition-all focus:outline-none focus:ring-2 focus:ring-[#800020]/40"
                   style="background:rgba(255,255,255,0.9);border:1px solid rgba(16,24,32,0.15);"
                   placeholder="••••••••">
            <button type="button" @click="show = !show" class="absolute right-3 top-1/2 -translate-y-1/2 text-[#6B7178] hover:text-[#101820] transition-colors">
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path x-show="!show" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                <path x-show="show" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.477 0-8.268-2.943-9.542-7a10.05 10.05 0 011.318-2.825M6.343 6.343A9.965 9.965 0 0112 5c4.477 0 8.268 2.943 9.542 7a9.965 9.965 0 01-4.885 5.657M15 12a3 3 0 00-3-3m0 0a3 3 0 00-3 3m3-3l-6-6m9 9l-6 6"/>
              </svg>
            </button>
          </div>
        </div>

        <!-- Submit -->
        <button type="submit" class="w-full py-3 rounded-xl text-[15px] font-semibold text-white transition-all hover:-translate-y-0.5 hover:shadow-lg active:translate-y-0"
                style="background:linear-gradient(135deg,#A22638,#800020);box-shadow:0 6px 20px -6px rgba(128,0,32,0.5);">
          <?= __('লগইন করুন', 'Log In') ?>
        </button>
      </form>

      <p class="mt-6 text-center text-[13px] text-[#6B7178]">
        <?= __('এখনো সদস্য নন?', 'Not yet a member?') ?> <a href="<?= url('/register') ?>" class="text-[#800020] font-medium hover:underline"><?= __('নিবন্ধন করুন →', 'Register as Alumni →') ?></a>
      </p>
    </div>
  </div>
</div>
