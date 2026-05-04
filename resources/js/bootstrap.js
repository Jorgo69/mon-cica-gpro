/**
 * We'll load the axios HTTP library which allows us to easily issue requests
 * to our Laravel back-end. This library automatically handles sending the
 * CSRF token as a header based on the value of the "XSRF" token cookie.
 */

import axios from 'axios';
window.axios = axios;

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

/**
 * Echo exposes an expressive API for subscribing to channels and listening
 * for events that are broadcast by Laravel. Echo and event broadcasting
 * allows your team to easily build robust real-time web applications.
 *
 * Broadcasting is OFF by default (BROADCAST_DRIVER=log/null).
 * To enable: set BROADCAST_DRIVER=reverb and configure VITE_REVERB_* vars.
 */

if (import.meta.env.VITE_REVERB_APP_KEY) {
    import('pusher-js').then((Pusher) => {
        window.Pusher = Pusher.default;

        import('laravel-echo').then((Echo) => {
            window.Echo = new Echo.default({
                broadcaster: 'reverb',
                key: import.meta.env.VITE_REVERB_APP_KEY,
                wsHost: import.meta.env.VITE_REVERB_HOST,
                wsPort: import.meta.env.VITE_REVERB_PORT ?? 80,
                wssPort: import.meta.env.VITE_REVERB_PORT ?? 443,
                forceTLS: (import.meta.env.VITE_REVERB_SCHEME ?? 'https') === 'https',
                enabledTransports: ['ws', 'wss'],
            });

            // Dispatch event so Alpine/Livewire components know Echo is ready
            window.dispatchEvent(new CustomEvent('echo-ready'));
        });
    });
}
