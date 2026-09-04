<div class="min-h-screen py-16 flex items-center justify-center px-4" style="background:#F2F4F7;">
  <div class="w-full max-w-md bg-white p-8 rounded-3xl shadow-xl shadow-gray-200/50 border border-gray-100">
    <div class="text-center mb-8">
      <h2 class="font-serif text-[26px] font-bold text-gray-800">Forgot Password</h2>
      <p class="text-[13px] text-gray-500 mt-1.5">Enter your email and we'll send you a password reset link.</p>
    </div>

    <form method="POST" action="<?= url('/forgot-password') ?>" class="space-y-5">
      <?= csrf_field() ?>
      
      <div>
        <label class="block text-[12.5px] font-medium text-gray-700 mb-1.5" for="email">Email Address</label>
        <input id="email" type="email" name="email" required placeholder="you@example.com"
               class="w-full px-4 py-2.5 rounded-xl text-[14px] text-gray-800 bg-gray-50 border border-gray-200 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all">
      </div>

      <button type="submit" class="w-full py-2.5 rounded-xl text-[14px] font-semibold text-white transition-all hover:-translate-y-px"
              style="background:linear-gradient(135deg,#A22638,#800020);">
        Send Reset Link
      </button>
      
      <div class="text-center pt-2">
        <a href="<?= url('/login') ?>" class="text-[13px] text-blue-600 hover:underline">Back to Login</a>
      </div>
    </form>
  </div>
</div>
