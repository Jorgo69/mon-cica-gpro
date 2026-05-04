@props(['wireModel' => 'permissionLevel', 'selected' => null, 'error' => null])

<div>
    <label class="text-xs font-bold text-heading uppercase tracking-wider block mb-2 ml-1">
        {{ __('admin.members.permission_level') }}
    </label>

    <div class="grid grid-cols-2 sm:grid-cols-4 gap-2">
        @foreach(\App\Enums\PermissionLevel::cases() as $level)
            <button type="button"
                wire:click="$set('{{ $wireModel }}', {{ $level->value }})"
                class="relative p-3 rounded-xl border-2 text-center transition-all
                    {{ (int)$selected === $level->value
                        ? 'border-accent bg-accent/5 shadow-sm ring-1 ring-accent/20'
                        : 'border-border-light bg-card hover:border-accent/30' }}">

                {{-- Level badge --}}
                <div class="absolute -top-2 -right-2 w-5 h-5 rounded-full {{ $level->bgColor() }} {{ $level->color() }} text-[9px] font-black flex items-center justify-center border border-white dark:border-gray-800">
                    {{ $level->value }}
                </div>

                {{-- Icon --}}
                <div class="w-8 h-8 mx-auto mb-1.5 rounded-lg flex items-center justify-center {{ $level->bgColor() }}">
                    <x-dynamic-component :component="'lucide-' . $level->icon()" class="w-4 h-4 {{ $level->color() }}" />
                </div>

                {{-- Label --}}
                <p class="text-[10px] font-bold {{ (int)$selected === $level->value ? 'text-accent' : 'text-heading' }}">
                    {{ $level->label() }}
                </p>

                {{-- Description --}}
                <p class="text-[8px] text-muted mt-0.5 leading-tight">
                    {{ $level->description() }}
                </p>
            </button>
        @endforeach
    </div>

    @if($error)
        <p class="text-xs text-error mt-1">{{ $error }}</p>
    @endif
</div>
