@props(['total', 'perPage' => 10])

@if($total > $perPage)
<div class="px-4 py-3 border-t border-gray-50 flex items-center justify-between" x-show="totalPages > 1">
    <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">
        Showing <span x-text="from"></span>–<span x-text="to"></span> of <span x-text="total"></span>
    </span>
    <div class="flex items-center gap-1.5">
        {{-- Prev --}}
        <button @click="page = Math.max(1, page - 1)"
                :disabled="page === 1"
                :class="page === 1 ? 'opacity-30 cursor-not-allowed' : 'hover:bg-gray-50'"
                class="h-8 w-8 flex items-center justify-center rounded-lg border border-gray-100 text-gray-400 transition">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/>
            </svg>
        </button>

        {{-- Page Numbers --}}
        <template x-for="p in totalPages" :key="p">
            <button @click="page = p"
                    :class="page === p ? 'bg-primary text-white shadow-lg shadow-primary/20' : 'bg-white border border-gray-100 text-gray-500 hover:bg-gray-50'"
                    class="h-8 w-8 flex items-center justify-center rounded-lg text-[10px] font-black transition">
                <span x-text="p"></span>
            </button>
        </template>

        {{-- Next --}}
        <button @click="page = Math.min(totalPages, page + 1)"
                :disabled="page === totalPages"
                :class="page === totalPages ? 'opacity-30 cursor-not-allowed' : 'hover:bg-gray-50'"
                class="h-8 w-8 flex items-center justify-center rounded-lg border border-gray-100 text-gray-400 transition">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/>
            </svg>
        </button>
    </div>
</div>
@endif
