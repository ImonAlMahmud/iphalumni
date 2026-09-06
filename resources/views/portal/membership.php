<?php
/**
 * Alumni Portal Membership View
 * Variables: $user, $profile, $membership, $types, $paymentInstructions
 */
?>
<div class="w-full max-w-6xl mx-auto space-y-8">
  <div class="p-8 rounded-3xl bg-white border border-gray-100 shadow-sm">
    <h3 class="font-serif text-[22px] font-semibold text-gray-800 mb-6">Membership Status</h3>

    <?php if ($membership && in_array($membership['status'], ['active', 'pending'])): ?>
      
      <!-- Current Membership Info -->
      <div class="p-6 rounded-2xl border <?php
        echo $membership['status'] === 'active' 
          ? 'border-emerald-200 bg-emerald-50/50' 
          : 'border-amber-200 bg-amber-50/50';
      ?>">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
          <div>
            <span class="px-2.5 py-0.5 rounded-full text-[10.5px] font-mono font-semibold uppercase <?php
              echo $membership['status'] === 'active' 
                ? 'bg-emerald-100 text-emerald-700' 
                : 'bg-amber-100 text-amber-800';
            ?>">
              <?= strtoupper($membership['status']) ?>
            </span>
            <?php 
              $dispMemNo = (!empty($membership['membership_number']) && str_starts_with($membership['membership_number'], 'IPHAA-')) 
                ? $membership['membership_number'] 
                : ('IPHAA-' . str_pad((string)($membership['alumni_profile_id'] ?? $profile['id'] ?? 1), 5, '0', STR_PAD_LEFT));
            ?>
            <h4 class="font-serif text-[18px] font-semibold text-gray-800 mt-2">
              <?= e($membership['type_name'] ?? 'Alumni Membership') ?> — <?= e($dispMemNo) ?>
            </h4>
            <p class="text-[13px] text-gray-500 mt-1">
              Validity: <?= date('d M Y', strtotime($membership['start_date'])) ?> 
              to <?= $membership['end_date'] ? date('d M Y', strtotime($membership['end_date'])) : 'Lifetime' ?>
            </p>
            <?php if ($membership['status'] === 'pending'): ?>
              <p class="text-[12.5px] text-amber-800 mt-2 flex items-center gap-1.5">
                <span>⏳ আপনার আবেদনটি পর্যালোচনায় আছে অথবা পেমেন্টের অপেক্ষায় রয়েছে।</span>
              </p>
            <?php endif; ?>
          </div>

          <div class="flex items-center gap-2 flex-wrap">
            <?php if ($membership['status'] === 'active'): ?>
              <a href="<?= url('/portal/membership/qr') ?>" class="btn btn-gold px-6">
                🪪 View QR ID Card
              </a>
            <?php elseif ($membership['status'] === 'pending' && ($membership['fee'] ?? 0) > 0): ?>
              <form method="POST" action="<?= url('/portal/membership/payment/uddoktapay') ?>">
                <?= csrf_field() ?>
                <input type="hidden" name="membership_id" value="<?= (int)$membership['id'] ?>">
                <button type="submit" class="btn btn-gold px-5 py-2.5 flex items-center gap-2 shadow-md">
                  <span>⚡ Pay ৳<?= number_format((float)$membership['fee']) ?> via UddoktaPay</span>
                </button>
              </form>
            <?php endif; ?>
          </div>
        </div>
      </div>

    <?php else: ?>

      <!-- Not a Member Yet -->
      <div class="p-6 rounded-2xl bg-gray-50 border border-gray-100 text-center space-y-3">
        <div class="text-[32px]">🪪</div>
        <h4 class="font-serif text-[17px] font-semibold text-gray-800">You do not have any active membership</h4>
        <p class="text-[13px] text-gray-500 max-w-md mx-auto">
          Apply for a membership tier below to obtain your digital QR ID card, unlock directory privileges, and attend exclusive alumni events.
        </p>
      </div>

      <!-- Pricing Plans / Application Grid -->
      <div class="mt-8 pt-8 border-t border-gray-100" x-data="{ 
          showModal: false, 
          payMode: 'online', 
          selectedTypeId: null, 
          selectedTypeName: '', 
          selectedTypeFee: 0 
      }">
        <h4 class="font-serif text-[18px] font-semibold text-gray-800 mb-6 text-center">Available Membership Tiers</h4>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
          <?php foreach ($types as $t): ?>
          <div class="p-6 rounded-2xl border border-gray-100 hover:border-[#A22638]/50 transition-all flex flex-col justify-between"
               style="background:rgba(255,255,255,0.72);box-shadow:0 4px 20px -10px rgba(16,24,32,0.05);">
            <div>
              <h5 class="font-serif text-[18px] font-semibold text-gray-800"><?= e($t['name']) ?></h5>
              <div class="font-serif text-[26px] font-bold text-gray-800 mt-3">
                ৳<?= number_format($t['fee']) ?>
                <span class="text-[12px] font-sans text-gray-400 font-normal">
                  / <?= $t['duration_months'] ? $t['duration_months'] . ' mos' : 'Lifetime' ?>
                </span>
              </div>
              <p class="text-[12.5px] text-gray-400 mt-2 leading-relaxed"><?= e($t['description'] ?? '') ?></p>
            </div>

            <button type="button" @click="selectedTypeId = <?= $t['id'] ?>; selectedTypeName = '<?= e($t['name']) ?>'; selectedTypeFee = <?= (int)$t['fee'] ?>; showModal = true" 
                    class="btn btn-gold w-full py-2.5 text-[13px] mt-6 flex items-center justify-center gap-1.5 font-semibold">
              <span>Apply Now</span>
              <span>→</span>
            </button>
          </div>
          <?php endforeach; ?>
        </div>

        <!-- Membership Application & Payment Modal -->
        <div x-show="showModal" class="fixed inset-0 z-[1000] bg-black/60 backdrop-blur-sm flex items-center justify-center p-4" style="display:none;" x-transition>
          <div class="bg-white p-6 md:p-8 rounded-3xl max-w-lg w-full shadow-2xl border border-gray-100 max-h-[92vh] overflow-y-auto" @click.away="showModal = false">
            <div class="flex items-center justify-between pb-4 border-b border-gray-100">
              <div>
                <h4 class="font-serif text-[19px] font-bold text-gray-800">
                  Apply for <span x-text="selectedTypeName" class="text-[#800020]"></span>
                </h4>
                <p class="text-[12.5px] text-gray-500 mt-0.5">Membership Fee: <strong class="text-gray-800 font-serif text-[14px]">৳<span x-text="selectedTypeFee"></span></strong></p>
              </div>
              <button type="button" @click="showModal = false" class="text-gray-400 hover:text-gray-600 text-xl font-bold p-1">&times;</button>
            </div>

            <!-- Free Tier Form -->
            <template x-if="selectedTypeFee <= 0">
              <form method="POST" action="<?= url('/portal/membership/apply') ?>" class="mt-5 space-y-4">
                <?= csrf_field() ?>
                <input type="hidden" name="membership_type_id" :value="selectedTypeId">
                <p class="text-[13px] text-gray-600">This is a complimentary membership. Click below to submit your application and activate immediately.</p>
                <div class="flex justify-end gap-2 pt-4 border-t border-gray-100">
                  <button type="button" @click="showModal = false" class="btn btn-ghost px-5 py-2">Cancel</button>
                  <button type="submit" class="btn btn-gold px-6 py-2">Confirm & Activate</button>
                </div>
              </form>
            </template>

            <!-- Paid Tier Options (Fee > 0) -->
            <template x-if="selectedTypeFee > 0">
              <div class="mt-5 space-y-5">
                <!-- Payment Method Selector Tabs -->
                <div class="flex items-center gap-2 p-1 bg-gray-100 rounded-2xl">
                  <button type="button" @click="payMode = 'online'"
                          :class="payMode === 'online' ? 'bg-white text-gray-900 shadow-sm font-semibold' : 'text-gray-500 hover:text-gray-900'"
                          class="flex-1 py-2 text-[12.5px] rounded-xl transition-all flex items-center justify-center gap-1.5">
                    <span>⚡ Online Payment</span>
                    <span class="text-[10px] px-1.5 py-0.2 rounded-full bg-emerald-100 text-emerald-700 font-bold">Instant</span>
                  </button>
                  <button type="button" @click="payMode = 'offline'"
                          :class="payMode === 'offline' ? 'bg-white text-gray-900 shadow-sm font-semibold' : 'text-gray-500 hover:text-gray-900'"
                          class="flex-1 py-2 text-[12.5px] rounded-xl transition-all flex items-center justify-center gap-1.5">
                    <span>📄 Manual Slip / Bank</span>
                  </button>
                </div>

                <!-- Tab 1: Instant Online Payment via UddoktaPay -->
                <div x-show="payMode === 'online'" class="space-y-4">
                  <div class="p-4 rounded-2xl bg-gradient-to-br from-emerald-50/60 to-teal-50/40 border border-emerald-100/80 text-center space-y-3">
                    <div class="text-[28px]">💳</div>
                    <div>
                      <h5 class="font-semibold text-gray-800 text-[14px]">Automatic Instant Activation</h5>
                      <p class="text-[12px] text-gray-500 mt-1 max-w-sm mx-auto">
                        Pay securely with bKash, Nagad, Rocket, Upay, Visa, MasterCard, or Internet Banking via UddoktaPay.
                      </p>
                    </div>

                    <!-- Payment Badges -->
                    <div class="flex items-center justify-center flex-wrap gap-1.5 pt-1">
                      <span class="px-2 py-0.5 rounded-md bg-white border border-pink-200 text-pink-700 text-[10.5px] font-bold">bKash</span>
                      <span class="px-2 py-0.5 rounded-md bg-white border border-orange-200 text-orange-700 text-[10.5px] font-bold">Nagad</span>
                      <span class="px-2 py-0.5 rounded-md bg-white border border-purple-200 text-purple-700 text-[10.5px] font-bold">Rocket</span>
                      <span class="px-2 py-0.5 rounded-md bg-white border border-blue-200 text-blue-700 text-[10.5px] font-bold">Upay</span>
                      <span class="px-2 py-0.5 rounded-md bg-white border border-gray-200 text-gray-700 text-[10.5px] font-bold">Cards / Bank</span>
                    </div>
                  </div>

                  <form method="POST" action="<?= url('/portal/membership/payment/uddoktapay') ?>">
                    <?= csrf_field() ?>
                    <input type="hidden" name="membership_type_id" :value="selectedTypeId">

                    <div class="flex justify-end gap-2 pt-4 border-t border-gray-100">
                      <button type="button" @click="showModal = false" class="btn btn-ghost px-5 py-2 text-[13px]">Cancel</button>
                      <button type="submit" class="btn btn-gold px-6 py-2 text-[13px] font-semibold flex items-center gap-1.5 shadow-md">
                        <span>Pay ৳<span x-text="selectedTypeFee"></span> Online</span>
                        <span>→</span>
                      </button>
                    </div>
                  </form>
                </div>

                <!-- Tab 2: Manual Slip Upload (Offline) -->
                <div x-show="payMode === 'offline'" style="display:none;" class="space-y-4">
                  <form method="POST" action="<?= url('/portal/membership/apply') ?>" enctype="multipart/form-data" class="space-y-4">
                    <?= csrf_field() ?>
                    <input type="hidden" name="membership_type_id" :value="selectedTypeId">

                    <div class="p-3.5 rounded-2xl bg-amber-50 border border-amber-200/50 text-[12px] text-amber-900 whitespace-pre-line leading-relaxed">
                      <strong>Payment Instructions:</strong><br><?= e($paymentInstructions) ?>
                    </div>

                    <div>
                      <label class="form-label font-semibold text-[13px]" for="method">Payment Method</label>
                      <select id="method" name="method" required class="form-input text-[13px]">
                        <option value="">Select Method</option>
                        <option value="bkash">bKash</option>
                        <option value="nagad">Nagad</option>
                        <option value="rocket">Rocket</option>
                        <option value="bank_transfer">Bank Transfer</option>
                      </select>
                    </div>

                    <div>
                      <label class="form-label font-semibold text-[13px]" for="transaction_id">Transaction ID / Reference</label>
                      <input id="transaction_id" type="text" name="transaction_id" required placeholder="e.g. TRX98273612" class="form-input text-[13px]">
                    </div>

                    <div>
                      <label class="form-label font-semibold text-[13px]" for="payment_slip">Payment Screenshot / Deposit Slip</label>
                      <p class="text-[11.5px] text-gray-400 mb-1.5">Max 5MB PDF, JPG or PNG.</p>
                      <input id="payment_slip" type="file" name="payment_slip" required accept=".pdf,image/*" class="form-input text-[12px]">
                    </div>

                    <div class="flex justify-end gap-2 pt-4 border-t border-gray-100">
                      <button type="button" @click="showModal = false" class="btn btn-ghost px-5 py-2 text-[13px]">Cancel</button>
                      <button type="submit" class="btn btn-gold px-6 py-2 text-[13px]">Submit Application</button>
                    </div>
                  </form>
                </div>
              </div>
            </template>

          </div>
        </div>

      </div>
    <?php endif; ?>

  </div>
</div>
