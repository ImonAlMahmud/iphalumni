<?php
/**
 * Admin Email Templates Preview View
 * Variables: $templates, $selectedKey, $activeTemplate, $renderedHtml
 */
?>
<div class="space-y-6 font-['Kalpurush']">
  <div class="flex items-center justify-between gap-4">
    <div>
      <h2 class="text-[19px] font-bold text-white mb-1">✉ System Event Email Templates Preview (ইমেইল টেমপ্লেট প্রিভিউ)</h2>
      <p class="text-[13.5px] text-white/50">সিস্টেমের বিভিন্ন ইভেন্টে (যেমন: কন্টাক্ট রিকোয়েস্ট, এপ্রুভাল, জবে আবেদন ইত্যাদি) কী ধরণের ইমেইল প্রেরিত হয় তা লাইভ ভিউ করুন।</p>
    </div>
    <div class="px-3.5 py-1.5 rounded-xl bg-white/10 text-white font-mono text-[12px] border border-white/10">
      Active Events: <?= count($templates) ?>
    </div>
  </div>

  <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
    
    <!-- Left Navigation Menu (4 cols) -->
    <div class="lg:col-span-4 space-y-3">
      <h3 class="font-bold text-white/70 text-[12px] uppercase font-mono tracking-wider px-1">Select Event Template</h3>
      <div class="space-y-2">
        <?php foreach ($templates as $key => $tpl): ?>
        <a href="<?= url('/admin/email-templates?key=' . $key) ?>"
           class="block p-4 rounded-2xl border transition-all <?= $selectedKey === $key ? 'bg-[#800020] text-white border-rose-400/40 shadow-lg' : 'bg-white/5 text-white/80 border-white/10 hover:bg-white/10' ?>">
          <div class="flex items-center justify-between gap-2 mb-1">
            <span class="font-bold text-[14px]"><?= e($tpl['name']) ?></span>
            <?php if ($selectedKey === $key): ?>
            <span class="text-[11px] font-mono bg-white/20 px-2 py-0.5 rounded-full">ACTIVE</span>
            <?php endif; ?>
          </div>
          <p class="text-[11.5px] opacity-75 line-clamp-2"><?= e($tpl['trigger']) ?></p>
        </a>
        <?php endforeach; ?>
      </div>
    </div>

    <!-- Right Live Email Render Area (8 cols) -->
    <div class="lg:col-span-8 space-y-5">
      <div class="p-5 rounded-2xl bg-black/40 border border-white/10 space-y-2">
        <div class="flex items-center gap-2 text-[12.5px] text-white/70">
          <span class="font-mono font-bold text-amber-400">EVENT TRIGGER:</span>
          <span><?= e($activeTemplate['trigger']) ?></span>
        </div>
        <div class="flex items-center gap-2 text-[13px] text-white">
          <span class="font-mono font-bold text-emerald-400">SUBJECT LINE:</span>
          <span class="font-semibold text-rose-200"><?= e($activeTemplate['subject']) ?></span>
        </div>
      </div>

      <!-- Iframe HTML Rendering Container -->
      <div class="rounded-3xl overflow-hidden border border-white/15 shadow-2xl bg-white">
        <div class="bg-gray-100 px-4 py-2.5 border-b border-gray-200 flex items-center justify-between text-gray-600 text-[12px] font-mono">
          <div class="flex items-center gap-2">
            <span class="w-3 h-3 rounded-full bg-rose-400 inline-block"></span>
            <span class="w-3 h-3 rounded-full bg-amber-400 inline-block"></span>
            <span class="w-3 h-3 rounded-full bg-emerald-400 inline-block"></span>
            <span class="ml-2 font-bold text-gray-700">Responsive Email Preview Canvas</span>
          </div>
          <span>Width: 100% (Responsive)</span>
        </div>

        <iframe id="email_canvas" class="w-full h-[650px] border-0 bg-[#f6f6f6]"></iframe>
      </div>
    </div>

  </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function() {
  const iframe = document.getElementById('email_canvas');
  if (iframe) {
    const doc = iframe.contentWindow.document;
    doc.open();
    doc.write(<?= json_encode($renderedHtml) ?>);
    doc.close();
  }
});
</script>
