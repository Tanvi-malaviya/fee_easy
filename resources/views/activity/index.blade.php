<x-admin-layout title="Activity Monitoring">

    <div class="">
        <div class="max-w-7xl mx-auto">
            
       
            <div class="bg-white border border-gray-100 rounded-2xl shadow-sm overflow-hidden min-h-[600px]">
                <div class="px-8 py-5 border-b border-gray-50 bg-gray-50/75 flex justify-between items-center">
                    <h3 class="text-xs font-bold text-gray-500 uppercase tracking-wider">Latest System Events</h3>
                    <div class="text-[10px] font-bold text-indigo-600 bg-indigo-50 border border-indigo-100 px-3 py-1 rounded-lg uppercase tracking-wider">Sync Active</div>
                </div>

                <div class="p-8">
                    <div class="relative">
                        <!-- Vertical Line -->
                        <div class="absolute left-6 top-0 bottom-0 w-0.5 bg-gray-100"></div>

                        <div class="space-y-10">
                            @forelse($activities as $activity)
                                <div class="relative pl-16">
                                    <!-- Icon Circle -->
                                    <div class="absolute left-0 top-1 w-12 h-12 rounded-xl bg-white border border-gray-100 flex items-center justify-center shadow-sm z-10 transition hover:shadow-md">
                                        @if(str_contains(strtolower($activity->activity), 'registered'))
                                            <svg class="w-6 h-6 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                                        @elseif(str_contains(strtolower($activity->activity), 'subscription') || str_contains(strtolower($activity->activity), 'paid'))
                                            <svg class="w-6 h-6 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
                                        @elseif(str_contains(strtolower($activity->activity), 'broadcast'))
                                            <svg class="w-6 h-6 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"></path></svg>
                                        @elseif(str_contains(strtolower($activity->activity), 'setting'))
                                            <svg class="w-6 h-6 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                        @elseif(str_contains(strtolower($activity->activity), 'deleted'))
                                            <svg class="w-6 h-6 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        @else
                                            <svg class="w-6 h-6 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                                        @endif
                                    </div>

                                    <div class="bg-gray-50/30 p-6 rounded-2xl border border-gray-100/50 hover:bg-white hover:border-indigo-100 transition-all duration-300">
                                        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                                            <div>
                                                <h4 class="text-base font-bold text-gray-900 leading-tight">
                                                    {{ $activity->activity }}
                                                </h4>
                                                <div class="flex items-center mt-2 space-x-4">
                                                    <div class="flex items-center text-[10px] font-bold uppercase tracking-wider text-gray-400">
                                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                                        Admin: {{ $activity->user ? $activity->user->name : 'System' }}
                                                    </div>
                                                    <div class="flex items-center text-[10px] font-bold uppercase tracking-wider text-indigo-400">
                                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                                                        IP: {{ $activity->ip_address }}
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="text-right whitespace-nowrap">
                                                <!-- <div class="text-sm font-bold text-gray-900">{{ $activity->created_at->format('h:i A') }}</div> -->
                                                <div class="text-sm font-bold text-gray-900">{{ $activity->created_at->format('d M, Y') }}</div>
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

                    @if($activities->hasPages())
                        <div class="mt-12 border-t border-gray-50 pt-8">
                            {{ $activities->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>
