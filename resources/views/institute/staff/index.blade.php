@extends('layouts.institute')

@section('content')
    <style>
        /* Hide number input spinners */
        input::-webkit-outer-spin-button,
        input::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        input[type=number] {
            -moz-appearance: textfield;
        }

        /* Hide scrollbars for overflow items */
        .no-scrollbar::-webkit-scrollbar {
            display: none;
        }

        .no-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }

        /* Toast Notification Styles */
        :root {
            --primary: #FF6B00;
            --primary-hover: #e66000;
        }

        #toast-container {
            position: fixed;
            top: 1.5rem;
            right: 1.5rem;
            z-index: 9999;
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
            pointer-events: none;
        }

        .toast-item {
            pointer-events: auto;
            animation: toast-in 0.5s cubic-bezier(0.16, 1, 0.3, 1);
            transition: all 0.3s ease;
        }

        .toast-item.fade-out {
            opacity: 0;
            transform: translateX(20px) scale(0.95);
        }

        @keyframes toast-in {
            from {
                opacity: 0;
                transform: translateX(40px) scale(0.9);
            }

            to {
                opacity: 1;
                transform: translateX(0) scale(1);
            }
        }
    </style>
    <div class="max-w-7xl mx-auto ">
        <!-- Header Section -->
        <div class="flex flex-col md:flex-row md:items-center justify-between mb-4 gap-4">
            <div>
                <h1 class="text-xl font-semibold text-slate-800 tracking-tight">Staff Management Hub</h1>
                <p class="text-xs text-slate-400 mt-0.5 font-medium">Manage your institute's faculty and support staff.</p>
            </div>


        </div>

        <!-- Navigation & Actions -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-1">
            <div class="flex items-center gap-2 p-1 bg-slate-100 rounded-2xl overflow-x-auto max-w-full no-scrollbar shrink-0">
                <button onclick="switchTab('staff')" id="tab-staff"
                    class="px-6 py-2.5 rounded-xl text-xs font-bold transition-all bg-[#FF6B00] text-white shadow-lg shadow-orange-900/10 whitespace-nowrap shrink-0">
                    Staffs Management
                </button>
                <button onclick="switchTab('attendance')" id="tab-attendance"
                    class="px-6 py-2.5 rounded-xl text-xs font-bold transition-all text-slate-500 whitespace-nowrap shrink-0">
                    Attendance Management
                </button>
                <button onclick="switchTab('salary')" id="tab-salary"
                    class="px-6 py-2.5 rounded-xl text-xs font-bold transition-all text-slate-500 whitespace-nowrap shrink-0">
                    Salary Management
                </button>
            </div>



        </div>




            <!-- Staff Management View -->
            <div id="staff-view" class="relative">
                <!-- Loading Spinner -->
                <div id="loading-spinner"
                    class="absolute inset-0 z-50 bg-white/60 backdrop-blur-[2px] hidden flex items-center justify-center rounded-3xl">
                    <div class="h-10 w-10 border-4 border-slate-100 border-t-[#FF6B00] rounded-full animate-spin"></div>
                </div>

                <!-- Search & Filter Bar -->
                <div class="bg-white rounded-2xl border border-slate-100 p-3 mb-2 flex flex-col lg:flex-row lg:items-center gap-3">
                    <div class="w-full lg:flex-1 flex flex-col sm:flex-row items-stretch sm:items-center gap-2">
                        <div class="relative flex-1 min-w-0">
                            <input type="text" id="staff-search" placeholder="Search staff name or department..."
                                class="w-full pl-10 pr-24 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium focus:border-[#FF6B00] transition-all outline-none">
                            <svg class="w-4 h-4 absolute left-3.5 top-3.5 text-slate-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                            <button onclick="fetchStaff(1)"
                                class="absolute right-1.5 top-1.5 bottom-1.5 px-3 bg-[#FF6B00] text-white rounded-lg text-[10px] font-bold hover:bg-[#ea580c] transition-colors">
                                Search
                            </button>
                        </div>

                        @if(Auth::guard('institute')->user()->hasActiveSubscription())
                        <button onclick="openAddModal()"
                            class="px-4 py-2.5 bg-[#FF6B00] text-white rounded-xl text-xs font-bold flex items-center justify-center gap-1.5 hover:translate-y-[-1px] shadow-lg shadow-orange-900/10 active:scale-95 transition-all whitespace-nowrap shrink-0 sm:w-auto w-full">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4" />
                            </svg>
                            Add Staff
                        </button>
                        @endif
                    </div>

                    <div class="flex items-center gap-2 overflow-visible flex-wrap sm:flex-nowrap w-full lg:w-auto">
                        <!-- Custom Role Dropdown -->
                        <div class="relative w-full sm:w-auto" id="role-dropdown-container">
                            <button type="button" onclick="toggleCustomDropdown('role')" id="role-dropdown-btn"
                                class="flex items-center justify-between gap-3 px-4 py-2 bg-slate-50 border border-slate-100 rounded-xl text-[10px] font-black text-slate-600 hover:border-[#FF6B00] transition-all w-full sm:min-w-[130px]">
                                <span id="selected-role-label">All Roles</span>
                                <svg class="w-3 h-3 text-slate-400 transition-transform duration-200" id="role-chevron"
                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                        d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>
                            <div id="role-dropdown-menu"
                                class="absolute left-0 right-0 sm:right-0 sm:left-auto z-[100] mt-2 w-full sm:w-48 bg-white border border-slate-100 rounded-xl shadow-xl overflow-hidden hidden transform origin-top transition-all">
                                <div class="py-1 max-h-60 overflow-y-auto no-scrollbar">
                                    <button type="button" onclick="selectCustomOption('role', '', 'All Roles')"
                                        class="w-full text-left px-4 py-2.5 text-[10px] font-bold text-slate-600 hover:bg-slate-50 hover:text-[#FF6B00] transition-colors border-b border-slate-50 last:border-0">All
                                        Roles</button>
                                    @foreach($roles as $role)
                                        <button type="button"
                                            onclick="selectCustomOption('role', '{{ $role->id }}', '{{ $role->name }}')"
                                            class="w-full text-left px-4 py-2.5 text-[10px] font-bold text-slate-600 hover:bg-slate-50 hover:text-[#FF6B00] transition-colors border-b border-slate-50 last:border-0">{{ $role->name }}</button>
                                    @endforeach
                                </div>
                            </div>
                            <input type="hidden" id="role-filter" value="">
                        </div>

                        <!-- Custom Department Dropdown -->
                        <div class="relative w-full sm:w-auto" id="dept-dropdown-container">
                            <button type="button" onclick="toggleCustomDropdown('dept')" id="dept-dropdown-btn"
                                class="flex items-center justify-between gap-3 px-4 py-2 bg-slate-50 border border-slate-100 rounded-xl text-[10px] font-black text-slate-600 hover:border-[#FF6B00] transition-all w-full sm:min-w-[150px]">
                                <span id="selected-dept-label">All Departments</span>
                                <svg class="w-3 h-3 text-slate-400 transition-transform duration-200" id="dept-chevron"
                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                        d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>
                            <div id="dept-dropdown-menu"
                                class="absolute left-0 right-0 sm:right-0 sm:left-auto z-[100] mt-2 w-full sm:w-56 bg-white border border-slate-100 rounded-xl shadow-xl overflow-hidden hidden transform origin-top transition-all">
                                <div class="py-1 max-h-60 overflow-y-auto no-scrollbar">
                                    <button type="button" onclick="selectCustomOption('dept', '', 'All Departments')"
                                        class="w-full text-left px-4 py-2.5 text-[10px] font-bold text-slate-600 hover:bg-slate-50 hover:text-[#FF6B00] transition-colors border-b border-slate-50 last:border-0">All
                                        Departments</button>
                                    @foreach($departments as $dept)
                                        <button type="button"
                                            onclick="selectCustomOption('dept', '{{ $dept->id }}', '{{ $dept->name }}')"
                                            class="w-full text-left px-4 py-2.5 text-[10px] font-bold text-slate-600 hover:bg-slate-50 hover:text-[#FF6B00] transition-colors border-b border-slate-50 last:border-0">{{ $dept->name }}</button>
                                    @endforeach
                                </div>
                            </div>
                            <input type="hidden" id="dept-filter" value="">
                        </div>
                    </div>
                </div>

                <div id="staff-grid" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-2 relative min-h-[200px]">
                    <!-- Dynamic Content -->
                </div>

            <div id="pagination-container" class="mt-8"></div>
        </div>

        <!-- Attendance Management View -->
        <div id="attendance-view" class="hidden">
            <div class="bg-white rounded-2xl border border-slate-200/60 shadow-sm overflow-hidden">
                <!-- Table Header Actions -->
                <div class="p-4 border-b border-slate-100 flex flex-col md:flex-row md:items-center justify-between gap-4">
                    <div class="w-full md:flex-1 flex flex-col sm:flex-row items-stretch sm:items-center gap-2">
                        <div class="relative flex-1 min-w-0">
                            <input type="text" id="attendance-search-input" placeholder="Search staff name or department..."
                                class="w-full pl-10 pr-24 py-2 bg-slate-50 border border-slate-200 rounded-lg text-xs outline-none focus:border-[#FF6B00] transition-all">
                            <svg class="w-4 h-4 absolute left-3 top-2.5 text-slate-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                            <button onclick="fetchAttendance(1)"
                                class="absolute right-1.5 top-1 bottom-1 px-3 bg-[#FF6B00] text-white rounded-md text-[10px] font-bold hover:bg-[#ea580c] transition-colors">
                                Search
                            </button>
                        </div>

                        @if(Auth::guard('institute')->user()->hasActiveSubscription())
                        <button onclick="openAttendanceModal()"
                            class="px-4 py-2 bg-[#FF6B00] text-white rounded-lg text-xs font-bold shadow-lg shadow-orange-900/20 hover:translate-y-[-1px] active:scale-95 transition-all flex items-center justify-center gap-2 shrink-0 w-full sm:w-auto">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                            </svg>
                            Log Attendance
                        </button>
                        @endif
                    </div>

                    <div class="flex items-center gap-3 w-full md:w-auto justify-between md:justify-start">
                        <div class="flex items-center gap-1.5">
                            <div class="relative">
                                <input type="date" id="attendance-filter-date" onchange="fetchAttendance()"
                                    class="absolute opacity-0 pointer-events-none">
                                <button onclick="document.getElementById('attendance-filter-date').showPicker()"
                                    class="px-4 py-2 border border-slate-200 rounded-lg text-xs font-bold text-slate-600 hover:bg-slate-50 flex items-center gap-2">
                                    <svg class="w-4 h-4 text-[#FF6B00]" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 00-2 2z" />
                                    </svg>
                                    <span id="attendance-filter-label">Filter by Date</span>
                                </button>
                            </div>
                            <button id="clear-attendance-filter" onclick="clearAttendanceFilter()" style="display: none;"
                                class="h-6 w-6 flex items-center justify-center bg-rose-50 text-rose-500 rounded-md hover:bg-rose-100 transition-all"
                                title="Clear Filter">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                        <button onclick="exportAttendance()"
                            class="px-4 py-2 border border-slate-200 rounded-lg text-xs font-bold text-slate-600 hover:bg-slate-50 flex items-center gap-2">
                            <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 16v1a2 2 0 002 2h12a2 2 0 002-2v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                            </svg>
                            Export
                        </button>
                    </div>
                </div>

                <!-- Attendance Table -->
                <div class="overflow-x-auto relative">
                    <div id="attendance-loader"
                        class="absolute inset-0 bg-white/60 backdrop-blur-[2px] hidden flex items-center justify-center z-10">
                        <div class="h-10 w-10 border-4 border-slate-100 border-t-[#FF6B00] rounded-full animate-spin"></div>
                    </div>
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50/50">
                                <th class="px-4 py-3 text-[10px] font-bold text-slate-400 uppercase tracking-wider">Staff
                                    Name</th>
                                <th class="px-4 py-3 text-[10px] font-bold text-slate-400 uppercase tracking-wider">
                                    Department</th>
                                <th class="px-4 py-3 text-[10px] font-bold text-slate-400 uppercase tracking-wider">Date
                                </th>
                                <th class="px-4 py-3 text-[10px] font-bold text-slate-400 uppercase tracking-wider">Status
                                </th>
                                <th
                                    class="px-4 py-3 text-[10px] font-bold text-slate-400 uppercase tracking-wider text-center">
                                    Action</th>
                            </tr>
                        </thead>
                        <tbody id="attendance-table-body" class="divide-y divide-slate-100">
                            <!-- Dynamic Content -->
                        </tbody>
                    </table>
                </div>

                <!-- Table Footer -->
                <div id="attendance-pagination-container"
                    class="p-4 bg-slate-50/30 border-t border-slate-100 flex items-center justify-between">
                    <!-- Dynamic Pagination -->
                </div>
            </div>
        </div>

        <!-- Salary Management View -->
        <div id="salary-view" class="hidden">
            <div class="bg-white rounded-2xl border border-slate-200/60 shadow-sm overflow-hidden">
                <!-- Table Header Actions -->
                <div class="p-4 border-b border-slate-100 flex flex-col md:flex-row md:items-center justify-between gap-4">
                    <div class="w-full md:flex-1 flex flex-col sm:flex-row items-stretch sm:items-center gap-2">
                        <div class="relative flex-1 min-w-0">
                            <input type="text" id="salary-search-input" placeholder="Search staff name..."
                                class="w-full pl-10 pr-24 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium focus:border-[#FF6B00] transition-all outline-none">
                            <svg class="w-4 h-4 absolute left-3.5 top-3.5 text-slate-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                            <button onclick="fetchSalaries(1)"
                                class="absolute right-1.5 top-1.5 bottom-1.5 px-3 bg-[#FF6B00] text-white rounded-lg text-[10px] font-bold hover:bg-[#ea580c] transition-colors">
                                Search
                            </button>
                        </div>

                        @if(Auth::guard('institute')->user()->hasActiveSubscription())
                        <button onclick="openSalaryModal()"
                            class="px-4 py-2.5 bg-[#FF6B00] text-white rounded-xl text-xs font-bold shadow-lg shadow-orange-900/20 hover:translate-y-[-1px] active:scale-95 transition-all flex items-center justify-center gap-2 shrink-0 w-full sm:w-auto">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                            </svg>
                            Add Salary
                        </button>
                        @endif
                    </div>

                    <div class="flex items-center gap-3 w-full md:w-auto justify-between md:justify-start">
                        <div class="flex items-center gap-1.5">
                            <div class="relative">
                                <input type="month" id="salary-filter-month" onchange="fetchSalaries()"
                                    class="absolute opacity-0 pointer-events-none">
                                <button onclick="document.getElementById('salary-filter-month').showPicker()"
                                    class="px-4 py-2 border border-slate-200 rounded-lg text-xs font-bold text-slate-600 hover:bg-slate-50 flex items-center gap-2">
                                    <svg class="w-4 h-4 text-[#FF6B00]" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 00-2 2z" />
                                    </svg>
                                    <span id="salary-filter-label">Filter by Month</span>
                                </button>
                            </div>
                            <button id="clear-salary-filter" onclick="clearSalaryFilter()" style="display: none;"
                                class="h-6 w-6 flex items-center justify-center bg-rose-50 text-rose-500 rounded-md hover:bg-rose-100 transition-all"
                                title="Clear Filter">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                        <button onclick="exportSalaries()"
                            class="px-4 py-2 border border-slate-200 rounded-lg text-xs font-bold text-slate-600 hover:bg-slate-50 flex items-center gap-2">
                            <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 16v1a2 2 0 002 2h12a2 2 0 002-2v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                            </svg>
                            Export
                        </button>
                    </div>
                </div>

                <!-- Salary Table -->
                <div class="overflow-x-auto relative">
                    <div id="salary-loader"
                        class="absolute inset-0 bg-white/60 backdrop-blur-[2px] hidden flex items-center justify-center z-10">
                        <div class="h-10 w-10 border-4 border-slate-100 border-t-[#FF6B00] rounded-full animate-spin"></div>
                    </div>
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50/50">
                                <th class="px-4 py-3 text-[10px] font-bold text-slate-400 uppercase tracking-wider">Staff
                                    Name</th>
                                <th class="px-4 py-3 text-[10px] font-bold text-slate-400 uppercase tracking-wider">Employee
                                    ID</th>
                                <th class="px-4 py-3 text-[10px] font-bold text-slate-400 uppercase tracking-wider">Date
                                </th>
                                <th class="px-4 py-3 text-[10px] font-bold text-slate-400 uppercase tracking-wider">Payment
                                    Mode</th>
                                <th
                                    class="px-4 py-3 text-[10px] font-bold text-slate-400 uppercase tracking-wider text-center">
                                    Amount</th>
                                <th
                                    class="px-4 py-3 text-[10px] font-bold text-slate-400 uppercase tracking-wider text-center">
                                    Action</th>
                            </tr>
                        </thead>
                        <tbody id="salary-table-body" class="divide-y divide-slate-100">
                            <!-- Dynamic Content -->
                        </tbody>
                    </table>
                </div>

                <!-- Footer/Pagination -->
                <div id="salary-pagination-container"
                    class="p-4 border-t border-slate-50 bg-slate-50/30 flex items-center justify-between">
                    <!-- Pagination -->
                </div>
            </div>
        </div>
    </div>



    <!-- Add/Edit Staff Modal -->
    <div id="add-staff-modal" class="fixed inset-0 z-[100] hidden overflow-y-auto">
        <div class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm transition-opacity" onclick="closeAddModal()"></div>
        <div class="relative min-h-screen flex items-center justify-center p-4">
            <div
                class="relative w-full max-w-[600px] bg-white rounded-[1.5rem] shadow-2xl border border-slate-100 overflow-hidden animate-in zoom-in-95 duration-300 max-h-[95vh] flex flex-col">
            <!-- Modal Header -->
            <div class="px-5 py-3.5 flex items-center justify-between shrink-0">
                <h1 id="modal-title" class="text-base font-bold text-slate-800 tracking-tight">Add Staff Member</h1>
                <button onclick="closeAddModal()" class="text-slate-400 hover:text-slate-600 transition-all">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <div class="overflow-y-auto px-5 pb-5 custom-scrollbar">
                <form id="add-staff-form" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="staff_id" id="staff_id">

                    <!-- Profile Image -->
                    <div class="mb-3">
                        <label class="block text-[11px] font-bold text-slate-800 mb-1.5">Profile Image</label>
                        <div class="flex items-center gap-3">
                            <div
                                class="relative h-14 w-14 rounded-xl border border-slate-200 bg-slate-50 flex items-center justify-center overflow-hidden group">
                                <img id="image-preview"
                                    src="https://ui-avatars.com/api/?name=Staff&background=F1F5F9&color=64748B&bold=true"
                                    class="h-full w-full object-cover">
                                <input type="file" name="profile_image" id="profile_image_input" accept="image/*"
                                    class="absolute inset-0 opacity-0 cursor-pointer z-10" onchange="previewImage(this)">
                                <div
                                    class="absolute inset-0 bg-black/20 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center pointer-events-none">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                </div>
                            </div>
                             <div>
                                <p class="text-[10px] text-slate-400 max-w-[200px]">Upload a professional headshot. Max size 2MB.</p>
                                <span id="error-profile_image" class="text-[10px] text-rose-500 font-bold mt-1 block"></span>
                            </div>
                        </div>
                    </div>

                    <div class="space-y-3">
                        <div>
                            <label class="block text-[11px] font-bold text-slate-800 mb-1.5">Full Name</label>
                            <input type="text" name="full_name" id="field-name" required placeholder="e.g. Jonathan Smith"
                                class="w-full px-4 py-2.5 bg-white border border-slate-200 rounded-lg text-xs font-medium focus:border-brand-800 outline-none transition-all placeholder:text-slate-300">
                            <span id="error-full_name" class="text-[10px] text-rose-500 font-bold mt-1 block"></span>
                        </div>

                        <div>
                            <label class="block text-[11px] font-bold text-slate-800 mb-1.5">Department</label>
                            <div class="relative group" id="modal-dept-dropdown">
                                <button type="button" onclick="toggleModalSelect('dept')" id="modal-dept-btn"
                                    class="w-full px-4 py-2.5 bg-white border border-slate-200 rounded-lg text-xs font-medium text-slate-700 flex items-center justify-between focus:border-brand-800 focus:ring-4 focus:ring-brand-800/10 transition-all outline-none">
                                    <span id="modal-dept-label">Select Department</span>
                                    <svg class="w-4 h-4 text-slate-400 group-hover:text-brand-800 transition-colors"
                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                            d="M19 9l-7 7-7-7" />
                                    </svg>
                                </button>
                                <div id="modal-dept-menu"
                                    class="absolute z-[110] mt-1 w-full bg-white border border-slate-200 rounded-lg shadow-xl overflow-hidden hidden transform origin-top transition-all">
                                    <div class="py-1 max-h-48 overflow-y-auto custom-scrollbar">
                                        @foreach($departments as $dept)
                                            <button type="button"
                                                onclick="selectModalOption('dept', '{{ $dept->id }}', '{{ $dept->name }}')"
                                                class="w-full text-left px-4 py-2 text-xs font-medium text-slate-600 hover:bg-slate-50 hover:text-brand-800 transition-colors">
                                                {{ $dept->name }}
                                            </button>
                                        @endforeach
                                    </div>
                                </div>
                                <input type="hidden" name="staff_department_id" id="field-dept" required>
                            </div>
                            <span id="error-staff_department_id"
                                class="text-[10px] text-rose-500 font-bold mt-1 block"></span>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-[11px] font-bold text-slate-800 mb-1.5">Email Address</label>
                                <input type="email" name="email" id="field-email" required placeholder="j.smith@company.com"
                                    class="w-full px-4 py-2.5 bg-white border border-slate-200 rounded-lg text-xs font-medium focus:border-brand-800 outline-none transition-all placeholder:text-slate-300">
                                <span id="error-email" class="text-[10px] text-rose-500 font-bold mt-1 block"></span>
                            </div>
                            <div>
                                <label class="block text-[11px] font-bold text-slate-800 mb-1.5">Phone Number</label>
                                <input type="text" name="phone" id="field-phone" required placeholder="+1 (555) 000-0000"
                                    class="w-full px-4 py-2.5 bg-white border border-slate-200 rounded-lg text-xs font-medium focus:border-brand-800 outline-none transition-all placeholder:text-slate-300">
                                <span id="error-phone" class="text-[10px] text-rose-500 font-bold mt-1 block"></span>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-[11px] font-bold text-slate-800 mb-1.5">Employment Type</label>
                                <div class="flex p-1 bg-slate-100 rounded-lg w-full">
                                    <label class="flex-1 cursor-pointer">
                                        <input type="radio" name="employment_type" id="employment-salary" value="Salary"
                                            checked class="hidden peer">
                                        <div
                                            class="py-1.5 rounded-md text-[10px] font-bold text-slate-500 peer-checked:bg-white 
                                                                peer-checked:text-[#FF6B00]
                                                                peer-checked:text-brand-800 peer-checked:shadow-sm transition-all text-center">
                                            Salary</div>
                                    </label>
                                    <label class="flex-1 cursor-pointer">
                                        <input type="radio" name="employment_type" id="employment-hourly" value="Hourly"
                                            class="hidden peer">
                                        <div
                                            class="py-1.5 rounded-md text-[10px] font-bold text-slate-500 peer-checked:bg-white 
                                                                peer-checked:text-[#FF6B00]
                                                                            peer-checked:shadow-sm transition-all text-center">
                                            Hourly</div>
                                    </label>
                                </div>
                            </div>

                            <div>
                                <label class="block text-[11px] font-bold text-slate-800 mb-1.5">Monthly Salary</label>
                                <div class="relative">
                                    <span
                                        class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 font-bold text-xs">₹</span>
                                    <input type="number" name="base_salary" id="field-salary" required placeholder="25,000"
                                        class="w-full pl-8 pr-4 py-2.5 bg-white border border-slate-200 rounded-lg text-xs font-medium focus:border-brand-800 outline-none transition-all">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Modal Footer -->
                    <div class="flex items-center justify-end gap-3 mt-5">
                        <button type="button" onclick="closeAddModal()"
                            class="text-xs font-bold text-slate-500 hover:text-slate-800 transition-colors">Cancel</button>
                        <button type="submit" id="submit-btn"
                            class="px-6 py-2 bg-[#FF6B00] text-white rounded-lg text-xs font-bold shadow-lg shadow-amber-900/20 hover:translate-y-[-1px] active:scale-95 transition-all">Save
                            Staff Member</button>
                    </div>
                </form>
            </div>
        </div>
        </div>
    </div>

    <!-- Log Attendance Modal -->
    <div id="log-attendance-modal" class="fixed inset-0 z-[100] hidden overflow-y-auto">
        <div class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm transition-opacity" onclick="closeAttendanceModal()"></div>
        <div class="relative min-h-screen flex items-center justify-center p-4">
            <div
                class="relative w-full max-w-[380px] bg-white rounded-[1.5rem] shadow-2xl border border-slate-100 overflow-hidden animate-in zoom-in-95 duration-300 flex flex-col">
            <!-- Modal Header -->
            <div class="px-5 pt-3 pb-1 flex items-center justify-between border-b border-slate-50">
                <h1 class="text-base font-bold text-slate-800 tracking-tight">Log Attendance</h1>
                <button onclick="closeAttendanceModal()" class="text-slate-400 hover:text-slate-600 transition-all">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <div class="px-5 pb-5">
                <form id="log-attendance-form" class="space-y-4">
                    @csrf
                    <input type="hidden" name="id" id="attendance-id-input">
                    <!-- Select Staff Member -->
                    <div class="relative" id="attendance-staff-dropdown-container">
                        <label class="block text-[9px] font-bold text-slate-400 uppercase tracking-wider mb-1.5">Select
                            Staff Member</label>
                        <input type="hidden" name="staff_id" id="attendance-staff-select">
                        <div id="attendance-staff-trigger" onclick="toggleStaffDropdown()"
                            class="w-full px-4 py-2.5 bg-white border border-slate-200 rounded-xl text-xs font-medium focus:border-[#FF6B00] outline-none transition-all cursor-pointer text-slate-700 flex items-center justify-between group">
                            <span id="attendance-staff-label" class="text-slate-400">Choose a staff member...</span>
                            <svg class="w-4 h-4 text-slate-300 group-hover:text-[#FF6B00] transition-colors" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </div>

                        <!-- Dropdown Menu -->
                        <div id="attendance-staff-menu"
                            class="absolute left-0 right-0 mt-1 bg-white border border-slate-100 rounded-xl shadow-xl z-[110] hidden overflow-hidden animate-in fade-in slide-in-from-top-2 duration-200">
                            <div class="p-2 border-b border-slate-50">
                                <input type="text" id="attendance-staff-search" placeholder="Search staff..."
                                    oninput="filterStaffOptions()"
                                    class="w-full px-3 py-1.5 bg-slate-50 border-none rounded-lg text-xs outline-none focus:ring-0 placeholder:text-slate-300">
                            </div>
                            <div id="attendance-staff-options" class="max-h-[180px] overflow-y-auto py-1 custom-scrollbar">
                                <!-- Options will be rendered here -->
                                <div class="px-4 py-2 text-xs text-slate-400">Loading staff...</div>
                            </div>
                        </div>
                        <span id="error-attendance-staff" class="text-[10px] text-rose-500 font-bold mt-1 block"></span>
                    </div>

                    <!-- Select Date -->
                    <div>
                        <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-2">Select
                            Date</label>
                        <div class="relative">
                            <input type="date" name="date" id="attendance-date" required value="{{ date('Y-m-d') }}"
                                max="{{ date('Y-m-d') }}"
                                class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl text-xs font-medium focus:border-[#FF6B00] outline-none transition-all text-slate-700">
                            <span id="error-attendance-date" class="text-[10px] text-rose-500 font-bold mt-1 block"></span>
                        </div>
                    </div>

                    <!-- Attendance Status -->
                    <div>
                        <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-2">Attendance
                            Status</label>
                        <div class="flex gap-3">
                            <input type="hidden" name="status" id="attendance-status-input" value="Present">
                            <button type="button" onclick="setAttendanceStatus('Present')" id="status-present-btn"
                                class="flex-1 py-3 px-4 rounded-xl border-2 border-[#FF6B00] bg-orange-50 text-[#FF6B00] text-xs font-bold flex items-center justify-center gap-2 transition-all">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7" />
                                </svg>
                                Present
                            </button>
                            <button type="button" onclick="setAttendanceStatus('Absent')" id="status-absent-btn"
                                class="flex-1 py-3 px-4 rounded-xl border-2 border-slate-100 text-slate-400 text-xs font-bold flex items-center justify-center gap-2 transition-all hover:bg-slate-50">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12" />
                                </svg>
                                Absent
                            </button>
                        </div>
                    </div>

                    <!-- Additional Notes -->
                    <div>
                        <label class="block text-[9px] font-bold text-slate-400 uppercase tracking-wider mb-1.5">Additional
                            Notes</label>
                        <textarea name="note" id="attendance-note" placeholder="Any comments or reasons for absence..."
                            class="w-full px-4 py-2.5 bg-white border border-slate-200 rounded-xl text-xs font-medium focus:border-[#FF6B00] outline-none transition-all placeholder:text-slate-300 min-h-[80px] resize-none text-slate-700"></textarea>
                    </div>

                    <!-- Log Button -->
                    <div class="pt-1">
                        <button type="submit" id="log-attendance-btn"
                            class="w-full py-3.5 bg-[#FF6B00] text-white rounded-xl text-xs font-bold shadow-lg shadow-amber-900/20 hover:translate-y-[-1px] active:scale-95 transition-all flex items-center justify-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                    d="M5 13l4 4L19 7m-14 0l4 4L19 7" />
                            </svg>
                            Log Attendance
                        </button>
                        <button type="button" onclick="closeAttendanceModal()"
                            class="w-full mt-3 text-[10px] font-bold text-slate-400 hover:text-slate-600 transition-colors text-center">
                            Cancel and close
                        </button>
                    </div>
                </form>
            </div>
        </div>
        </div>
    </div>

    <!-- Add/Edit Salary Modal -->
    <div id="salary-modal" class="fixed inset-0 z-[120] hidden overflow-y-auto">
        <div class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm transition-opacity" onclick="closeSalaryModal()"></div>
        <div class="relative min-h-screen flex items-center justify-center p-4">
            <div class="bg-white rounded-2xl shadow-2xl w-full max-w-4xl overflow-hidden flex flex-col md:flex-row transition-all scale-95 opacity-0 duration-300"
                id="salary-modal-content">

                <!-- Left Section: Form -->
                <div class="flex-1 p-5 border-b md:border-b-0 md:border-r border-slate-100">
                    <div class="flex items-center justify-between mb-6">
                        <div>
                            <h3 class="text-xl font-bold text-slate-800" id="salary-modal-title">Add Salary</h3>
                            <p class="text-slate-400 text-xs mt-1">Record a new salary payment for your staff.</p>
                        </div>
                        <button onclick="closeSalaryModal()"
                            class="text-slate-400 hover:text-slate-600 transition-all p-2 hover:bg-slate-50 rounded-lg md:hidden">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    <form id="salary-form" class="space-y-4">
                        @csrf
                        <input type="hidden" name="salary_id" id="salary_id_input">

                        <!-- Staff Member -->
                        <div class="space-y-1.5">
                            <label class="block text-[11px] font-bold text-slate-800 uppercase tracking-wider">Select Staff
                                Member</label>
                            <div class="relative" id="salary-staff-dropdown-container">
                                <button type="button" onclick="toggleSalaryStaffDropdown()" id="salary-staff-selector"
                                    class="w-full flex items-center justify-between px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium text-slate-700 hover:border-[#FF6B00] transition-all">
                                    <span id="selected-salary-staff-name" class="text-slate-400">Choose a staff
                                        member...</span>
                                    <svg class="w-4 h-4 text-slate-400 transition-transform duration-200"
                                        id="salary-staff-chevron" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 9l-7 7-7-7" />
                                    </svg>
                                </button>
                                <input type="hidden" name="staff_id" id="salary_staff_id_input">

                                <div id="salary-staff-dropdown"
                                    class="absolute z-20 w-full mt-2 bg-white border border-slate-100 rounded-xl shadow-xl overflow-hidden hidden transform origin-top transition-all">
                                    <div class="p-2 border-b border-slate-50">
                                        <input type="text" id="salary-staff-search" placeholder="Search staff..."
                                            oninput="filterSalaryStaffOptions(this.value)"
                                            class="w-full px-3 py-2 bg-slate-50 border-none rounded-lg text-xs outline-none focus:ring-1 focus:ring-[#FF6B00]/20">
                                    </div>
                                    <div class="max-h-48 overflow-y-auto custom-scrollbar" id="salary-staff-options">
                                        <!-- Options injected via JS -->
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="space-y-1.5">
                                <label class="block text-[11px] font-bold text-slate-800 uppercase tracking-wider">Payment
                                    Date</label>
                                <input type="date" name="payment_date" id="salary_payment_date" max="{{ date('Y-m-d') }}"
                                    onchange="updateSalaryPreview()"
                                    class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium outline-none focus:border-[#FF6B00] transition-all">
                            </div>
                            <div class="space-y-1.5">
                                <label class="block text-[11px] font-bold text-slate-800 uppercase tracking-wider">Salary
                                    Amount</label>
                                <div class="relative">
                                    <span class="absolute left-4 top-2.5 text-slate-400 text-xs">₹</span>
                                    <input type="number" name="base_salary" id="salary_base_amount"
                                        oninput="updateSalaryPreview()" placeholder="0.00"
                                        class="w-full pl-8 pr-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-bold outline-none focus:border-[#FF6B00] transition-all">
                                </div>
                            </div>
                        </div>

                        <div class="space-y-1.5">
                            <label class="block text-[11px] font-bold text-slate-800 uppercase tracking-wider">Payment
                                Method</label>
                            <div class="flex p-1 bg-slate-100 rounded-xl gap-1">
                                <button type="button" onclick="setSalaryMethod('Cash')" id="salary-method-cash"
                                    class="flex-1 py-2 rounded-lg text-[10px] font-bold transition-all flex items-center justify-center gap-2 bg-white text-[#FF6B00] shadow-sm">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                                    </svg>
                                    Cash
                                </button>
                                <button type="button" onclick="setSalaryMethod('Online')" id="salary-method-online"
                                    class="flex-1 py-2 rounded-lg text-[10px] font-bold transition-all flex items-center justify-center gap-2 text-slate-500 hover:text-[#FF6B00]">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                                    </svg>
                                    Online
                                </button>
                                <input type="hidden" name="payment_method" id="salary_payment_method_input" value="Cash">
                                <input type="hidden" name="status" id="salary_status_input" value="Paid">
                            </div>
                        </div>

                        <div class="space-y-1.5">
                            <label class="block text-[11px] font-bold text-slate-800 uppercase tracking-wider">Notes</label>
                            <textarea name="notes" id="salary_notes" rows="3"
                                placeholder="Add any special remarks or bonus details..."
                                class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium outline-none focus:border-[#FF6B00] transition-all resize-none"></textarea>
                        </div>
                    </form>
                </div>

                <!-- Right Section: Summary -->
                <div class="w-full md:w-[320px] bg-slate-50 p-5 flex flex-col">
                    <div class="flex items-center justify-between mb-5">
                        <h4 class="text-sm font-bold text-slate-800 uppercase tracking-wider">Review Summary</h4>
                        <button onclick="closeSalaryModal()"
                            class="text-slate-400 hover:text-slate-600 transition-all hidden md:block">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    <div class="bg-white rounded-2xl p-4 shadow-sm border border-slate-100 mb-4">
                        <div class="space-y-3 mb-4 border-b border-slate-100 pb-4">
                            <div class="flex justify-between text-[11px]">
                                <span class="text-slate-400 font-medium">Base Salary</span>
                                <span class="text-slate-800 font-bold" id="preview-base-salary">$0.00</span>
                            </div>
                            <div class="flex justify-between text-[11px]">
                                <span class="text-slate-400 font-medium">Deductions</span>
                                <span class="text-rose-500 font-bold" id="preview-deductions">-$0.00</span>
                            </div>
                        </div>
                        <div class="space-y-1">
                            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Total
                                Disbursement</span>
                            <div class="text-3xl font-black text-[#FF6B00]" id="preview-total-disbursement">$0.00</div>
                        </div>
                    </div>

                    <div class="bg-emerald-50 rounded-xl p-3 border border-emerald-100 flex gap-3 mb-auto">
                        <div class="shrink-0 text-emerald-500">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                                    clip-rule="evenodd" />
                            </svg>
                        </div>
                        <p class="text-[10px] text-emerald-800 font-medium leading-relaxed">
                            This record will be synced with the <strong>Internal Audit</strong> logs immediately upon
                            saving.
                        </p>
                    </div>

                    <div class="mt-6 space-y-2">
                        <button onclick="saveSalaryRecord()" id="save-salary-btn"
                            class="w-full py-3.5 bg-[#FF6B00] text-white rounded-xl text-xs font-bold shadow-lg shadow-amber-900/20 hover:translate-y-[-1px] active:scale-95 transition-all flex items-center justify-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4" />
                            </svg>
                            Save Salary Record
                        </button>
                        <button onclick="closeSalaryModal()"
                            class="w-full py-3 text-slate-400 hover:text-slate-600 text-xs font-bold transition-all">
                            Cancel & Exit
                        </button>
                    </div>
                </div>
            </div>
        </div>
    <!-- Empty State Templates -->
    <template id="staff-empty-state">
        <x-empty-state title="No staff members found" subtitle="Try adjusting your filters or add a new staff member." icon="staff" />
    </template>
    
    <template id="attendance-empty-state">
        <x-empty-state title="No attendance records found" subtitle="Log attendance to see entries here." icon="calendar" plain="true" />
    </template>

    <template id="salary-empty-state">
        <x-empty-state title="No salary records found" subtitle="Record salary payments to track history here." icon="salary" plain="true" />
    </template>

    <script>
        const API_URL = "{{ url('api/v1/institute/staff') }}";
        const CSRF_TOKEN = "{{ csrf_token() }}";
        const PRIMARY_COLOR = '#FF6B00';
        const PRIMARY_HOVER = '#e66000';

        let staffListData = [];
        let departmentsListData = [];

        async function fetchDepartments() {
            try {
                const response = await fetch("{{ url('api/v1/institute/staff-departments') }}", {
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': CSRF_TOKEN
                    }
                });
                const result = await response.json();
                if (Array.isArray(result)) {
                    departmentsListData = result;
                    renderDepartmentsDropdowns(result);
                }
            } catch (error) {
                console.error('Error fetching departments:', error);
            }
        }

        function renderDepartmentsDropdowns(departments) {
            // 1. Populate the filter dropdown:
            const filterMenu = document.querySelector('#dept-dropdown-menu .py-1');
            if (filterMenu) {
                let html = `<button type="button" onclick="selectCustomOption('dept', '', 'All Departments')"
                    class="w-full text-left px-4 py-2.5 text-[10px] font-bold text-slate-600 hover:bg-slate-50 hover:text-[#FF6B00] transition-colors border-b border-slate-50 last:border-0">All Departments</button>`;
                
                departments.forEach(dept => {
                    html += `<button type="button"
                        onclick="selectCustomOption('dept', '${dept.id}', '${dept.name.replace(/'/g, "\\'")}')"
                        class="w-full text-left px-4 py-2.5 text-[10px] font-bold text-slate-600 hover:bg-slate-50 hover:text-[#FF6B00] transition-colors border-b border-slate-50 last:border-0">${dept.name}</button>`;
                });
                filterMenu.innerHTML = html;
            }

            // 2. Populate the modal select dropdown:
            const modalMenu = document.querySelector('#modal-dept-menu .py-1');
            if (modalMenu) {
                let html = '';
                departments.forEach(dept => {
                    html += `<button type="button"
                        onclick="selectModalOption('dept', '${dept.id}', '${dept.name.replace(/'/g, "\\'")}')"
                        class="w-full text-left px-4 py-2 text-xs font-medium text-slate-600 hover:bg-slate-50 hover:text-brand-800 transition-colors">
                        ${dept.name}
                    </button>`;
                });
                modalMenu.innerHTML = html;
            }
        }

        window.toggleStaffDropdown = () => {
            const menu = document.getElementById('attendance-staff-menu');
            if (menu) menu.classList.toggle('hidden');
        };

        window.switchTab = (tab) => {
            const staffView = document.getElementById('staff-view');
            const attendanceView = document.getElementById('attendance-view');
            const salaryView = document.getElementById('salary-view'); // Assuming this exists or will exist
            const staffBtn = document.getElementById('tab-staff');
            const attendanceBtn = document.getElementById('tab-attendance');
            const salaryBtn = document.getElementById('tab-salary');

            // Reset All
            staffView.classList.add('hidden');
            attendanceView.classList.add('hidden');
            if (salaryView) salaryView.classList.add('hidden');

            [staffBtn, attendanceBtn, salaryBtn].forEach(btn => {
                if (btn) {
                    btn.classList.remove('bg-[#FF6B00]', 'text-white', 'shadow-lg', 'shadow-orange-900/10');
                    btn.classList.add('text-slate-500');
                }
            });

            if (tab === 'staff') {
                staffView.classList.remove('hidden');
                staffBtn.classList.add('bg-[#FF6B00]', 'text-white', 'shadow-lg', 'shadow-orange-900/10');
                staffBtn.classList.remove('text-slate-500');
                fetchStaff();
            } else if (tab === 'attendance') {
                attendanceView.classList.remove('hidden');
                attendanceBtn.classList.add('bg-[#FF6B00]', 'text-white', 'shadow-lg', 'shadow-orange-900/10');
                attendanceBtn.classList.remove('text-slate-500');
                fetchAttendance();
            } else if (tab === 'salary') {
                if (salaryView) salaryView.classList.remove('hidden');
                salaryBtn.classList.add('bg-[#FF6B00]', 'text-white', 'shadow-lg', 'shadow-orange-900/10');
                salaryBtn.classList.remove('text-slate-500');
                fetchSalaries();
            }
        };

        document.addEventListener('DOMContentLoaded', () => {
            fetchStaff();
            fetchDepartments();

            const searchInput = document.getElementById('staff-search');
            const searchBtn = document.getElementById('search-btn');
            const roleFilter = document.getElementById('role-filter');

            searchInput.addEventListener('keypress', (e) => {
                if (e.key === 'Enter') fetchStaff();
            });

            const salarySearchInput = document.getElementById('salary-search-input');
            if (salarySearchInput) {
                salarySearchInput.addEventListener('keypress', (e) => {
                    if (e.key === 'Enter') fetchSalaries();
                });
            }

            // Close custom dropdowns when clicking outside
            document.addEventListener('click', (e) => {
                if (!e.target.closest('#role-dropdown-container')) {
                    document.getElementById('role-dropdown-menu')?.classList.add('hidden');
                    document.getElementById('role-chevron')?.classList.remove('rotate-180');
                }
                if (!e.target.closest('#dept-dropdown-container')) {
                    document.getElementById('dept-dropdown-menu')?.classList.add('hidden');
                    document.getElementById('dept-chevron')?.classList.remove('rotate-180');
                }
            });
        });

        // Custom Dropdown Logic
        window.toggleCustomDropdown = (type) => {
            const menu = document.getElementById(`${type}-dropdown-menu`);
            const chevron = document.getElementById(`${type}-chevron`);
            const isHidden = menu.classList.contains('hidden');

            // Close other dropdowns first
            document.getElementById('role-dropdown-menu')?.classList.add('hidden');
            document.getElementById('dept-dropdown-menu')?.classList.add('hidden');
            document.getElementById('role-chevron')?.classList.remove('rotate-180');
            document.getElementById('dept-chevron')?.classList.remove('rotate-180');

            if (isHidden) {
                menu.classList.remove('hidden');
                chevron.classList.add('rotate-180');
            }
        };

        window.selectCustomOption = (type, value, label) => {
            document.getElementById(`${type}-filter`).value = value;
            document.getElementById(`selected-${type}-label`).textContent = label;
            document.getElementById(`${type}-dropdown-menu`).classList.add('hidden');
            document.getElementById(`${type}-chevron`).classList.remove('rotate-180');
            fetchStaff(1);
        };

        async function fetchStaff(page = 1) {
            const grid = document.getElementById('staff-grid');
            const loader = document.getElementById('loading-spinner');
            const searchInput = document.getElementById('staff-search');
            const roleFilter = document.getElementById('role-filter');

            if (!grid || !loader) return;

            const search = searchInput ? searchInput.value : '';
            const roleId = document.getElementById('role-filter')?.value || '';
            const deptId = document.getElementById('dept-filter')?.value || '';

            loader.classList.remove('hidden');

            try {
                const params = new URLSearchParams({
                    page: page,
                    search: search,
                    role_id: roleId,
                    department_id: deptId,
                    per_page: 12,
                    _t: new Date().getTime()
                });

                const response = await fetch(`${API_URL}?${params}`, {
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': CSRF_TOKEN
                    }
                });

                const result = await response.json();

                if (result.status === 'success') {
                    staffListData = result.data.items || [];
                    renderStaff(staffListData);
                    renderPagination(result.data);
                } else {
                    console.error('API Error:', result.message);
                }
            } catch (error) {
                console.error('Fetch Error:', error);
            } finally {
                loader.classList.add('hidden');
            }
        }

        function renderStaff(staffMembers) {
            const grid = document.getElementById('staff-grid');
            if (!grid) return;

            if (staffMembers.length === 0) {
                grid.innerHTML = document.getElementById('staff-empty-state').innerHTML;
                return;
            }

            const cardsHtml = staffMembers.map(staff => {
                const profileImg = staff.profile_url ? staff.profile_url : `https://ui-avatars.com/api/?name=${encodeURIComponent(staff.full_name)}&background=F1F5F9&color=64748B&bold=true`;
                const statusColor = staff.status === 'active' || staff.status === 1 ? 'bg-emerald-500' : 'bg-slate-300';

                return `
                                                    <div class="bg-white rounded-[1rem] border border-slate-200/60 p-4 flex flex-col items-center text-center group hover:shadow-xl hover:shadow-slate-200/30 transition-all duration-300 animate-in fade-in slide-in-from-bottom-2 h-fit relative">
                                                        <!-- Floating Delete Button -->
                                                        <button onclick="deleteStaff(${staff.id}, '${staff.full_name.replace(/'/g, "\\'")}')"
                                                            class="absolute top-3 right-3 p-1.5 bg-rose-50 text-rose-500 rounded-full hover:bg-rose-500 hover:text-white transition-all shadow-sm">
                                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                            </svg>
                                                        </button>

                                                        <div class="relative mb-3">
                                                            <div class="h-24 w-24 rounded-full border-2 border-slate-100 overflow-hidden p-1 bg-white">
                                                                <img src="${profileImg}" alt="${staff.full_name}" class="h-full w-full object-cover rounded-full">
                                                            </div>
                                                            <div class="absolute bottom-1 right-1 h-3.5 w-3.5 ${statusColor} border-2 border-white rounded-full"></div>
                                                        </div>

                                                        <h3 class="text-sm font-bold text-slate-800 leading-tight mb-0.5">${staff.full_name}</h3>
                                                        <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mb-4">${staff.role ? staff.role.name : 'Staff Member'}</p>

                                                        <div class="flex items-center gap-2 w-full">
                                                            <a href="{{ url('institute/staff') }}/${staff.id}" class="flex-1 py-1.5 bg-slate-50 text-slate-600 rounded-xl text-[10px] font-bold hover:bg-slate-100 transition-all text-center">Profile</a>
                                                            <button onclick="openEditModalById(${staff.id})" id="edit-btn-${staff.id}"
                                                                class="flex-1 py-1.5 bg-slate-50 text-slate-600 rounded-xl text-[10px] font-bold hover:bg-slate-100 transition-all">Edit</button>
                                                        </div>
                                                    </div>
                                                `;
            }).join('');

            grid.innerHTML = cardsHtml;
        }

        function renderPagination(pagination) {
            const container = document.getElementById('pagination-container');
            if (!pagination || pagination.last_page <= 1) {
                container.innerHTML = '';
                return;
            }

            const from = (pagination.current_page - 1) * pagination.per_page + 1;
            const to = Math.min(pagination.current_page * pagination.per_page, pagination.total);

            let html = `
                                                    <p class="text-xs font-medium text-slate-400">
                                                        Showing ${from} to ${to} of ${pagination.total} staff members
                                                    </p>
                                                    <div class="flex items-center gap-2">
                                                `;

            // Previous Button
            html += `
                                                    <button onclick="fetchStaff(${pagination.current_page - 1})" ${pagination.current_page === 1 ? 'disabled' : ''} 
                                                        class="px-4 py-2 bg-white border border-slate-100 text-slate-600 rounded-lg text-xs font-bold hover:bg-slate-50 transition-all disabled:opacity-50 disabled:cursor-not-allowed">
                                                        Previous
                                                    </button>
                                                `;

            // Page Numbers (Simplified)
            for (let i = 1; i <= pagination.last_page; i++) {
                if (i === pagination.current_page) {
                    html += `<span class="h-8 w-8 flex items-center justify-center rounded-lg bg-[#FF6B00] text-white text-xs font-bold shadow-md shadow-orange-900/10">${i}</span>`;
                } else if (i <= 3 || i > pagination.last_page - 1 || (i >= pagination.current_page - 1 && i <= pagination.current_page + 1)) {
                    html += `<button onclick="fetchStaff(${i})" class="h-8 w-8 flex items-center justify-center rounded-lg bg-white border border-slate-100 text-slate-600 text-xs font-bold hover:bg-slate-50 transition-all">${i}</button>`;
                } else if (i === 4 || i === pagination.last_page - 1) {
                    html += `<span class="text-slate-300">...</span>`;
                }
            }

            // Next Button
            html += `
                                                    <button onclick="fetchStaff(${pagination.current_page + 1})" ${pagination.current_page === pagination.last_page ? 'disabled' : ''} 
                                                        class="px-4 py-2 bg-white border border-slate-100 text-slate-600 rounded-lg text-xs font-bold hover:bg-slate-50 transition-all disabled:opacity-50 disabled:cursor-not-allowed">
                                                        Next
                                                    </button>
                                                `;

            html += `</div>`;
            container.innerHTML = html;
        }

        // Modal Functions
        window.previewImage = (input) => {
            const errorEl = document.getElementById('error-profile_image');
            if (errorEl) errorEl.innerText = '';

            if (input.files && input.files[0]) {
                const file = input.files[0];
                if (file.size > 2 * 1024 * 1024) { // 2MB
                    if (errorEl) {
                        errorEl.innerText = 'The profile image must not be greater than 2MB.';
                    }
                    input.value = ''; // Reset file input
                    document.getElementById('image-preview').src = "https://ui-avatars.com/api/?name=Staff&background=F1F5F9&color=64748B&bold=true";
                    return;
                }

                const reader = new FileReader();
                reader.onload = (e) => document.getElementById('image-preview').src = e.target.result;
                reader.readAsDataURL(file);
            }
        };

        window.toggleModalSelect = (type) => {
            const menu = document.getElementById(`modal-${type}-menu`);
            const isHidden = menu.classList.contains('hidden');

            // Close all other modal selects first
            ['role', 'dept'].forEach(t => {
                const m = document.getElementById(`modal-${t}-menu`);
                if (m) m.classList.add('hidden');
            });

            if (isHidden) {
                menu.classList.remove('hidden');
            }
        };

        window.selectModalOption = (type, value, label) => {
            document.getElementById(`field-${type}`).value = value;
            document.getElementById(`modal-${type}-label`).innerText = label;
            document.getElementById(`modal-${type}-menu`).classList.add('hidden');
        };

        window.openAddModal = () => {
            document.getElementById('modal-title').innerText = 'Add Staff Member';
            document.getElementById('add-staff-form').reset();
            document.getElementById('staff_id').value = '';
            document.getElementById('image-preview').src = 'https://ui-avatars.com/api/?name=Staff&background=F1F5F9&color=64748B&bold=true';

            // Reset custom selects
            document.getElementById('field-dept').value = '';
            document.getElementById('modal-dept-label').innerText = 'Select Department';
            document.getElementById('modal-dept-menu')?.classList.add('hidden');

            document.getElementById('add-staff-modal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        };

        window.openEditModalById = (id) => {
            const staff = staffListData.find(s => s.id == id);
            if (staff) window.openEditModal(staff);
        };

        window.openEditModal = (staff) => {
            document.getElementById('modal-title').innerText = 'Edit Staff Member';
            document.getElementById('add-staff-form').reset();

            document.getElementById('staff_id').value = staff.id;
            document.getElementById('field-name').value = staff.full_name;
            document.getElementById('field-email').value = staff.email;
            document.getElementById('field-phone').value = staff.phone || '';

            // Set custom select values
            document.getElementById('field-dept').value = staff.staff_department_id;
            document.getElementById('modal-dept-label').innerText = staff.department ? staff.department.name : 'Select Department';
            document.getElementById('modal-dept-menu')?.classList.add('hidden');

            if (staff.employment_type === 'Hourly') {
                document.getElementById('employment-hourly').checked = true;
            } else {
                document.getElementById('employment-salary').checked = true;
            }

            document.getElementById('field-salary').value = staff.base_salary;

            const profileImg = staff.profile_url ? staff.profile_url : `https://ui-avatars.com/api/?name=${encodeURIComponent(staff.full_name)}&background=F1F5F9&color=64748B&bold=true`;
            document.getElementById('image-preview').src = profileImg;

            document.getElementById('add-staff-modal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        };

        window.closeAddModal = () => {
            document.getElementById('modal-dept-menu')?.classList.add('hidden');
            document.getElementById('add-staff-modal').classList.add('hidden');
            document.body.style.overflow = 'auto';
        };

        // Form Submission
        const addForm = document.getElementById('add-staff-form');
        addForm.addEventListener('submit', async (e) => {
            e.preventDefault();

            // Clear previous errors
            document.querySelectorAll('[id^="error-"]').forEach(el => el.innerText = '');

            const formData = new FormData(addForm);
            const staffId = document.getElementById('staff_id').value;
            const submitBtn = document.getElementById('submit-btn');
            const originalText = submitBtn.innerText;

            submitBtn.disabled = true;
            submitBtn.innerText = 'Saving...';

            try {
                // If editing, use PUT spoofing
                if (staffId) {
                    formData.append('_method', 'PUT');
                }

                const url = staffId ? `${API_URL}/${staffId}` : API_URL;
                const response = await fetch(url, {
                    method: 'POST', // Use POST for both (PUT is spoofed)
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': CSRF_TOKEN
                    }
                });

                const result = await response.json();

                if (response.status === 422) {
                    if (result.errors) {
                        Object.keys(result.errors).forEach(key => {
                            const errorEl = document.getElementById(`error-${key}`);
                            if (errorEl) {
                                let errorMsg = result.errors[key][0];
                                // Clean messages
                                errorMsg = errorMsg.replace('staff role id', 'staff role')
                                    .replace('staff department id', 'staff department');
                                errorEl.innerText = errorMsg;
                            }
                        });
                    }
                    return;
                }

                if (result.status === 'success') {
                    closeAddModal();
                    fetchStaff();
                } else {
                    showToast(result.message || 'Something went wrong', 'error');
                }
            } catch (error) {
                console.error('Error:', error);
                showToast('Something went wrong. Please try again.', 'error');
            } finally {
                submitBtn.disabled = false;
                submitBtn.innerText = originalText;
            }
        });

        window.deleteStaff = (id, name) => {
            showConfirmModal(
                'Delete Staff member',
                `<p class="text-xs text-slate-600 mb-4 leading-relaxed">
                    Are you sure you want to delete the staff member "<span class="font-bold text-slate-900">${name}</span>"?
                </p>
                <div class="bg-slate-50 rounded-xl p-3 mb-4 border border-slate-100 text-left">
                    <p class="text-[9px] font-bold text-slate-500 mb-1.5">Data to be permanently lost:</p>
                    <ul class="space-y-1">
                        <li class="flex items-center gap-2 text-[10px] text-slate-600 font-medium">
                            <svg class="w-2.5 h-2.5 text-[#FF6B00]/60" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                            Attendance records and history
                        </li>
                        <li class="flex items-center gap-2 text-[10px] text-slate-600 font-medium">
                            <svg class="w-2.5 h-2.5 text-[#FF6B00]/60" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                            Salary and payment documentation
                        </li>
                        <li class="flex items-center gap-2 text-[10px] text-slate-600 font-medium">
                            <svg class="w-2.5 h-2.5 text-rose-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                            Profile data and portal access
                        </li>
                    </ul>
                </div>`,
                async () => {
                    try {
                        const response = await fetch(`${API_URL}/${id}`, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': CSRF_TOKEN,
                                'X-Requested-With': 'XMLHttpRequest',
                                'Accept': 'application/json'
                            }
                        });

                        if (response.ok) {
                            fetchStaff();
                            showToast('Staff member deleted successfully');
                        } else {
                            const result = await response.json();
                            showToast(result.message || 'Error deleting staff member', 'error');
                        }
                    } catch (error) {
                        console.error('Error:', error);
                        showToast('Error deleting staff member', 'error');
                    }
                },
                'Yes, Delete Staff',
                'bg-[#FF6B00] text-white rounded-lg text-[10px] font-bold shadow-lg hover:opacity-90 active:scale-95 transition-all'
            );
        };


        // Attendance Modal Functions
        window.openAttendanceModal = async () => {
            document.getElementById('log-attendance-modal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';

            // Reset form
            document.getElementById('log-attendance-form').reset();
            document.getElementById('attendance-id-input').value = '';
            document.getElementById('attendance-staff-select').value = '';
            document.getElementById('attendance-staff-label').innerText = 'Choose a staff member...';
            document.getElementById('attendance-staff-label').classList.add('text-slate-400');
            document.getElementById('attendance-staff-label').classList.remove('text-slate-700');
            document.getElementById('attendance-date').value = new Date().toISOString().split('T')[0];
            setAttendanceStatus('Present');

            // Fetch staff list for the dropdown
            fetchStaffForAttendance();
        };

        window.closeAttendanceModal = () => {
            document.getElementById('log-attendance-modal').classList.add('hidden');
            document.body.style.overflow = 'auto';
        };

        window.setAttendanceStatus = (status) => {
            document.getElementById('attendance-status-input').value = status;
            const presentBtn = document.getElementById('status-present-btn');
            const absentBtn = document.getElementById('status-absent-btn');

            if (status === 'Present') {
                presentBtn.classList.add('border-[#FF6B00]', 'bg-orange-50', 'text-[#FF6B00]');
                presentBtn.classList.remove('border-slate-100', 'text-slate-400');
                absentBtn.classList.remove('border-[#FF6B00]', 'bg-orange-50', 'text-[#FF6B00]');
                absentBtn.classList.add('border-slate-100', 'text-slate-400');
            } else {
                absentBtn.classList.add('border-[#FF6B00]', 'bg-orange-50', 'text-[#FF6B00]');
                absentBtn.classList.remove('border-slate-100', 'text-slate-400');
                presentBtn.classList.remove('border-[#FF6B00]', 'bg-orange-50', 'text-[#FF6B00]');
                presentBtn.classList.add('border-slate-100', 'text-slate-400');
            }
        };

        const attendanceForm = document.getElementById('log-attendance-form');
        async function fetchAttendance(page = 1) {
            const tbody = document.getElementById('attendance-table-body');
            const loader = document.getElementById('attendance-loader');
            if (!tbody || !loader) return;

            const search = document.getElementById('attendance-search-input').value;
            const date = document.getElementById('attendance-filter-date').value;
            const filterLabel = document.getElementById('attendance-filter-label');

            const clearBtn = document.getElementById('clear-attendance-filter');
            if (date) {
                const dateObj = new Date(date);
                filterLabel.textContent = dateObj.toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' });
                if (clearBtn) clearBtn.style.display = 'flex';
            } else {
                filterLabel.textContent = 'Filter by Date';
                if (clearBtn) clearBtn.style.display = 'none';
            }

            loader.classList.remove('hidden');

            try {
                let url = `{{ url('api/v1/institute/attendance') }}?page=${page}`;
                if (search) url += `&search=${encodeURIComponent(search)}`;
                if (date) url += `&date=${date}`;

                const response = await fetch(url, {
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': CSRF_TOKEN
                    }
                });
                const result = await response.json();

                if (result.status === 'success') {
                    renderAttendance(result.data);
                    renderAttendancePagination(result.pagination);
                }
            } catch (error) {
                console.error('Error fetching attendance:', error);
            } finally {
                loader.classList.add('hidden');
            }
        }

        // Attendance Search Listener (Manual trigger via button or Enter key)
        document.getElementById('attendance-search-input').addEventListener('keypress', (e) => {
            if (e.key === 'Enter') {
                fetchAttendance(1);
            }
        });

        window.exportAttendance = () => {
            const date = document.getElementById('attendance-filter-date').value;
            let url = `{{ url('api/v1/institute/attendance/export') }}`;
            if (date) url += `?date=${date}`;

            window.location.href = url;
        };

        function renderAttendance(data) {
            const tbody = document.getElementById('attendance-table-body');
            if (!tbody) return;

            if (data.length === 0) {
                tbody.innerHTML = `
                                                    <tr>
                                                        <td colspan="6" class="px-6 py-12 text-center">
                                                            ${document.getElementById('attendance-empty-state').innerHTML}
                                                        </td>
                                                    </tr>
                                                `;
                const pagContainer = document.getElementById('attendance-pagination-container');
                if (pagContainer) pagContainer.classList.add('hidden');
                return;
            }
            const pagContainer = document.getElementById('attendance-pagination-container');
            if (pagContainer) pagContainer.classList.remove('hidden');             tbody.innerHTML = data.map(item => {
                const staff = item.staff || {};
                const dept = staff.department ? staff.department.name : 'N/A';
                const initial = staff.full_name ? staff.full_name.charAt(0).toUpperCase() : '?';
                const statusClass = item.status === 'Present' ? 'bg-emerald-50 text-emerald-600' : 'bg-rose-50 text-rose-600';

                // Format date: Oct 24, 2024
                const dateObj = new Date(item.date);
                const formattedDate = dateObj.toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' });

                const avatarHtml = staff.profile_url
                    ? `<img src="${staff.profile_url}" alt="${staff.full_name}" class="h-7 w-7 rounded-full object-cover border border-slate-100 shadow-sm">`
                    : `<div class="h-7 w-7 rounded-full bg-orange-100 text-orange-600 flex items-center justify-center text-[10px] font-bold">${initial}</div>`;

                return `
                                                    <tr class="hover:bg-slate-50/50 transition-colors">
                                                        <td class="px-4 py-3">
                                                            <div class="flex items-center gap-2.5">
                                                                ${avatarHtml}
                                                                <span class="text-xs font-bold text-slate-700">${staff.full_name || 'Unknown'}</span>
                                                            </div>
                                                        </td>
                                                        <td class="px-4 py-3 text-xs font-medium text-slate-500">${dept}</td>
                                                        <td class="px-4 py-3 text-xs font-medium text-slate-500">${formattedDate}</td>
                                                        <td class="px-4 py-3">
                                                            <span class="px-2 py-0.5 rounded-full ${statusClass} text-[9px] font-bold uppercase tracking-wider">${item.status}</span>
                                                        </td>
                                                        <td class="px-4 py-3">
                                                            <div class="flex items-center justify-center gap-2">
                                                                <button onclick='openEditAttendanceModal(${JSON.stringify(item).replace(/'/g, "&apos;")})' 
                                                                    class="p-1.5 hover:bg-amber-50 text-amber-600 rounded-lg transition-all" title="Edit">
                                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                                    </svg>
                                                                </button>
                                                                <button onclick="deleteAttendance(${item.id})" 
                                                                    class="p-1.5 hover:bg-rose-50 text-rose-500 rounded-lg transition-all" title="Delete">
                                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                                    </svg>
                                                                </button>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                `;
            }).join('');
        }

        function renderAttendancePagination(pagination) {
            const container = document.getElementById('attendance-pagination-container');
            if (!container) return;

            if (!pagination || pagination.last_page <= 1) {
                container.innerHTML = `<span class="text-[10px] font-medium text-slate-400">Showing all records</span>`;
                return;
            }

            const from = (pagination.current_page - 1) * pagination.per_page + 1;
            const to = Math.min(pagination.current_page * pagination.per_page, pagination.total);

            let html = `
                                                <span class="text-[10px] font-medium text-slate-400">Showing ${from}-${to} of ${pagination.total} entries</span>
                                                <div class="flex items-center gap-2">
                                                    <button onclick="fetchAttendance(${pagination.current_page - 1})" ${pagination.current_page === 1 ? 'disabled' : ''} 
                                                        class="px-3 py-1.5 border border-slate-200 rounded-lg text-[10px] font-bold text-slate-400 hover:bg-slate-50 disabled:opacity-50">Previous</button>
                                            `;

            for (let i = 1; i <= pagination.last_page; i++) {
                if (i === pagination.current_page) {
                    html += `<button class="h-8 w-8 bg-[#FF6B00] text-white rounded-lg text-[10px] font-bold">${i}</button>`;
                } else {
                    html += `<button onclick="fetchAttendance(${i})" class="h-8 w-8 text-slate-600 rounded-lg text-[10px] font-bold hover:bg-slate-100">${i}</button>`;
                }
            }

            html += `
                                                    <button onclick="fetchAttendance(${pagination.current_page + 1})" ${pagination.current_page === pagination.last_page ? 'disabled' : ''} 
                                                        class="px-3 py-1.5 border border-slate-200 rounded-lg text-[10px] font-bold text-slate-600 hover:bg-slate-50 disabled:opacity-50">Next</button>
                                                </div>
                                            `;

            container.innerHTML = html;
        }

        attendanceForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            const submitBtn = document.getElementById('log-attendance-btn');
            const originalText = submitBtn.innerHTML;

            const formData = {
                date: document.getElementById('attendance-date').value,
                attendances: [
                    {
                        id: document.getElementById('attendance-id-input').value,
                        staff_id: document.getElementById('attendance-staff-select').value,
                        status: document.getElementById('attendance-status-input').value,
                        note: document.getElementById('attendance-note').value
                    }
                ]
            };

            const today = new Date().toLocaleDateString('en-CA'); // Gets YYYY-MM-DD in local time

            if (formData.date > today) {
                const errEl = document.getElementById('error-attendance-date');
                if (errEl) errEl.innerText = 'Future dates are not allowed';
                return;
            }

            submitBtn.disabled = true;
            submitBtn.innerHTML = 'Logging...';

            try {
                const response = await fetch("{{ url('api/v1/institute/attendance') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': CSRF_TOKEN
                    },
                    body: JSON.stringify(formData)
                });

                // Clear previous errors
                document.querySelectorAll('[id^="error-attendance-"]').forEach(el => el.innerText = '');

                const result = await response.json();

                if (response.ok && (result.status === 'success' || result.message.toLowerCase().includes('success'))) {
                    closeAttendanceModal();
                    // Refresh the attendance list
                    fetchAttendance();
                    showToast(result.message || 'Attendance logged successfully!', 'success');
                } else if (response.status === 422) {
                    if (result.errors) {
                        Object.keys(result.errors).forEach(key => {
                            // Map technical keys to UI spans
                            if (key.includes('staff_id')) {
                                const errEl = document.getElementById('error-attendance-staff');
                                if (errEl) errEl.innerText = result.errors[key][0];
                            }
                            if (key === 'date') {
                                const errEl = document.getElementById('error-attendance-date');
                                if (errEl) errEl.innerText = result.errors[key][0];
                            }
                        });
                    }
                } else {
                    showToast(result.message || 'Something went wrong', 'error');
                }
            } catch (error) {
                console.error('Error:', error);
                showToast('Something went wrong. Please try again.', 'error');
            } finally {
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalText;
            }
        });
        // Close dropdown when clicking outside
        document.addEventListener('click', (e) => {
            const container = document.getElementById('attendance-staff-dropdown-container');
            const menu = document.getElementById('attendance-staff-menu');
            if (container && !container.contains(e.target)) {
                if (menu) menu.classList.add('hidden');
            }
        });

        window.selectStaff = (id, name) => {
            document.getElementById('attendance-staff-select').value = id;
            document.getElementById('attendance-staff-label').textContent = name;
            document.getElementById('attendance-staff-label').classList.remove('text-slate-400');
            document.getElementById('attendance-staff-label').classList.add('text-slate-700');
            document.getElementById('attendance-staff-menu').classList.add('hidden');
        }

        window.filterStaffOptions = () => {
            const search = document.getElementById('attendance-staff-search').value.toLowerCase();
            renderStaffOptions(staffListData.filter(s => s.full_name.toLowerCase().includes(search)));
        }

        function renderStaffOptions(data) {
            const container = document.getElementById('attendance-staff-options');
            if (!container) return;

            if (data.length === 0) {
                container.innerHTML = '<div class="px-4 py-2 text-xs text-slate-400 text-center">No staff found</div>';
                return;
            }

            container.innerHTML = data.map(staff => `
                                                <div onclick="selectStaff(${staff.id}, '${staff.full_name}')" 
                                                    class="px-4 py-2 text-xs text-slate-600 hover:bg-slate-50 hover:text-[#FF6B00] cursor-pointer transition-colors font-medium">
                                                    ${staff.full_name}
                                                </div>
                                            `).join('');
        }

        window.openEditAttendanceModal = (item) => {
            document.getElementById('log-attendance-modal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';

            // Set form data
            document.getElementById('attendance-id-input').value = item.id;
            document.getElementById('attendance-date').value = item.date;
            document.getElementById('attendance-note').value = item.note || '';
            setAttendanceStatus(item.status);

            // Set staff label and value
            if (item.staff) {
                selectStaff(item.staff_id, item.staff.full_name);
            }

            // Always load fresh staff list
            fetchStaffForAttendance();
        };

        async function fetchStaffForAttendance() {
            try {
                const response = await fetch("{{ url('api/v1/institute/staff') }}?all=1&_t=" + new Date().getTime(), {
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': CSRF_TOKEN
                    }
                });
                const result = await response.json();

                if (result.status === 'success') {
                    staffListData = result.data;
                    renderStaffOptions(staffListData);
                }
            } catch (error) {
                console.error('Error fetching staff list:', error);
            }
        }

        window.clearAttendanceFilter = () => {
            document.getElementById('attendance-filter-date').value = '';
            fetchAttendance(1);
        };

        window.deleteAttendance = (id) => {
            showConfirmModal(
                'Delete Attendance',
                'Are you sure you want to delete this attendance record? This action will permanently remove the record from logs.',
                async () => {
                    try {
                        const response = await fetch(`{{ url('api/v1/institute/attendance') }}/${id}`, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': CSRF_TOKEN,
                                'Accept': 'application/json'
                            }
                        });

                        if (response.ok) {
                            fetchAttendance();
                            showToast('Attendance record deleted successfully');
                        } else {
                            showToast('Error deleting attendance record', 'error');
                        }
                    } catch (error) {
                        console.error('Error:', error);
                        showToast('Error deleting attendance record', 'error');
                    }
                },
                'Yes, Delete Record',
                'bg-[#FF6B00] text-white rounded-lg text-[10px] font-bold shadow-lg hover:opacity-90 active:scale-95 transition-all'
            );
        };





        // Salary Management Logic
        let salaryStaffListData = [];

        async function fetchSalaries(page = 1) {
            const tbody = document.getElementById('salary-table-body');
            const loader = document.getElementById('salary-loader');
            if (!tbody || !loader) return;

            const search = document.getElementById('salary-search-input').value;
            const monthVal = document.getElementById('salary-filter-month').value;
            const filterLabel = document.getElementById('salary-filter-label');

            const clearBtn = document.getElementById('clear-salary-filter');
            if (monthVal) {
                const dateObj = new Date(monthVal + '-01');
                filterLabel.textContent = dateObj.toLocaleDateString('en-US', { month: 'short', year: 'numeric' });
                if (clearBtn) clearBtn.style.display = 'flex';
            } else {
                filterLabel.textContent = 'Filter by Month';
                if (clearBtn) clearBtn.style.display = 'none';
            }

            loader.classList.remove('hidden');

            try {
                let url = `{{ url('api/v1/institute/salaries') }}?page=${page}`;
                if (search) url += `&search=${encodeURIComponent(search)}`;
                if (monthVal) {
                    const [year, month] = monthVal.split('-');
                    url += `&month=${month}&year=${year}`;
                }

                const response = await fetch(url, {
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': CSRF_TOKEN
                    }
                });
                const result = await response.json();

                if (result.status === 'success') {
                    renderSalaries(result.data);
                    renderSalaryPagination(result.pagination);
                }
            } catch (error) {
                console.error('Error fetching salaries:', error);
            } finally {
                loader.classList.add('hidden');
            }
        }

        function renderSalaries(data) {
            const tbody = document.getElementById('salary-table-body');
            if (!tbody) return;

            if (data.length === 0) {
                tbody.innerHTML = `<tr><td colspan="6" class="px-4 py-8 text-center">${document.getElementById('salary-empty-state').innerHTML}</td></tr>`;
                return;
            }

            tbody.innerHTML = data.map(item => {
                const staff = item.staff || {};
                const initial = staff.full_name ? staff.full_name.charAt(0).toUpperCase() : '?';
                const avatarHtml = staff.profile_url
                    ? `<img src="${staff.profile_url}" alt="${staff.full_name}" class="h-8 w-8 rounded-full object-cover border border-slate-100 shadow-sm">`
                    : `<div class="h-8 w-8 rounded-full bg-slate-100 flex items-center justify-center text-[10px] font-bold text-slate-500 uppercase">${initial}</div>`;

                return `
                                                <tr class="hover:bg-slate-50/50 transition-colors">
                                                    <td class="px-4 py-3">
                                                        <div class="flex items-center gap-3">
                                                            ${avatarHtml}
                                                            <div class="font-bold text-slate-700 text-xs">${staff.full_name || 'Unknown'}</div>
                                                        </div>
                                                    </td>
                                                    <td class="px-4 py-3 text-xs font-bold text-slate-500">${staff.employee_id || 'STF-' + staff.id}</td>
                                                    <td class="px-4 py-3 text-xs text-slate-500">${new Date(item.payment_date).toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' })}</td>
                                                    <td class="px-4 py-3">
                                                        <span class="flex items-center gap-1.5 text-[10px] font-bold ${item.payment_method === 'Online' ? 'text-blue-600' : 'text-amber-600'}">
                                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="${item.payment_method === 'Online' ? 'M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z' : 'M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z'}" /></svg>
                                                            ${item.payment_method}
                                                        </span>
                                                    </td>
                                                    <td class="px-4 py-3 text-xs font-bold text-slate-800 text-center">₹${parseFloat(item.net_salary).toLocaleString()}</td>
                                                    <td class="px-4 py-3">
                                                        <div class="flex items-center justify-center gap-2">
                                                            <button onclick='openSalaryModal(${JSON.stringify(item).replace(/'/g, "&apos;")})' 
                                                                class="p-1.5 hover:bg-amber-50 text-amber-600 rounded-lg transition-all" title="Edit">
                                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                                </svg>
                                                            </button>
                                                            <button onclick="deleteSalary(${item.id})" 
                                                                class="p-1.5 hover:bg-rose-50 text-rose-500 rounded-lg transition-all" title="Delete">
                                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                                </svg>
                                                            </button>
                                                        </div>
                                                    </td>
                                                </tr>
                `;
            }).join('');
        }

        function renderSalaryPagination(pagination) {
            const container = document.getElementById('salary-pagination-container');
            if (!container) return;

            if (pagination.last_page <= 1) {
                container.innerHTML = `<span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Showing all records</span>`;
                return;
            }

            container.innerHTML = `
                                                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Page ${pagination.current_page} of ${pagination.last_page}</span>
                                                <div class="flex items-center gap-1">
                                                    ${pagination.current_page > 1 ? `<button onclick="fetchSalaries(${pagination.current_page - 1})" class="p-1.5 rounded-lg border border-slate-200 text-slate-600 hover:bg-slate-50 transition-colors"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg></button>` : ''}
                                                    ${pagination.current_page < pagination.last_page ? `<button onclick="fetchSalaries(${pagination.current_page + 1})" class="p-1.5 rounded-lg border border-slate-200 text-slate-600 hover:bg-slate-50 transition-colors"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg></button>` : ''}
                                                </div>
                                            `;
        }

        window.openSalaryModal = (item = null) => {
            const modal = document.getElementById('salary-modal');
            const content = document.getElementById('salary-modal-content');
            modal.classList.remove('hidden');
            setTimeout(() => {
                content.classList.remove('scale-95', 'opacity-0');
            }, 10);

            document.getElementById('salary-form').reset();
            document.getElementById('salary_id_input').value = '';
            document.getElementById('salary-modal-title').textContent = 'Add Salary';
            document.getElementById('selected-salary-staff-name').textContent = 'Choose a staff member...';
            document.getElementById('selected-salary-staff-name').classList.add('text-slate-400');

            if (item) {
                document.getElementById('salary-modal-title').textContent = 'Edit Salary';
                document.getElementById('salary_id_input').value = item.id;
                document.getElementById('salary_staff_id_input').value = item.staff_id;
                document.getElementById('selected-salary-staff-name').textContent = item.staff.full_name;
                document.getElementById('selected-salary-staff-name').classList.remove('text-slate-400');
                document.getElementById('salary_payment_date').value = item.payment_date;
                document.getElementById('salary_base_amount').value = item.base_salary;
                document.getElementById('salary_notes').value = item.notes || '';
                setSalaryMethod(item.payment_method);
            } else {
                document.getElementById('salary_payment_date').value = new Date().toISOString().split('T')[0];
            }

            updateSalaryPreview();
            fetchStaffForSalary();
        };

        window.closeSalaryModal = () => {
            const modal = document.getElementById('salary-modal');
            const content = document.getElementById('salary-modal-content');
            content.classList.add('scale-95', 'opacity-0');
            setTimeout(() => {
                modal.classList.add('hidden');
            }, 300);
        };

        window.toggleSalaryStaffDropdown = () => {
            const dropdown = document.getElementById('salary-staff-dropdown');
            const chevron = document.getElementById('salary-staff-chevron');
            dropdown.classList.toggle('hidden');
            chevron.classList.toggle('rotate-180');
        };

        window.filterSalaryStaffOptions = (search) => {
            const filtered = salaryStaffListData.filter(s => s.full_name.toLowerCase().includes(search.toLowerCase()));
            renderSalaryStaffOptions(filtered);
        };

        function renderSalaryStaffOptions(data) {
            const container = document.getElementById('salary-staff-options');
            if (!container) return;

            if (data.length === 0) {
                container.innerHTML = '<div class="px-4 py-2 text-xs text-slate-400 text-center">No staff found</div>';
                return;
            }

            container.innerHTML = data.map(staff => `
                                                <div onclick="selectSalaryStaff(${staff.id}, '${staff.full_name}', ${staff.base_salary || 0})" 
                                                    class="px-4 py-2 text-xs text-slate-600 hover:bg-slate-50 hover:text-[#FF6B00] cursor-pointer transition-colors font-medium">
                                                    ${staff.full_name}
                                                </div>
                                            `).join('');
        }

        window.selectSalaryStaff = (id, name, baseSalary) => {
            document.getElementById('salary_staff_id_input').value = id;
            document.getElementById('selected-salary-staff-name').textContent = name;
            document.getElementById('selected-salary-staff-name').classList.remove('text-slate-400');

            if (!document.getElementById('salary_id_input').value) {
                document.getElementById('salary_base_amount').value = baseSalary;
            }

            toggleSalaryStaffDropdown();
            updateSalaryPreview();
        };

        async function fetchStaffForSalary() {
            try {
                const response = await fetch("{{ url('api/v1/institute/staff') }}?all=1&_t=" + new Date().getTime(), {
                    headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': CSRF_TOKEN }
                });
                const result = await response.json();
                if (result.status === 'success') {
                    salaryStaffListData = result.data;
                    renderSalaryStaffOptions(salaryStaffListData);
                }
            } catch (error) { console.error('Error:', error); }
        }

        window.clearSalaryFilter = () => {
            document.getElementById('salary-filter-month').value = '';
            fetchSalaries(1);
        };

        window.setSalaryMethod = (method) => {
            document.getElementById('salary_payment_method_input').value = method;
            const cashBtn = document.getElementById('salary-method-cash');
            const onlineBtn = document.getElementById('salary-method-online');

            if (method === 'Cash') {
                cashBtn.classList.add('bg-white', 'text-[#FF6B00]', 'shadow-sm');
                cashBtn.classList.remove('text-slate-500', 'hover:text-[#FF6B00]');
                onlineBtn.classList.remove('bg-white', 'text-[#FF6B00]', 'shadow-sm');
                onlineBtn.classList.add('text-slate-500', 'hover:text-[#FF6B00]');
            } else {
                onlineBtn.classList.add('bg-white', 'text-[#FF6B00]', 'shadow-sm');
                onlineBtn.classList.remove('text-slate-500', 'hover:text-[#FF6B00]');
                cashBtn.classList.remove('bg-white', 'text-[#FF6B00]', 'shadow-sm');
                cashBtn.classList.add('text-slate-500', 'hover:text-[#FF6B00]');
            }
        };

        window.updateSalaryPreview = () => {
            const base = parseFloat(document.getElementById('salary_base_amount').value) || 0;
            const deductions = 0; // Future enhancement
            const total = base - deductions;

            document.getElementById('preview-base-salary').textContent = `₹${base.toLocaleString()}`;
            document.getElementById('preview-deductions').textContent = `-₹${deductions.toLocaleString()}`;
            document.getElementById('preview-total-disbursement').textContent = `₹${total.toLocaleString()}`;
        };

        window.saveSalaryRecord = async () => {
            const form = document.getElementById('salary-form');
            const btn = document.getElementById('save-salary-btn');
            const formData = new FormData(form);

            const today = new Date().toLocaleDateString('en-CA');
            const selectedPaymentDate = formData.get('payment_date');

            if (selectedPaymentDate > today) {
                showToast('Future dates are not allowed for salary payment', 'error');
                btn.disabled = false;
                btn.innerHTML = `<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"/></svg> Save Salary Record`;
                return;
            }

            btn.disabled = true;
            btn.innerHTML = `<svg class="w-4 h-4 animate-spin" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" fill="none"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Saving...`;

            // Filter out _token from formData to avoid API validation error
            const payload = new FormData();
            for (let [key, value] of formData.entries()) {
                if (key !== '_token') {
                    payload.append(key, value);
                }
            }

            try {
                const response = await fetch("{{ url('api/v1/institute/salaries') }}", {
                    method: 'POST',
                    body: payload,
                    headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': CSRF_TOKEN }
                });

                const result = await response.json();
                if (response.ok) {
                    closeSalaryModal();
                    fetchSalaries();
                } else {
                    showToast(result.message || 'Error saving salary record', 'error');
                }
            } catch (error) {
                console.error('Error:', error);
                showToast('An unexpected error occurred', 'error');
            } finally {
                btn.disabled = false;
                btn.innerHTML = `<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"/></svg> Save Salary Record`;
            }
        };

        window.exportSalaries = () => {
            const monthVal = document.getElementById('salary-filter-month').value;
            let url = `{{ url('api/v1/institute/salaries/export') }}`;
            if (monthVal) {
                const [year, month] = monthVal.split('-');
                url += `?month=${month}&year=${year}`;
            }
            window.location.href = url;
        };

        window.deleteSalary = (id) => {
            showConfirmModal(
                'Delete Salary Record',
                'Are you sure you want to delete this salary record? This action will permanently remove the record from financial logs.',
                async () => {
                    try {
                        const response = await fetch(`{{ url('api/v1/institute/salaries') }}/${id}`, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': CSRF_TOKEN,
                                'Accept': 'application/json'
                            }
                        });

                        if (response.ok) {
                            fetchSalaries();
                            showToast('Salary record deleted successfully');
                        } else {
                            showToast('Error deleting salary record', 'error');
                        }
                    } catch (error) {
                        console.error('Error:', error);
                        showToast('Error deleting salary record', 'error');
                    }
                },
                'Yes, Delete Salary',
                'bg-[#FF6B00] text-white rounded-lg text-[10px] font-bold shadow-lg hover:opacity-90 active:scale-95 transition-all'
            );
        };

        // Close dropdown when clicking outside
        document.addEventListener('click', (e) => {
            const container = document.getElementById('salary-staff-dropdown-container');
            const menu = document.getElementById('salary-staff-dropdown');
            const chevron = document.getElementById('salary-staff-chevron');
            if (container && !container.contains(e.target)) {
                if (menu) menu.classList.add('hidden');
                if (chevron) chevron.classList.remove('rotate-180');
            }
        });
    </script>
@endsection