<x-admin-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center">
            <div>
                <h2 class="font-bold text-2xl text-gray-800 leading-tight tracking-tight">
                    {{ __('Broadcast Center') }}
                </h2>
                <p class="text-sm text-gray-500 mt-1">Communicate mass messages to your institutes via Dashboard & WhatsApp.</p>
            </div>
            <div class="mt-4 sm:mt-0">
                <span class="inline-flex items-center px-4 py-2 border border-blue-200 rounded-lg shadow-sm text-sm font-bold text-blue-600 bg-blue-50">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.167H3.3a1.598 1.598 0 01-1.283-2.502l3.417-6.284zm3.636 0l3.417 6.284a1.598 1.598 0 01-1.283 2.502h-1.954l-2.147 6.167a1.76 1.76 0 01-3.417-.592V5.882"></path></svg>
                    Public Announcement System
                </span>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                
                <!-- Compose Column -->
                <div class="lg:col-span-2">
                    <div class="bg-white border border-gray-100 rounded-3xl shadow-sm overflow-hidden">
                        <div class="p-8">
                            <h3 class="text-xl font-black text-gray-900 border-b pb-4 mb-6 italic">Compose New Message</h3>
                            
                            <form action="{{ route('broadcast.send') }}" method="POST">
                                @csrf
                                <div class="mb-6">
                                    <x-input-label for="title" value="Announcement Title" class="text-[10px] font-black uppercase tracking-[0.2em]" />
                                    <x-text-input id="title" name="title" type="text" class="mt-1 block w-full text-lg font-bold" placeholder="e.g. System Maintenance Update" required />
                                </div>

                                <div class="mb-6">
                                    <x-input-label for="message" value="Detailed Message" class="text-[10px] font-black uppercase tracking-[0.2em]" />
                                    <textarea name="message" id="message" rows="6" class="mt-1 block w-full rounded-2xl border-gray-200 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm text-sm" placeholder="Write your announcement here..." required></textarea>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                                    <!-- Targeting -->
                                    <div class="p-5 bg-gray-50/50 rounded-2xl border border-gray-100">
                                        <h4 class="text-[10px] font-black uppercase tracking-[0.2em] text-gray-400 mb-4">Target Audience</h4>
                                        <div class="space-y-3">
                                            <label class="flex items-center">
                                                <input type="radio" name="target" value="all" checked class="text-indigo-600 focus:ring-indigo-500">
                                                <span class="ml-3 text-sm font-black text-gray-700">All Registered Institutes</span>
                                            </label>
                                            <label class="flex items-center">
                                                <input type="radio" name="target" value="active" class="text-indigo-600 focus:ring-indigo-500">
                                                <span class="ml-3 text-sm font-black text-gray-700">Active Subscriptions Only</span>
                                            </label>
                                        </div>
                                    </div>

                                    <!-- Channels -->
                                    <div class="p-5 bg-gray-50/50 rounded-2xl border border-gray-100">
                                        <h4 class="text-[10px] font-black uppercase tracking-[0.2em] text-gray-400 mb-4">Delivery Channels</h4>
                                        <div class="space-y-3">
                                            <label class="flex items-center">
                                                <input type="checkbox" name="channels[]" value="dashboard" checked class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
                                                <span class="ml-3 text-sm font-black text-gray-700">Internal Dashboard Alert</span>
                                            </label>
                                            <label class="flex items-center opacity-70">
                                                <input type="checkbox" name="channels[]" value="whatsapp" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
                                                <span class="ml-3 text-sm font-black text-gray-700">WhatsApp (Official API)</span>
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <div class="flex justify-end">
                                    <button type="submit" class="px-8 py-4 bg-indigo-600 text-white rounded-2xl font-black text-sm uppercase tracking-widest hover:bg-indigo-700 transition shadow-xl shadow-indigo-200 flex items-center">
                                        🚀 Dispatch Announcement
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- History Column -->
                <div class="lg:col-span-1">
                    <div class="bg-white border border-gray-100 rounded-3xl shadow-sm overflow-hidden">
                        <div class="p-6 border-b border-gray-50 bg-gray-50/30 font-bold text-gray-700 flex justify-between items-center">
                            <span>Recent History</span>
                            <span class="text-[10px] font-black uppercase text-gray-400">Past 10</span>
                        </div>
                        <div class="divide-y divide-gray-50">
                            @forelse($recentNotifications as $notification)
                                <div class="p-5 hover:bg-gray-50 transition duration-200">
                                    <h4 class="text-sm font-bold text-gray-900 truncate">{{ $notification->title }}</h4>
                                    <p class="text-xs text-gray-400 mt-1 line-clamp-2 leading-relaxed">{{ $notification->message }}</p>
                                    <div class="flex justify-between items-center mt-3">
                                        <span class="text-[9px] font-black text-indigo-500 uppercase tracking-widest">{{ $notification->created_at->diffForHumans() }}</span>
                                        <span class="px-2 py-0.5 rounded text-[8px] font-black bg-blue-50 text-blue-600 uppercase">System</span>
                                    </div>
                                </div>
                            @empty
                                <div class="p-10 text-center">
                                    <p class="text-sm text-gray-400">No recent broadcasts sent.</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-admin-layout>
