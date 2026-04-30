/**
 * Firebase Cloud Messaging - Push Notifications
 *
 * Initialise Firebase, demande la permission push,
 * et envoie le token FCM au backend.
 */

const FIREBASE_CONFIG = {
    apiKey: import.meta.env.VITE_FIREBASE_API_KEY,
    projectId: import.meta.env.VITE_FIREBASE_PROJECT_ID,
    messagingSenderId: import.meta.env.VITE_FIREBASE_MESSAGING_SENDER_ID,
    appId: import.meta.env.VITE_FIREBASE_APP_ID,
};

const VAPID_KEY = import.meta.env.VITE_FIREBASE_VAPID_KEY;

// Skip if Firebase not configured
if (!FIREBASE_CONFIG.apiKey || !FIREBASE_CONFIG.projectId) {
    console.log('[FCM] Firebase not configured, skipping push setup.');
} else {
    initFirebasePush();
}

async function initFirebasePush() {
    // Check browser support
    if (!('serviceWorker' in navigator) || !('PushManager' in window)) {
        console.log('[FCM] Push not supported in this browser.');
        return;
    }

    try {
        // Dynamic import Firebase (tree-shakeable)
        const { initializeApp } = await import('firebase/app');
        const { getMessaging, getToken, onMessage } = await import('firebase/messaging');

        const app = initializeApp(FIREBASE_CONFIG);
        const messaging = getMessaging(app);

        // Register service worker
        const registration = await navigator.serviceWorker.register('/firebase-messaging-sw.js');

        // Send config to service worker
        if (registration.active) {
            registration.active.postMessage({
                type: 'FIREBASE_CONFIG',
                config: FIREBASE_CONFIG,
            });
        }

        // Request permission
        const permission = await Notification.requestPermission();
        if (permission !== 'granted') {
            console.log('[FCM] Permission denied.');
            return;
        }

        // Get FCM token
        const token = await getToken(messaging, {
            vapidKey: VAPID_KEY,
            serviceWorkerRegistration: registration,
        });

        if (token) {
            await sendTokenToServer(token);
        }

        // Handle foreground messages
        onMessage(messaging, (payload) => {
            const notification = payload.notification || {};
            const data = payload.data || {};

            // Show toast via Alpine/Livewire
            if (window.Livewire) {
                window.Livewire.dispatch('toast-notification', {
                    type: 'info',
                    message: notification.body || 'Nouvelle notification',
                    title: notification.title || 'CICA-GPRO',
                });
            }

            // Also show native notification if page is not focused
            if (document.hidden && Notification.permission === 'granted') {
                new Notification(notification.title || 'CICA-GPRO', {
                    body: notification.body || '',
                    icon: '/avatars/avatar-1.svg',
                    data: { action_url: data.action_url || '/' },
                });
            }
        });

    } catch (error) {
        console.warn('[FCM] Init error:', error.message);
    }
}

async function sendTokenToServer(token) {
    try {
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
        if (!csrfToken) return;

        await fetch('/api/fcm-tokens', {
            method: 'POST',
            credentials: 'same-origin',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
            },
            body: JSON.stringify({
                token: token,
                device_name: navigator.userAgent.substring(0, 100),
                platform: 'web',
            }),
        });

        console.log('[FCM] Token registered.');
    } catch (error) {
        console.warn('[FCM] Failed to send token:', error.message);
    }
}
