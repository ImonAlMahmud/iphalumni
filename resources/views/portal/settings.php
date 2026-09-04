<?php
/**
 * Alumni Portal Account Settings View
 * Variables: $user
 */
?>
<div class="w-full max-w-6xl mx-auto space-y-8">
  <div class="flex flex-col md:flex-row gap-8 items-start">
    
    <!-- Left Column: Avatar & Quick Info -->
    <?php require view_path('portal/partials/profile_sidebar.php'); ?>

    <!-- Right Column: Settings Forms -->
    <div class="flex-1 space-y-6">
      
      <!-- Change Password -->
      <div class="p-8 rounded-3xl bg-white border border-gray-100 shadow-sm">
        <h3 class="font-serif text-[20px] font-semibold text-gray-800 mb-6 font-semibold">Change Password</h3>
        
        <form method="POST" action="<?= url('/portal/settings') ?>" class="space-y-4">
          <?= csrf_field() ?>

          <div>
            <label class="form-label" for="current_password">Current Password</label>
            <input id="current_password" type="password" name="current_password" required class="form-input">
          </div>

          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
              <label class="form-label" for="new_password">New Password</label>
              <input id="new_password" type="password" name="new_password" required minlength="8" class="form-input">
            </div>
            <div>
              <label class="form-label" for="confirm_password">Confirm New Password</label>
              <input id="confirm_password" type="password" name="confirm_password" required minlength="8" class="form-input"
                     oninput="this.setCustomValidity(this.value !== document.getElementById('new_password').value ? 'Passwords do not match.' : '')">
            </div>
          </div>

          <button type="submit" class="btn btn-gold px-6">Update Password</button>
        </form>
      </div>

      <!-- Privacy Settings -->
      <div class="p-8 rounded-3xl bg-white border border-gray-100 shadow-sm">
        <h3 class="font-serif text-[18px] font-semibold text-gray-800 mb-4">Privacy & Visibility</h3>
        
        <form method="POST" action="<?= url('/portal/settings') ?>" class="space-y-4">
          <?= csrf_field() ?>

          <div class="flex items-start gap-3">
            <input id="is_public" type="checkbox" name="is_public" value="1" 
                   <?= ($profile['is_public'] ?? 1) ? 'checked' : '' ?>
                   class="mt-1 rounded border-gray-300 text-blue-600 focus:ring-blue-500">
            <div>
              <label for="is_public" class="block text-[14px] font-medium text-gray-800 cursor-pointer">Show Profile in Public Directory</label>
              <p class="text-[12.5px] text-gray-400 mt-1">If unchecked, other alumni will not be able to find your profile or contact details via the search tool.</p>
            </div>
          </div>

          <button type="submit" class="btn btn-gold px-6">Save Privacy Settings</button>
        </form>
      </div>

    </div>

  </div>
</div>
