@if ($paginator->hasPages())
    <nav role="navigation" aria-label="{{ __('Pagination Navigation') }}" class="flex items-center justify-between">
        {{-- Mobile View --}}
        <div class="flex justify-between flex-1 sm:hidden">
            @if ($paginator->onFirstPage())
                <span class="relative inline-flex items-center px-6 py-2.5 text-xs font-semibold uppercase tracking-wider text-gray-300 bg-white border border-gray-100 cursor-default rounded-xl">
                    {!! __('pagination.previous') !!}
                </span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" class="relative inline-flex items-center px-6 py-2.5 text-xs font-semibold uppercase tracking-wider text-gray-500 bg-white border border-gray-100 rounded-xl hover:bg-gray-50 transition active:scale-95 shadow-sm">
                    {!! __('pagination.previous') !!}
                </a>
            @endif

            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" class="relative inline-flex items-center px-6 py-2.5 text-xs font-semibold uppercase tracking-wider text-gray-500 bg-white border border-gray-100 rounded-xl hover:bg-gray-50 transition active:scale-95 shadow-sm">
                    {!! __('pagination.next') !!}
                </a>
            @else
                <span class="relative inline-flex items-center px-6 py-2.5 text-xs font-semibold uppercase tracking-wider text-gray-300 bg-white border border-gray-100 cursor-default rounded-xl">
                    {!! __('pagination.next') !!}
                </span>
            @endif
        </div>

        {{-- Desktop View --}}
        <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
            <div>
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider flex items-center gap-1.5 ml-1">
                    Showing 
                    <span class="text-gray-900">{{ $paginator->firstItem() ?? 0 }}</span>
                    to 
                    <span class="text-gray-900">{{ $paginator->lastItem() ?? 0 }}</span>
                    of 
                    <span class="text-gray-900">{{ $paginator->total() }}</span>
                    results
                </p>
            </div>

            <div>
                <span class="relative z-0 inline-flex gap-1.5 p-1 rounded-2xl">
                    {{-- Previous Page Link --}}
                    @if ($paginator->onFirstPage())
                        <span aria-disabled="true" aria-label="{{ __('pagination.previous') }}">
                            <span class="relative inline-flex items-center justify-center w-10 h-10 text-gray-300 bg-white/50 cursor-default rounded-xl transition duration-150 ease-in-out" aria-hidden="true">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"></path>
                                </svg>
                            </span>
                        </span>
                    @else
                        <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="relative inline-flex items-center justify-center w-10 h-10 text-gray-500 bg-white rounded-xl shadow-sm hover:text-indigo-600 hover:scale-105 transition-all duration-200 active:scale-95" aria-label="{{ __('pagination.previous') }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"></path>
                            </svg>
                        </a>
                    @endif

                    {{-- Pagination Elements --}}
                    @foreach ($elements as $element)
                        {{-- "Three Dots" Separator --}}
                        @if (is_string($element))
                            <span aria-disabled="true">
                                <span class="relative inline-flex items-center justify-center w-10 h-10 text-sm font-bold text-gray-400 cursor-default">{{ $element }}</span>
                            </span>
                        @endif

                        {{-- Array Of Links --}}
                        @if (is_array($element))
                            @foreach ($element as $page => $url)
                                @if ($page == $paginator->currentPage())
                                    <span aria-current="page">
                                        <span class="relative inline-flex items-center justify-center w-10 h-10 text-sm font-black text-white bg-indigo-600 rounded-xl shadow-lg shadow-indigo-600/30 cursor-default">{{ $page }}</span>
                                    </span>
                                @else
                                    <a href="{{ $url }}" class="relative inline-flex items-center justify-center w-10 h-10 text-sm font-bold text-gray-500 bg-white rounded-xl shadow-sm hover:text-indigo-600 hover:scale-105 transition-all duration-200 active:scale-95" aria-label="{{ __('Go to page :page', ['page' => $page]) }}">
                                        {{ $page }}
                                    </a>
                                @endif
                            @endforeach
                        @endif
                    @endforeach

                    {{-- Next Page Link --}}
                    @if ($paginator->hasMorePages())
                        <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="relative inline-flex items-center justify-center w-10 h-10 text-gray-500 bg-white rounded-xl shadow-sm hover:text-indigo-600 hover:scale-105 transition-all duration-200 active:scale-95" aria-label="{{ __('pagination.next') }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </a>
                    @else
                        <span aria-disabled="true" aria-label="{{ __('pagination.next') }}">
                            <span class="relative inline-flex items-center justify-center w-10 h-10 text-gray-300 bg-white/50 cursor-default rounded-xl transition duration-150 ease-in-out" aria-hidden="true">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </span>
                        </span>
                    @endif
                </span>
            </div>
        </div>
    </nav>
@endif
