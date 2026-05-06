import './bootstrap';
import Alpine from 'alpinejs';
import collapse from '@alpinejs/collapse';
import richEditor from './components/rich-editor';
import './firebase-push';

// Register plugins before Alpine starts (works for both standalone and Livewire pages)
document.addEventListener('alpine:init', () => {
    window.Alpine.plugin(collapse);
    window.Alpine.data('richEditor', richEditor);
});

// On pages without Livewire (e.g. landing page), Alpine is not auto-started.
// Start it manually if Livewire hasn't already claimed it.
if (!window.Alpine) {
    window.Alpine = Alpine;
    Alpine.plugin(collapse);
    Alpine.data('richEditor', richEditor);
    Alpine.start();
}
