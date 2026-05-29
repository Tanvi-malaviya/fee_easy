@props([
    'id' => null,
    'title' => 'No items found',
    'subtitle' => 'Try adjusting your filters or add a new item.',
    'icon' => 'default',
    'plain' => false,
    'class' => '',
])

<div @if($id) id="{{ $id }}" @endif class="col-span-full py-16 px-4 flex flex-col items-center justify-center text-center w-full transition-all duration-300 {{ $plain ? '' : 'bg-white rounded-3xl border border-slate-200/50 shadow-sm shadow-slate-100' }} {{ $class }}">
    <!-- Modern Ambient Gradient Icon Container -->
    <div class="h-16 w-16 bg-gradient-to-br from-slate-50 to-slate-100 border border-slate-200/50 rounded-2xl flex items-center justify-center text-slate-400 mb-4 shadow-[inset_0_2px_4px_rgba(0,0,0,0.02),0_4px_12px_rgba(0,0,0,0.01)] transition-transform hover:scale-105 duration-300 relative group">
        <!-- Accent Glow Dot -->
        <div class="absolute -top-1 -right-1 h-3 w-3 bg-[#FF6B00] rounded-full border-2 border-white shadow-sm animate-pulse"></div>
        
        @switch($icon)
            @case('students')
            @case('student')
            @case('users')
                <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                </svg>
                @break

            @case('batches')
            @case('batch')
            @case('calendar')
                <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                @break

            @case('teachers')
            @case('teacher')
                <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14zm-4 6v-7.5l4-2.222" />
                </svg>
                @break

            @case('updates')
            @case('announcements')
                <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z" />
                </svg>
                @break

            @case('expenses')
            @case('wallet')
            @case('salary')
            @case('fees')
                <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                </svg>
                @break

            @case('notes')
            @case('documents')
                <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                @break

            @case('homework')
            @case('book')
            @case('assignments')
                <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                </svg>
                @break

            @default
                <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
        @endswitch
    </div>

    <!-- Title & Subtitle -->
    <h3 class="text-base font-extrabold text-slate-800 tracking-tight mb-1 uppercase tracking-wider">{{ $title }}</h3>
    <p class="text-xs text-slate-400 font-semibold max-w-sm px-4 leading-relaxed">{{ $subtitle }}</p>

    {{ $slot }}
</div>
