@extends('layouts.teacher')

@section('title', 'My Profile')

@section('content')
<div class="max-w-2xl">
    <h1 class="text-2xl font-black text-slate-900 tracking-tight mb-6">My Profile</h1>

    <div class="bg-white rounded-2xl border border-slate-100 p-6 mb-6">
        <div class="flex items-center gap-4 mb-6">
            <img src="{{ $teacher->profile_url ?? 'https://ui-avatars.com/api/?name=' . urlencode($teacher->full_name) . '&background=F1F5F9&color=64748B&bold=true' }}" class="h-16 w-16 rounded-full object-cover border border-slate-100">
            <div>
                <h2 class="font-black text-slate-900">{{ $teacher->full_name }}</h2>
                <p class="text-xs text-slate-500">{{ $teacher->role->name ?? 'Teacher' }} &middot; {{ $teacher->institute->institute_name ?? '' }}</p>
            </div>
        </div>

        <form action="{{ route('teacher.profile.avatar') }}" method="POST" enctype="multipart/form-data" class="flex items-center gap-3">
            @csrf
            <input type="file" name="profile_image" accept="image/*" class="text-xs">
            <button type="submit" class="px-3 py-2 bg-slate-100 hover:bg-slate-200 rounded-lg text-xs font-bold text-slate-700">Update Photo</button>
        </form>

        <dl class="grid grid-cols-1 sm:grid-cols-2 gap-4 mt-6 text-sm">
            <div><dt class="text-[10px] uppercase font-black text-slate-400">Email</dt><dd class="font-semibold text-slate-800">{{ $teacher->email }}</dd></div>
            <div><dt class="text-[10px] uppercase font-black text-slate-400">Phone</dt><dd class="font-semibold text-slate-800">{{ $teacher->phone ?: '-' }}</dd></div>
            <div><dt class="text-[10px] uppercase font-black text-slate-400">Employee ID</dt><dd class="font-semibold text-slate-800">{{ $teacher->employee_id }}</dd></div>
            <div><dt class="text-[10px] uppercase font-black text-slate-400">Department</dt><dd class="font-semibold text-slate-800">{{ $teacher->departments->pluck('name')->implode(', ') ?: '-' }}</dd></div>
        </dl>
    </div>

    <div class="bg-white rounded-2xl border border-slate-100 p-6 mb-6">
        <h3 class="font-black text-slate-800 mb-1">Attendance Summary</h3>
        <p class="text-xs text-slate-500 mb-4">Your recorded attendance.</p>
        <div class="grid grid-cols-2 gap-4">
            <div class="p-4 bg-emerald-50 rounded-xl text-center">
                <p class="text-2xl font-black text-emerald-600">{{ $teacher->attendances->where('status', 'Present')->count() }}</p>
                <p class="text-[10px] font-bold uppercase text-emerald-600">Present</p>
            </div>
            <div class="p-4 bg-rose-50 rounded-xl text-center">
                <p class="text-2xl font-black text-rose-600">{{ $teacher->attendances->where('status', 'Absent')->count() }}</p>
                <p class="text-[10px] font-bold uppercase text-rose-600">Absent</p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-2xl border border-slate-100 p-6">
        <h3 class="font-black text-slate-800 mb-1">Security</h3>
        <p class="text-xs text-slate-500 mb-4">Change your account password.</p>
        <a href="{{ route('teacher.password.change') }}" class="inline-block px-4 py-2.5 bg-primary text-white rounded-lg text-xs font-bold hover:bg-orange-700">Change Password</a>
    </div>
</div>
@endsection
