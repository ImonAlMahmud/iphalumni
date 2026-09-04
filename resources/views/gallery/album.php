<?php
/**
 * Album Detail View
 * Variables: $album, $photos
 */
?>
<div class="max-w-6xl mx-auto px-6 py-14">
  <div class="mb-6">
    <a href="<?= url('/gallery') ?>" class="text-[13px] text-[#6B7178] hover:text-[#101820] inline-flex items-center gap-1">
      ← <?= __('গ্যালারিতে ফিরে যান', 'Back to Gallery') ?>
    </a>
  </div>

  <div class="mb-10 flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
    <div>
      <span class="font-mono text-[11px] text-[#A22638] block mb-2"><?= date('d F Y', strtotime($album['album_date'])) ?></span>
      <h1 class="font-serif text-[clamp(26px,4vw,36px)] font-semibold text-[#101820] mb-2"><?= e($album['title']) ?></h1>
      <?php if (!empty($album['description'])): ?>
      <p class="text-[14px] text-[#6B7178]"><?= e($album['description']) ?></p>
      <?php endif; ?>
    </div>

    <?php 
    $canUpload = false;
    $currentUser = auth();
    if ($currentUser) {
        if (in_array($currentUser['role'], ['super_admin', 'admin'])) {
            $canUpload = true;
        } else {
            $pdo = \App\Services\Database::connection();
            $stmt = $pdo->prepare("SELECT status FROM alumni_profiles WHERE user_id = ? LIMIT 1");
            $stmt->execute([$currentUser['id']]);
            $alumniStatus = $stmt->fetchColumn();
            if ($alumniStatus === 'approved') {
                $canUpload = true;
            }
        }
    }
    if ($canUpload): 
    ?>
    <div class="p-5 rounded-2xl bg-white border border-gray-100 shadow-sm w-full md:max-w-md">
      <h4 class="text-[13px] font-semibold text-gray-800 mb-1"><?= __('অ্যালবামে ছবি যোগ করুন', 'Add Photos to Album') ?></h4>
      <form method="POST" action="<?= url('/gallery/' . $album['id'] . '/upload') ?>" enctype="multipart/form-data" class="flex items-center gap-2 mt-2">
        <?= csrf_field() ?>
        <input type="file" name="photos[]" multiple required accept="image/*" class="text-[11.5px] text-gray-500 flex-1 file:mr-2 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-[11.5px] file:font-semibold file:bg-[#800020]/10 file:text-[#800020] cursor-pointer">
        <button type="submit" class="px-3.5 py-1.5 rounded-lg text-[11.5px] font-semibold text-white" style="background:linear-gradient(135deg,#A22638,#800020);">
          <?= __('আপলোড', 'Upload') ?>
        </button>
      </form>
    </div>
    <?php endif; ?>
  </div>

  <?php if (empty($photos)): ?>
  <p class="text-[#6B7178] text-center py-20 glass"><?= __('এই অ্যালবামে এখনো কোনো ছবি নেই।', 'No photos in this album yet.') ?></p>
  <?php else:
    // Generate array of image URLs for Alpine.js
    $photoUrls = [];
    foreach ($photos as $photo) {
        $photoUrls[] = asset('storage/gallery/' . $album['id'] . '/' . $photo['filename']);
    }
  ?>
  <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4" 
       x-data="{ open: false, index: 0, photos: <?= e(json_encode($photoUrls)) ?> }">
     
     <?php foreach ($photos as $idx => $photo):
       $photoUrl = $photoUrls[$idx];
     ?>
     <div class="rounded-2xl overflow-hidden bg-white border border-gray-100 shadow-sm flex flex-col justify-between hover-lift">
       <div class="aspect-square w-full overflow-hidden cursor-pointer relative group"
            @click="index = <?= $idx ?>; open = true">
         <img src="<?= $photoUrl ?>" alt="" class="w-full h-full object-cover">
         <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
           <span class="text-white text-[20px]">🔍</span>
         </div>
       </div>
       <div class="px-4 py-2.5 text-[11px] text-gray-500 border-t border-gray-50 bg-gray-50/30 truncate">
         <?= __('আপলোডকারী:', 'By:') ?> <span class="font-medium text-gray-800"><?= e($photo['uploader_name'] ?: __('অ্যাডমিন', 'Admin')) ?></span>
       </div>
     </div>
     <?php endforeach; ?>

     <!-- Alpine Lightbox Modal with Arrows -->
     <div x-show="open" 
          class="fixed inset-0 z-[1000] bg-black/95 flex items-center justify-center p-4 select-none" 
          style="display:none;" 
          @keydown.escape.window="open = false"
          @keydown.arrow-left.window="if(open) index = index === 0 ? photos.length - 1 : index - 1"
          @keydown.arrow-right.window="if(open) index = index === photos.length - 1 ? 0 : index + 1">
       
       <!-- Close Button -->
       <button @click="open = false" class="absolute top-4 right-4 text-white hover:text-gray-300 text-[32px] p-2 focus:outline-none">✕</button>
       
       <!-- Prev Button -->
       <button @click="index = index === 0 ? photos.length - 1 : index - 1" 
               class="absolute left-4 top-1/2 -translate-y-1/2 w-12 h-12 rounded-full bg-white/10 hover:bg-white/20 text-white flex items-center justify-center text-[22px] transition-colors focus:outline-none">
         ‹
       </button>

       <!-- Main Image -->
       <div class="max-w-[85vw] max-h-[85vh] flex items-center justify-center">
         <img :src="photos[index]" class="max-w-full max-h-full object-contain rounded-lg shadow-2xl">
       </div>

       <!-- Next Button -->
       <button @click="index = index === photos.length - 1 ? 0 : index + 1" 
               class="absolute right-4 top-1/2 -translate-y-1/2 w-12 h-12 rounded-full bg-white/10 hover:bg-white/20 text-white flex items-center justify-center text-[22px] transition-colors focus:outline-none">
         ›
       </button>

       <!-- Counter -->
       <div class="absolute bottom-4 left-1/2 -translate-x-1/2 text-white/60 text-[13px] font-mono">
         <span x-text="index + 1"></span> / <span x-text="photos.length"></span>
       </div>

     </div>
  </div>
  <?php endif; ?>
</div>
