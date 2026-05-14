<x-admin-layout title="{{ $institute->institute_name }} - Details">
    <div class="max-w-6xl mx-auto">
        <div class="bg-white border border-gray-100 rounded-3xl shadow-sm overflow-hidden p-5">
            <!-- Header Section -->
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 border-b border-gray-50 pb-4 mb-4">
                <div class="flex items-center gap-4">
                    <a href="{{ route('institutes.index') }}"
                        class="p-2 bg-gray-50 rounded-xl text-gray-400 hover:text-primary transition shadow-sm active:scale-90">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                    </a>
                    <div class="flex items-center gap-3">
                        <!-- Logo in Header -->
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 rounded-lg bg-gray-50 border border-gray-100 flex items-center justify-center overflow-hidden shadow-sm">
                                @if($institute->logo)
                                    <img src="{{ asset('storage/' . $institute->logo) }}" class="w-full h-full object-cover">
                                @else
                                    <div class="text-sm">🏢</div>
                                @endif
                            </div>
                        </div>
                        <h2 class="text-xl font-black text-gray-900 tracking-tight">{{ $institute->institute_name }}</h2>
                        <span class="px-2 py-0.5 rounded-full text-[9px] font-black uppercase tracking-wider
                            @if($institute->status == 'active') bg-emerald-50 text-emerald-600 border border-emerald-100 
                            @elseif($institute->status == 'suspended') bg-amber-50 text-amber-600 border border-amber-100
                            @else bg-red-50 text-red-600 border border-red-100 @endif">
                            ● {{ $institute->status }}
                        </span>
                    </div>
                </div>
                
                <div class="flex items-center gap-3">
                    <a href="{{ route('institutes.edit', $institute) }}"
                        class="inline-flex items-center px-5 py-2 bg-primary text-white rounded-xl text-[10px] font-bold uppercase tracking-widest shadow-lg shadow-primary/20 hover:opacity-90 transition active:scale-95">
                        <svg class="w-3.5 h-3.5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                            </path>
                        </svg>
                        Edit Details
                    </a>
                </div>
            </div>

            <!-- Single Line Primary & Location Details -->
            <div class="flex flex-wrap items-center gap-x-5 gap-y-4 px-2">
                <!-- Primary Fields -->
                <div class="flex flex-col">
                    <span class="text-[9px] font-bold text-gray-400 uppercase tracking-widest leading-none">Owner</span>
                    <span class="text-sm font-bold text-gray-900 mt-1">{{ $institute->name }}</span>
                </div>

                <div class="flex flex-col border-l border-gray-100 pl-5">
                    <span class="text-[9px] font-bold text-gray-400 uppercase tracking-widest leading-none">Email</span>
                    <span class="text-sm font-bold text-gray-700 mt-1">{{ $institute->email }}</span>
                </div>

                <div class="flex flex-col border-l border-gray-100 pl-5">
                    <span class="text-[9px] font-bold text-gray-400 uppercase tracking-widest leading-none">Phone</span>
                    <span class="text-sm font-bold text-gray-700 mt-1">{{ $institute->phone }}</span>
                </div>

                <!-- Location Fields -->
                <div class="flex flex-col border-l border-gray-100 pl-5">
                    <span class="text-[9px] font-bold text-gray-400 uppercase tracking-widest leading-none">Address</span>
                    <span class="text-sm font-bold text-gray-700 mt-1">{{ $institute->address ?? 'N/A' }}</span>
                </div>

                <div class="flex flex-col border-l border-gray-100 pl-5">
                    <span class="text-[9px] font-bold text-gray-400 uppercase tracking-widest leading-none">City</span>
                    <span class="text-sm font-bold text-gray-700 mt-1">{{ $institute->city ?? 'N/A' }}</span>
                </div>

                <div class="flex flex-col border-l border-gray-100 pl-8">
                    <span class="text-[9px] font-bold text-gray-400 uppercase tracking-widest leading-none">State</span>
                    <span class="text-sm font-bold text-gray-700 mt-1">{{ $institute->state ?? 'N/A' }}</span>
                </div>

                <div class="flex flex-col border-l border-gray-100 pl-8">
                    <span class="text-[9px] font-bold text-gray-400 uppercase tracking-widest leading-none">Pincode</span>
                    <span class="text-sm font-bold text-gray-700 mt-1">{{ $institute->pincode ?? 'N/A' }}</span>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>