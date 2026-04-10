<x-admin-layout title="Broadcast Center">
    <div class=" min-h-screen bg-[#f8fafc]">
        <div class="max-w-7xl mx-auto">
            <!-- Header Section with Stats/Context -->
            <!-- <div class="mb-8 flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                    <h2 class="text-3xl font-extrabold text-gray-900 tracking-tight">Broadcast Center</h2>
                    <p class="mt-1 text-sm font-medium text-gray-500">Reach your community with high-impact announcements.</p>
                </div>
                <div class="flex items-center gap-3">
                    @if(session('success'))
                        <div class="px-4 py-2 bg-emerald-50 border border-emerald-100 rounded-2xl shadow-sm flex items-center gap-3 text-emerald-700 text-xs font-bold animate-bounce">
                            <span>{{ session('success') }}</span>
                        </div>
                    @endif
                    <div class="px-4 py-2 bg-white border border-gray-200 rounded-2xl shadow-sm flex items-center gap-3">
                        <div class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></div>
                        <span class="text-xs font-bold text-gray-700 uppercase tracking-wider">Systems Nominal</span>
                    </div>
                </div>
            </div> -->

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-4 items-start">
                <!-- Compose Column -->
                <div class="lg:col-span-8">
                    <div class="bg-white rounded-[2.5rem] shadow-xl shadow-indigo-500/5 border border-gray-100 overflow-hidden transition-all duration-300">
                        <div class="p-8 sm:p-12">
                            <div class="flex items-center gap-4 mb-10">
                                <div class="w-12 h-12 rounded-2xl bg-indigo-50 flex items-center justify-center">
                                    <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-xl font-bold text-gray-900">Compose Dispatch</h3>
                                    <p class="text-xs font-semibold text-gray-400 uppercase tracking-widest mt-0.5">Prepare manual announcement</p>
                                </div>
                            </div>

                            <form action="{{ route('broadcast.send') }}" method="POST" class="space-y-8">
                                @csrf
                                <input type="hidden" name="channels[]" value="dashboard">
                                
                                @if($errors->any())
                                    <div class="p-4 bg-red-50 border border-red-100 rounded-2xl">
                                        <ul class="list-disc list-inside text-xs font-bold text-red-600 space-y-1">
                                            @foreach($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif
                                <div>
                                    <label for="title" class="block text-sm font-bold text-gray-700 mb-3">Announcement Title</label>
                                    <input type="text" id="title" name="title" required
                                        class="block w-full px-6 py-4 text-lg font-bold text-gray-900 bg-gray-50 border-transparent rounded-2xl focus:bg-white focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all duration-300 placeholder:text-gray-300"
                                        placeholder="e.g. Scheduled System Upgrade">
                                </div>

                                <!-- Message Field -->
                                <div>
                                    <label for="message" class="block text-sm font-bold text-gray-700 mb-3">Message Content</label>
                                    <textarea name="message" id="message" rows="8" required
                                        class="block w-full px-6 py-5 text-base font-medium text-gray-700 bg-gray-50 border-transparent rounded-[2rem] focus:bg-white focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all duration-300 placeholder:text-gray-300 leading-relaxed"
                                        placeholder="Type your detailed announcement here... Describe the what, when, and why."></textarea>
                                </div>

                                <!-- Target Audience Cards -->
                                <div x-data="{ target: 'all' }">
                                    <input type="hidden" name="target" :value="target">
                                    <label class="block text-sm font-bold text-gray-700 mb-4">Select Target Audience</label>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <!-- All Registered -->
                                        <div @click="target = 'all'" 
                                            :class="target === 'all' ? 'border-indigo-600 bg-indigo-50/50 ring-4 ring-indigo-500/5' : 'border-gray-100 bg-white hover:border-indigo-200'"
                                            class="cursor-pointer p-6 rounded-3xl border-2 transition-all duration-300 group">
                                            <div class="flex items-center justify-between mb-4">
                                                <div :class="target === 'all' ? 'bg-indigo-600 text-white' : 'bg-gray-100 text-gray-400 group-hover:bg-indigo-100 group-hover:text-indigo-600'"
                                                    class="w-10 h-10 rounded-xl flex items-center justify-center transition-colors">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                                                </div>
                                                <div x-show="target === 'all'" class="w-5 h-5 rounded-full bg-indigo-600 flex items-center justify-center">
                                                    <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                                                </div>
                                            </div>
                                            <h4 class="font-bold text-gray-900">All Registered</h4>
                                            <p class="text-xs font-semibold text-gray-500 mt-1">Broadcast to every user in the database</p>
                                        </div>

                                        <!-- Active Subscriptions -->
                                        <div @click="target = 'active'" 
                                            :class="target === 'active' ? 'border-indigo-600 bg-indigo-50/50 ring-4 ring-indigo-500/5' : 'border-gray-100 bg-white hover:border-indigo-200'"
                                            class="cursor-pointer p-6 rounded-3xl border-2 transition-all duration-300 group">
                                            <div class="flex items-center justify-between mb-4">
                                                <div :class="target === 'active' ? 'bg-indigo-600 text-white' : 'bg-gray-100 text-gray-400 group-hover:bg-indigo-100 group-hover:text-indigo-600'"
                                                    class="w-10 h-10 rounded-xl flex items-center justify-center transition-colors">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path></svg>
                                                </div>
                                                <div x-show="target === 'active'" class="w-5 h-5 rounded-full bg-indigo-600 flex items-center justify-center">
                                                    <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                                                </div>
                                            </div>
                                            <h4 class="font-bold text-gray-900">Active Only</h4>
                                            <p class="text-xs font-semibold text-gray-500 mt-1">Only users with currently active plans</p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Action Buttons -->
                                <div class="flex items-center justify-end gap-4 pt-6">
                                    <!-- <button type="button" class="px-6 py-4 text-sm font-bold text-gray-500 hover:text-gray-700 transition">Save Draft</button> -->
                                    <button type="submit"
                                        class="group relative inline-flex items-center gap-3 px-8 py-3 bg-indigo-600 text-white rounded-xl font-bold text-xs uppercase tracking-widest hover:bg-indigo-700 transition-all duration-300 shadow-lg shadow-indigo-600/20 active:scale-95">
                                        <span>Dispatch Now</span>
                                        <svg class="w-4 h-4 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                                        </svg>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- History Column -->
                <div class="lg:col-span-4 space-y-6">
                    <!-- Stats Card -->
                 

                    <!-- History Timeline Card -->
                    <div class="bg-white rounded-[2.5rem] shadow-xl shadow-indigo-500/5 border border-gray-100 overflow-hidden">
                        <div class="p-6 border-b border-gray-50 flex items-center justify-between">
                            <h3 class="font-bold text-gray-900">Recent Dispatch</h3>
                            <!-- <a href="#" class="text-[10px] font-bold text-indigo-600 uppercase tracking-wider hover:underline">View All</a> -->
                        </div>
                        <div class="divide-y divide-gray-50">
                            @forelse($recentNotifications as $notification)
                                <div class="p-4 hover:bg-indigo-50/30 transition-colors cursor-default group">
                                    <div class="flex gap-4">
                                        <div class="flex-shrink-0 flex flex-col items-center">
                                            <div class="w-2.5 h-2.5 rounded-full bg-emerald-500 border-4 border-emerald-100"></div>
                                            <div class="w-0.5 h-full bg-gray-100 my-2"></div>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <div class="flex items-start justify-between mb-1">
                                                <h4 class="text-sm font-extrabold text-gray-900 truncate pr-4">{{ $notification->title }}</h4>
                                                <span class="text-[9px] font-bold text-gray-400 whitespace-nowrap uppercase">{{ $notification->created_at->diffForHumans(null, true) }}</span>
                                            </div>
                                            <p class="text-xs font-medium text-gray-500 line-clamp-2 leading-relaxed mb-4">{{ $notification->message }}</p>
                                            <div class="flex items-center justify-between">
                                                <div class="flex flex-col">
                                                    <span class="text-[10px] font-bold text-gray-400 uppercase tracking-tighter">{{ $notification->created_at->format('M d, Y') }}</span>
                                                    <span class="text-[9px] font-medium text-gray-400 uppercase">{{ $notification->created_at->format('h:i A') }}</span>
                                                </div>
                                                <div class="px-3 py-1 bg-emerald-50 text-emerald-700 text-[9px] font-black uppercase tracking-widest rounded-full border border-emerald-100 group-hover:bg-emerald-500 group-hover:text-white transition-colors">
                                                    Sent
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="py-20 flex flex-col items-center justify-center text-center px-8">
                                    <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mb-6">
                                        <svg class="w-10 h-10 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"></path></svg>
                                    </div>
                                    <h4 class="font-bold text-gray-900">Quiet in here...</h4>
                                    <p class="text-xs font-medium text-gray-400 mt-2 leading-relaxed">Your broadcast history will appear here once you send your first announcement.</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>