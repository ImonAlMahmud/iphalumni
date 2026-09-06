<?php
/**
 * Admin Student Reference Database View — Redesigned
 * Variables: $students, $pagination, $batches, $sessions, $depts, $batch, $session, $dept, $search, $missingInfo, $totalAll, $missingCount
 */
$missingInfo = $_GET['missing_info'] ?? '';
$totalAll = $totalAll ?? ($pagination['total'] ?? 0);
$missingCount = $missingCount ?? 0;
?>

<style>
  .glass-card {
    background: rgba(255, 255, 255, 0.03);
    border: 1px solid rgba(255, 255, 255, 0.08);
    backdrop-filter: blur(16px);
    -webkit-backdrop-filter: blur(16px);
  }
  .glass-card:hover {
    border-color: rgba(255, 255, 255, 0.14);
  }
  .pill-scroll::-webkit-scrollbar {
    height: 5px;
  }
  .pill-scroll::-webkit-scrollbar-track {
    background: rgba(255, 255, 255, 0.02);
    border-radius: 4px;
  }
  .pill-scroll::-webkit-scrollbar-thumb {
    background: rgba(255, 255, 255, 0.15);
    border-radius: 4px;
  }
  .pill-scroll::-webkit-scrollbar-thumb:hover {
    background: rgba(162, 38, 56, 0.5);
  }
</style>

<!-- ════════════════════════════ PAGE HEADER & HERO ════════════════════════════ -->
<div class="flex flex-col lg:flex-row lg:items-center justify-between gap-5 mb-8">
  <div>
    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-[11px] font-mono font-semibold uppercase tracking-wider mb-2.5"
         style="background:rgba(162,38,56,0.15);color:#E58E97;border:1px solid rgba(162,38,56,0.3);">
      <i class="fa-solid fa-database text-[10px]"></i> Central Student Registry
    </div>
    <h1 class="font-serif text-[24px] lg:text-[28px] font-bold text-white tracking-tight flex items-center gap-3">
      Student Reference Database
      <span class="text-[14px] font-sans font-normal text-white/40 hidden sm:inline">(শিক্ষার্থী তথ্যভান্ডার)</span>
    </h1>
    <p class="text-[13px] text-white/55 mt-1 max-w-2xl">
      অ্যালামনাই ভেরিফিকেশন, অটো-ম্যাপিং এবং গ্র্যাজুয়েটিং ব্যাচ ট্র্যাকিংয়ের মূল ডাটাবেজ। এখান থেকে সরাসরি নতুন ব্যাচ যোগ এবং এক্সেল ফাইলের মাধ্যমে ডাটা ইমপোর্ট করা যাবে।
    </p>
  </div>

  <!-- Primary Header Actions -->
  <div class="flex flex-wrap items-center gap-2.5 shrink-0">
    <!-- Add Student / New Batch Button -->
    <button onclick="openAddModal()" 
            class="px-4 py-2.5 rounded-xl text-[13px] font-semibold text-white transition-all hover:scale-[1.02] active:scale-95 shadow-lg shadow-[#A22638]/25 flex items-center gap-2"
            style="background:linear-gradient(135deg,#A22638,#800020);border:1px solid rgba(229,142,151,0.3);">
      <i class="fa-solid fa-plus-circle text-[13px]"></i>
      <span>নতুন শিক্ষার্থী / ব্যাচ যোগ</span>
    </button>

    <!-- Import Excel / CSV Button -->
    <button onclick="openImportModal()" 
            class="px-4 py-2.5 rounded-xl text-[13px] font-semibold text-white transition-all hover:scale-[1.02] active:scale-95 shadow-lg shadow-emerald-950/40 flex items-center gap-2"
            style="background:linear-gradient(135deg,#137333,#0d5324);border:1px solid rgba(52,211,153,0.3);">
      <i class="fa-solid fa-file-excel text-[13px] text-emerald-300"></i>
      <span>এক্সেল / সিএসভি ইমপোর্ট</span>
    </button>

    <!-- Sample Template Download -->
    <a href="<?= url('/admin/students/sample-template') ?>" 
       class="px-3.5 py-2.5 glass-card rounded-xl text-[12.5px] font-medium text-white hover:text-amber-300 transition-colors flex items-center gap-1.5"
       title="Download Sample CSV/Excel Template">
      <i class="fa-solid fa-download text-amber-400 text-[11px]"></i>
      <span>টেমপ্লেট</span>
    </a>
  </div>
</div>

<!-- ════════════════════════════ FLASH NOTIFICATIONS ════════════════════════════ -->
<?php if (has_flash('success')): ?>
  <div class="mb-6 p-4 rounded-2xl bg-emerald-500/15 border border-emerald-500/30 text-emerald-200 text-[13.5px] flex items-center justify-between shadow-lg shadow-emerald-950/20">
    <div class="flex items-center gap-3">
      <div class="w-8 h-8 rounded-xl bg-emerald-500/20 flex items-center justify-center text-emerald-400 shrink-0">
        <i class="fa-solid fa-circle-check text-base"></i>
      </div>
      <span class="font-medium"><?= get_flash('success') ?></span>
    </div>
    <button onclick="this.parentElement.remove()" class="text-emerald-400 hover:text-white text-lg px-2">&times;</button>
  </div>
<?php endif; ?>

<?php if (has_flash('error')): ?>
  <div class="mb-6 p-4 rounded-2xl bg-rose-500/15 border border-rose-500/30 text-rose-200 text-[13.5px] flex items-center justify-between shadow-lg shadow-rose-950/20">
    <div class="flex items-center gap-3">
      <div class="w-8 h-8 rounded-xl bg-rose-500/20 flex items-center justify-center text-rose-400 shrink-0">
        <i class="fa-solid fa-triangle-exclamation text-base"></i>
      </div>
      <span class="font-medium"><?= get_flash('error') ?></span>
    </div>
    <button onclick="this.parentElement.remove()" class="text-rose-400 hover:text-white text-lg px-2">&times;</button>
  </div>
<?php endif; ?>

<!-- ════════════════════════════ STAT METRIC CARDS ════════════════════════════ -->
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
  <!-- Card 1: Total Students -->
  <div class="glass-card rounded-2xl p-5 relative overflow-hidden transition-all hover:-translate-y-0.5">
    <div class="flex items-center justify-between mb-2.5">
      <span class="text-[12px] font-mono text-white/50 uppercase tracking-wider">সর্বমোট শিক্ষার্থী</span>
      <div class="w-9 h-9 rounded-xl bg-[#A22638]/20 border border-[#A22638]/30 flex items-center justify-center text-[#E58E97]">
        <i class="fa-solid fa-users text-[14px]"></i>
      </div>
    </div>
    <div class="font-serif text-[28px] font-bold text-white tracking-tight">
      <?= number_format($totalAll) ?>
    </div>
    <div class="text-[12px] text-white/40 mt-1 flex items-center gap-1.5">
      <span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span>
      ডাটাবেজে স্থায়ী রেকর্ড
    </div>
  </div>

  <!-- Card 2: Total Batches -->
  <div class="glass-card rounded-2xl p-5 relative overflow-hidden transition-all hover:-translate-y-0.5">
    <div class="flex items-center justify-between mb-2.5">
      <span class="text-[12px] font-mono text-white/50 uppercase tracking-wider">গ্র্যাজুয়েটিং ব্যাচ</span>
      <div class="w-9 h-9 rounded-xl bg-amber-500/15 border border-amber-500/30 flex items-center justify-center text-amber-400">
        <i class="fa-solid fa-graduation-cap text-[14px]"></i>
      </div>
    </div>
    <div class="font-serif text-[28px] font-bold text-amber-300 tracking-tight">
      <?= count($batches) ?> <span class="text-[15px] font-normal text-white/40 font-sans">টি ব্যাচ</span>
    </div>
    <div class="text-[12px] text-white/40 mt-1 truncate">
      <?= !empty($batches) ? reset($batches) . ' থেকে ' . end($batches) : 'কোনো ব্যাচ নেই' ?>
    </div>
  </div>

  <!-- Card 3: Missing Info / Incomplete -->
  <a href="<?= url('/admin/students?' . http_build_query(array_merge($_GET, ['missing_info' => $missingInfo === '1' ? '' : '1', 'page' => 1]))) ?>" 
     class="glass-card rounded-2xl p-5 relative overflow-hidden transition-all hover:-translate-y-0.5 block group <?= $missingInfo === '1' ? 'ring-2 ring-rose-500/50 bg-rose-500/[0.06]' : '' ?>">
    <div class="flex items-center justify-between mb-2.5">
      <span class="text-[12px] font-mono text-white/50 uppercase tracking-wider group-hover:text-rose-300 transition-colors">অসম্পূর্ণ তথ্য</span>
      <div class="w-9 h-9 rounded-xl <?= $missingCount > 0 ? 'bg-rose-500/20 text-rose-400 border border-rose-500/30' : 'bg-emerald-500/20 text-emerald-400 border border-emerald-500/30' ?> flex items-center justify-center">
        <i class="fa-solid <?= $missingCount > 0 ? 'fa-triangle-exclamation' : 'fa-check' ?> text-[14px]"></i>
      </div>
    </div>
    <div class="font-serif text-[28px] font-bold <?= $missingCount > 0 ? 'text-rose-400' : 'text-emerald-400' ?> tracking-tight">
      <?= number_format($missingCount) ?>
    </div>
    <div class="text-[12px] <?= $missingInfo === '1' ? 'text-rose-300 font-semibold' : 'text-white/40' ?> mt-1 flex items-center justify-between">
      <span><?= $missingInfo === '1' ? 'ফিল্টার সক্রিয় (ক্লিক করে বাতিল)' : 'রোল বা ফোন নম্বর নেই' ?></span>
      <i class="fa-solid fa-arrow-right text-[10px] opacity-40 group-hover:opacity-100 group-hover:translate-x-0.5 transition-all"></i>
    </div>
  </a>

  <!-- Card 4: Filtered Records -->
  <div class="glass-card rounded-2xl p-5 relative overflow-hidden transition-all hover:-translate-y-0.5">
    <div class="flex items-center justify-between mb-2.5">
      <span class="text-[12px] font-mono text-white/50 uppercase tracking-wider">ফিল্টার ফলাফল</span>
      <div class="w-9 h-9 rounded-xl bg-emerald-500/15 border border-emerald-500/30 flex items-center justify-center text-emerald-400">
        <i class="fa-solid fa-filter text-[14px]"></i>
      </div>
    </div>
    <div class="font-serif text-[28px] font-bold text-white tracking-tight">
      <?= number_format($pagination['total']) ?>
    </div>
    <div class="text-[12px] text-white/40 mt-1 flex items-center justify-between">
      <span><?= $batch ? "ব্যাচ '{$batch}' সিলেক্টেড" : ($search ? "'{$search}' অনুসন্ধান" : 'সব রেকর্ড দৃশ্যমান') ?></span>
      <span class="font-mono text-[11px] text-white/30">পৃষ্ঠা <?= $pagination['current_page'] ?> / <?= max(1, $pagination['last_page']) ?></span>
    </div>
  </div>
</div>

<!-- ════════════════════════════ QUICK BATCH PILL BAR ════════════════════════════ -->
<div class="mb-6">
  <div class="flex items-center justify-between mb-2 px-1">
    <div class="text-[12px] font-mono text-white/50 uppercase tracking-wider flex items-center gap-1.5">
      <i class="fa-solid fa-layer-group text-[#E58E97]"></i> কুইক ব্যাচ ফিল্টার (Quick Batch Switcher):
    </div>
    <?php if ($batch !== ''): ?>
    <a href="<?= url('/admin/students?' . http_build_query(array_merge($_GET, ['batch' => '']))) ?>" 
       class="text-[11.5px] text-[#E58E97] hover:text-white transition-colors flex items-center gap-1">
      <i class="fa-solid fa-xmark"></i> ব্যাচ ফিল্টার মুছুন
    </a>
    <?php endif; ?>
  </div>

  <div class="flex items-center gap-2 overflow-x-auto pb-2 pill-scroll">
    <!-- All Batches Pill -->
    <a href="<?= url('/admin/students?' . http_build_query(array_merge($_GET, ['batch' => '', 'page' => 1]))) ?>" 
       class="px-3.5 py-1.5 rounded-xl text-[12.5px] font-mono font-medium transition-all shrink-0 flex items-center gap-2 <?= empty($batch) ? 'bg-[#A22638] text-white shadow-md shadow-[#A22638]/30 font-bold border border-[#E58E97]/40' : 'bg-white/5 text-white/70 hover:bg-white/10 hover:text-white border border-white/10' ?>">
      <span>All Batches</span>
      <span class="px-1.5 py-0.2 rounded-md text-[10.5px] <?= empty($batch) ? 'bg-black/30 text-white' : 'bg-white/10 text-white/60' ?>"><?= count($batches) ?></span>
    </a>

    <?php foreach ($batches as $b): ?>
    <?php 
      $isCurrent = ($batch === $b);
      $isLBatch = str_starts_with($b, 'L-');
    ?>
    <a href="<?= url('/admin/students?' . http_build_query(array_merge($_GET, ['batch' => $b, 'page' => 1]))) ?>" 
       class="px-3.5 py-1.5 rounded-xl text-[12.5px] font-mono transition-all shrink-0 flex items-center gap-1.5 <?= $isCurrent ? 'bg-[#800020] text-white shadow-md shadow-[#800020]/40 font-bold border border-[#E58E97]' : 'bg-white/5 text-white/70 hover:bg-white/10 hover:text-white border border-white/10' ?>">
      <span class="w-2 h-2 rounded-full <?= $isLBatch ? 'bg-[#E58E97]' : 'bg-emerald-400' ?>"></span>
      <span><?= e($b) ?></span>
    </a>
    <?php endforeach; ?>
  </div>
</div>

<!-- ════════════════════════════ SEARCH & ADVANCED FILTERS ════════════════════════════ -->
<div class="glass-card rounded-2xl p-4 lg:p-5 mb-6">
  <form method="GET" action="<?= url('/admin/students') ?>" class="flex flex-wrap items-center gap-3">
    <!-- Search Box -->
    <div class="relative flex-1 min-w-[240px]">
      <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-1/2 -translate-y-1/2 text-white/40 text-[13px]"></i>
      <input type="text" name="q" value="<?= e($search) ?>" placeholder="নাম, রোল অথবা ফোন নম্বর দিয়ে খুঁজুন..."
             class="w-full pl-10 pr-4 py-2.5 rounded-xl text-[13px] text-white placeholder:text-white/30 focus:outline-none focus:ring-2 focus:ring-[#A22638]/40"
             style="background:rgba(255,255,255,0.06);border:1px solid rgba(255,255,255,0.1);">
    </div>

    <!-- Batch Select -->
    <select name="batch" class="px-3.5 py-2.5 rounded-xl text-[13px] text-white focus:outline-none bg-[#121316]"
            style="border:1px solid rgba(255,255,255,0.1);min-width:130px;">
      <option value="">সকল ব্যাচ</option>
      <?php foreach ($batches as $b): ?>
      <option value="<?= e($b) ?>" <?= $batch === $b ? 'selected' : '' ?>><?= e($b) ?></option>
      <?php endforeach; ?>
    </select>

    <!-- Session Select -->
    <select name="session" class="px-3.5 py-2.5 rounded-xl text-[13px] text-white focus:outline-none bg-[#121316]"
            style="border:1px solid rgba(255,255,255,0.1);min-width:130px;">
      <option value="">সকল সেশন</option>
      <?php foreach ($sessions as $s): ?>
      <option value="<?= e($s) ?>" <?= $session === $s ? 'selected' : '' ?>><?= e($s) ?></option>
      <?php endforeach; ?>
    </select>

    <!-- Department Select -->
    <select name="dept" class="px-3.5 py-2.5 rounded-xl text-[13px] text-white focus:outline-none bg-[#121316]"
            style="border:1px solid rgba(255,255,255,0.1);max-width:210px;">
      <option value="">সকল বিভাগ (Dept)</option>
      <?php foreach ($depts as $d): ?>
      <option value="<?= e($d) ?>" <?= $dept === $d ? 'selected' : '' ?>><?= e($d) ?></option>
      <?php endforeach; ?>
    </select>

    <!-- Missing Info Toggle -->
    <input type="hidden" name="missing_info" value="<?= e($missingInfo) ?>">

    <!-- Submit & Reset -->
    <button type="submit" class="px-5 py-2.5 rounded-xl text-[13px] font-semibold text-white transition-all shadow-md shadow-[#A22638]/20 hover:brightness-110 flex items-center gap-2"
            style="background:linear-gradient(135deg,#A22638,#800020);">
      <i class="fa-solid fa-filter text-[11px]"></i> ফিল্টার
    </button>

    <a href="<?= url('/admin/students') ?>" class="px-4 py-2.5 rounded-xl text-[13px] text-white/70 hover:text-white glass-card transition-colors">
      রিসেট
    </a>

    <!-- Export Actions on the Right -->
    <div class="ml-auto flex items-center gap-2 w-full lg:w-auto justify-end pt-2 lg:pt-0 border-t lg:border-t-0 border-white/5">
      <?php if ($batch !== ''): ?>
      <!-- Delete Entire Batch Button -->
      <form method="POST" action="<?= url('/admin/students/batch/delete') ?>" 
            onsubmit="return confirm('সতর্কতা: আপনি কি নিশ্চিত যে ব্যাচ \'<?= e(addslashes($batch)) ?>\'-এর সকল শিক্ষার্থীর রেকর্ড মুছে ফেলতে চান? এই অ্যাকশন আনডু করা যাবে না!');"
            class="inline">
        <?= csrf_field() ?>
        <input type="hidden" name="batch" value="<?= e($batch) ?>">
        <button type="submit" class="px-3 py-2 bg-rose-950/40 hover:bg-rose-900 text-rose-300 border border-rose-800/40 rounded-xl text-[12px] font-medium transition-colors flex items-center gap-1.5" title="ব্যাচের সমস্ত ডাটা মুছুন">
          <i class="fa-solid fa-trash-can text-[11px]"></i> ব্যাচ '<?= e($batch) ?>' মুছুন
        </button>
      </form>
      <?php endif; ?>

      <a href="<?= url('/admin/students/export/csv?' . http_build_query($_GET)) ?>" 
         class="px-3.5 py-2 bg-emerald-950/40 hover:bg-emerald-900/60 text-emerald-300 border border-emerald-800/50 rounded-xl text-[12.5px] font-medium transition-colors flex items-center gap-1.5">
        <i class="fa-solid fa-file-excel text-[12px]"></i> Export Excel
      </a>

      <a href="<?= url('/admin/students/export/print?' . http_build_query($_GET)) ?>" target="_blank" 
         class="px-3.5 py-2 bg-rose-950/40 hover:bg-rose-900/60 text-rose-300 border border-rose-800/50 rounded-xl text-[12.5px] font-medium transition-colors flex items-center gap-1.5">
        <i class="fa-solid fa-file-pdf text-[12px]"></i> Print / PDF
      </a>
    </div>
  </form>
</div>

<!-- ════════════════════════════ STUDENT DATA TABLE ════════════════════════════ -->
<div class="glass-card rounded-2xl overflow-hidden shadow-2xl">
  <div class="overflow-x-auto">
    <table class="w-full text-[13px] text-left border-collapse">
      <thead>
        <tr class="border-b border-white/10 bg-white/[0.02]">
          <th class="px-4 py-4 font-mono font-semibold text-[11px] text-white/45 uppercase tracking-wider text-center w-16">রোল (Roll)</th>
          <th class="px-5 py-4 font-mono font-semibold text-[11px] text-white/45 uppercase tracking-wider">শিক্ষার্থীর নাম (English & Bangla)</th>
          <th class="px-4 py-4 font-mono font-semibold text-[11px] text-white/45 uppercase tracking-wider">যোগাযোগ (Contact)</th>
          <th class="px-4 py-4 font-mono font-semibold text-[11px] text-white/45 uppercase tracking-wider">ব্যাচ ও সেশন</th>
          <th class="px-4 py-4 font-mono font-semibold text-[11px] text-white/45 uppercase tracking-wider">বিভাগ (Department)</th>
          <th class="px-4 py-4 font-mono font-semibold text-[11px] text-white/45 uppercase tracking-wider text-center">তথ্য স্ট্যাটাস</th>
          <th class="px-5 py-4 font-mono font-semibold text-[11px] text-white/45 uppercase tracking-wider text-right pr-6">অ্যাকশন</th>
        </tr>
      </thead>
      <tbody class="divide-y divide-white/5">
        <?php if (empty($students)): ?>
        <tr>
          <td colspan="7" class="px-6 py-16 text-center text-white/40">
            <div class="max-w-md mx-auto space-y-3">
              <div class="w-14 h-14 rounded-2xl bg-white/5 border border-white/10 flex items-center justify-center text-2xl text-white/30 mx-auto">
                <i class="fa-solid fa-user-slash"></i>
              </div>
              <div class="text-[16px] font-bold text-white">কোনো শিক্ষার্থী পাওয়া যায়নি</div>
              <p class="text-[12.5px] text-white/50">বর্তমান ফিল্টারে কোনো তথ্য মেলেনি। দয়া করে ফিল্টার পরিবর্তন করুন অথবা নতুন শিক্ষার্থী যোগ করুন।</p>
              <div class="pt-2 flex justify-center gap-2">
                <a href="<?= url('/admin/students') ?>" class="px-4 py-2 rounded-xl bg-white/10 text-white text-[12.5px] hover:bg-white/20">ফিল্টার রিসেট করুন</a>
                <button onclick="openAddModal()" class="px-4 py-2 rounded-xl bg-[#A22638] text-white text-[12.5px] hover:bg-[#800020]">নতুন শিক্ষার্থী যোগ</button>
              </div>
            </div>
          </td>
        </tr>
        <?php else: ?>
        <?php foreach ($students as $s): ?>
        <?php 
          $hasMissing = empty($s['roll']) || empty($s['name_english']) || empty($s['name_bangla']) || empty($s['mobile']) || empty($s['guardian_mobile']) || empty($s['batch']) || empty($s['session']) || empty($s['department']);
          $initial = mb_substr(trim($s['name_english'] ?? 'S'), 0, 1, 'UTF-8');
          $isL = str_starts_with($s['batch'] ?? '', 'L-');
        ?>
        <tr class="hover:bg-white/[0.03] transition-colors group <?= $hasMissing ? 'bg-amber-500/[0.015]' : '' ?>">
          
          <!-- Roll -->
          <td class="px-4 py-4 text-center">
            <?php if (empty($s['roll'])): ?>
              <span class="inline-block px-2 py-0.5 rounded-md text-[10.5px] font-sans font-medium bg-rose-500/20 text-rose-300 border border-rose-500/30">Missing</span>
            <?php else: ?>
              <span class="inline-block px-2.5 py-1 rounded-lg text-[12px] font-mono font-bold bg-white/5 text-white/90 border border-white/10 group-hover:border-white/20">
                <?= e($s['roll']) ?>
              </span>
            <?php endif; ?>
          </td>

          <!-- Name (English + Bangla + Avatar) -->
          <td class="px-5 py-4">
            <div class="flex items-center gap-3">
              <div class="w-9 h-9 rounded-full flex items-center justify-center font-bold text-[13px] shrink-0 uppercase shadow-inner"
                   style="background:linear-gradient(135deg, <?= $isL ? 'rgba(162,38,56,0.3), rgba(128,0,32,0.4)' : 'rgba(16,185,129,0.2), rgba(5,150,105,0.3)' ?>);color:<?= $isL ? '#E58E97' : '#6EE7B7' ?>;border:1px solid <?= $isL ? 'rgba(162,38,56,0.4)' : 'rgba(16,185,129,0.3)' ?>;">
                <?= e($initial) ?>
              </div>
              <div>
                <div class="font-semibold text-white group-hover:text-[#E58E97] transition-colors text-[13.5px]">
                  <?= e($s['name_english']) ?>
                </div>
                <div class="text-[12px] text-white/50 mt-0.5 font-sans">
                  <?php if (!empty($s['name_bangla'])): ?>
                    <?= e($s['name_bangla']) ?>
                  <?php else: ?>
                    <span class="text-white/30 italic text-[11px]">(বাংলা নাম নেই)</span>
                  <?php endif; ?>
                </div>
              </div>
            </div>
          </td>

          <!-- Contact (Mobile + Guardian Mobile) -->
          <td class="px-4 py-4 font-mono text-[12.5px]">
            <div>
              <?php if (!empty($s['mobile'])): ?>
                <a href="tel:<?= e($s['mobile']) ?>" class="text-white/80 hover:text-emerald-400 transition-colors flex items-center gap-1.5" title="কল করুন">
                  <i class="fa-solid fa-phone text-[10px] text-emerald-400"></i>
                  <span><?= e($s['mobile']) ?></span>
                </a>
              <?php else: ?>
                <span class="text-rose-400/80 text-[11.5px] italic">মোবাইল নেই</span>
              <?php endif; ?>
            </div>
            <div class="mt-1 text-[11.5px] text-white/40 flex items-center gap-1.5">
              <i class="fa-solid fa-user-shield text-[10px] text-amber-400/70"></i>
              <span><?= !empty($s['guardian_mobile']) ? e($s['guardian_mobile']) : 'অভিভাবকের নম্বর নেই' ?></span>
            </div>
          </td>

          <!-- Batch & Session -->
          <td class="px-4 py-4">
            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11.5px] font-mono font-bold"
                  style="background:<?= $isL ? 'rgba(162,38,56,0.18)' : 'rgba(16,185,129,0.18)' ?>;color:<?= $isL ? '#E58E97' : '#34D399' ?>;border:1px solid <?= $isL ? 'rgba(162,38,56,0.35)' : 'rgba(16,185,129,0.35)' ?>;">
              <span class="w-1.5 h-1.5 rounded-full <?= $isL ? 'bg-[#E58E97]' : 'bg-emerald-400' ?>"></span>
              <?= e($s['batch']) ?>
            </span>
            <div class="text-[11.5px] font-mono text-white/45 mt-1">
              সেশন: <?= e($s['session']) ?>
            </div>
          </td>

          <!-- Department -->
          <td class="px-4 py-4 text-xs">
            <div class="text-white/80 font-medium truncate max-w-[200px]" title="<?= e($s['department']) ?>">
              <?= e($s['department']) ?>
            </div>
          </td>

          <!-- Completeness Status -->
          <td class="px-4 py-4 text-center">
            <?php if ($hasMissing): ?>
              <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[11px] font-medium bg-amber-500/15 text-amber-300 border border-amber-500/30" title="কিছু তথ্য অনুপস্থিত রয়েছে">
                <i class="fa-solid fa-circle-exclamation text-[10px]"></i> অসম্পূর্ণ
              </span>
            <?php else: ?>
              <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[11px] font-medium bg-emerald-500/15 text-emerald-300 border border-emerald-500/30">
                <i class="fa-solid fa-check text-[10px]"></i> সম্পূর্ণ
              </span>
            <?php endif; ?>
          </td>

          <!-- Actions -->
          <td class="px-5 py-4 text-right pr-6">
            <div class="flex items-center justify-end gap-2">
              <!-- Edit Button -->
              <button onclick="openEditModal(<?= htmlspecialchars(json_encode($s), ENT_QUOTES, 'UTF-8') ?>)"
                      class="px-2.5 py-1.5 bg-white/10 hover:bg-white/20 text-white border border-white/10 rounded-xl text-[12px] font-medium transition-all hover:scale-105 flex items-center gap-1.5 shadow-sm"
                      title="তথ্য সংশোধন করুন">
                <i class="fa-solid fa-pen text-[10px] text-[#E58E97]"></i>
                <span class="hidden sm:inline">Edit</span>
              </button>
              
              <!-- Delete Button -->
              <form method="POST" action="<?= url('/admin/students/' . $s['id'] . '/delete') ?>" 
                    onsubmit="return confirm('আপনি কি নিশ্চিত যে <?= e(addslashes($s['name_english'])) ?>-এর রেকর্ড মুছে ফেলতে চান?');" class="inline">
                <?= csrf_field() ?>
                <button type="submit" 
                        class="px-2.5 py-1.5 bg-rose-950/40 hover:bg-rose-900 text-rose-300 border border-rose-800/40 rounded-xl text-[12px] font-medium transition-all hover:scale-105 flex items-center gap-1.5 shadow-sm"
                        title="রেকর্ড মুছুন">
                  <i class="fa-solid fa-trash-can text-[10px]"></i>
                  <span class="hidden sm:inline">Delete</span>
                </button>
              </form>
            </div>
          </td>
        </tr>
        <?php endforeach; ?>
        <?php endif; ?>
      </tbody>
    </table>
  </div>

  <!-- Pagination Bottom Bar -->
  <div class="px-6 py-4 border-t border-white/10 bg-white/[0.01] flex flex-col sm:flex-row items-center justify-between gap-4">
    <div class="text-[12.5px] text-white/50">
      সর্বমোট <strong class="text-white"><?= number_format($pagination['total']) ?></strong> টির মধ্যে 
      <span class="text-white"><?= ($pagination['total'] > 0 ? ($pagination['current_page'] - 1) * $pagination['per_page'] + 1 : 0) ?></span> 
      থেকে 
      <span class="text-white"><?= min($pagination['current_page'] * $pagination['per_page'], $pagination['total']) ?></span> পর্যন্ত প্রদর্শিত
    </div>

    <?php if ($pagination['last_page'] > 1): ?>
    <div class="flex items-center gap-1.5">
      <!-- Previous Page -->
      <?php if ($pagination['current_page'] > 1): ?>
      <a href="<?= url('/admin/students?' . http_build_query(array_merge($_GET, ['page' => $pagination['current_page'] - 1]))) ?>"
         class="px-3 py-1.5 rounded-lg text-[12.5px] bg-white/5 hover:bg-white/10 text-white border border-white/10 transition-colors flex items-center gap-1">
        <i class="fa-solid fa-chevron-left text-[10px]"></i>
      </a>
      <?php endif; ?>

      <?php 
        $startP = max(1, $pagination['current_page'] - 2);
        $endP   = min($pagination['last_page'], $pagination['current_page'] + 2);
      ?>

      <?php for ($p = $startP; $p <= $endP; $p++): ?>
      <a href="<?= url('/admin/students?' . http_build_query(array_merge($_GET, ['page' => $p]))) ?>"
         class="w-8 h-8 rounded-lg flex items-center justify-center text-[12.5px] font-mono transition-all <?= $p === $pagination['current_page'] ? 'bg-[#A22638] text-white font-bold shadow-md shadow-[#A22638]/30 border border-[#E58E97]/40' : 'bg-white/5 text-white/60 hover:bg-white/10 hover:text-white border border-white/10' ?>">
        <?= $p ?>
      </a>
      <?php endfor; ?>

      <!-- Next Page -->
      <?php if ($pagination['current_page'] < $pagination['last_page']): ?>
      <a href="<?= url('/admin/students?' . http_build_query(array_merge($_GET, ['page' => $pagination['current_page'] + 1]))) ?>"
         class="px-3 py-1.5 rounded-lg text-[12.5px] bg-white/5 hover:bg-white/10 text-white border border-white/10 transition-colors flex items-center gap-1">
        <i class="fa-solid fa-chevron-right text-[10px]"></i>
      </a>
      <?php endif; ?>
    </div>
    <?php endif; ?>
  </div>
</div>

<!-- ════════════════════════════ ADD STUDENT / NEW BATCH MODAL ════════════════════════════ -->
<div id="addStudentModal" class="fixed inset-0 bg-black/85 backdrop-blur-md z-50 hidden flex items-center justify-center p-4 overflow-y-auto">
  <div class="bg-[#151619] border border-white/15 rounded-3xl w-full max-w-xl p-6 lg:p-7 shadow-2xl space-y-6 my-8">
    <div class="flex items-center justify-between border-b border-white/10 pb-4">
      <div>
        <h3 class="font-serif text-[18px] font-bold text-white flex items-center gap-2.5">
          <span class="w-8 h-8 rounded-xl bg-[#A22638]/30 border border-[#A22638]/40 flex items-center justify-center text-[#E58E97]">
            <i class="fa-solid fa-user-plus text-sm"></i>
          </span>
          নতুন শিক্ষার্থী ও ব্যাচ যোগ করুন
        </h3>
        <p class="text-[12px] text-white/50 mt-1">নতুন ব্যাচের নাম লিখলে তা স্বয়ংক্রিয়ভাবে সিস্টেমের ব্যাচ তালিকায় যুক্ত হবে।</p>
      </div>
      <button onclick="closeAddModal()" class="w-8 h-8 rounded-full bg-white/5 hover:bg-white/10 text-white/60 hover:text-white flex items-center justify-center transition-colors text-lg">&times;</button>
    </div>

    <form method="POST" action="<?= url('/admin/students/store') ?>" class="space-y-4">
      <?= csrf_field() ?>

      <!-- Batch Selection Box -->
      <div class="p-4 rounded-2xl bg-white/[0.02] border border-white/10 space-y-2.5">
        <div class="flex items-center justify-between">
          <label class="block text-[12px] font-mono font-semibold text-[#E58E97] flex items-center gap-1.5">
            <i class="fa-solid fa-layer-group"></i> ব্যাচের নাম (Batch Name) <span class="text-rose-400">*</span>
          </label>
          <span class="text-[11px] text-white/40">ড্রপডাউন থেকে বাছুন বা নতুন লিখুন</span>
        </div>
        <input type="text" name="batch" id="add_batch" list="batch_datalist" required placeholder="যেমন: L-10, F-6 ইত্যাদি"
               class="w-full px-4 py-2.5 bg-white/5 border border-white/10 rounded-xl text-white text-[13.5px] font-mono focus:outline-none focus:border-[#A22638] focus:ring-1 focus:ring-[#A22638]">
        <datalist id="batch_datalist">
          <?php foreach ($batches as $b): ?>
          <option value="<?= e($b) ?>"><?= e($b) ?> (Existing Batch)</option>
          <?php endforeach; ?>
        </datalist>

        <!-- Quick Select Pills -->
        <div class="flex flex-wrap items-center gap-1.5 pt-1">
          <span class="text-[11px] text-white/40">কুইক সিলেক্ট:</span>
          <?php foreach (array_slice($batches, 0, 7) as $qb): ?>
          <button type="button" onclick="document.getElementById('add_batch').value='<?= e($qb) ?>'"
                  class="px-2 py-0.5 bg-white/5 hover:bg-white/15 text-white/70 hover:text-white text-[11px] font-mono rounded-md border border-white/10 transition-colors">
            <?= e($qb) ?>
          </button>
          <?php endforeach; ?>
        </div>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <!-- Session -->
        <div>
          <label class="block text-[12px] font-mono text-white/60 mb-1">Session (সেশন) <span class="text-rose-400">*</span></label>
          <input type="text" name="session" list="session_datalist" required placeholder="যেমন: 2026-27"
                 class="w-full px-3.5 py-2.5 bg-white/5 border border-white/10 rounded-xl text-white text-[13px] font-mono focus:outline-none focus:border-[#A22638]">
          <datalist id="session_datalist">
            <?php foreach ($sessions as $s): ?>
            <option value="<?= e($s) ?>">
            <?php endforeach; ?>
          </datalist>
        </div>

        <!-- Department -->
        <div>
          <label class="block text-[12px] font-mono text-white/60 mb-1">Department (বিভাগ) <span class="text-rose-400">*</span></label>
          <input type="text" name="department" list="dept_datalist" required placeholder="যেমন: Laboratory"
                 class="w-full px-3.5 py-2.5 bg-white/5 border border-white/10 rounded-xl text-white text-[13px] focus:outline-none focus:border-[#A22638]">
          <datalist id="dept_datalist">
            <?php foreach ($depts as $d): ?>
            <option value="<?= e($d) ?>">
            <?php endforeach; ?>
          </datalist>
        </div>

        <!-- Roll -->
        <div>
          <label class="block text-[12px] font-mono text-white/60 mb-1">Roll / Class ID</label>
          <input type="text" name="roll" placeholder="যেমন: 1"
                 class="w-full px-3.5 py-2.5 bg-white/5 border border-white/10 rounded-xl text-white text-[13px] font-mono focus:outline-none focus:border-[#A22638]">
        </div>

        <!-- Name English -->
        <div>
          <label class="block text-[12px] font-mono text-white/60 mb-1">Name (English) <span class="text-rose-400">*</span></label>
          <input type="text" name="name_english" required placeholder="Full Name in English"
                 class="w-full px-3.5 py-2.5 bg-white/5 border border-white/10 rounded-xl text-white text-[13px] focus:outline-none focus:border-[#A22638]">
        </div>

        <!-- Name Bangla -->
        <div>
          <label class="block text-[12px] font-mono text-white/60 mb-1">Name (Bangla)</label>
          <input type="text" name="name_bangla" placeholder="বাংলা নাম (ঐচ্ছিক)"
                 class="w-full px-3.5 py-2.5 bg-white/5 border border-white/10 rounded-xl text-white text-[13px] focus:outline-none focus:border-[#A22638]">
        </div>

        <!-- Mobile -->
        <div>
          <label class="block text-[12px] font-mono text-white/60 mb-1">Mobile Number</label>
          <input type="text" name="mobile" placeholder="01711..."
                 class="w-full px-3.5 py-2.5 bg-white/5 border border-white/10 rounded-xl text-white text-[13px] font-mono focus:outline-none focus:border-[#A22638]">
        </div>

        <!-- Guardian Mobile -->
        <div class="md:col-span-2">
          <label class="block text-[12px] font-mono text-white/60 mb-1">Guardian Mobile (অভিভাবকের মোবাইল)</label>
          <input type="text" name="guardian_mobile" placeholder="01811..."
                 class="w-full px-3.5 py-2.5 bg-white/5 border border-white/10 rounded-xl text-white text-[13px] font-mono focus:outline-none focus:border-[#A22638]">
        </div>
      </div>

      <div class="flex items-center justify-end gap-3 pt-4 border-t border-white/10">
        <button type="button" onclick="closeAddModal()" class="px-5 py-2.5 bg-white/10 hover:bg-white/20 text-white rounded-xl text-[13px] font-semibold transition-colors">
          Cancel
        </button>
        <button type="submit" class="px-6 py-2.5 rounded-xl text-[13px] font-semibold text-white transition-all shadow-lg shadow-[#A22638]/25 flex items-center gap-2"
                style="background:linear-gradient(135deg,#A22638,#800020);">
          <i class="fa-solid fa-check"></i> সংরক্ষণ করুন (Save)
        </button>
      </div>
    </form>
  </div>
</div>

<!-- ════════════════════════════ IMPORT EXCEL / CSV MODAL ════════════════════════════ -->
<div id="importModal" class="fixed inset-0 bg-black/85 backdrop-blur-md z-50 hidden flex items-center justify-center p-4 overflow-y-auto">
  <div class="bg-[#151619] border border-white/15 rounded-3xl w-full max-w-2xl p-6 lg:p-7 shadow-2xl space-y-5 my-8">
    <div class="flex items-center justify-between border-b border-white/10 pb-4">
      <div>
        <h3 class="font-serif text-[18px] font-bold text-white flex items-center gap-2.5">
          <span class="w-8 h-8 rounded-xl bg-emerald-500/20 border border-emerald-500/30 flex items-center justify-center text-emerald-400">
            <i class="fa-solid fa-file-import text-sm"></i>
          </span>
          এক্সেল / সিএসভি থেকে ব্যাচ ডাটা ইমপোর্ট
        </h3>
        <p class="text-[12px] text-white/50 mt-1">মাইক্রোসফট এক্সেল (.xlsx) বা সিএসভি (.csv) ফাইল থেকে শিক্ষার্থী ডাটা স্বয়ংক্রিয়ভাবে ইমপোর্ট করুন।</p>
      </div>
      <button onclick="closeImportModal()" class="w-8 h-8 rounded-full bg-white/5 hover:bg-white/10 text-white/60 hover:text-white flex items-center justify-center transition-colors text-lg">&times;</button>
    </div>

    <!-- Instructions Banner -->
    <div class="p-4 rounded-2xl bg-emerald-500/10 border border-emerald-500/20 text-[12.5px] text-emerald-200/90 space-y-2.5">
      <div class="font-semibold flex items-center justify-between">
        <span class="flex items-center gap-2"><i class="fa-solid fa-circle-info text-emerald-400"></i> ফাইলের কলাম বিন্যাস:</span>
        <a href="<?= url('/admin/students/sample-template') ?>" class="inline-flex items-center gap-1.5 text-[11.5px] text-amber-300 hover:text-white bg-amber-500/20 px-3 py-1.5 rounded-xl border border-amber-500/30 transition-colors">
          <i class="fa-solid fa-download text-[10px]"></i> স্যাম্পল টেমপ্লেট
        </a>
      </div>
      <div class="font-mono text-[11px] bg-black/40 p-2.5 rounded-xl border border-white/10 text-white/80 overflow-x-auto">
        Roll, Name (English), Name (Bangla), Mobile, Guardian Mobile, Batch, Session, Department
      </div>
      <p class="text-[11.5px] text-white/50">
        * ফাইলে Batch বা Session কলাম না থাকলেও নিচের ডিফল্ট ইনপুট থেকে ডাটা সেভ হয়ে যাবে।
      </p>
    </div>

    <form method="POST" action="<?= url('/admin/students/import') ?>" enctype="multipart/form-data" id="importForm" class="space-y-4">
      <?= csrf_field() ?>

      <!-- Dropzone File Picker -->
      <div class="border-2 border-dashed border-white/20 hover:border-emerald-500/50 rounded-2xl p-7 text-center transition-all bg-white/[0.015] hover:bg-emerald-500/[0.02]" id="dropzoneBox">
        <i class="fa-solid fa-cloud-arrow-up text-4xl text-emerald-400 mb-2.5 block"></i>
        <div class="text-[14px] text-white font-semibold mb-1">এক্সেল বা সিএসভি ফাইল সিলেক্ট করুন</div>
        <div class="text-[12px] text-white/40 mb-4">.xlsx, .csv ফাইল ড্র্যাগ করে এখানে ড্রপ করুন অথবা ব্রাউজ করুন</div>
        
        <label class="inline-flex items-center gap-2 px-5 py-2.5 bg-white/10 hover:bg-white/20 text-white rounded-xl text-[13px] font-medium cursor-pointer transition-all border border-white/15 hover:border-white/30 shadow-sm">
          <i class="fa-solid fa-folder-open text-[#E58E97]"></i> ফাইল নির্বাচন করুন
          <input type="file" name="file" id="import_file_input" accept=".xlsx,.xls,.csv,.txt" required class="hidden" onchange="handleFileSelected(this)">
        </label>
        <div id="selected_file_info" class="mt-3 text-[12.5px] text-emerald-300 hidden font-mono"></div>
      </div>

      <!-- Defaults (Batch, Session, Dept) -->
      <div class="p-4 rounded-2xl bg-white/[0.02] border border-white/10 space-y-3">
        <div class="text-[12px] font-mono font-semibold text-white/80 flex items-center gap-1.5">
          <i class="fa-solid fa-sliders text-[#E58E97]"></i> ডিফল্ট তথ্য (যদি ফাইলে এই কলামগুলো না থাকে বা নির্দিষ্ট করতে চান):
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
          <div>
            <label class="block text-[11.5px] font-mono text-white/50 mb-1">ব্যাচ (Default Batch)</label>
            <input type="text" name="default_batch" list="batch_datalist" placeholder="যেমন: L-10"
                   class="w-full px-3 py-2 bg-white/5 border border-white/10 rounded-xl text-white text-[12.5px] font-mono focus:outline-none focus:border-[#A22638]">
          </div>
          <div>
            <label class="block text-[11.5px] font-mono text-white/50 mb-1">সেশন (Default Session)</label>
            <input type="text" name="default_session" list="session_datalist" placeholder="যেমন: 2026-27"
                   class="w-full px-3 py-2 bg-white/5 border border-white/10 rounded-xl text-white text-[12.5px] focus:outline-none focus:border-[#A22638]">
          </div>
          <div>
            <label class="block text-[11.5px] font-mono text-white/50 mb-1">বিভাগ (Department)</label>
            <input type="text" name="default_department" list="dept_datalist" placeholder="যেমন: Laboratory"
                   class="w-full px-3 py-2 bg-white/5 border border-white/10 rounded-xl text-white text-[12.5px] focus:outline-none focus:border-[#A22638]">
          </div>
        </div>
      </div>

      <!-- Duplicate Handling -->
      <div class="p-4 rounded-2xl bg-white/[0.02] border border-white/10 space-y-2.5">
        <label class="block text-[12px] font-mono font-semibold text-white/80 flex items-center gap-1.5">
          <i class="fa-solid fa-clone text-amber-400"></i> ডুপ্লিকেট রেকর্ড সমাধান (Duplicate Policy):
        </label>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-3 text-[12px]">
          <label class="flex items-center gap-2 p-3 rounded-xl bg-white/5 border border-white/10 cursor-pointer hover:bg-white/10 transition-colors">
            <input type="radio" name="duplicate_action" value="skip" checked class="text-[#A22638] focus:ring-0">
            <span class="text-white">ডুপ্লিকেট বাদ দিন (Skip)</span>
          </label>
          <label class="flex items-center gap-2 p-3 rounded-xl bg-white/5 border border-white/10 cursor-pointer hover:bg-white/10 transition-colors">
            <input type="radio" name="duplicate_action" value="update" class="text-[#A22638] focus:ring-0">
            <span class="text-white">তথ্য আপডেট করুন (Update)</span>
          </label>
          <label class="flex items-center gap-2 p-3 rounded-xl bg-white/5 border border-white/10 cursor-pointer hover:bg-white/10 transition-colors">
            <input type="radio" name="duplicate_action" value="insert" class="text-[#A22638] focus:ring-0">
            <span class="text-white">সব ইনসার্ট করুন (Insert)</span>
          </label>
        </div>
      </div>

      <div class="flex items-center justify-end gap-3 pt-4 border-t border-white/10">
        <button type="button" onclick="closeImportModal()" class="px-5 py-2.5 bg-white/10 hover:bg-white/20 text-white rounded-xl text-[13px] font-semibold transition-colors">
          Cancel
        </button>
        <button type="submit" id="importSubmitBtn" 
                class="px-6 py-2.5 bg-emerald-600 hover:bg-emerald-500 text-white rounded-xl text-[13px] font-semibold transition-all shadow-lg shadow-emerald-950/40 flex items-center gap-2">
          <i class="fa-solid fa-cloud-arrow-up"></i> ইমপোর্ট শুরু করুন (Start Import)
        </button>
      </div>
    </form>
  </div>
</div>

<!-- ════════════════════════════ EDIT STUDENT MODAL ════════════════════════════ -->
<div id="editStudentModal" class="fixed inset-0 bg-black/85 backdrop-blur-md z-50 hidden flex items-center justify-center p-4 overflow-y-auto">
  <div class="bg-[#151619] border border-white/15 rounded-3xl w-full max-w-xl p-6 lg:p-7 shadow-2xl space-y-5 my-8">
    <div class="flex items-center justify-between border-b border-white/10 pb-4">
      <h3 class="font-serif text-[18px] font-bold text-white flex items-center gap-2.5">
        <span class="w-8 h-8 rounded-xl bg-[#A22638]/20 border border-[#A22638]/30 flex items-center justify-center text-[#E58E97]">
          <i class="fa-solid fa-user-pen text-sm"></i>
        </span>
        শিক্ষার্থীর তথ্য সংশোধন (Edit Student Record)
      </h3>
      <button onclick="closeEditModal()" class="w-8 h-8 rounded-full bg-white/5 hover:bg-white/10 text-white/60 hover:text-white flex items-center justify-center transition-colors text-lg">&times;</button>
    </div>

    <form id="editStudentForm" method="POST" action="" class="space-y-4">
      <?= csrf_field() ?>
      <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <!-- Roll -->
        <div>
          <label class="block text-[12px] font-mono text-white/60 mb-1">Roll / Class ID</label>
          <input type="text" name="roll" id="edit_roll" placeholder="e.g. 101"
                 class="w-full px-3.5 py-2.5 bg-white/5 border border-white/10 rounded-xl text-white text-[13px] font-mono focus:outline-none focus:border-[#A22638]">
        </div>

        <!-- Name English -->
        <div>
          <label class="block text-[12px] font-mono text-white/60 mb-1">Name (English) <span class="text-rose-400">*</span></label>
          <input type="text" name="name_english" id="edit_name_english" required
                 class="w-full px-3.5 py-2.5 bg-white/5 border border-white/10 rounded-xl text-white text-[13px] focus:outline-none focus:border-[#A22638]">
        </div>

        <!-- Name Bangla -->
        <div>
          <label class="block text-[12px] font-mono text-white/60 mb-1">Name (Bangla)</label>
          <input type="text" name="name_bangla" id="edit_name_bangla" placeholder="বাংলা নাম"
                 class="w-full px-3.5 py-2.5 bg-white/5 border border-white/10 rounded-xl text-white text-[13px] focus:outline-none focus:border-[#A22638]">
        </div>

        <!-- Mobile -->
        <div>
          <label class="block text-[12px] font-mono text-white/60 mb-1">Mobile Number</label>
          <input type="text" name="mobile" id="edit_mobile" placeholder="01711..."
                 class="w-full px-3.5 py-2.5 bg-white/5 border border-white/10 rounded-xl text-white text-[13px] font-mono focus:outline-none focus:border-[#A22638]">
        </div>

        <!-- Guardian Mobile -->
        <div>
          <label class="block text-[12px] font-mono text-white/60 mb-1">Guardian Mobile</label>
          <input type="text" name="guardian_mobile" id="edit_guardian_mobile" placeholder="01811..."
                 class="w-full px-3.5 py-2.5 bg-white/5 border border-white/10 rounded-xl text-white text-[13px] font-mono focus:outline-none focus:border-[#A22638]">
        </div>

        <!-- Batch -->
        <div>
          <label class="block text-[12px] font-mono text-white/60 mb-1">Batch <span class="text-rose-400">*</span></label>
          <input type="text" name="batch" id="edit_batch" list="batch_datalist" required placeholder="e.g. L-01"
                 class="w-full px-3.5 py-2.5 bg-white/5 border border-white/10 rounded-xl text-white text-[13px] font-mono focus:outline-none focus:border-[#A22638]">
        </div>

        <!-- Session -->
        <div>
          <label class="block text-[12px] font-mono text-white/60 mb-1">Session <span class="text-rose-400">*</span></label>
          <input type="text" name="session" id="edit_session" list="session_datalist" required placeholder="e.g. 2018-2019"
                 class="w-full px-3.5 py-2.5 bg-white/5 border border-white/10 rounded-xl text-white text-[13px] font-mono focus:outline-none focus:border-[#A22638]">
        </div>

        <!-- Department -->
        <div>
          <label class="block text-[12px] font-mono text-white/60 mb-1">Department <span class="text-rose-400">*</span></label>
          <input type="text" name="department" id="edit_department" list="dept_datalist" required placeholder="e.g. Laboratory"
                 class="w-full px-3.5 py-2.5 bg-white/5 border border-white/10 rounded-xl text-white text-[13px] focus:outline-none focus:border-[#A22638]">
        </div>
      </div>

      <div class="flex items-center justify-end gap-3 pt-4 border-t border-white/10">
        <button type="button" onclick="closeEditModal()" class="px-5 py-2.5 bg-white/10 hover:bg-white/20 text-white rounded-xl text-[13px] font-semibold transition-colors">
          Cancel
        </button>
        <button type="submit" class="px-6 py-2.5 bg-[#A22638] hover:bg-[#800020] text-white rounded-xl text-[13px] font-semibold transition-all shadow-lg shadow-[#A22638]/20">
          Save Changes (সংরক্ষণ করুন)
        </button>
      </div>
    </form>
  </div>
</div>

<script>
// Open and close Add Modal
function openAddModal() {
  document.getElementById('addStudentModal').classList.remove('hidden');
}
function closeAddModal() {
  document.getElementById('addStudentModal').classList.add('hidden');
}

// Open and close Import Modal
function openImportModal() {
  document.getElementById('importModal').classList.remove('hidden');
}
function closeImportModal() {
  document.getElementById('importModal').classList.add('hidden');
}

// File selected preview
function handleFileSelected(input) {
  const infoEl = document.getElementById('selected_file_info');
  if (input.files && input.files[0]) {
    const file = input.files[0];
    const sizeKB = (file.size / 1024).toFixed(1);
    infoEl.innerHTML = '<i class="fa-solid fa-file-circle-check"></i> ' + file.name + ' (' + sizeKB + ' KB)';
    infoEl.classList.remove('hidden');
  } else {
    infoEl.classList.add('hidden');
  }
}

// Open and close Edit Modal
function openEditModal(student) {
  document.getElementById('editStudentForm').action = '<?= url('/admin/students/') ?>' + student.id + '/update';
  document.getElementById('edit_roll').value = student.roll || '';
  document.getElementById('edit_name_english').value = student.name_english || '';
  document.getElementById('edit_name_bangla').value = student.name_bangla || '';
  document.getElementById('edit_mobile').value = student.mobile || '';
  document.getElementById('edit_guardian_mobile').value = student.guardian_mobile || '';
  document.getElementById('edit_batch').value = student.batch || '';
  document.getElementById('edit_session').value = student.session || '';
  document.getElementById('edit_department').value = student.department || '';
  
  document.getElementById('editStudentModal').classList.remove('hidden');
}
function closeEditModal() {
  document.getElementById('editStudentModal').classList.add('hidden');
}

// Loading state on form submit
document.getElementById('importForm').addEventListener('submit', function() {
  const btn = document.getElementById('importSubmitBtn');
  btn.disabled = true;
  btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> ইমপোর্ট হচ্ছে, অনুগ্রহ করে অপেক্ষা করুন...';
});
</script>
