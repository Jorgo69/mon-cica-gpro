const CACHE_NAME = 'gpro-v1';
const OFFLINE_URL = '/offline';

// Assets to pre-cache
const PRECACHE_URLS = [
    '/offline',
    '/icons/icon-192x192.png',
    '/icons/icon-512x512.png',
];

// Install: pre-cache essential assets
self.addEventListener('install', (event) => {
    event.waitUntil(
        caches.open(CACHE_NAME).then((cache) => {
            return cache.addAll(PRECACHE_URLS);
        })
    );
    self.skipWaiting();
});

// Activate: clean old caches
self.addEventListener('activate', (event) => {
    event.waitUntil(
        caches.keys().then((keys) => {
            return Promise.all(
                keys.filter((key) => key !== CACHE_NAME).map((key) => caches.delete(key))
            );
        })
    );
    self.clients.claim();
});

// Fetch: network-first, fallback to cache, then offline page
self.addEventListener('fetch', (event) => {
    // Skip non-GET requests
    if (event.request.method !== 'GET') return;

    // Skip API requests and Livewire updates
    if (event.request.url.includes('/livewire/') ||
        event.request.url.includes('/api/') ||
        event.request.url.includes('_debugbar')) {
        return;
    }

    event.respondWith(
        fetch(event.request)
            .then((response) => {
                // Cache successful responses for static assets
                if (response.ok && (
                    event.request.url.includes('/build/') ||
                    event.request.url.includes('/icons/') ||
                    event.request.url.includes('/avatars/')
                )) {
                    const clone = response.clone();
                    caches.open(CACHE_NAME).then((cache) => cache.put(event.request, clone));
                }
                return response;
            })
            .catch(() => {
                // Try cache first
                return caches.match(event.request).then((cached) => {
                    if (cached) return cached;

                    // For navigation requests, show offline page
                    if (event.request.mode === 'navigate') {
                        return caches.match(OFFLINE_URL);
                    }

                    return new Response('Offline', { status: 503 });
                });
            })
    );
});

// Listen for push notifications (FCM integration)
self.addEventListener('push', (event) => {
    if (!event.data) return;

    const data = event.data.json();
    const options = {
        body: data.body || '',
        icon: '/icons/icon-192x192.png',
        badge: '/icons/icon-72x72.png',
        data: { url: data.click_action || '/dashboard' },
        vibrate: [200, 100, 200],
    };

    event.waitUntil(
        self.registration.showNotification(data.title || 'CICA-GPRO', options)
    );
});

// Click on notification -> open the app
self.addEventListener('notificationclick', (event) => {
    event.notification.close();
    const url = event.notification.data?.url || '/dashboard';
    event.waitUntil(
        clients.matchAll({ type: 'window' }).then((clientList) => {
            for (const client of clientList) {
                if (client.url.includes(url) && 'focus' in client) {
                    return client.focus();
                }
            }
            return clients.openWindow(url);
        })
    );
});
