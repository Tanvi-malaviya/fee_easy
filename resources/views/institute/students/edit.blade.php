@extends('layouts.institute')

@section('content')
    <div class="max-w-6xl mx-auto">
        <form action="{{ route('institute.students.update', $student->id) }}" method="POST" enctype="multipart/form-data"
            class="space-y-2">
            @csrf
            @method('PUT')

            <!-- Basic Information & Identity -->
            <div class="bg-white rounded-3xl border border-slate-100 shadow-sm p-3">
                <div class="flex flex-col lg:flex-row gap-5">
                    <!-- Photo Column -->
                    <div class="flex flex-col items-center flex-none">
                        <div class="relative group">
                            <div class="h-28 w-28 rounded-3xl bg-slate-50 border-2 border-dashed border-slate-200 overflow-hidden flex items-center justify-center group-hover:border-orange-500/30 transition-all">
                                <img id="image-preview" src="{{ $student->profile_image_url }}" class="w-full h-full object-cover">
                                <div class="absolute inset-0 bg-black/20 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                </div>
                            </div>
                            <label for="profile_image" class="absolute -bottom-2 -right-2 h-8 w-8 bg-orange-500 text-white rounded-xl flex items-center justify-center cursor-pointer shadow-lg shadow-orange-500/20 hover:scale-110 transition-transform">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                </svg>
                            </label>
                            <input type="file" name="profile_image" id="profile_image" accept="image/*" class="hidden" onchange="previewImage(event)">
                        </div>
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mt-3">Student Photo</p>
                    </div>

                    <!-- Information Column -->
                    <div class="flex-1">
                        <div class="flex items-center gap-3 mb-3">
                            <div class="h-8 w-8 bg-blue-50 text-blue-500 rounded-xl flex items-center justify-center">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                            </div>
                            <h2 class="text-lg font-bold text-slate-800 tracking-tight">Basic Information</h2>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
                            <div class="space-y-1">
                                <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest ml-1">Student Name</label>
                                <input type="text" name="name" required value="{{ old('name', $student->name) }}" placeholder="Arjun Malhotra"
                                    class="w-full px-4 py-2.5 bg-slate-50 border border-slate-100 rounded-xl text-sm font-bold outline-none focus:ring-4 focus:ring-blue-500/5 transition-all @error('name') border-rose-500 @enderror">
                                @error('name') <p class="text-rose-500 text-[10px] font-bold mt-1 ml-1">{{ $message }}</p> @enderror
                            </div>

                            <div class="space-y-1">
                                <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest ml-1">Phone Number</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none">
                                        <span class="text-sm font-bold text-slate-400">+91</span>
                                    </div>
                                    <input type="text" name="phone" required value="{{ old('phone', $student->phone) }}" placeholder="98765 43210"
                                        class="w-full pl-14 pr-4 py-2.5 bg-slate-50 border border-slate-100 rounded-xl text-sm font-bold outline-none focus:ring-4 focus:ring-blue-500/5 transition-all @error('phone') border-rose-500 @enderror">
                                </div>
                                @error('phone') <p class="text-rose-500 text-[10px] font-bold mt-1 ml-1">{{ $message }}</p> @enderror
                            </div>

                            <div class="space-y-1">
                                <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest ml-1">Email Address</label>
                                <input type="email" name="email" required value="{{ old('email', $student->email) }}" placeholder="arjun@tuoora.edu"
                                    class="w-full px-4 py-2.5 bg-slate-50 border border-slate-100 rounded-xl text-sm font-bold outline-none focus:ring-4 focus:ring-blue-500/5 transition-all @error('email') border-rose-500 @enderror">
                                @error('email') <p class="text-rose-500 text-[10px] font-bold mt-1 ml-1">{{ $message }}</p> @enderror
                            </div>

                            <div class="space-y-1">
                                <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest ml-1">Date of Birth</label>
                                <input type="date" name="dob" required value="{{ old('dob', $student->dob) }}"
                                    class="w-full px-4 py-2.5 bg-slate-50 border border-slate-100 rounded-xl text-sm font-bold outline-none focus:ring-4 focus:ring-blue-500/5 transition-all @error('dob') border-rose-500 @enderror">
                                @error('dob') <p class="text-rose-500 text-[10px] font-bold mt-1 ml-1">{{ $message }}</p> @enderror
                            </div>

                            <div class="space-y-1">
                                <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest ml-1">Standard / Grade</label>
                                <input type="text" name="standard" value="{{ old('standard', $student->standard) }}" placeholder="e.g. 10th Grade"
                                    class="w-full px-4 py-2.5 bg-slate-50 border border-slate-100 rounded-xl text-sm font-bold outline-none focus:ring-4 focus:ring-blue-500/5 transition-all @error('standard') border-rose-500 @enderror">
                                @error('standard') <p class="text-rose-500 text-[10px] font-bold mt-1 ml-1">{{ $message }}</p> @enderror
                            </div>

                            <div class="space-y-1">
                                <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest ml-1">Guardian Name</label>
                                <input type="text" name="guardian_name" value="{{ old('guardian_name', $student->guardian_name) }}" placeholder="Mr. Rajesh Malhotra"
                                    class="w-full px-4 py-2.5 bg-slate-50 border border-slate-100 rounded-xl text-sm font-bold outline-none focus:ring-4 focus:ring-blue-500/5 transition-all @error('guardian_name') border-rose-500 @enderror">
                                @error('guardian_name') <p class="text-rose-500 text-[10px] font-bold mt-1 ml-1">{{ $message }}</p> @enderror
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Residential Address -->
            <div class="bg-white rounded-3xl border border-slate-100 shadow-sm p-4">
                <div class="flex items-center gap-3 mb-3">
                    <div class="h-10 w-10 bg-emerald-50 text-emerald-500 rounded-xl flex items-center justify-center">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                        </svg>
                    </div>
                    <h2 class="text-xl font-medium text-slate-800 tracking-tight">Residential Address</h2>
                </div>

                <div class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                        <div class="space-y-1">
                            <label class="text-[12px] font-medium text-slate-400 uppercase tracking-widest ml-1">Address Line
                                1</label>
                            <input type="text" name="address_line_1"
                                value="{{ old('address_line_1', $student->address_line_1) }}"
                                placeholder="Street address, P.O. box"
                                class="w-full px-4 py-2.5 bg-slate-50 border border-slate-50 rounded-xl text-sm font-bold outline-none focus:ring-4 focus:ring-blue-500/5 transition-all">
                        </div>

                        <div class="space-y-1">
                            <label class="text-[12px] font-medium text-slate-400 uppercase tracking-widest ml-1">Address Line
                                2 (Optional)</label>
                            <input type="text" name="address_line_2"
                                value="{{ old('address_line_2', $student->address_line_2) }}"
                                placeholder="Apartment, suite, unit, etc."
                                class="w-full px-4 py-2.5 bg-slate-50 border border-slate-50 rounded-xl text-sm font-bold outline-none focus:ring-4 focus:ring-blue-500/5 transition-all">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
                        <div class="space-y-1">
                            <label class="text-[12px] font-medium text-slate-400 uppercase tracking-widest ml-1">City</label>
                            <input type="text" name="city" value="{{ old('city', $student->city) }}" placeholder="Gurgaon"
                                class="w-full px-4 py-2.5 bg-slate-50 border border-slate-50 rounded-xl text-sm font-bold outline-none focus:ring-4 focus:ring-blue-500/5 transition-all">
                        </div>

                        <div class="space-y-1">
                            <label
                                class="text-[12px] font-medium text-slate-400 uppercase tracking-widest ml-1">State</label>
                            <input type="text" name="state" value="{{ old('state', $student->state) }}"
                                placeholder="Haryana"
                                class="w-full px-4 py-2.5 bg-slate-50 border border-slate-50 rounded-xl text-sm font-bold outline-none focus:ring-4 focus:ring-blue-500/5 transition-all">
                        </div>

                        <div class="space-y-1">
                            <label
                                class="text-[12px] font-medium text-slate-400 uppercase tracking-widest ml-1">Country</label>
                            <input type="text" name="country" value="{{ old('country', $student->country) }}" placeholder="India"
                                class="w-full px-4 py-2.5 bg-slate-50 border border-slate-50 rounded-xl text-sm font-bold outline-none focus:ring-4 focus:ring-blue-500/5 transition-all">
                        </div>

                        <div class="space-y-1">
                            <label
                                class="text-[12px] font-medium text-slate-400 uppercase tracking-widest ml-1">Pincode</label>
                            <input type="text" name="pincode" value="{{ old('pincode', $student->pincode) }}"
                                placeholder="122001"
                                class="w-full px-4 py-2.5 bg-slate-50 border border-slate-50 rounded-xl text-sm font-bold outline-none focus:ring-4 focus:ring-blue-500/5 transition-all">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="flex items-center justify-end gap-3 mt-6 mb-10 px-4">
                <a href="{{ route('institute.students.index') }}"
                    class="px-8 py-2.5 bg-white border border-[#006666] text-[#006666] rounded-xl font-bold text-sm hover:bg-slate-50 transition-all">
                    Cancel
                </a>
                <button type="submit"
                    class="px-8 py-2.5 bg-[#ff6600] text-white rounded-xl font-bold text-sm shadow-lg shadow-orange-900/10 hover:translate-y-[-2px] transition-all">
                    Save Student
                </button>
            </div>
        </form>
    </div>

    <script>
        function previewImage(event) {
            const reader = new FileReader();
            reader.onload = function () {
                const output = document.getElementById('image-preview');
                output.src = reader.result;
            }
            reader.readAsDataURL(event.target.files[0]);
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