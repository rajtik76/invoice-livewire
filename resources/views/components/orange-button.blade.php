<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center px-4 py-2 bg-amber-400/20 dark:bg-amber-400/10 border border-transparent rounded-md font-semibold text-xs text-amber-800 dark:text-amber-500 uppercase tracking-widest hover:bg-amber-400/60 dark:hover:bg-amber-400/25 dark:active:bg-amber-700 focus:outline-none focus:ring-2 dark:focus:ring-amber-400/20 focus:ring-offset-2 focus:ring-offset-gray-800 transition ease-in-out duration-150']) }}>
    {{ $slot }}
</button>
