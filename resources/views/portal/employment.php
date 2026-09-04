<?php
/**
 * Alumni Portal Employment View
 * Variables: $user, $profile, $employment
 */
?>
<div class="w-full space-y-8">
  <div class="flex flex-col lg:flex-row gap-8 items-start">
    
    <!-- Left Column: Avatar & Quick Info -->
    <?php require view_path('portal/partials/profile_sidebar.php'); ?>

    <!-- Right Column: Employment List & Add Form -->
    <div class="flex-1 min-w-0 space-y-6">
      
      <!-- Employment List -->
      <div class="p-8 rounded-3xl bg-white border border-gray-100 shadow-sm">
        <h3 class="font-serif text-[20px] font-semibold text-gray-800 mb-6 font-semibold">Employment History</h3>
        
        <?php if (empty($employment)): ?>
        <p class="text-[13.5px] text-gray-400 py-4 text-center">No employment records added yet.</p>
        <?php else: ?>
        <div class="space-y-4 divide-y divide-gray-100">
          <?php foreach ($employment as $emp): ?>
          <div class="pt-4 first:pt-0 flex items-start justify-between gap-4">
            <div>
              <div class="flex items-center gap-2">
                <h4 class="font-semibold text-gray-800 text-[15px]"><?= e($emp['job_title'] ?? '') ?></h4>
                <?php if (!empty($emp['is_current'])): ?>
                <span class="px-2 py-0.5 rounded bg-emerald-500/10 text-emerald-600 text-[10px] font-semibold font-mono uppercase">Current</span>
                <?php endif; ?>
              </div>
              <div class="text-[13px] text-gray-600 mt-1"><?= e($emp['organization'] ?? '') ?> · <?= e($emp['department'] ?? '') ?></div>
              <div class="text-[11.5px] text-gray-400 mt-0.5 font-mono">
                <?= e($emp['start_year'] ?? '') ?> — <?= !empty($emp['is_current']) ? 'Present' : e($emp['end_year'] ?? '') ?>
              </div>
            </div>
            <form method="POST" action="<?= url('/portal/profile/employment/delete') ?>" onsubmit="return confirm('Are you sure you want to delete this employment record? / রেকর্ডটি মুছে ফেলতে চান?');">
              <?= csrf_field() ?>
              <input type="hidden" name="id" value="<?= $emp['id'] ?>">
              <button type="submit" class="px-3 py-1.5 rounded-xl text-[12px] font-semibold text-red-600 bg-red-50 hover:bg-red-100 border border-red-100 transition-colors">
                🗑 Delete
              </button>
            </form>
          </div>
          <?php endforeach; ?>
        </div>
        <?php endif; ?>
      </div>

      <!-- Add Employment Form -->
      <div class="p-8 rounded-3xl bg-white border border-gray-100 shadow-sm">
        <h3 class="font-serif text-[18px] font-semibold text-gray-800 mb-5">Add Employment Record</h3>
        
        <form method="POST" action="<?= url('/portal/profile/employment') ?>" class="space-y-4">
          <?= csrf_field() ?>

          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
              <label class="form-label" for="job_title">Job Title</label>
              <input id="job_title" type="text" name="job_title" placeholder="e.g. Senior Epidemiologist" required class="form-input">
            </div>
            <div>
              <label class="form-label" for="organization">Organization / Company</label>
              <input id="organization" type="text" name="organization" placeholder="e.g. WHO or Ministry of Health" required class="form-input">
            </div>
          </div>

          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
              <label class="form-label" for="department">Department</label>
              <input id="department" type="text" name="department" placeholder="e.g. Research / Field Ops" class="form-input">
            </div>
            <div>
              <label class="form-label" for="location">Location</label>
              <input id="location" type="text" name="location" placeholder="e.g. Dhaka, Bangladesh" class="form-input">
            </div>
          </div>

          <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
              <label class="form-label" for="start_year">Start Year</label>
              <input id="start_year" type="number" name="start_year" min="1950" max="<?= date('Y') ?>" placeholder="2018" required class="form-input">
            </div>
            <div>
              <label class="form-label" for="end_year">End Year</label>
              <input id="end_year" type="number" name="end_year" min="1950" max="<?= date('Y') + 1 ?>" placeholder="2022" class="form-input" x-data x-bind:disabled="$store.isCurrent">
            </div>
            <div class="flex items-center pt-8">
              <label class="inline-flex items-center gap-2 text-[13.5px] cursor-pointer">
                <input type="checkbox" name="is_current" value="1" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500" 
                       x-data @change="$store.isCurrent = $el.checked">
                Current Job
              </label>
            </div>
          </div>

          <div>
            <label class="form-label" for="description">Job Description</label>
            <textarea id="description" name="description" rows="2" placeholder="Brief summary of duties..." class="form-input"></textarea>
          </div>

          <button type="submit" class="btn btn-gold px-6">Add Employment</button>
        </form>
      </div>

    </div>

  </div>
</div>

<script>
// Store flag in Alpine store for simpler bind state
document.addEventListener('alpine:init', () => {
    Alpine.store('isCurrent', false);
});
</script>
