@extends('layouts.institute')

@section('content')
    <div id="toast-container" class="fixed top-24 right-8 z-[1000] space-y-4"></div>

    <div class="max-w-[1400px] mx-auto pb-6 px-4 sm:px-6">
        <!-- Breadcrumb & Top Header Actions -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-3 mb-4 mt-2">
            <div>
                <!-- Breadcrumbs -->
                <nav class="flex flex-wrap items-center pt-1 gap-y-1 gap-x-2 text-[10px] sm:text-[11px] font-bold text-slate-400 uppercase tracking-[0.1em] sm:tracking-[0.2em] mb-1.5">
                    <a href="{{ route('institute.batches.index') }}" class="hover:text-primary transition-colors">Batches</a>
                    <svg class="w-3 h-3 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
                    <span class="text-slate-600">{{ $batch->name }}</span>
                </nav>

                <div class="flex items-center gap-2.5">
                    <h1 class="text-xl sm:text-2xl font-bold text-slate-900 tracking-tight leading-none">
                        {{ $batch->name }}
                    </h1>
                    @if($batch->status === 'closed')
                        <span class="px-2.5 py-0.5 rounded-lg text-[9px] font-bold uppercase tracking-widest bg-slate-100 text-slate-500 border border-slate-200">
                            Closed
                        </span>
                    @else
                        <span class="px-2.5 py-0.5 rounded-lg text-[9px] font-bold uppercase tracking-widest bg-emerald-50 text-emerald-600 border border-emerald-200/60">
                            Active
                        </span>
                    @endif
                </div>
            </div>

            <div class="flex items-center gap-2 shrink-0">
                @if($batch->status !== 'closed')
                    <button onclick="openCloseBatchModal()"
                        class="flex items-center gap-1.5 px-3.5 py-2 bg-white border border-rose-200 hover:bg-rose-50 hover:border-rose-300 rounded-xl text-xs font-bold text-rose-500 transition-all shadow-sm">
                        <svg class="w-3.5 h-3.5 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Close Batch
                    </button>
                @endif
            </div>
        </div>

        <!-- Top 3 Info Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-3 mb-4">
            <!-- Card 1: Batch Running Days & Details -->
            <div class="bg-white p-4 rounded-2xl border border-slate-100/90 shadow-sm relative overflow-hidden flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between mb-2">
                        <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest">Batch Running Days</p>
                    </div>

                    <div class="flex flex-wrap gap-1.5 my-2">
                        @php
                            $activeDays = is_array($batch->days) ? $batch->days : [];
                        @endphp
                        @foreach(['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'] as $d)
                            @php $isActive = in_array($d, $activeDays); @endphp
                            <span class="px-2.5 py-1 rounded-lg text-[10px] font-bold transition-all {{ $isActive ? 'bg-primary text-white shadow-sm shadow-orange-500/20' : 'bg-slate-50 text-slate-300 border border-slate-100' }}">
                                {{ $d }}
                            </span>
                        @endforeach
                    </div>

                    @if($batch->description)
                        <div class="mt-2.5 pt-2 border-t border-slate-100/70">
                            <p class="text-[11px] text-slate-600 font-medium line-clamp-2" title="{{ $batch->description }}">
                                <span class="text-slate-400 font-bold text-[9px] uppercase tracking-wider block mb-0.5">Description</span>
                                {{ $batch->description }}
                            </p>
                        </div>
                    @endif
                </div>

                <!-- <div class="mt-3 pt-2.5 border-t border-slate-100/70 flex items-center justify-between text-[11px]">
                    <span class="text-slate-400 font-medium">Lecture Schedule:</span>
                    <a href="{{ route('institute.batches.timetable', $batch->id) }}" class="font-bold text-primary hover:underline flex items-center gap-1">
                        <span>TimeTable Driven</span>
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </a>
                </div> -->
            </div>

            <!-- Card 2: Enrollment & Strength -->
            <div class="bg-white p-4 rounded-2xl border border-slate-100/90 shadow-sm relative overflow-hidden flex flex-col justify-between">
                <div>
                    <!-- <div class="flex items-center justify-between mb-1.5">
                        <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest">Enrolled Scholars</p>
                        <a href="{{ route('institute.batches.students', $batch->id) }}" class="text-[10px] font-bold text-blue-600 hover:underline">
                            View All &rarr;
                        </a>
                    </div> -->
                    
                    @php
                        $studentCount = $batch->students_count ?? 0;
                        $cap = $batch->max_capacity ?: 30;
                        $pct = min(100, round(($studentCount / $cap) * 100));
                    @endphp
                    <div class="flex items-baseline gap-1.5 mb-1.5">
                        <span class="text-3xl font-extrabold text-slate-900 leading-none">{{ $studentCount }}</span>
                        <span class="text-base font-bold text-slate-300 leading-none">/ {{ $cap }}</span>
                    </div>

                    <div class="w-full h-1.5 bg-slate-100 rounded-full overflow-hidden mb-2">
                        <div class="h-full bg-gradient-to-r from-orange-400 to-rose-400 rounded-full transition-all duration-700" style="width: {{ $pct }}%"></div>
                    </div>
                </div>

                <div class="mt-2 pt-2.5 border-t border-slate-100/70 flex items-center justify-between text-[11px] text-slate-500">
                    <span>Active Strength</span>
                    <span class="font-bold text-slate-700">{{ $studentCount }} Active</span>
                </div>
            </div>

            <!-- Card 3: Fee Collection Summary (Only TOTAL FEE & COLLECTION) -->
            <div class="bg-white p-4 rounded-2xl border border-slate-100/90 shadow-sm relative overflow-hidden flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between mb-2">
                        <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest">Fee Collection Summary</p>
                        <span class="text-[10px] font-bold text-emerald-600">Current Cycle</span>
                    </div>

                    <!-- Only TOTAL FEE and COLLECTION -->
                    <div class="grid grid-cols-2 gap-2 my-1">
                        <div class="bg-slate-50/80 p-2.5 rounded-xl border border-slate-100/80">
                            <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mb-0.5">Total Fee</p>
                            <h4 class="text-base font-bold text-slate-800">₹{{ number_format($batch->total_expected ?? 0, 0) }}</h4>
                        </div>
                        <div class="bg-slate-50/80 p-2.5 rounded-xl border border-slate-100/80">
                            <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mb-0.5">Collection</p>
                            <h4 class="text-base font-bold text-slate-800">₹{{ number_format($batch->total_paid ?? 0, 0) }}</h4>
                        </div>
                    </div>
                </div>

                <div class="mt-2.5 pt-2.5 border-t border-slate-100/70 flex items-center justify-between gap-2">
                    <div class="text-[11px] truncate">
                        <span class="text-slate-400 font-medium">Plan:</span>
                        <span class="font-bold text-blue-600">₹{{ number_format($batch->fees ?? 0, 0) }}</span>
                    </div>

                    <button type="button" onclick="sendBatchFeeReminders({{ $batch->id }})" id="btn-batch-fee-reminder"
                        class="px-2.5 py-1 bg-amber-500 hover:bg-amber-600 text-white rounded-lg text-[10px] font-bold shadow-xs hover:scale-105 active:scale-95 transition flex items-center gap-1 cursor-pointer"
                        title="Send reminder to all students with pending fees">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                        <span>Send Fee Reminder</span>
                    </button>
                </div>
            </div>
        </div>

        <!-- 6 Feature Navigation Cards (Styled with Top Border Accents) -->
        <div class="mb-4">
            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-3">
                <!-- 1. Students -->
                <a href="{{ route('institute.batches.students', $id) }}"
                    class="group bg-white p-4 rounded-2xl border-t-4 border-t-orange-500 border-x border-b border-slate-100/90 shadow-sm hover:shadow-md transition-all hover:-translate-y-0.5 relative overflow-hidden">
                    <div class="flex items-center justify-between mb-3">
                        <div class="h-10 w-10 rounded-xl bg-orange-50 flex items-center justify-center text-orange-500 group-hover:scale-105 transition-transform">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                        </div>
                        <svg class="w-5 h-5 text-slate-200 group-hover:text-orange-400 group-hover:translate-x-1 transition-all" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7" />
                        </svg>
                    </div>
                    <h3 class="text-base font-bold text-slate-900 leading-tight">Students</h3>
                    <p class="text-[9px] font-bold text-slate-400 mt-1 uppercase tracking-wider">Manage enrollments</p>
                </a>

                <!-- 2. TimeTable -->
                <a href="{{ route('institute.batches.timetable', $id) }}"
                    class="group bg-white p-4 rounded-2xl border-t-4 border-t-blue-500 border-x border-b border-slate-100/90 shadow-sm hover:shadow-md transition-all hover:-translate-y-0.5 relative overflow-hidden">
                    <div class="flex items-center justify-between mb-3">
                        <div class="h-10 w-10 rounded-xl bg-blue-50 flex items-center justify-center text-blue-600 group-hover:scale-105 transition-transform">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <svg class="w-5 h-5 text-slate-200 group-hover:text-blue-400 group-hover:translate-x-1 transition-all" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7" />
                        </svg>
                    </div>
                    <h3 class="text-base font-bold text-slate-900 leading-tight">TimeTable</h3>
                    <p class="text-[9px] font-bold text-slate-400 mt-1 uppercase tracking-wider">Schedule & lectures</p>
                </a>

                <!-- 3. Exams -->
                <a href="{{ route('institute.batches.exams', $id) }}"
                    class="group bg-white p-4 rounded-2xl border-t-4 border-t-indigo-600 border-x border-b border-slate-100/90 shadow-sm hover:shadow-md transition-all hover:-translate-y-0.5 relative overflow-hidden">
                    <div class="flex items-center justify-between mb-3">
                        <div class="h-10 w-10 rounded-xl bg-indigo-50 flex items-center justify-center text-indigo-600 group-hover:scale-105 transition-transform">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                            </svg>
                        </div>
                        <svg class="w-5 h-5 text-slate-200 group-hover:text-indigo-400 group-hover:translate-x-1 transition-all" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7" />
                        </svg>
                    </div>
                    <h3 class="text-base font-bold text-slate-900 leading-tight">Exams</h3>
                    <p class="text-[9px] font-bold text-slate-400 mt-1 uppercase tracking-wider">Tests & marks entry</p>
                </a>

                <!-- 4. Homework -->
                <a href="{{ route('institute.batches.homework', $id) }}"
                    class="group bg-white p-4 rounded-2xl border-t-4 border-t-teal-400 border-x border-b border-slate-100/90 shadow-sm hover:shadow-md transition-all hover:-translate-y-0.5 relative overflow-hidden">
                    <div class="flex items-center justify-between mb-3">
                        <div class="h-10 w-10 rounded-xl bg-teal-50 flex items-center justify-center text-teal-500 group-hover:scale-105 transition-transform">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                            </svg>
                        </div>
                        <svg class="w-5 h-5 text-slate-200 group-hover:text-teal-400 group-hover:translate-x-1 transition-all" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7" />
                        </svg>
                    </div>
                    <h3 class="text-base font-bold text-slate-900 leading-tight">Homework</h3>
                    <p class="text-[9px] font-bold text-slate-400 mt-1 uppercase tracking-wider">Manage assignments</p>
                </a>

                <!-- 5. Attendance -->
                <a href="{{ route('institute.batches.attendance', $id) }}"
                    class="group bg-white p-4 rounded-2xl border-t-4 border-t-amber-700 border-x border-b border-slate-100/90 shadow-sm hover:shadow-md transition-all hover:-translate-y-0.5 relative overflow-hidden">
                    <div class="flex items-center justify-between mb-3">
                        <div class="h-10 w-10 rounded-xl bg-amber-50 flex items-center justify-center text-amber-700 group-hover:scale-105 transition-transform">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <svg class="w-5 h-5 text-slate-200 group-hover:text-amber-500 group-hover:translate-x-1 transition-all" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7" />
                        </svg>
                    </div>
                    <h3 class="text-base font-bold text-slate-900 leading-tight">Attendance</h3>
                    <p class="text-[9px] font-bold text-slate-400 mt-1 uppercase tracking-wider">Track student presence</p>
                </a>

                <!-- 6. Resources -->
                <a href="{{ route('institute.batches.resources', $id) }}"
                    class="group bg-white p-4 rounded-2xl border-t-4 border-t-emerald-500 border-x border-b border-slate-100/90 shadow-sm hover:shadow-md transition-all hover:-translate-y-0.5 relative overflow-hidden">
                    <div class="flex items-center justify-between mb-3">
                        <div class="h-10 w-10 rounded-xl bg-emerald-50 flex items-center justify-center text-emerald-500 group-hover:scale-105 transition-transform">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z" />
                            </svg>
                        </div>
                        <svg class="w-5 h-5 text-slate-200 group-hover:text-emerald-400 group-hover:translate-x-1 transition-all" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7" />
                        </svg>
                    </div>
                    <h3 class="text-base font-bold text-slate-900 leading-tight">Resources</h3>
                    <p class="text-[9px] font-bold text-slate-400 mt-1 uppercase tracking-wider">Materials and documents</p>
                </a>
            </div>
        </div>

        <!-- Weekly Lecture Schedule (TimeTable Routine for this Batch) -->
        <!-- <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-4">
            <div class="flex items-center justify-between mb-3">
                <div class="flex items-center gap-2">
                    <div class="h-7 w-7 rounded-lg bg-orange-50 text-primary flex items-center justify-center">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-sm font-bold text-slate-800 leading-tight">Weekly Lecture Schedule</h3>
                        <p class="text-[10px] text-slate-400 font-medium">Configured daily routine & teacher assignments</p>
                    </div>
                </div>

                <a href="{{ route('institute.timetable.index', ['batch_id' => $batch->id]) }}"
                    class="inline-flex items-center gap-1 text-[11px] font-bold text-primary hover:underline">
                    <span>+ Add / Modify Lecture</span>
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </a>
            </div>

            @if(isset($timetables) && $timetables->count() > 0)
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-2.5">
                    @foreach($timetables as $slot)
                        <div class="bg-slate-50/70 border border-slate-100 rounded-xl p-2.5 flex flex-col justify-between hover:bg-white hover:shadow-sm transition-all">
                            <div>
                                <div class="flex items-center justify-between mb-1.5">
                                    <span class="px-2 py-0.5 rounded-md text-[9px] font-bold uppercase tracking-wider bg-primary/10 text-primary">
                                        {{ ucfirst(substr($slot->day_of_week, 0, 3)) }}
                                    </span>
                                    <span class="text-[10px] font-bold text-slate-500">
                                        {{ date('h:i A', strtotime($slot->start_time)) }} - {{ date('h:i A', strtotime($slot->end_time)) }}
                                    </span>
                                </div>
                                <h4 class="text-xs font-bold text-slate-800 leading-tight">{{ $slot->subject }}</h4>
                            </div>

                            <div class="mt-2 pt-2 border-t border-slate-200/50 flex items-center justify-between text-[10px] text-slate-500">
                                <span class="flex items-center gap-1">
                                    <svg class="w-3 h-3 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                    <strong class="text-slate-700 font-semibold">{{ $slot->staff->full_name ?? 'Faculty Unassigned' }}</strong>
                                </span>
                                @if($slot->room_no)
                                    <span class="px-1.5 py-0.2 bg-white border border-slate-200 rounded text-[9px] font-semibold text-slate-600">
                                        {{ $slot->room_no }}
                                    </span>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="py-8 text-center bg-slate-50/50 rounded-xl border border-dashed border-slate-200">
                    <div class="w-10 h-10 mx-auto rounded-xl bg-orange-50 flex items-center justify-center text-primary mb-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <p class="text-xs font-bold text-slate-700">No lectures scheduled in TimeTable yet</p>
                    <p class="text-[10px] text-slate-400 mt-0.5">Configure subjects, lecture timings, rooms and assign faculty.</p>
                    <a href="{{ route('institute.timetable.index', ['batch_id' => $batch->id]) }}"
                        class="inline-flex items-center gap-1.5 mt-3 px-3 py-1.5 bg-primary text-white rounded-lg text-[11px] font-bold hover:opacity-90 shadow-sm shadow-orange-500/20 transition-all">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                        Set Up TimeTable
                    </a>
                </div>
            @endif
        </div> -->
    </div>

    <script>
        const BATCH_ID = "{{ $id }}";
        const CSRF_TOKEN = "{{ csrf_token() }}";
        const API_BATCH_URL = `/institute/batches/${BATCH_ID}`;

        function showToast(message, type = 'success') {
            const container = document.getElementById('toast-container');
            const toast = document.createElement('div');
            const color = type === 'success' ? 'emerald' : 'rose';
            toast.className = `bg-${color}-50 border border-${color}-200 text-${color}-600 px-5 py-3 rounded-xl shadow-lg flex items-center text-xs font-bold animate-in slide-in-from-right-10 duration-300`;
            toast.innerText = message;
            container.appendChild(toast);
            setTimeout(() => { toast.remove(); }, 3000);
        }

        function openCloseBatchModal() {
            showConfirmModal(
                'Close this Batch?',
                'Are you sure you want to close this batch? This will mark the batch as \'Closed\'.',
                executeCloseBatch,
                'Close Batch',
                'bg-primary hover:opacity-90 shadow-orange-950/15'
            );
        }

        async function executeCloseBatch() {
            toggleLoader(true);
            try {
                const response = await fetch(`${API_BATCH_URL}/close`, {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': CSRF_TOKEN
                    }
                });

                const result = await response.json();
                if (result.status === 'success') {
                    showToast('Batch closed successfully', 'success');
                    setTimeout(() => {
                        window.location.href = "{{ route('institute.batches.index') }}";
                    }, 800);
                } else {
                    showToast(result.message || 'Failed to close batch', 'error');
                }
            } catch (error) {
                showToast('An error occurred while closing the batch', 'error');
            } finally {
                toggleLoader(false);
            }
        }

        async function sendBatchFeeReminders(batchId) {
            const btn = document.getElementById('btn-batch-fee-reminder');
            const originalHTML = btn ? btn.innerHTML : '';
            if (btn) {
                btn.disabled = true;
                btn.classList.add('opacity-70', 'cursor-not-allowed');
                btn.innerHTML = `
                    <svg class="animate-spin h-3 w-3 text-white" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <span>Sending...</span>
                `;
            }

            try {
                const response = await fetch(`/institute/batches/${batchId}/fee-reminders`, {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': CSRF_TOKEN
                    }
                });

                const data = await response.json();
                if (data.status === 'success') {
                    showToast(data.message || 'Fee reminders sent successfully!', 'success');
                } else if (data.status === 'info') {
                    showToast(data.message || 'No students have pending fee dues.', 'success');
                } else {
                    showToast(data.message || 'Failed to send fee reminders.', 'error');
                }
            } catch (err) {
                console.error(err);
                showToast('Something went wrong while sending reminders.', 'error');
            } finally {
                if (btn) {
                    btn.disabled = false;
                    btn.classList.remove('opacity-70', 'cursor-not-allowed');
                    btn.innerHTML = originalHTML;
                }
            }
        }
    </script>
@endsection