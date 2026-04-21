@extends('layouts.institute')

@section('content')
<div class="space-y-10 max-w-[1600px] mx-auto">
    <!-- Welcome Section -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h2 class="text-2xl font-extrabold text-slate-800 tracking-tight">Welcome back, {{ Auth::guard('institute')->user()->name }}.</h2>
            <p class="text-sm text-slate-400 mt-1">Here's the scholarly pulse of {{ $institute->institute_name }} for today.</p>
        </div>
        <div class="flex items-center space-x-3">
            <button class="px-5 py-2.5 bg-white border border-slate-200 text-slate-600 rounded-xl text-xs font-bold shadow-sm hover:bg-slate-50 transition-colors">Generate Report</button>
            <button class="px-5 py-2.5 bg-[#1e3a8a] text-white rounded-xl text-xs font-bold shadow-lg shadow-blue-900/10 hover:scale-[1.02] transition-transform">+ Add Entry</button>
        </div>
    </div>

    <!-- Stat Cards Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Total Students -->
        <div class="bg-white p-7 rounded-[2rem] shadow-sm border border-slate-100 flex flex-col justify-between h-44 relative group hover:shadow-xl hover:shadow-indigo-50/50 transition-all duration-300">
            <div class="absolute top-0 left-0 bottom-0 w-1.5 bg-indigo-600 rounded-l-full"></div>
            <div class="flex justify-between items-start">
                <div class="h-10 w-10 bg-indigo-50 rounded-xl flex items-center justify-center text-indigo-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"/></svg>
                </div>
                <span class="text-[10px] font-bold text-slate-400 bg-slate-50 px-2.5 py-1 rounded-lg">{{ $stats['total_batches'] }} Batches</span>
            </div>
            <div>
                <div class="text-[10px] uppercase font-bold text-slate-400 tracking-widest mb-1.5">Total Students</div>
                <div class="text-3xl font-extrabold text-slate-800 tracking-tight">{{ number_format($stats['total_students']) }}</div>
            </div>
        </div>

        <!-- Total Revenue -->
        <div class="bg-white p-7 rounded-[2rem] shadow-sm border border-slate-100 flex flex-col justify-between h-44 relative group hover:shadow-xl hover:shadow-emerald-50/50 transition-all duration-300">
            <div class="absolute top-0 left-0 bottom-0 w-1.5 bg-emerald-500 rounded-l-full"></div>
            <div class="flex justify-between items-start">
                <div class="h-10 w-10 bg-emerald-50 rounded-xl flex items-center justify-center text-emerald-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                </div>
                <span class="text-[10px] font-bold text-emerald-500 bg-emerald-50 px-2.5 py-1 rounded-lg">API Verified</span>
            </div>
            <div>
                <div class="text-[10px] uppercase font-bold text-slate-400 tracking-widest mb-1.5">Total Revenue</div>
                <div class="text-3xl font-extrabold text-slate-800 tracking-tight">${{ $stats['monthly_revenue_formatted'] }}</div>
            </div>
        </div>

        <!-- Due Fees -->
        <div class="bg-white p-7 rounded-[2rem] shadow-sm border border-slate-100 flex flex-col justify-between h-44 relative group hover:shadow-xl hover:shadow-rose-50/50 transition-all duration-300">
            <div class="absolute top-0 left-0 bottom-0 w-1.5 bg-rose-500 rounded-l-full"></div>
            <div class="flex justify-between items-start">
                <div class="h-10 w-10 bg-rose-50 rounded-xl flex items-center justify-center text-rose-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2-2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/></svg>
                </div>
                <span class="text-[10px] font-bold text-rose-500 bg-rose-50 px-2.5 py-1 rounded-lg">Target: ${{ $stats['total_fees_formatted'] }}</span>
            </div>
            <div>
                <div class="text-[10px] uppercase font-bold text-slate-400 tracking-widest mb-1.5">Total Due Fees</div>
                <div class="text-3xl font-extrabold text-slate-800 tracking-tight">${{ $stats['pending_fees_formatted'] }}</div>
            </div>
        </div>

        <!-- Subscription Status -->
        <div class="bg-white p-7 rounded-[2rem] shadow-sm border border-slate-100 flex flex-col justify-between h-44 relative group hover:shadow-xl hover:shadow-amber-50/50 transition-all duration-300">
            <div class="absolute top-0 left-0 bottom-0 w-1.5 bg-amber-500 rounded-l-full"></div>
            <div class="flex justify-between items-start">
                <div class="h-10 w-10 bg-amber-50 rounded-xl flex items-center justify-center text-amber-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/></svg>
                </div>
                <span class="text-[10px] font-bold text-amber-600 bg-amber-50 px-2.5 py-1 rounded-lg">{{ $stats['active_subscriptions'] }} Active</span>
            </div>
            <div>
                <div class="text-[10px] uppercase font-bold text-slate-400 tracking-widest mb-1.5">Today's Attendance</div>
                <div class="text-3xl font-extrabold text-slate-800 tracking-tight">{{ $stats['today_attendance'] }}</div>
            </div>
        </div>
    </div>

    <!-- Main Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Revenue Chart -->
        <div class="lg:col-span-2 bg-white rounded-[2.5rem] p-8 shadow-sm border border-slate-100">
            <div class="flex items-center justify-between mb-8 px-2">
                <div>
                    <h3 class="text-lg font-extrabold text-slate-800 tracking-tight">Revenue flow</h3>
                    <p class="text-xs text-slate-400 mt-0.5 font-medium uppercase tracking-wider">Weekly trend analysis</p>
                </div>
                <div class="flex space-x-1 bg-slate-50 p-1 rounded-xl">
                    <button class="px-4 py-2 text-[10px] font-extrabold bg-[#1e3a8a] text-white rounded-lg shadow-md">Collections</button>
                    <button class="px-4 py-2 text-[10px] font-extrabold text-slate-400 hover:text-slate-700 transition-colors">Forecast</button>
                </div>
            </div>

            <!-- Chart Simulation -->
            <div class="flex items-end justify-between h-56 px-6 mb-4">
                @php $days = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun']; @endphp
                @php $heights = [45, 75, 60, 95, 55, 30, 20]; @endphp
                @foreach($days as $index => $day)
                <div class="flex flex-col items-center flex-1 group">
                    <div class="w-12 bg-slate-50 rounded-2xl relative overflow-hidden h-44 flex items-end mb-4">
                        <div class="w-full bg-[#1e3a8a] rounded-t-xl transition-all duration-1000 delay-{{ $index * 100 }} group-hover:opacity-80" style="height: {{ $heights[$index] }}%"></div>
                    </div>
                    <span class="text-[10px] font-extrabold text-slate-400 uppercase tracking-widest">{{ $day }}</span>
                </div>
                @endforeach
            </div>
        </div>

        <!-- Activity Timeline -->
        <div class="bg-white rounded-[2.5rem] p-8 shadow-sm border border-slate-100 flex flex-col">
            <div class="flex items-center justify-between mb-8 px-2">
                <h3 class="text-lg font-extrabold text-slate-800 tracking-tight">Recent Activity</h3>
                <a href="#" class="text-[10px] font-extrabold text-[#1e3a8a] hover:underline uppercase tracking-widest">See all</a>
            </div>

            <div class="space-y-8 flex-1">
                @php
                    $activities = [
                        ['type' => 'payment', 'title' => 'Fee Payment Received', 'desc' => 'James Anderson paid $450.00', 'time' => '2 min ago', 'color' => 'indigo'],
                        ['type' => 'enroll', 'title' => 'New Student enrolled', 'desc' => 'Sarah Jenkins joined Batch \'Elite-2024\'', 'time' => '45 min ago', 'color' => 'violet'],
                        ['type' => 'alert', 'title' => 'Attendance Alert', 'desc' => 'Batch 09-A reports low attendance', 'time' => '2 hour ago', 'color' => 'rose'],
                    ];
                @endphp

                @foreach($activities as $act)
                <div class="flex items-start">
                    <div class="h-10 w-10 bg-slate-50 rounded-xl flex items-center justify-center mr-4 border border-slate-100 shrink-0">
                        @if($act['type'] == 'payment') <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg> @endif
                        @if($act['type'] == 'enroll') <svg class="w-5 h-5 text-violet-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/></svg> @endif
                        @if($act['type'] == 'alert') <svg class="w-5 h-5 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg> @endif
                    </div>
                    <div>
                        <h4 class="text-[13px] font-extrabold text-slate-800 tracking-tight leading-tight">{{ $act['title'] }}</h4>
                        <p class="text-[11px] text-slate-400 mt-1 font-medium leading-relaxed">{{ $act['desc'] }}</p>
                        <span class="text-[9px] font-extrabold text-slate-300 mt-2 block tracking-widest uppercase">{{ $act['time'] }}</span>
                    </div>
                </div>
                @endforeach
            </div>

            <button class="mt-8 py-3 bg-[#1e3a8a] text-white rounded-2xl text-xs font-extrabold shadow-xl shadow-blue-900/10 hover:scale-[1.02] transition-transform">View All Notifications</button>
        </div>
    </div>
</div>
@endsection
