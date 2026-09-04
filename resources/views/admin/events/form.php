<?php
/**
 * Admin Event Create/Edit Form View
 * Variables: $event
 */
$action = $event ? url('/admin/events/' . $event['id']) : url('/admin/events');
?>
<div class="mb-6">
  <a href="<?= url('/admin/events') ?>" class="text-[13px] text-white/50 hover:text-white inline-flex items-center gap-1">
    ← Back to Events Management
  </a>
</div>

<div class="p-8 rounded-3xl" style="background:rgba(255,255,255,0.04);border:1px solid rgba(255,255,255,0.08);">
  <form method="POST" action="<?= $action ?>" enctype="multipart/form-data" class="space-y-5">
    <?= csrf_field() ?>

    <!-- Event Poster Image Upload -->
    <div x-data="{ preview: '<?= !empty($event['cover_image']) ? asset('storage/events/' . e($event['cover_image'])) : '' ?>' }">
      <label class="block text-[13px] font-medium text-white/70 mb-1.5">Event Poster / Banner Image</label>
      <div class="flex items-center gap-4">
        <template x-if="preview">
          <div class="relative w-32 h-20 rounded-xl overflow-hidden border border-white/20 shrink-0">
            <img :src="preview" class="w-full h-full object-cover">
          </div>
        </template>
        <input type="file" name="cover_image" accept="image/*"
               @change="const file = $el.files[0]; if(file) preview = URL.createObjectURL(file)"
               class="w-full px-4 py-2 rounded-xl text-[13px] text-white/80 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-[12px] file:font-semibold file:bg-white/10 file:text-white hover:file:bg-white/20"
               style="background:rgba(255,255,255,0.06);border:1px solid rgba(255,255,255,0.12);">
      </div>
      <span class="text-[11.5px] text-white/40 mt-1 block">Recommended format: JPG, PNG, WEBP (Max 5MB). High resolution landscape poster.</span>
    </div>

    <div>
      <label class="block text-[13px] font-medium text-white/70 mb-1.5" for="title">Event Title</label>
      <input id="title" type="text" name="title" value="<?= e($event['title'] ?? '') ?>" required
             class="w-full px-4 py-2.5 rounded-xl text-[14px] text-white focus:outline-none"
             style="background:rgba(255,255,255,0.06);border:1px solid rgba(255,255,255,0.12);"
             placeholder="Event Title">
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
      <div>
        <label class="block text-[13px] font-medium text-white/70 mb-1.5" for="event_date">Event Date & Time</label>
        <input id="event_date" type="datetime-local" name="event_date" value="<?= $event ? date('Y-m-d\TH:i', strtotime($event['event_date'])) : '' ?>" required
               class="w-full px-4 py-2.5 rounded-xl text-[14px] text-white focus:outline-none"
               style="background:rgba(255,255,255,0.06);border:1px solid rgba(255,255,255,0.12);">
      </div>
      <div>
        <label class="block text-[13px] font-medium text-white/70 mb-1.5" for="venue">Venue</label>
        <input id="venue" type="text" name="venue" value="<?= e($event['venue'] ?? '') ?>" required
               class="w-full px-4 py-2.5 rounded-xl text-[14px] text-white focus:outline-none"
               style="background:rgba(255,255,255,0.06);border:1px solid rgba(255,255,255,0.12);"
               placeholder="IPH Auditorium / Online (Zoom)">
      </div>
    </div>

    <div>
      <label class="block text-[13px] font-medium text-white/70 mb-1.5" for="description">Description</label>
      <textarea id="description" name="description" rows="6" required
                class="w-full px-4 py-2.5 rounded-xl text-[14px] text-white focus:outline-none"
                style="background:rgba(255,255,255,0.06);border:1px solid rgba(255,255,255,0.12);"
                placeholder="Event details..."><?= e($event['description'] ?? '') ?></textarea>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
      <div>
        <label class="block text-[13px] font-medium text-white/70 mb-1.5" for="max_attendees">Max Attendees (0 for unlimited)</label>
        <input id="max_attendees" type="number" name="max_attendees" value="<?= e($event['max_attendees'] ?? '0') ?>"
               class="w-full px-4 py-2.5 rounded-xl text-[14px] text-white focus:outline-none"
               style="background:rgba(255,255,255,0.06);border:1px solid rgba(255,255,255,0.12);">
      </div>
      <div>
        <label class="block text-[13px] font-medium text-white/70 mb-1.5" for="status">Status</label>
        <select id="status" name="status" class="w-full px-4 py-2.5 rounded-xl text-[14px] text-white focus:outline-none"
                style="background:rgba(255,255,255,0.06);border:1px solid rgba(255,255,255,0.12);">
          <option value="draft" <?= ($event['status'] ?? '') === 'draft' ? 'selected' : '' ?>>Draft</option>
          <option value="published" <?= ($event['status'] ?? '') === 'published' ? 'selected' : '' ?>>Published</option>
        </select>
      </div>
    <!-- Registration Type & Ticket Fee & Target Roles -->
    <div x-data="{ regType: '<?= e($event['registration_type'] ?? 'free') ?>' }" class="p-5 rounded-2xl border border-white/10 space-y-4" style="background:rgba(255,255,255,0.03);">
      <div class="font-semibold text-[14px] text-amber-300 flex items-center gap-2">
        <i class="fa-solid fa-ticket"></i> Registration & Ticket Type (নিবন্ধন ও টিকিটের ধরন)
      </div>

      <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div>
          <label class="block text-[13px] font-medium text-white/70 mb-1.5" for="registration_type">Payment Required? (পেমেন্ট লাগবে কি না)</label>
          <select id="registration_type" name="registration_type" class="w-full px-4 py-2.5 rounded-xl text-[14px] text-white focus:outline-none bg-[#101820]"
                  style="background:rgba(255,255,255,0.06);border:1px solid rgba(255,255,255,0.12);"
                  x-model="regType">
            <option value="free" <?= ($event['registration_type'] ?? 'free') === 'free' ? 'selected' : '' ?>>🎟️ Free (বিনামূল্যে রেসপন্স ও পাস গ্রহণ)</option>
            <option value="paid" <?= ($event['registration_type'] ?? '') === 'paid' ? 'selected' : '' ?>>💳 Paid (পেমেন্ট পরিশোধ সাপেক্ষে পাস গ্রহণ)</option>
          </select>
        </div>

        <div x-show="regType === 'paid'" x-transition>
          <label class="block text-[13px] font-medium text-white/70 mb-1.5" for="ticket_fee">Ticket Fee / Registration Charge (৳)</label>
          <input id="ticket_fee" type="number" step="0.01" name="ticket_fee" value="<?= e($event['ticket_fee'] ?? '0.00') ?>" placeholder="e.g. 500"
                 class="w-full px-4 py-2.5 rounded-xl text-[14px] text-white focus:outline-none"
                 style="background:rgba(255,255,255,0.06);border:1px solid rgba(255,255,255,0.12);">
        </div>

        <div>
          <label class="block text-[13px] font-medium text-white/70 mb-1.5" for="allowed_roles">Eligible Members (কারা অংশ নিতে পারবেন)</label>
          <select id="allowed_roles" name="allowed_roles" class="w-full px-4 py-2.5 rounded-xl text-[14px] text-white focus:outline-none bg-[#101820]"
                  style="background:rgba(255,255,255,0.06);border:1px solid rgba(255,255,255,0.12);">
            <option value="all" <?= ($event['allowed_roles'] ?? 'all') === 'all' ? 'selected' : '' ?>>🌐 All Members & Students (সকলের জন্য উন্মুক্ত)</option>
            <option value="verified_alumni" <?= ($event['allowed_roles'] ?? '') === 'verified_alumni' ? 'selected' : '' ?>>🎓 Only Verified Alumni Members (শুধু ভেরিফাইড অ্যালামনাই)</option>
            <option value="students" <?= ($event['allowed_roles'] ?? '') === 'students' ? 'selected' : '' ?>>👨‍🎓 Only Running Students (শুধু রানিং স্টুডেন্ট)</option>
          </select>
        </div>
      </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4" x-data="{ isCrowd: <?= ($event['is_crowdfunding'] ?? 0) ? 'true' : 'false' ?> }">
      <div>
        <label class="block text-[13px] font-medium text-white/70 mb-1.5" for="is_crowdfunding">Crowd Funding Campaign</label>
        <select id="is_crowdfunding" name="is_crowdfunding" class="w-full px-4 py-2.5 rounded-xl text-[14px] text-white focus:outline-none bg-[#101820]"
                style="background:rgba(255,255,255,0.06);border:1px solid rgba(255,255,255,0.12);"
                @change="isCrowd = $el.value === '1'">
          <option value="0" <?= ($event['is_crowdfunding'] ?? 0) === 0 ? 'selected' : '' ?>>Disable</option>
          <option value="1" <?= ($event['is_crowdfunding'] ?? 0) === 1 ? 'selected' : '' ?>>Enable (Raise funds from members)</option>
        </select>
      </div>
      <div x-show="isCrowd">
        <label class="block text-[13px] font-medium text-white/70 mb-1.5" for="crowdfunding_goal">Crowdfund Target Goal (৳)</label>
        <input id="crowdfunding_goal" type="number" name="crowdfunding_goal" value="<?= e($event['crowdfunding_goal'] ?? '') ?>" placeholder="e.g. 50000"
               class="w-full px-4 py-2.5 rounded-xl text-[14px] text-white focus:outline-none"
               style="background:rgba(255,255,255,0.06);border:1px solid rgba(255,255,255,0.12);">
      </div>
    </div>

    <button type="submit" class="px-6 py-2.5 rounded-xl text-[14px] font-semibold text-white transition-all hover:scale-105"
            style="background:linear-gradient(135deg,#A22638,#800020);">Save Event</button>
  </form>
</div>
