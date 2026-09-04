<div class="min-h-screen py-16 flex items-center justify-center px-4" style="background:#F2F4F7;">
  <div class="w-full max-w-md bg-white p-8 rounded-3xl shadow-xl shadow-gray-200/50 border border-gray-100">
    <div class="text-center mb-8">
      <h2 class="font-serif text-[26px] font-bold text-gray-800">Reset Password</h2>
      <p class="text-[13px] text-gray-500 mt-1.5">Please enter a new secure password for your account.</p>
    </div>

    <form method="POST" action="<?= url('/reset-password') ?>" class="space-y-5">
      <?= csrf_field() ?>
      <input type="hidden" name="token" value="<?= e($token) ?>">
      
      <div>
        <label class="block text-[12.5px] font-medium text-gray-700 mb-1.5" for="password">New Password</label>
        <input id="password" type="password" name="password" required placeholder="Minimum 8 characters"
               class="w-full px-4 py-2.5 rounded-xl text-[14px] text-gray-800 bg-gray-50 border border-gray-200 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all">
      </div>

      <div>
        <label class="block text-[12.5px] font-medium text-gray-700 mb-1.5" for="password_confirm">Confirm Password</label>
        <input id="password_confirm" type="password" name="password_confirm" required placeholder="Confirm your password"
               class="w-full px-4 py-2.5 rounded-xl text-[14px] text-gray-800 bg-gray-50 border border-gray-200 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all">
      </div>

      <button type="submit" class="w-full py-2.5 rounded-xl text-[14px] font-semibold text-white transition-all hover:-translate-y-px"
              style="background:linear-gradient(135deg,#A22638,#800020);">
        Update Password
      </button>
    </form>
  </div>
</div>
