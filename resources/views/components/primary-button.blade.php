<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center px-8 py-3 bg-primary border border-transparent rounded-xl font-bold text-xs text-white uppercase tracking-widest hover:opacity-90 active:scale-95 focus:outline-none focus:ring-2 focus:ring-primary transition transform ease-in-out duration-150 shadow-lg shadow-primary/20']) }}>
    {{ $slot }}
</button>
