<x-admin-layout title="Broadcast Center">

    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">

                <!-- Compose Column -->
                <div class="lg:col-span-8">
                    <div class="bg-white border border-gray-100 rounded-2xl shadow-sm overflow-hidden">
                        <div class="p-8 sm:p-10">
                            <div class="flex items-center justify-between mb-8 pb-4 border-b border-gray-50/50">
                                <h3 class="text-xl font-bold text-gray-900 leading-none">Compose New Message</h3>
                                <span class="text-[10px] font-semibold text-gray-400 uppercase tracking-wider">Platform Broadcast</span>
                            </div>

                            <form action="{{ route('broadcast.send') }}" method="POST">
                                @csrf
                                <div class="mb-6">
                                    <x-input-label for="title" value="Announcement Title"
                                        class="text-xs font-semibold uppercase tracking-wider text-gray-500 mb-2" />
                                    <x-text-input id="title" name="title" type="text"
                                        class="mt-1 block w-full py-3 px-4 text-base font-bold text-gray-900 border-gray-200 bg-gray-50/50 rounded-xl focus:ring-indigo-500 focus:border-indigo-500 transition shadow-sm"
                                        placeholder="e.g. System Maintenance Update" required />
                                </div>

                                <div class="mb-6">
                                    <x-input-label for="message" value="Detailed Message"
                                        class="text-xs font-semibold uppercase tracking-wider text-gray-500 mb-2" />
                                    <textarea name="message" id="message" rows="6"
                                        class="mt-1 block w-full rounded-2xl border-gray-200 bg-gray-50/50 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm text-sm font-semibold text-gray-700 py-3 px-4 transition leading-relaxed"
                                        placeholder="Write your announcement here..." required></textarea>
                                </div>

                                <!-- Compact Configuration Section -->
                                <div class="bg-gray-50/50 rounded-2xl border border-gray-100 p-6 mb-8">
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-12 gap-y-8">
                                        <!-- Audience -->
                                        <div class="space-y-4">
                                            <div class="flex items-center gap-2 mb-2">
                                                <div class="w-1.5 h-1.5 rounded-full bg-indigo-500"></div>
                                                <h4 class="text-xs font-semibold uppercase tracking-wider text-gray-500">Target Audience</h4>
                                            </div>
                                            <div class="space-y-2">
                                                <label class="flex items-center group cursor-pointer">
                                                    <input type="radio" name="target" value="all" checked
                                                        class="w-4 h-4 text-indigo-600 focus:ring-indigo-500 border-gray-200 transition shadow-sm">
                                                    <span class="ml-3 text-sm font-bold text-gray-700 transition">All Registered</span>
                                                </label>
                                                <label class="flex items-center group cursor-pointer">
                                                    <input type="radio" name="target" value="active"
                                                        class="w-4 h-4 text-indigo-600 focus:ring-indigo-500 border-gray-200 transition shadow-sm">
                                                    <span class="ml-3 text-sm font-bold text-gray-700 transition">Active Subscriptions</span>
                                                </label>
                                            </div>
                                        </div>

                                        <!-- Channels -->
                                        <div class="space-y-4">
                                            <div class="flex items-center gap-2 mb-2">
                                                <div class="w-1.5 h-1.5 rounded-full bg-emerald-500"></div>
                                                <h4 class="text-xs font-semibold uppercase tracking-wider text-gray-500">Delivery Channels</h4>
                                            </div>
                                            <div class="space-y-2">
                                                <label class="flex items-center group cursor-pointer">
                                                    <input type="checkbox" name="channels[]" value="dashboard" checked
                                                        class="w-4 h-4 rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500 transition">
                                                    <span class="ml-3 text-sm font-bold text-gray-700 transition">Internal Dashboard</span>
                                                </label>
                                                <label class="flex items-center group cursor-pointer">
                                                    <input type="checkbox" name="channels[]" value="whatsapp"
                                                        class="w-4 h-4 rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500 transition">
                                                    <span class="ml-3 text-sm font-bold text-gray-700 transition">Official WhatsApp API</span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="flex justify-end">
                                    <button type="submit"
                                        class="px-6 py-2.5 bg-indigo-600 text-white rounded-xl font-bold text-xs uppercase tracking-widest hover:bg-indigo-700 transition transform hover:-translate-y-0.5 shadow-lg shadow-indigo-600/20 flex items-center gap-2">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path></svg>
                                        Dispatch Announcement
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- History Column -->
                <div class="lg:col-span-4">
                    <div class="bg-white border border-gray-100 rounded-2xl shadow-sm overflow-hidden sticky top-8">
                        <div
                            class="p-5 border-b border-gray-50 bg-gray-50/50 font-semibold text-xs uppercase tracking-wider text-gray-500 flex justify-between items-center">
                            <span>Recent History</span>
                            <span class="opacity-50">Past 10</span>
                        </div>
                        <div class="divide-y divide-gray-50">
                            @forelse($recentNotifications as $notification)
                                <div class="p-6 hover:bg-gray-50 transition duration-200">
                                    <h4 class="text-sm font-bold text-gray-900 tracking-tight">{{ $notification->title }}</h4>
                                    <p class="text-xs text-gray-500 mt-2 font-medium line-clamp-2 leading-relaxed">
                                        {{ $notification->message }}</p>
                                    <div class="flex justify-between items-center mt-5">
                                        <span
                                            class="text-[10px] font-bold text-indigo-600 uppercase tracking-wider">{{ $notification->created_at->diffForHumans() }}</span>
                                        <span
                                            class="px-2.5 py-1 rounded-lg text-[10px] font-semibold bg-emerald-50 text-emerald-700 border border-emerald-100 uppercase tracking-wider">Sent</span>
                                    </div>
                                </div>
                            @empty
                                <div class="p-12 text-center">
                                    <p class="text-xs font-semibold uppercase text-gray-400 tracking-wider">No recent broadcasts</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-admin-layout>