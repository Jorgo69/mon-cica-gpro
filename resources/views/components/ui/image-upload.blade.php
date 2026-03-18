@props([
    'label' => 'Photo',
    'preview' => null,
    'error' => null,
])

<div class="space-y-2">
    @if($label)
        <label class="block text-[11px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-wider ml-1">
            {{ $label }}
        </label>
    @endif

    <div 
        x-data="{ isUploading: false, progress: 0 }"
        x-on:livewire-upload-start="isUploading = true"
        x-on:livewire-upload-finish="isUploading = false"
        x-on:livewire-upload-error="isUploading = false"
        x-on:livewire-upload-progress="progress = $event.detail.progress"
        class="relative"
    >
        <div class="flex items-center gap-4 p-4 rounded-2xl bg-white dark:bg-slate-900 border-2 border-dashed border-slate-200 dark:border-slate-800 hover:border-accent/40 transition-all group overflow-hidden">
            
            {{-- Preview Area --}}
            <div class="relative shrink-0">
                @if ($preview)
                    <img src="{{ $preview instanceof \Livewire\Features\SupportFileUploads\TemporaryUploadedFile ? $preview->temporaryUrl() : asset('storage/'.$preview) }}" 
                         class="h-16 w-16 object-cover rounded-xl shadow-md border border-slate-100 dark:border-slate-800">
                @else
                    <div class="h-16 w-16 rounded-xl bg-slate-50 dark:bg-slate-800 border border-slate-100 dark:border-slate-800 flex items-center justify-center text-slate-300">
                        <x-lucide-camera class="w-8 h-8" />
                    </div>
                @endif
                
                {{-- Loading Overlay --}}
                <div x-show="isUploading" class="absolute inset-0 bg-slate-900/40 backdrop-blur-[2px] rounded-xl flex items-center justify-center">
                    <x-lucide-loader-2 class="w-6 h-6 text-white animate-spin" />
                </div>
            </div>

            {{-- Upload Content --}}
            <div class="flex-1 min-w-0">
                <input type="file" {{ $attributes->merge(['class' => 'hidden']) }} id="image-upload-{{ $attributes->get('wire:model') ?? md5($label) }}">
                <label for="image-upload-{{ $attributes->get('wire:model') ?? md5($label) }}" class="cursor-pointer block">
                    <div class="text-sm font-bold text-slate-700 dark:text-slate-200 group-hover:text-accent transition-colors">
                        {{ $preview ? 'Changer la photo' : 'Téléverser une photo' }}
                    </div>
                    <div class="text-[10px] text-slate-400 font-bold uppercase tracking-tight mt-0.5">
                        JPG, PNG ou GIF (Max. 2MB)
                    </div>
                </label>
            </div>

            {{-- Success/Error Indicator --}}
            @if ($preview && !$error)
                <div class="text-emerald-500 bg-emerald-500/10 p-1.5 rounded-full">
                    <x-lucide-check class="w-3.5 h-3.5" />
                </div>
            @endif
        </div>

        {{-- Upload Progress Bar --}}
        <div x-show="isUploading" class="absolute -bottom-[2px] left-2 right-2 h-1 bg-slate-100 dark:bg-slate-800 rounded-full overflow-hidden">
            <div class="h-full bg-accent transition-all duration-300" :style="'width: ' + progress + '%'"></div>
        </div>
    </div>

    @if($error)
        <p class="text-[10px] text-rose-500 font-bold italic mt-1.5 ml-1 uppercase tracking-tight">{{ $error }}</p>
    @endif
</div>
