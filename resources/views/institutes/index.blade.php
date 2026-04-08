<x-admin-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center">
            <div>
                <h2 class="font-bold text-2xl text-gray-800 leading-tight tracking-tight">
                    {{ __('Institutes Management') }}
                </h2>
                <p class="text-sm text-gray-500 mt-1">Manage platform clients, view subscriptions, and handle accounts.</p>
            </div>
            <div class="mt-4 sm:mt-0">
                <a href="{{ route('institutes.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition shadow-indigo-600/30">
                    <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    Create New Institute
                </a>
            </div>
        </div>
    </x-slot>

    <!-- Filters & Search -->
    <div class="mt-6 bg-white p-4 rounded-2xl border border-gray-100 shadow-sm">
        <form action="{{ route('institutes.index') }}" method="GET" class="flex flex-col md:flex-row gap-4">
            <div class="flex-1 relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </div>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by name, institute, email or phone..." class="block w-full pl-10 pr-3 py-2 border border-gray-200 rounded-xl leading-5 bg-gray-50 placeholder-gray-500 focus:outline-none focus:bg-white focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm transition">
            </div>
            <div class="w-full md:w-48">
                <select name="status" onchange="this.form.submit()" class="block w-full pl-3 pr-10 py-2 text-base border-gray-200 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-xl bg-gray-50 transition">
                    <option value="all">All Status</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                    <option value="suspended" {{ request('status') == 'suspended' ? 'selected' : '' }}>Suspended</option>
                    <option value="blocked" {{ request('status') == 'blocked' ? 'selected' : '' }}>Blocked</option>
                </select>
            </div>
          
            @if(request()->has('search') || request()->has('status'))
                <a href="{{ route('institutes.index') }}" class="inline-flex items-center px-4 py-2 text-sm font-medium text-red-600 hover:text-red-800 transition">
                    Clear
                </a>
            @endif
        </form>
    </div>

    <!-- Table Section -->
    <div class="bg-white border border-gray-100 rounded-2xl shadow-sm overflow-hidden mt-6" x-data="{ expanded: null }">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-100">
                <thead class="bg-gray-50/75">
                    <tr>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Institute</th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Contact Info</th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Location</th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                        <th scope="col" class="px-6 py-4 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-100">
                    @forelse($institutes as $institute)
                        <tr class="hover:bg-gray-50/50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center cursor-pointer" @click="expanded = expanded === {{ $institute->id }} ? null : {{ $institute->id }}">
                                    <div class="flex-shrink-0 h-10 w-10 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-700 font-bold text-sm">
                                        {{ substr($institute->name, 0, 1) }}
                                    </div>
                                    <div class="ml-4">
                                        <div class="flex items-center">
                                            <div class="text-sm font-semibold text-gray-900">{{ $institute->institute_name ?? $institute->name }}</div>
                                            <svg class="w-4 h-4 ml-2 text-gray-400 transform transition-transform" :class="expanded === {{ $institute->id }} ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                        </div>
                                        <div class="text-xs text-gray-500">Owner: {{ $institute->name }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900 font-medium whitespace-nowrap">{{ $institute->email }}</div>
                                <div class="text-xs text-gray-500">{{ $institute->phone }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $institute->city ?? 'N/A' }}</div>
                                <div class="text-xs text-gray-500">{{ $institute->state ?? '' }} {{ $institute->pincode ?? '' }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="relative inline-block text-left" x-data="{ open: false }">
                                    <button @click="open = !open" type="button" class="px-2.5 py-1 inline-flex text-xs leading-5 font-semibold rounded-md border transition cursor-pointer
                                        @if($institute->status === 'active') bg-green-100 text-green-700 border-green-200 
                                        @elseif($institute->status === 'suspended') bg-amber-100 text-amber-700 border-amber-200 
                                        @elseif($institute->status === 'blocked') bg-red-100 text-red-700 border-red-200 
                                        @else bg-gray-100 text-gray-700 border-gray-200 @endif">
                                        {{ ucfirst($institute->status) }}
                                        <svg class="w-3 h-3 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                    </button>
                                    
                                    <div x-show="open" @click.away="open = false" class="origin-top-right absolute z-50 mt-2 w-36 rounded-xl shadow-lg bg-white ring-1 ring-black ring-opacity-5 divide-y divide-gray-100 focus:outline-none">
                                        <div class="py-1">
                                            <form action="{{ route('institutes.status', $institute) }}" method="POST">
                                                @csrf @method('PATCH')
                                                <input type="hidden" name="status" value="active">
                                                <button type="submit" class="group flex items-center px-4 py-2 text-xs text-gray-700 hover:bg-green-50 hover:text-green-700 w-full text-left">Activate</button>
                                            </form>
                                            <form action="{{ route('institutes.status', $institute) }}" method="POST">
                                                @csrf @method('PATCH')
                                                <input type="hidden" name="status" value="inactive">
                                                <button type="submit" class="group flex items-center px-4 py-2 text-xs text-gray-700 hover:bg-gray-50 w-full text-left">Deactivate</button>
                                            </form>
                                            <form action="{{ route('institutes.status', $institute) }}" method="POST">
                                                @csrf @method('PATCH')
                                                <input type="hidden" name="status" value="suspended">
                                                <button type="submit" class="group flex items-center px-4 py-2 text-xs text-gray-700 hover:bg-amber-50 hover:text-amber-700 w-full text-left">Suspend</button>
                                            </form>
                                            <form action="{{ route('institutes.status', $institute) }}" method="POST">
                                                @csrf @method('PATCH')
                                                <input type="hidden" name="status" value="blocked">
                                                <button type="submit" class="group flex items-center px-4 py-2 text-xs text-gray-700 hover:bg-red-50 hover:text-red-700 w-full text-left">Block</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex justify-end gap-3">
                                    <a href="{{ route('institutes.edit', $institute) }}" class="text-indigo-600 hover:text-indigo-900 transition-colors p-1 bg-indigo-50 rounded-lg" title="Edit">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                    </a>
                                    <form action="{{ route('institutes.destroy', $institute) }}" method="POST" onsubmit="return confirm('Are you sure?');">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900 transition-colors p-1 bg-red-50 rounded-lg" title="Delete">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        <!-- Expanded Section -->
                        <tr x-show="expanded === {{ $institute->id }}" x-cloak bg-gray-50/30>
                            <td colspan="5" class="px-6 py-4 border-t border-indigo-100 bg-indigo-50/30">
                                <div class="grid grid-cols-2 md:grid-cols-4 gap-6 py-2">
                                    <div>
                                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Full Address</p>
                                        <p class="text-sm text-gray-700 mt-1">{{ $institute->address ?? 'No address provided' }}</p>
                                    </div>
                                    <div>
                                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">State & Pincode</p>
                                        <p class="text-sm text-gray-700 mt-1">{{ $institute->state ?? 'N/A' }}, {{ $institute->pincode ?? 'N/A' }}</p>
                                    </div>
                                    <div>
                                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Registered At</p>
                                        <p class="text-sm text-gray-700 mt-1">{{ $institute->created_at->format('M d, Y') }}</p>
                                    </div>
                                    <div class="flex items-center space-x-3">
                                        <div class="w-12 h-12 rounded-lg bg-white border border-gray-200 flex items-center justify-center overflow-hidden">
                                            @if($institute->logo)
                                                <img src="{{ asset('storage/' . $institute->logo) }}" class="w-full h-full object-cover">
                                            @else
                                                <svg class="w-6 h-6 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                            @endif
                                        </div>
                                        <div>
                                            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Logo</p>
                                            <p class="text-xs text-gray-500 mt-0.5">Institute Asset</p>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-gray-500 italic">
                                No institutes found matching your criteria.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($institutes->hasPages())
        <div class="px-6 py-4 border-t border-gray-100">
            {{ $institutes->appends(request()->query())->links() }}
        </div>
        @endif
    </div>
</x-admin-layout>
