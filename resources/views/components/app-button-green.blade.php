<button {{ $attributes->merge(['type' => 'button', 'class' => 'inline-flex items-center px-4 py-2 bg-green-400/20 dark:bg-green-400/10 border border-transparent rounded-md font-semibold text-xs text-green-800 dark:text-green-500 uppercase tracking-widest hover:bg-green-400/60 dark:hover:bg-green-400/25 dark:active:bg-green-700 focus:outline-none focus:ring-2 dark:focus:ring-green-400/20 focus:ring-offset-2 focus:ring-offset-gray-800 transition ease-in-out duration-150']) }}>
    {{ $slot }}
</button>
