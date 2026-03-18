<div x-data="toastHandler()" 
     @toast-notification.window="addToast($event.detail)"
     class="fixed top-24 right-5 z-[9999] flex flex-col gap-3 pointer-events-none">
    
    <template x-for="toast in toasts" :key="toast.id">
        <div x-show="toast.visible"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-x-full"
             x-transition:enter-end="opacity-100 translate-x-0"
             x-transition:leave="transition ease-in duration-300"
             x-transition:leave-start="opacity-100 scale-100 translate-x-0"
             x-transition:leave-end="opacity-0 scale-95 translate-x-4"
             class="pointer-events-auto flex items-start gap-4 p-4 w-[350px] rounded-2xl shadow-2xl shadow-slate-200/50 dark:shadow-black/50 border bg-white dark:bg-slate-900 border-slate-100 dark:border-slate-800"
             :class="{
                'border-l-4 border-l-emerald-500': toast.type === 'success',
                'border-l-4 border-l-rose-500': toast.type === 'error',
                'border-l-4 border-l-blue-500': toast.type === 'info',
                'border-l-4 border-l-amber-500': toast.type === 'warning'
             }">
            
            <div class="flex-shrink-0 mt-0.5" 
                 :class="{
                    'text-emerald-500': toast.type === 'success',
                    'text-rose-500': toast.type === 'error',
                    'text-blue-500': toast.type === 'info',
                    'text-amber-500': toast.type === 'warning'
                 }">
                <i class="fas text-xl" 
                   :class="{
                      'fa-check-circle': toast.type === 'success',
                      'fa-exclamation-circle': toast.type === 'error',
                      'fa-info-circle': toast.type === 'info',
                      'fa-exclamation-triangle': toast.type === 'warning'
                   }"></i>
            </div>
            
            <div class="flex-1 min-w-0">
                <h4 class="text-[11px] font-black uppercase tracking-widest text-slate-800 dark:text-slate-100 mb-0.5" 
                    x-text="toast.title || (toast.type === 'success' ? 'Succès' : (toast.type === 'error' ? 'Erreur' : (toast.type === 'warning' ? 'Attention' : 'Notification')))"></h4>
                <p class="text-[13px] font-medium text-slate-500 dark:text-slate-400 mt-0.5 leading-snug" x-text="toast.message"></p>
            </div>
            
            <button @click="removeToast(toast.id)" class="flex-shrink-0 text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 transition-colors mt-0.5">
                <i class="fas fa-times text-sm"></i>
            </button>
        </div>
    </template>
</div>

@push('scripts')
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('toastHandler', () => ({
            toasts: [],
            init() {
                @if(session()->has('toast_notification'))
                    this.addToast(@json(session('toast_notification')));
                @endif
            },
            addToast(data) {
                const toast = {
                    id: Date.now() + Math.random().toString(36).substr(2, 9),
                    visible: true,
                    type: data.type || 'info',
                    message: data.message || '',
                    title: data.title || null,
                    duration: data.duration || 5000
                };
                
                this.toasts.push(toast);

                if (toast.duration !== 0) {
                    setTimeout(() => {
                        this.removeToast(toast.id);
                    }, toast.duration);
                }
            },
            removeToast(id) {
                const index = this.toasts.findIndex(t => t.id === id);
                if (index !== -1) {
                    this.toasts[index].visible = false;
                    setTimeout(() => {
                        this.toasts = this.toasts.filter(t => t.id !== id);
                    }, 300); // Wait for exit transition
                }
            }
        }));
    });
</script>
@endpush
