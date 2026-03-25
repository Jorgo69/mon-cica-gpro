import './bootstrap';
import collapse from '@alpinejs/collapse';
import richEditor from './components/rich-editor';

document.addEventListener('alpine:init', () => {
    window.Alpine.plugin(collapse);
    window.Alpine.data('richEditor', richEditor);
});
