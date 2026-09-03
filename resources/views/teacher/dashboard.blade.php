@extends('layouts.teacher')

@section('title', 'My Batches')

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-black text-slate-900 tracking-tight">Welcome, {{ $teacher->full_name }}</h1>
    <p class="text-sm text-slate-500 mt-1">Batches assigned to you by your institute.</p>
</div>

@if($batches->isEmpty())
    <div class="bg-white rounded-2xl border border-slate-100 p-12 text-center">
        <p class="text-sm font-semibold text-slate-500">No batches have been assigned to you yet. Contact your institute admin.</p>
    </div>
@else
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
        @foreach($batches as $batch)
            <a href="{{ route('teacher.batches.show', $batch->id) }}" class="bg-white rounded-2xl border border-slate-100 p-5 hover:shadow-lg hover:-translate-y-0.5 transition-all block">
                <div class="flex items-start justify-between mb-4">
                    <div class="h-10 w-10 rounded-xl bg-orange-50 flex items-center justify-center text-primary">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                    </div>
                    <span class="text-[10px] font-black uppercase tracking-wide px-2 py-1 rounded-full {{ $batch->status === 'closed' ? 'bg-slate-100 text-slate-500' : 'bg-emerald-50 text-emerald-600' }}">{{ $batch->status ?? 'active' }}</span>
                </div>
                <h3 class="font-black text-slate-900 text-base mb-1">{{ $batch->name }}</h3>
                <p class="text-xs text-slate-500 font-medium mb-4">{{ $batch->subject ?: 'General' }}</p>
                <div class="flex items-center justify-between text-xs font-bold text-slate-500 pt-3 border-t border-slate-50">
                    <span>{{ $batch->students_count }} Students</span>
                    <span class="text-primary">View Batch &rarr;</span>
                </div>
            </a>
        @endforeach
    </div>
@endif
@endsection
