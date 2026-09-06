<?php
/**
 * Admin Alumni Mapping View — Student Reference Database Mapping
 */
?>
<div class="max-w-7xl mx-auto py-6 font-['Kalpurush']">

  <!-- Header -->
  <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8">
    <div>
      <span class="font-mono text-[11px] font-bold text-[#E58E97] uppercase tracking-wider block mb-1">
        <i class="fa-solid fa-link mr-1"></i> VERIFICATION & DATA MATCHING
      </span>
      <h1 class="font-serif text-[28px] font-bold text-white"><?= __('অ্যালামনাই ও স্টুডেন্ট রেফারেন্স ম্যাপিং', 'Alumni Student Reference Mapping') ?></h1>
    </div>
    <div class="flex items-center gap-2">
      <a href="<?= url('/admin/students') ?>" class="px-4 py-2 rounded-xl bg-white/10 text-white text-[13px] hover:bg-white/20 transition-all">
        <i class="fa-solid fa-database mr-1"></i> Reference Database
      </a>
    </div>
  </div>

  <!-- Filter & Search Bar -->
  <div class="p-5 rounded-2xl bg-white/5 border border-white/10 mb-8 flex flex-col md:flex-row justify-between items-center gap-4">
    <div class="flex items-center gap-2">
      <a href="<?= url('/admin/alumni/mapping?filter=all') ?>" 
         class="px-4 py-2 rounded-xl text-[13px] font-semibold transition-all <?= ($filter === 'all') ? 'bg-[#800020] text-white shadow-sm' : 'bg-white/5 text-white/70 hover:bg-white/10' ?>">
        সকল মেম্বার (All)
      </a>
      <a href="<?= url('/admin/alumni/mapping?filter=unmapped') ?>" 
         class="px-4 py-2 rounded-xl text-[13px] font-semibold transition-all <?= ($filter === 'unmapped') ? 'bg-amber-600 text-white shadow-sm' : 'bg-white/5 text-white/70 hover:bg-white/10' ?>">
        ⚠️ Unmapped Members Only
      </a>
      <a href="<?= url('/admin/alumni/mapping?filter=mapped') ?>" 
         class="px-4 py-2 rounded-xl text-[13px] font-semibold transition-all <?= ($filter === 'mapped') ? 'bg-emerald-600 text-white shadow-sm' : 'bg-white/5 text-white/70 hover:bg-white/10' ?>">
        ✓ Mapped Members Only
      </a>
    </div>

    <form method="GET" action="<?= url('/admin/alumni/mapping') ?>" class="w-full md:w-auto flex items-center gap-2">
      <input type="hidden" name="filter" value="<?= e($filter) ?>">
      <input type="text" name="q" value="<?= e($search) ?>" placeholder="মেম্বার নাম, ইমেইল বা ফোন দিয়ে খোঁজ..."
             class="px-4 py-2 rounded-xl bg-black/40 border border-white/10 text-white text-[13.5px] focus:outline-none focus:border-[#E58E97] w-64">
      <button type="submit" class="px-4 py-2 rounded-xl bg-[#800020] text-white text-[13px] font-semibold">খুঁজুন</button>
    </form>
  </div>

  <!-- Mapping Table -->
  <div class="rounded-3xl bg-white/5 border border-white/10 overflow-hidden shadow-xl">
    <div class="overflow-x-auto">
      <table class="w-full text-left border-collapse text-[13.5px]">
        <thead>
          <tr class="border-b border-white/10 bg-black/40 text-white/60 font-mono text-[11px] uppercase tracking-wider">
            <th class="p-4">Alumni Member</th>
            <th class="p-4">Batch & Contact</th>
            <th class="p-4">Mapping Status</th>
            <th class="p-4">Matched Reference Record</th>
            <th class="p-4 text-right">Action / Manual Mapping</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-white/5 text-slate-200">
          <?php if (empty($alumniList)): ?>
          <tr>
            <td colspan="5" class="p-8 text-center text-white/40">কোনো অ্যালামনাই মেম্বার তথ্য পাওয়া যায়নি।</td>
          </tr>
          <?php else: ?>
            <?php foreach ($alumniList as $row): 
              $isMapped = !empty($row['student_reference_id']);
            ?>
            <tr class="hover:bg-white/[0.02] transition-colors">
              <td class="p-4">
                <div class="font-bold text-white text-[14.5px]"><?= e($row['name']) ?></div>
                <div class="text-[12px] text-white/50"><?= e($row['email']) ?></div>
              </td>

              <td class="p-4">
                <div class="font-mono text-[12px] text-[#E58E97] font-semibold">Batch: <?= e($row['batch_year'] ?: 'N/A') ?></div>
                <div class="text-[12px] text-white/60"><?= e($row['phone'] ?: 'No Phone') ?></div>
              </td>

              <td class="p-4">
                <?php if ($isMapped): ?>
                  <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-emerald-500/20 text-emerald-300 border border-emerald-500/30 text-[11px] font-bold font-mono">
                    <i class="fa-solid fa-link text-[10px]"></i> MAPPED
                  </span>
                <?php else: ?>
                  <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-amber-500/20 text-amber-300 border border-amber-500/30 text-[11px] font-bold font-mono">
                    <i class="fa-solid fa-link-slash text-[10px]"></i> UNMAPPED
                  </span>
                <?php endif; ?>
              </td>

              <td class="p-4">
                <?php if ($isMapped): ?>
                  <div class="text-[13px] text-white font-semibold"><?= e($row['ref_name_en']) ?> (<?= e($row['ref_name_bn']) ?>)</div>
                  <div class="text-[11.5px] font-mono text-emerald-400">Roll: <?= e($row['ref_roll']) ?> | Batch: <?= e($row['ref_batch']) ?> | Mobile: <?= e($row['ref_mobile']) ?></div>
                <?php else: ?>
                  <span class="text-white/40 text-[12.5px] italic">রেফারেন্স ডাটাবেসে ম্যাপ করা হয়নি</span>
                <?php endif; ?>
              </td>

              <td class="p-4 text-right">
                <form action="<?= url('/admin/alumni/map-student') ?>" method="POST" class="inline-flex items-center gap-2">
                  <?= csrf_field() ?>
                  <input type="hidden" name="profile_id" value="<?= $row['id'] ?>">

                  <?php if ($isMapped): ?>
                    <input type="hidden" name="student_reference_id" value="">
                    <button type="submit" class="px-3 py-1.5 rounded-xl text-[12px] font-medium bg-red-950/60 text-red-300 border border-red-800/50 hover:bg-red-900 transition-all">
                      <i class="fa-solid fa-unlink mr-1"></i> Unmap
                    </button>
                  <?php else: ?>
                    <div x-data="searchableStudentCombobox(<?= $row['id'] ?>)" 
                         @close-student-dropdowns.window="if ($event.detail !== profileId) open = false"
                         class="inline-block text-left">
                      
                      <!-- Hidden Form Value -->
                      <input type="hidden" name="student_reference_id" :value="selectedId" required>

                      <div class="flex items-center gap-2">
                        <!-- Custom Select Trigger Button -->
                        <button type="button" 
                                x-ref="triggerBtn"
                                @click="toggle()"
                                :class="selectedId ? 'border-emerald-500/50 bg-emerald-950/30 text-emerald-200 ring-1 ring-emerald-500/30' : 'border-white/20 bg-black/60 text-white/80 hover:border-white/40 hover:text-white'"
                                class="px-3 py-1.5 rounded-xl border text-[12px] font-medium flex items-center justify-between gap-2 min-w-[210px] max-w-[260px] transition-all cursor-pointer shadow-sm text-left">
                          
                          <span class="truncate flex items-center gap-1.5">
                            <template x-if="selectedId">
                              <i class="fa-solid fa-circle-check text-emerald-400 text-[11px] shrink-0"></i>
                            </template>
                            <template x-if="!selectedId">
                              <i class="fa-solid fa-magnifying-glass text-white/40 text-[10px] shrink-0"></i>
                            </template>
                            <span x-text="selectedLabel || '-- মেম্বার সিলেক্ট করুন --'" class="truncate"></span>
                          </span>

                          <span class="flex items-center gap-1.5 shrink-0">
                            <template x-if="selectedId">
                              <span @click.stop="clear()" title="মুছে ফেলুন" class="text-white/40 hover:text-red-400 px-0.5 cursor-pointer">
                                <i class="fa-solid fa-xmark text-[11px]"></i>
                              </span>
                            </template>
                            <i class="fa-solid fa-chevron-down text-[10px] text-white/40 transition-transform duration-200" :class="open ? 'rotate-180 text-[#E58E97]' : ''"></i>
                          </span>
                        </button>

                        <!-- Submit Button -->
                        <button type="submit" 
                                :disabled="!selectedId"
                                :class="selectedId ? 'bg-emerald-600 hover:bg-emerald-500 text-white shadow-lg shadow-emerald-900/40 ring-1 ring-emerald-400/50 cursor-pointer' : 'bg-white/10 text-white/30 cursor-not-allowed opacity-60'"
                                class="px-3 py-1.5 rounded-xl text-[12px] font-semibold transition-all flex items-center gap-1">
                          <i class="fa-solid fa-link text-[11px]"></i> Map & Autofill
                        </button>
                      </div>

                      <!-- Teleported Floating Search Dropdown Menu -->
                      <template x-teleport="body">
                        <div x-show="open"
                             x-transition:enter="transition ease-out duration-150 transform"
                             x-transition:enter-start="opacity-0 scale-95 -translate-y-1"
                             x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                             x-transition:leave="transition ease-in duration-100 transform"
                             x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                             x-transition:leave-end="opacity-0 scale-95 -translate-y-1"
                             @click.outside="if (!$refs.triggerBtn.contains($event.target)) open = false"
                             @keydown.escape.window="open = false"
                             @scroll.window="if(open) updatePos()"
                             @resize.window="if(open) updatePos()"
                             :style="panelStyle"
                             class="fixed z-[99999] bg-[#121216] border border-white/20 rounded-2xl shadow-2xl shadow-black overflow-hidden flex flex-col font-['Kalpurush'] backdrop-blur-2xl"
                             style="width: 340px; display: none;">

                          <!-- Header with Search Input Box -->
                          <div class="p-2.5 bg-white/[0.04] border-b border-white/10">
                            <div class="relative flex items-center">
                              <i class="fa-solid fa-magnifying-glass absolute left-3 text-[#E58E97] text-[11px]"></i>
                              <input type="text"
                                     x-ref="searchInput"
                                     x-model="query"
                                     @input.debounce.200ms="search()"
                                     placeholder="রোল, নাম, ব্যাচ বা ফোন..."
                                     class="w-full pl-8 pr-7 py-1.5 bg-black/70 border border-white/15 focus:border-[#E58E97] rounded-xl text-[12.5px] text-white placeholder:text-white/40 focus:outline-none transition-colors">
                              <button type="button" 
                                      x-show="query" 
                                      @click="query = ''; search(); $refs.searchInput.focus()" 
                                      class="absolute right-2.5 text-white/40 hover:text-white text-[11px]">
                                <i class="fa-solid fa-circle-xmark"></i>
                              </button>
                            </div>
                            
                            <!-- Search Status Helper -->
                            <div class="flex items-center justify-between text-[11px] text-white/50 mt-1.5 px-1 font-mono">
                              <span>
                                <template x-if="loading">
                                  <span class="text-amber-400 flex items-center gap-1 font-sans">
                                    <i class="fa-solid fa-circle-notch fa-spin text-[10px]"></i> খোঁজা হচ্ছে...
                                  </span>
                                </template>
                                <template x-if="!loading && query">
                                  <span class="text-emerald-400 font-sans" x-text="students.length + ' জন পাওয়া গেছে'"></span>
                                </template>
                                <template x-if="!loading && !query">
                                  <span class="font-sans">উপলব্ধ স্টুডেন্ট রেফারেন্স</span>
                                </template>
                              </span>
                              <span class="text-[9.5px] text-white/40 uppercase tracking-wider">Click to select</span>
                            </div>
                          </div>

                          <!-- Candidate Students List -->
                          <div class="max-h-[250px] overflow-y-auto divide-y divide-white/5 scrollbar-thin scrollbar-thumb-white/20">
                            <template x-for="st in students" :key="st.id">
                              <div @click="select(st)"
                                   :class="selectedId == st.id ? 'bg-[#800020]/40 border-l-2 border-[#E58E97]' : 'hover:bg-white/[0.07]'"
                                   class="p-2.5 cursor-pointer transition-all group">
                                <div class="flex items-start justify-between gap-2">
                                  <div class="min-w-0 flex-1 text-left">
                                    <div class="flex items-center gap-1.5">
                                      <span class="font-mono text-[10.5px] font-bold px-1.5 py-0.5 rounded bg-emerald-950/80 text-emerald-300 border border-emerald-800/60 shrink-0">
                                        Roll: <span x-text="st.roll"></span>
                                      </span>
                                      <span class="font-bold text-white text-[13px] truncate group-hover:text-[#E58E97] transition-colors" x-text="st.name_english"></span>
                                    </div>
                                    <template x-if="st.name_bangla">
                                      <div class="text-[11.5px] text-white/60 truncate mt-0.5" x-text="st.name_bangla"></div>
                                    </template>
                                    <div class="flex items-center gap-2 mt-1 text-[11px] text-white/50 font-mono">
                                      <span class="text-[#E58E97] font-semibold" x-text="'Batch: ' + (st.batch || 'N/A')"></span>
                                      <span class="text-white/20">•</span>
                                      <span x-text="st.mobile ? st.mobile : (st.session ? 'Sess: ' + st.session : 'No phone')"></span>
                                    </div>
                                  </div>

                                  <template x-if="selectedId == st.id">
                                    <div class="text-emerald-400 text-xs shrink-0 self-center">
                                      <i class="fa-solid fa-check"></i>
                                    </div>
                                  </template>
                                </div>
                              </div>
                            </template>

                            <!-- Empty Search Result -->
                            <template x-if="!loading && students.length === 0">
                              <div class="p-6 text-center text-white/40 text-[12.5px]">
                                <i class="fa-solid fa-magnifying-glass text-white/20 text-2xl mb-2 block"></i>
                                কোনো রেফারেন্স মেম্বার পাওয়া যায়নি
                              </div>
                            </template>
                          </div>

                          <!-- Footer Help -->
                          <div class="p-2 bg-black/60 border-t border-white/5 flex items-center justify-between text-[11px] text-white/40 px-3">
                            <span><i class="fa-solid fa-keyboard text-[10px] mr-1"></i> রোল, নাম বা ব্যাচ লিখে সার্চ করুন</span>
                            <button type="button" @click="open = false" class="text-[11.5px] text-white/60 hover:text-white transition-colors">বন্ধ করুন</button>
                          </div>

                        </div>
                      </template>
                    </div>
                  <?php endif; ?>
                </form>
              </td>
            </tr>
            <?php endforeach; ?>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>

</div>

<script>
window.defaultUnmappedStudents = <?= json_encode($unmappedStudents, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_UNESCAPED_UNICODE) ?>;

function searchableStudentCombobox(profileId) {
  return {
    profileId: profileId,
    open: false,
    query: '',
    loading: false,
    selectedId: '',
    selectedLabel: '',
    students: [],
    panelStyle: 'top: 0px; left: 0px; display: none;',
    init() {
      this.students = Array.isArray(window.defaultUnmappedStudents) ? [...window.defaultUnmappedStudents] : [];
    },
    toggle() {
      if (!this.open) {
        window.dispatchEvent(new CustomEvent('close-student-dropdowns', { detail: this.profileId }));
        this.updatePos();
        this.open = true;
        this.$nextTick(() => {
          this.$refs.searchInput?.focus();
        });
      } else {
        this.open = false;
      }
    },
    updatePos() {
      if (!this.$refs.triggerBtn) return;
      const rect = this.$refs.triggerBtn.getBoundingClientRect();
      const width = 340;
      let left = rect.right - width;
      if (left < 10) left = 10;
      let top = rect.bottom + 6;
      if (top + 330 > window.innerHeight && rect.top > 330) {
        top = rect.top - 336;
      }
      this.panelStyle = `top: ${top}px; left: ${left}px; width: ${width}px;`;
    },
    search() {
      const q = this.query.trim();
      if (!q) {
        this.students = Array.isArray(window.defaultUnmappedStudents) ? [...window.defaultUnmappedStudents] : [];
        this.loading = false;
        return;
      }
      this.loading = true;
      fetch('<?= url('/admin/alumni/search-students') ?>?q=' + encodeURIComponent(q))
        .then(res => res.json())
        .then(data => {
          this.students = Array.isArray(data) ? data : [];
          this.loading = false;
        })
        .catch(() => {
          this.loading = false;
        });
    },
    select(st) {
      this.selectedId = st.id;
      this.selectedLabel = `[Roll: ${st.roll}] ${st.name_english} (${st.batch})`;
      this.open = false;
    },
    clear() {
      this.selectedId = '';
      this.selectedLabel = '';
      this.query = '';
      this.students = Array.isArray(window.defaultUnmappedStudents) ? [...window.defaultUnmappedStudents] : [];
    }
  };
}
</script>
