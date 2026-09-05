<?php
/**
 * Admin Settings View
 * Variables: $settingsMap (site settings)
 */
?>
<div class="p-8 rounded-3xl" style="background:rgba(255,255,255,0.04);border:1px solid rgba(255,255,255,0.08);">
  <form method="POST" action="<?= url('/admin/settings') ?>" class="space-y-5">
    <?= csrf_field() ?>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
      <div>
        <label class="block text-[13px] font-medium text-white/70 mb-1.5" for="site_name">Association Name</label>
        <input id="site_name" type="text" name="site_name" value="<?= e($settingsMap['site_name'] ?? '') ?>" required
               class="w-full px-4 py-2.5 rounded-xl text-[14px] text-white focus:outline-none"
               style="background:rgba(255,255,255,0.06);border:1px solid rgba(255,255,255,0.12);">
      </div>
      <div>
        <label class="block text-[13px] font-medium text-white/70 mb-1.5" for="site_tagline">Tagline</label>
        <input id="site_tagline" type="text" name="site_tagline" value="<?= e($settingsMap['site_tagline'] ?? '') ?>"
               class="w-full px-4 py-2.5 rounded-xl text-[14px] text-white focus:outline-none"
               style="background:rgba(255,255,255,0.06);border:1px solid rgba(255,255,255,0.12);">
      </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
      <div>
        <label class="block text-[13px] font-medium text-white/70 mb-1.5" for="site_email">Contact Email</label>
        <input id="site_email" type="email" name="site_email" value="<?= e($settingsMap['site_email'] ?? '') ?>" required
               class="w-full px-4 py-2.5 rounded-xl text-[14px] text-white focus:outline-none"
               style="background:rgba(255,255,255,0.06);border:1px solid rgba(255,255,255,0.12);">
      </div>
      <div>
        <label class="block text-[13px] font-medium text-white/70 mb-1.5" for="site_phone">Contact Phone</label>
        <input id="site_phone" type="tel" name="site_phone" value="<?= e($settingsMap['site_phone'] ?? '') ?>"
               class="w-full px-4 py-2.5 rounded-xl text-[14px] text-white focus:outline-none"
               style="background:rgba(255,255,255,0.06);border:1px solid rgba(255,255,255,0.12);">
      </div>
      <div>
        <label class="block text-[13px] font-medium text-white/70 mb-1.5" for="site_founded">Founded Year</label>
        <input id="site_founded" type="text" name="site_founded" value="<?= e($settingsMap['site_founded'] ?? '2025') ?>"
               class="w-full px-4 py-2.5 rounded-xl text-[14px] text-white focus:outline-none"
               style="background:rgba(255,255,255,0.06);border:1px solid rgba(255,255,255,0.12);">
      </div>
    </div>

    <div>
      <label class="block text-[13px] font-medium text-white/70 mb-1.5" for="site_address">Office Address</label>
      <textarea id="site_address" name="site_address" rows="2"
                class="w-full px-4 py-2.5 rounded-xl text-[14px] text-white focus:outline-none"
                style="background:rgba(255,255,255,0.06);border:1px solid rgba(255,255,255,0.12);"><?= e($settingsMap['site_address'] ?? '') ?></textarea>
    </div>

    <div>
      <label class="block text-[13px] font-medium text-white/70 mb-1.5" for="payment_instructions">Payment Instructions (bKash/Nagad/Bank details)</label>
      <textarea id="payment_instructions" name="payment_instructions" rows="3"
                placeholder="Enter details showing how users can pay for their memberships..."
                class="w-full px-4 py-2.5 rounded-xl text-[14px] text-white focus:outline-none"
                style="background:rgba(255,255,255,0.06);border:1px solid rgba(255,255,255,0.12);"><?= e($settingsMap['payment_instructions'] ?? '') ?></textarea>
    <!-- Public Directory Membership Policy -->
    <div class="p-5 rounded-2xl border" style="background:rgba(255,255,255,0.03); border-color:rgba(255,255,255,0.08);">
      <div class="flex items-start gap-3 mb-3">
        <div class="w-9 h-9 rounded-xl flex items-center justify-center text-[16px] shrink-0" style="background:rgba(47,136,99,0.15); color:#4E9C81;">
          <i class="fa-solid fa-address-book"></i>
        </div>
        <div>
          <h4 class="font-semibold text-[15px] text-white">পাবলিক ডিরেক্টরিতে মেম্বার প্রদর্শনী নীতিমালা (Directory Visibility Policy)</h4>
          <p class="text-[12.5px] text-white/60 mt-0.5">
            পেইড / সক্রিয় মেম্বারশিপ গ্রহণ করা ছাড়া সাধারণ অনুমোদিত অ্যালামনাই সদস্যদের পাবলিক ডিরেক্টরিতে দেখানো হবে কি না তা নির্ধারণ করুন।
          </p>
        </div>
      </div>

      <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 pt-2">
        <label class="flex items-center gap-3 p-3.5 rounded-xl cursor-pointer transition-all border <?= ($settingsMap['directory_require_membership'] ?? '0') === '0' ? 'bg-emerald-500/10 border-emerald-500/30' : 'bg-white/5 border-white/10 hover:bg-white/[0.07]' ?>">
          <input type="radio" name="directory_require_membership" value="0" <?= ($settingsMap['directory_require_membership'] ?? '0') === '0' ? 'checked' : '' ?> class="text-emerald-500 focus:ring-0">
          <div>
            <div class="font-semibold text-[13px] text-white">সকলকে দেখাবে (Show All Approved)</div>
            <div class="text-[11.5px] text-white/50">মেম্বারশিপ না থাকলেও সকল অনুমোদিত সদস্যকে পাবলিক ডিরেক্টরিতে দেখাবে।</div>
          </div>
        </label>

        <label class="flex items-center gap-3 p-3.5 rounded-xl cursor-pointer transition-all border <?= ($settingsMap['directory_require_membership'] ?? '0') === '1' ? 'bg-rose-500/10 border-rose-500/30' : 'bg-white/5 border-white/10 hover:bg-white/[0.07]' ?>">
          <input type="radio" name="directory_require_membership" value="1" <?= ($settingsMap['directory_require_membership'] ?? '0') === '1' ? 'checked' : '' ?> class="text-rose-500 focus:ring-0">
          <div>
            <div class="font-semibold text-[13px] text-white">শুধুমাত্র মেম্বারদের দেখাবে (Members Only)</div>
            <div class="text-[11.5px] text-white/50">শুধুমাত্র সক্রিয় ও ফি পরিশোধিত মেম্বারশিপ থাকা সদস্যদের ডিরেক্টরিতে দেখাবে।</div>
          </div>
        </label>
      </div>
    </div>

    <!-- SMTP Configuration Section -->
    <div class="pt-6 border-t border-white/5 space-y-4" x-data="{
        testingSmtp: false,
        smtpResult: null,
        testRecipient: '<?= e(\Illuminate\Support\Facades\Auth::user()?->email ?? ($user['email'] ?? '')) ?>',
        async testSmtpConnection() {
            if (!this.testRecipient || !this.testRecipient.includes('@')) {
                alert('অনুগ্রহ করে একটি সঠিক টেস্ট প্রাপক ইমেইল এড্রেস প্রদান করুন।');
                return;
            }
            this.testingSmtp = true;
            this.smtpResult = null;
            try {
                const res = await fetch('<?= url('/admin/settings/smtp/test') ?>', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '<?= csrf_token() ?>',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        test_to: this.testRecipient,
                        mail_host: document.getElementById('mail_host')?.value || '',
                        mail_port: document.getElementById('mail_port')?.value || '',
                        mail_encryption: document.getElementById('mail_encryption')?.value || '',
                        mail_username: document.getElementById('mail_username')?.value || '',
                        mail_password: document.getElementById('mail_password')?.value || '',
                        mail_from_address: document.getElementById('mail_from_address')?.value || '',
                        mail_from_name: document.getElementById('mail_from_name')?.value || ''
                    })
                });
                const data = await res.json();
                this.smtpResult = data;
            } catch (err) {
                this.smtpResult = { success: false, message: 'কানেকশন টেস্টে সমস্যা হয়েছে: ' + err.message };
            } finally {
                this.testingSmtp = false;
            }
        }
    }">
      <div class="flex items-center justify-between flex-wrap gap-2">
        <div>
          <h3 class="text-[16px] font-semibold text-[#E58E97] font-serif">SMTP & Email Configuration</h3>
          <p class="text-[12px] text-white/50">Configure the mail server settings used for sending notifications, invoices and password resets.</p>
        </div>
      </div>
      
      <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div>
          <label class="block text-[13px] font-medium text-white/70 mb-1.5" for="mail_host">SMTP Host</label>
          <input id="mail_host" type="text" name="mail_host" value="<?= e($settingsMap['mail_host'] ?? '') ?>" placeholder="smtp.mailtrap.io"
                 class="w-full px-4 py-2.5 rounded-xl text-[14px] text-white focus:outline-none"
                 style="background:rgba(255,255,255,0.06);border:1px solid rgba(255,255,255,0.12);">
        </div>
        <div>
          <label class="block text-[13px] font-medium text-white/70 mb-1.5" for="mail_port">SMTP Port</label>
          <input id="mail_port" type="text" name="mail_port" value="<?= e($settingsMap['mail_port'] ?? '') ?>" placeholder="587"
                 class="w-full px-4 py-2.5 rounded-xl text-[14px] text-white focus:outline-none"
                 style="background:rgba(255,255,255,0.06);border:1px solid rgba(255,255,255,0.12);">
        </div>
        <div>
          <label class="block text-[13px] font-medium text-white/70 mb-1.5" for="mail_encryption">Encryption</label>
          <select id="mail_encryption" name="mail_encryption"
                  class="w-full px-4 py-2.5 rounded-xl text-[14px] text-white focus:outline-none"
                  style="background:rgba(255,255,255,0.06);border:1px solid rgba(255,255,255,0.12);">
            <option value="none" <?= ($settingsMap['mail_encryption'] ?? '') === 'none' ? 'selected' : '' ?>>None</option>
            <option value="tls" <?= ($settingsMap['mail_encryption'] ?? '') === 'tls' ? 'selected' : '' ?>>TLS (Recommended)</option>
            <option value="ssl" <?= ($settingsMap['mail_encryption'] ?? '') === 'ssl' ? 'selected' : '' ?>>SSL</option>
          </select>
        </div>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
          <label class="block text-[13px] font-medium text-white/70 mb-1.5" for="mail_username">SMTP Username</label>
          <input id="mail_username" type="text" name="mail_username" value="<?= e($settingsMap['mail_username'] ?? '') ?>" placeholder="username"
                 class="w-full px-4 py-2.5 rounded-xl text-[14px] text-white focus:outline-none"
                 style="background:rgba(255,255,255,0.06);border:1px solid rgba(255,255,255,0.12);">
        </div>
        <div>
          <label class="block text-[13px] font-medium text-white/70 mb-1.5" for="mail_password">SMTP Password</label>
          <input id="mail_password" type="password" name="mail_password" value="<?= e($settingsMap['mail_password'] ?? '') ?>" placeholder="••••••••"
                 class="w-full px-4 py-2.5 rounded-xl text-[14px] text-white focus:outline-none"
                 style="background:rgba(255,255,255,0.06);border:1px solid rgba(255,255,255,0.12);">
        </div>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
          <label class="block text-[13px] font-medium text-white/70 mb-1.5" for="mail_from_address">From Email Address</label>
          <input id="mail_from_address" type="email" name="mail_from_address" value="<?= e($settingsMap['mail_from_address'] ?? '') ?>" placeholder="noreply@iphalumni.org"
                 class="w-full px-4 py-2.5 rounded-xl text-[14px] text-white focus:outline-none"
                 style="background:rgba(255,255,255,0.06);border:1px solid rgba(255,255,255,0.12);">
        </div>
        <div>
          <label class="block text-[13px] font-medium text-white/70 mb-1.5" for="mail_from_name">From Sender Name</label>
          <input id="mail_from_name" type="text" name="mail_from_name" value="<?= e($settingsMap['mail_from_name'] ?? '') ?>" placeholder="IPH Alumni Association"
                 class="w-full px-4 py-2.5 rounded-xl text-[14px] text-white focus:outline-none"
                 style="background:rgba(255,255,255,0.06);border:1px solid rgba(255,255,255,0.12);">
        </div>
      </div>

      <!-- Test SMTP Connection Live Dispatcher -->
      <div class="p-4 rounded-2xl bg-white/[0.03] border border-white/[0.08] space-y-3 mt-2">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
          <div>
            <h4 class="text-[13.5px] font-semibold text-white/90">🧪 Test SMTP Email Delivery</h4>
            <p class="text-[11.5px] text-white/50">আপনার কনফিগার করা SMTP সার্ভারের মাধ্যমে একটি লাইভ টেস্ট ইমেইল পাঠিয়ে যাচাই করুন।</p>
          </div>

          <div class="flex items-center gap-2 flex-wrap">
            <input type="email" x-model="testRecipient" placeholder="আপনার টেস্ট ইমেইল..."
                   class="px-3 py-1.5 rounded-xl text-[12.5px] text-white placeholder-white/40 focus:outline-none bg-black/30 border border-white/10 w-48 sm:w-56">
            <button type="button" @click="testSmtpConnection()" :disabled="testingSmtp"
                    class="px-4 py-1.5 rounded-xl text-[12.5px] font-semibold text-white transition-all flex items-center gap-1.5 whitespace-nowrap"
                    style="background:linear-gradient(135deg,#0284c7,#0369a1);">
              <span x-show="!testingSmtp">📨 Send Test Email</span>
              <span x-show="testingSmtp" style="display:none;" class="flex items-center gap-1.5">
                <svg class="animate-spin h-3.5 w-3.5 text-white" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path></svg>
                Connecting...
              </span>
            </button>
          </div>
        </div>

        <!-- SMTP Test Result Feedback Alert -->
        <div x-show="smtpResult !== null" style="display:none;" x-transition class="mt-2">
          <div :class="smtpResult?.success ? 'bg-emerald-500/15 border-emerald-500/30 text-emerald-300' : 'bg-rose-500/15 border-rose-500/30 text-rose-300'"
               class="p-3.5 rounded-xl border text-[13px] leading-relaxed flex items-start justify-between gap-2.5">
            <div class="flex items-start gap-2">
              <span class="text-base" x-text="smtpResult?.success ? '✅' : '❌'"></span>
              <p class="font-medium" x-text="smtpResult?.message"></p>
            </div>
            <button type="button" @click="smtpResult = null" class="text-white/60 hover:text-white font-bold">&times;</button>
          </div>
        </div>
      </div>
    </div>

    <!-- UddoktaPay Payment Gateway Configuration Section -->
    <div class="pt-6 border-t border-white/5 space-y-4" x-data="{
        testing: false,
        testResult: null,
        mode: '<?= e($settingsMap['uddoktapay_mode'] ?? 'sandbox') ?>',
        apiUrl: '<?= e($settingsMap['uddoktapay_api_url'] ?? 'https://sandbox.uddoktapay.com/api') ?>',
        apiKey: '<?= e($settingsMap['uddoktapay_api_key'] ?? '') ?>',
        showKey: false,
        updateDefaultUrl() {
            if (this.mode === 'live' && (this.apiUrl === '' || this.apiUrl.includes('sandbox.uddoktapay.com'))) {
                this.apiUrl = 'https://pay.uddoktapay.com/api';
            } else if (this.mode === 'sandbox' && (this.apiUrl === '' || this.apiUrl.includes('pay.uddoktapay.com'))) {
                this.apiUrl = 'https://sandbox.uddoktapay.com/api';
            }
        },
        async testConnection() {
            if (!this.apiKey) {
                alert('অনুগ্রহ করে প্রথমে UddoktaPay API Key প্রদান করুন।');
                return;
            }
            this.testing = true;
            this.testResult = null;
            try {
                const res = await fetch('<?= url('/admin/settings/uddoktapay/test') ?>', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '<?= csrf_token() ?>',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        uddoktapay_api_url: this.apiUrl,
                        uddoktapay_api_key: this.apiKey,
                        uddoktapay_mode: this.mode
                    })
                });
                const data = await res.json();
                this.testResult = data;
            } catch (err) {
                this.testResult = { success: false, message: 'কানেকশন টেস্টে সমস্যা হয়েছে: ' + err.message };
            } finally {
                this.testing = false;
            }
        }
    }">
      <div class="flex items-center justify-between flex-wrap gap-2">
        <div>
          <h3 class="text-[16px] font-semibold text-[#E58E97] font-serif flex items-center gap-2">
            <span>💳 UddoktaPay Payment Gateway Configuration</span>
            <span class="text-[11px] px-2 py-0.5 rounded-full bg-emerald-500/20 text-emerald-300 font-mono">v2 API</span>
          </h3>
          <p class="text-[12px] text-white/50 mt-1">Configure automated online payments for membership (bKash, Nagad, Rocket, Cards, etc.)</p>
        </div>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div>
          <label class="block text-[13px] font-medium text-white/70 mb-1.5" for="uddoktapay_mode">Gateway Environment</label>
          <select id="uddoktapay_mode" name="uddoktapay_mode" x-model="mode" @change="updateDefaultUrl()"
                  class="w-full px-4 py-2.5 rounded-xl text-[14px] text-white focus:outline-none"
                  style="background:rgba(255,255,255,0.06);border:1px solid rgba(255,255,255,0.12);">
            <option value="sandbox">Sandbox (Testing / Demo)</option>
            <option value="live">Live (Production / Real Payments)</option>
          </select>
        </div>

        <div class="md:col-span-2">
          <label class="block text-[13px] font-medium text-white/70 mb-1.5" for="uddoktapay_api_url">
            Base API URL
            <span class="text-[11px] text-white/40 font-normal ml-1">(Sandbox: https://sandbox.uddoktapay.com/api | Live: https://pay.uddoktapay.com/api)</span>
          </label>
          <input id="uddoktapay_api_url" type="text" name="uddoktapay_api_url" x-model="apiUrl"
                 placeholder="https://sandbox.uddoktapay.com/api"
                 class="w-full px-4 py-2.5 rounded-xl text-[14px] text-white focus:outline-none"
                 style="background:rgba(255,255,255,0.06);border:1px solid rgba(255,255,255,0.12);">
        </div>
      </div>

      <div>
        <label class="block text-[13px] font-medium text-white/70 mb-1.5" for="uddoktapay_api_key">
          UddoktaPay API Key (RT-UDDOKTAPAY-API-KEY)
        </label>
        <div class="relative">
          <input id="uddoktapay_api_key" :type="showKey ? 'text' : 'password'" name="uddoktapay_api_key" x-model="apiKey"
                 placeholder="Enter your UddoktaPay API Key from merchant dashboard"
                 class="w-full px-4 py-2.5 pr-20 rounded-xl text-[14px] text-white focus:outline-none font-mono"
                 style="background:rgba(255,255,255,0.06);border:1px solid rgba(255,255,255,0.12);">
          <button type="button" @click="showKey = !showKey" class="absolute right-3 top-2.5 text-xs text-white/60 hover:text-white">
            <span x-text="showKey ? 'Hide' : 'Show'"></span>
          </button>
        </div>
      </div>

      <!-- Live Test Connection & Feedback Banner -->
      <div class="flex items-center gap-3 pt-2">
        <button type="button" @click="testConnection()" :disabled="testing"
                class="px-4 py-2 rounded-xl text-[13px] font-semibold text-white transition-all flex items-center gap-2"
                style="background:linear-gradient(135deg,#0284c7,#0369a1);">
          <span x-show="!testing">🧪 Test Connection (কানেকশন টেস্ট করুন)</span>
          <span x-show="testing" style="display:none;" class="flex items-center gap-2">
            <svg class="animate-spin h-4 w-4 text-white" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path></svg>
            Testing Connection...
          </span>
        </button>
      </div>

      <!-- Test Result Alert -->
      <div x-show="testResult !== null" style="display:none;" class="mt-2" x-transition>
        <div :class="testResult?.success ? 'bg-emerald-500/15 border-emerald-500/30 text-emerald-300' : 'bg-rose-500/15 border-rose-500/30 text-rose-300'"
             class="p-4 rounded-xl border text-[13px] leading-relaxed flex items-start gap-2.5">
          <span class="text-lg" x-text="testResult?.success ? '✅' : '❌'"></span>
          <div class="flex-1">
            <p class="font-medium" x-text="testResult?.message"></p>
          </div>
        </div>
      </div>

      <!-- Gateway Webhook & Callback URLs Info Card -->
      <div class="p-4 rounded-2xl bg-white/[0.03] border border-white/[0.06] space-y-2 mt-3">
        <h4 class="text-[13px] font-medium text-white/80">📋 UddoktaPay Merchant Panel URLs (Copy & Paste):</h4>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-3 text-[12px]">
          <div>
            <span class="text-white/50 block">Webhook (IPN) URL:</span>
            <div class="flex items-center gap-2 mt-0.5">
              <code class="px-2 py-1 bg-black/40 text-emerald-400 rounded text-[11.5px] select-all flex-1"><?= url('/webhook/uddoktapay') ?></code>
            </div>
          </div>
          <div>
            <span class="text-white/50 block">Redirect (Success) URL:</span>
            <div class="flex items-center gap-2 mt-0.5">
              <code class="px-2 py-1 bg-black/40 text-amber-300 rounded text-[11.5px] select-all flex-1"><?= url('/portal/membership/payment/uddoktapay/success') ?></code>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="pt-4">
      <button type="submit" class="px-6 py-2.5 rounded-xl text-[14px] font-semibold text-white"
              style="background:linear-gradient(135deg,#A22638,#800020);">Save Settings</button>
    </div>
  </form>
</div>
