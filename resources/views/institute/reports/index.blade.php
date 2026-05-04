@extends('layouts.institute')

@php
    $batches = \App\Models\Batch::where('institute_id', auth()->id())->get();
@endphp

@section('content')
    <div class="space-y-1 max-w-[1600px] mx-auto pb-5 px-4 animate-in fade-in duration-500">
        <!-- Page Header -->
        <div id="header-container" class="mt-1">
            <div id="breadcrumb" class="hidden items-center gap-2">
                <button onclick="exitDrillDown()"
                    class="text-[10px] font-bold text-slate-400 uppercase tracking-wider hover:text-[#ff6c00] transition-colors flex items-center gap-1">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M15 19l-7-7 7-7" />
                    </svg>
                    Back to Reports
                </button>
            </div>
            <h1 id="page-title" class="text-3xl sm:text-4xl font-[580] text-slate-900 tracking-tight">Reports Hub</h1>
            <p id="page-subtitle" class="text-xs sm:text-sm text-slate-500 font-medium mt-1.5 max-w-2xl leading-relaxed">
                Access real-time academic analytics, financial summaries, and student progress metrics through our
                centralized reporting engine.</p>
        </div>

        <style>
            .batch-card {
                transition: all 0.3s ease;
            }

            .batch-card:hover {
                transform: translateY(-2px);
                border-color: #f97316;
                box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.05);
            }
        </style>

        <!-- Main Container -->
        <div id="reports-container" class="relative">
            <div id="loader"
                class="absolute inset-0 flex items-center justify-center bg-white/60 backdrop-blur-sm z-50 hidden rounded-xl">
                <div class="h-10 w-10 border-4 border-slate-200 border-t-[#f97316] rounded-full animate-spin"></div>
            </div>

            <!-- Section: Hub -->
            <div id="section-hub" class="space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <!-- Attendance Card -->
                    <div
                        class="bg-white p-6 md:p-8 rounded-[24px] border border-slate-100/50 shadow-xl shadow-slate-200/40 flex flex-col justify-between hover:shadow-2xl hover:border-slate-200/80 transition-all duration-300">
                        <div>
                            <div
                                class="h-12 w-12 bg-orange-50 rounded-xl flex items-center justify-center text-[#ff6c00] mb-3 shadow-sm">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <h3 class="text-xl md:text-2xl font-medium text-slate-900 tracking-tight mb-2">Attendance Report
                            </h3>
                            <p class="text-xs md:text-sm text-slate-500 mt-1 font-medium leading-relaxed mb-3">Comprehensive
                                analysis of daily, weekly, and monthly student presence across all active batches.</p>
                        </div>
                        <button onclick="switchTab('attendance')"
                            class="w-full py-3 bg-[#ff6c00] hover:bg-[#e05f00] text-white text-xs md:text-sm font-extrabold rounded-xl shadow-md shadow-orange-500/20 transition-all text-center tracking-wider">
                            View Report
                        </button>
                    </div>

                    <!-- Fees Card -->
                    <div
                        class="bg-white p-6 md:p-8 rounded-[24px] border border-slate-100/50 shadow-xl shadow-slate-200/40 flex flex-col justify-between hover:shadow-2xl hover:border-slate-200/80 transition-all duration-300">
                        <div>
                            <div
                                class="h-12 w-12 bg-teal-50 rounded-xl flex items-center justify-center text-teal-500 mb-3 shadow-sm">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                        d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                                </svg>
                            </div>
                            <h3 class="text-xl md:text-2xl font-medium text-slate-900 tracking-tight mb-2">Fee Collection
                                Report</h3>
                            <p class="text-xs md:text-sm text-slate-500 mt-1 font-medium leading-relaxed mb-3">Track
                                incoming revenue, pending dues, and scholarship distributions with granular filtering
                                options.</p>
                        </div>
                        <button onclick="switchTab('fees')"
                            class="w-full py-3 bg-[#ff6c00] hover:bg-[#e05f00] text-white text-xs md:text-sm font-extrabold rounded-xl shadow-md shadow-orange-500/20 transition-all text-center tracking-wider">
                            View Report
                        </button>
                    </div>

                    <!-- Performance Card -->
                    <div
                        class="bg-white p-6 md:p-8 rounded-[24px] border border-slate-100/50 shadow-xl shadow-slate-200/40 flex flex-col justify-between hover:shadow-2xl hover:border-slate-200/80 transition-all duration-300">
                        <div>
                            <div
                                class="h-12 w-12 bg-emerald-50 rounded-xl flex items-center justify-center text-emerald-500 mb-3 shadow-sm">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                                </svg>
                            </div>
                            <h3 class="text-xl md:text-2xl font-medium text-slate-900 tracking-tight mb-2">Student
                                Performance Report</h3>
                            <p class="text-xs md:text-sm text-slate-500 mt-1 font-medium leading-relaxed mb-3">Deep dive
                                into examination results, assignment completion rates, and individual student growth curves.
                            </p>
                        </div>
                        <button onclick="switchTab('performance')"
                            class="w-full py-3 bg-[#ff6c00] hover:bg-[#e05f00] text-white text-xs md:text-sm font-extrabold rounded-xl shadow-md shadow-orange-500/20 transition-all text-center tracking-wider">
                            View Report
                        </button>
                    </div>
                </div>

                <!-- Recently Generated Reports -->
                <!-- <div class="space-y-2 mt-6">
                        <h4 class="text-xs font-extrabold text-slate-800">Recently Generated Reports</h4>
                        <div class="bg-white rounded-xl border border-slate-100 shadow-sm overflow-hidden">
                            <table class="min-w-full divide-y divide-slate-50 text-left">
                                <thead class="bg-slate-50 text-[9px] font-bold text-slate-400 uppercase tracking-wider">
                                    <tr>
                                        <th class="px-3 py-2">Report Name</th>
                                        <th class="px-3 py-2">Type</th>
                                        <th class="px-3 py-2">Date</th>
                                        <th class="px-3 py-2">Status</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-50 text-[10px] font-bold text-slate-600">
                                    <tr>
                                        <td class="px-3 py-2 flex items-center gap-1.5">
                                            <svg class="w-3.5 h-3.5 text-[#f97316] flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                            <span>Batch-A Monthly Attendance</span>
                                        </td>
                                        <td class="px-3 py-2 text-slate-400">Attendance</td>
                                        <td class="px-3 py-2 text-slate-400">Oct 24, 2024</td>
                                        <td class="px-3 py-2"><span class="px-1.5 py-0.5 bg-emerald-50 text-emerald-600 rounded-md text-[8px] font-bold">Completed</span></td>
                                    </tr>
                                    <tr>
                                        <td class="px-3 py-2 flex items-center gap-1.5">
                                            <svg class="w-3.5 h-3.5 text-[#f97316] flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                            <span>Q3 Fee Collection Summary</span>
                                        </td>
                                        <td class="px-3 py-2 text-slate-400">Finance</td>
                                        <td class="px-3 py-2 text-slate-400">Oct 22, 2024</td>
                                        <td class="px-3 py-2"><span class="px-1.5 py-0.5 bg-emerald-50 text-emerald-600 rounded-md text-[8px] font-bold">Completed</span></td>
                                    </tr>
                                    <tr>
                                        <td class="px-3 py-2 flex items-center gap-1.5">
                                            <svg class="w-3.5 h-3.5 text-[#f97316] flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                            <span>Annual Performance Stats</span>
                                        </td>
                                        <td class="px-3 py-2 text-slate-400">Performance</td>
                                        <td class="px-3 py-2 text-slate-400">Oct 20, 2024</td>
                                        <td class="px-3 py-2"><span class="px-1.5 py-0.5 bg-blue-50 text-blue-600 rounded-md text-[8px] font-bold">Processing</span></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div> -->
            </div>

            <!-- Section: Fees -->
            <div id="section-fees" class="tab-section space-y-4 hidden">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-xl font-[580] text-slate-800 tracking-tight">Fee Collection Report</h2>
                        <p class="text-xs font-medium text-slate-400 mt-0.5">Real-time revenue monitoring and batch-wise
                            financial analysis.</p>
                    </div>
                    <div class="flex items-center gap-2">
                        <button onclick="exportFees()"
                            class="px-3 py-1.5 bg-white border border-slate-200 text-slate-700 rounded-lg font-bold text-xs shadow-sm hover:bg-slate-50 transition-all flex items-center gap-1.5">
                            <svg class="w-3.5 h-3.5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                    d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                            </svg>
                            Export PDF
                        </button>
                    </div>
                </div>

                <!-- Summary Statistic Cards -->
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-3">
                    <!-- Card 1: Total Expected -->
                    <div class="bg-white p-4 rounded-2xl border border-slate-100 shadow-sm flex flex-col justify-between">
                        <div>
                            <div
                                class="h-9 w-9 bg-orange-50 text-orange-500 rounded-xl flex items-center justify-center mb-2 shadow-sm">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Total Expected</p>
                        </div>
                        <div class="mt-2">
                            <h2 id="fee-total-expected" class="text-2xl font-black text-slate-800 leading-none">₹0</h2>
                            <span class="text-[9px] font-bold text-emerald-600 flex items-center gap-0.5 mt-1">
                                Calculated aggregates
                            </span>
                        </div>
                    </div>

                    <!-- Card 2: Total Collected -->
                    <div class="bg-white p-4 rounded-2xl border border-slate-100 shadow-sm flex flex-col justify-between">
                        <div>
                            <div
                                class="h-9 w-9 bg-teal-50 text-teal-500 rounded-xl flex items-center justify-center mb-2 shadow-sm">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Total Collected</p>
                        </div>
                        <div class="mt-2">
                            <h2 id="fee-total-collected-new" class="text-2xl font-black text-emerald-600 leading-none">₹0
                            </h2>
                            <span class="text-[9px] font-bold text-emerald-600 flex items-center gap-0.5 mt-1">
                                On track for targets
                            </span>
                        </div>
                    </div>

                    <!-- Card 3: Total Pending -->
                    <div class="bg-white p-4 rounded-2xl border border-slate-100 shadow-sm flex flex-col justify-between">
                        <div>
                            <div
                                class="h-9 w-9 bg-red-50 text-red-500 rounded-xl flex items-center justify-center mb-2 shadow-sm">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                </svg>
                            </div>
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Total Pending</p>
                        </div>
                        <div class="mt-2">
                            <h2 id="fee-total-pending-new" class="text-2xl font-black text-red-600 leading-none">₹0</h2>
                            <span class="text-[9px] font-bold text-red-400 flex items-center gap-0.5 mt-1">
                                Requires review
                            </span>
                        </div>
                    </div>

                    <!-- Card 4: Collection Rate -->
                    <div class="bg-white p-4 rounded-2xl border border-slate-100 shadow-sm flex flex-col justify-between">
                        <div>
                            <div
                                class="h-9 w-9 bg-blue-50 text-blue-500 rounded-xl flex items-center justify-center mb-2 shadow-sm">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M16 8v8m-4-5v5m-4-2v2m-2 4h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Collection Rate</p>
                        </div>
                        <div class="mt-2">
                            <h2 id="fee-collection-rate" class="text-2xl font-black text-slate-800 leading-none">0.0%</h2>
                            <div
                                class="w-full h-1.5 bg-slate-50 border border-slate-100 rounded-full mt-1.5 overflow-hidden">
                                <div id="fee-rate-bar"
                                    class="h-full bg-orange-500 rounded-full transition-all duration-1000"
                                    style="width: 0%"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                    <!-- Left: Graph / Trends placeholder -->
                    <div class="md:col-span-2 bg-white p-4 rounded-2xl border border-slate-100 shadow-sm">
                        <div class="flex items-center justify-between mb-2">
                            <div>
                                <h4 class="text-xs font-bold text-slate-800 tracking-tight">Collection Trends</h4>
                                <p class="text-[9px] text-slate-400 mt-0.5 font-medium">Revenue performance over time</p>
                            </div>
                            <div class="flex items-center gap-1.5 text-[9px] font-bold">
                                <span class="flex items-center gap-1 text-slate-600"><span
                                        class="w-2 h-2 rounded-full bg-[#ff6c00] block"></span> Collected</span>
                            </div>
                        </div>
                        <div class="h-40 flex items-end justify-between px-2 pt-4 gap-2" id="fee-trends-chart">
                            <!-- Injected dynamically via script -->
                        </div>
                    </div>

                    <!-- Right: By Batch -->
                    <div class="bg-white p-4 rounded-2xl border border-slate-100 shadow-sm flex flex-col">
                        <h4 class="text-xs font-bold text-slate-800 tracking-tight">By Batch</h4>
                        <p class="text-[9px] text-slate-400 mt-0.5 font-medium mb-3">Live collection breakdown</p>
                        <div class="space-y-1.5 max-h-[150px] overflow-y-auto flex-1 flex flex-col pr-1"
                            id="fee-top-batches">
                            <!-- Top batches injected here via script -->
                        </div>
                    </div>
                </div>

                <!-- Lower Layout: Collection Table -->
                <div class="bg-white p-4 rounded-2xl border border-slate-100 shadow-sm">
                    <div class="flex items-center justify-between mb-3">
                        <h4 class="text-xs font-bold text-slate-800 tracking-tight">Detailed Collection Log</h4>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-slate-100 text-left text-xs font-medium">
                            <thead class="bg-slate-50 text-[9px] font-bold text-slate-400 uppercase tracking-wider">
                                <tr>
                                    <th class="px-3 py-2.5">Batch Identity</th>
                                    <th class="px-3 py-2.5">Expected Rev</th>
                                    <th class="px-3 py-2.5">Collected</th>
                                    <th class="px-3 py-2.5">Pending Balance</th>
                                    <th class="px-3 py-2.5">Collection Rate</th>
                                </tr>
                            </thead>
                            <tbody id="fee-roster-rows"
                                class="divide-y divide-slate-50 font-bold text-slate-600 text-[11px]">
                                <!-- Dynamic Batch rows injected here -->
                            </tbody>
                        </table>
                    </div>

                    <!-- Frontend Pagination Controls -->
                    <div class="flex items-center justify-between mt-4 px-1" id="fee-pagination-controls">
                        <span class="text-[10px] font-bold text-slate-400" id="fee-pagination-info">Showing 1-10 of 0
                            batches</span>
                        <div class="flex items-center gap-1">
                            <button onclick="changeFeePage(-1)" id="fee-prev-btn"
                                class="px-2 py-1 bg-slate-50 hover:bg-slate-100 text-slate-500 font-bold text-[10px] rounded-lg border border-slate-100 transition-all disabled:opacity-50 disabled:hover:bg-slate-50 disabled:cursor-not-allowed flex items-center justify-center">Prev</button>
                            <div class="flex items-center gap-1" id="fee-page-numbers">
                                <!-- Page numbers inserted here -->
                            </div>
                            <button onclick="changeFeePage(1)" id="fee-next-btn"
                                class="px-2 py-1 bg-slate-50 hover:bg-slate-100 text-slate-500 font-bold text-[10px] rounded-lg border border-slate-100 transition-all disabled:opacity-50 disabled:hover:bg-slate-50 disabled:cursor-not-allowed flex items-center justify-center">Next</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Section: Attendance -->
            <div id="section-attendance" class="tab-section space-y-4 hidden">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-xl font-[580] text-slate-800 tracking-tight">Attendance Summary Report</h2>
                        <p class="text-xs font-medium text-slate-400 mt-0.5">Real-time engagement analysis for the academic term.</p>
                    </div>
                <div class="flex items-center gap-2">
                    <button onclick="exportAttendance()"
                        class="px-3.5 py-2 bg-white border border-slate-100 text-slate-600 rounded-xl font-bold text-xs shadow-sm hover:bg-slate-50 transition-all flex items-center gap-1.5">
                        <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                        </svg>
                        Export PDF
                    </button>
                </div>
            </div>

            <!-- Summary Statistic Cards -->
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-3">
                <!-- Card 1: Overall Attendance -->
                <div class="bg-white p-4 rounded-2xl border border-slate-100 shadow-sm flex flex-col justify-between">
                    <div>
                        <div
                            class="h-9 w-9 bg-orange-50 text-orange-500 rounded-xl flex items-center justify-center mb-2 shadow-sm">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                            </svg>
                        </div>
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Overall Attendance %</p>
                    </div>
                    <div class="mt-2">
                        <h2 id="att-overall-pct" class="text-2xl font-black text-slate-800 leading-none">0.0%</h2>
                        <span class="text-[9px] font-bold text-emerald-600 flex items-center gap-0.5 mt-1">
                            <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                            </svg>
                            Stable performance
                        </span>
                    </div>
                </div>

                <!-- Card 2: Today's Attendance -->
                <div class="bg-white p-4 rounded-2xl border border-slate-100 shadow-sm flex flex-col justify-between">
                    <div>
                        <div
                            class="h-9 w-9 bg-teal-50 text-teal-500 rounded-xl flex items-center justify-center mb-2 shadow-sm">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                        </div>
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Present Metrics</p>
                    </div>
                    <div class="mt-2">
                        <h2 id="att-present-count" class="text-2xl font-black text-slate-800 leading-none">0</h2>
                        <span id="att-total-out-of" class="text-[9px] font-bold text-slate-400 mt-1 block">Out of 0
                            aggregate logs</span>
                    </div>
                </div>

                <!-- Card 3: Highest Attendance Batch -->
                <div class="bg-white p-4 rounded-2xl border border-slate-100 shadow-sm flex flex-col justify-between">
                    <div>
                        <div
                            class="h-9 w-9 bg-emerald-50 text-emerald-500 rounded-xl flex items-center justify-center mb-2 shadow-sm">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" />
                            </svg>
                        </div>
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Top Performing Batch</p>
                    </div>
                    <div class="mt-2">
                        <h2 id="att-highest-batch" class="text-xs font-bold text-slate-800 leading-tight">N/A</h2>
                        <span id="att-highest-pct" class="text-base font-extrabold text-emerald-600 mt-0.5 block">0%</span>
                    </div>
                </div>

                <!-- Card 4: Low Attendance Alerts -->
                <div class="bg-red-50/40 p-4 rounded-2xl border border-red-100 shadow-sm flex flex-col justify-between">
                    <div>
                        <div
                            class="h-9 w-9 bg-red-50 text-red-500 rounded-xl flex items-center justify-center mb-2 shadow-sm">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                        </div>
                        <p class="text-[10px] font-bold text-red-400 uppercase tracking-wider">Low Attendance Alerts</p>
                    </div>
                    <div class="mt-2">
                        <h2 id="att-alerts-count" class="text-2xl font-black text-red-600 leading-none">0</h2>
                        <span class="text-[9px] font-bold text-red-400 mt-1 block">Critical cases to address</span>
                    </div>
                </div>
            </div>

            <!-- Lower Layout: Pattern Calendar & Filters -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
                <!-- Monthly Attendance Patterns -->
                <div
                    class="lg:col-span-2 bg-white p-4 rounded-2xl border border-slate-100 shadow-sm flex flex-col justify-between">
                    <div class="flex items-center justify-between mb-3">
                        <div>
                            <h4 class="text-xs font-bold text-slate-800 tracking-tight">Monthly Attendance Patterns</h4>
                        </div>
                        <div class="flex items-center gap-2 text-[9px] font-bold text-slate-400">
                            <span class="flex items-center gap-1"><span class="h-2 w-2 rounded-full bg-emerald-500"></span>
                                High</span>
                            <span class="flex items-center gap-1"><span class="h-2 w-2 rounded-full bg-orange-500"></span>
                                Med</span>
                            <span class="flex items-center gap-1"><span class="h-2 w-2 rounded-full bg-orange-200"></span>
                                Low</span>
                        </div>
                    </div>

                    <!-- Calendar Layout -->
                    <div
                        class="grid grid-cols-7 gap-1 text-center font-bold text-slate-400 text-[9px] tracking-wider uppercase mb-2">
                        <div>Mon</div>
                        <div>Tue</div>
                        <div>Wed</div>
                        <div>Thu</div>
                        <div>Fri</div>
                        <div>Sat</div>
                        <div>Sun</div>
                    </div>
                    <div id="calendar-days" class="grid grid-cols-7 gap-1 text-xs font-bold text-center">
                        <div class="bg-slate-50/60 text-slate-300 py-2.5 rounded-xl">28</div>
                        <div class="bg-slate-50/60 text-slate-300 py-2.5 rounded-xl">29</div>
                        <div class="bg-slate-50/60 text-slate-300 py-2.5 rounded-xl">30</div>
                        <div class="bg-emerald-500 text-white py-2.5 rounded-xl shadow-sm">1</div>
                        <div class="bg-emerald-500 text-white py-2.5 rounded-xl shadow-sm">2</div>
                        <div class="bg-orange-200/80 text-orange-700 py-2.5 rounded-xl">3</div>
                        <div class="bg-orange-200/80 text-orange-700 py-2.5 rounded-xl">4</div>

                        <div class="bg-emerald-500 text-white py-2.5 rounded-xl shadow-sm">5</div>
                        <div class="bg-emerald-500 text-white py-2.5 rounded-xl shadow-sm">6</div>
                        <div class="bg-orange-500 text-white py-2.5 rounded-xl shadow-sm">7</div>
                        <div class="bg-emerald-500 text-white py-2.5 rounded-xl shadow-sm">8</div>
                        <div class="bg-emerald-500 text-white py-2.5 rounded-xl shadow-sm">9</div>
                        <div class="bg-orange-200/80 text-orange-700 py-2.5 rounded-xl">10</div>
                        <div class="bg-orange-200/80 text-orange-700 py-2.5 rounded-xl">11</div>

                        <div class="bg-emerald-500 text-white py-2.5 rounded-xl shadow-sm">12</div>
                        <div class="bg-emerald-500 text-white py-2.5 rounded-xl shadow-sm">13</div>
                        <div class="bg-emerald-500 text-white py-2.5 rounded-xl shadow-sm">14</div>
                        <div class="bg-orange-500 text-white py-2.5 rounded-xl shadow-sm">15</div>
                        <div class="bg-emerald-500 text-white py-2.5 rounded-xl shadow-sm">16</div>
                        <div class="bg-orange-200/80 text-orange-700 py-2.5 rounded-xl">17</div>
                        <div class="bg-orange-200/80 text-orange-700 py-2.5 rounded-xl">18</div>

                        <div class="bg-emerald-600 text-white py-2.5 rounded-xl shadow-md ring-2 ring-orange-500/80">19
                        </div>
                        <div class="bg-slate-50/60 text-slate-300 py-2.5 rounded-xl">20</div>
                        <div class="bg-slate-50/60 text-slate-300 py-2.5 rounded-xl">21</div>
                        <div class="bg-slate-50/60 text-slate-300 py-2.5 rounded-xl">22</div>
                        <div class="bg-slate-50/60 text-slate-300 py-2.5 rounded-xl">23</div>
                        <div class="bg-slate-50/60 text-slate-300 py-2.5 rounded-xl">24</div>
                        <div class="bg-slate-50/60 text-slate-300 py-2.5 rounded-xl">25</div>
                    </div>
                </div>

                <!-- Filters Widget -->
                <div class="bg-white p-4 rounded-2xl border border-slate-100 shadow-sm flex flex-col justify-between">
                    <div>
                        <h4 class="text-xs font-bold text-slate-800 tracking-tight mb-3">Data Filters</h4>
                        <div class="space-y-3">
                            <div>
                                <label
                                    class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block mb-1">Select
                                    Batch</label>
                                <select id="filter-att-batch" onchange="fetchAttendanceData()"
                                    class="w-full text-xs font-bold text-slate-600 bg-slate-50 border border-slate-100 rounded-xl py-2 px-3 focus:outline-none focus:border-[#f97316] transition-all">
                                    <option value="">All Batches</option>
                                    @foreach($batches as $b)
                                        <option value="{{ $b->id }}">{{ $b->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block mb-1">Date
                                    Range (Month)</label>
                                <select id="filter-att-month" onchange="fetchAttendanceData()"
                                    class="w-full text-xs font-bold text-slate-600 bg-slate-50 border border-slate-100 rounded-xl py-2 px-3 focus:outline-none focus:border-[#f97316] transition-all">
                                    <option value="">Full Year</option>
                                    <option value="1">January</option>
                                    <option value="2">February</option>
                                    <option value="3">March</option>
                                    <option value="4">April</option>
                                    <option value="5">May</option>
                                    <option value="6">June</option>
                                    <option value="7">July</option>
                                    <option value="8">August</option>
                                    <option value="9">September</option>
                                    <option value="10">October</option>
                                    <option value="11">November</option>
                                    <option value="12">December</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <button onclick="fetchAttendanceData()"
                        class="w-full mt-4 py-2.5 bg-teal-600 hover:bg-teal-700 text-white text-xs font-bold rounded-xl shadow-sm transition-all text-center tracking-wider flex items-center justify-center gap-1.5">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                        </svg>
                        Apply Filters
                    </button>
                </div>
            </div>

            <!-- Full-Width Roster -->
            <div class="bg-white p-4 rounded-2xl border border-slate-100 shadow-sm mt-4">
                <div class="flex items-center justify-between mb-3">
                    <h4 class="text-xs font-bold text-slate-800 tracking-tight">Detailed Attendance Roster</h4>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-100 text-left text-xs font-medium">
                        <thead class="bg-slate-50 text-[9px] font-bold text-slate-400 uppercase tracking-wider">
                            <tr>
                                <th class="px-3 py-2.5">Student Name</th>
                                <th class="px-3 py-2.5">Batch</th>
                                <th class="px-3 py-2.5">Total Classes</th>
                                <th class="px-3 py-2.5">Present</th>
                                <th class="px-3 py-2.5">Absent</th>
                                <th class="px-3 py-2.5">Ratio (%)</th>
                            </tr>
                        </thead>
                        <tbody id="att-roster-rows" class="divide-y divide-slate-50 font-bold text-slate-600 text-[11px]">
                            <!-- Roster records injected here -->
                        </tbody>
                    </table>
                </div>

                <div class="flex items-center justify-between mt-4 border-t border-slate-50 pt-3">
                    <div class="text-[10px] font-bold text-slate-400" id="roster-page-info">
                        Showing 1-10 of 0 students
                    </div>
                    <div class="flex items-center gap-1" id="roster-pagination-controls">
                        <!-- Controls generated here -->
                    </div>
                </div>
            </div>
        </div>

    <!-- Drill-down View: Student Data -->
    <div id="section-drilldown" class="hidden space-y-3">
        <div class="bg-white p-3 rounded-xl border border-slate-100 shadow-sm flex items-center justify-between">
            <div class="flex items-center gap-2">
                <div class="h-8 w-8 rounded-lg bg-orange-50 flex items-center justify-center text-[#f97316]">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                            d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                    </svg>
                </div>
                <div>
                    <h4 id="drilldown-batch-name" class="text-sm font-bold text-slate-800 leading-none">Batch Name</h4>
                    <p class="text-[9px] font-bold text-slate-400 uppercase tracking-wider mt-0.5">Student Analytics
                        Breakdown</p>
                </div>
            </div>
            <div class="flex items-center gap-2 pr-1">
                <div class="text-right">
                    <p class="text-[8px] font-bold text-slate-400 uppercase tracking-wider">Aggregate</p>
                    <p id="drilldown-avg" class="text-sm font-black text-[#f97316] tracking-tight">0%</p>
                </div>
            </div>
        </div>
        <div id="drilldown-student-grid" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-2">
            <!-- Student cards injected here -->
        </div>
    </div>
    <div id="section-performance" class="tab-section hidden space-y-4">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-xl font-[580] text-slate-800 tracking-tight">Student Performance Report</h2>
                <p class="text-xs font-medium text-slate-400 mt-0.5">Holistic assessment breakdown and tracking.</p>
            </div>
            <div class="flex items-center gap-2">
                <button onclick="exportPerformance()"
                    class="px-3.5 py-2 bg-white border border-slate-100 text-slate-600 rounded-xl font-bold text-xs shadow-sm hover:bg-slate-50 transition-all flex items-center gap-1.5">
                    <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                            d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                    </svg>
                    Export PDF
                </button>
            </div>
        </div>

        <!-- Stat Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-3">
            <!-- Card 1: Avg Grade -->
            <div class="bg-white p-4 rounded-2xl border border-slate-100 shadow-sm flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between">
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Average Grade</p>
                        <svg class="w-5 h-5 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 14zm0 0l-6.16-3.422a12.083 12.083 0 00-.665 6.479A11.952 11.952 0 0012 14z" />
                        </svg>
                    </div>
                    <h3 id="perf-avg-grade" class="text-xl font-extrabold text-slate-800 mt-2">N/A</h3>
                    <p class="text-[9px] text-emerald-600 font-bold mt-1 flex items-center gap-1">
                        <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                        </svg>
                        +2.4% from last term
                    </p>
                </div>
            </div>

            <!-- Card 2: Pass Pct -->
            <div class="bg-white p-4 rounded-2xl border border-slate-100 shadow-sm flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between">
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Pass Percentage</p>
                        <svg class="w-5 h-5 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4" />
                        </svg>
                    </div>
                    <h3 id="perf-pass-pct" class="text-xl font-extrabold text-slate-800 mt-2">0%</h3>
                    <div class="w-full bg-slate-100 h-1.5 rounded-full mt-2 overflow-hidden">
                        <div id="perf-pass-bar" class="bg-emerald-500 h-full rounded-full" style="width: 0%"></div>
                    </div>
                </div>
            </div>

            <!-- Card 3: Top Batch -->
            <div class="bg-white p-4 rounded-2xl border border-slate-100 shadow-sm flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between">
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Top Performing Batch</p>
                        <svg class="w-5 h-5 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                    </div>
                    <h3 id="perf-top-batch" class="text-sm font-extrabold text-slate-800 mt-2">N/A</h3>
                    <p id="perf-top-batch-score" class="text-[9px] text-slate-400 font-bold mt-1">Avg. Score: 0/100</p>
                </div>
            </div>

            <!-- Card 4: Needs Attention -->
            <div class="bg-rose-50/30 p-4 rounded-2xl border border-rose-100 shadow-sm flex flex-col justify-between">
                <div>
                    <p class="text-[10px] font-bold text-rose-500 uppercase tracking-wider">Needs Attention</p>
                    <h3 id="perf-needs-attention" class="text-xl font-extrabold text-rose-600 mt-2">0</h3>
                    <p class="text-[9px] text-slate-400 font-bold mt-1">Students below 50% avg.</p>
                </div>
            </div>
        </div>

        <!-- Charts Row -->
        <div class="grid grid-cols-1 gap-3">
            <div class="bg-white p-4 rounded-2xl border border-slate-100 shadow-sm">
                <div class="flex items-center justify-between mb-3">
                    <div>
                        <h4 class="text-xs font-bold text-slate-800 tracking-tight">Academic Growth Trend</h4>
                    </div>
                    <div class="flex items-center gap-3">
                        <div class="flex items-center gap-1.5">
                            <span class="w-2.5 h-2.5 rounded-full bg-[#ff6c00]"></span>
                            <span class="text-[9px] font-bold text-slate-500">Current Term</span>
                        </div>
                        <div class="flex items-center gap-1.5">
                            <span class="w-2.5 h-2.5 rounded-full bg-slate-300"></span>
                            <span class="text-[9px] font-bold text-slate-500">Last Term</span>
                        </div>
                    </div>
                </div>
                <!-- Line Chart placeholder drawn neatly via custom CSS curves -->
                <div class="h-44 flex items-end justify-between px-2 pt-4 relative" id="performance-growth-container">
                    <svg class="absolute inset-0 w-full h-full p-4" viewBox="0 0 500 100" preserveAspectRatio="none">
                        <!-- Last Term Grey Line -->
                        <path d="M0,80 Q50,40 100,70 T200,60 T300,80 T400,50 T500,70" fill="none" stroke="#cbd5e1"
                            stroke-width="2.5" />
                        <!-- Current Term Orange Line -->
                        <path d="M0,90 Q50,60 100,40 T200,75 T300,30 T400,65 T500,20" fill="none" stroke="#ff6c00"
                            stroke-width="3" />
                    </svg>
                    <div class="w-full flex justify-between absolute bottom-1 px-4 text-[8px] font-bold text-slate-400">
                        <span>Jan</span><span>Feb</span><span>Mar</span><span>Apr</span><span>May</span><span>Jun</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Detailed Student Ranking -->
        <div class="bg-white p-4 rounded-2xl border border-slate-100 shadow-sm">
            <div class="flex items-center justify-between mb-3">
                <h4 class="text-xs font-bold text-slate-800 tracking-tight">Detailed Student Ranking</h4>
                <div class="relative w-48">
                    <input type="text" id="search-perf-student" onkeyup="renderPerformanceRanking()"
                        placeholder="Search by name, batch..."
                        class="w-full text-[10px] font-bold text-slate-800 placeholder-slate-400 px-3 py-1.5 bg-slate-50 border border-slate-100 rounded-xl focus:outline-none focus:border-[#ff6c00] transition-all">
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-100 text-left text-xs font-medium">
                    <thead class="bg-slate-50 text-[9px] font-bold text-slate-400 uppercase tracking-wider">
                        <tr>
                            <th class="px-3 py-2.5">Rank</th>
                            <th class="px-3 py-2.5">Student Name</th>
                            <th class="px-3 py-2.5">Batch</th>
                            <th class="px-3 py-2.5">Attendance %</th>
                            <th class="px-3 py-2.5">Average Score</th>
                        </tr>
                    </thead>
                    <tbody id="perf-ranking-rows" class="divide-y divide-slate-50 font-bold text-slate-600 text-[11px]">
                        <!-- Dynamic Rankings injected here -->
                    </tbody>
                </table>
            </div>

            <!-- Frontend Pagination Controls -->
            <div class="flex items-center justify-between mt-4 px-1">
                <span class="text-[10px] font-bold text-slate-400" id="perf-pagination-info">Showing 1-10 of 0
                    students</span>
                <div class="flex items-center gap-1">
                    <button onclick="changePerfPage(-1)" id="perf-prev-btn"
                        class="px-2 py-1 bg-slate-50 hover:bg-slate-100 text-slate-500 font-bold text-[10px] rounded-lg border border-slate-100 disabled:opacity-50 flex items-center justify-center">Prev</button>
                    <div class="flex items-center gap-1" id="perf-page-numbers"></div>
                    <button onclick="changePerfPage(1)" id="perf-next-btn"
                        class="px-2 py-1 bg-slate-50 hover:bg-slate-100 text-slate-500 font-bold text-[10px] rounded-lg border border-slate-100 disabled:opacity-50 flex items-center justify-center">Next</button>
                </div>
            </div>
    </div>
</div>
</div>


    <script>
        const API_REPORTS_URL = "/api/v1/institute/reports";
        const API_BATCHES_URL = "/api/v1/institute/batches";
        const API_STUDENTS_URL = "/api/v1/institute/students";
        const CSRF_TOKEN = "{{ csrf_token() }}";

        let currentTab = 'fees';
        let globalBatches = @json($batches);
        let globalFeeData = null;
        let globalAttendanceData = null;
        let currentRosterPage = 1;
        let currentFeePage = 1;
        const feeRowsPerPage = 10;
        const rosterRowsPerPage = 10;

        function changeFeePage(direction) {
            currentFeePage += direction;
            renderFeeBatchCards();
        }

        function goToFeePage(page) {
            currentFeePage = page;
            renderFeeBatchCards();
        }

        let globalPerformanceData = [];
        let currentPerfPage = 1;
        const perfRowsPerPage = 10;

        async function fetchPerformanceData() {
            toggleLoader(true);
            try {
                const res = await fetch("/api/v1/institute/reports/performance", { headers: { 'Accept': 'application/json' } });
                const result = await res.json();
                if (result.status === 'success') {
                    const roster = result.data.student_roster || [];
                    globalPerformanceData = roster.map((item, idx) => {
                        return {
                            rank: idx + 1,
                            name: item.student_name,
                            batch_name: item.batch_name || 'N/A',
                            attendance: parseFloat(item.avg_attendance || 0),
                            score: parseFloat(item.avg_score || 0),
                            id_label: '#ST-' + item.student_id
                        };
                    });

                    globalPerformanceData.sort((a, b) => b.score - a.score);
                    globalPerformanceData.forEach((s, i) => s.rank = i + 1);

                    const summary = result.data.summary || {};
                    document.getElementById('perf-avg-grade').innerText = summary.average_grade || 'N/A';
                    document.getElementById('perf-pass-pct').innerText = summary.pass_percentage || '0%';
                    document.getElementById('perf-pass-bar').style.width = summary.pass_percentage || '0%';
                    document.getElementById('perf-needs-attention').innerText = summary.needs_attention || '0';

                    renderPerformanceRanking();

                    const trends = result.data.trends || [];
                    const svgContainer = document.getElementById('performance-growth-container');
                    if (trends.length > 0 && svgContainer) {
                        const width = 500;
                        const height = 100;
                        const stepX = width / (trends.length - 1);

                        let pathD = "";
                        trends.forEach((t, i) => {
                            const x = i * stepX;
                            const y = 90 - ((t.avg_score / 100) * 80);
                            pathD += (i === 0 ? `M` : ` L`) + `${x},${y}`;
                        });

                        let labelsHtml = trends.map(t => `<span>${t.month}</span>`).join('');
                        let tooltipHtml = `<div id="growth-tooltip" class="absolute hidden bg-slate-900 text-white text-[9px] font-bold px-2 py-1 rounded-lg shadow-md pointer-events-none transition-all duration-150 z-30 transform -translate-x-1/2 -translate-y-full mt-2"></div>`;

                        svgContainer.innerHTML = `
                                <svg class="absolute inset-0 w-full h-full p-4" viewBox="0 0 500 100" preserveAspectRatio="none">
                                    <path d="${pathD}" fill="none" stroke="#ff6c00" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                                <div class="w-full flex justify-between absolute bottom-1 px-4 text-[8px] font-bold text-slate-400">
                                    ${labelsHtml}
                                </div>
                                ${tooltipHtml}
                            `;

                        svgContainer.addEventListener('mousemove', (e) => {
                            const rect = svgContainer.getBoundingClientRect();
                            const mouseX = e.clientX - rect.left;
                            const pctX = mouseX / rect.width;
                            const svgX = pctX * 500;

                            let closestIdx = 0;
                            let minDiff = 500;
                            trends.forEach((t, i) => {
                                const pointX = i * stepX;
                                const diff = Math.abs(svgX - pointX);
                                if (diff < minDiff) {
                                    minDiff = diff;
                                    closestIdx = i;
                                }
                            });

                            const closestPoint = trends[closestIdx];
                            const tooltip = document.getElementById('growth-tooltip');
                            if (tooltip) {
                                tooltip.innerText = `${closestPoint.month}: ${closestPoint.avg_score}%`;
                                tooltip.style.left = `${(closestIdx * stepX / 500) * 100}%`;
                                const pointY = 90 - ((closestPoint.avg_score / 100) * 80);
                                tooltip.style.top = `${(pointY / 100) * 100}%`;
                                tooltip.classList.remove('hidden');
                            }
                        });

                        svgContainer.addEventListener('mouseleave', () => {
                            const tooltip = document.getElementById('growth-tooltip');
                            if (tooltip) tooltip.classList.add('hidden');
                        });
                    }
                }
            } catch (e) {
                console.error('Performance load error:', e);
            } finally {
                toggleLoader(false);
            }
        }

        function renderPerformanceRanking() {
            const container = document.getElementById('perf-ranking-rows');
            const searchVal = document.getElementById('search-perf-student').value.toLowerCase();

            let filtered = globalPerformanceData;
            if (searchVal) {
                filtered = globalPerformanceData.filter(s =>
                    s.name.toLowerCase().includes(searchVal) ||
                    s.batch_name.toLowerCase().includes(searchVal)
                );
            }

            const totalItems = filtered.length;
            const totalPages = Math.ceil(totalItems / perfRowsPerPage);

            if (currentPerfPage < 1) currentPerfPage = 1;
            if (currentPerfPage > totalPages) currentPerfPage = totalPages;

            const startIdx = (currentPerfPage - 1) * perfRowsPerPage;
            const endIdx = startIdx + perfRowsPerPage;
            const pageData = filtered.slice(startIdx, endIdx);

            document.getElementById('perf-pagination-info').innerText = `Showing ${startIdx + 1}-${Math.min(endIdx, totalItems)} of ${totalItems} students`;
            document.getElementById('perf-prev-btn').disabled = (currentPerfPage === 1);
            document.getElementById('perf-next-btn').disabled = (currentPerfPage === totalPages || totalPages === 0);

            let pagesHtml = '';
            for (let p = 1; p <= totalPages; p++) {
                const isActive = p === currentPerfPage;
                pagesHtml += `<button onclick="goToPerfPage(${p})" class="px-2 py-0.5 font-bold text-[10px] rounded-md transition-all ${isActive ? 'bg-[#ff6c00] text-white' : 'bg-slate-50 text-slate-400 hover:bg-slate-100'}">${p}</button>`;
            }
            document.getElementById('perf-page-numbers').innerHTML = pagesHtml;

            if (globalPerformanceData.length > 0) {
                const batchScores = {};
                globalPerformanceData.forEach(s => {
                    if (!batchScores[s.batch_name]) batchScores[s.batch_name] = { sum: 0, count: 0 };
                    batchScores[s.batch_name].sum += s.score;
                    batchScores[s.batch_name].count += 1;
                });
                let topBatch = 'N/A';
                let topAvg = 0;
                for (const b in batchScores) {
                    const avg = batchScores[b].sum / batchScores[b].count;
                    if (avg > topAvg) {
                        topAvg = avg;
                        topBatch = b;
                    }
                }
                document.getElementById('perf-top-batch').innerText = topBatch;
                document.getElementById('perf-top-batch-score').innerText = `Avg. Score: ${topAvg.toFixed(1)}/100`;
            }

            if (pageData.length > 0) {
                container.innerHTML = pageData.map(s => {
                    const rankLabel = s.rank.toString().padStart(2, '0');
                    const rankColor = s.rank === 1 ? 'text-amber-500 bg-amber-50' :
                        s.rank === 2 ? 'text-slate-500 bg-slate-50' :
                            s.rank === 3 ? 'text-orange-500 bg-orange-50' : 'text-slate-400 bg-slate-50';
                    return `
                            <tr class="hover:bg-slate-50/40 transition-colors">
                                <td class="px-3 py-3">
                                    <span class="px-2 py-1 rounded-lg font-black text-[10px] ${rankColor}">${rankLabel}</span>
                                </td>
                                <td class="px-3 py-3 flex items-center gap-2">
                                    <div class="h-7 w-7 rounded-full bg-orange-100 text-[#ff6c00] font-bold text-xs flex items-center justify-center">
                                        ${s.name.substring(0, 1)}
                                    </div>
                                    <div>
                                        <h5 class="text-xs font-bold text-slate-800 leading-none">${s.name}</h5>
                                        <p class="text-[8px] font-bold text-slate-400 mt-1 uppercase">${s.id_label}</p>
                                    </div>
                                </td>
                                <td class="px-3 py-3 text-slate-600">${s.batch_name}</td>
                                <td class="px-3 py-3">
                                    <div class="flex items-center gap-2">
                                        <div class="w-12 bg-slate-100 h-1 rounded-full overflow-hidden">
                                            <div class="bg-emerald-500 h-full rounded-full" style="width: ${s.attendance}%"></div>
                                        </div>
                                        <span class="text-slate-800 font-bold text-[10px]">${s.attendance}%</span>
                                    </div>
                                </td>
                                <td class="px-3 py-3 text-slate-800 font-extrabold text-xs">${s.score}</td>
                            </tr>
                        `;
                }).join('');
            } else {
                container.innerHTML = `<tr><td colspan="5" class="px-3 py-4 text-center text-slate-400 italic">No rankings available.</td></tr>`;
            }
        }

        function changePerfPage(direction) {
            currentPerfPage += direction;
            renderPerformanceRanking();
        }

        function goToPerfPage(page) {
            currentPerfPage = page;
            renderPerformanceRanking();
        }

        async function fetchFeeData() {
            toggleLoader(true);
            try {
                const res = await fetch(`${API_REPORTS_URL}/fee`, { headers: { 'Accept': 'application/json' } });
                const result = await res.json();
                if (result.status === 'success') {
                    globalFeeData = result.data;
                    renderFeeBatchCards();
                }
            } catch (e) {
                console.error('Fee fetch error:', e);
            } finally {
                toggleLoader(false);
            }
        }

        function renderBatchCards() {
            if (currentTab === 'fees') renderFeeBatchCards();
            if (currentTab === 'attendance') renderAttendanceBatchCards();
        }

        function renderFeeBatchCards() {
            const summary = globalFeeData.summary || { total_amount: 0, paid_amount: 0 };
            const total = summary.total_amount || 0;
            const collected = summary.paid_amount || 0;
            const pending = total - collected;
            const pct = total > 0 ? ((collected / total) * 100).toFixed(1) : '0.0';

            document.getElementById('fee-total-expected').innerText = '₹' + total.toLocaleString();
            document.getElementById('fee-total-collected-new').innerText = '₹' + collected.toLocaleString();
            document.getElementById('fee-total-pending-new').innerText = '₹' + pending.toLocaleString();
            document.getElementById('fee-collection-rate').innerText = pct + '%';
            document.getElementById('fee-rate-bar').style.width = pct + '%';

            const feeBatches = globalFeeData.batches || [];

            // By Batch Section
            const sortedBatches = [...feeBatches].sort((a, b) => {
                const b1 = (a.total_collected + a.total_due) || 1;
                const pct1 = a.total_collected / b1;

                const b2 = (b.total_collected + b.total_due) || 1;
                const pct2 = b.total_collected / b2;
                return pct2 - pct1;
            });

            const topBatchesContainer = document.getElementById('fee-top-batches');
            if (topBatchesContainer) {
                topBatchesContainer.innerHTML = sortedBatches.map(b => {
                    const billed = b.total_collected + b.total_due;
                    const paid = b.total_collected;
                    const bPct = billed > 0 ? ((paid / billed) * 100).toFixed(0) : '0';
                    return `
                            <div class="flex items-center justify-between border-b border-slate-50 pb-1.5 last:border-0 last:pb-0">
                                <div>
                                    <h5 class="text-[10px] font-bold text-slate-800 leading-none">${b.batch_name}</h5>
                                    <p class="text-[8px] font-bold text-slate-400 mt-1 uppercase">${b.students_count || 0} Students</p>
                                </div>
                                <div class="text-right">
                                    <span class="text-[10px] font-black text-emerald-600">${bPct}%</span>
                                    <p class="text-[7px] font-bold text-slate-400 mt-0.5">₹${(paid / 1000).toFixed(1)}k / ₹${(billed / 1000).toFixed(1)}k</p>
                                </div>
                            </div>
                        `;
                }).join('');
            }

            // Collection Trends Chart mapping real payload
            const chartContainer = document.getElementById('fee-trends-chart');
            if (chartContainer) {
                const trends = globalFeeData.trends || [];

                // Find maximum value to normalize heights properly
                const maxVal = Math.max(...trends.map(t => t.collected), 100);

                chartContainer.innerHTML = trends.map(t => {
                    const hCollected = maxVal > 0 ? ((t.collected / maxVal) * 100) : 0;

                    return `
                            <div class="flex-1 flex flex-col items-center gap-1.5 h-full justify-end">
                                <div class="w-full flex items-end justify-center h-full">
                                    <div class="bg-[#ff6c00] w-3 rounded-t-sm shadow-sm transition-all duration-500" style="height: ${hCollected}%" title="Collected: ₹${t.collected}"></div>
                                </div>
                                <span class="text-[8px] font-bold text-slate-400">${t.month}</span>
                            </div>
                        `;
                }).join('');
            }

            const rosterContainer = document.getElementById('fee-roster-rows');
            if (feeBatches && feeBatches.length > 0) {
                const totalFeeItems = feeBatches.length;
                const totalFeePages = Math.ceil(totalFeeItems / feeRowsPerPage);

                if (currentFeePage < 1) currentFeePage = 1;
                if (currentFeePage > totalFeePages) currentFeePage = totalFeePages;

                const startIdx = (currentFeePage - 1) * feeRowsPerPage;
                const endIdx = startIdx + feeRowsPerPage;
                const pageBatches = feeBatches.slice(startIdx, endIdx);

                document.getElementById('fee-pagination-info').innerText = `Showing ${startIdx + 1}-${Math.min(endIdx, totalFeeItems)} of ${totalFeeItems} batches`;
                document.getElementById('fee-prev-btn').disabled = (currentFeePage === 1);
                document.getElementById('fee-next-btn').disabled = (currentFeePage === totalFeePages || totalFeePages === 0);

                let pagesHtml = '';
                for (let p = 1; p <= totalFeePages; p++) {
                    const isActive = p === currentFeePage;
                    pagesHtml += `<button onclick="goToFeePage(${p})" class="px-2 py-0.5 font-bold text-[10px] rounded-md transition-all ${isActive ? 'bg-[#ff6c00] text-white' : 'bg-slate-50 text-slate-400 hover:bg-slate-100'}">${p}</button>`;
                }
                document.getElementById('fee-page-numbers').innerHTML = pagesHtml;

                rosterContainer.innerHTML = pageBatches.map(batch => {
                    const billed = batch.total_collected + batch.total_due;
                    const paid = batch.total_collected;
                    const due = batch.total_due;
                    const bPct = billed > 0 ? ((paid / billed) * 100).toFixed(1) : '0.0';

                    return `
                            <tr class="hover:bg-slate-50/40 transition-colors">
                                <td class="px-3 py-3 flex items-center gap-2">
                                    <div class="h-8 w-8 rounded-xl bg-orange-100 text-[#ff6c00] font-bold text-xs flex items-center justify-center">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                        </svg>
                                    </div>
                                    <div>
                                        <h5 class="text-xs font-bold text-slate-800 leading-none">${batch.batch_name}</h5>
                                        <p class="text-[8px] font-bold text-slate-400 mt-1 uppercase tracking-wider">${batch.students_count || 0} Students enrolled</p>
                                    </div>
                                </td>
                                <td class="px-3 py-3 text-slate-600 font-bold text-xs">₹${billed.toLocaleString()}</td>
                                <td class="px-3 py-3 text-emerald-600 font-black text-xs">₹${paid.toLocaleString()}</td>
                                <td class="px-3 py-3 text-rose-500 font-black text-xs">₹${due.toLocaleString()}</td>
                                <td class="px-3 py-3">
                                    <div class="flex items-center gap-2">
                                        <div class="w-16 bg-slate-100 h-1 rounded-full overflow-hidden">
                                            <div class="bg-[#ff6c00] h-full rounded-full" style="width: ${bPct}%"></div>
                                        </div>
                                        <span class="text-slate-800 font-black text-xs">${bPct}%</span>
                                    </div>
                                </td>
                            </tr>
                        `;
                }).join('');
            } else {
                rosterContainer.innerHTML = `
                        <tr>
                            <td colspan="5" class="px-3 py-4 text-center text-slate-400 font-medium italic">No corresponding collections mapped.</td>
                        </tr>
                    `;
            }
        }

        async function fetchAttendanceData() {
            currentRosterPage = 1;
            toggleLoader(true);
            try {
                const batchId = document.getElementById('filter-att-batch').value;
                const month = document.getElementById('filter-att-month').value;

                let url = `${API_REPORTS_URL}/attendance`;
                let params = [];
                if (batchId) params.push(`batch_id=${batchId}`);
                if (month) params.push(`month=${month}`);
                if (params.length > 0) url += `?${params.join('&')}`;

                const res = await fetch(url, { headers: { 'Accept': 'application/json' } });
                const result = await res.json();

                if (result.status === 'success') {
                    globalAttendanceData = result.data;
                    renderAttendanceSummary();
                }
            } catch (e) {
                console.error('Attendance fetch error:', e);
            } finally {
                toggleLoader(false);
            }
        }

        function renderAttendanceSummary() {
            const summary = globalAttendanceData.summary || { present: 0, absent: 0, leave: 0, total: 0 };
            const batches = globalAttendanceData.batches || [];

            // Card 1: Overall Percentage
            const pct = summary.total > 0 ? ((summary.present / summary.total) * 100).toFixed(1) : '0.0';
            document.getElementById('att-overall-pct').innerText = pct + '%';

            // Card 2: Present Metrics
            document.getElementById('att-present-count').innerText = summary.present;
            document.getElementById('att-total-out-of').innerText = `Out of ${summary.total} aggregate logs`;

            // Card 3: Top Performing Batch
            let topBatch = { batch_name: 'N/A', avg_attendance: 0 };
            let lowAlerts = 0;

            batches.forEach(b => {
                if (b.avg_attendance > topBatch.avg_attendance) {
                    topBatch = b;
                }
                if (b.avg_attendance < 75) {
                    lowAlerts++;
                }
            });

            document.getElementById('att-highest-batch').innerText = topBatch.batch_name || topBatch.name || 'N/A';
            document.getElementById('att-highest-pct').innerText = (topBatch.avg_attendance || 0) + '%';
            document.getElementById('att-alerts-count').innerText = lowAlerts;

            // Populate Calendar Days dynamically based on real student attendance logs
            const calendarContainer = document.getElementById('calendar-days');
            if (calendarContainer) {
                let html = '';
                
                let monthSelected = parseInt(document.getElementById('filter-att-month').value) || (new Date().getMonth() + 1);
                let currentYear = new Date().getFullYear();
                
                // Calculate first day of the month (0=Sun, 1=Mon, ..., 6=Sat)
                const firstDayDate = new Date(currentYear, monthSelected - 1, 1);
                const firstDayIdx = firstDayDate.getDay(); 
                
                // Adjust offset for Mon-Sun grid
                // Mon=0, Tue=1, ..., Sat=5, Sun=6
                const offset = (firstDayIdx === 0) ? 6 : (firstDayIdx - 1);
                
                // Previous month days for padding
                const prevMonthLastDate = new Date(currentYear, monthSelected - 1, 0).getDate();
                for (let i = offset - 1; i >= 0; i--) {
                    html += `<div class="bg-slate-50/60 text-slate-300 py-2.5 rounded-xl">${prevMonthLastDate - i}</div>`;
                }

                const dayStatusMap = {};
                if (globalAttendanceData.attendance) {
                    globalAttendanceData.attendance.forEach(att => {
                        let dateStr = att.date || att.created_at || '';
                        if (dateStr) {
                            let dateObj = new Date(dateStr);
                            if (dateObj.getMonth() + 1 === monthSelected) {
                                let dayNum = dateObj.getDate();
                                if (!dayStatusMap[dayNum]) dayStatusMap[dayNum] = { present: 0, absent: 0, leave: 0 };
                                let st = (att.status || 'present').toLowerCase();
                                if (st === 'present') dayStatusMap[dayNum].present++;
                                else if (st === 'absent') dayStatusMap[dayNum].absent++;
                                else dayStatusMap[dayNum].leave++;
                            }
                        }
                    });
                }

                let daysInMonth = new Date(currentYear, monthSelected, 0).getDate();

                for (let day = 1; day <= daysInMonth; day++) {
                    let colorClass = 'bg-slate-50 text-slate-300';
                    if (dayStatusMap[day]) {
                        const counts = dayStatusMap[day];
                        if (counts.absent > 0 && counts.present === 0) {
                            colorClass = 'bg-red-500 text-white shadow-sm';
                        } else if (counts.present > 0 && counts.absent === 0) {
                            colorClass = 'bg-emerald-500 text-white shadow-sm';
                        } else {
                            colorClass = 'bg-orange-500 text-white shadow-sm';
                        }
                    }
                    html += `<div class="${colorClass} py-2.5 rounded-xl">${day}</div>`;
                }
                calendarContainer.innerHTML = html;
            }
            const rosterContainer = document.getElementById('att-roster-rows');
            if (globalAttendanceData.student_roster && globalAttendanceData.student_roster.length > 0) {
                const totalStudents = globalAttendanceData.student_roster.length;
                const totalPages = Math.ceil(totalStudents / rosterRowsPerPage);

                if (currentRosterPage > totalPages) currentRosterPage = totalPages;
                if (currentRosterPage < 1) currentRosterPage = 1;

                const startIdx = (currentRosterPage - 1) * rosterRowsPerPage;
                const endIdx = Math.min(startIdx + rosterRowsPerPage, totalStudents);

                document.getElementById('roster-page-info').innerText = `Showing ${startIdx + 1}-${endIdx} of ${totalStudents} students`;

                // Generate Pagination Controls
                let pagHtml = '';
                pagHtml += `<button onclick="changeRosterPage(${currentRosterPage - 1})" class="px-2.5 py-1 border border-slate-100 rounded-lg text-slate-500 font-bold text-[10px] hover:border-teal-500 hover:text-teal-600 transition-colors ${currentRosterPage === 1 ? 'opacity-40 pointer-events-none' : ''}">&lt;</button>`;

                for (let p = 1; p <= totalPages; p++) {
                    pagHtml += `<button onclick="changeRosterPage(${p})" class="px-2.5 py-1 rounded-lg font-bold text-[10px] transition-all ${p === currentRosterPage ? 'bg-teal-600 text-white shadow-sm' : 'border border-slate-100 text-slate-500 hover:border-teal-500 hover:text-teal-600'}">${p}</button>`;
                }

                pagHtml += `<button onclick="changeRosterPage(${currentRosterPage + 1})" class="px-2.5 py-1 border border-slate-100 rounded-lg text-slate-500 font-bold text-[10px] hover:border-teal-500 hover:text-teal-600 transition-colors ${currentRosterPage === totalPages ? 'opacity-40 pointer-events-none' : ''}">&gt;</button>`;
                document.getElementById('roster-pagination-controls').innerHTML = pagHtml;

                const pageItems = globalAttendanceData.student_roster.slice(startIdx, endIdx);

                rosterContainer.innerHTML = pageItems.map(r => {
                    const initials = r.student_name.split(' ').map(n => n[0]).join('').toUpperCase().substring(0, 2);
                    const pctVal = parseFloat(r.percentage);

                    let barColor = 'bg-emerald-600';
                    let textColor = 'text-emerald-600';
                    if (pctVal < 50) { barColor = 'bg-rose-500'; textColor = 'text-rose-500'; }
                    else if (pctVal < 75) { barColor = 'bg-[#ff6c00]'; textColor = 'text-[#ff6c00]'; }

                    return `
                            <tr class="hover:bg-slate-50/40 transition-colors">
                                <td class="px-3 py-3 flex items-center gap-2">
                                    <div class="h-8 w-8 rounded-full bg-orange-100 text-[#ff6c00] font-bold text-xs flex items-center justify-center">${initials}</div>
                                    <div>
                                        <h5 class="text-xs font-bold text-slate-800 leading-none">${r.student_name}</h5>
                                        <p class="text-[8px] font-bold text-slate-400 mt-1 uppercase tracking-wider">ID: #ST-${r.student_id}</p>
                                    </div>
                                </td>
                                <td class="px-3 py-3">
                                    <span class="px-2 py-1 bg-teal-50 text-teal-600 text-[8px] font-black uppercase tracking-wider rounded-md">${r.batch_name}</span>
                                </td>
                                <td class="px-3 py-3 text-slate-600 font-bold text-xs">${r.total_logs} Days</td>
                                <td class="px-3 py-3 text-emerald-600 font-black text-sm">${r.present}</td>
                                <td class="px-3 py-3 text-rose-500 font-black text-sm">${r.absent}</td>
                                <td class="px-3 py-3">
                                    <div class="flex items-center gap-2">
                                        <div class="w-16 bg-slate-100 h-1 rounded-full overflow-hidden">
                                            <div class="${barColor} h-full rounded-full" style="width: ${pctVal}%"></div>
                                        </div>
                                        <span class="${textColor} font-black text-xs">${r.percentage}</span>
                                    </div>
                                </td>
                            </tr>
                        `;
                }).join('');
            } else {
                rosterContainer.innerHTML = `
                        <tr>
                            <td colspan="6" class="px-3 py-4 text-center text-slate-400 font-medium italic">No individual student logs mapped for the current constraints.</td>
                        </tr>
                    `;
            }
        }

        function changeRosterPage(page) {
            currentRosterPage = page;
            renderAttendanceSummary();
        }

        async function drillDownBatch(batchId, batchName, type) {
            toggleLoader(true);
            try {
                const resp = await fetch(`${API_STUDENTS_URL}?batch_id=${batchId}`, { headers: { 'Accept': 'application/json' } });
                const result = await resp.json();
                const students = result.data.items;

                document.getElementById('drilldown-batch-name').innerText = batchName;

                const container = document.getElementById('drilldown-student-grid');

                if (type === 'fees') {
                    const batch = globalBatches.find(b => b.id == batchId);
                    const paid = batch ? (batch.total_paid || 0) : 0;
                    document.getElementById('drilldown-avg').innerText = 'COLLECTED: ₹' + paid;
                    document.getElementById('drilldown-avg').classList.replace('text-blue-600', 'text-emerald-600');

                    container.innerHTML = students.map(s => {
                        const due = s.total_due || 0;
                        return `
                                <div class="bg-white p-4 rounded-xl border border-slate-100 shadow-sm flex items-center gap-3 hover:border-blue-500 hover:shadow-md transition-all duration-300">
                                    <div class="h-10 w-10 bg-slate-100 rounded-xl flex items-center justify-center text-slate-600 font-bold text-sm">${s.name.charAt(0).toUpperCase()}</div>
                                    <div class="flex-1">
                                        <h5 class="text-sm font-bold text-slate-900 leading-none">${s.name}</h5>
                                    </div>
                                    <div class="text-right">
                                        <span class="px-2.5 py-1 ${due <= 0 ? 'bg-emerald-50 text-emerald-600' : 'bg-rose-50 text-rose-600'} rounded-lg text-[10px] font-bold uppercase tracking-wider">
                                            ${due <= 0 ? 'Paid' : '₹' + due}
                                        </span>
                                    </div>
                                </div>
                            `;
                    }).join('');
                } else if (type === 'attendance') {
                    const pct = Math.floor(Math.random() * (98 - 75 + 1) + 75); // Mock
                    document.getElementById('drilldown-avg').innerText = 'AVG PRESENT: ' + pct + '%';
                    document.getElementById('drilldown-avg').classList.replace('text-emerald-600', 'text-blue-600');

                    container.innerHTML = students.map(s => {
                        const sPct = Math.floor(Math.random() * (100 - 60 + 1) + 60); // Mock
                        return `
                                <div class="bg-white p-4 rounded-xl border border-slate-100 shadow-sm flex items-center gap-3 hover:border-blue-500 hover:shadow-md transition-all duration-300">
                                    <div class="h-10 w-10 bg-slate-100 rounded-xl flex items-center justify-center text-slate-600 font-bold text-sm">${s.name.charAt(0).toUpperCase()}</div>
                                    <div class="flex-1">
                                        <h5 class="text-sm font-bold text-slate-900 leading-none">${s.name}</h5>
                                        <div class="mt-2.5 flex items-center gap-2">
                                            <div class="flex-1 bg-slate-50 h-1.5 rounded-full overflow-hidden border border-slate-100/50">
                                                <div class="bg-blue-500 h-full rounded-full" style="width: ${sPct}%"></div>
                                            </div>
                                            <span class="text-[9px] font-bold text-blue-600">${sPct}%</span>
                                        </div>
                                    </div>
                                </div>
                            `;
                    }).join('');
                }

                // UI Transitions
                document.querySelectorAll('.tab-section').forEach(s => s.classList.add('hidden'));
                document.getElementById('section-hub').classList.add('hidden');
                document.getElementById('breadcrumb').classList.remove('hidden');
                document.getElementById('section-drilldown').classList.remove('hidden');
                document.getElementById('page-title').innerText = 'Batch Intelligence';

            } catch (error) {
                console.error('Drill-down Error:', error);
            } finally {
                toggleLoader(false);
            }
        }

        function exitDrillDown() {
            if (!document.getElementById('section-drilldown').classList.contains('hidden')) {
                document.getElementById('section-drilldown').classList.add('hidden');
                document.getElementById(`section-${currentTab}`).classList.remove('hidden');
                document.getElementById('page-title').innerText = 'Reports Hub';
            } else {
                document.querySelectorAll('.tab-section').forEach(sec => sec.classList.add('hidden'));
                document.getElementById('section-hub').classList.remove('hidden');
                document.getElementById('breadcrumb').classList.add('hidden');
                document.getElementById('page-title').classList.remove('hidden');
                document.getElementById('page-subtitle').classList.remove('hidden');
                document.getElementById('page-title').innerText = 'Reports Hub';
            }
        }

        function switchTab(tabId) {
            currentTab = tabId;
            document.getElementById('section-hub').classList.add('hidden');
            document.querySelectorAll('.tab-section').forEach(sec => sec.classList.add('hidden'));
            document.getElementById(`section-${tabId}`).classList.remove('hidden');
            document.getElementById('breadcrumb').classList.remove('hidden');

            document.getElementById('page-title').classList.add('hidden');
            document.getElementById('page-subtitle').classList.add('hidden');

            if (tabId === 'attendance') {
                fetchAttendanceData();
            } else if (tabId === 'fees') {
                fetchFeeData();
            } else if (tabId === 'performance') {
                fetchPerformanceData();
            }
        }

        function toggleLoader(show) { document.getElementById('loader').classList.toggle('hidden', !show); }

        function exportFees() {
            const batchId = document.getElementById('filter-att-batch').value;
            window.location.href = `/api/v1/institute/reports/fee/export${batchId ? '?batch_id=' + batchId : ''}`;
        }

        function exportAttendance() {
            const batchId = document.getElementById('filter-att-batch').value;
            const month = document.getElementById('filter-att-month').value;
            let url = `/api/v1/institute/reports/attendance/export`;
            let params = [];
            if (batchId) params.push(`batch_id=${batchId}`);
            if (month) params.push(`month=${month}`);
            if (params.length > 0) url += `?${params.join('&')}`;

            window.location.href = url;
        }

        function exportPerformance() {
            const batchId = document.getElementById('filter-att-batch').value;
            let url = `/api/v1/institute/reports/performance/export`;
            if (batchId) url += `?batch_id=${batchId}`;
            window.location.href = url;
        }
    </script>
@endsection