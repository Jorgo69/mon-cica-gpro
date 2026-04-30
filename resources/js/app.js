import './bootstrap';
import collapse from '@alpinejs/collapse';
import richEditor from './components/rich-editor';
import './firebase-push';

// Livewire 3 manages Alpine.js. We just need to register our plugins before it starts.
document.addEventListener('alpine:init', () => {
    window.Alpine.plugin(collapse);
    window.Alpine.data('richEditor', richEditor);
});
