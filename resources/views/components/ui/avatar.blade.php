@props([
    'user' => null,
    'size' => 'md',
    'class' => '',
])

@php
    $sizes = [
        'xs' => 'w-6 h-6 text-[8px]',
        'sm' => 'w-8 h-8 text-[10px]',
        'md' => 'w-10 h-10 text-xs',
        'lg' => 'w-14 h-14 text-sm',
        'xl' => 'w-20 h-20 text-lg',
    ];
    $sizeClass = $sizes[$size] ?? $sizes['md'];

    $avatarKey = null;
    $initials = '?';
    $name = '';

    if ($user) {
        $name = $user->name ?? '';
        $initials = collect(explode(' ', $name))->map(fn($w) => mb_substr($w, 0, 1))->take(2)->implode('');
        $avatarKey = $user->getMeta('avatar') ?? null;
    }
@endphp

@if($avatarKey && file_exists(public_path("avatars/{$avatarKey}.svg")))
    <img src="{{ asset("avatars/{$avatarKey}.svg") }}"
        alt="{{ $name }}"
        title="{{ $name }}"
        class="{{ $sizeClass }} rounded-full object-cover {{ $class }}" />
@else
    <div class="{{ $sizeClass }} rounded-full bg-accent/10 text-accent flex items-center justify-center font-black uppercase {{ $class }}"
        title="{{ $name }}">
        {{ $initials }}
    </div>
@endif
