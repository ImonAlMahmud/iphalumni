const CACHE_NAME = 'iph-alumni-v1.0';
const OFFLINE_URL = 'offline.html';

const PRECACHE_ASSETS = [
  'offline.html',
  'manifest.webmanifest',
  'images/LOGO.png',
  'css/app.css'
];

// Install Event — precache core offline assets
self.addEventListener('install', (event) => {
  event.waitUntil(
    caches.open(CACHE_NAME).then((cache) => {
      return cache.addAll(PRECACHE_ASSETS).catch((err) => {
        console.warn('Pre-caching assets warning:', err);
      });
    }).then(() => self.skipWaiting())
  );
});

// Activate Event — cleanup old caches
self.addEventListener('activate', (event) => {
  event.waitUntil(
    caches.keys().then((keys) => {
      return Promise.all(
        keys.map((key) => {
          if (key !== CACHE_NAME) {
            return caches.delete(key);
          }
        })
      );
    }).then(() => self.clients.claim())
  );
});

// Fetch Event — Network-first for pages, cache-first/stale for assets
self.addEventListener('fetch', (event) => {
  const req = event.request;

  // Only handle GET requests
  if (req.method !== 'GET') return;

  const url = new URL(req.url);

  // HTML navigation requests
  if (req.mode === 'navigate') {
    event.respondWith(
      fetch(req).catch(() => {
        return caches.match(OFFLINE_URL);
      })
    );
    return;
  }

  // Static Assets (images, css, js, fonts)
  if (
    url.pathname.startsWith('/images/') ||
    url.pathname.startsWith('/css/') ||
    url.pathname.startsWith('/js/') ||
    url.hostname.includes('fonts.googleapis.com') ||
    url.hostname.includes('fonts.gstatic.com') ||
    url.hostname.includes('fonts.maateen.me')
  ) {
    event.respondWith(
      caches.match(req).then((cached) => {
        if (cached) return cached;
        return fetch(req).then((response) => {
          if (response && response.status === 200 && response.type === 'basic') {
            const respClone = response.clone();
            caches.open(CACHE_NAME).then((c) => c.put(req, respClone));
          }
          return response;
        }).catch(() => null);
      })
    );
  }
});
