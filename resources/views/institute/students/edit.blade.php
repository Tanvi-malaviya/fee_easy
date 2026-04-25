@extends('layouts.institute')

@section('content')
<div class="max-w-7xl mx-auto">
    <!-- Header -->
    <div class="flex items-center gap-4 mb-3">
        <a href="{{ route('institute.students.index') }}" 
           class="h-10 w-10 bg-white border border-slate-100 rounded-xl flex items-center justify-center text-slate-400 hover:text-blue-600 transition-all shadow-sm group">
            <svg class="w-4 h-4 group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
        </a>
        <div>
            <h1 class="text-2xl font-extrabold text-[#111827] tracking-tight">Edit Student Profile</h1>
            <!-- <p class="text-sm text-slate-400 mt-1">Update information for <span class="text-blue-600 font-bold">{{ $student->name }}</span>.</p> -->
        </div>
    </div>

    <!-- Form Card -->
    <div class="bg-white rounded-[1rem] shadow-sm border border-slate-100 overflow-hidden">
        <form action="{{ route('institute.students.update', $student->id) }}" method="POST" enctype="multipart/form-data" class="p-4 sm:p-3 space-y-2">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Name -->
                <div class="space-y-2">
                    <label class="text-[11px] font-extrabold text-slate-400 uppercase tracking-widest ml-1">Full Name</label>
                    <input type="text" name="name" required value="{{ old('name', $student->name) }}" placeholder="John Doe"
                        class="w-full px-3 py-2.5 bg-slate-50 border border-slate-100 rounded-2xl text-sm font-bold outline-none focus:ring-4 focus:ring-blue-500/5 transition-all @error('name') border-rose-500 @enderror">
                    @error('name') <p class="text-rose-500 text-[10px] font-bold mt-1 ml-1">{{ $message }}</p> @enderror
                </div>

                <!-- Email -->
                <div class="space-y-2">
                    <label class="text-[11px] font-extrabold text-slate-400 uppercase tracking-widest ml-1">Email Address</label>
                    <input type="email" name="email" required value="{{ old('email', $student->email) }}" placeholder="john@example.com"
                        class="w-full px-3 py-2.5 bg-slate-50 border border-slate-100 rounded-2xl text-sm font-bold outline-none focus:ring-4 focus:ring-blue-500/5 transition-all @error('email') border-rose-500 @enderror">
                    @error('email') <p class="text-rose-500 text-[10px] font-bold mt-1 ml-1">{{ $message }}</p> @enderror
                </div>

                <!-- Phone -->
                <div class="space-y-2">
                    <label class="text-[11px] font-extrabold text-slate-400 uppercase tracking-widest ml-1">Phone Number</label>
                    <input type="text" name="phone" required value="{{ old('phone', $student->phone) }}" placeholder="+91 00000 00000"
                        class="w-full px-3 py-2.5 bg-slate-50 border border-slate-100 rounded-2xl text-sm font-bold outline-none focus:ring-4 focus:ring-blue-500/5 transition-all @error('phone') border-rose-500 @enderror">
                    @error('phone') <p class="text-rose-500 text-[10px] font-bold mt-1 ml-1">{{ $message }}</p> @enderror
                </div>

                <!-- Guardian Name -->
                <div class="space-y-2">
                    <label class="text-[11px] font-extrabold text-slate-400 uppercase tracking-widest ml-1">Guardian Name</label>
                    <input type="text" name="guardian_name" required value="{{ old('guardian_name', $student->guardian_name) }}" placeholder="Mr. Richard Roe"
                        class="w-full px-3 py-2.5 bg-slate-50 border border-slate-100 rounded-2xl text-sm font-bold outline-none focus:ring-4 focus:ring-blue-500/5 transition-all @error('guardian_name') border-rose-500 @enderror">
                    @error('guardian_name') <p class="text-rose-500 text-[10px] font-bold mt-1 ml-1">{{ $message }}</p> @enderror
                </div>

                <!-- Date of Birth -->
                <div class="space-y-2">
                    <label class="text-[11px] font-extrabold text-slate-400 uppercase tracking-widest ml-1">Date of Birth</label>
                    <input type="date" name="dob" required value="{{ old('dob', $student->dob) }}"
                        class="w-full px-3 py-2.5 bg-slate-50 border border-slate-100 rounded-2xl text-sm font-bold outline-none focus:ring-4 focus:ring-blue-500/5 transition-all @error('dob') border-rose-500 @enderror">
                    @error('dob') <p class="text-rose-500 text-[10px] font-bold mt-1 ml-1">{{ $message }}</p> @enderror
                </div>

                <!-- Standard -->
                <div class="space-y-2">
                    <label class="text-[11px] font-extrabold text-slate-400 uppercase tracking-widest ml-1">Standard</label>
                    <input type="text" name="standard" required value="{{ old('standard', $student->standard) }}" placeholder="e.g. 10th Grade"
                        class="w-full px-3 py-2.5 bg-slate-50 border border-slate-100 rounded-2xl text-sm font-bold outline-none focus:ring-4 focus:ring-blue-500/5 transition-all @error('standard') border-rose-500 @enderror">
                    @error('standard') <p class="text-rose-500 text-[10px] font-bold mt-1 ml-1">{{ $message }}</p> @enderror
                </div>

                <!-- Status -->
                <!-- <div class="space-y-2">
                    <label class="text-[11px] font-extrabold text-slate-400 uppercase tracking-widest ml-1">Account Status</label>
                    <select name="status" required
                        class="w-full px-3 py-2.5 bg-slate-50 border border-slate-100 rounded-2xl text-sm font-bold outline-none appearance-none focus:ring-4 focus:ring-blue-500/5 transition-all @error('status') border-rose-500 @enderror">
                        <option value="1" {{ old('status', $student->status) == 1 ? 'selected' : '' }}>Active</option>
                        <option value="0" {{ old('status', $student->status) == 0 ? 'selected' : '' }}>Inactive</option>
                    </select>
                    @error('status') <p class="text-rose-500 text-[10px] font-bold mt-1 ml-1">{{ $message }}</p> @enderror
                </div> -->

                <!-- Profile Image -->
                <div class="space-y-2">
                    <label class="text-[11px] font-extrabold text-slate-400 uppercase tracking-widest ml-1">Profile Image (Optional)</label>
                    <div class="flex items-center gap-4">
                        @if($student->profile_image)
                            <img src="{{ asset('storage/' . $student->profile_image) }}" alt="{{ $student->name }}" class="h-10 w-10 rounded-2xl object-cover border border-slate-200">
                        @endif
                        <input type="file" name="profile_image" accept="image/*"
                            class="w-full px-3 py-1.5 bg-slate-50 border border-slate-100 rounded-2xl text-sm font-bold outline-none focus:ring-4 focus:ring-blue-500/5 transition-all @error('profile_image') border-rose-500 @enderror">
                    </div>
                    @error('profile_image') <p class="text-rose-500 text-[10px] font-bold mt-1 ml-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <!-- Submit Section -->
            <div class="pt-8 border-t border-slate-50 flex items-center justify-end gap-4">
                <a href="{{ route('institute.students.index') }}" class="px-8 py-3.5 text-[13px] font-bold text-slate-400 hover:text-slate-600 transition-colors">Cancel</a>
                <button type="submit"
                    class="px-10 py-3.5 bg-[#1e3a8a] text-white rounded-2xl font-bold text-[13px] shadow-lg shadow-blue-900/10 hover:scale-[1.02] transition-transform flex items-center">
                    Update Profile
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
