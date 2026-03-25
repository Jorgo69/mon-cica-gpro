@props([
    'name'        => '',
    'value'       => '',
    'label'       => null,
    'required'    => false,
    'placeholder' => 'Écrivez ici...',
    'height'      => 200,
])

@php
    $uid      = 'editor-' . preg_replace('/[^a-z0-9]/', '-', strtolower($name));
    $hasError = $errors->has($name);
    $borderClass = $hasError
        ? 'border-rose-500 ring-2 ring-rose-500/20'
        : 'border-slate-200 dark:border-slate-700';
@endphp

<div class="space-y-2">
    @if ($label)
        <label class="block text-[11px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-[0.15em] ml-1">
            {{ $label }}@if ($required) <span class="text-rose-500">*</span>@endif
        </label>
    @endif

    {{-- Editor container — Livewire ne touche pas ce bloc --}}
    <div
        wire:ignore
        x-data="richEditor({ uniqueId: '{{ $uid }}', content: {{ Js::from($value ?? '') }}, placeholder: '{{ $placeholder }}' })"
        class="rounded-xl overflow-hidden border {{ $borderClass }} shadow-sm bg-white dark:bg-slate-900"
    >
        {{-- Barre d'outils --}}
        <div class="flex flex-wrap items-center gap-0.5 px-3 py-2 border-b border-slate-100 dark:border-slate-800 bg-slate-50 dark:bg-slate-800/50">

            {{-- Titres --}}
            <button type="button" @mousedown.prevent @click="setHeading(1)"
                :class="updatedAt && isActive('heading', { level: 1 }) ? 'bg-accent/10 text-accent' : 'text-slate-500 dark:text-slate-400 hover:text-slate-800 dark:hover:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-700'"
                class="p-1.5 rounded text-[11px] font-black transition-all leading-none">H1</button>

            <button type="button" @mousedown.prevent @click="setHeading(2)"
                :class="updatedAt && isActive('heading', { level: 2 }) ? 'bg-accent/10 text-accent' : 'text-slate-500 dark:text-slate-400 hover:text-slate-800 dark:hover:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-700'"
                class="p-1.5 rounded text-[11px] font-black transition-all leading-none">H2</button>

            <div class="w-px h-4 bg-slate-200 dark:bg-slate-700 mx-1"></div>

            {{-- Format --}}
            <button type="button" @mousedown.prevent @click="toggleBold()"
                :class="updatedAt && isActive('bold') ? 'bg-accent/10 text-accent' : 'text-slate-500 dark:text-slate-400 hover:text-slate-800 dark:hover:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-700'"
                class="p-1.5 rounded text-[11px] font-black transition-all leading-none">B</button>

            <button type="button" @mousedown.prevent @click="toggleItalic()"
                :class="updatedAt && isActive('italic') ? 'bg-accent/10 text-accent' : 'text-slate-500 dark:text-slate-400 hover:text-slate-800 dark:hover:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-700'"
                class="p-1.5 rounded text-[11px] italic font-bold transition-all leading-none">I</button>

            <button type="button" @mousedown.prevent @click="toggleUnderline()"
                :class="updatedAt && isActive('underline') ? 'bg-accent/10 text-accent' : 'text-slate-500 dark:text-slate-400 hover:text-slate-800 dark:hover:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-700'"
                class="p-1.5 rounded text-[11px] font-black underline transition-all leading-none">U</button>

            <button type="button" @mousedown.prevent @click="toggleStrike()"
                :class="updatedAt && isActive('strike') ? 'bg-accent/10 text-accent' : 'text-slate-500 dark:text-slate-400 hover:text-slate-800 dark:hover:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-700'"
                class="p-1.5 rounded text-[11px] font-black line-through transition-all leading-none">S</button>

            <div class="w-px h-4 bg-slate-200 dark:bg-slate-700 mx-1"></div>

            {{-- Listes --}}
            <button type="button" @mousedown.prevent @click="toggleBulletList()"
                :class="updatedAt && isActive('bulletList') ? 'bg-accent/10 text-accent' : 'text-slate-500 dark:text-slate-400 hover:text-slate-800 dark:hover:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-700'"
                class="p-1.5 rounded text-[11px] font-black transition-all leading-none">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" d="M8 6h13M8 12h13M8 18h13M3 6h.01M3 12h.01M3 18h.01"/>
                </svg>
            </button>

            <button type="button" @mousedown.prevent @click="toggleOrderedList()"
                :class="updatedAt && isActive('orderedList') ? 'bg-accent/10 text-accent' : 'text-slate-500 dark:text-slate-400 hover:text-slate-800 dark:hover:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-700'"
                class="p-1.5 rounded text-[11px] font-black transition-all leading-none">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" d="M10 6h11M10 12h11M10 18h11M4 6h.01M4 12h.01M4 18h.01"/>
                    <path d="M4 4v4M3 8h2" stroke-linecap="round"/>
                </svg>
            </button>

            {{-- Citation --}}
            <button type="button" @mousedown.prevent @click="toggleBlockquote()"
                :class="updatedAt && isActive('blockquote') ? 'bg-accent/10 text-accent' : 'text-slate-500 dark:text-slate-400 hover:text-slate-800 dark:hover:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-700'"
                class="p-1.5 rounded text-[13px] font-black transition-all leading-none">"</button>

            <div class="w-px h-4 bg-slate-200 dark:bg-slate-700 mx-1"></div>

            {{-- Effacer formatage --}}
            <button type="button" @mousedown.prevent @click="clearFormatting()"
                class="p-1.5 rounded text-[11px] font-black transition-all leading-none text-slate-400 dark:text-slate-500 hover:text-slate-700 dark:hover:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-700"
                title="Effacer le formatage">✕</button>
        </div>

        {{-- Zone d'édition (TipTap mount point) --}}
        <div x-ref="editorContent" style="min-height: {{ $height }}px"></div>
    </div>

    {{-- Textarea cachée hors de wire:ignore — Livewire lit la valeur via wire:model.defer --}}
    <textarea
        id="{{ $uid }}"
        wire:model.defer="{{ $name }}"
        class="hidden"
        aria-hidden="true"
    >{{ $value }}</textarea>

    @error($name)
        <p class="text-[10px] text-rose-500 font-bold italic mt-1.5 ml-1 uppercase tracking-tight">{{ $message }}</p>
    @enderror
</div>
