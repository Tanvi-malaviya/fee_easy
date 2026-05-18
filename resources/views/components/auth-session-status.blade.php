@props(['status'])

@if ($status)
    <div {{ $attributes->merge(['class' => 'font-bold text-xs text-emerald-600 bg-emerald-50 border border-emerald-100/80 px-4 py-3 rounded-xl flex items-center gap-2.5 shadow-sm']) }}>
        <svg class="w-4 h-4 shrink-0 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        <span>{{ $status }}</span>
    </div>
@endif
