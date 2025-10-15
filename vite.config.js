import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
            ],
            refresh: true,
        }),
    ],
});


// import { defineConfig } from 'vite';
// import laravel from 'laravel-vite-plugin';

// export default defineConfig({
//     plugins: [
//         laravel({
//             input: ['resources/css/app.css', 'resources/js/app.js'],
//             refresh: true,
//         }),
//     ],
//     server: {
//         host: '0.0.0.0', // Permet l'accès depuis l'extérieur
//         hmr: {
//             host: '8c745fa7bf33.ngrok-free.app' // Votre URL ngrok
//         }
//     },
// });


// import { defineConfig } from 'vite';
// import laravel from 'laravel-vite-plugin';

// export default defineConfig({
//   plugins: [
//     laravel({
//       input: ['resources/css/app.css', 'resources/js/app.js'],
//       refresh: true,
//     }),
//   ],
//   server: {
//     host: '0.0.0.0',
//     port: 5173, // explicite
//     hmr: {
//       protocol: 'wss',
//       host: process.env.VITE_HMR_HOST || 'YOUR_VITE_NGROK_DOMAIN', // ex: 1234abcd-5173.ngrok-free.app
//       clientPort: 443
//     }
//   }
// });
