@extends('layouts.institute')

@section('content')
<div class="max-w-7xl mx-auto pb-20">
    <!-- Breadcrumbs & Actions -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
        <div class="flex items-center gap-4">
            <a href="{{ route('institute.students.index') }}" 
               class="h-10 w-10 bg-white border border-slate-100 rounded-xl flex items-center justify-center text-slate-400 hover:text-blue-600 transition-all shadow-sm group">
                <svg class="w-4 h-4 group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
            </a>
            <div>
                <h1 class="text-2xl font-extrabold text-[#111827] tracking-tight">Student Profile</h1>
                <p class="text-sm text-slate-400 mt-1">Detailed academic and financial records.</p>
            </div>
        </div>

        <div class="flex items-center gap-3">
            <a href="{{ route('institute.students.edit', $student->id) }}" 
               class="px-6 py-2.5 bg-white border border-slate-200 text-slate-700 rounded-xl font-bold text-[13px] shadow-sm hover:bg-slate-50 transition-all flex items-center gap-2">
                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                Edit Profile
            </a>
            <button onclick="openDeleteModal({{ $student->id }})" 
               class="px-6 py-2.5 bg-white border border-rose-100 text-rose-600 rounded-xl font-bold text-[13px] shadow-sm hover:bg-rose-50 transition-all flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                Delete Student
            </button>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Sidebar Column -->
        <div class="lg:col-span-1 space-y-8">
            <!-- Student Identity Card -->
            <div class="bg-white rounded-[2rem] shadow-sm border border-slate-100 p-8 text-center relative overflow-hidden">
                <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-blue-600/10 via-blue-600 to-blue-600/10"></div>
                
                <div class="relative inline-block mb-6">
                    <div class="h-32 w-32 rounded-[2.5rem] bg-slate-50 border border-slate-100 p-1 shadow-inner mx-auto overflow-hidden transform group-hover:scale-105 transition-transform duration-500">
                        <img src="https://ui-avatars.com/api/?name={{ urlencode($student->name) }}&background=1e3a8a&color=fff&size=256&bold=true" class="w-full h-full object-cover rounded-[2.2rem]">
                    </div>
                    <div class="absolute -bottom-1 -right-1 h-7 w-7 bg-emerald-500 border-4 border-white rounded-full shadow-sm"></div>
                </div>

                <h2 class="text-2xl font-black text-slate-800 tracking-tight">{{ $student->name }}</h2>
                <div class="flex items-center justify-center gap-2 mt-2">
                    <span class="text-[11px] font-bold text-slate-400 uppercase tracking-widest bg-slate-50 px-3 py-1 rounded-full border border-slate-100">STU-{{ str_pad($student->id, 4, '0', STR_PAD_LEFT) }}</span>
                </div>

                <div class="flex items-center justify-center gap-3 mt-8">
                    <div class="flex flex-col items-center p-4 bg-slate-50 rounded-[1.5rem] border border-slate-100 flex-1">
                        <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Standard</span>
                        <span class="text-sm font-black text-blue-600">{{ $student->standard ?: 'N/A' }}</span>
                    </div>
                    <div class="flex flex-col items-center p-4 bg-slate-50 rounded-[1.5rem] border border-slate-100 flex-1">
                        <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Status</span>
                        <span class="text-sm font-black {{ $student->status == 1 ? 'text-emerald-600' : 'text-rose-600' }}">{{ $student->status == 1 ? 'Active' : 'Inactive' }}</span>
                    </div>
                </div>
            </div>

            <!-- Fee Balance Card -->
            <div class="bg-[#003d82] rounded-[2.5rem] p-8 shadow-xl shadow-blue-900/10 relative overflow-hidden">
                <div class="absolute top-0 right-0 w-32 h-32 bg-white/5 rounded-full -mr-16 -mt-16"></div>
                <div class="absolute bottom-0 left-0 w-24 h-24 bg-white/5 rounded-full -ml-12 -mb-12"></div>
                
                <div class="relative z-10">
                    <div class="flex items-center justify-between mb-6">
                        <div>
                            <h4 class="text-white/60 text-[11px] font-black uppercase tracking-widest">Fee Balance</h4>
                            <p class="text-white/40 text-[10px] font-medium mt-0.5">Current Dues</p>
                        </div>
                        <div class="h-10 w-10 bg-white/10 rounded-xl flex items-center justify-center text-white/80">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                    </div>
                    
                    <div class="mb-8">
                        <span class="text-4xl font-black text-white tracking-tighter">₹{{ number_format($balance, 2) }}</span>
                        @if($balance <= 0)
                            <div class="mt-4 flex items-center gap-2">
                                <span class="h-1.5 w-1.5 bg-emerald-400 rounded-full"></span>
                                <span class="text-[10px] font-black text-emerald-400 uppercase tracking-widest">All Dues Cleared</span>
                            </div>
                        @else
                            <div class="mt-4 flex items-center gap-2">
                                <span class="h-1.5 w-1.5 bg-rose-400 rounded-full"></span>
                                <span class="text-[10px] font-black text-rose-400 uppercase tracking-widest">Payment Pending</span>
                            </div>
                        @endif
                    </div>

                    <button class="w-full py-4 bg-white text-[#003d82] rounded-2xl font-black text-[13px] hover:bg-slate-50 transition-all shadow-lg active:scale-95 group">
                        <span class="flex items-center justify-center gap-2">
                            View All Receipts
                            <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                        </span>
                    </button>
                </div>
            </div>
        </div>

        <!-- Main Content Column -->
        <div class="lg:col-span-2 space-y-8">
            <!-- Information Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <!-- Academic Info -->
                <div class="bg-white rounded-[2rem] shadow-sm border border-slate-100 p-8 flex flex-col">
                    <div class="flex items-center gap-3 mb-8">
                        <div class="h-10 w-10 bg-blue-50 rounded-xl flex items-center justify-center text-blue-600">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                        </div>
                        <h3 class="text-lg font-extrabold text-slate-800 tracking-tight">Academic Records</h3>
                    </div>

                    <div class="space-y-6 flex-1">
                        <div class="group">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest block mb-1 group-hover:text-blue-500 transition-colors">Current Batch</label>
                            <p class="text-[15px] font-bold text-slate-700">{{ $student->batch ? $student->batch->name : 'No Batch Assigned' }}</p>
                        </div>
                        <div class="group">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest block mb-1 group-hover:text-blue-500 transition-colors">Date of Admission</label>
                            <p class="text-[15px] font-bold text-slate-700">{{ $student->created_at->format('F d, Y') }}</p>
                        </div>
                        <div class="group">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest block mb-1 group-hover:text-blue-500 transition-colors">Monthly Tuition Fee</label>
                            <div class="flex items-center gap-2">
                                <span class="text-[15px] font-black text-slate-800">₹{{ number_format($student->monthly_fee, 2) }}</span>
                                <span class="text-[10px] font-bold text-slate-400 bg-slate-50 px-2 py-0.5 rounded border border-slate-100 italic">Recurring</span>
                            </div>
                        </div>
                        <div class="group">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest block mb-1 group-hover:text-blue-500 transition-colors">Assigned Grade</label>
                            <p class="text-[15px] font-bold text-slate-700">{{ $student->standard ?: 'Not Specified' }}</p>
                        </div>
                    </div>
                </div>

                <!-- Personal Info -->
                <div class="bg-white rounded-[2rem] shadow-sm border border-slate-100 p-8 flex flex-col">
                    <div class="flex items-center gap-3 mb-8">
                        <div class="h-10 w-10 bg-indigo-50 rounded-xl flex items-center justify-center text-indigo-600">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                        </div>
                        <h3 class="text-lg font-extrabold text-slate-800 tracking-tight">Personal Details</h3>
                    </div>

                    <div class="space-y-6 flex-1">
                        <div class="group">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest block mb-1 group-hover:text-indigo-500 transition-colors">Guardian Name</label>
                            <p class="text-[15px] font-bold text-slate-700">{{ $student->guardian_name ?: 'Not Provided' }}</p>
                        </div>
                        <div class="group">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest block mb-1 group-hover:text-indigo-500 transition-colors">Phone Number</label>
                            <p class="text-[15px] font-bold text-slate-700">{{ $student->phone ?: 'Not Provided' }}</p>
                        </div>
                        <div class="group">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest block mb-1 group-hover:text-indigo-500 transition-colors">Email Address</label>
                            <p class="text-[15px] font-bold text-slate-700">{{ $student->email }}</p>
                        </div>
                        <div class="group">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest block mb-1 group-hover:text-indigo-500 transition-colors">Date of Birth</label>
                            <p class="text-[15px] font-bold text-slate-700">{{ $student->dob ? \Carbon\Carbon::parse($student->dob)->format('F d, Y') : 'Not Provided' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Activity Placeholder -->
            <div class="bg-white rounded-[2rem] shadow-sm border border-slate-100 p-8">
                <div class="flex items-center justify-between mb-8">
                    <div class="flex items-center gap-3">
                        <div class="h-10 w-10 bg-amber-50 rounded-xl flex items-center justify-center text-amber-600">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <h3 class="text-lg font-extrabold text-slate-800 tracking-tight">Recent Activity</h3>
                    </div>
                    <button class="text-[11px] font-black text-blue-600 uppercase tracking-widest hover:underline">View All</button>
                </div>

                <div class="space-y-6">
                    <div class="flex items-center justify-center py-12 text-center bg-slate-50/50 rounded-[1.5rem] border border-dashed border-slate-200">
                        <div>
                            <div class="h-12 w-12 bg-white rounded-full flex items-center justify-center mx-auto mb-3 shadow-sm">
                                <svg class="w-6 h-6 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/></svg>
                            </div>
                            <p class="text-[13px] font-bold text-slate-400">No recent transactions or logs found.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Modal -->
<div id="delete-modal" class="fixed inset-0 z-[110] flex items-center justify-center hidden px-4">
    <div onclick="closeDeleteModal()" class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm"></div>
    <div class="bg-white w-full max-w-md rounded-[2.5rem] shadow-2xl relative z-10 overflow-hidden pt-10 px-10 pb-10 text-center animate-in fade-in zoom-in duration-300">
        <div class="h-24 w-24 bg-rose-50 text-rose-500 rounded-full flex items-center justify-center mx-auto mb-8">
            <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
        </div>
        <h3 class="text-2xl font-black text-slate-800 tracking-tight mb-3">Delete Student Profile?</h3>
        <p class="text-[15px] text-slate-400 font-medium mb-10 px-6">This action cannot be undone. All academic records, attendance history, and fee receipts for <span class="text-slate-800 font-bold">{{ $student->name }}</span> will be permanently removed.</p>
        <div class="flex items-center gap-4">
            <button onclick="closeDeleteModal()" class="flex-1 py-4 text-[13px] font-black text-slate-500 bg-slate-50 rounded-2xl hover:bg-slate-100 transition-colors uppercase tracking-widest">Cancel</button>
            <form id="delete-form" action="{{ route('institute.students.destroy', $student->id) }}" method="POST" class="flex-1">
                @csrf
                @method('DELETE')
                <button type="submit" class="w-full py-4 text-[13px] font-black text-white bg-rose-500 rounded-2xl shadow-lg shadow-rose-200 active:scale-95 transition-transform uppercase tracking-widest">Confirm Delete</button>
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
</script>
@endsection
