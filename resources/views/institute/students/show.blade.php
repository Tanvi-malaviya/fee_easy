@extends('layouts.institute')

@section('content')
    <div class="max-w-6xl mx-auto">
        <!-- Breadcrumb & Actions -->
        <div class="flex items-center justify-between mb-1">
            <a href="{{ route('institute.students.index') }}"
                class="flex items-center text-slate-400 hover:text-slate-600 font-bold text-sm transition-all group">
                <svg class="w-5 h-5 mr-2 group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
                Back to Students
            </a>
            <div class="flex items-center gap-3">
                <a href="{{ route('institute.students.edit', $student->id) }}"
                    class="px-6 py-2 bg-white border border-slate-100 text-slate-600 rounded-lg font-medium text-sm hover:bg-slate-50 transition-all flex items-center shadow-sm">
                    <svg class="w-4 h-4 mr-2 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                    </svg>
                    Edit Student
                </a>
                <button onclick="openDeleteModal()"
                    class="px-6 py-2 bg-white border border-slate-100 text-slate-600 rounded-lg font-medium text-sm hover:bg-rose-50 hover:text-rose-600 transition-all flex items-center shadow-sm">
                    <svg class="w-4 h-4 mr-2 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                    Delete Student
                </button>
            </div>
        </div>

        <!-- Main Content Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 mb-4">
            <!-- Profile Header Card (2/3) -->
            <div class="lg:col-span-2 bg-white rounded-xl border border-slate-100 shadow-sm p-4 h-full flex flex-col justify-center">
                <div class="flex flex-col md:flex-row items-center gap-4">
                    <div class="relative">
                        <div class="h-20 w-20 rounded-xl bg-slate-50 overflow-hidden border-2 border-white shadow-md">
                            <img src="{{ $student->profile_image_url }}" class="w-full h-full object-cover">
                        </div>
                        <div
                            class="absolute -bottom-1 -left-1 bg-emerald-500 text-white px-2 py-0.5 rounded-lg text-[8px] font-bold uppercase tracking-widest border-2 border-white shadow-sm">
                            <div class="flex items-center gap-1">
                                <svg class="w-2.5 h-2.5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                        clip-rule="evenodd" />
                                </svg>
                                {{ $student->status == 1 ? 'Enrolled' : 'Inactive' }}
                            </div>
                        </div>
                    </div>

                    <div class="flex-1 text-center md:text-left">
                        <h1 class="text-xl font-semibold text-slate-700 tracking-tight">{{ $student->name }}</h1>
                        <p class="text-[10px] text-slate-400 mt-0.5 font-semibold uppercase tracking-widest">Student ID: <span
                                class="text-slate-500 font-bold">TU-{{ $student->created_at->format('Y') }}-{{ str_pad($student->id, 4, '0', STR_PAD_LEFT) }}</span>
                        </p>

                        <div class="flex flex-wrap items-center justify-center md:justify-start gap-2 mt-4">
                            <div class="bg-slate-50 rounded-xl px-3 py-2 border border-slate-100 min-w-[90px]">
                                <p class="text-[8px] font-bold text-slate-400 uppercase tracking-widest mb-0.5">Avg Grade
                                </p>
                                <p class="text-sm font-bold text-slate-700">{{ $averageGrade }}/10</p>
                            </div>
                            <div class="bg-emerald-50/30 rounded-xl px-3 py-2 border border-emerald-100 min-w-[90px]">
                                <p class="text-[8px] font-bold text-emerald-600/50 uppercase tracking-widest mb-0.5">Payment
                                    Status</p>
                                <p class="text-sm font-bold text-emerald-700">
                                    {{ $balance > 0 ? 'Partial Dues' : 'Full Paid' }}</p>
                            </div>
                            <div class="bg-blue-50/30 rounded-xl px-3 py-2 border border-blue-100 min-w-[90px]">
                                <p class="text-[8px] font-bold text-blue-600/50 uppercase tracking-widest mb-0.5">Attendance
                                </p>
                                <p class="text-sm font-bold text-blue-600">{{ $attendancePercentage }}%</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Fee Balance Card (1/3) -->
            <div
                class="lg:col-span-1 bg-white rounded-xl border border-slate-100 shadow-sm p-4 relative overflow-hidden flex flex-col h-full">
                <div class="absolute top-0 right-0 w-12 h-12 bg-blue-500/5 rounded-full -mr-6 -mt-6"></div>

                <div class="flex items-center gap-2 mb-3">
                    <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                            d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                    <h2 class="text-sm font-bold text-slate-700 tracking-tight uppercase">Fee Balance</h2>
                </div>

                <div class="mb-3 bg-slate-50 p-3 rounded-xl border border-slate-100">
                    <p class="text-[8px] font-bold text-slate-400 uppercase tracking-widest mb-0.5">Total Outstanding</p>
                    <div class="flex items-baseline gap-1.5">
                        <span
                            class="text-2xl font-bold text-slate-700 tracking-tighter">₹{{ number_format($balance) }}</span>
                        <span class="text-[8px] font-bold text-slate-400">/
                            ₹{{ number_format($student->monthly_fee * 12) }} Total</span>
                    </div>
                </div>

                <div class="space-y-1.5 mb-3 flex-1">
                    <div class="flex justify-between text-[10px]">
                        <span class="font-bold text-slate-400 uppercase">Standard</span>
                        <span class="font-bold text-slate-600">{{ $student->standard }}</span>
                    </div>
                    <div class="flex justify-between text-[10px]">
                        <span class="font-bold text-slate-400 uppercase">Monthly Fee</span>
                        <span class="font-bold text-slate-600">₹{{ number_format($student->monthly_fee) }}</span>
                    </div>
                </div>

                <button
                    class="w-full py-2 bg-[#ff6600] text-white rounded-lg font-bold text-[10px] uppercase tracking-widest shadow-lg shadow-orange-900/10 hover:translate-y-[-1px] transition-all flex items-center justify-center">
                    <svg class="w-3.5 h-3.5 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    View Receipts
                </button>
            </div>
        </div>

        <!-- Academic & Contact Information (Full Width) -->
        <div class="bg-white rounded-xl border border-slate-100 shadow-sm p-4 mb-4">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-1.5 h-4 bg-blue-600 rounded-full"></div>
                <h2 class="text-base font-semibold text-slate-700 tracking-tight">Academic & Contact Information</h2>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-y-4 gap-x-8">
                <div>
                    <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">Batch Name</p>
                    <p class="text-sm font-semibold text-slate-600 leading-tight">
                        {{ $student->batch ? $student->batch->name : 'N/A' }}</p>
                </div>
                <div>
                    <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">Date of Admission</p>
                    <p class="text-sm font-semibold text-slate-600 leading-tight">
                        {{ $student->created_at->format('M d, Y') }}</p>
                </div>
                <div>
                    <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">Guardian Name</p>
                    <p class="text-sm font-semibold text-slate-600 leading-tight">{{ $student->guardian_name ?: 'N/A' }}</p>
                </div>
                <div>
                    <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">Phone Number</p>
                    <p class="text-sm font-semibold text-slate-600 leading-tight">+91 {{ $student->phone }}</p>
                </div>
                <div class="lg:col-span-4 pt-3 border-t border-slate-50">
                    <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">Residential Address</p>
                    <p class="text-sm font-medium text-slate-600 leading-relaxed max-w-3xl">
                        {{ $student->address_line_1 }}@if($student->address_line_2), {{ $student->address_line_2 }} @endif,
                        {{ $student->city }}@if($student->state), {{ $student->state }} @endif @if($student->pincode) -
                        {{ $student->pincode }} @endif
                    </p>
                </div>
            </div>
        </div>

        <!-- Quick Stats -->
        <!-- <div class="bg-white rounded-xl border border-slate-100 shadow-sm p-5">
                    <div class="flex items-center gap-3 mb-6">
                        <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                        <h2 class="text-lg font-medium text-slate-800 tracking-tight">Quick Stats</h2>
                    </div>

                    <div class="space-y-6 mb-6">
                        <div class="flex items-center gap-4">
                            <div class="h-9 w-9 bg-slate-50 text-slate-400 rounded-lg flex items-center justify-center border border-slate-100">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                            </div>
                            <div class="flex-1">
                                <div class="flex justify-between items-center mb-1.5">
                                    <span class="text-[9px] font-medium text-slate-400 uppercase tracking-widest">Overall GPA</span>
                                    <span class="text-xs font-medium text-slate-700">8.4 / 10</span>
                                </div>
                                <div class="h-1.5 bg-slate-50 rounded-full overflow-hidden border border-slate-100">
                                    <div class="h-full bg-emerald-500 rounded-full" style="width: 84%"></div>
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center gap-4">
                            <div class="h-9 w-9 bg-slate-50 text-slate-400 rounded-lg flex items-center justify-center border border-slate-100">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                            </div>
                            <div class="flex-1">
                                <div class="flex justify-between items-center mb-1.5">
                                    <span class="text-[9px] font-medium text-slate-400 uppercase tracking-widest">Assignments Done</span>
                                    <span class="text-xs font-medium text-slate-700">18 / 20</span>
                                </div>
                                <div class="h-1.5 bg-slate-50 rounded-full overflow-hidden border border-slate-100">
                                    <div class="h-full bg-blue-500 rounded-full" style="width: 90%"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <button class="w-full py-2.5 bg-white border border-slate-200 text-slate-600 rounded-xl font-medium text-[13px] hover:bg-slate-50 transition-all shadow-sm">
                        Detailed Progress Report
                    </button>
                </div> -->
    </div>
    </div>



    </div>

    <!-- Delete Modal -->
    <div id="delete-modal" class="fixed inset-0 z-[110] flex items-center justify-center hidden px-4">
        <div onclick="closeDeleteModal()" class="absolute inset-0 bg-slate-900/40 backdrop-blur-sm"></div>
        <div
            class="bg-white w-full max-w-md rounded-2xl shadow-2xl relative z-10 overflow-hidden animate-in fade-in zoom-in duration-300">
            <!-- Top Accent Border -->
            <div class="h-1 bg-rose-600 w-full"></div>

            <div class="p-6">
                <div class="flex items-start gap-4 mb-5">
                    <div class="h-10 w-10 bg-rose-50 rounded-full flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 text-rose-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                                clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold text-slate-800 mb-2">Delete Student?</h3>
                        <p class="text-sm text-slate-500 leading-relaxed">
                            Are you sure you want to permanently remove <span
                                class="font-bold text-slate-800">{{ $student->name }}</span>? This action cannot be undone
                            and will erase all academic and financial history.
                        </p>
                    </div>
                </div>

                <div class="flex items-center gap-3">
                    <button onclick="closeDeleteModal()"
                        class="flex-1 py-2.5 border-2 border-teal-600 text-teal-600 rounded-xl font-semibold text-sm hover:bg-teal-50 transition-all text-center">
                        Cancel
                    </button>
                    <form id="delete-form" action="{{ route('institute.students.destroy', $student->id) }}" method="POST"
                        class="flex-1">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                            class="w-full py-2.5 bg-[#be1e1e] text-white rounded-xl font-semibold text-sm shadow-lg shadow-rose-900/10 hover:bg-rose-800 transition-all">
                            Yes, Delete Student
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function openDeleteModal() {
            document.getElementById('delete-modal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }
        function closeDeleteModal() {
            document.getElementById('delete-modal').classList.add('hidden');
            document.body.style.overflow = 'auto';
        }
    </script>

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Outfit:wght@100;300;400;500;600;700;800;900&display=swap');

        :root {
            --font-outfit: 'Outfit', sans-serif;
        }

        body {
            font-family: var(--font-outfit);
            background-color: #f8fafc;
        }
    </style>
@endsection