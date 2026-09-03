<x-admin-layout title="White Label Add-on">
    <div class="py-0">
        <div class="max-w-7xl mx-auto">

            <!-- Filters -->
            <div class="mb-4">
                <form action="{{ route('whitelabel.index') }}" method="GET" class="flex flex-col md:flex-row gap-4 items-center">
                    <div class="flex-1 w-full">
                        <select name="status" onchange="this.form.submit()"
                            class="block w-full md:w-64 border border-gray-200 rounded-xl py-2.5 px-3 text-sm focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary">
                            <option value="">All Statuses</option>
                            <option value="pending" @selected(request('status') === 'pending')>Pending Payment</option>
                            <option value="active" @selected(request('status') === 'active')>Active</option>
                            <option value="cancelled" @selected(request('status') === 'cancelled')>Cancelled</option>
                        </select>
                    </div>
                </form>
            </div>

            <div class="relative bg-white border border-gray-100 rounded-2xl shadow-sm overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="bg-gray-50/75 border-b border-gray-100">
                                <th class="px-5 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Institute</th>
                                <th class="px-5 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-5 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Branding</th>
                                <th class="px-5 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Purchased</th>
                                <th class="px-5 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider text-center">Review</th>
                                <th class="px-5 py-4 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 bg-white">
                            @forelse($records as $record)
                                <tr class="hover:bg-gray-50/50 transition duration-150">
                                    <td class="px-5 py-3 whitespace-nowrap">
                                        <div class="text-sm font-bold text-gray-900 leading-tight">{{ $record->institute->institute_name ?? '—' }}</div>
                                        <div class="text-[10px] text-gray-400 font-semibold uppercase tracking-widest mt-0.5">
                                            {{ $record->institute->email ?? '' }}
                                        </div>
                                    </td>
                                    <td class="px-5 py-3 whitespace-nowrap">
                                        @if($record->status === 'active')
                                            <span class="px-2.5 py-1 rounded-lg text-[10px] font-black uppercase tracking-wider bg-emerald-50 text-emerald-700 border border-emerald-200">Active</span>
                                        @elseif($record->status === 'pending')
                                            <span class="px-2.5 py-1 rounded-lg text-[10px] font-black uppercase tracking-wider bg-amber-50 text-amber-700 border border-amber-200">Pending Payment</span>
                                        @else
                                            <span class="px-2.5 py-1 rounded-lg text-[10px] font-black uppercase tracking-wider bg-gray-100 text-gray-500 border border-gray-200">Cancelled</span>
                                        @endif
                                    </td>
                                    <td class="px-5 py-3 whitespace-nowrap">
                                        @if($record->branding_complete)
                                            <div class="flex items-center gap-2">
                                                @if($record->app_logo_url)
                                                    <img src="{{ $record->app_logo_url }}" class="w-7 h-7 rounded-md object-cover border border-gray-200" alt="">
                                                @endif
                                                <span class="text-sm font-semibold text-gray-800">{{ $record->app_name }}</span>
                                                @if($record->primary_color)
                                                    <span class="w-4 h-4 rounded-full border border-gray-200" style="background-color: {{ $record->primary_color }}" title="{{ $record->primary_color }}"></span>
                                                @endif
                                            </div>
                                        @else
                                            <span class="text-xs text-gray-400 italic">Not submitted yet</span>
                                        @endif
                                    </td>
                                    <td class="px-5 py-3 whitespace-nowrap">
                                        <span class="text-xs font-bold text-gray-500">
                                            {{ $record->purchased_at ? $record->purchased_at->format('M d, Y') : '—' }}
                                        </span>
                                    </td>
                                    <td class="px-5 py-3 whitespace-nowrap text-center">
                                        @if($record->admin_confirmed_at)
                                            <span class="px-2.5 py-1 rounded-lg text-[10px] font-black uppercase tracking-wider bg-emerald-50 text-emerald-700 border border-emerald-200">Confirmed</span>
                                        @elseif($record->branding_complete)
                                            <span class="px-2.5 py-1 rounded-lg text-[10px] font-black uppercase tracking-wider bg-blue-50 text-blue-700 border border-blue-200">Awaiting Review</span>
                                        @else
                                            <span class="text-xs text-gray-300">—</span>
                                        @endif
                                    </td>
                                    <td class="px-5 py-3 whitespace-nowrap text-right text-xs">
                                        @if($record->branding_complete && !$record->admin_confirmed_at)
                                            <form action="{{ route('whitelabel.confirm', $record->id) }}" method="POST" class="inline-flex">
                                                @csrf
                                                <button type="submit"
                                                    class="text-emerald-700 hover:text-emerald-900 transition-colors px-3 py-1.5 bg-emerald-50 hover:bg-emerald-100 rounded-lg font-semibold">
                                                    Confirm for Publishing
                                                </button>
                                            </form>
                                        @else
                                            <span class="text-gray-300">—</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="p-0">
                                        <x-empty-state
                                            title="No White Label purchases yet"
                                            subtitle="Once an institute buys the Mobile App White Label add-on, it will show up here for review."
                                            icon="teacher"
                                            plain="true"
                                            class="py-12"
                                        />
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($records->hasPages())
                    <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/50">
                        {{ $records->appends(request()->query())->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-admin-layout>
