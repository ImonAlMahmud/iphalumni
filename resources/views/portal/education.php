<?php
/**
 * Alumni Portal Education View
 * Variables: $user, $profile, $education
 */
?>
<div class="w-full space-y-8">
  <div class="flex flex-col lg:flex-row gap-8 items-start">
    
    <!-- Left Column: Avatar & Quick Info -->
    <?php require view_path('portal/partials/profile_sidebar.php'); ?>

    <!-- Right Column: Education List & Add Form -->
    <div class="flex-1 min-w-0 space-y-6">
      
      <!-- Education List -->
      <div class="p-8 rounded-3xl bg-white border border-gray-100 shadow-sm">
        <h3 class="font-serif text-[20px] font-semibold text-gray-800 mb-6 font-semibold">Education History</h3>
        
        <?php if (empty($education)): ?>
        <p class="text-[13.5px] text-gray-400 py-4 text-center">No education records added yet.</p>
        <?php else: ?>
        <div class="space-y-4 divide-y divide-gray-100">
          <?php foreach ($education as $edu): ?>
          <div class="pt-4 first:pt-0 flex items-start justify-between gap-4">
            <div>
              <h4 class="font-semibold text-gray-800 text-[15px]"><?= e($edu['degree'] ?? '') ?> in <?= e($edu['field_of_study'] ?? '') ?></h4>
              <div class="text-[13px] text-gray-600 mt-1"><?= e($edu['institution'] ?? '') ?></div>
              <?php if (!empty($edu['graduation_year'])): ?>
              <div class="text-[11.5px] text-gray-400 mt-0.5 font-mono">Class of <?= e($edu['graduation_year']) ?></div>
              <?php endif; ?>
            </div>
            <form method="POST" action="<?= url('/portal/profile/education/delete') ?>" onsubmit="return confirm('Are you sure you want to delete this education record? / রেকর্ডটি মুছে ফেলতে চান?');">
              <?= csrf_field() ?>
              <input type="hidden" name="id" value="<?= $edu['id'] ?>">
              <button type="submit" class="px-3 py-1.5 rounded-xl text-[12px] font-semibold text-red-600 bg-red-50 hover:bg-red-100 border border-red-100 transition-colors">
                🗑 Delete
              </button>
            </form>
          </div>
          <?php endforeach; ?>
        </div>
        <?php endif; ?>
      </div>

      <!-- Add Education Form -->
      <div class="p-8 rounded-3xl bg-white border border-gray-100 shadow-sm">
        <h3 class="font-serif text-[18px] font-semibold text-gray-800 mb-5">Add Education Record</h3>
        
        <form method="POST" action="<?= url('/portal/profile/education') ?>" class="space-y-4">
          <?= csrf_field() ?>

          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
              <label class="form-label" for="degree">Degree / Certificate</label>
              <input id="degree" type="text" name="degree" placeholder="e.g. Master of Public Health (MPH)" required class="form-input">
            </div>
            <div>
              <label class="form-label" for="field_of_study">Field of Study</label>
              <input id="field_of_study" type="text" name="field_of_study" placeholder="e.g. Epidemiology" required class="form-input">
            </div>
          </div>

          <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="md:col-span-2">
              <label class="form-label" for="institution">Institution / University</label>
              <input id="institution" type="text" name="institution" placeholder="e.g. Institute of Public Health" required class="form-input">
            </div>
            <div>
              <label class="form-label" for="graduation_year">Graduation Year</label>
              <input id="graduation_year" type="number" name="graduation_year" min="1950" max="<?= date('Y') + 5 ?>" placeholder="2020" required class="form-input">
            </div>
          </div>

          <button type="submit" class="btn btn-gold px-6">Add Education</button>
        </form>
      </div>

    </div>

  </div>
</div>
