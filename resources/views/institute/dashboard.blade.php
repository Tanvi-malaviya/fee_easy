@extends('layouts.institute')

@section('content')
    <div class="space-y-2 max-w-[1600px] mx-auto">
        <!-- Welcome Section -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h2 class="text-2xl mt-2 font-extrabold text-slate-800 tracking-tight">Welcome back,
                    {{ Auth::guard('institute')->user()->name }}.</h2>
                <!-- <p class="text-sm text-slate-400 mt-1">Here's the scholarly pulse of {{ $institute->institute_name }} for today.</p> -->
            </div>

        </div>

        <!-- Stat Cards Grid -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-2">
            <!-- Total Students -->
            <div
                class="group relative overflow-hidden bg-white p-1 rounded-[1rem] transition-all duration-500 hover:shadow-2xl hover:shadow-indigo-500/10 hover:-translate-y-1">
                <div class="relative bg-white border border-slate-100 rounded-[0.9rem] p-5 h-full">
                    <!-- Background Decoration -->
                    <div
                        class="absolute -right-10 -top-10 h-32 w-32 bg-indigo-50 rounded-full blur-3xl opacity-50 group-hover:scale-150 transition-transform duration-700">
                    </div>

                    <div class="relative z-10 flex flex-col h-full justify-between">
                        <div class="flex items-start justify-between">
                            <div
                                class="h-14 w-14 bg-gradient-to-br from-indigo-500 to-indigo-600 rounded-2xl flex items-center justify-center text-white shadow-lg shadow-indigo-200 group-hover:rotate-6 transition-transform">
                                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 14l9-5-9-5-9 5 9 5z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z" />
                                </svg>
                            </div>
                            <span
                                class="text-[10px] font-black text-indigo-500 bg-indigo-50 px-3 py-1.5 rounded-full uppercase tracking-widest">{{ $stats['total_batches'] }}
                                Batches</span>
                        </div>

                        <div class="mt-4">
                            <p class="text-[11px] font-black text-slate-400 uppercase tracking-[0.2em] mb-1">Total Students
                            </p>
                            <h3 class="text-3xl font-black text-slate-800 tracking-tighter">
                                {{ number_format($stats['total_students']) }}</h3>
                            <div class="mt-4 flex items-center gap-2">
                                <span class="flex h-1.5 w-1.5 rounded-full bg-emerald-500"></span>
                                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Growth trending
                                    up</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pending Fees -->
            <div
                class="group relative overflow-hidden bg-white p-1 rounded-[1rem] transition-all duration-500 hover:shadow-2xl hover:shadow-rose-500/10 hover:-translate-y-1">
                <div class="relative bg-white border border-slate-100 rounded-[0.9rem] p-5 h-full">
                    <!-- Background Decoration -->
                    <div
                        class="absolute -right-10 -top-10 h-32 w-32 bg-rose-50 rounded-full blur-3xl opacity-50 group-hover:scale-150 transition-transform duration-700">
                    </div>

                    <div class="relative z-10 flex flex-col h-full justify-between">
                        <div class="flex items-start justify-between">
                            <div
                                class="h-14 w-14 bg-gradient-to-br from-rose-500 to-rose-600 rounded-2xl flex items-center justify-center text-white shadow-lg shadow-rose-200 group-hover:-rotate-6 transition-transform">
                                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 5H7a2 2 0 00-2-2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                                </svg>
                            </div>
                            <span
                                class="text-[10px] font-black text-rose-500 bg-rose-50 px-3 py-1.5 rounded-full uppercase tracking-widest">Urgent
                                Task</span>
                        </div>

                        <div class="mt-4">
                            <p class="text-[11px] font-black text-slate-400 uppercase tracking-[0.2em] mb-1">Pending Fees
                            </p>
                            <h3 class="text-3xl font-black text-rose-600 tracking-tighter">
                                ₹{{ $stats['pending_fees_formatted'] }}</h3>
                            <div class="mt-4 flex items-center gap-2">
                                <span class="flex h-1.5 w-1.5 rounded-full bg-rose-500 animate-pulse"></span>
                                <span class="text-[10px] font-bold text-rose-400 uppercase tracking-widest">Action
                                    required</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Attendance -->
            <div
                class="group relative overflow-hidden bg-white p-1 rounded-[1rem] transition-all duration-500 hover:shadow-2xl hover:shadow-amber-500/10 hover:-translate-y-1">
                <div class="relative bg-white border border-slate-100 rounded-[0.9rem] p-5 h-full">
                    <!-- Background Decoration -->
                    <div
                        class="absolute -right-10 -top-10 h-32 w-32 bg-amber-50 rounded-full blur-3xl opacity-50 group-hover:scale-150 transition-transform duration-700">
                    </div>

                    <div class="relative z-10 flex flex-col h-full justify-between">
                        <div class="flex items-start justify-between">
                            <div
                                class="h-14 w-14 bg-gradient-to-br from-amber-500 to-amber-600 rounded-2xl flex items-center justify-center text-white shadow-lg shadow-amber-200 group-hover:rotate-12 transition-transform">
                                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" />
                                </svg>
                            </div>
                            <span
                                class="text-[10px] font-black text-amber-600 bg-amber-50 px-3 py-1.5 rounded-full uppercase tracking-widest">System
                                Health</span>
                        </div>

                        <div class="mt-4">
                            <p class="text-[11px] font-black text-slate-400 uppercase tracking-[0.2em] mb-1">Today's
                                Presence</p>
                            <h3 class="text-3xl font-black text-slate-800 tracking-tighter">{{ $stats['today_attendance'] }}
                            </h3>
                            <div class="mt-4 flex items-center gap-2">
                                <span class="flex h-1.5 w-1.5 rounded-full bg-amber-500"></span>
                                <span
                                    class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">{{ $stats['active_subscriptions'] }}
                                    Active Subs</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 items-start">
            <!-- Recent Batches -->
            <div class="bg-white rounded-[1rem] p-5 shadow-sm border border-slate-100">
                <div class="flex items-center justify-between mb-4 px-2">
                    <h3 class="text-lg font-extrabold text-slate-800 tracking-tight">Recent Batches</h3>
                    <a href="{{ route('institute.batches.index') }}"
                        class="text-[10px] font-extrabold text-blue-600 hover:underline uppercase tracking-widest">View
                        All</a>
                </div>
                <div class="space-y-3">
                    @forelse($recent_batches as $batch)
                        <div
                            class="flex items-center justify-between p-3 bg-slate-50 rounded-xl border border-slate-100/50 hover:border-blue-600/20 hover:bg-white hover:shadow-xl hover:shadow-blue-900/5 transition-all group">
                            <div class="flex items-center gap-4">
                                <div
                                    class="h-10 w-10 bg-white rounded-xl flex items-center justify-center text-blue-600 border border-slate-100 shadow-sm group-hover:scale-110 transition-transform">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                                    </svg>
                                </div>
                                <div>
                                    <h4 class="text-sm font-black text-slate-800 tracking-tight">{{ $batch->name }}</h4>
                                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-0.5">
                                        {{ $batch->time }} • {{ $batch->students_count ?? $batch->students()->count() }}
                                        Students</p>
                                </div>
                            </div>
                            <a href="{{ route('institute.batches.show', $batch->id) }}"
                                class="h-8 w-8 rounded-lg bg-white border border-slate-100 flex items-center justify-center text-slate-400 hover:text-blue-600 hover:border-blue-600/30 transition-all">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7" />
                                </svg>
                            </a>
                        </div>
                    @empty
                        <div class="py-10 text-center">
                            <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">No batches found</p>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Recent Students -->
            <div class="bg-white rounded-[1rem] p-5 shadow-sm border border-slate-100">
                <div class="flex items-center justify-between mb-4 px-2">
                    <h3 class="text-lg font-extrabold text-slate-800 tracking-tight">Recent Students</h3>
                    <a href="{{ route('institute.students.index') }}"
                        class="text-[10px] font-extrabold text-blue-600 hover:underline uppercase tracking-widest">View
                        All</a>
                </div>
                <div class="space-y-3">
                    @forelse($recent_students as $student)
                        <div
                            class="flex items-center justify-between p-3 bg-slate-50 rounded-xl border border-slate-100/50 hover:border-emerald-600/20 hover:bg-white hover:shadow-xl hover:shadow-emerald-900/5 transition-all group">
                            <div class="flex items-center gap-4">
                                <div
                                    class="h-10 w-10 bg-white rounded-xl flex items-center justify-center text-emerald-600 border border-slate-100 shadow-sm group-hover:scale-110 transition-transform">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                </div>
                                <div>
                                    <h4 class="text-sm font-black text-slate-800 tracking-tight">{{ $student->name }}</h4>
                                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-0.5">
                                        {{ $student->email }} • Joined {{ $student->created_at->format('d M') }}</p>
                                </div>
                            </div>
                            <a href="{{ route('institute.students.show', $student->id) }}"
                                class="h-8 w-8 rounded-lg bg-white border border-slate-100 flex items-center justify-center text-slate-400 hover:text-emerald-600 hover:border-emerald-600/30 transition-all">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7" />
                                </svg>
                            </a>
                        </div>
                    @empty
                        <div class="py-10 text-center">
                            <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">No students found</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
@endsection