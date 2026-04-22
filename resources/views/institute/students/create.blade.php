@extends('layouts.institute')

@section('content')
<div class="max-w-4xl mx-auto pb-10">
    <!-- Header -->
    <div class="flex items-center gap-4 mb-3">
        <a href="{{ route('institute.students.index') }}" 
           class="h-10 w-10 bg-white border border-slate-100 rounded-xl flex items-center justify-center text-slate-400 hover:text-blue-600 transition-all shadow-sm group">
            <svg class="w-4 h-4 group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
        </a>
        <div>
            <h1 class="text-2xl font-extrabold text-[#111827] tracking-tight">New Student Registration</h1>
            <!-- <p class="text-sm text-slate-400 mt-1">Enroll a new scholar into the academic registry.</p> -->
        </div>
    </div>

    <!-- Form Card -->
    <div class="bg-white rounded-[1rem] shadow-sm border border-slate-100 overflow-hidden">
        <form action="{{ route('institute.students.store') }}" method="POST" class="p-4  sm:p-3 space-y-2">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Name -->
                <div class="space-y-2">
                    <label class="text-[11px] font-extrabold text-slate-400 uppercase tracking-widest ml-1">Full Name</label>
                    <input type="text" name="name" required value="{{ old('name') }}" placeholder="John Doe"
                        class="w-full px-3 py-2.5 bg-slate-50 border border-slate-100 rounded-2xl text-sm font-bold outline-none focus:ring-4 focus:ring-blue-500/5 transition-all @error('name') border-rose-500 @enderror">
                    @error('name') <p class="text-rose-500 text-[10px] font-bold mt-1 ml-1">{{ $message }}</p> @enderror
                </div>

                <!-- Email -->
                <div class="space-y-2">
                    <label class="text-[11px] font-extrabold text-slate-400 uppercase tracking-widest ml-1">Email Address</label>
                    <input type="email" name="email" required value="{{ old('email') }}" placeholder="john@example.com"
                        class="w-full px-3 py-2.5 bg-slate-50 border border-slate-100 rounded-2xl text-sm font-bold outline-none focus:ring-4 focus:ring-blue-500/5 transition-all @error('email') border-rose-500 @enderror">
                    @error('email') <p class="text-rose-500 text-[10px] font-bold mt-1 ml-1">{{ $message }}</p> @enderror
                </div>

                <!-- Phone -->
                <div class="space-y-2">
                    <label class="text-[11px] font-extrabold text-slate-400 uppercase tracking-widest ml-1">Phone Number</label>
                    <input type="text" name="phone" value="{{ old('phone') }}" placeholder="+123 456 7890"
                        class="w-full px-3 py-2.5 bg-slate-50 border border-slate-100 rounded-2xl text-sm font-bold outline-none focus:ring-4 focus:ring-blue-500/5 transition-all @error('phone') border-rose-500 @enderror">
                    @error('phone') <p class="text-rose-500 text-[10px] font-bold mt-1 ml-1">{{ $message }}</p> @enderror
                </div>

                <!-- Password -->
                <div class="space-y-2">
                    <label class="text-[11px] font-extrabold text-slate-400 uppercase tracking-widest ml-1">Password</label>
                    <input type="password" name="password" required placeholder="••••••••"
                        class="w-full px-3 py-2.5 bg-slate-50 border border-slate-100 rounded-2xl text-sm font-bold outline-none focus:ring-4 focus:ring-blue-500/5 transition-all @error('password') border-rose-500 @enderror">
                    @error('password') <p class="text-rose-500 text-[10px] font-bold mt-1 ml-1">{{ $message }}</p> @enderror
                </div>

                <!-- Guardian Name -->
                <div class="space-y-2">
                    <label class="text-[11px] font-extrabold text-slate-400 uppercase tracking-widest ml-1">Guardian Name</label>
                    <input type="text" name="guardian_name" value="{{ old('guardian_name') }}" placeholder="Mr. Richard Roe"
                        class="w-full px-3 py-2.5 bg-slate-50 border border-slate-100 rounded-2xl text-sm font-bold outline-none focus:ring-4 focus:ring-blue-500/5 transition-all @error('guardian_name') border-rose-500 @enderror">
                    @error('guardian_name') <p class="text-rose-500 text-[10px] font-bold mt-1 ml-1">{{ $message }}</p> @enderror
                </div>

                <!-- Date of Birth -->
                <div class="space-y-2">
                    <label class="text-[11px] font-extrabold text-slate-400 uppercase tracking-widest ml-1">Date of Birth</label>
                    <input type="date" name="dob" value="{{ old('dob') }}"
                        class="w-full px-3 py-2.5 bg-slate-50 border border-slate-100 rounded-2xl text-sm font-bold outline-none focus:ring-4 focus:ring-blue-500/5 transition-all @error('dob') border-rose-500 @enderror">
                    @error('dob') <p class="text-rose-500 text-[10px] font-bold mt-1 ml-1">{{ $message }}</p> @enderror
                </div>

                <!-- Batch -->
                <div class="space-y-2">
                    <label class="text-[11px] font-extrabold text-slate-400 uppercase tracking-widest ml-1">Assigned Batch</label>
                    <select name="batch_id"
                        class="w-full px-3 py-2.5 bg-slate-50 border border-slate-100 rounded-2xl text-sm font-bold outline-none appearance-none focus:ring-4 focus:ring-blue-500/5 transition-all @error('batch_id') border-rose-500 @enderror">
                        <option value="">No Batch Assigned</option>
                        @foreach($batches as $batch)
                            <option value="{{ $batch->id }}" {{ old('batch_id') == $batch->id ? 'selected' : '' }}>{{ $batch->name }}</option>
                        @endforeach
                    </select>
                    @error('batch_id') <p class="text-rose-500 text-[10px] font-bold mt-1 ml-1">{{ $message }}</p> @enderror
                </div>

                <!-- Standard -->
                <div class="space-y-2">
                    <label class="text-[11px] font-extrabold text-slate-400 uppercase tracking-widest ml-1">Standard</label>
                    <input type="text" name="standard" value="{{ old('standard') }}" placeholder="e.g. 10th Grade"
                        class="w-full px-3 py-2.5 bg-slate-50 border border-slate-100 rounded-2xl text-sm font-bold outline-none focus:ring-4 focus:ring-blue-500/5 transition-all @error('standard') border-rose-500 @enderror">
                    @error('standard') <p class="text-rose-500 text-[10px] font-bold mt-1 ml-1">{{ $message }}</p> @enderror
                </div>

                <!-- Monthly Fee -->
                <div class="space-y-2">
                    <label class="text-[11px] font-extrabold text-slate-400 uppercase tracking-widest ml-1"> Fee (₹)</label>
                    <input type="number" name="monthly_fee" value="{{ old('monthly_fee', 0) }}" placeholder="0"
                        class="w-full px-3 py-2.5 bg-slate-50 border border-slate-100 rounded-2xl text-sm font-bold outline-none focus:ring-4 focus:ring-blue-500/5 transition-all @error('monthly_fee') border-rose-500 @enderror">
                    @error('monthly_fee') <p class="text-rose-500 text-[10px] font-bold mt-1 ml-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <!-- Submit Section -->
            <div class="pt-8 border-t border-slate-50 flex items-center justify-end gap-4">
                <a href="{{ route('institute.students.index') }}" class="px-8 py-3.5 text-[13px] font-bold text-slate-400 hover:text-slate-600 transition-colors">Cancel</a>
                <button type="submit"
                    class="px-10 py-3.5 bg-[#1e3a8a] text-white rounded-2xl font-bold text-[13px] shadow-lg shadow-blue-900/10 hover:scale-[1.02] transition-transform flex items-center">
                    Confirm Registration
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
