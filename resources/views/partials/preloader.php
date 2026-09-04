<!-- ══════════════════════════ GLOBAL ANIMATED LOADER ══════════════════════════ -->
<style>
#app-preloader {
  position: fixed;
  inset: 0;
  z-index: 99999;
  background: #FAFAFA;
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  transition: opacity 0.5s cubic-bezier(0.4, 0, 0.2, 1), visibility 0.5s ease;
}

#app-preloader.fade-out {
  opacity: 0;
  visibility: hidden;
  pointer-events: none;
}

@keyframes loaderPulse {
  0%, 100% { transform: scale(1); opacity: 0.9; }
  50% { transform: scale(1.08); opacity: 1; filter: drop-shadow(0 12px 28px rgba(128,0,32,0.3)); }
}

@keyframes spinRing {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}

@keyframes spinRingRev {
  0% { transform: rotate(360deg); }
  100% { transform: rotate(0deg); }
}

@keyframes lineProgress {
  0% { width: 0%; left: 0; }
  50% { width: 60%; left: 20%; }
  100% { width: 100%; left: 100%; }
}

.loader-logo-wrap {
  position: relative;
  width: 110px;
  height: 110px;
  display: flex;
  align-items: center;
  justify-content: center;
}

.loader-ring-1 {
  position: absolute;
  inset: 0;
  border-radius: 50%;
  border: 2.5px solid transparent;
  border-top-color: #800020;
  border-right-color: #800020;
  animation: spinRing 1.4s cubic-bezier(0.68, -0.55, 0.265, 1.55) infinite;
}

.loader-ring-2 {
  position: absolute;
  inset: -8px;
  border-radius: 50%;
  border: 2px solid transparent;
  border-bottom-color: #2F8863;
  border-left-color: rgba(47,136,99,0.4);
  animation: spinRingRev 2s ease-in-out infinite;
}

.loader-logo-img {
  width: 62px;
  height: 62px;
  object-fit: contain;
  animation: loaderPulse 1.8s ease-in-out infinite;
}

.loader-progress-bar {
  width: 140px;
  height: 3px;
  background: rgba(16,24,32,0.08);
  border-radius: 10px;
  overflow: hidden;
  position: relative;
  margin-top: 24px;
}

.loader-progress-inner {
  position: absolute;
  height: 100%;
  background: linear-gradient(90deg, #800020, #2F8863);
  border-radius: 10px;
  animation: lineProgress 1.5s ease-in-out infinite;
}
</style>

<div id="app-preloader">
  <div class="loader-logo-wrap">
    <div class="loader-ring-2"></div>
    <div class="loader-ring-1"></div>
    <img src="<?= asset('images/LOGO.png') ?>" alt="IPH Logo" class="loader-logo-img">
  </div>

  <div class="text-center mt-5">
    <div class="font-serif text-[17px] font-bold text-[#101820] tracking-wide">
      <?= env('APP_NAME', 'IPH Alumni Association') ?>
    </div>
    <div class="font-mono text-[10px] tracking-[0.2em] text-[#800020] uppercase mt-1">
      Institute of Public Health
    </div>
  </div>

  <div class="loader-progress-bar">
    <div class="loader-progress-inner"></div>
  </div>
</div>

<script>
(function(){
  const loader = document.getElementById('app-preloader');
  if(!loader) return;

  function hideLoader(){
    loader.classList.add('fade-out');
    setTimeout(() => loader.remove(), 600);
  }

  if (document.readyState === 'complete') {
    setTimeout(hideLoader, 350);
  } else {
    window.addEventListener('load', () => setTimeout(hideLoader, 350));
    // Fallback if load takes too long
    setTimeout(hideLoader, 2500);
  }
})();
</script>
