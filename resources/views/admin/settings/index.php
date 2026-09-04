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
    </div>

    <!-- SMTP Configuration Section -->
    <div class="pt-6 border-t border-white/5 space-y-4">
      <h3 class="text-[16px] font-semibold text-[#E58E97] font-serif">SMTP & Email Configuration</h3>
      <p class="text-[12px] text-white/50">Configure the mail server settings used for sending notifications and password resets.</p>
      
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
    </div>

    <button type="submit" class="px-6 py-2.5 rounded-xl text-[14px] font-semibold text-white"
            style="background:linear-gradient(135deg,#A22638,#800020);">Save Settings</button>
  </form>
</div>
