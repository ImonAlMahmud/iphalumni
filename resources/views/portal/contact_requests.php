<?php
/**
 * Alumni Portal Contact Requests View
 * Variables: $requests
 */
?>
<div class="max-w-6xl mx-auto space-y-6 font-['Kalpurush']">
  
  <div>
    <h2 class="font-serif text-[24px] font-bold text-gray-900 mb-1">💬 Contact Requests (যোগাযোগের অনুরোধসমূহ)</h2>
    <p class="text-[14px] text-gray-500">
      ডিরেক্টরি থেকে সাধারণ ব্যবহারকারী বা জুনিয়রদের পাঠানো যোগাযোগের অনুরোধ এখানে দেখতে পাবেন। পছন্দ অনুযায়ী Accept/Reject করে আপনার কন্টাক্ট ডিটেইলস পাঠাতে পারেন।
    </p>
  </div>

  <?php if (empty($requests)): ?>
  <div class="p-12 rounded-3xl bg-white border border-gray-100 shadow-sm text-center">
    <div class="w-16 h-16 rounded-full bg-gray-100 text-gray-400 flex items-center justify-center text-2xl mx-auto mb-3">📬</div>
    <h3 class="font-bold text-[16px] text-gray-700">কোনো অনুরোধ পেন্ডিং নেই</h3>
    <p class="text-[13px] text-gray-400 mt-1">বর্তমানে আপনার কোনো নতুন যোগাযোগের অনুরোধ আসেনি।</p>
  </div>
  <?php else: ?>
  <div class="grid grid-cols-1 gap-5">
    <?php foreach ($requests as $req): ?>
    <div class="p-6 rounded-3xl bg-white border border-slate-200/80 shadow-sm space-y-4">
      <div class="flex flex-wrap items-center justify-between gap-3 border-b border-gray-100 pb-3">
        <div>
          <span class="font-bold text-gray-900 text-[16px]"><?= e($req['requester_name']) ?></span>
          <span class="text-[13px] text-gray-500 font-mono ml-2">&lt;<?= e($req['requester_email']) ?>&gt;</span>
          <?php if (!empty($req['requester_phone'])): ?>
          <span class="text-[12px] text-gray-400 font-mono ml-2">📞 <?= e($req['requester_phone']) ?></span>
          <?php endif; ?>
        </div>
        <div class="flex items-center gap-3">
          <span class="px-3 py-1 rounded-full text-[11.5px] font-mono font-bold <?= $req['status'] === 'accepted' ? 'bg-emerald-100 text-emerald-800' : ($req['status'] === 'rejected' ? 'bg-rose-100 text-rose-800' : 'bg-amber-100 text-amber-800') ?>">
            <?= strtoupper($req['status']) ?>
          </span>
          <span class="text-[11px] text-gray-400 font-mono"><?= date('d M Y, h:i A', strtotime($req['created_at'])) ?></span>
          <form method="POST" action="<?= url('/portal/contact-requests/' . $req['id'] . '/delete') ?>" class="inline" onsubmit="return confirm('আপনি কি নিশ্চিত যে এই অনুরোধটির রেকর্ড মুছে ফেলতে (Delete) চান?')">
            <?= csrf_field() ?>
            <button type="submit" class="px-2.5 py-1 rounded-lg bg-rose-50 text-rose-600 hover:bg-rose-100 text-[12px] font-bold transition-all" title="Delete Response">
              🗑 Delete
            </button>
          </form>
        </div>
      </div>

      <div class="space-y-2">
        <div>
          <span class="text-[11px] font-mono text-gray-400 uppercase block font-bold">DISCUSSION TOPIC (আলোচনার বিষয়)</span>
          <h4 class="font-bold text-[15px] text-[#800020]"><?= e($req['discussion_topic']) ?></h4>
        </div>

        <div>
          <span class="text-[11px] font-mono text-gray-400 uppercase block font-bold">BRIEF MESSAGE (বার্তার বিষয়সংক্ষেপ)</span>
          <p class="text-[14px] text-gray-700 bg-gray-50 p-3.5 rounded-2xl border border-gray-100 leading-relaxed"><?= e($req['brief_message']) ?></p>
        </div>
      </div>

      <?php if ($req['status'] === 'pending'): ?>
      <!-- Accept Form Modal Toggle -->
      <div x-data="{ open: false }" class="pt-2 border-t border-gray-100">
        <div class="flex items-center gap-3">
          <button type="button" @click="open = !open" class="px-5 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-[13px] shadow">
            ✓ Accept & Share Contact Info
          </button>
          <form method="POST" action="<?= url('/portal/contact-requests/' . $req['id'] . '/reject') ?>" onsubmit="return confirm('আপনি কি নিশ্চিত যে এই অনুরোধটি প্রত্যাখ্যান করতে চান?')">
            <?= csrf_field() ?>
            <button type="submit" class="px-4 py-2 rounded-xl bg-rose-100 hover:bg-rose-200 text-rose-800 font-semibold text-[13px]">
              ✕ Reject
            </button>
          </form>
        </div>

        <!-- Accept Form Inputs -->
        <div x-show="open" x-transition class="mt-4 p-5 rounded-2xl bg-emerald-50/70 border border-emerald-200/80 space-y-4">
          <h4 class="font-bold text-emerald-950 text-[14.5px]">অনুরোধের উত্তর ও যোগাযোগের তথ্য নির্বাচন করুন</h4>
          <form method="POST" action="<?= url('/portal/contact-requests/' . $req['id'] . '/accept') ?>" class="space-y-3">
            <?= csrf_field() ?>

            <div>
              <label class="block text-[12.5px] font-bold text-emerald-900 mb-1">পছন্দের যোগাযোগ মাধ্যম (Preferred Contact Method)</label>
              <select name="accepted_contact_method" required class="w-full px-3 py-2 rounded-xl border border-emerald-300 bg-white text-[13.5px]">
                <option value="Email">Email Only (ইমেইল)</option>
                <option value="WhatsApp">WhatsApp Message (হোয়াটসঅ্যাপ)</option>
                <option value="Phone Call">Direct Phone Call (সরাসরি কল)</option>
                <option value="Email & Phone">Both Email & Phone Call</option>
              </select>
            </div>

            <div>
              <label class="block text-[12.5px] font-bold text-emerald-900 mb-1">যোগাযোগের বিবরণ (Contact Details / Number / Email)</label>
              <input type="text" name="accepted_contact_details" required placeholder="e.g. +8801700000000 or email@domain.com" value="<?= e($user['email']) ?>" class="w-full px-3.5 py-2 rounded-xl border border-emerald-300 bg-white text-[13.5px]">
            </div>

            <div>
              <label class="block text-[12.5px] font-bold text-emerald-900 mb-1">নির্দেশনা / সময়সূচি (Instruction / Contact Time / Notes)</label>
              <textarea name="instruction_note" rows="2" placeholder="যেমন: শুধুমাত্র শনি-রবিবার সন্ধ্যা ৭টার পর হোয়াটসঅ্যাপে বার্তা দিন..." class="w-full px-3.5 py-2 rounded-xl border border-emerald-300 bg-white text-[13.5px] resize-none"></textarea>
            </div>

            <button type="submit" class="px-6 py-2.5 rounded-xl bg-emerald-700 hover:bg-emerald-800 text-white font-bold text-[13.5px] shadow">
              উক্ত ইমেইলে কন্টাক্ট ইনফো পাঠান (Send Email Alert)
            </button>
          </form>
        </div>
      </div>
      <?php elseif ($req['status'] === 'accepted'): ?>
      <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-[13px] text-emerald-900 space-y-1">
        <p><strong>✓ Accepted Method:</strong> <?= e($req['accepted_contact_method']) ?> (<?= e($req['accepted_contact_details']) ?>)</p>
        <?php if (!empty($req['instruction_note'])): ?>
        <p><strong>Note:</strong> <?= e($req['instruction_note']) ?></p>
        <?php endif; ?>
      </div>
      <?php endif; ?>
    </div>
    <?php endforeach; ?>
  </div>
  <?php endif; ?>

</div>
