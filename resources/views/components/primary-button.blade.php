<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center px-4 py-2 bg-surface-alt dark:bg-border border border-transparent rounded-md font-semibold text-xs text-white dark:text-heading uppercase tracking-widest hover:bg-surface-alt dark:hover:bg-white focus:bg-surface-alt dark:focus:bg-white active:bg-surface dark:active:bg-border focus:outline-none focus:ring-2 focus:ring-accent focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150']) }}>
    {{ $slot }}
</button>
