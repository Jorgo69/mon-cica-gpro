import './bootstrap';
import collapse from '@alpinejs/collapse';

// Livewire 3 manages Alpine.js. We just need to register our plugins before it starts.
document.addEventListener('alpine:init', () => {
    window.Alpine.plugin(collapse);
});
