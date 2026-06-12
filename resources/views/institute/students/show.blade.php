@extends('layouts.institute')

@section('content')
    @php
        $feeStatusText = 'Full Paid';
        $feeStatusBg = 'bg-emerald-50/30';
        $feeStatusBorder = 'border-emerald-100';
        $feeStatusLabel = 'text-emerald-600/50';
        $feeStatusValue = 'text-emerald-700';

        if (!$student->monthly_fee || $student->monthly_fee == 0) {
            $feeStatusText = 'No Fee';
            $feeStatusBg = 'bg-slate-50';
            $feeStatusBorder = 'border-slate-200';
            $feeStatusLabel = 'text-slate-400';
            $feeStatusValue = 'text-slate-500';
        } elseif ($balance >= $student->monthly_fee) {
            $feeStatusText = 'Pending';
            $feeStatusBg = 'bg-rose-50/30';
            $feeStatusBorder = 'border-rose-100';
            $feeStatusLabel = 'text-rose-600/50';
            $feeStatusValue = 'text-rose-700';
        } elseif ($balance > 0) {
            $feeStatusText = 'Partial Dues';
            $feeStatusBg = 'bg-amber-50/30';
            $feeStatusBorder = 'border-amber-100';
            $feeStatusLabel = 'text-amber-600/50';
            $feeStatusValue = 'text-amber-700';
        }
    @endphp
    <div class="max-w-6xl mx-auto">
        <!-- Breadcrumb & Actions -->
        <div class="flex items-center justify-between mb-1">
            <a href="{{ session('student_back_url') ?: route('institute.students.index') }}"
                class="flex items-center text-slate-400 hover:text-slate-600 font-bold text-sm transition-all group">
                <svg class="w-5 h-5 mr-2 group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
                Back
            </a>
            <div class="flex flex-wrap items-center gap-3">
                <a href="{{ route('institute.students.edit', $student->id) }}"
                    class="px-5 py-1.5 bg-white border-2 border-[#008080] text-[#008080] hover:bg-teal-50/50 rounded-xl font-bold text-sm transition-all flex items-center group shadow-sm">
                    <svg class="w-4 h-4 mr-2 text-[#008080]" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                            d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                    </svg>
                    Edit
                </a>
                <button onclick="openDeleteModal()"
                    class="px-5 py-1.5 bg-white border-2 border-rose-500 text-rose-500 hover:bg-rose-50/50 rounded-xl font-bold text-sm transition-all flex items-center group shadow-sm">
                    <svg class="w-4 h-4 mr-2 text-rose-500" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                    Delete Student
                </button>
            </div>
        </div>

        <!-- Main Content Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 mb-2">
            <!-- Profile Header Card (2/3) -->
            <div
                class="lg:col-span-2 bg-white rounded-xl border border-slate-100 shadow-sm p-4 h-full flex flex-col justify-center relative">
                <div class="flex flex-col md:flex-row items-center gap-4">
                    <div class="relative">
                        <div class="h-20 w-20 rounded-xl bg-slate-50 overflow-hidden border-2 border-white shadow-md">
                            <img src="{{ $student->profile_image_url }}" class="w-full h-full object-cover">
                        </div>
                    </div>

                    <div class="flex-1 text-center md:text-left">
                        <h1 class="text-xl font-semibold text-slate-800 tracking-tight">{{ $student->name }}</h1>
                        <p class="text-[10px] text-slate-400 mt-0.5 font-semibold uppercase tracking-widest">Enrollment ID:
                            <span class="text-slate-500 font-bold">{{ $student->enrollment_id }}</span>
                        </p>

                        <div class="flex flex-wrap items-center justify-center md:justify-start gap-2 mt-4">
                            <div class="bg-slate-50 rounded-xl px-3 py-2 border border-slate-100 min-w-[90px]">
                                <p class="text-[8px] font-bold text-slate-400 uppercase tracking-widest mb-0.5">Avg Grade
                                </p>
                                <p class="text-sm font-bold text-slate-700">{{ $averageGrade }}/10</p>
                            </div>
                            <div class="{{ $feeStatusBg }} rounded-xl px-3 py-2 border {{ $feeStatusBorder }} min-w-[90px]">
                                <p class="text-[8px] font-bold {{ $feeStatusLabel }} uppercase tracking-widest mb-0.5">Payment
                                    Status</p>
                                <p class="text-sm font-bold {{ $feeStatusValue }}">
                                    {{ $feeStatusText }}
                                </p>
                            </div>
                            <div class="bg-blue-50/30 rounded-xl px-3 py-2 border border-blue-100 min-w-[90px]">
                                <p class="text-[8px] font-bold text-blue-600/50 uppercase tracking-widest mb-0.5">Attendance
                                </p>
                                <p class="text-sm font-bold text-blue-600">{{ $attendancePercentage }}%</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Password Management Buttons in Top-Right Corner -->
                <div class="md:absolute md:top-4 md:right-4 flex flex-row gap-2 mt-4 md:mt-0 justify-center">
                    <button onclick="sendPasswordEmail({{ $student->id }})" id="btn-send-pass"
                        class="px-4 py-1.5 bg-white border-2 border-amber-500 text-amber-500 hover:bg-amber-50/50 rounded-xl font-bold text-xs transition-all flex items-center justify-center group shadow-sm">
                        <svg class="w-3.5 h-3.5 mr-1.5 text-amber-500 transition-transform group-hover:rotate-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                        Forgot Password
                    </button>
                    <button onclick="openResetPasswordModal()"
                        class="px-4 py-1.5 bg-white border-2 border-indigo-600 text-indigo-600 hover:bg-indigo-50/50 rounded-xl font-bold text-xs transition-all flex items-center justify-center group shadow-sm">
                        <svg class="w-3.5 h-3.5 mr-1.5 text-indigo-600 transition-transform group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                        </svg>
                        Reset Password
                    </button>
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
                    <p class="text-[8px] font-bold text-slate-400 uppercase tracking-widest mb-0.5">Pending Amount</p>
                    <div class="flex items-baseline gap-1.5">
                        <span
                            class="text-2xl font-bold text-slate-700 tracking-tighter">₹{{ number_format($balance) }}</span>
                        <span class="text-[8px] font-bold text-slate-400">/
                            ₹{{ number_format($student->monthly_fee) }} Total</span>
                    </div>

                    @if($balance > 0)
                    @if(Auth::guard('institute')->user()->hasActiveSubscription())
                    <button onclick="sendFeeReminder({{ $student->id }})" id="btn-fee-reminder"
                        class="w-full mt-3 py-2.5 bg-primary hover:bg-orange-600 text-white rounded-xl font-bold text-[10px] shadow-md shadow-orange-500/10 hover:translate-y-[-1px] active:scale-[0.98] transition-all flex items-center justify-center gap-1.5 uppercase tracking-wider">
                        <svg class="w-3.5 h-3.5 text-white animate-bounce" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                        </svg>
                        Send Fee Reminder
                    </button>
                    @else
                    <button onclick="handleExpiredSubscription(event)" id="btn-fee-reminder"
                        class="w-full mt-3 py-2.5 bg-primary hover:bg-orange-600 text-white rounded-xl font-bold text-[10px] shadow-md shadow-orange-500/10 hover:translate-y-[-1px] active:scale-[0.98] transition-all flex items-center justify-center gap-1.5 uppercase tracking-wider">
                        <svg class="w-3.5 h-3.5 text-white animate-bounce" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                        </svg>
                        Send Fee Reminder
                    </button>
                    @endif
                    @endif
                </div>

                <div class="space-y-1.5 mb-3 flex-1">
                    <div class="flex justify-between text-[10px]">
                        <span class="font-bold text-slate-400 uppercase">Standard</span>
                        <span class="font-bold text-slate-600">{{ $student->standard }}</span>
                    </div>
                    <div class="flex justify-between text-[10px]">
                        <span class="font-bold text-slate-400 uppercase">    Fee</span>
                        <span class="font-bold text-slate-600">₹{{ number_format($student->monthly_fee) }}</span>
                    </div>
                </div>
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
                        {{ $student->batch ? $student->batch->name : 'N/A' }}
                    </p>
                </div>
                <div>
                    <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">Date of Admission</p>
                    <p class="text-sm font-semibold text-slate-600 leading-tight">
                        {{ $student->created_at->format('M d, Y') }}
                    </p>
                </div>
                <div>
                    <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">Parent Name</p>
                    <p class="text-sm font-semibold text-slate-600 leading-tight">{{ $student->guardian_name ?: 'N/A' }}</p>
                </div>
                <div>
                    <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">Phone Number</p>
                    <p class="text-sm font-semibold text-slate-600 leading-tight">+91 {{ $student->phone }}</p>
                </div>
                <div>
                    <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">Email Address</p>
                    <p class="text-sm font-semibold text-slate-600 leading-tight">{{ $student->email }}</p>
                </div>
                <div>
                    <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">Date of Birth</p>
                    <p class="text-sm font-semibold text-slate-600 leading-tight">
                        {{ $student->dob ? \Carbon\Carbon::parse($student->dob)->format('M d, Y') : 'N/A' }}
                    </p>
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

        <!-- Fee Payment History Section -->
        <div class="bg-white rounded-xl border border-slate-100 shadow-sm p-4 mb-4">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center gap-3">
                    <div class="w-1.5 h-4 bg-emerald-600 rounded-full"></div>
                    <h2 class="text-base font-semibold text-slate-700 tracking-tight">Fee Payment History</h2>
                </div>
            </div>

            @if($student->fees && $student->fees->count() > 0)
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b border-slate-50 text-[10px] font-black text-slate-400 uppercase tracking-widest">
                                <th class="pb-3 pl-2">Date / Month</th>
                                <th class="pb-3">Total Fee</th>
                                <th class="pb-3">Paid Amount</th>
                                <th class="pb-3">Remaining</th>
                                <th class="pb-3">Status</th>
                                <th class="pb-3 text-right pr-2">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            @foreach($student->fees->sortByDesc('date') as $fee)
                                @php
                                    $remaining = max(0, $fee->total_amount - $fee->paid_amount);
                                    $statusBg = 'bg-rose-50 text-rose-600 border-rose-100';
                                    if ($fee->status === 'Paid' || $remaining == 0) {
                                        $statusBg = 'bg-emerald-50 text-emerald-600 border-emerald-100';
                                    } elseif ($fee->paid_amount > 0) {
                                        $statusBg = 'bg-amber-50 text-amber-600 border-amber-100';
                                    }
                                @endphp
                                <tr class="text-xs font-semibold text-slate-600 hover:bg-slate-50/50 cursor-pointer transition-colors" onclick="window.location.href='{{ route('institute.fees.receipts.show', $fee->id) }}'">
                                    <td class="py-3 pl-2 font-bold text-slate-700">
                                        {{ \Carbon\Carbon::parse($fee->date)->format('M Y') }}
                                    </td>
                                    <td class="py-3 font-bold">₹{{ number_format($fee->total_amount) }}</td>
                                    <td class="py-3 font-bold text-emerald-600">₹{{ number_format($fee->paid_amount) }}</td>
                                    <td class="py-3 font-bold text-rose-500">₹{{ number_format($remaining) }}</td>
                                    <td class="py-3">
                                        <span class="px-2.5 py-0.5 rounded-full text-[9px] font-bold uppercase tracking-wider border {{ $statusBg }}">
                                            {{ $fee->status ?: ($remaining == 0 ? 'Paid' : 'Unpaid') }}
                                        </span>
                                    </td>
                                    <td class="py-3 text-right pr-2">
                                        <a href="{{ route('institute.fees.receipts.show', $fee->id) }}"
                                            class="inline-flex items-center gap-1.5 px-3 py-1 bg-slate-50 hover:bg-slate-100 text-slate-500 hover:text-slate-700 border border-slate-100 hover:border-slate-200 rounded-lg text-[10px] font-bold transition-all shadow-sm">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                            </svg>
                                            View Receipt
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="flex flex-col items-center justify-center py-6 text-center">
                    <div class="h-10 w-10 bg-slate-50 rounded-xl flex items-center justify-center text-slate-400 mb-2 border border-slate-100">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                    </div>
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">No Fee Records Found</p>
                    <p class="text-[10px] text-slate-400 mt-0.5">This student doesn't have any registered monthly fee cycles yet.</p>
                </div>
            @endif
        </div>
    </div>
    <form id="delete-form" action="{{ route('institute.students.destroy', $student->id) }}" method="POST" class="hidden">
        @csrf
        @method('DELETE')
    </form>

    <!-- Reset Password Modal -->
    <div id="reset-password-modal" class="fixed inset-0 z-[200] hidden overflow-y-auto">
        <!-- Backdrop -->
        <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" onclick="closeResetPasswordModal()"></div>

        <!-- Modal Wrapper -->
        <div class="flex min-h-full items-center justify-center p-4 text-center">
            <div class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-md border border-slate-100">
                <!-- Close Button -->
                <button onclick="closeResetPasswordModal()" class="absolute top-4 right-4 text-slate-400 hover:text-slate-600 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>

                <!-- Modal Content -->
                <div class="p-6">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="h-10 w-10 bg-indigo-50 rounded-xl flex items-center justify-center text-indigo-600 border border-indigo-100">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-base font-bold text-slate-800">Reset Student Password</h3>
                            <p class="text-[10px] text-slate-400 font-semibold uppercase tracking-wider">Direct Password Update</p>
                        </div>
                    </div>

                    <!-- Description -->
                    <p class="text-xs text-slate-500 mb-4 leading-relaxed">
                        Update the password for <span class="font-bold text-slate-700">{{ $student->name }}</span>. The new password must satisfy the platform password policy.
                    </p>

                    <!-- Form -->
                    <form id="reset-password-form" onsubmit="submitResetPassword(event)">
                        @csrf
                        <div class="mb-4 relative">
                            <label for="new_password" class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">New Password</label>
                            <div class="relative">
                                <input type="password" name="password" id="new_password" required
                                    class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-semibold text-slate-700 placeholder-slate-400 focus:outline-none focus:bg-white focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-all pr-10"
                                    placeholder="Enter secure password">
                                <button type="button" onclick="togglePasswordVisibility('new_password', this)" class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 transition-colors">
                                    <svg class="w-4 h-4 eye-open" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                    <svg class="w-4 h-4 eye-closed hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18" />
                                    </svg>
                                </button>
                            </div>
                        </div>

                        <!-- Policy checklist -->
                        <div class="mb-5 bg-slate-50 p-3.5 rounded-xl border border-slate-100 text-[11px] text-slate-500 space-y-1.5 font-medium">
                            <p class="font-bold text-slate-600 uppercase tracking-widest text-[9px] mb-1">Password Requirements:</p>
                            <div class="flex items-center gap-2" id="req-length">
                                <span class="bullet w-1.5 h-1.5 rounded-full bg-slate-300 transition-colors duration-200"></span>
                                <span>8 to 15 characters</span>
                            </div>
                            <div class="flex items-center gap-2" id="req-upper">
                                <span class="bullet w-1.5 h-1.5 rounded-full bg-slate-300 transition-colors duration-200"></span>
                                <span>At least 1 uppercase letter</span>
                            </div>
                            <div class="flex items-center gap-2" id="req-digit">
                                <span class="bullet w-1.5 h-1.5 rounded-full bg-slate-300 transition-colors duration-200"></span>
                                <span>At least 1 digit (number)</span>
                            </div>
                            <div class="flex items-center gap-2" id="req-special">
                                <span class="bullet w-1.5 h-1.5 rounded-full bg-slate-300 transition-colors duration-200"></span>
                                <span>At least 1 special character (@#$% etc.)</span>
                            </div>
                        </div>

                        <!-- Action buttons -->
                        <div class="flex gap-3">
                            <button type="button" onclick="closeResetPasswordModal()"
                                class="flex-1 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-xl font-bold text-xs transition-all border border-slate-200">
                                Cancel
                            </button>
                            <button type="submit" id="btn-submit-reset"
                                class="flex-1 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl font-bold text-xs transition-all shadow-md shadow-indigo-500/10">
                                Save Password
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function openDeleteModal() {
            showConfirmModal(
                'Delete Student?',
                'Are you sure you want to permanently remove <span class="font-bold text-slate-800">{{ $student->name }}</span>? This action cannot be undone and will erase all academic and financial history.',
                function () {
                    document.getElementById('delete-form').submit();
                },
                'Yes, Delete Student',
                'bg-rose-600 hover:bg-rose-700 shadow-rose-950/15'
            );
        }

        async function sendFeeReminder(studentId) {
            if (typeof toggleLoader === 'function') toggleLoader(true);
            try {
                const response = await fetch(`/institute/students/${studentId}/fee-reminder`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                });
                const data = await response.json();
                if (response.ok && data.status === 'success') {
                    if (typeof showToast === 'function') showToast(data.message, 'success');
                } else {
                    if (typeof showToast === 'function') showToast(data.message || 'Failed to send reminder.', 'error');
                }
            } catch (error) {
                console.error(error);
                if (typeof showToast === 'function') showToast('Something went wrong. Please try again.', 'error');
            } finally {
                if (typeof toggleLoader === 'function') toggleLoader(false);
            }
        }

        // --- Forgot Password Button (Send Email) ---
        async function sendPasswordEmail(studentId) {
            const btn = document.getElementById('btn-send-pass');
            if (typeof toggleLoader === 'function') toggleLoader(true);
            btn.disabled = true;
            
            try {
                const response = await fetch(`/institute/students/${studentId}/send-password`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                });
                const data = await response.json();
                if (response.ok && data.status === 'success') {
                    if (typeof showToast === 'function') showToast(data.message, 'success');
                } else {
                    if (typeof showToast === 'function') showToast(data.message || 'Failed to send password.', 'error');
                }
            } catch (error) {
                console.error(error);
                if (typeof showToast === 'function') showToast('Something went wrong. Please try again.', 'error');
            } finally {
                btn.disabled = false;
                if (typeof toggleLoader === 'function') toggleLoader(false);
            }
        }

        // --- Reset Password Modal Functions ---
        function openResetPasswordModal() {
            document.getElementById('reset-password-modal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeResetPasswordModal() {
            document.getElementById('reset-password-modal').classList.add('hidden');
            document.body.style.overflow = 'auto';
            document.getElementById('reset-password-form').reset();
            // Clear criteria indicators
            ['req-length', 'req-upper', 'req-digit', 'req-special'].forEach(id => {
                updateReqIndicator(id, false);
            });
        }

        function togglePasswordVisibility(fieldId, btn) {
            const input = document.getElementById(fieldId);
            const openIcon = btn.querySelector('.eye-open');
            const closedIcon = btn.querySelector('.eye-closed');
            if (input.type === 'password') {
                input.type = 'text';
                openIcon.classList.add('hidden');
                closedIcon.classList.remove('hidden');
            } else {
                input.type = 'password';
                openIcon.classList.remove('hidden');
                closedIcon.classList.add('hidden');
            }
        }

        // --- Live password criteria check ---
        document.getElementById('new_password').addEventListener('input', function() {
            const val = this.value;
            
            // Length: 8-15
            const lenValid = val.length >= 8 && val.length <= 15;
            updateReqIndicator('req-length', lenValid);
            
            // Uppercase
            const upperValid = /[A-Z]/.test(val);
            updateReqIndicator('req-upper', upperValid);
            
            // Digit
            const digitValid = /[0-9]/.test(val);
            updateReqIndicator('req-digit', digitValid);
            
            // Special Char
            const specialValid = /[\W_]/.test(val);
            updateReqIndicator('req-special', specialValid);
        });

        function updateReqIndicator(id, isValid) {
            const el = document.getElementById(id);
            if (!el) return;
            const bullet = el.querySelector('.bullet');
            if (isValid) {
                bullet.classList.remove('bg-slate-300', 'bg-rose-500');
                bullet.classList.add('bg-emerald-500');
                el.classList.remove('text-slate-500', 'text-rose-500');
                el.classList.add('text-emerald-600');
            } else {
                bullet.classList.remove('bg-emerald-500');
                bullet.classList.add('bg-slate-300');
                el.classList.remove('text-emerald-600', 'text-rose-500');
                el.classList.add('text-slate-500');
            }
        }

        async function submitResetPassword(event) {
            event.preventDefault();
            const password = document.getElementById('new_password').value;
            const btn = document.getElementById('btn-submit-reset');
            
            if (typeof toggleLoader === 'function') toggleLoader(true);
            btn.disabled = true;

            try {
                const response = await fetch(`/institute/students/{{ $student->id }}/reset-password-direct`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ password: password })
                });
                
                const data = await response.json();
                
                if (response.ok && data.status === 'success') {
                    if (typeof showToast === 'function') showToast(data.message, 'success');
                    closeResetPasswordModal();
                } else {
                    let errMsg = data.message || 'Failed to reset password.';
                    if (data.errors && data.errors.password) {
                        errMsg = data.errors.password[0];
                    }
                    if (typeof showToast === 'function') showToast(errMsg, 'error');
                }
            } catch (error) {
                console.error(error);
                if (typeof showToast === 'function') showToast('Something went wrong. Please try again.', 'error');
            } finally {
                btn.disabled = false;
                if (typeof toggleLoader === 'function') toggleLoader(false);
            }
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