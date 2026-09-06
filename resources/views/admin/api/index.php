<?php
/**
 * Admin Mobile API Documentation & Interactive Testing Hub
 * Variables: $endpoints, $stats, $recentTokens, $tokenString
 */
?>
<div class="max-w-7xl mx-auto py-6 font-['Kalpurush','Inter',sans-serif]" 
     x-data="{ 
        activeCategory: 'All',
        testMethod: 'GET',
        testUrl: '<?= url('/api/v1/config') ?>',
        testToken: '<?= e($tokenString ?? '') ?>',
        testBody: '',
        testLoading: false,
        testResponse: null,
        testStatus: null,
        testTime: null,
        generatedToken: '<?= e($tokenString ?? '') ?>',
        tokenModal: false,
        async sendTest() {
            this.testLoading = true;
            this.testResponse = null;
            this.testStatus = null;
            const startTime = performance.now();
            try {
                const headers = { 'Accept': 'application/json' };
                if (this.testToken.trim()) {
                    headers['Authorization'] = 'Bearer ' + this.testToken.trim();
                }
                const options = {
                    method: this.testMethod,
                    headers: headers
                };
                if (this.testMethod === 'POST' && this.testBody.trim()) {
                    headers['Content-Type'] = 'application/json';
                    options.body = this.testBody.trim();
                }
                const res = await fetch(this.testUrl, options);
                this.testStatus = res.status + ' ' + res.statusText;
                const endTime = performance.now();
                this.testTime = Math.round(endTime - startTime) + ' ms';
                const json = await res.json();
                this.testResponse = JSON.stringify(json, null, 2);
            } catch (err) {
                const endTime = performance.now();
                this.testTime = Math.round(endTime - startTime) + ' ms';
                this.testStatus = 'Network / Request Error';
                this.testResponse = err.toString();
            } finally {
                this.testLoading = false;
            }
        },
        loadEndpoint(method, path, body, reqAuth) {
            this.testMethod = method;
            this.testUrl = '<?= url('/') ?>' + path.split('?')[0];
            if (path.includes('?')) {
                this.testUrl += '?' + path.split('?')[1];
            }
            this.testBody = body ? JSON.stringify(body, null, 2) : '';
            if (reqAuth && !this.testToken && this.generatedToken) {
                this.testToken = this.generatedToken;
            }
            document.getElementById('api-console-section').scrollIntoView({ behavior: 'smooth' });
        },
        async createAdminToken() {
            try {
                const res = await fetch('<?= url('/admin/mobile-api/generate-token') ?>', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '<?= csrf_token() ?>',
                        'Accept': 'application/json'
                    }
                });
                const data = await res.json();
                if (data.success) {
                    this.generatedToken = data.access_token;
                    this.testToken = data.access_token;
                    this.tokenModal = true;
                } else {
                    alert('Token creation failed: ' + (data.message || 'Unknown error'));
                }
            } catch (e) {
                alert('Request error: ' + e);
            }
        },
        copyText(txt, alertMsg) {
            navigator.clipboard.writeText(txt);
            if (alertMsg) alert(alertMsg);
        }
     }">

  <!-- Header & Breadcrumbs -->
  <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-6">
    <div>
      <div class="flex items-center gap-2 mb-1.5">
        <a href="<?= url('/admin/dashboard') ?>" class="text-[12px] font-mono text-white/50 hover:text-white transition-colors">
          <i class="fa-solid fa-arrow-left mr-1"></i> Dashboard
        </a>
        <span class="text-white/30 text-[10px]">/</span>
        <span class="text-[11px] font-mono font-bold text-[#E58E97] uppercase tracking-wider">
          DEVELOPER & MOBILE SUITE
        </span>
      </div>
      <h1 class="font-serif text-[26px] font-bold text-white tracking-tight flex items-center gap-3">
        <i class="fa-solid fa-mobile-screen-button text-[#E58E97]"></i>
        <?= __('মোবাইল অ্যাপ ও রেস্ট এপিআই হাব', 'Mobile App & REST API Hub') ?>
        <span class="text-[11px] font-mono px-2.5 py-1 rounded-full bg-emerald-500/10 text-emerald-400 border border-emerald-500/20 font-semibold tracking-wide">
          v1.0 Operational
        </span>
      </h1>
      <p class="text-[13px] text-white/60 mt-1">
        <?= __('অ্যান্ড্রয়েড ও আইওএস মোবাইল অ্যাপের জন্য সুরক্ষিত RESTful এন্ডপয়েন্টস, গেটপাস কিউআর স্ক্যানার ও লাইভ টেস্টিং কনসোল।', 'Secure RESTful endpoints, Gate Pass QR attendance verification, and interactive testing console for Android & iOS apps.') ?>
      </p>
    </div>

    <!-- Quick Token Actions -->
    <div class="flex items-center gap-2 flex-wrap">
      <button @click="createAdminToken()" 
              class="px-4 py-2 rounded-xl text-[13px] font-semibold text-white bg-gradient-to-r from-[#800020] to-[#A22638] hover:from-[#960026] hover:to-[#b82b40] shadow-md border border-[#E58E97]/30 flex items-center gap-2 transition-all transform active:scale-95">
        <i class="fa-solid fa-key text-amber-300"></i>
        <span>Generate Admin Bearer Token</span>
      </button>
      <a href="#api-console-section" 
         class="px-4 py-2 rounded-xl text-[13px] font-semibold text-white bg-white/10 hover:bg-white/15 border border-white/15 flex items-center gap-2 transition-colors">
        <i class="fa-solid fa-terminal text-emerald-400"></i>
        <span>Open Test Console</span>
      </a>
    </div>
  </div>

  <!-- Metric Overview Cards -->
  <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
    <div class="p-4 rounded-2xl bg-white/[0.03] border border-white/10 relative overflow-hidden group hover:border-[#E58E97]/40 transition-colors">
      <div class="flex items-center justify-between">
        <span class="text-[12px] font-medium text-white/60">Total REST Endpoints</span>
        <div class="w-8 h-8 rounded-lg bg-blue-500/10 text-blue-400 flex items-center justify-center text-[13px] border border-blue-500/20">
          <i class="fa-solid fa-network-wired"></i>
        </div>
      </div>
      <div class="text-[26px] font-bold text-white mt-2 font-mono"><?= $stats['total_endpoints'] ?></div>
      <div class="text-[11px] text-white/40 mt-0.5">Fully documented & tested</div>
    </div>

    <div class="p-4 rounded-2xl bg-white/[0.03] border border-white/10 relative overflow-hidden group hover:border-[#E58E97]/40 transition-colors">
      <div class="flex items-center justify-between">
        <span class="text-[12px] font-medium text-white/60">Token Protected (Auth)</span>
        <div class="w-8 h-8 rounded-lg bg-amber-500/10 text-amber-400 flex items-center justify-center text-[13px] border border-amber-500/20">
          <i class="fa-solid fa-shield-halved"></i>
        </div>
      </div>
      <div class="text-[26px] font-bold text-white mt-2 font-mono"><?= $stats['auth_endpoints'] ?></div>
      <div class="text-[11px] text-white/40 mt-0.5">Bearer Token validation</div>
    </div>

    <div class="p-4 rounded-2xl bg-white/[0.03] border border-white/10 relative overflow-hidden group hover:border-[#E58E97]/40 transition-colors">
      <div class="flex items-center justify-between">
        <span class="text-[12px] font-medium text-white/60">Public Endpoints</span>
        <div class="w-8 h-8 rounded-lg bg-emerald-500/10 text-emerald-400 flex items-center justify-center text-[13px] border border-emerald-500/20">
          <i class="fa-solid fa-globe"></i>
        </div>
      </div>
      <div class="text-[26px] font-bold text-white mt-2 font-mono"><?= $stats['public_endpoints'] ?></div>
      <div class="text-[11px] text-white/40 mt-0.5">Directory, Notices, Events</div>
    </div>

    <div class="p-4 rounded-2xl bg-white/[0.03] border border-white/10 relative overflow-hidden group hover:border-[#E58E97]/40 transition-colors">
      <div class="flex items-center justify-between">
        <span class="text-[12px] font-medium text-white/60">Active App Sessions</span>
        <div class="w-8 h-8 rounded-lg bg-purple-500/10 text-purple-400 flex items-center justify-center text-[13px] border border-purple-500/20">
          <i class="fa-solid fa-mobile"></i>
        </div>
      </div>
      <div class="text-[26px] font-bold text-white mt-2 font-mono"><?= $stats['active_tokens'] ?></div>
      <div class="text-[11px] text-white/40 mt-0.5">Valid client device tokens</div>
    </div>
  </div>

  <?php if (session('success')): ?>
  <div class="mb-6 p-4 rounded-2xl bg-emerald-500/10 border border-emerald-500/30 text-emerald-300 flex items-center justify-between shadow-lg">
    <div class="flex items-center gap-3">
      <i class="fa-solid fa-circle-check text-lg"></i>
      <span class="text-sm font-semibold"><?= e(session('success')) ?></span>
    </div>
    <span class="text-xs font-mono opacity-60">Auto-saved</span>
  </div>
  <?php endif; ?>

  <!-- Mobile App Store Links & Public Website CTA Settings -->
  <div class="p-6 rounded-2xl bg-gradient-to-br from-[#1e232d] to-[#12161f] border border-[#E58E97]/30 shadow-2xl mb-8 relative overflow-hidden">
    <div class="absolute -right-16 -top-16 w-56 h-56 bg-[#800020]/20 rounded-full blur-3xl pointer-events-none"></div>
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 pb-5 border-b border-white/10">
      <div>
        <div class="flex items-center gap-2">
          <span class="w-2.5 h-2.5 rounded-full bg-[#E58E97]"></span>
          <h2 class="text-[17px] font-bold text-white flex items-center gap-2">
            <i class="fa-brands fa-google-play text-emerald-400"></i>
            <?= __('মোবাইল অ্যাপ ডাউনলোড লিংক ও পাবলিক সাইট CTA সেটিংস', 'Mobile App Download Links & Public Site CTA Settings') ?>
          </h2>
        </div>
        <p class="text-[12.5px] text-white/60 mt-1">
          <?= __('এখানে গুগল প্লে-স্টোর বা অ্যাপ স্টোরের লিংক প্রদান করুন। লিংক প্রদান করা থাকলে পাবলিক ওয়েবসাইটের হোমপেজ ও ফুটারে ডাউনলোড ব্যাজ ও ইন্টারেক্টিভ CTA সেকশন প্রদর্শিত হবে।', 'Provide your Google Play Store or App Store links here. When saved, interactive download buttons and banners will automatically appear on the public website.') ?>
        </p>
      </div>
      <div class="flex items-center gap-2">
        <span class="px-3 py-1 rounded-full text-[11px] font-mono <?= !empty($appLinks['google_play_url']) ? 'bg-emerald-500/20 text-emerald-300 border border-emerald-500/30' : 'bg-amber-500/20 text-amber-300 border border-amber-500/30' ?>">
          <i class="fa-solid <?= !empty($appLinks['google_play_url']) ? 'fa-circle-check' : 'fa-triangle-exclamation' ?> mr-1"></i>
          <?= !empty($appLinks['google_play_url']) ? 'Play Store Active' : 'Play Store Not Set' ?>
        </span>
      </div>
    </div>

    <!-- Form for Updating Links -->
    <form action="<?= url('/admin/mobile-api/update-links') ?>" method="POST" class="mt-6">
      <?= csrf_field() ?>
      <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
        <!-- Google Play Store URL -->
        <div class="space-y-1.5">
          <label class="block text-[13px] font-medium text-white/90 flex items-center justify-between">
            <span class="flex items-center gap-2">
              <i class="fa-brands fa-google-play text-emerald-400"></i>
              <span><?= __('গুগল প্লে-স্টোর লিংক (Google Play Store URL)', 'Google Play Store URL') ?></span>
              <span class="text-rose-400 font-bold">*</span>
            </span>
            <?php if (!empty($appLinks['google_play_url'])): ?>
            <a href="<?= e($appLinks['google_play_url']) ?>" target="_blank" rel="noopener noreferrer" class="text-[11px] text-emerald-400 hover:underline flex items-center gap-1 font-mono">
              <i class="fa-solid fa-arrow-up-right-from-square text-[10px]"></i> টেস্ট করুন
            </a>
            <?php endif; ?>
          </label>
          <div class="relative">
            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-white/40">
              <i class="fa-brands fa-android text-[15px]"></i>
            </div>
            <input type="url" 
                   name="app_google_play_url" 
                   value="<?= e($appLinks['google_play_url'] ?? '') ?>" 
                   placeholder="https://play.google.com/store/apps/details?id=com.iphalumni.app"
                   class="w-full pl-10 pr-4 py-2.5 rounded-xl bg-black/40 border border-white/15 text-white text-[13.5px] focus:border-[#E58E97] focus:ring-1 focus:ring-[#E58E97] outline-none transition-all placeholder:text-white/30 font-mono">
          </div>
          <p class="text-[11px] text-white/40">
            উদাহরণ: <code class="text-white/60">https://play.google.com/store/apps/details?id=com.iphalumni.app</code>
          </p>
        </div>

        <!-- Apple App Store URL -->
        <div class="space-y-1.5">
          <label class="block text-[13px] font-medium text-white/90 flex items-center justify-between">
            <span class="flex items-center gap-2">
              <i class="fa-brands fa-apple text-slate-300"></i>
              <span><?= __('অ্যাপল অ্যাপ স্টোর লিংক (Apple App Store URL)', 'Apple App Store URL') ?></span>
              <span class="text-[11px] text-white/40 font-normal">(ঐচ্ছিক / Optional)</span>
            </span>
            <?php if (!empty($appLinks['apple_store_url'])): ?>
            <a href="<?= e($appLinks['apple_store_url']) ?>" target="_blank" rel="noopener noreferrer" class="text-[11px] text-sky-400 hover:underline flex items-center gap-1 font-mono">
              <i class="fa-solid fa-arrow-up-right-from-square text-[10px]"></i> টেস্ট করুন
            </a>
            <?php endif; ?>
          </label>
          <div class="relative">
            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-white/40">
              <i class="fa-brands fa-apple text-[16px]"></i>
            </div>
            <input type="url" 
                   name="app_apple_store_url" 
                   value="<?= e($appLinks['apple_store_url'] ?? '') ?>" 
                   placeholder="https://apps.apple.com/app/iph-alumni/id123456789"
                   class="w-full pl-10 pr-4 py-2.5 rounded-xl bg-black/40 border border-white/15 text-white text-[13.5px] focus:border-[#E58E97] focus:ring-1 focus:ring-[#E58E97] outline-none transition-all placeholder:text-white/30 font-mono">
          </div>
          <p class="text-[11px] text-white/40">আইওএস বা আইফোন ব্যবহারকারীদের জন্য অ্যাপ স্টোর লিংক।</p>
        </div>

        <!-- Direct APK File URL -->
        <div class="space-y-1.5">
          <label class="block text-[13px] font-medium text-white/90 flex items-center gap-2">
            <i class="fa-solid fa-file-arrow-down text-amber-400"></i>
            <span><?= __('সরাসরি APK ফাইল ডাউনলোড লিংক (Direct APK URL)', 'Direct APK Download URL') ?></span>
            <span class="text-[11px] text-white/40 font-normal">(ঐচ্ছিক / Optional)</span>
          </label>
          <div class="relative">
            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-white/40">
              <i class="fa-solid fa-link text-[14px]"></i>
            </div>
            <input type="url" 
                   name="app_apk_url" 
                   value="<?= e($appLinks['apk_url'] ?? '') ?>" 
                   placeholder="https://iphalumni.org/downloads/app-release.apk"
                   class="w-full pl-10 pr-4 py-2.5 rounded-xl bg-black/40 border border-white/15 text-white text-[13.5px] focus:border-[#E58E97] focus:ring-1 focus:ring-[#E58E97] outline-none transition-all placeholder:text-white/30 font-mono">
          </div>
          <p class="text-[11px] text-white/40">যাদের প্লে-স্টোর অ্যাকাউন্ট নেই তারা সরাসরি APK ডাউনলোড করতে পারবেন।</p>
        </div>

        <!-- App Version -->
        <div class="space-y-1.5">
          <label class="block text-[13px] font-medium text-white/90 flex items-center gap-2">
            <i class="fa-solid fa-code-branch text-purple-400"></i>
            <span><?= __('বর্তমান অ্যাপ সংস্করণ (Current App Version)', 'Current App Version') ?></span>
          </label>
          <div class="relative">
            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-white/40">
              <i class="fa-solid fa-tag text-[14px]"></i>
            </div>
            <input type="text" 
                   name="app_version_name" 
                   value="<?= e($appLinks['version_name'] ?? '1.0.0') ?>" 
                   placeholder="1.0.0"
                   class="w-full pl-10 pr-4 py-2.5 rounded-xl bg-black/40 border border-white/15 text-white text-[13.5px] focus:border-[#E58E97] focus:ring-1 focus:ring-[#E58E97] outline-none transition-all font-mono">
          </div>
          <p class="text-[11px] text-white/40">এপিআই কনফিগারে ক্লায়েন্ট ডিভাইসকে পাঠানো হবে।</p>
        </div>
      </div>

      <!-- Visibility Toggle & Submit Action -->
      <div class="mt-6 pt-5 border-t border-white/10 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <label class="flex items-center gap-3 cursor-pointer select-none">
          <input type="checkbox" 
                 name="app_cta_enabled" 
                 value="1" 
                 <?= (!empty($appLinks['cta_enabled'])) ? 'checked' : '' ?>
                 class="w-4 h-4 rounded text-[#800020] bg-black/40 border-white/30 focus:ring-[#E58E97] focus:ring-offset-0">
          <div>
            <span class="text-[13.5px] font-semibold text-white">
              <?= __('পাবলিক ওয়েবসাইটে অ্যাপ ডাউনলোড CTA ও ডাউনলোড বোতাম দেখান', 'Enable App Download CTA & Badges on Public Website') ?>
            </span>
            <p class="text-[11px] text-white/50">হোমপেজ এবং ফুটার সহ পাবলিক পেজে অ্যাপ স্টোর ডাউনলোড ব্যাজ ও অ্যানিমেটেড ব্যানার প্রদর্শন করা হবে।</p>
          </div>
        </label>

        <button type="submit" 
                class="px-6 py-2.5 rounded-xl text-[13.5px] font-bold text-white bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-500 hover:to-teal-500 shadow-lg shadow-emerald-900/30 flex items-center gap-2 transition-all transform active:scale-95 shrink-0">
          <i class="fa-solid fa-floppy-disk"></i>
          <span><?= __('সংরক্ষণ করুন (Save Links)', 'Save Links') ?></span>
        </button>
      </div>
    </form>
  </div>

  <!-- Interactive Test Console Section -->
  <div id="api-console-section" class="p-5 rounded-2xl bg-black/60 border border-[#E58E97]/30 shadow-2xl mb-8 relative overflow-hidden backdrop-blur-md">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-3 pb-4 border-b border-white/10">
      <div>
        <div class="flex items-center gap-2">
          <span class="w-2.5 h-2.5 rounded-full bg-emerald-400 animate-pulse"></span>
          <h2 class="text-[16px] font-bold text-white font-mono flex items-center gap-2">
            <i class="fa-solid fa-terminal text-emerald-400"></i>
            Live Interactive API Console
          </h2>
        </div>
        <p class="text-[12px] text-white/60 mt-0.5">
          Execute real HTTP calls against any endpoint directly from your browser.
        </p>
      </div>
      <div class="flex items-center gap-2 text-[11px] font-mono text-white/50">
        <span>Base Host:</span>
        <code class="px-2 py-1 rounded bg-white/10 text-[#E58E97]"><?= url('/api/v1') ?></code>
        <button @click="copyText('<?= url('/api/v1') ?>', 'Base URL copied!')" class="hover:text-white" title="Copy Base URL">
          <i class="fa-solid fa-copy"></i>
        </button>
      </div>
    </div>

    <!-- Request Builder Controls -->
    <div class="grid grid-cols-1 md:grid-cols-12 gap-3 mt-4">
      <div class="md:col-span-2">
        <label class="block text-[11px] font-mono text-white/50 mb-1">HTTP METHOD</label>
        <select x-model="testMethod" class="w-full px-3 py-2 rounded-xl bg-white/5 border border-white/15 text-white font-mono text-xs focus:outline-none focus:border-[#E58E97]">
          <option value="GET">GET</option>
          <option value="POST">POST</option>
        </select>
      </div>

      <div class="md:col-span-7">
        <label class="block text-[11px] font-mono text-white/50 mb-1">ENDPOINT URL</label>
        <div class="relative">
          <input type="text" x-model="testUrl" class="w-full px-3 py-2 rounded-xl bg-white/5 border border-white/15 text-white font-mono text-xs focus:outline-none focus:border-[#E58E97] pr-10">
          <button @click="copyText(testUrl, 'URL copied!')" class="absolute right-3 top-2 text-white/40 hover:text-white text-xs">
            <i class="fa-solid fa-copy"></i>
          </button>
        </div>
      </div>

      <div class="md:col-span-3 flex items-end">
        <button @click="sendTest()" 
                :disabled="testLoading"
                class="w-full py-2 px-4 rounded-xl text-xs font-bold text-white bg-emerald-600 hover:bg-emerald-500 active:bg-emerald-700 disabled:opacity-50 transition-colors flex items-center justify-center gap-2 shadow-lg">
          <i class="fa-solid" :class="testLoading ? 'fa-spinner fa-spin' : 'fa-paper-plane'"></i>
          <span x-text="testLoading ? 'Sending...' : 'Send Live Request'"></span>
        </button>
      </div>
    </div>

    <!-- Token Header Input & Body Input -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-3">
      <div>
        <div class="flex items-center justify-between mb-1">
          <label class="text-[11px] font-mono text-white/50">BEARER TOKEN (HEADER)</label>
          <button x-show="generatedToken" @click="testToken = generatedToken" class="text-[10.5px] font-mono text-amber-300 hover:underline">
            Use Admin Generated Token
          </button>
        </div>
        <input type="text" x-model="testToken" placeholder="Paste Bearer Token here..." class="w-full px-3 py-2 rounded-xl bg-white/5 border border-white/15 text-white font-mono text-xs focus:outline-none focus:border-[#E58E97]">
      </div>

      <div>
        <label class="block text-[11px] font-mono text-white/50 mb-1">REQUEST BODY (JSON - FOR POST)</label>
        <textarea x-model="testBody" rows="2" placeholder='{"email":"...","password":"..."}' class="w-full px-3 py-1.5 rounded-xl bg-white/5 border border-white/15 text-emerald-300 font-mono text-xs focus:outline-none focus:border-[#E58E97]"></textarea>
      </div>
    </div>

    <!-- Output Response Screen -->
    <div class="mt-4 pt-3 border-t border-white/10" x-show="testResponse !== null">
      <div class="flex items-center justify-between mb-2">
        <div class="flex items-center gap-2">
          <span class="text-[11px] font-mono font-bold uppercase text-white/60">HTTP Status:</span>
          <span class="px-2 py-0.5 rounded text-[11px] font-mono font-bold" 
                :class="testStatus.startsWith('2') ? 'bg-emerald-500/20 text-emerald-400 border border-emerald-500/30' : 'bg-red-500/20 text-red-400 border border-red-500/30'"
                x-text="testStatus"></span>
          <span class="text-[11px] font-mono text-white/40" x-text="'Latency: ' + testTime"></span>
        </div>
        <button @click="copyText(testResponse, 'Response JSON copied!')" class="text-[11px] font-mono text-white/50 hover:text-white flex items-center gap-1">
          <i class="fa-solid fa-copy"></i>
          <span>Copy Response</span>
        </button>
      </div>
      <pre class="max-h-72 overflow-y-auto p-4 rounded-xl bg-[#090d13] border border-white/10 text-emerald-400 font-mono text-[12px] leading-relaxed select-all" x-text="testResponse"></pre>
    </div>
  </div>

  <!-- Endpoints Explorer Section -->
  <div class="mb-6">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 mb-4">
      <h2 class="text-[18px] font-bold text-white flex items-center gap-2">
        <i class="fa-solid fa-layer-group text-[#E58E97]"></i>
        Available API Endpoints
      </h2>

      <!-- Category Filter Tabs -->
      <div class="flex items-center gap-1.5 flex-wrap bg-white/5 p-1 rounded-xl border border-white/10 text-[12px]">
        <?php 
          $categories = ['All', 'Authentication', 'Member & Digital Pass', 'Directory', 'Notices & Circulars', 'Events & Reunion', 'Innovative Mobile Features'];
          foreach ($categories as $cat): 
        ?>
          <button @click="activeCategory = '<?= $cat ?>'"
                  :class="activeCategory === '<?= $cat ?>' ? 'bg-[#800020] text-white shadow-sm' : 'text-white/60 hover:text-white'"
                  class="px-3 py-1 rounded-lg font-medium transition-all">
            <?= $cat ?>
          </button>
        <?php endforeach; ?>
      </div>
    </div>

    <!-- Endpoint Cards List -->
    <div class="space-y-3">
      <?php foreach ($endpoints as $idx => $ep): ?>
        <div x-show="activeCategory === 'All' || activeCategory === '<?= $ep['group'] ?>'"
             class="p-4 rounded-2xl bg-white/[0.02] border border-white/10 hover:border-white/20 transition-all">
          <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-3">
            <div class="flex items-start sm:items-center gap-3 min-w-0">
              <!-- Method Badge -->
              <span class="px-2.5 py-1 rounded-lg text-[11px] font-mono font-bold shrink-0 <?= $ep['method'] === 'POST' ? 'bg-blue-500/20 text-blue-400 border border-blue-500/30' : 'bg-emerald-500/20 text-emerald-400 border border-emerald-500/30' ?>">
                <?= $ep['method'] ?>
              </span>

              <!-- Endpoint Path -->
              <div class="min-w-0">
                <div class="flex items-center gap-2 flex-wrap">
                  <code class="text-[13.5px] font-mono font-semibold text-white tracking-wide"><?= $ep['path'] ?></code>
                  
                  <?php if ($ep['auth']): ?>
                    <span class="px-2 py-0.5 rounded text-[10px] font-mono bg-amber-500/10 text-amber-300 border border-amber-500/20">
                      <i class="fa-solid fa-lock text-[9px] mr-1"></i> Bearer Token Required
                    </span>
                  <?php else: ?>
                    <span class="px-2 py-0.5 rounded text-[10px] font-mono bg-emerald-500/10 text-emerald-400 border border-emerald-500/20">
                      <i class="fa-solid fa-earth-americas text-[9px] mr-1"></i> Public
                    </span>
                  <?php endif; ?>

                  <span class="px-2 py-0.5 rounded text-[10px] font-mono bg-white/5 text-white/50">
                    <?= $ep['group'] ?>
                  </span>
                </div>
                <div class="text-[12.5px] text-white/70 mt-1 font-medium"><?= $ep['title'] ?> — <span class="text-white/50"><?= $ep['description'] ?></span></div>
              </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex items-center gap-2 shrink-0 self-end lg:self-center">
              <button @click="copyText('<?= url($ep['path']) ?>', 'Full Endpoint URL copied!')" 
                      class="px-2.5 py-1.5 rounded-lg bg-white/5 hover:bg-white/10 text-white/60 hover:text-white text-[12px] border border-white/10 transition-colors" title="Copy Full URL">
                <i class="fa-solid fa-copy mr-1 text-[11px]"></i> Copy URL
              </button>

              <button @click='loadEndpoint(<?= json_encode($ep['method']) ?>, <?= json_encode($ep['path']) ?>, <?= json_encode($ep['request_body']) ?>, <?= $ep['auth'] ? 'true' : 'false' ?>)'
                      class="px-3 py-1.5 rounded-lg bg-emerald-500/15 hover:bg-emerald-500/25 text-emerald-300 text-[12px] font-medium border border-emerald-500/30 transition-colors flex items-center gap-1.5">
                <i class="fa-solid fa-play text-[10px]"></i>
                <span>Test in Console</span>
              </button>
            </div>
          </div>

          <!-- Collapsible Request / Response Details -->
          <div x-data="{ expanded: false }" class="mt-3 pt-2 border-t border-white/5">
            <button @click="expanded = !expanded" class="text-[11px] font-mono text-white/40 hover:text-white flex items-center gap-1.5 transition-colors">
              <i class="fa-solid" :class="expanded ? 'fa-chevron-down' : 'fa-chevron-right'"></i>
              <span x-text="expanded ? 'Hide Payload & Schema Details' : 'View Payload & Schema Details'"></span>
            </button>

            <div x-show="expanded" class="grid grid-cols-1 md:grid-cols-2 gap-3 mt-3" style="display: none;">
              <?php if (!empty($ep['request_body'])): ?>
                <div>
                  <div class="text-[10px] font-mono text-white/50 uppercase mb-1">Request Payload Example (JSON)</div>
                  <pre class="p-3 rounded-xl bg-black/80 border border-white/10 text-amber-300 font-mono text-[11px] overflow-x-auto"><?= json_encode($ep['request_body'], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) ?></pre>
                </div>
              <?php endif; ?>
              <div>
                <div class="text-[10px] font-mono text-white/50 uppercase mb-1">Standard Success Response Example (JSON)</div>
                <pre class="p-3 rounded-xl bg-black/80 border border-white/10 text-emerald-400 font-mono text-[11px] overflow-x-auto"><?= json_encode($ep['response'], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) ?></pre>
              </div>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>

  <!-- Active Mobile App Tokens Table -->
  <div class="p-5 rounded-2xl bg-white/[0.02] border border-white/10">
    <div class="flex items-center justify-between mb-4">
      <div>
        <h3 class="text-[16px] font-bold text-white flex items-center gap-2">
          <i class="fa-solid fa-key text-amber-300"></i>
          Active Mobile App & API Tokens
        </h3>
        <p class="text-[12px] text-white/50">List of recently issued Bearer tokens for mobile clients and administrative testing.</p>
      </div>
    </div>

    <div class="overflow-x-auto">
      <table class="w-full text-left border-collapse text-[12.5px]">
        <thead>
          <tr class="border-b border-white/10 text-white/40 font-mono text-[11px] uppercase">
            <th class="py-2.5 px-3">User / Account</th>
            <th class="py-2.5 px-3">Client / Name</th>
            <th class="py-2.5 px-3">Created</th>
            <th class="py-2.5 px-3">Last Active</th>
            <th class="py-2.5 px-3 text-right">Actions</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-white/5">
          <?php if ($recentTokens->isEmpty()): ?>
            <tr>
              <td colspan="5" class="py-6 text-center text-white/40 font-mono">
                No active tokens generated yet. Click "Generate Admin Bearer Token" above.
              </td>
            </tr>
          <?php else: ?>
            <?php foreach ($recentTokens as $rt): ?>
              <tr class="hover:bg-white/[0.02] transition-colors">
                <td class="py-3 px-3">
                  <div class="font-medium text-white"><?= e($rt->user_name) ?></div>
                  <div class="text-[11px] text-white/50"><?= e($rt->user_email) ?></div>
                </td>
                <td class="py-3 px-3 font-mono text-white/80">
                  <span class="px-2 py-0.5 rounded bg-white/5 border border-white/10 text-[11px]">
                    <?= e($rt->name) ?>
                  </span>
                  <?php if ($rt->device_name): ?>
                    <span class="text-[11px] text-white/40 ml-1">(<?= e($rt->device_name) ?>)</span>
                  <?php endif; ?>
                </td>
                <td class="py-3 px-3 font-mono text-white/60">
                  <?= date('d M Y, h:i A', strtotime($rt->created_at)) ?>
                </td>
                <td class="py-3 px-3 font-mono text-emerald-400">
                  <?= $rt->last_used_at ? date('d M Y, h:i A', strtotime($rt->last_used_at)) : 'Never' ?>
                </td>
                <td class="py-3 px-3 text-right">
                  <button @click="if (confirm('Revoke this token?')) { 
                    fetch('<?= url('/admin/mobile-api/revoke-token') ?>', {
                      method: 'POST',
                      headers: {'X-CSRF-TOKEN': '<?= csrf_token() ?>', 'Content-Type': 'application/json'},
                      body: JSON.stringify({ token_id: <?= $rt->id ?> })
                    }).then(() => location.reload());
                  }" class="px-2.5 py-1 rounded bg-red-500/10 text-red-400 hover:bg-red-500/20 border border-red-500/20 text-[11px] transition-colors">
                    Revoke
                  </button>
                </td>
              </tr>
            <?php endforeach; ?>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>

  <!-- Token Generated Modal -->
  <div x-show="tokenModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/80 backdrop-blur-sm" style="display: none;">
    <div class="w-full max-w-lg p-6 rounded-2xl bg-[#0f141c] border border-[#E58E97]/40 shadow-2xl" @click.away="tokenModal = false">
      <div class="flex items-center justify-between mb-4">
        <h3 class="text-[16px] font-bold text-white flex items-center gap-2">
          <i class="fa-solid fa-circle-check text-emerald-400"></i>
          Bearer Token Ready
        </h3>
        <button @click="tokenModal = false" class="text-white/40 hover:text-white"><i class="fa-solid fa-xmark"></i></button>
      </div>
      <p class="text-[13px] text-white/70 mb-3">
        Use this Bearer token in the <code class="text-amber-300">Authorization: Bearer &lt;token&gt;</code> header for testing in Postman or the built-in Console.
      </p>
      <div class="p-3 rounded-xl bg-black border border-white/10 font-mono text-[12px] text-emerald-400 break-all select-all mb-4" x-text="generatedToken"></div>
      <div class="flex justify-end gap-2">
        <button @click="copyText(generatedToken, 'Token copied to clipboard!')" class="px-4 py-2 rounded-xl text-xs font-bold text-white bg-emerald-600 hover:bg-emerald-500 flex items-center gap-1.5 shadow">
          <i class="fa-solid fa-copy"></i> Copy Token
        </button>
        <button @click="tokenModal = false" class="px-4 py-2 rounded-xl text-xs font-semibold text-white/60 hover:text-white bg-white/10">
          Close
        </button>
      </div>
    </div>
  </div>

</div>
