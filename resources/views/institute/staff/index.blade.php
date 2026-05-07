@extends('layouts.institute')

@section('content')
    <div class="max-w-7xl mx-auto ">
        <!-- Header Section -->
        <div class="flex flex-col md:flex-row md:items-center justify-between mb-4 gap-4">
            <div>
                <h1 class="text-2xl font-bold text-slate-800 tracking-tight">Staff Management Hub</h1>
                <p class="text-xs text-slate-500 mt-0.5">Manage your institute's faculty and support staff.</p>
            </div>


        </div>
        
        <!-- Navigation & Actions -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
            <div class="flex items-center gap-2 p-1 bg-slate-100 rounded-2xl w-fit">
                <button onclick="switchTab('staff')" id="tab-staff"
                    class="px-6 py-2.5 rounded-xl text-xs font-bold transition-all bg-[#A8440B] text-white shadow-lg shadow-amber-900/10">
                    Staffs Management
                </button>
                <button onclick="switchTab('attendance')" id="tab-attendance"
                    class="px-6 py-2.5 rounded-xl text-xs font-bold transition-all text-slate-500 hover:text-slate-700">
                    Attendance Management
                </button>
                <button onclick="switchTab('salary')" id="tab-salary"
                    class="px-6 py-2.5 rounded-xl text-xs font-bold transition-all text-slate-500 hover:text-slate-700">
                    Salary Management
                </button>
            </div>

            <div id="staff-actions">
                <button onclick="openAddModal()"
                    class="px-5 py-2 bg-[#A8440B] text-white rounded-xl text-xs font-bold flex items-center justify-center gap-1.5 hover:translate-y-[-1px] shadow-lg shadow-amber-900/10 active:scale-95 transition-all">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4" />
                    </svg>
                    Add Staff
                </button>
            </div>
            <div id="attendance-actions" class="hidden">
                <button class="px-5 py-2 bg-[#A8440B] text-white rounded-xl text-xs font-bold flex items-center justify-center gap-1.5 hover:translate-y-[-1px] shadow-lg shadow-amber-900/10 active:scale-95 transition-all">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4" />
                    </svg>
                    Log Attendance
                </button>
            </div>
        </div>




    <!-- Staff Management View -->
    <div id="staff-view">
        <!-- Search & Filter Bar -->
        <div class="bg-white rounded-2xl border border-slate-100 p-2 mb-8 flex flex-wrap items-center gap-2">
            <div class="flex-1 min-w-[200px] relative group">
                <input type="text" id="staff-search" placeholder="Search staff..."
                    class="w-full pl-10 pr-4 py-2.5 bg-slate-50 border-none rounded-xl text-xs font-medium focus:ring-2 focus:ring-brand-800/20 transition-all outline-none">
                <svg class="w-4 h-4 absolute left-3.5 top-3 text-slate-400 group-focus-within:text-brand-800 transition-colors"
                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </div>

            <button id="search-btn" onclick="fetchStaff()"
                class="px-5 py-2.5 bg-[#A8440B] text-white rounded-xl text-xs font-bold hover:bg-[#8e3a09] transition-all shadow-sm">SEARCH</button>

            <div class="h-8 w-px bg-slate-100 mx-2"></div>

            <select id="role-filter" onchange="fetchStaff()"
                class="bg-slate-50 border-none rounded-xl px-4 py-2.5 text-xs font-bold text-slate-700 focus:ring-2 focus:ring-brand-800/20 outline-none">
                <option value="">Role: All</option>
                @foreach ($roles as $role)
                    <option value="{{ $role->id }}">{{ $role->name }}</option>
                @endforeach
            </select>

            <button
                class="px-5 py-2.5 border border-slate-200 rounded-xl text-xs font-bold text-slate-600 hover:bg-slate-50 transition-all flex items-center gap-2">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                </svg>
                FILTERS
            </button>
        </div>

        <div id="staff-grid" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 relative min-h-[200px]">
            <!-- Loading Spinner -->
            <div id="loading-spinner"
                class="absolute inset-0 z-50 bg-white/60 backdrop-blur-[2px] hidden flex items-center justify-center rounded-3xl">
                <div class="h-10 w-10 border-4 border-slate-100 border-t-brand-800 rounded-full animate-spin"></div>
            </div>
            <!-- Dynamic Content -->
        </div>

        <div id="pagination-container" class="mt-8"></div>
    </div>

        <!-- Attendance Management View -->
        <div id="attendance-view" class="hidden">
            <div class="bg-white rounded-2xl border border-slate-200/60 shadow-sm overflow-hidden">
                <!-- Table Header Actions -->
                <div class="p-4 border-b border-slate-100 flex flex-wrap items-center justify-between gap-4">
                    <div class="flex-1 min-w-[300px] relative">
                        <input type="text" placeholder="Search staff name or department..."
                            class="w-full pl-10 pr-4 py-2 bg-slate-50 border border-slate-200 rounded-lg text-xs outline-none focus:border-brand-800 transition-all">
                        <svg class="w-4 h-4 absolute left-3 top-2.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                    <div class="flex items-center gap-3">
                        <button class="px-4 py-2 border border-slate-200 rounded-lg text-xs font-bold text-slate-600 hover:bg-slate-50 flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 00-2 2z" />
                            </svg>
                            Filter by Date
                        </button>
                        <button class="px-4 py-2 border border-slate-200 rounded-lg text-xs font-bold text-slate-600 hover:bg-slate-50 flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a2 2 0 002 2h12a2 2 0 002-2v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                            </svg>
                            Export
                        </button>
                    </div>
                </div>

                <!-- Attendance Table -->
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50/50">
                                <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-wider">Staff Name</th>
                                <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-wider">Employee ID</th>
                                <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-wider">Department</th>
                                <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-wider">Date</th>
                                <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-wider">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            <tr>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="h-8 w-8 rounded-full bg-orange-100 text-orange-600 flex items-center justify-center text-[10px] font-bold">JD</div>
                                        <span class="text-xs font-bold text-slate-700">Julian Drumm</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-xs font-medium text-slate-500">EMP-2045</td>
                                <td class="px-6 py-4 text-xs font-medium text-slate-500">Administrative Affairs</td>
                                <td class="px-6 py-4 text-xs font-medium text-slate-500">Oct 24, 2024</td>
                                <td class="px-6 py-4">
                                    <span class="px-2.5 py-1 rounded-full bg-emerald-50 text-emerald-600 text-[9px] font-bold uppercase tracking-wider">Present</span>
                                </td>
                                <td class="px-6 py-4">
                                    <button class="p-1 hover:bg-slate-100 rounded transition-all text-slate-400">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12 8c1.1 0 2-.9 2-2s-.9-2-2-2-2 .9-2 2 .9 2 2 2zm0 2c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2zm0 6c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2z"/></svg>
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Table Footer -->
                <div class="p-4 bg-slate-50/30 border-t border-slate-100 flex items-center justify-between">
                    <span class="text-[10px] font-medium text-slate-400">Showing 1-10 of 1284 entries</span>
                    <div class="flex items-center gap-2">
                        <button class="px-3 py-1.5 border border-slate-200 rounded-lg text-[10px] font-bold text-slate-400 hover:bg-slate-50">Previous</button>
                        <button class="h-8 w-8 bg-[#A8440B] text-white rounded-lg text-[10px] font-bold">1</button>
                        <button class="h-8 w-8 text-slate-600 rounded-lg text-[10px] font-bold hover:bg-slate-100">2</button>
                        <button class="px-3 py-1.5 border border-slate-200 rounded-lg text-[10px] font-bold text-slate-600 hover:bg-slate-50">Next</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add/Edit Staff Modal -->
    <div id="add-staff-modal" class="fixed inset-0 z-[100] hidden">
        <div class="absolute inset-0 bg-slate-900/40 backdrop-blur-sm transition-opacity" onclick="closeAddModal()"></div>
        <div
            class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-full max-w-[550px] bg-white rounded-[2rem] shadow-2xl border border-slate-100 overflow-hidden animate-in zoom-in-95 duration-300 max-h-[95vh] flex flex-col">
            <!-- Modal Header -->
            <div class="px-8 py-5 flex items-center justify-between shrink-0">
                <h1 id="modal-title" class="text-lg font-bold text-slate-800 tracking-tight">Add Staff Member</h1>
                <button onclick="closeAddModal()" class="text-slate-400 hover:text-slate-600 transition-all">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <div class="overflow-y-auto px-8 pb-8 custom-scrollbar">
                <form id="add-staff-form" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="staff_id" id="staff_id">

                    <!-- Profile Image -->
                    <div class="mb-4">
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
                            <p class="text-[10px] text-slate-400 max-w-[200px]">Upload a professional headshot. Max size
                                2MB.</p>
                        </div>
                    </div>

                    <div class="space-y-4">
                        <div>
                            <label class="block text-[11px] font-bold text-slate-800 mb-1.5">Full Name</label>
                            <input type="text" name="full_name" id="field-name" required placeholder="e.g. Jonathan Smith"
                                class="w-full px-4 py-2.5 bg-white border border-slate-200 rounded-lg text-xs font-medium focus:border-brand-800 outline-none transition-all placeholder:text-slate-300">
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-[11px] font-bold text-slate-800 mb-1.5">Role</label>
                                <select name="staff_role_id" id="field-role" required
                                    class="w-full px-4 py-2.5 bg-white border border-slate-200 rounded-lg text-xs font-medium focus:border-brand-800 outline-none transition-all appearance-none cursor-pointer">
                                    <option value="">Select Role</option>
                                    @foreach($roles as $role)
                                        <option value="{{ $role->id }}">{{ $role->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-[11px] font-bold text-slate-800 mb-1.5">Department</label>
                                <select name="staff_department_id" id="field-dept" required
                                    class="w-full px-4 py-2.5 bg-white border border-slate-200 rounded-lg text-xs font-medium focus:border-brand-800 outline-none transition-all appearance-none cursor-pointer">
                                    <option value="">Select Department</option>
                                    @foreach($departments as $dept)
                                        <option value="{{ $dept->id }}">{{ $dept->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-[11px] font-bold text-slate-800 mb-1.5">Email Address</label>
                                <input type="email" name="email" id="field-email" required placeholder="j.smith@company.com"
                                    class="w-full px-4 py-2.5 bg-white border border-slate-200 rounded-lg text-xs font-medium focus:border-brand-800 outline-none transition-all placeholder:text-slate-300">
                            </div>
                            <div>
                                <label class="block text-[11px] font-bold text-slate-800 mb-1.5">Phone Number</label>
                                <input type="text" name="phone" id="field-phone" required placeholder="+1 (555) 000-0000"
                                    class="w-full px-4 py-2.5 bg-white border border-slate-200 rounded-lg text-xs font-medium focus:border-brand-800 outline-none transition-all placeholder:text-slate-300">
                                <span id="error-phone" class="text-[10px] text-rose-500 font-bold mt-1 block"></span>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-[11px] font-bold text-slate-800 mb-1.5">Employment Type</label>
                                <div class="flex p-1 bg-slate-100 rounded-lg w-full">
                                    <label class="flex-1 cursor-pointer">
                                        <input type="radio" name="employment_type" id="employment-salary" value="Salary"
                                            checked class="hidden peer">
                                        <div
                                            class="py-1.5 rounded-md text-[10px] font-bold text-slate-500 peer-checked:bg-white peer-checked:text-brand-800 peer-checked:shadow-sm transition-all text-center">
                                            Salary</div>
                                    </label>
                                    <label class="flex-1 cursor-pointer">
                                        <input type="radio" name="employment_type" id="employment-hourly" value="Hourly"
                                            class="hidden peer">
                                        <div
                                            class="py-1.5 rounded-md text-[10px] font-bold text-slate-500 peer-checked:bg-white peer-checked:text-brand-800 peer-checked:shadow-sm transition-all text-center">
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
                    <div class="flex items-center justify-end gap-4 mt-6">
                        <button type="button" onclick="closeAddModal()"
                            class="text-xs font-bold text-slate-500 hover:text-slate-800 transition-colors">Cancel</button>
                        <button type="submit" id="submit-btn"
                            class="px-8 py-2.5 bg-[#A8440B] text-white rounded-lg text-xs font-bold shadow-lg shadow-amber-900/20 hover:translate-y-[-1px] active:scale-95 transition-all">Save
                            Staff Member</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        const API_URL = "{{ url('api/v1/institute/staff') }}";
        const CSRF_TOKEN = "{{ csrf_token() }}";

        window.switchTab = (tab) => {
            const staffView = document.getElementById('staff-view');
            const attendanceView = document.getElementById('attendance-view');
            const staffBtn = document.getElementById('tab-staff');
            const attendanceBtn = document.getElementById('tab-attendance');
            const staffActions = document.getElementById('staff-actions');
            const attendanceActions = document.getElementById('attendance-actions');

            if (tab === 'staff') {
                staffView.classList.remove('hidden');
                attendanceView.classList.add('hidden');
                staffActions.classList.remove('hidden');
                attendanceActions.classList.add('hidden');
                staffBtn.classList.add('bg-[#A8440B]', 'text-white', 'shadow-lg');
                staffBtn.classList.remove('text-slate-500');
                attendanceBtn.classList.remove('bg-[#A8440B]', 'text-white', 'shadow-lg');
                attendanceBtn.classList.add('text-slate-500');
            } else if (tab === 'attendance') {
                staffView.classList.add('hidden');
                attendanceView.classList.remove('hidden');
                staffActions.classList.add('hidden');
                attendanceActions.classList.remove('hidden');
                attendanceBtn.classList.add('bg-[#A8440B]', 'text-white', 'shadow-lg');
                attendanceBtn.classList.remove('text-slate-500');
                staffBtn.classList.remove('bg-[#A8440B]', 'text-white', 'shadow-lg');
                staffBtn.classList.add('text-slate-500');
            }
        };

        document.addEventListener('DOMContentLoaded', () => {
            fetchStaff();

            const searchInput = document.getElementById('staff-search');
            const searchBtn = document.getElementById('search-btn');
            const roleFilter = document.getElementById('role-filter');

            searchInput.addEventListener('keypress', (e) => {
                if (e.key === 'Enter') fetchStaff();
            });

            searchBtn.addEventListener('click', () => fetchStaff());
            roleFilter.addEventListener('change', () => fetchStaff());
        });

        async function fetchStaff(page = 1) {
            const grid = document.getElementById('staff-grid');
            const loader = document.getElementById('loading-spinner');
            const searchInput = document.getElementById('staff-search');
            const roleFilter = document.getElementById('role-filter');

            if (!grid || !loader) return;

            const search = searchInput ? searchInput.value : '';
            const roleId = roleFilter ? roleFilter.value : '';

            loader.classList.remove('hidden');

            try {
                const params = new URLSearchParams({
                    page: page,
                    search: search,
                    role_id: roleId,
                    per_page: 12
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
                    renderStaff(result.data.items || []);
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

            // Preserve the loading spinner
            const loaderHtml = `
                <div id="loading-spinner" class="absolute inset-0 z-50 bg-white/60 backdrop-blur-[2px] hidden flex items-center justify-center rounded-3xl">
                    <div class="h-10 w-10 border-4 border-slate-100 border-t-brand-800 rounded-full animate-spin"></div>
                </div>
            `;

            if (staffMembers.length === 0) {
                grid.innerHTML = loaderHtml + `
                    <div class="col-span-full py-20 flex flex-col items-center text-center bg-white rounded-3xl border border-slate-100 w-full">
                        <div class="h-20 w-20 bg-slate-50 rounded-full flex items-center justify-center text-slate-300 mb-4">
                            <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-slate-800 mb-1">No staff members found</h3>
                        <p class="text-sm text-slate-400">Try adjusting your filters or add a new staff member.</p>
                    </div>
                `;
                return;
            }

            const cardsHtml = staffMembers.map(staff => {
                const profileImg = staff.profile_image ? `/storage/${staff.profile_image}` : `https://ui-avatars.com/api/?name=${encodeURIComponent(staff.full_name)}&background=F1F5F9&color=64748B&bold=true`;
                const statusColor = staff.status === 'active' || staff.status === 1 ? 'bg-emerald-500' : 'bg-slate-300';

                return `
                    <div class="bg-white rounded-[1.5rem] border border-slate-200/60 p-4 flex flex-col items-center text-center group hover:shadow-xl hover:shadow-slate-200/30 transition-all duration-300 animate-in fade-in slide-in-from-bottom-2 h-fit relative">
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
                            <a href="#" class="flex-1 py-1.5 bg-slate-50 text-slate-600 rounded-xl text-[10px] font-bold hover:bg-slate-100 transition-all text-center">Profile</a>
                            <button onclick='openEditModal(${JSON.stringify(staff).replace(/'/g, "&apos;")})' 
                                class="flex-1 py-1.5 bg-slate-50 text-slate-600 rounded-xl text-[10px] font-bold hover:bg-slate-100 transition-all">Edit</button>
                        </div>
                    </div>
                `;
            }).join('');

            grid.innerHTML = loaderHtml + cardsHtml;
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
                    html += `<span class="h-8 w-8 flex items-center justify-center rounded-lg bg-brand-800 text-white text-xs font-bold shadow-md shadow-amber-900/20">${i}</span>`;
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
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = (e) => document.getElementById('image-preview').src = e.target.result;
                reader.readAsDataURL(input.files[0]);
            }
        };

        window.openAddModal = () => {
            document.getElementById('modal-title').innerText = 'Add Staff Member';
            document.getElementById('add-staff-form').reset();
            document.getElementById('staff_id').value = '';
            document.getElementById('image-preview').src = 'https://ui-avatars.com/api/?name=Staff&background=F1F5F9&color=64748B&bold=true';
            document.getElementById('add-staff-modal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        };

        window.openEditModal = (staff) => {
            document.getElementById('modal-title').innerText = 'Edit Staff Member';
            document.getElementById('add-staff-form').reset();

            document.getElementById('staff_id').value = staff.id;
            document.getElementById('field-name').value = staff.full_name;
            document.getElementById('field-role').value = staff.staff_role_id;
            document.getElementById('field-dept').value = staff.staff_department_id;
            document.getElementById('field-email').value = staff.email;
            document.getElementById('field-phone').value = staff.phone || '';

            if (staff.employment_type === 'Hourly') {
                document.getElementById('employment-hourly').checked = true;
            } else {
                document.getElementById('employment-salary').checked = true;
            }

            document.getElementById('field-salary').value = staff.base_salary;

            const profileImg = staff.profile_image ? `/storage/${staff.profile_image}` : `https://ui-avatars.com/api/?name=${encodeURIComponent(staff.full_name)}&background=F1F5F9&color=64748B&bold=true`;
            document.getElementById('image-preview').src = profileImg;

            document.getElementById('add-staff-modal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        };

        window.closeAddModal = () => {
            document.getElementById('add-staff-modal').classList.add('hidden');
            document.body.style.overflow = 'auto';
        };

        // Form Submission
        const addForm = document.getElementById('add-staff-form');
        addForm.addEventListener('submit', async (e) => {
            e.preventDefault();
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
                            if (errorEl) errorEl.innerText = result.errors[key][0];
                        });
                    }
                    return;
                }

                if (result.status === 'success') {
                    closeAddModal();
                    fetchStaff();
                } else {
                    alert(result.message || 'Something went wrong');
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Something went wrong. Please try again.');
            } finally {
                submitBtn.disabled = false;
                submitBtn.innerText = originalText;
            }
        });

        // Delete Function
        window.deleteStaff = async (id, name) => {
            if (!confirm(`Are you sure you want to delete ${name}?`)) return;

            try {
                const response = await fetch(`{{ url('institute/staff') }}/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': "{{ csrf_token() }}",
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                });

                if (response.ok) {
                    fetchStaff(); // Refresh list via API
                } else {
                    alert('Error deleting staff member');
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Error deleting staff member');
            }
        };
    </script>
@endsection