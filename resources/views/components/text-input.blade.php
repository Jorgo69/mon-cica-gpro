@props(['disabled' => false])

<input {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge(['class' => 'border-border dark:bg-surface dark:text-body focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-accent dark:focus:ring-indigo-600 rounded-md shadow-sm']) !!}>
