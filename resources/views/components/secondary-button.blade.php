<button {{ $attributes->merge(['type' => 'button', 'class' => 'inline-flex items-center px-8 py-3 bg-white border border-gray-200 rounded-xl font-bold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-25 transition transform active:scale-95 ease-in-out duration-150']) }}>
    {{ $slot }}
</button>
