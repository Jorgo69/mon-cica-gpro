// Firebase Messaging Service Worker
// Ce fichier doit rester dans /public/ (racine web)

importScripts('https://www.gstatic.com/firebasejs/10.12.0/firebase-app-compat.js');
importScripts('https://www.gstatic.com/firebasejs/10.12.0/firebase-messaging-compat.js');

// La config est injectee dynamiquement via le message du client
let firebaseConfig = null;

self.addEventListener('message', (event) => {
    if (event.data && event.data.type === 'FIREBASE_CONFIG') {
        firebaseConfig = event.data.config;
        firebase.initializeApp(firebaseConfig);
        firebase.messaging();
    }
});

// Handle background messages
self.addEventListener('push', (event) => {
    if (!event.data) return;

    const payload = event.data.json();
    const notification = payload.notification || {};
    const data = payload.data || {};

    const title = notification.title || 'CICA-GPRO';
    const options = {
        body: notification.body || '',
        icon: '/avatars/avatar-1.svg',
        badge: '/avatars/avatar-1.svg',
        data: {
            action_url: data.action_url || '/',
        },
        tag: data.type || 'general',
        renotify: true,
    };

    event.waitUntil(self.registration.showNotification(title, options));
});

// Handle notification click
self.addEventListener('notificationclick', (event) => {
    event.notification.close();

    const url = event.notification.data?.action_url || '/';

    event.waitUntil(
        clients.matchAll({ type: 'window', includeUncontrolled: true }).then((clientList) => {
            // Focus existing tab if found
            for (const client of clientList) {
                if (client.url.includes(self.location.origin) && 'focus' in client) {
                    client.navigate(url);
                    return client.focus();
                }
            }
            // Open new tab
            return clients.openWindow(url);
        })
    );
});
