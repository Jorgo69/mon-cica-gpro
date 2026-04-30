<button {{ $attributes->merge(['type' => 'button', 'class' => 'inline-flex items-center px-4 py-2 bg-card border border-border dark:border-border rounded-md font-semibold text-xs text-body uppercase tracking-widest shadow-sm hover:bg-surface dark:hover:bg-surface-alt focus:outline-none focus:ring-2 focus:ring-accent focus:ring-offset-2 dark:focus:ring-offset-gray-800 disabled:opacity-25 transition ease-in-out duration-150']) }}>
    {{ $slot }}
</button>
