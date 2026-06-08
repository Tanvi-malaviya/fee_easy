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

            <div class="flex flex-col sm:flex-row items-stretch sm:items-center justify-center sm:justify-end gap-3 border-t sm:border-t-0 border-slate-50 pt-3 sm:pt-0 w-full sm:w-auto">
                <button onclick="openEditModal()" 
                    class="flex items-center justify-center gap-2 px-5 py-2 border-2 border-teal-600 text-teal-600 rounded-xl hover:bg-teal-50 transition-all text-xs font-bold w-full sm:w-auto">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                    Edit
                </button>
                <button type="button" onclick="openDeleteModal()" 
                    class="flex items-center justify-center gap-2 px-5 py-2 border-2 border-rose-600 text-rose-600 rounded-xl hover:bg-rose-50 transition-all text-xs font-bold w-full sm:w-auto">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                    Delete Profile
                </button>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Form -->
    <form id="delete-form" action="{{ route('institute.staff.destroy', $staff->id) }}" method="POST" class="hidden">
        @csrf
        @method('DELETE')
    </form>

    <!-- Add/Edit Staff Modal (Same as index.blade.php) -->
    <div id="add-staff-modal" class="fixed inset-0 z-[100] hidden overflow-y-auto">
        <div class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm transition-opacity" onclick="closeAddModal()"></div>
        <div class="relative min-h-screen flex items-center justify-center p-4">
            <div
                class="relative w-full max-w-[600px] bg-white rounded-[1.5rem] shadow-2xl border border-slate-100 overflow-hidden animate-in zoom-in-95 duration-300 max-h-[95vh] flex flex-col">
            <!-- Modal Header -->
            <div class="px-6 py-4 bg-gradient-to-r from-[#e05f00] via-[#ff6c00] to-[#ff9f43] flex items-center justify-between shrink-0">
                <h1 id="modal-title" class="text-base font-bold text-white tracking-tight">Add Staff Member</h1>
                <button type="button" onclick="closeAddModal()" class="h-8 w-8 flex items-center justify-center rounded-full hover:bg-white/10 text-white/80 hover:text-white transition-all">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <div class="overflow-y-auto px-6 pb-6 pt-4 custom-scrollbar">
                <form id="add-staff-form" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="staff_id" id="staff_id">

                    <!-- Profile Image -->
                    <div class="mb-3">
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
                            <div>
                                <p class="text-[10px] text-slate-400 max-w-[200px]">Upload a professional headshot. Max size 2MB.</p>
                                <span id="error-profile_image" class="text-[10px] text-rose-500 font-bold mt-1 block"></span>
                            </div>
                        </div>
                    </div>

                    <div class="space-y-3">
                        <div>
                            <label class="block text-[11px] font-bold text-slate-800 mb-1.5 uppercase tracking-widest">Full Name</label>
                            <input type="text" name="full_name" id="field-name" required placeholder="e.g. Jonathan Smith"
                                class="w-full px-4 py-2.5 bg-white border border-slate-200 rounded-lg text-xs font-medium focus:border-brand-800 outline-none transition-all placeholder:text-slate-300">
                            <span id="error-full_name" class="text-[10px] text-rose-500 font-bold mt-1 block"></span>
                        </div>

                        <div class="relative">
                            <label class="block text-[11px] font-bold text-slate-800 mb-1.5 uppercase tracking-widest">Department</label>
                            <button type="button" onclick="toggleModalDropdown('dept')"
                                class="w-full px-4 py-2.5 bg-white border border-slate-200 rounded-lg text-xs font-medium text-left flex items-center justify-between hover:border-brand-800 transition-all">
                                <span id="modal-dept-label" class="text-slate-400">Select Department</span>
                                <svg id="modal-dept-chevron" class="w-4 h-4 text-slate-400 transition-transform"
                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                        d="M19 9l-7 7-7-7" />
                                    </svg>
                            </button>
                            <div id="modal-dept-menu"
                                class="absolute z-[110] mt-1 w-full bg-white border border-slate-200 rounded-lg shadow-xl overflow-hidden hidden transform origin-top transition-all">
                                <div class="py-1 max-h-48 overflow-y-auto custom-scrollbar">
                                    @foreach(App\Models\StaffDepartment::orderBy('name')->get() as $dept)
                                        <button type="button"
                                            onclick="selectModalOption('dept', '{{ $dept->id }}', '{{ $dept->name }}')"
                                            class="w-full text-left px-4 py-2 text-xs font-medium text-slate-600 hover:bg-slate-50 hover:text-brand-800 transition-colors">
                                            {{ $dept->name }}
                                        </button>
                                    @endforeach
                                </div>
                            </div>
                            <input type="hidden" name="staff_department_id" id="field-dept-id" required>
                            <span id="error-staff_department_id" class="text-[10px] text-rose-500 font-bold mt-1 block"></span>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-[11px] font-bold text-slate-800 mb-1.5 uppercase tracking-widest">Email Address</label>
                                <input type="email" name="email" id="field-email" required placeholder="j.smith@company.com"
                                    class="w-full px-4 py-2.5 bg-white border border-slate-200 rounded-lg text-xs font-medium focus:border-brand-800 outline-none transition-all placeholder:text-slate-300">
                                <span id="error-email" class="text-[10px] text-rose-500 font-bold mt-1 block"></span>
                            </div>
                            <div>
                                <label class="block text-[11px] font-bold text-slate-800 mb-1.5 uppercase tracking-widest">Phone Number</label>
                                <input type="text" name="phone" id="field-phone" required placeholder="Enter Phone Number" maxlength="10"
                                    oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                                    class="w-full px-4 py-2.5 bg-white border border-slate-200 rounded-lg text-xs font-medium focus:border-brand-800 outline-none transition-all placeholder:text-slate-300">
                                <span id="error-phone" class="text-[10px] text-rose-500 font-bold mt-1 block"></span>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
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
                                <span id="error-base_salary" class="text-[10px] text-rose-500 font-bold mt-1 block"></span>
                            </div>
                        </div>
                    </div>

                    <!-- Modal Footer -->
                    <div class="flex items-center justify-end gap-3 mt-5">
                        <button type="button" onclick="closeAddModal()"
                            class="text-xs font-bold text-slate-400 hover:text-slate-600 transition-colors uppercase tracking-widest">Cancel</button>
                        <button type="submit" id="submit-btn"
                            class="px-6 py-2 bg-primary text-white rounded-lg text-xs font-bold shadow-lg shadow-amber-900/20 hover:translate-y-[-1px] active:scale-95 transition-all uppercase tracking-widest">Save Changes</button>
                    </div>
                </form>
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
                    <div class="relative w-full sm:w-auto flex items-center gap-2">
                        <div class="relative">
                            <button onclick="document.getElementById('month-picker').showPicker()"
                                class="bg-slate-100 hover:bg-slate-200 transition-all rounded-lg px-3 py-1.5 flex items-center justify-center gap-2 cursor-pointer w-full sm:w-auto">
                                <span id="current-month-display" class="text-[11px] font-bold text-slate-600"></span>
                                <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>
                            <input type="month" id="month-picker" class="absolute inset-0 opacity-0 pointer-events-none"
                                onchange="handleMonthChange(this.value)">
                        </div>
                        <button onclick="goToCurrentMonth()"
                            class="bg-orange-50 hover:bg-orange-100 text-[#ff6c00] text-[11px] font-bold px-3 py-1.5 rounded-lg border border-orange-100 transition-all cursor-pointer flex items-center gap-1.5 shadow-sm">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            This Month
                        </button>
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

            <div id="salary-history-container" class="space-y-4 mb-6 flex-1 overflow-y-auto max-h-[450px] pr-2 no-scrollbar">
                <div class="flex flex-col items-center justify-center py-10 text-center animate-pulse">
                    <div class="h-8 w-8 border-3 border-slate-100 border-t-brand-800 rounded-full animate-spin mx-auto mb-3"></div>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Loading Records...</p>
                </div>
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

        /* Hide number input spinners */
        input::-webkit-outer-spin-button,
        input::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }
        input[type=number] {
            -moz-appearance: textfield;
        }
    </style>

    <script>
        const API_URL = "{{ url('api/v1/institute/staff') }}";
        const CSRF_TOKEN = "{{ csrf_token() }}";
        const currentStaff = @json($staff);

        function openDeleteModal() {
            showConfirmModal(
                'Delete Staff',
                `<p class="text-xs text-slate-600 mb-4 leading-relaxed">
                    Are you sure you want to delete the staff member "<span class="font-bold text-slate-900">${currentStaff.full_name}</span>"?
                </p>
                <div class="bg-slate-50 rounded-xl p-4 mb-2 border border-slate-100 text-left">
                    <p class="text-[10px] font-bold text-slate-500 mb-2">Data to be permanently lost:</p>
                    <ul class="space-y-1.5">
                        <li class="flex items-center gap-2 text-[11px] text-slate-600 font-medium">
                            <svg class="w-3 h-3 text-rose-500/60" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12" /></svg>
                            Attendance records and history
                        </li>
                        <li class="flex items-center gap-2 text-[11px] text-slate-600 font-medium">
                            <svg class="w-3 h-3 text-rose-500/60" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12" /></svg>
                            Salary and payment documentation
                        </li>
                        <li class="flex items-center gap-2 text-[11px] text-slate-600 font-medium">
                            <svg class="w-3 h-3 text-rose-500/60" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12" /></svg>
                            Profile data and portal access
                        </li>
                    </ul>
                </div>`,
                function() {
                    document.getElementById('delete-form').submit();
                },
                'Delete Staff',
                'bg-rose-600 shadow-rose-900/20',
                null,
                'Irreversible Action',
                'rose'
            );
        }

        function openEditModal() {
            const staff = currentStaff;
            document.getElementById('modal-title').innerText = 'Edit Staff Member';
            document.getElementById('add-staff-form').reset();

            document.getElementById('staff_id').value = staff.id;
            document.getElementById('field-name').value = staff.full_name;
            
            // Set custom dropdown values
            if (staff.department) {
                selectModalOption('dept', staff.staff_department_id, staff.department.name);
            }

            document.getElementById('field-email').value = staff.email;
            document.getElementById('field-phone').value = staff.phone || '';

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
        }

        function closeAddModal() {
            document.getElementById('add-staff-modal').classList.add('hidden');
            document.body.style.overflow = 'auto';
        }

        function previewImage(input) {
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

                var reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('image-preview').src = e.target.result;
                }
                reader.readAsDataURL(file);
            }
        }

        // Custom Dropdown Functions
        function toggleModalDropdown(type) {
            const menu = document.getElementById(`modal-${type}-menu`);
            const chevron = document.getElementById(`modal-${type}-chevron`);

            // Close other menus
            ['role', 'dept'].forEach(t => {
                if (t !== type) {
                    document.getElementById(`modal-${t}-menu`).classList.add('hidden');
                    document.getElementById(`modal-${t}-chevron`).classList.remove('rotate-180');
                }
            });

            menu.classList.toggle('hidden');
            chevron.classList.toggle('rotate-180');
        }

        function selectModalOption(type, id, name) {
            document.getElementById(`field-${type}-id`).value = id;
            document.getElementById(`modal-${type}-label`).textContent = name;
            document.getElementById(`modal-${type}-label`).classList.remove('text-slate-400');
            document.getElementById(`modal-${type}-label`).classList.add('text-slate-700');
            document.getElementById(`modal-${type}-menu`).classList.add('hidden');
            document.getElementById(`modal-${type}-chevron`).classList.remove('rotate-180');
        }

        // Close menus when clicking outside
        document.addEventListener('click', (e) => {
            ['role', 'dept'].forEach(type => {
                const button = document.getElementById(`modal-${type}-label`)?.parentElement;
                const menu = document.getElementById(`modal-${type}-menu`);
                if (button && !button.contains(e.target) && !menu.contains(e.target)) {
                    menu.classList.add('hidden');
                    document.getElementById(`modal-${type}-chevron`).classList.remove('rotate-180');
                }
            });
        });

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
                    showToast('Staff information updated successfully!', 'success');
                    setTimeout(() => {
                        window.location.href = window.location.pathname;
                    }, 1000);
                } else {
                    showToast(result.message || 'Something went wrong', 'error');
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Something went wrong. Please try again.');
            } finally {
                submitBtn.disabled = false;
                submitBtn.innerText = originalText;
            }
        });

        const staffId = "{{ $staff->id }}";
        let currentAttendances = [];

        const monthNames = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
        const today = new Date();
        let selectedYear = today.getFullYear();
        let selectedMonthIndex = today.getMonth(); // 0-indexed

        // Set month picker initial value on DOM load
        document.addEventListener('DOMContentLoaded', () => {
            const monthPicker = document.getElementById('month-picker');
            if (monthPicker) {
                monthPicker.value = `${selectedYear}-${String(selectedMonthIndex + 1).padStart(2, '0')}`;
            }
            const monthDisplay = document.getElementById('current-month-display');
            if (monthDisplay) {
                monthDisplay.textContent = `${monthNames[selectedMonthIndex]} ${selectedYear}`;
            }
        });

        function handleMonthChange(value) {
            if (!value) return;
            const parts = value.split('-');
            selectedYear = parseInt(parts[0]);
            selectedMonthIndex = parseInt(parts[1]) - 1;
            
            const monthDisplay = document.getElementById('current-month-display');
            if (monthDisplay) {
                monthDisplay.textContent = `${monthNames[selectedMonthIndex]} ${selectedYear}`;
            }
            fetchAttendance();
        }

        function goToCurrentMonth() {
            const now = new Date();
            selectedYear = now.getFullYear();
            selectedMonthIndex = now.getMonth();
            
            const monthPicker = document.getElementById('month-picker');
            if (monthPicker) {
                monthPicker.value = `${selectedYear}-${String(selectedMonthIndex + 1).padStart(2, '0')}`;
            }
            const monthDisplay = document.getElementById('current-month-display');
            if (monthDisplay) {
                monthDisplay.textContent = `${monthNames[selectedMonthIndex]} ${selectedYear}`;
            }
            fetchAttendance();
        }

        async function fetchAttendance() {
            const monthIndex = selectedMonthIndex + 1;
            const year = selectedYear;
            
            calendarContainer.innerHTML = '<div class="col-span-7 py-10 text-center"><div class="h-6 w-6 border-2 border-slate-100 border-t-brand-800 rounded-full animate-spin mx-auto mb-2"></div><p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest">Updating...</p></div>';

            try {
                const response = await fetch(`/api/v1/institute/attendance/${staffId}?month=${monthIndex}&year=${year}`, {
                    headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': CSRF_TOKEN }
                });
                const result = await response.json();
                currentAttendances = result.data || [];
                renderCalendar();
            } catch (error) {
                console.error('Error fetching attendance:', error);
            }
        }

        async function fetchSalaries() {
            const salaryContainer = document.getElementById('salary-history-container');
            
            try {
                const response = await fetch(`/api/v1/institute/salaries/${staffId}`, {
                    headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': CSRF_TOKEN }
                });
                const result = await response.json();
                const salaries = result.data || [];

                if (salaries.length === 0) {
                    salaryContainer.innerHTML = `
                        <div class="flex flex-col items-center justify-center py-10 text-center">
                            <div class="h-12 w-12 bg-slate-50 rounded-full flex items-center justify-center text-slate-200 mb-3">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                            </div>
                            <p class="text-[11px] font-bold text-slate-400 uppercase tracking-widest">No payment records yet.</p>
                        </div>
                    `;
                    return;
                }

                salaryContainer.innerHTML = salaries.map(salary => `
                    <div class="p-4 bg-slate-50 rounded-xl border border-slate-200 group/salary hover:bg-white hover:border-brand-800 transition-all duration-300">
                        <div class="flex items-center justify-between">
                            <div class="flex flex-col">
                                <h4 class="text-sm font-bold text-slate-800 mb-1">${new Date(salary.payment_date).toLocaleDateString('en-US', { month: 'long', year: 'numeric' })}</h4>
                                <p class="text-[11px] font-medium text-slate-400">Paid on ${new Date(salary.payment_date).toLocaleDateString('en-US', { day: 'numeric', month: 'short', year: 'numeric' })}</p>
                            </div>
                            <div class="flex flex-col items-end gap-1">
                                <span class="text-sm font-bold text-slate-800">₹${parseFloat(salary.net_salary).toLocaleString('en-IN', { minimumFractionDigits: 2 })}</span>
                                <div class="flex items-center gap-1 px-2 py-0.5 ${salary.payment_method === 'Online' ? 'bg-blue-50 text-blue-500' : 'bg-emerald-50 text-emerald-500'} rounded text-[9px] font-bold uppercase tracking-widest">
                                    ${salary.payment_method === 'Online' ? '<svg class="w-2.5 h-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z" /></svg>' : '<svg class="w-2.5 h-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" /></svg>'}
                                    ${salary.payment_method || 'CASH'}
                                </div>
                            </div>
                        </div>
                    </div>
                `).join('');
            } catch (error) {
                console.error('Error fetching salaries:', error);
            }
        }

        const calendarContainer = document.getElementById('attendance-calendar');

        function renderCalendar() {
            const year = selectedYear;
            const monthIndex = selectedMonthIndex;

            calendarContainer.innerHTML = '';
            ['MON', 'TUE', 'WED', 'THU', 'FRI', 'SAT', 'SUN'].forEach(day => {
                calendarContainer.innerHTML += `<div class="text-[9px] font-bold text-slate-400 uppercase tracking-widest text-center py-1">${day}</div>`;
            });

            const firstDay = new Date(year, monthIndex, 1).getDay();
            const daysInMonth = new Date(year, monthIndex + 1, 0).getDate();
            const adjustedFirstDay = firstDay === 0 ? 7 : firstDay;

            for (let i = 0; i < adjustedFirstDay - 1; i++) {
                const dayNum = new Date(year, monthIndex, 0).getDate() - (adjustedFirstDay - 2) + i;
                calendarContainer.innerHTML += `<div class="aspect-[2/1] flex items-center justify-center text-[10px] font-bold text-slate-200 bg-slate-50/30 rounded-lg">${dayNum}</div>`;
            }

            const today = new Date();
            const isCurrentMonth = today.getMonth() === monthIndex && today.getFullYear() === year;
            const todayDate = today.getDate();

            for (let day = 1; day <= daysInMonth; day++) {
                const dateStr = `${year}-${String(monthIndex + 1).padStart(2, '0')}-${String(day).padStart(2, '0')}`;
                const attendance = currentAttendances.find(a => a.date === dateStr);

                let statusClasses = 'bg-slate-50 text-slate-400 hover:bg-slate-100';
                if (attendance) {
                    if (attendance.status === 'Present') {
                        statusClasses = 'bg-green-100 text-green-700 border-2 border-green-500';
                    } else if (attendance.status === 'Absent') {
                        statusClasses = 'bg-red-500 text-white border-2 border-red-700';
                    } else if (attendance.status === 'Half Day') {
                        statusClasses = 'bg-amber-100 text-amber-700 border-2 border-amber-500';
                    } else if (attendance.status === 'Late') {
                        statusClasses = 'bg-sky-100 text-sky-700 border-2 border-sky-500';
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

            const totalSlots = 42; 
            const currentSlots = (adjustedFirstDay - 1) + daysInMonth;
            for (let i = 1; i <= totalSlots - currentSlots; i++) {
                calendarContainer.innerHTML += `<div class="aspect-[2/1] flex items-center justify-center text-[10px] font-medium text-slate-200 bg-slate-50/50 rounded-lg">${i}</div>`;
            }
        }

        // Initial fetch
        fetchAttendance();
        fetchSalaries();
    </script>
@endsection