<?php
/**
 * Alumni Portal Membership View
 * Variables: $user, $profile, $membership, $types
 */
?>
<div class="w-full max-w-6xl mx-auto space-y-8">
  <div class="p-8 rounded-3xl bg-white border border-gray-100 shadow-sm">
    <h3 class="font-serif text-[22px] font-semibold text-gray-800 mb-6">Membership Status</h3>

    <?php if ($membership): ?>
      
      <!-- Current Membership Info -->
      <div class="p-6 rounded-2xl border <?php
        echo $membership['status'] === 'active' 
          ? 'border-emerald-200 bg-emerald-50/50' 
          : 'border-yellow-200 bg-yellow-50/50';
      ?>">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
          <div>
            <span class="px-2.5 py-0.5 rounded-full text-[10.5px] font-mono font-semibold uppercase <?php
              echo $membership['status'] === 'active' 
                ? 'bg-emerald-100 text-emerald-700' 
                : 'bg-yellow-100 text-yellow-700';
            ?>">
              <?= strtoupper($membership['status']) ?>
            </span>
            <h4 class="font-serif text-[18px] font-semibold text-gray-800 mt-2">
              Membership No: <?= e($membership['membership_number']) ?>
            </h4>
            <p class="text-[13px] text-gray-500 mt-1">
              Validity: <?= date('d M Y', strtotime($membership['start_date'])) ?> 
              to <?= $membership['end_date'] ? date('d M Y', strtotime($membership['end_date'])) : 'Lifetime' ?>
            </p>
          </div>
          <?php if ($membership['status'] === 'active'): ?>
          <a href="<?= url('/portal/membership/qr') ?>" class="btn btn-gold px-6">
            🪪 View QR ID Card
          </a>
          <?php endif; ?>
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
      <div class="mt-8 pt-8 border-t border-gray-100" x-data="{ showUploadModal: false, selectedTypeId: null, selectedTypeName: '', selectedTypeFee: 0 }">
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

            <button type="button" @click="selectedTypeId = <?= $t['id'] ?>; selectedTypeName = '<?= e($t['name']) ?>'; selectedTypeFee = <?= (int)$t['fee'] ?>; showUploadModal = true" 
                    class="btn btn-gold w-full py-2 text-[13px] mt-6">
              Apply Now
            </button>
          </div>
          <?php endforeach; ?>
        </div>

        <!-- Document Upload & Payment Modal -->
        <div x-show="showUploadModal" class="fixed inset-0 z-[1000] bg-black/60 flex items-center justify-center p-4" style="display:none;" x-transition>
          <div class="bg-white p-6 rounded-3xl max-w-md w-full shadow-2xl border border-gray-100 max-h-[90vh] overflow-y-auto" @click.away="showUploadModal = false">
            <h4 class="font-serif text-[18px] font-bold text-gray-800 mb-2">Apply for <span x-text="selectedTypeName" class="text-[#800020]"></span></h4>
            <p class="text-[13px] text-gray-500 mb-5">To complete your membership, please upload the required details below.</p>

            <form method="POST" action="<?= url('/portal/membership/apply') ?>" enctype="multipart/form-data" class="space-y-4">
              <?= csrf_field() ?>
              <input type="hidden" name="membership_type_id" :value="selectedTypeId">


              <!-- Payment Section (visible if fee > 0) -->
              <template x-if="selectedTypeFee > 0">
                <div class="pt-4 border-t border-gray-100 space-y-4">
                  <div class="p-3.5 rounded-2xl bg-amber-50 border border-amber-200/50 text-[12px] text-amber-900 whitespace-pre-line leading-relaxed">
                    <strong>Payment Instructions:</strong><br><?= e($paymentInstructions) ?>
                  </div>

                  <div>
                    <label class="form-label font-semibold" for="method">Payment Method</label>
                    <select id="method" name="method" required class="form-input">
                      <option value="">Select Method</option>
                      <option value="bkash">bKash</option>
                      <option value="nagad">Nagad</option>
                      <option value="bank_transfer">Bank Transfer</option>
                    </select>
                  </div>

                  <div>
                    <label class="form-label font-semibold" for="transaction_id">Transaction ID</label>
                    <input id="transaction_id" type="text" name="transaction_id" required placeholder="e.g. TRX10029388" class="form-input">
                  </div>

                  <div>
                    <label class="form-label font-semibold" for="payment_slip">Payment Screenshot / Slip</label>
                    <p class="text-[11.5px] text-gray-400 mb-2">Upload screenshot of receipt or bank deposit slip (Max 5MB PDF/JPG/PNG).</p>
                    <input id="payment_slip" type="file" name="payment_slip" required accept=".pdf,image/*" class="form-input">
                  </div>
                </div>
              </template>

              <div class="flex justify-end gap-2 pt-4 border-t border-gray-100">
                <button type="button" @click="showUploadModal = false" class="btn btn-ghost px-5 py-2">Cancel</button>
                <button type="submit" class="btn btn-gold px-6 py-2">Submit & Apply</button>
              </div>
            </form>
          </div>
        </div>

      </div>
    <?php endif; ?>

  </div>
</div>
