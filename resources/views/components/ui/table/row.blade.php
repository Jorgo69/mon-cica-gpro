@props(['striped' => false])

<tr {{ $attributes->merge(['class' => 'transition-colors hover:bg-slate-50/50 dark:hover:bg-slate-800/30' . ($striped ? ' even:bg-slate-50/30 dark:even:bg-slate-800/10' : '')]) }}>
    {{ $slot }}
</tr>
