<?php
/**
 * Admin Email Templates Preview & Live Test Dispatcher View
 * Variables: $templates, $selectedKey, $activeTemplate, $renderedHtml
 */
?>
<!-- Hidden holder for raw HTML (Prevents unescaped quotes in DOM attributes) -->
<textarea id="raw_email_html" class="hidden" style="display:none;"><?= htmlspecialchars($renderedHtml, ENT_QUOTES, 'UTF-8') ?></textarea>

<div class="space-y-6 font-['Kalpurush']" x-data="{
    deviceMode: 'desktop',
    copySuccess: false,
    testEmail: localStorage.getItem('iph_test_email') || '',
    sending: false,
    sendResult: null,
    showModal: false,
    targetKey: '<?= e($selectedKey) ?>',
    targetName: '<?= e($activeTemplate['name']) ?>',

    copyHtml() {
        const raw = document.getElementById('raw_email_html').value;
        navigator.clipboard.writeText(raw);
        this.copySuccess = true;
        setTimeout(() => { this.copySuccess = false; }, 2500);
    },

    openTestModal(key, name) {
        this.targetKey = key;
        this.targetName = name;
        this.sendResult = null;
        this.showModal = true;
    },

    async sendTestMail(key = null) {
        const tKey = key || this.targetKey;
        if (!this.testEmail || !this.testEmail.includes('@')) {
            alert('অনুগ্রহ করে একটি সঠিক ইমেইল ঠিকানা প্রদান করুন।');
            return;
        }

        localStorage.setItem('iph_test_email', this.testEmail);
        this.sending = true;
        this.sendResult = null;

        try {
            const res = await fetch('<?= url('/admin/email-templates/send-test') ?>', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '<?= csrf_token() ?>',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    test_email: this.testEmail,
                    template_key: tKey
                })
            });
            const data = await res.json();
            this.sendResult = data;
        } catch (err) {
            this.sendResult = { success: false, message: 'নেটওয়ার্ক এরর: ' + err.message };
        } finally {
            this.sending = false;
        }
    }
}">
  <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
    <div>
      <h2 class="text-[20px] font-bold text-white flex items-center gap-2">
        <span>✉ System Event Email Templates</span>
        <span class="text-[11px] px-2.5 py-0.5 rounded-full bg-emerald-500/20 text-emerald-300 font-mono">Animated HTML5</span>
      </h2>
      <p class="text-[13.5px] text-white/60 mt-1">প্রতিটি টেমপ্লেটের জন্য আলাদাভাবে লাইভ টেস্ট ইমেইল পাঠানোর ও রিয়েল-টাইম প্রিভিউ সিস্টেম।</p>
    </div>
    
    <!-- Header Quick Test Bar -->
    <div class="flex items-center gap-2 bg-white/5 p-1.5 rounded-2xl border border-white/10">
      <input type="email" x-model="testEmail" placeholder="টেস্ট ইমেইল এড্রেস..."
             class="px-3 py-1.5 rounded-xl text-[12.5px] text-white placeholder-white/40 focus:outline-none bg-black/30 border border-white/10 w-44 md:w-56">
      <button type="button" @click="sendTestMail('<?= e($selectedKey) ?>')" :disabled="sending"
              class="px-3.5 py-1.5 rounded-xl text-[12px] font-semibold text-white transition-all whitespace-nowrap flex items-center gap-1.5"
              style="background:linear-gradient(135deg,#A22638,#800020);">
        <span x-show="!sending">🚀 Send This Template</span>
        <span x-show="sending" style="display:none;" class="flex items-center gap-1">
          <svg class="animate-spin h-3.5 w-3.5 text-white" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path></svg>
          Sending...
        </span>
      </button>
    </div>
  </div>

  <!-- Real-time Test Result Alert -->
  <div x-show="sendResult !== null" style="display:none;" x-transition class="mt-2">
    <div :class="sendResult?.success ? 'bg-emerald-500/20 border-emerald-500/40 text-emerald-300' : 'bg-rose-500/20 border-rose-500/40 text-rose-300'"
         class="p-4 rounded-2xl border text-[13.5px] flex items-center justify-between gap-3 shadow-lg">
      <div class="flex items-center gap-2.5">
        <span class="text-xl" x-text="sendResult?.success ? '🎉' : '⚠️'"></span>
        <div>
          <p class="font-bold" x-text="sendResult?.success ? 'ইমেইল সফলভাবে পাঠানো হয়েছে!' : 'ইমেইল পাঠানো যায়নি'"></p>
          <p class="text-[12.5px] opacity-90" x-text="sendResult?.message"></p>
        </div>
      </div>
      <button type="button" @click="sendResult = null" class="text-white/60 hover:text-white font-bold px-2 py-1">&times;</button>
    </div>
  </div>

  <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
    
    <!-- Left Navigation Menu (4 cols) -->
    <div class="lg:col-span-4 space-y-3">
      <div class="flex items-center justify-between px-1">
        <h3 class="font-bold text-white/70 text-[12px] uppercase font-mono tracking-wider">Select Event Template</h3>
        <span class="text-[11px] text-amber-400 font-mono"><?= count($templates) ?> Templates</span>
      </div>

      <div class="space-y-2.5 max-h-[750px] overflow-y-auto pr-1">
        <?php foreach ($templates as $key => $tpl): ?>
        <div class="p-3.5 rounded-2xl border transition-all <?= $selectedKey === $key ? 'bg-[#800020] text-white border-rose-400/40 shadow-lg' : 'bg-white/5 text-white/80 border-white/10 hover:bg-white/10' ?>">
          <div class="flex items-start justify-between gap-2">
            <a href="<?= url('/admin/email-templates?key=' . $key) ?>" class="flex-1 block">
              <span class="font-bold text-[13.5px] block leading-snug hover:underline"><?= e($tpl['name']) ?></span>
              <p class="text-[11.5px] opacity-75 line-clamp-2 mt-1"><?= e($tpl['trigger']) ?></p>
            </a>
          </div>
          
          <div class="flex items-center justify-between mt-3 pt-2.5 border-t border-white/10 text-[11px]">
            <a href="<?= url('/admin/email-templates?key=' . $key) ?>" class="text-white/80 hover:text-white font-semibold">
              👁️ Preview Template
            </a>
            <button type="button" @click="openTestModal('<?= $key ?>', '<?= addslashes($tpl['name']) ?>')"
                    class="px-2.5 py-1 rounded-lg bg-white/15 hover:bg-white/25 text-white font-semibold transition-all flex items-center gap-1">
              <span>✉️ Test Send</span>
            </button>
          </div>
        </div>
        <?php endforeach; ?>
      </div>
    </div>

    <!-- Right Live Email Render Area (8 cols) -->
    <div class="lg:col-span-8 space-y-4">
      <!-- Meta Card -->
      <div class="p-4 rounded-2xl bg-black/40 border border-white/10 flex flex-col md:flex-row md:items-center justify-between gap-3">
        <div class="space-y-1">
          <div class="flex items-center gap-2 text-[12px] text-white/70">
            <span class="font-mono font-bold text-amber-400">TRIGGER:</span>
            <span><?= e($activeTemplate['trigger']) ?></span>
          </div>
          <div class="flex items-center gap-2 text-[13px] text-white">
            <span class="font-mono font-bold text-emerald-400">SUBJECT:</span>
            <span class="font-semibold text-rose-200"><?= e($activeTemplate['subject']) ?></span>
          </div>
        </div>

        <!-- Controls: Device Mode & Copy HTML -->
        <div class="flex items-center gap-2 self-end md:self-center">
          <div class="flex items-center bg-white/10 p-1 rounded-xl border border-white/10 text-[12px]">
            <button type="button" @click="deviceMode = 'desktop'"
                    :class="deviceMode === 'desktop' ? 'bg-white/20 text-white font-bold' : 'text-white/60 hover:text-white'"
                    class="px-2.5 py-1 rounded-lg transition-all flex items-center gap-1">
              🖥️ Desktop
            </button>
            <button type="button" @click="deviceMode = 'mobile'"
                    :class="deviceMode === 'mobile' ? 'bg-white/20 text-white font-bold' : 'text-white/60 hover:text-white'"
                    class="px-2.5 py-1 rounded-lg transition-all flex items-center gap-1">
              📱 Mobile
            </button>
          </div>

          <button type="button" @click="copyHtml()"
                  class="px-3 py-1.5 rounded-xl bg-white/10 hover:bg-white/20 text-white text-[12px] font-mono border border-white/10 transition-all flex items-center gap-1">
            <span x-show="!copySuccess">📋 Copy HTML</span>
            <span x-show="copySuccess" style="display:none;" class="text-emerald-400 font-bold">✓ Copied!</span>
          </button>
        </div>
      </div>

      <!-- Iframe HTML Rendering Container -->
      <div class="flex justify-center transition-all">
        <div :class="deviceMode === 'mobile' ? 'w-[390px]' : 'w-full'"
             class="rounded-3xl overflow-hidden border border-white/15 shadow-2xl bg-white transition-all duration-300">
          
          <!-- Mockup Browser Header -->
          <div class="bg-gray-100 px-4 py-2.5 border-b border-gray-200 flex items-center justify-between text-gray-600 text-[11.5px] font-mono">
            <div class="flex items-center gap-2">
              <span class="w-2.5 h-2.5 rounded-full bg-rose-400 inline-block"></span>
              <span class="w-2.5 h-2.5 rounded-full bg-amber-400 inline-block"></span>
              <span class="w-2.5 h-2.5 rounded-full bg-emerald-400 inline-block"></span>
              <span class="ml-2 font-bold text-gray-700">Email Canvas Preview</span>
            </div>
            <span x-text="deviceMode === 'mobile' ? 'Mobile View (375px)' : 'Desktop View (100%)'"></span>
          </div>

          <iframe id="email_canvas" class="w-full h-[680px] border-0 bg-[#f1f5f9]"></iframe>
        </div>
      </div>
    </div>

  </div>

  <!-- Dedicated Template Test Send Modal -->
  <div x-show="showModal" class="fixed inset-0 z-[1000] bg-black/70 backdrop-blur-sm flex items-center justify-center p-4" style="display:none;" x-transition>
    <div class="bg-[#1e293b] p-6 md:p-7 rounded-3xl max-w-md w-full shadow-2xl border border-white/15 text-white" @click.away="showModal = false">
      <div class="flex items-center justify-between pb-3 border-b border-white/10">
        <div>
          <h4 class="font-bold text-[16px] text-white">Send Template Test Email</h4>
          <p class="text-[12px] text-amber-400 font-medium mt-0.5" x-text="targetName"></p>
        </div>
        <button type="button" @click="showModal = false" class="text-white/60 hover:text-white text-xl font-bold">&times;</button>
      </div>

      <div class="py-4 space-y-4 text-[13px]">
        <div>
          <label class="block text-white/70 mb-1.5 font-medium">প্রাপকের ইমেইল এড্রেস (Recipient Email):</label>
          <input type="email" x-model="testEmail" placeholder="e.g. yourname@gmail.com"
                 class="w-full px-4 py-2.5 rounded-xl text-white bg-black/40 border border-white/15 focus:outline-none focus:border-rose-400">
        </div>

        <p class="text-[11.5px] text-white/50 leading-relaxed">
          💡 এই টেমপ্লেটের অফিসিয়াল ডিজাইন, ব্র্যান্ডেড লোগো ও অ্যানিমেশন সহ একটি লাইভ ইমেইল আপনার ইনবক্সে প্রেরণ করা হবে।
        </p>
      </div>

      <div class="flex justify-end gap-2 pt-3 border-t border-white/10">
        <button type="button" @click="showModal = false" class="px-4 py-2 rounded-xl bg-white/10 hover:bg-white/20 text-white text-[12.5px]">Cancel</button>
        <button type="button" @click="sendTestMail(); showModal = false;" :disabled="sending"
                class="px-5 py-2 rounded-xl text-white font-semibold text-[12.5px] flex items-center gap-1.5"
                style="background:linear-gradient(135deg,#A22638,#800020);">
          <span>🚀 Send Test Now</span>
        </button>
      </div>
    </div>
  </div>

</div>

<script>
document.addEventListener("DOMContentLoaded", function() {
  const iframe = document.getElementById('email_canvas');
  const rawHolder = document.getElementById('raw_email_html');
  if (iframe && rawHolder) {
    const doc = iframe.contentWindow.document;
    doc.open();
    doc.write(rawHolder.value);
    doc.close();
  }
});
</script>
