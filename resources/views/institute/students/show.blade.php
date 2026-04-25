@extends('layouts.institute')

@section('content')
    <div class="max-w-7xl mx-auto pb-20">
        <!-- Header Section -->
        <div class="mb-3">
            <div class="flex items-center gap-3 mb-4 mt-2">
                <a href="{{ route('institute.students.index') }}" onclick="if(document.referrer.indexOf(window.location.host) !== -1) { event.preventDefault(); window.history.back(); }"
               class="h-10 w-10 bg-white border border-slate-200 rounded-xl flex items-center justify-center text-slate-400 hover:text-blue-600 hover:border-blue-300 transition-all shadow-sm group">
                    <svg class="w-4 h-4 group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                </a>
                <div>
                    <h1 class="text-4xl font-black text-slate-900 tracking-tight">{{ $student->name }}</h1>
                    <p class="text-sm text-slate-500 mt-1 font-medium">Student ID: <span
                            class="font-bold text-slate-700">STU-{{ str_pad($student->id, 4, '0', STR_PAD_LEFT) }}</span>
                    </p>
                </div>
            </div>

            <div class="flex items-center gap-2 flex-wrap">
                <a href="{{ route('institute.students.edit', $student->id) }}"
                    class="px-5 py-2 bg-blue-600 text-white rounded-lg font-bold text-[12px] shadow-md hover:bg-blue-700 transition-all flex items-center gap-2 uppercase tracking-wide">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                    </svg>
                    Edit
                </a>
                <button onclick="openDeleteModal({{ $student->id }})"
                    class="px-5 py-2 bg-rose-600 text-white rounded-lg font-bold text-[12px] shadow-md hover:bg-rose-700 transition-all flex items-center gap-2 uppercase tracking-wide">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                    Delete
                </button>
            </div>
        </div>

        <!-- Quick Stats Row -->
        <div class="grid grid-cols-1 sm:grid-cols-4 gap-4 mb-4">
            <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-xl p-4 border border-blue-200">
                <p class="text-[10px] font-bold text-blue-600 uppercase tracking-wide">Standard</p>
                <p class="text-2xl font-black text-blue-900 mt-1">{{ $student->standard ?: 'N/A' }}</p>
            </div>
            <div class="bg-gradient-to-br from-emerald-50 to-emerald-100 rounded-xl p-4 border border-emerald-200">
                <p class="text-[10px] font-bold text-emerald-600 uppercase tracking-wide">Status</p>
                <p class="text-2xl font-black {{ $student->status == 1 ? 'text-emerald-900' : 'text-rose-900' }} mt-1">
                    {{ $student->status == 1 ? 'Active' : 'Inactive' }}</p>
            </div>
            <div class="bg-gradient-to-br from-amber-50 to-amber-100 rounded-xl p-4 border border-amber-200">
                <p class="text-[10px] font-bold text-amber-600 uppercase tracking-wide">Batch</p>
                <p class="text-xl font-black text-amber-900 mt-1 truncate">
                    {{ $student->batch ? $student->batch->name : 'None' }}</p>
            </div>
            <div class="bg-gradient-to-br from-purple-50 to-purple-100 rounded-xl p-4 border border-purple-200">
                <p class="text-[10px] font-bold text-purple-600 uppercase tracking-wide"> Fee</p>
                <p class="text-xl font-black text-purple-900 mt-1">₹{{ number_format($student->monthly_fee, 0) }}</p>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-2">
            <!-- Sidebar Column -->
            <div class="lg:col-span-1 space-y-2">
                <!-- Student Profile Card -->
                <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden shadow-md">
                    <div class="h-24 bg-gradient-to-r from-blue-600 to-blue-700"></div>

                    <div class="px-6 py-8 text-center -mt-12 relative">
                        <div class="relative inline-block mb-4">
                            <div
                                class="h-24 w-24 rounded-xl bg-slate-100 border-4 border-white p-1 shadow-lg mx-auto overflow-hidden">
                                @if($student->profile_image)
                                    <img src="{{ asset('storage/' . $student->profile_image) }}" alt="{{ $student->name }}"
                                        class="w-full h-full object-cover rounded-lg">
                                @else
                                    <img src="https://ui-avatars.com/api/?name={{ urlencode($student->name) }}&background=1e3a8a&color=fff&size=256&bold=true"
                                        class="w-full h-full object-cover rounded-lg">
                                @endif
                            </div>
                            <div
                                class="absolute -bottom-2 -right-2 h-6 w-6 {{ $student->status == 1 ? 'bg-emerald-500' : 'bg-rose-500' }} border-4 border-white rounded-full shadow-md">
                            </div>
                        </div>

                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-4 mb-1">Joined On</p>
                        <p class="text-sm font-bold text-slate-600">{{ $student->created_at->format('M d, Y') }}</p>
                    </div>
                </div>

                <!-- Fee Balance Card -->
                <div
                    class="bg-gradient-to-br from-slate-900 to-slate-800 rounded-2xl p-8 shadow-xl border border-slate-700 relative overflow-hidden">
                    <div class="absolute top-0 right-0 w-40 h-40 bg-blue-500/10 rounded-full -mr-20 -mt-20"></div>

                    <div class="relative z-10">
                        <div class="flex items-center justify-between mb-6">
                            <h4 class="text-white/70 text-[11px] font-black uppercase tracking-widest">Fee Balance</h4>
                            <div class="h-10 w-10 bg-blue-500/20 rounded-lg flex items-center justify-center text-blue-300">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                        </div>

                        <div class="mb-8">
                            <span
                                class="text-5xl font-black text-white tracking-tighter">₹{{ number_format($balance, 2) }}</span>
                            <p class="text-white/50 text-[10px] font-bold mt-2 uppercase tracking-wide">Current Outstanding
                            </p>
                            @if($balance <= 0)
                                <div
                                    class="mt-4 inline-flex items-center gap-2 bg-emerald-500/20 border border-emerald-400/50 px-3 py-1.5 rounded-lg">
                                    <span class="h-2 w-2 bg-emerald-400 rounded-full animate-pulse"></span>
                                    <span class="text-[10px] font-black text-emerald-300 uppercase tracking-widest">All Dues
                                        Cleared</span>
                                </div>
                            @else
                                <div
                                    class="mt-4 inline-flex items-center gap-2 bg-rose-500/20 border border-rose-400/50 px-3 py-1.5 rounded-lg">
                                    <span class="h-2 w-2 bg-rose-400 rounded-full animate-pulse"></span>
                                    <span class="text-[10px] font-black text-rose-300 uppercase tracking-widest">Payment
                                        Pending</span>
                                </div>
                            @endif
                        </div>

                        <!-- <button class="w-full py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-bold text-[12px] shadow-lg transition-all active:scale-95 uppercase tracking-wide">
                            View Receipts →
                        </button> -->
                    </div>
                </div>
            </div>

            <!-- Main Content Column -->
            <div class="lg:col-span-2 space-y-2">
                <!-- Academic Info Card -->
                <div class="bg-white rounded-2xl border border-slate-200 p-8 shadow-md">
                    <div class="flex items-center gap-4 pb-6 border-b border-slate-200 mb-6">
                        <div
                            class="h-12 w-12 bg-blue-100 rounded-xl flex items-center justify-center text-blue-600 font-bold text-lg">
                            📚</div>
                        <h3 class="text-xl font-black text-slate-800 tracking-tight">Academic Records</h3>
                    </div>

                    <div class="grid grid-cols-2 gap-6">
                        <div class="bg-blue-50 rounded-lg p-4 border border-blue-200">
                            <p class="text-[10px] font-bold text-blue-600 uppercase tracking-wide">Current Batch</p>
                            <p class="text-lg font-black text-slate-800 mt-2">
                                {{ $student->batch ? $student->batch->name : 'N/A' }}</p>
                        </div>
                        <div class="bg-indigo-50 rounded-lg p-4 border border-indigo-200">
                            <p class="text-[10px] font-bold text-indigo-600 uppercase tracking-wide">Admission Date</p>
                            <p class="text-lg font-black text-slate-800 mt-2">{{ $student->created_at->format('M d, Y') }}
                            </p>
                        </div>
                        <div class="bg-purple-50 rounded-lg p-4 border border-purple-200 col-span-2">
                            <p class="text-[10px] font-bold text-purple-600 uppercase tracking-wide"> Fee</p>
                            <div class="flex items-center gap-3 mt-2">
                                <span
                                    class="text-3xl font-black text-slate-800">₹{{ number_format($student->monthly_fee, 0) }}</span>
                                <!-- <span class="text-[10px] font-bold text-purple-600 bg-purple-100 px-2 py-1 rounded uppercase">Monthly</span> -->
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Personal Info Card -->
                <div class="bg-white rounded-2xl border border-slate-200 p-8 shadow-md">
                    <div class="flex items-center gap-4 pb-6 border-b border-slate-200 mb-6">
                        <div
                            class="h-12 w-12 bg-emerald-100 rounded-xl flex items-center justify-center text-emerald-600 font-bold text-lg">
                            👤</div>
                        <h3 class="text-xl font-black text-slate-800 tracking-tight">Personal Information</h3>
                    </div>

                    <div class="space-y-4">
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <p class="text-[10px] font-bold text-slate-500 uppercase tracking-wide">Guardian Name</p>
                                <p class="text-base font-bold text-slate-700 mt-1">{{ $student->guardian_name ?: '—' }}</p>
                            </div>
                            <div>
                                <p class="text-[10px] font-bold text-slate-500 uppercase tracking-wide">Phone</p>
                                <p class="text-base font-bold text-slate-700 mt-1">{{ $student->phone ?: '—' }}</p>
                            </div>
                        </div>
                        <div>
                            <p class="text-[10px] font-bold text-slate-500 uppercase tracking-wide">Email Address</p>
                            <p class="text-base font-bold text-blue-600 mt-1">{{ $student->email }}</p>
                        </div>
                        <div>
                            <p class="text-[10px] font-bold text-slate-500 uppercase tracking-wide">Date of Birth</p>
                            <p class="text-base font-bold text-slate-700 mt-1">
                                {{ $student->dob ? \Carbon\Carbon::parse($student->dob)->format('F d, Y') : '—' }}</p>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- Delete Modal -->
    <div id="delete-modal" class="fixed inset-0 z-[110] flex items-center justify-center hidden px-4">
        <div onclick="closeDeleteModal()" class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm"></div>
        <div
            class="bg-white w-full max-w-md rounded-[2.5rem] shadow-2xl relative z-10 overflow-hidden pt-10 px-10 pb-10 text-center animate-in fade-in zoom-in duration-300">
            <div class="h-24 w-24 bg-rose-50 text-rose-500 rounded-full flex items-center justify-center mx-auto mb-8">
                <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                </svg>
            </div>
            <h3 class="text-2xl font-black text-slate-800 tracking-tight mb-3">Delete Student Profile?</h3>
            <p class="text-[15px] text-slate-400 font-medium mb-10 px-6">This action cannot be undone. All academic records,
                attendance history, and fee receipts for <span class="text-slate-800 font-bold">{{ $student->name }}</span>
                will be permanently removed.</p>
            <div class="flex items-center gap-4">
                <button onclick="closeDeleteModal()"
                    class="flex-1 py-4 text-[13px] font-black text-slate-500 bg-slate-50 rounded-2xl hover:bg-slate-100 transition-colors uppercase tracking-widest">Cancel</button>
                <form id="delete-form" action="{{ route('institute.students.destroy', $student->id) }}" method="POST"
                    class="flex-1">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                        class="w-full py-4 text-[13px] font-black text-white bg-rose-500 rounded-2xl shadow-lg shadow-rose-200 active:scale-95 transition-transform uppercase tracking-widest">Confirm
                        Delete</button>
                </form>
            </div>
        </div>
    </div>

    <script>
        function openDeleteModal(id) {
            document.getElementById('delete-modal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }
        function closeDeleteModal() {
            document.getElementById('delete-modal').classList.add('hidden');
            document.body.style.overflow = 'auto';
        }

        document.getElementById('delete-form').onsubmit = function () {
            const btn = this.querySelector('button[type="submit"]');
            btn.disabled = true;
            btn.innerHTML = `<div class="flex items-center justify-center"><span class="h-5 w-5 border-2 border-white/30 border-t-white rounded-full animate-spin"></span></div>`;
        };
    </script>
@endsection