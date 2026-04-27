@extends('layouts.institute')
@section('content')
<div class="max-w-[1400px] mx-auto pb-10 px-4 sm:px-6">
    <nav class="flex items-center gap-2 text-[11px] font-bold text-slate-400 uppercase tracking-[0.2em] mb-4">
        <a href="{{ route('institute.batches.index') }}" class="hover:text-blue-600">Batches</a>
        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 5l7 7-7 7" /></svg>
        <a href="{{ route('institute.batches.show', $id) }}" class="text-slate-600">Batch Details</a>
    </nav>
    <div class="bg-white p-12 rounded-2xl border border-slate-100 text-center">
        <h1 class="text-3xl font-bold text-slate-900 mb-4">Attendance History</h1>
        <p class="text-slate-500">This module is currently under development.</p>
    </div>
</div>
@endsection
