<?php $appName = env('APP_NAME', 'IPH Alumni Association'); ?>
<footer class="mt-20 text-white relative overflow-hidden font-['Kalpurush']"
        style="background: linear-gradient(135deg, #0F172A 0%, #1E1B4B 40%, #2E1065 75%, #0F172A 100%); border-top:1px solid rgba(255,255,255,0.1);">
  
  <!-- Subtle Gradient Ambient Glow Orbs -->
  <div class="absolute -top-24 -left-24 w-96 h-96 rounded-full blur-3xl opacity-20 pointer-events-none" style="background:#800020;"></div>
  <div class="absolute -bottom-24 -right-24 w-96 h-96 rounded-full blur-3xl opacity-20 pointer-events-none" style="background:#2F8863;"></div>

  <div class="max-w-7xl mx-auto px-6 py-16 relative z-10">
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-10">

      <!-- Brand Column -->
      <div class="lg:col-span-2">
        <a href="<?= url('/') ?>" class="inline-flex items-center gap-3 mb-4 group">
          <img src="<?= asset('images/LOGO.png') ?>" alt="IPH Logo"
               class="w-11 h-11 object-contain transition-transform duration-300 group-hover:scale-105 filter drop-shadow-md">
          <div>
            <div class="font-semibold text-white text-[16px] leading-tight">
              <?= e(__('আইপিএইচ অ্যালামনাই অ্যাসোসিয়েশন', $appName)) ?>
            </div>
            <div class="font-mono text-[10px] text-rose-300 tracking-widest mt-0.5">IPH ALUMNI NETWORK</div>
          </div>
        </a>
        <p class="text-[13.5px] text-slate-300 leading-relaxed max-w-[310px] mb-6">
          <?= __('ইনস্টিটিউট অব পাবলিক হেলথ-এর গ্র্যাজুয়েটদের জন্য একটি বিশ্বস্ত ডিজিটাল প্ল্যাটফর্ম। ভেরিফাইড প্রোফাইল, মেম্বারশিপ কার্ড, ও সংযুক্ত কমিউনিটি।',
                'A trusted digital home for IPH graduates — verified profiles, membership cards, and a connected alumni community.') ?>
        </p>

        <!-- Social Icons -->
        <div class="flex items-center gap-3">
          <a href="#" aria-label="Facebook"
             class="w-10 h-10 rounded-xl flex items-center justify-center text-slate-300 hover:text-white hover:bg-[#1877F2] transition-all duration-200 bg-white/10 border border-white/10 hover:border-transparent shadow-sm">
            <i class="fa-brands fa-facebook-f text-[14px]"></i>
          </a>
          <a href="#" aria-label="LinkedIn"
             class="w-10 h-10 rounded-xl flex items-center justify-center text-slate-300 hover:text-white hover:bg-[#0A66C2] transition-all duration-200 bg-white/10 border border-white/10 hover:border-transparent shadow-sm">
            <i class="fa-brands fa-linkedin-in text-[14px]"></i>
          </a>
          <a href="#" aria-label="YouTube"
             class="w-10 h-10 rounded-xl flex items-center justify-center text-slate-300 hover:text-white hover:bg-[#FF0000] transition-all duration-200 bg-white/10 border border-white/10 hover:border-transparent shadow-sm">
            <i class="fa-brands fa-youtube text-[14px]"></i>
          </a>
          <a href="<?= url('/contact') ?>" aria-label="Email"
             class="w-10 h-10 rounded-xl flex items-center justify-center text-slate-300 hover:text-white hover:bg-[#800020] transition-all duration-200 bg-white/10 border border-white/10 hover:border-transparent shadow-sm">
            <i class="fa-solid fa-envelope text-[13px]"></i>
          </a>
        </div>
      </div>

      <!-- Platform Links -->
      <div>
        <h5 class="font-mono text-[11px] tracking-widest text-rose-300 uppercase mb-4 font-bold flex items-center gap-2">
          <i class="fa-solid fa-layer-group text-[12px]"></i><?= __('প্ল্যাটফর্ম', 'Platform') ?>
        </h5>
        <div class="flex flex-col gap-2.5">
          <?php
          $platformLinks = [
            ['/directory', 'fa-solid fa-address-book',   __('অ্যালামনাই ডিরেক্টরি', 'Alumni Directory')],
            ['/stories',   'fa-solid fa-trophy',          __('সফলতার গল্প',          'Success Stories')],
            ['/events',    'fa-solid fa-calendar-days',   __('ইভেন্ট ও অনুষ্ঠান',    'Events')],
            ['/news',      'fa-solid fa-newspaper',       __('সংবাদ ও আপডেট',        'News & Updates')],
            ['/gallery',   'fa-solid fa-images',          __('ফটো গ্যালারি',          'Photo Gallery')],
          ];
          foreach ($platformLinks as [$href, $icon, $label]):
          ?>
          <a href="<?= url($href) ?>"
             class="flex items-center gap-2 text-[13.5px] text-slate-300 hover:text-white transition-colors group">
            <i class="<?= $icon ?> w-4 text-center text-[11px] text-slate-400 group-hover:text-rose-300 transition-colors"></i>
            <?= $label ?>
          </a>
          <?php endforeach; ?>
        </div>
      </div>

      <!-- Association Links -->
      <div>
        <h5 class="font-mono text-[11px] tracking-widest text-rose-300 uppercase mb-4 font-bold flex items-center gap-2">
          <i class="fa-solid fa-landmark text-[12px]"></i><?= __('অ্যাসোসিয়েশন', 'Association') ?>
        </h5>
        <div class="flex flex-col gap-2.5">
          <?php
          $assocLinks = [
            ['/about',       'fa-solid fa-circle-info',  __('আমাদের পরিচিতি',    'About Us')],
            ['/constitution','fa-solid fa-scroll',       __('গঠনতন্ত্র (Constitution)', 'Constitution')],
            ['/history',     'fa-solid fa-landmark',     __('আইপিএইচ ইতিহাস',    'IPH History')],
            ['/committee',   'fa-solid fa-users-gear',   __('কমিটি মেম্বারগণ',    'Committee Members')],
            ['/faq',         'fa-solid fa-circle-question', __('সাধারণ প্রশ্নাবলী', 'FAQ')],
          ];
          foreach ($assocLinks as [$href, $icon, $label]):
          ?>
          <a href="<?= url($href) ?>"
             class="flex items-center gap-2 text-[13.5px] text-slate-300 hover:text-white transition-colors group">
            <i class="<?= $icon ?> w-4 text-center text-[11px] text-slate-400 group-hover:text-rose-300 transition-colors"></i>
            <?= $label ?>
          </a>
          <?php endforeach; ?>
        </div>
      </div>

      <!-- Member Portal Links -->
      <div>
        <h5 class="font-mono text-[11px] tracking-widest text-rose-300 uppercase mb-4 font-bold flex items-center gap-2">
          <i class="fa-solid fa-user-shield text-[12px]"></i><?= __('সদস্য এরিয়া', 'Members Area') ?>
        </h5>
        <div class="flex flex-col gap-2.5">
          <?php if (is_logged_in()): ?>
          <a href="<?= url('/portal') ?>"
             class="flex items-center gap-2 text-[13.5px] text-slate-300 hover:text-white transition-colors group">
            <i class="fa-solid fa-table-columns w-4 text-center text-[11px] text-slate-400 group-hover:text-rose-300 transition-colors"></i>
            <?= __('আমার পোর্টাল', 'My Portal') ?>
          </a>
          <a href="<?= url('/portal/profile') ?>"
             class="flex items-center gap-2 text-[13.5px] text-slate-300 hover:text-white transition-colors group">
            <i class="fa-solid fa-user-pen w-4 text-center text-[11px] text-slate-400 group-hover:text-rose-300 transition-colors"></i>
            <?= __('প্রোফাইল সম্পাদন', 'Edit Profile') ?>
          </a>
          <a href="<?= url('/logout') ?>"
             class="flex items-center gap-2 text-[13.5px] text-rose-400 hover:text-rose-300 transition-colors">
            <i class="fa-solid fa-arrow-right-from-bracket w-4 text-center text-[11px]"></i>
            <?= __('লগআউট', 'Logout') ?>
          </a>
          <?php else: ?>
          <a href="<?= url('/register') ?>"
             class="inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl text-[13px] font-semibold text-white mb-1 transition-all hover:scale-105 shadow-lg"
             style="background:linear-gradient(135deg,#A22638,#800020);">
            <i class="fa-solid fa-user-plus text-[12px]"></i>
            <?= __('অ্যালামনাই হিসেবে যোগ দিন', 'Join as Alumni') ?>
          </a>
          <a href="<?= url('/login') ?>"
             class="flex items-center gap-2 text-[13.5px] text-slate-300 hover:text-white transition-colors group">
            <i class="fa-solid fa-right-to-bracket w-4 text-center text-[11px] text-slate-400 group-hover:text-rose-300 transition-colors"></i>
            <?= __('লগইন করুন', 'Log in') ?>
          </a>
          <?php if (is_admin()): ?>
          <a href="<?= url('/admin') ?>"
             class="flex items-center gap-2 text-[13.5px] text-slate-300 hover:text-white transition-colors group">
            <i class="fa-solid fa-gauge-high w-4 text-center text-[11px] text-slate-400 group-hover:text-rose-300 transition-colors"></i>
            <?= __('অ্যাডমিন প্যানেল', 'Admin Panel') ?>
          </a>
          <?php endif; ?>
          <?php endif; ?>
        </div>
      </div>

    </div>
  </div>

  <!-- Bottom Bar -->
  <div style="border-top:1px solid rgba(255,255,255,0.08);" class="bg-black/20 backdrop-blur-md relative z-10">
    <div class="max-w-7xl mx-auto px-6 py-5 flex flex-col sm:flex-row justify-between items-center gap-3 text-[12.5px] text-slate-400">
      <span>
        <i class="fa-regular fa-copyright mr-1"></i>
        <?= date('Y') ?> <?= e(__('আইপিএইচ অ্যালামনাই অ্যাসোসিয়েশন', $appName)) ?>.
        <?= __('সর্বস্বত্ব সংরক্ষিত।', 'All rights reserved.') ?>
      </span>
      <div class="flex items-center gap-4">
        <span class="font-mono text-[11px] flex items-center gap-1">
          <i class="fa-solid fa-heart text-rose-500 text-[10px]"></i>
          Built with love for IPH graduates By <a href="https://ideomet.com" target="_blank" rel="noopener noreferrer" class="hover:underline text-rose-300 font-semibold">Ideomet Technologies</a>
        </span>
        <a href="<?= url('/contact') ?>" class="hover:text-white transition-colors flex items-center gap-1">
          <i class="fa-solid fa-envelope text-[11px]"></i>
          <?= __('যোগাযোগ', 'Contact') ?>
        </a>
      </div>
    </div>
  </div>
</footer>
