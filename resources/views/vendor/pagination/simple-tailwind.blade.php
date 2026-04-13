@if ($paginator->hasPages())
    <nav role="navigation" aria-label="Pagination Navigation" class="flex justify-between items-center bg-gray-100/50 p-1.5 rounded-2xl">
        {{-- Previous Page Link --}}
        @if ($paginator->onFirstPage())
            <span class="relative inline-flex items-center px-6 py-2.5 text-xs font-semibold uppercase tracking-wider text-gray-300 bg-white/50 cursor-default rounded-xl">
                {!! __('pagination.previous') !!}
            </span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="relative inline-flex items-center px-6 py-2.5 text-xs font-semibold uppercase tracking-wider text-gray-500 bg-white border border-gray-100 rounded-xl hover:text-indigo-600 hover:bg-gray-50 transition-all duration-200 active:scale-95 shadow-sm">
                {!! __('pagination.previous') !!}
            </a>
        @endif

        {{-- Next Page Link --}}
        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="relative inline-flex items-center px-6 py-2.5 text-xs font-semibold uppercase tracking-wider text-gray-500 bg-white border border-gray-100 rounded-xl hover:text-indigo-600 hover:bg-gray-50 transition-all duration-200 active:scale-95 shadow-sm">
                {!! __('pagination.next') !!}
            </a>
        @else
            <span class="relative inline-flex items-center px-6 py-2.5 text-xs font-semibold uppercase tracking-wider text-gray-300 bg-white/50 cursor-default rounded-xl">
                {!! __('pagination.next') !!}
            </span>
        @endif
    </nav>
@endif
