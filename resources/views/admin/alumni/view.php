<?php
/**
 * Admin Alumni Comprehensive Detail View — Complete Information Overview
 * Variables: $alumni, $education, $employment, $approvalHistory
 */
?>
<div class="mb-6 flex items-center justify-between">
  <a href="<?= url('/admin/alumni') ?>" class="text-[13px] text-white/60 hover:text-white inline-flex items-center gap-1.5 transition-colors">
    ← Back to Alumni List
  </a>

  <div class="flex items-center gap-2">
    <a href="<?= url('/admin/alumni/' . $alumni['id'] . '/card-svg/zip') ?>" class="px-3 py-1.5 rounded-xl bg-amber-500/20 hover:bg-amber-500/30 text-amber-300 text-[12px] border border-amber-500/30 font-semibold flex items-center gap-1.5 transition-colors" title="Download Print Card in SVG format (Both Sides)">
      <i class="fa-solid fa-id-card"></i> Download Card (SVG)
    </a>
    <?php if (!empty($alumni['phone'])): ?>
    <a href="tel:<?= e($alumni['phone']) ?>" class="px-3 py-1.5 rounded-xl bg-white/5 hover:bg-white/10 text-white text-[12px] border border-white/10 font-semibold flex items-center gap-1.5 transition-colors">
      📞 Call Member
    </a>
    <?php endif; ?>
    <a href="mailto:<?= e($alumni['email']) ?>" class="px-3 py-1.5 rounded-xl bg-white/5 hover:bg-white/10 text-white text-[12px] border border-white/10 font-semibold flex items-center gap-1.5 transition-colors">
      ✉ Send Email
    </a>
  </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

  <!-- Left: Comprehensive Profile Info -->
  <div class="lg:col-span-2 space-y-6">

    <!-- Card 1: Core Header Banner -->
    <div class="p-6 rounded-3xl" style="background:rgba(255,255,255,0.04);border:1px solid rgba(255,255,255,0.08);">
      <div class="flex flex-col sm:flex-row items-start sm:items-center gap-5">
        <div class="w-20 h-20 rounded-2xl overflow-hidden flex items-center justify-center font-serif font-bold text-[28px] shrink-0 border-2 border-white/20 shadow-lg"
             style="background:linear-gradient(135deg,#800020,#2F8863);color:#FFFFFF;">
          <?php if (!empty($alumni['avatar'])): ?>
            <img src="<?= asset('storage/avatars/' . e($alumni['avatar'])) ?>" alt="Avatar" class="w-full h-full object-cover">
          <?php else: ?>
            <?= initials($alumni['name']) ?>
          <?php endif; ?>
        </div>
        <div class="flex-1 min-w-0">
          <div class="flex items-center gap-3 flex-wrap">
            <h2 class="text-[22px] font-bold text-white"><?= e($alumni['name']) ?></h2>
            <span class="px-3 py-0.5 rounded-full text-[10.5px] font-mono font-bold uppercase"
                  style="background:<?= $alumni['status'] === 'approved' || $alumni['status'] === 'verified' ? 'rgba(47,136,99,0.2)' : 'rgba(232,199,126,0.2)' ?>;color:<?= $alumni['status'] === 'approved' || $alumni['status'] === 'verified' ? '#4E9C81' : '#E8C77E' ?>;border:1px solid currentColor;">
              <?= e($alumni['status']) ?>
            </span>
          </div>

          <div class="text-[13.5px] text-white/60 mt-1 flex flex-wrap items-center gap-x-4 gap-y-1">
            <span>✉ <?= e($alumni['email']) ?></span>
            <?php if (!empty($alumni['phone'])): ?>
            <span>📞 <?= e($alumni['phone']) ?></span>
            <?php endif; ?>
          </div>

          <div class="mt-3 flex flex-wrap gap-2">
            <span class="px-2.5 py-0.5 rounded-lg text-[11px] font-mono text-emerald-300 font-semibold" style="background:rgba(47,136,99,0.2);">
              BATCH: <?= e($alumni['batch_year'] ?? 'N/A') ?>
            </span>
            <?php if (!empty($alumni['student_id'])): ?>
            <span class="px-2.5 py-0.5 rounded-lg text-[11px] font-mono text-blue-300 font-semibold" style="background:rgba(59,130,246,0.2);">
              DU REG: <?= e($alumni['student_id']) ?>
            </span>
            <?php endif; ?>
            <?php if (!empty($alumni['blood_group'])): ?>
            <span class="px-2.5 py-0.5 rounded-lg text-[11px] font-mono text-rose-300 font-semibold" style="background:rgba(225,29,72,0.2);">
              BLOOD: <?= e($alumni['blood_group']) ?>
            </span>
            <?php endif; ?>
          </div>
        </div>
      </div>
    </div>

    <!-- Card 2: Detailed Personal & Contact Information -->
    <div class="p-6 rounded-3xl space-y-4" style="background:rgba(255,255,255,0.04);border:1px solid rgba(255,255,255,0.08);">
      <h3 class="text-[15px] font-semibold text-[#E58E97] font-serif flex items-center gap-2 pb-3 border-b border-white/5">
        👤 Personal & Contact Details (ব্যক্তিগত ও যোগাযোগের তথ্য)
      </h3>

      <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4 text-[13px]">
        <div>
          <div class="text-white/40 font-mono text-[11px]">NID NUMBER</div>
          <div class="font-medium text-white text-[14px]"><?= e($alumni['nid_number'] ?? '—') ?></div>
        </div>
        <div>
          <div class="text-white/40 font-mono text-[11px]">DATE OF BIRTH</div>
          <div class="font-medium text-white"><?= !empty($alumni['dob']) ? date('d F Y', strtotime($alumni['dob'])) : '—' ?></div>
        </div>
        <div>
          <div class="text-white/40 font-mono text-[11px]">GENDER / BLOOD GROUP</div>
          <div class="font-medium text-white"><?= e(ucfirst($alumni['gender'] ?? '—')) ?> / <?= e($alumni['blood_group'] ?? '—') ?></div>
        </div>
        <div>
          <div class="text-white/40 font-mono text-[11px]">CURRENT LOCATION</div>
          <div class="font-medium text-white"><?= e($alumni['current_location'] ?? '—') ?></div>
        </div>
        <?php if (!empty($alumni['thana_upazila'])): ?>
        <div>
          <div class="text-white/40 font-mono text-[11px]">THANA / UPAZILA</div>
          <div class="font-medium text-white"><?= e($alumni['thana_upazila']) ?></div>
        </div>
        <?php endif; ?>
        <?php if (!empty($alumni['country'])): ?>
        <div>
          <div class="text-white/40 font-mono text-[11px]">COUNTRY</div>
          <div class="font-medium text-white"><?= e($alumni['country']) ?></div>
        </div>
        <?php endif; ?>
        <div>
          <div class="text-white/40 font-mono text-[11px]">REGISTRATION DATE</div>
          <div class="font-medium text-white"><?= !empty($alumni['registered_at']) ? date('d M Y, h:i A', strtotime($alumni['registered_at'])) : '—' ?></div>
        </div>
      </div>

      <!-- Social & Professional Links -->
      <?php if (!empty($alumni['website']) || !empty($alumni['linkedin_url']) || !empty($alumni['facebook_url'])): ?>
      <div class="pt-3 border-t border-white/5 flex flex-wrap gap-3">
        <?php if (!empty($alumni['website'])): ?>
        <a href="<?= e($alumni['website']) ?>" target="_blank" class="px-3 py-1.5 rounded-xl bg-white/5 hover:bg-white/10 text-blue-300 text-[12px] flex items-center gap-1.5">
          🌐 Website
        </a>
        <?php endif; ?>
        <?php if (!empty($alumni['linkedin_url'])): ?>
        <a href="<?= e($alumni['linkedin_url']) ?>" target="_blank" class="px-3 py-1.5 rounded-xl bg-blue-600/20 hover:bg-blue-600/30 text-blue-300 text-[12px] flex items-center gap-1.5 border border-blue-500/20">
          🔗 LinkedIn Profile
        </a>
        <?php endif; ?>
        <?php if (!empty($alumni['facebook_url'])): ?>
        <a href="<?= e($alumni['facebook_url']) ?>" target="_blank" class="px-3 py-1.5 rounded-xl bg-indigo-600/20 hover:bg-indigo-600/30 text-indigo-300 text-[12px] flex items-center gap-1.5 border border-indigo-500/20">
          📘 Facebook Profile
        </a>
        <?php endif; ?>
      </div>
      <?php endif; ?>
    </div>

    <!-- Card 3: Family Information -->
    <?php if (!empty($alumni['spouse_name']) || !empty($alumni['children_info'])): ?>
    <div class="p-6 rounded-3xl space-y-4" style="background:rgba(255,255,255,0.04);border:1px solid rgba(255,255,255,0.08);">
      <h3 class="text-[15px] font-semibold text-[#E58E97] font-serif flex items-center gap-2 pb-3 border-b border-white/5">
        👨‍👩‍👧‍👦 Family Details (পারিবারিক তথ্য)
      </h3>
      <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-[13px]">
        <?php if (!empty($alumni['spouse_name'])): ?>
        <div>
          <div class="text-white/40 font-mono text-[11px]">SPOUSE NAME</div>
          <div class="font-medium text-white"><?= e($alumni['spouse_name']) ?></div>
        </div>
        <?php endif; ?>
        <?php if (!empty($alumni['children_info'])): ?>
        <div class="col-span-2">
          <div class="text-white/40 font-mono text-[11px]">CHILDREN INFORMATION</div>
          <div class="font-medium text-white"><?= nl2br(e($alumni['children_info'])) ?></div>
        </div>
        <?php endif; ?>
      </div>
    </div>
    <?php endif; ?>

    <!-- Card 4: Academic Background (Education) -->
    <div class="p-6 rounded-3xl space-y-4" style="background:rgba(255,255,255,0.04);border:1px solid rgba(255,255,255,0.08);">
      <h3 class="text-[15px] font-semibold text-[#E58E97] font-serif flex items-center gap-2 pb-3 border-b border-white/5">
        🎓 Academic Background (শিক্ষাগত যোগ্যতা)
      </h3>
      <?php if (empty($education)): ?>
        <p class="text-[13px] text-white/40 italic">No academic history records added yet.</p>
      <?php else: ?>
        <div class="space-y-3">
          <?php foreach ($education as $edu): ?>
          <div class="p-4 rounded-2xl bg-white/[0.02] border border-white/5 space-y-1">
            <div class="flex items-center justify-between">
              <h4 class="font-bold text-white text-[14px]"><?= e($edu['degree']) ?> <?= !empty($edu['field_of_study']) ? 'in ' . e($edu['field_of_study']) : '' ?></h4>
              <span class="text-[11px] font-mono text-emerald-400 bg-emerald-500/10 px-2.5 py-0.5 rounded-full"><?= e($edu['graduation_year']) ?></span>
            </div>
            <p class="text-[12.5px] text-white/70"><?= e($edu['institution']) ?></p>
          </div>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>
    </div>

    <!-- Card 5: Employment & Professional Experience -->
    <div class="p-6 rounded-3xl space-y-4" style="background:rgba(255,255,255,0.04);border:1px solid rgba(255,255,255,0.08);">
      <h3 class="text-[15px] font-semibold text-[#E58E97] font-serif flex items-center gap-2 pb-3 border-b border-white/5">
        💼 Professional Experience (কর্মসংস্থান তথ্য)
      </h3>
      <?php if (empty($employment)): ?>
        <p class="text-[13px] text-white/40 italic">No professional experience records added yet.</p>
      <?php else: ?>
        <div class="space-y-3">
          <?php foreach ($employment as $emp): ?>
          <div class="p-4 rounded-2xl bg-white/[0.02] border border-white/5 space-y-1">
            <div class="flex items-center justify-between">
              <h4 class="font-bold text-white text-[14px]"><?= e($emp['job_title']) ?></h4>
              <?php if (!empty($emp['is_current'])): ?>
              <span class="text-[10px] font-mono font-bold text-emerald-300 bg-emerald-500/20 px-2 py-0.5 rounded">CURRENT JOB</span>
              <?php endif; ?>
            </div>
            <p class="text-[12.5px] text-white/80 font-medium"><?= e($emp['organization']) ?></p>
            <?php if (!empty($emp['start_date'])): ?>
            <p class="text-[11px] font-mono text-white/40">
              <?= date('M Y', strtotime($emp['start_date'])) ?> — <?= !empty($emp['is_current']) ? 'Present' : (!empty($emp['end_date']) ? date('M Y', strtotime($emp['end_date'])) : '—') ?>
            </p>
            <?php endif; ?>
          </div>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>
    </div>

    <!-- Card 6: Verification Document / Proof Document -->
    <?php if (!empty($alumni['proof_document'])): ?>
    <div class="p-6 rounded-3xl space-y-3" style="background:rgba(255,255,255,0.04);border:1px solid rgba(255,255,255,0.08);">
      <h3 class="text-[15px] font-semibold text-[#E58E97] font-serif flex items-center gap-2 pb-2 border-b border-white/5">
        📄 Verification Document (ভেরিফিকেশন নথি)
      </h3>
      <p class="text-[12.5px] text-white/60">Uploaded certificate or proof of studentship document submitted during registration.</p>
      <a href="<?= asset('storage/documents/' . e($alumni['proof_document'])) ?>" target="_blank"
         class="px-5 py-2.5 bg-blue-600/20 hover:bg-blue-600/40 text-blue-300 border border-blue-600/30 rounded-xl text-[13px] font-semibold inline-flex items-center gap-2 transition-colors">
        📄 Download / Open Uploaded Document
      </a>
    </div>
    <?php endif; ?>

  </div>

  <!-- Right: Verification & Approval Control Panel -->
  <div class="space-y-6">
    <!-- Action Panel -->
    <div class="p-6 rounded-3xl space-y-4" style="background:rgba(255,255,255,0.04);border:1px solid rgba(255,255,255,0.08);">
      <h3 class="text-[15px] font-semibold text-white font-serif border-b border-white/5 pb-3">Action & Status Panel</h3>
      
      <?php if ($alumni['status'] === 'pending' || $alumni['status'] === 'under_review'): ?>
      <div class="space-y-3">
        <form method="POST" action="<?= url('/admin/alumni/' . $alumni['id'] . '/approve') ?>">
          <?= csrf_field() ?>
          <button type="submit" onclick="return confirm('Are you sure you want to approve this alumni profile?');"
                  class="w-full py-3 rounded-xl text-[13.5px] font-semibold bg-emerald-600 hover:bg-emerald-700 text-white transition-colors shadow-lg shadow-emerald-600/20 flex items-center justify-center gap-2">
            ✓ Approve Profile & Activate Account
          </button>
        </form>

        <form method="POST" action="<?= url('/admin/alumni/' . $alumni['id'] . '/reject') ?>">
          <?= csrf_field() ?>
          <textarea name="reason" placeholder="Write reason for rejection..." required
                    class="w-full px-3.5 py-2.5 rounded-xl text-[12.5px] text-white focus:outline-none mb-2"
                    style="background:rgba(255,255,255,0.06);border:1px solid rgba(255,255,255,0.12);"></textarea>
          <button type="submit" onclick="return confirm('Are you sure you want to reject this alumni profile?');"
                  class="w-full py-3 rounded-xl text-[13.5px] font-semibold bg-rose-600 hover:bg-rose-700 text-white transition-colors shadow-lg shadow-rose-600/20 flex items-center justify-center gap-2">
            ✕ Reject Profile
          </button>
        </form>
      </div>
      <?php else: ?>
      <div class="p-4 rounded-2xl bg-emerald-500/10 border border-emerald-500/20 text-[13px] text-emerald-300">
        Status is <span class="font-bold text-white uppercase"><?= e($alumni['status']) ?></span>. Member account has full access.
      </div>
      <?php endif; ?>
    </div>

    <!-- Audit & History Trail -->
    <?php if (!empty($approvalHistory)): ?>
    <div class="p-6 rounded-3xl space-y-3" style="background:rgba(255,255,255,0.04);border:1px solid rgba(255,255,255,0.08);">
      <h3 class="text-[14px] font-semibold text-white font-serif border-b border-white/5 pb-2">Approval Log History</h3>
      <div class="space-y-2.5 text-[12px]">
        <?php foreach ($approvalHistory as $log): ?>
        <div class="p-3 rounded-xl bg-white/[0.02] border border-white/5">
          <div class="flex items-center justify-between text-white/80 font-semibold">
            <span><?= e(strtoupper($log['action'])) ?></span>
            <span class="text-white/40 text-[10px] font-mono"><?= date('d M Y, h:i A', strtotime($log['created_at'])) ?></span>
          </div>
          <p class="text-white/50 text-[11.5px] mt-1"><?= e($log['notes'] ?? '—') ?></p>
          <div class="text-[10.5px] text-white/30 mt-1">By: <?= e($log['actor']) ?></div>
        </div>
        <?php endforeach; ?>
      </div>
    </div>
    <?php endif; ?>

  </div>

</div>
