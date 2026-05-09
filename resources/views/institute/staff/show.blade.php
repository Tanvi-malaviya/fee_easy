@extends('layouts.institute')

@section('content')
    <div class="pb-2 space-y-2">
        <!-- Breadcrumb Navigation -->
        <div class="flex items-center px-1 ">
            <a href="{{ route('institute.staff.index') }}" class="flex items-center gap-1 text-slate-400 hover:text-brand-800 transition-all group">
                <div class="h-7 w-7 rounded-lg bg-white border border-slate-200 flex items-center justify-center shadow-sm group-hover:border-brand-800 transition-all">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                </div>
                <span class="text-xs font-semibold ml-1">Back to Staff Management</span>
            </a>
        </div>

        <!-- Profile Header Compact Card -->
        <div class="relative bg-white rounded-2xl p-4 shadow-sm border border-slate-200 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div class="flex flex-col sm:flex-row items-center gap-4 sm:gap-5 text-center sm:text-left">
                <!-- Profile Image centered on mobile -->
                <div class="relative h-20 w-20 flex-shrink-0 mx-auto sm:mx-0">
                    <div class="h-full w-full rounded-full border-2 border-slate-100 overflow-hidden bg-slate-50">
                        <img src="{{ $staff->profile_image ? asset('storage/' . $staff->profile_image) : 'https://ui-avatars.com/api/?name=' . urlencode($staff->full_name) . '&background=F1F5F9&color=64748B&bold=true' }}"
                            alt="{{ $staff->full_name }}" class="h-full w-full object-cover">
                    </div>
                    <div class="absolute bottom-1 right-1 h-4 w-4 bg-emerald-500 border-2 border-white rounded-full"></div>
                </div>

                <!-- Info -->
                <div class="space-y-1">
                    <h2 class="text-xl font-bold text-slate-800 tracking-tight leading-tight">{{ $staff->full_name }}</h2>
                    <p class="text-xs font-bold text-brand-800 uppercase tracking-widest">
                        {{ $staff->role->name ?? 'Staff Member' }}</p>
                    <div class="flex flex-col sm:flex-row items-center gap-2 sm:gap-4 pt-1">
                        <div class="flex items-center gap-1.5 text-slate-400">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                            <span class="text-[11px] font-medium">{{ $staff->email }}</span>
                        </div>
                        <div class="flex items-center gap-1.5 text-slate-400 sm:border-l sm:border-slate-100 sm:pl-4">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 8V7a2 2 0 012-2z" />
                            </svg>
                            <span class="text-[11px] font-medium">{{ $staff->phone ?? 'N/A' }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="flex items-center justify-center sm:justify-end gap-2 border-t sm:border-t-0 border-slate-50 pt-3 sm:pt-0">
                <button onclick="openEditModal()" class="flex items-center gap-2 px-4 py-2 text-slate-600 hover:bg-orange-50 hover:text-brand-800 rounded-xl transition-all text-xs font-bold uppercase tracking-widest">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                    </svg>
                    Edit Profile
                </button>
                <button type="button" onclick="openDeleteModal()" class="flex items-center gap-2 px-4 py-2 text-slate-400 hover:bg-rose-50 hover:text-rose-500 rounded-xl transition-all text-xs font-bold uppercase tracking-widest">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                    Delete
                </button>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div id="delete-modal" class="fixed inset-0 z-[120] hidden">
        <div class="absolute inset-0 bg-slate-900/40 backdrop-blur-sm transition-opacity" onclick="closeDeleteModal()"></div>
        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-full max-w-[380px] bg-white rounded-xl shadow-2xl overflow-hidden animate-in zoom-in-95 duration-200">
            <!-- Close Button -->
            <button onclick="closeDeleteModal()" class="absolute top-3 right-3 h-8 w-8 flex items-center justify-center text-slate-400 hover:text-slate-600 hover:bg-slate-100 rounded-lg transition-all z-10">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>

            <div class="p-5">
                <div class="flex items-center gap-3 mb-4">
                    <div class="h-12 w-12 bg-rose-50 text-rose-600 rounded-full flex items-center justify-center shrink-0 border border-rose-100">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold text-slate-800 tracking-tight">Delete Staff</h3>
                        <p class="text-[11px] font-semibold text-rose-600">Irreversible Action</p>
                    </div>
                </div>

                <p class="text-xs text-slate-600 mb-4 leading-relaxed">
                    Are you sure you want to delete the staff member "<span class="font-bold text-slate-900">{{ $staff->full_name }}</span>"?
                </p>

                <!-- Data Loss Warning Box -->
                <div class="bg-slate-50 rounded-xl p-4 mb-5 border border-slate-100">
                    <p class="text-[10px] font-bold text-slate-500 mb-2">Data to be permanently lost:</p>
                    <ul class="space-y-1.5">
                        <li class="flex items-center gap-2 text-[11px] text-slate-600 font-medium">
                            <svg class="w-3 h-3 text-rose-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                            Attendance records and history
                        </li>
                        <li class="flex items-center gap-2 text-[11px] text-slate-600 font-medium">
                            <svg class="w-3 h-3 text-rose-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                            Salary and payment documentation
                        </li>
                        <li class="flex items-center gap-2 text-[11px] text-slate-600 font-medium">
                            <svg class="w-3 h-3 text-rose-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                            Profile data and portal access
                        </li>
                    </ul>
                </div>

                <div class="flex items-center gap-3">
                    <form id="delete-form" action="{{ route('institute.staff.destroy', $staff->id) }}" method="POST" class="flex-1">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-full py-3 bg-rose-600 text-white rounded-lg text-xs font-bold hover:bg-rose-700 transition-all shadow-lg shadow-rose-600/20 active:scale-[0.98]">
                            Delete Staff
                        </button>
                    </form>
                    <button onclick="closeDeleteModal()" class="flex-1 py-3 bg-white border border-slate-200 text-slate-600 rounded-lg text-xs font-bold hover:bg-slate-50 transition-all active:scale-[0.98]">
                        Cancel
                    </button>
                </div>
            </div>

           
        </div>
    </div>
        </div>
    </div>

    <!-- Add/Edit Staff Modal (Same as index.blade.php) -->
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
                        <label class="block text-[11px] font-bold text-slate-800 mb-1.5 uppercase tracking-widest">Profile Image</label>
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
                            <label class="block text-[11px] font-bold text-slate-800 mb-1.5 uppercase tracking-widest">Full Name</label>
                            <input type="text" name="full_name" id="field-name" required placeholder="e.g. Jonathan Smith"
                                class="w-full px-4 py-2.5 bg-white border border-slate-200 rounded-lg text-xs font-medium focus:border-brand-800 outline-none transition-all placeholder:text-slate-300">
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-[11px] font-bold text-slate-800 mb-1.5 uppercase tracking-widest">Role</label>
                                <select name="staff_role_id" id="field-role" required
                                    class="w-full px-4 py-2.5 bg-white border border-slate-200 rounded-lg text-xs font-medium focus:border-brand-800 outline-none transition-all appearance-none cursor-pointer">
                                    <option value="">Select Role</option>
                                    @foreach(App\Models\StaffRole::where('institute_id', Auth::guard('institute')->id())->get() as $role)
                                        <option value="{{ $role->id }}">{{ $role->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-[11px] font-bold text-slate-800 mb-1.5 uppercase tracking-widest">Department</label>
                                <select name="staff_department_id" id="field-dept" required
                                    class="w-full px-4 py-2.5 bg-white border border-slate-200 rounded-lg text-xs font-medium focus:border-brand-800 outline-none transition-all appearance-none cursor-pointer">
                                    <option value="">Select Department</option>
                                    @foreach(App\Models\StaffDepartment::where('institute_id', Auth::guard('institute')->id())->get() as $dept)
                                        <option value="{{ $dept->id }}">{{ $dept->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-[11px] font-bold text-slate-800 mb-1.5 uppercase tracking-widest">Email Address</label>
                                <input type="email" name="email" id="field-email" required placeholder="j.smith@company.com"
                                    class="w-full px-4 py-2.5 bg-white border border-slate-200 rounded-lg text-xs font-medium focus:border-brand-800 outline-none transition-all placeholder:text-slate-300">
                            </div>
                            <div>
                                <label class="block text-[11px] font-bold text-slate-800 mb-1.5 uppercase tracking-widest">Phone Number</label>
                                <input type="text" name="phone" id="field-phone" required placeholder="+1 (555) 000-0000"
                                    class="w-full px-4 py-2.5 bg-white border border-slate-200 rounded-lg text-xs font-medium focus:border-brand-800 outline-none transition-all placeholder:text-slate-300">
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-[11px] font-bold text-slate-800 mb-1.5 uppercase tracking-widest">Employment Type</label>
                                <div class="flex p-1 bg-slate-100 rounded-lg w-full">
                                    <label class="flex-1 cursor-pointer">
                                        <input type="radio" name="employment_type" id="employment-salary" value="Salary"
                                            checked class="hidden peer">
                                        <div
                                            class="py-1.5 rounded-md text-[10px] font-bold text-slate-500 peer-checked:bg-white peer-checked:text-brand-800 peer-checked:shadow-sm transition-all text-center uppercase tracking-widest">
                                            Salary</div>
                                    </label>
                                    <label class="flex-1 cursor-pointer">
                                        <input type="radio" name="employment_type" id="employment-hourly" value="Hourly"
                                            class="hidden peer">
                                        <div
                                            class="py-1.5 rounded-md text-[10px] font-bold text-slate-500 peer-checked:bg-white peer-checked:text-brand-800 peer-checked:shadow-sm transition-all text-center uppercase tracking-widest">
                                            Hourly</div>
                                    </label>
                                </div>
                            </div>

                            <div>
                                <label class="block text-[11px] font-bold text-slate-800 mb-1.5 uppercase tracking-widest">Monthly Salary</label>
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
                            class="text-xs font-bold text-slate-400 hover:text-slate-600 transition-colors uppercase tracking-widest">Cancel</button>
                        <button type="submit" id="submit-btn"
                            class="px-8 py-2.5 bg-[#A8440B] text-white rounded-lg text-xs font-bold shadow-lg shadow-amber-900/20 hover:translate-y-[-1px] active:scale-95 transition-all uppercase tracking-widest">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
        </div>
    </div>


    <div class="grid grid-cols-1 lg:grid-cols-3 gap-2">
        <div class="lg:col-span-2 bg-white rounded-xl p-4 shadow-sm border border-slate-200 flex flex-col h-fit">
            <div class="flex flex-col xl:flex-row xl:items-center justify-between gap-4 mb-2">
                <div class="flex items-center gap-2">
                    <div class="h-8 w-8 bg-teal-50 text-teal-600 rounded-lg flex items-center justify-center">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-slate-800 tracking-tight">Attendance</h3>
                </div>

                <div class="flex flex-wrap items-center gap-4">
                    <div class="flex items-center bg-slate-100 rounded-lg px-2 py-1 w-full sm:w-auto justify-center sm:justify-start">
                        <select id="month-select" class="bg-transparent border-none text-[11px] font-bold text-slate-600 focus:ring-0 cursor-pointer py-1 px-2">
                            @php
                                $months = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
                                $currentMonth = date('F');
                            @endphp
                            @foreach($months as $month)
                                <option value="{{ $month }}" {{ $month == $currentMonth ? 'selected' : '' }}>{{ $month }}</option>
                            @endforeach
                        </select>
                        <div class="w-[1px] h-4 bg-slate-200 mx-1"></div>
                        <select id="year-select" class="bg-transparent border-none text-[11px] font-bold text-slate-600 focus:ring-0 cursor-pointer py-1 px-2">
                            @for($i = date('Y'); $i >= date('Y') - 5; $i--)
                                <option value="{{ $i }}">{{ $i }}</option>
                            @endfor
                        </select>
                    </div>
                    <div class="flex items-center gap-3 w-full sm:w-auto justify-center sm:justify-start">
                        <div class="flex items-center gap-1.5">
                            <div class="h-2 w-2 bg-emerald-500 rounded-full"></div>
                            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Present</span>
                        </div>
                        <div class="flex items-center gap-1.5">
                            <div class="h-2 w-2 bg-rose-500 rounded-full"></div>
                            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Absent</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Attendance Grid -->
            <div class="w-full">
                <div class="grid grid-cols-7 gap-1" id="attendance-calendar">
                    @foreach(['MON', 'TUE', 'WED', 'THU', 'FRI', 'SAT', 'SUN'] as $day)
                        <div class="text-[9px] font-bold text-slate-400 uppercase tracking-widest text-center py-1">{{ $day }}</div>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl p-6 shadow-sm border border-slate-200 flex flex-col">
            <div class="flex items-center gap-3 mb-6">
                <div class="h-8 w-8 bg-orange-50 text-orange-600 rounded-lg flex items-center justify-center">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-slate-800 tracking-tight">Salary History</h3>
            </div>

            <div class="space-y-4 mb-6 flex-1 overflow-y-auto max-h-[450px] pr-2 no-scrollbar">
                @forelse($staff->salaries->sortByDesc('payment_date') as $salary)
                    <div class="p-4 bg-slate-50 rounded-xl border border-slate-200 group/salary hover:bg-white hover:border-brand-800 transition-all duration-300">
                        <div class="flex items-center justify-between">
                            <div class="flex flex-col">
                                <h4 class="text-sm font-bold text-slate-800 mb-1">{{ date('F Y', strtotime($salary->payment_date)) }}</h4>
                                <p class="text-[11px] font-medium text-slate-400">Paid on {{ date('d M, Y', strtotime($salary->payment_date)) }}</p>
                            </div>
                            <div class="flex flex-col items-end gap-1">
                                <span class="text-sm font-bold text-slate-800">₹{{ number_format($salary->amount ?? $staff->base_salary, 2) }}</span>
                                <div class="flex items-center gap-1 px-2 py-0.5 {{ $salary->payment_method == 'Online' ? 'bg-blue-50 text-blue-500' : 'bg-emerald-50 text-emerald-500' }} rounded text-[9px] font-bold uppercase tracking-widest">
                                    @if($salary->payment_method == 'Online')
                                        <svg class="w-2.5 h-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z" /></svg>
                                    @else
                                        <svg class="w-2.5 h-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" /></svg>
                                    @endif
                                    {{ $salary->payment_method ?? 'CASH' }}
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="flex flex-col items-center justify-center py-10 text-center">
                        <div class="h-12 w-12 bg-slate-50 rounded-full flex items-center justify-center text-slate-200 mb-3">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <p class="text-[11px] font-bold text-slate-400">No payment records yet.</p>
                    </div>
                @endforelse
            </div>

            <button class="w-full py-4 bg-slate-100 text-slate-600 rounded-xl text-xs font-bold hover:bg-slate-200 transition-all flex items-center justify-center gap-2 shadow-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                Generate Payroll Report
            </button>
        </div>
    </div>
    </div>

    <style>
        .no-scrollbar::-webkit-scrollbar {
            display: none;
        }

        .no-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
    </style>

    <script>
        const API_URL = "{{ url('api/v1/institute/staff') }}";
        const CSRF_TOKEN = "{{ csrf_token() }}";
        const currentStaff = @json($staff);

        function openDeleteModal() {
            document.getElementById('delete-modal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeDeleteModal() {
            document.getElementById('delete-modal').classList.add('hidden');
            document.body.style.overflow = 'auto';
        }

        function openEditModal() {
            const staff = currentStaff;
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
        }

        function closeAddModal() {
            document.getElementById('add-staff-modal').classList.add('hidden');
            document.body.style.overflow = 'auto';
        }

        function previewImage(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('image-preview').src = e.target.result;
                }
                reader.readAsDataURL(input.files[0]);
            }
        }

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
                if (staffId) {
                    formData.append('_method', 'PUT');
                }

                const url = staffId ? `${API_URL}/${staffId}` : API_URL;
                const response = await fetch(url, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': CSRF_TOKEN
                    }
                });

                const result = await response.json();

                if (response.status === 422) {
                    // Handle validation errors if any
                    return;
                }

                if (result.status === 'success') {
                    // Show success message and reload
                    alert('Staff information updated successfully!');
                    window.location.href = window.location.pathname + '?v=' + new Date().getTime();
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

        const attendances = @json($staff->attendances);
        const monthSelect = document.getElementById('month-select');
        const yearSelect = document.getElementById('year-select');
        const calendarContainer = document.getElementById('attendance-calendar');

        function renderCalendar() {
            const month = monthSelect.value;
            const year = parseInt(yearSelect.value);
            const monthIndex = monthSelect.selectedIndex;

            // Clear existing dates (keep labels)
            const labels = calendarContainer.querySelectorAll('.tracking-widest');
            calendarContainer.innerHTML = '';
            labels.forEach(l => {
                if (l.innerText.length === 3) calendarContainer.appendChild(l);
            });

            const firstDay = new Date(year, monthIndex, 1).getDay();
            const daysInMonth = new Date(year, monthIndex + 1, 0).getDate();
            const prevMonthDays = new Date(year, monthIndex, 0).getDate();
            const adjustedFirstDay = firstDay === 0 ? 7 : firstDay;

            // Fill previous month days (greyed out)
            for (let i = 0; i < adjustedFirstDay - 1; i++) {
                const dayNum = new Date(year, monthIndex, 0).getDate() - (adjustedFirstDay - 2) + i;
                calendarContainer.innerHTML += `<div class="aspect-[2/1] flex items-center justify-center text-[10px] font-bold text-slate-200 bg-slate-50/30 rounded-lg">${dayNum}</div>`;
            }

            // Current month days
            const today = new Date();
            const isCurrentMonth = today.getMonth() === monthIndex && today.getFullYear() === year;
            const todayDate = today.getDate();

            for (let day = 1; day <= daysInMonth; day++) {
                const dateStr = `${year}-${String(monthIndex + 1).padStart(2, '0')}-${String(day).padStart(2, '0')}`;
                const attendance = attendances.find(a => a.date === dateStr);

                let statusClasses = 'bg-slate-50 text-slate-400 hover:bg-slate-100';
                if (attendance) {
                    if (attendance.status === 'Present') {
                        statusClasses = 'bg-green-100 text-green-700 border-2 border-green-500';
                    } else if (attendance.status === 'Absent') {
                        statusClasses = 'bg-red-500 text-white border-2 border-red-700';
                    }
                } else if (isCurrentMonth && day === todayDate) {
                    statusClasses = 'bg-orange-500 text-white';
                }

                calendarContainer.innerHTML += `
                        <div class="aspect-[2/1] flex items-center justify-center text-xs font-bold rounded-lg cursor-pointer transition-all duration-300 ${statusClasses}">
                            ${day}
                        </div>
                    `;
            }

            // Fill remaining spaces
            const totalSlots = 42; // 6 rows of 7
            const currentSlots = (adjustedFirstDay - 1) + daysInMonth;
            for (let i = 1; i <= totalSlots - currentSlots; i++) {
                calendarContainer.innerHTML += `<div class="aspect-[2/1] flex items-center justify-center text-[10px] font-medium text-slate-200 bg-slate-50/50 rounded-lg">${i}</div>`;
            }
        }

        monthSelect.addEventListener('change', renderCalendar);
        yearSelect.addEventListener('change', renderCalendar);

        // Initial render
        renderCalendar();
    </script>
@endsection