<!-- Hidden Google Translate Element -->
<div id="google_translate_element" style="display:none;position:absolute;top:-9999px;left:-9999px;visibility:hidden;width:0;height:0;overflow:hidden;"></div>

<style>
/* ── Google Translate Overrides: Completely Invisible Top Bar & Banner ── */
.goog-te-banner-frame.skiptranslate,
.goog-te-banner-frame,
iframe.goog-te-banner-frame,
.goog-te-gadget-simple,
.VIpgJd-ZVi9od-aZ2wEe-wOHMyf,
.VIpgJd-ZVi9od-aZ2wEe-OiiCO,
.VIpgJd-ZVi9od-ORHb-OwmBvb,
.VIpgJd-ZVi9od-l4eHX-hSRGPd,
.VIpgJd-y6EKJ-eEGn0b,
#goog-gt-tt,
.goog-te-balloon-frame {
  display: none !important;
  visibility: hidden !important;
  height: 0 !important;
  width: 0 !important;
  opacity: 0 !important;
  pointer-events: none !important;
}

body {
  top: 0px !important;
  position: static !important;
}

.goog-tooltip, .goog-tooltip:hover {
  display: none !important;
}

.goog-text-highlight {
  background-color: transparent !important;
  border: none !important;
  box-shadow: none !important;
}

#google_translate_element {
  display: none !important;
}

.skiptranslate:not(.keep-visible) {
  display: none !important;
}
</style>

<script type="text/javascript">
function googleTranslateElementInit() {
  new google.translate.TranslateElement({
    pageLanguage: 'bn',
    includedLanguages: 'en,bn',
    autoDisplay: false,
    layout: google.translate.TranslateElement.InlineLayout.SIMPLE
  }, 'google_translate_element');
}

function getGoogleTranslateCookie() {
  const match = document.cookie.match(/(^|;\s*)googtrans=([^;]+)/);
  return match ? decodeURIComponent(match[2]) : '';
}

function isCurrentlyEnglish() {
  const cookie = getGoogleTranslateCookie();
  return cookie.includes('/en') || cookie.endsWith('en');
}

function toggleGoogleTranslate() {
  const host = window.location.hostname;
  const hostParts = host.split('.');
  const domainVariants = ['', host, '.' + host];
  
  if (hostParts.length > 2) {
    const rootDomain = hostParts.slice(1).join('.');
    domainVariants.push(rootDomain);
    domainVariants.push('.' + rootDomain);
  }

  if (isCurrentlyEnglish()) {
    // ── Turn OFF Translation: Restore original Bangla ──
    domainVariants.forEach(dom => {
      const dStr = dom ? `; domain=${dom}` : '';
      document.cookie = `googtrans=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/${dStr}`;
      document.cookie = `googtrans=; Max-Age=0; path=/${dStr}`;
      document.cookie = `googtrans=/bn/bn; path=/${dStr}`;
    });

    try {
      const banner = document.querySelector('iframe.goog-te-banner-frame');
      if (banner && banner.contentWindow) {
        const restoreBtn = banner.contentWindow.document.querySelector('.goog-te-button button, [id*="restore"]');
        if (restoreBtn) restoreBtn.click();
      }
    } catch(e) {}

    const select = document.querySelector('.goog-te-combo');
    if (select) {
      select.value = '';
      select.dispatchEvent(new Event('change'));
    }

    setTimeout(() => {
      window.location.reload();
    }, 50);
  } else {
    // ── Turn ON Translation: Translate to English ──
    domainVariants.forEach(dom => {
      const dStr = dom ? `; domain=${dom}` : '';
      document.cookie = `googtrans=/bn/en; path=/${dStr}`;
    });

    const select = document.querySelector('.goog-te-combo');
    if (select) {
      select.value = 'en';
      select.dispatchEvent(new Event('change'));
    }

    setTimeout(() => {
      window.location.reload();
    }, 50);
  }
}

// Update translation toggle labels (Ensure notranslate is preserved)
(function() {
  function updateLabels() {
    const isEn = isCurrentlyEnglish();
    const labels = document.querySelectorAll('.gt-lang-label');
    labels.forEach(el => {
      el.textContent = isEn ? 'বাংলা' : 'English';
    });
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', updateLabels);
  } else {
    updateLabels();
  }
  
  // Re-check after Google Translate finishes loading
  window.addEventListener('load', updateLabels);
})();
</script>
<script type="text/javascript" src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>
