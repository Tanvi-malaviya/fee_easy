<x-admin-layout title="Activity Monitoring">
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center">
            <div>
                <h2 class="font-bold text-2xl text-gray-800 leading-tight tracking-tight">
                    {{ __('Activity Monitoring') }}
                </h2>
                <p class="text-sm text-gray-500 mt-1">Real-time audit trail of all platform events and administrative actions.</p>
            </div>
            <div class="mt-4 sm:mt-0 flex gap-3">
                <span class="inline-flex items-center px-4 py-2 border border-emerald-200 rounded-lg shadow-sm text-sm font-bold text-emerald-600 bg-emerald-50">
                    <span class="flex h-2 w-2 mr-2">
                      <span class="animate-ping absolute inline-flex h-2 w-2 rounded-full bg-emerald-400 opacity-75"></span>
                      <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
                    </span>
                    Live Feed Active
                </span>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white border border-gray-100 rounded-3xl shadow-sm overflow-hidden min-h-[600px]">
                <div class="px-8 py-6 border-b border-gray-50 bg-gray-50/30 flex justify-between items-center">
                    <h3 class="text-xs font-black text-gray-400 uppercase tracking-[0.2em]">Latest System Events</h3>
                    <div class="text-[10px] font-black text-indigo-500 bg-indigo-50 px-3 py-1 rounded-full uppercase">Showing Last 50 Events</div>
                </div>

                <div class="p-8">
                    <div class="relative">
                        <!-- Vertical Line -->
                        <div class="absolute left-6 top-0 bottom-0 w-0.5 bg-gray-100"></div>

                        <div class="space-y-10">
                            @forelse($activities as $activity)
                                <div class="relative pl-16">
                                    <!-- Icon Circle -->
                                    <div class="absolute left-0 top-0 w-12 h-12 rounded-2xl bg-white border border-gray-100 flex items-center justify-center shadow-sm z-10 transition hover:scale-110">
                                        @if(str_contains(strtolower($activity->activity), 'registered'))
                                            <span class="text-xl">🏢</span>
                                        @elseif(str_contains(strtolower($activity->activity), 'subscription') || str_contains(strtolower($activity->activity), 'paid'))
                                            <span class="text-xl">💳</span>
                                        @elseif(str_contains(strtolower($activity->activity), 'broadcast'))
                                            <span class="text-xl">📣</span>
                                        @elseif(str_contains(strtolower($activity->activity), 'setting'))
                                            <span class="text-xl">⚙️</span>
                                        @elseif(str_contains(strtolower($activity->activity), 'deleted'))
                                            <span class="text-xl text-red-500">🗑️</span>
                                        @else
                                            <span class="text-xl">📝</span>
                                        @endif
                                    </div>

                                    <div class="bg-gray-50/50 p-6 rounded-2xl border border-gray-100/50 hover:border-indigo-100 transition-colors">
                                        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                                            <div>
                                                <h4 class="text-base font-bold text-gray-900 leading-tight">
                                                    {{ $activity->activity }}
                                                </h4>
                                                <div class="flex items-center mt-2 space-x-4">
                                                    <div class="flex items-center text-[10px] font-black uppercase text-gray-400">
                                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                                        Admin: {{ $activity->user ? $activity->user->name : 'System' }}
                                                    </div>
                                                    <div class="flex items-center text-[10px] font-black uppercase text-indigo-400">
                                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                                                        IP: {{ $activity->ip_address }}
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="text-right">
                                                <div class="text-sm font-black text-gray-900">{{ $activity->created_at->format('h:i A') }}</div>
                                                <div class="text-[10px] font-black text-gray-400 mt-0.5 uppercase tracking-widest">{{ $activity->created_at->format('d M Y') }}</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="py-20 text-center">
                                    <div class="text-4xl mb-4 opacity-30">🛰️</div>
                                    <p class="text-gray-400 font-bold">Waiting for new activities...</p>
                                </div>
                            @endforelse
                        </div>
                    </div>

                    <div class="mt-12">
                        {{ $activities->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>
