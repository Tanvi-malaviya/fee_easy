<x-admin-layout title="Subscription Management">

    <div class="">
        <div class="max-w-7xl mx-auto">
        

            <!-- Filters & Search -->
            <div class="bg-white p-4 rounded-2xl border border-gray-100 shadow-sm mb-6">
                <form id="search-form" action="{{ route('subscriptions.index') }}" method="GET" class="flex flex-col md:flex-row gap-4">
                    <div class="flex-1 relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </div>
                        <input type="text" id="search-input" name="search" value="{{ request('search') }}" autocomplete="off"
                            placeholder="Search by institute or owner name..." 
                            class="block w-full pl-10 pr-3 py-2.5 border border-gray-200 rounded-xl leading-5 bg-gray-50 placeholder-gray-500 focus:outline-none focus:bg-white focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm transition">
                    </div>
                    
                    <div class="w-full md:w-48">
                        <select name="status" onchange="this.form.submit()" 
                            class="block w-full pl-3 pr-10 py-2.5 text-sm border-gray-200 focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 rounded-xl bg-gray-50 transition font-medium text-gray-700 cursor-pointer">
                            <option value="all">All Status</option>
                            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="trial" {{ request('status') == 'trial' ? 'selected' : '' }}>Trial</option>
                            <option value="expired" {{ request('status') == 'expired' ? 'selected' : '' }}>Expired</option>
                            <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                        </select>
                    </div>

                    <div class="flex items-center gap-2">
                        <button type="button" @click="$dispatch('open-modal', 'assign-plan')"
                            class="inline-flex items-center px-6 py-2.5 border border-transparent rounded-xl shadow-lg shadow-indigo-600/20 text-sm font-bold text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none transition transform active:scale-95 whitespace-nowrap">
                            <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                            Assign New Plan
                        </button>
                        @if(request()->has('search') || request()->has('status'))
                            <a href="{{ route('subscriptions.index') }}" class="inline-flex items-center px-4 py-2 text-sm font-medium text-red-600 hover:text-red-800 transition">
                                Clear
                            </a>
                        @endif
                    </div>
                </form>
            </div>

            <!-- Management Table -->
            <div class="bg-white border border-gray-100 rounded-2xl shadow-sm overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-100">
                        <thead class="bg-gray-50/50">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Institute</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Plan</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Validity</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-4 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-100">
                            @forelse($subscriptions as $subscription)
                                <tr class="hover:bg-gray-50/50 transition-colors">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-bold text-gray-900 leading-tight">{{ $subscription->institute->institute_name }}</div>
                                        <div class="text-xs text-gray-500 font-medium mt-0.5">{{ $subscription->institute->name }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-bold text-gray-900">{{ $subscription->plan_name }}</div>
                                        <div class="text-[10px] text-indigo-600 font-bold uppercase tracking-wider mt-1">{{ $currency }}{{ number_format($subscription->amount, 0) }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex flex-col">
                                            <span class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Expires On</span>
                                            <span class="text-sm font-bold text-gray-900 mt-0.5">{{ \Carbon\Carbon::parse($subscription->end_date)->format('d M, Y') }}</span>
                                            
                                            @php $daysLeft = \Carbon\Carbon::parse($subscription->end_date)->diffInDays(now(), false); @endphp
                                            @if($daysLeft < 0)
                                                <div class="inline-flex items-center mt-1.5">
                                                    <span class="w-1.5 h-1.5 rounded-full {{ strtolower($subscription->status) == 'trial' ? 'bg-blue-500' : 'bg-emerald-500' }} mr-2"></span>
                                                    <span class="text-[10px] font-bold {{ strtolower($subscription->status) == 'trial' ? 'text-blue-600' : 'text-emerald-600' }} uppercase tracking-wider">
                                                        {{ abs($daysLeft) }} Days {{ strtolower($subscription->status) == 'trial' ? 'Trial left' : 'Remaining' }}
                                                    </span>
                                                </div>
                                            @else
                                                <div class="inline-flex items-center mt-1.5">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-red-500 mr-2"></span>
                                                    <span class="text-[10px] font-bold text-red-600 uppercase tracking-wider italic">Expired {{ $daysLeft }} days ago</span>
                                                </div>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                         @php $status = trim(strtolower($subscription->status)); @endphp
                                         <span class="px-2 py-0.5 inline-flex text-[10px] font-bold rounded uppercase tracking-wider
                                             @if($status == 'active') bg-emerald-50 text-emerald-700 border border-emerald-100 
                                             @elseif($status == 'trial') bg-blue-50 text-blue-700 border border-blue-100 animate-pulse
                                             @elseif($status == 'cancelled') bg-gray-50 text-gray-500 border border-gray-100
                                             @else bg-red-50 text-red-700 border border-red-100 @endif">
                                             {{ $status == 'trial' ? 'Trial' : $status }}
                                         </span>
                                     </td>
                                     <td class="px-6 py-4 whitespace-nowrap text-right">
                                         <div class="flex justify-end items-center gap-1">
                                             @if($status == 'trial')
                                                 <button @click="$dispatch('open-modal', 'convert-trial-{{ $subscription->id }}')"
                                                     class="bg-indigo-600 text-white px-4 py-1.5 rounded-xl font-bold text-[10px] uppercase tracking-widest shadow-lg shadow-indigo-600/20 hover:bg-indigo-700 transition transform active:scale-95">
                                                     Paid
                                                 </button>
                                             @endif

                                             @if($status == 'cancelled')
                                                 <form action="{{ route('subscriptions.activate', $subscription) }}" method="POST" class="inline">
                                                     @csrf @method('PATCH')
                                                     <button type="submit" class="bg-indigo-50 text-indigo-700 border border-indigo-100 px-4 py-1.5 rounded-xl font-bold text-[10px] uppercase tracking-widest hover:bg-indigo-100 transition transform active:scale-95">
                                                         Activate
                                                     </button>
                                                 </form>
                                             @else
                                                 <div class="flex gap-1">
                                                     <button @click="$dispatch('open-modal', 'extend-subscription-{{ $subscription->id }}')"
                                                         class="bg-emerald-50 text-emerald-700 border border-emerald-100 px-4 py-1.5 rounded-xl text-[10px] font-bold uppercase tracking-widest hover:bg-emerald-100 transition transform active:scale-95">Extend</button>
                                                     
                                                     <form action="{{ route('subscriptions.cancel', $subscription) }}" method="POST" class="inline" onsubmit="return confirm('Cancel this subscription?')">
                                                         @csrf @method('PATCH')
                                                         <button type="submit" class="bg-red-50 text-red-600 border border-red-100 px-4 py-1.5 rounded-xl text-[10px] font-bold uppercase tracking-widest hover:bg-red-100 transition transform active:scale-95">Cancel</button>
                                                     </form>
                                                 </div>
                                             @endif
                                         </div>

                                        <!-- Modals nested for context -->
                                        <x-modal name="convert-trial-{{ $subscription->id }}" :show="false" focusable>
                                            <form method="post" action="{{ route('subscriptions.convert', $subscription) }}" class="p-8 text-left">
                                                @csrf @method('PATCH')
                                                <h2 class="text-xl font-bold text-gray-900 leading-tight">Convert Trial to Paid</h2>
                                                <p class="mt-2 text-xs text-gray-500 font-medium border-b border-gray-50 pb-4">Select the plan the institute has purchased and confirm activation.</p>

                                                <div class="mt-8">
                                                    <x-input-label for="plan_id" value="Select Purchased Plan" class="text-xs font-semibold uppercase tracking-wider text-gray-500 mb-2" />
                                                    <select name="plan_id" id="plan_id" class="mt-1 block w-full border-gray-100 bg-gray-50/50 focus:border-indigo-500 focus:ring-indigo-500 rounded-xl shadow-sm text-sm font-bold py-3 px-4 transition appearance-none bg-[url('data:image/svg+xml;charset=utf-8,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20fill%3D%22none%22%20viewBox%3D%220%200%2024%2024%22%20stroke%3D%22currentColor%22%3E%3Cpath%20stroke-linecap%3D%22round%22%20stroke-linejoin%3D%22round%22%20stroke-width%3D%222%22%20d%3D%22M19%209l-7%207-7-7%22%2F%3E%3C%2Fsvg%3E')] bg-[size:1.25em_1.25em] bg-[position:right_1rem_center] bg-no-repeat pr-10" required>
                                                        @foreach($plans as $plan)
                                                            <option value="{{ $plan->id }}">{{ $plan->name }} ({{ $currency }}{{ number_format($plan->price, 0) }})</option>
                                                        @endforeach
                                                    </select>
                                                </div>

                                                <div class="mt-8 flex justify-end gap-3">
                                                    <button type="button" x-on:click="$dispatch('close')" class="text-xs font-bold text-gray-400 uppercase tracking-widest px-4">Cancel</button>
                                                    <button type="submit" class="bg-indigo-600 text-white px-6 py-2.5 rounded-xl text-xs font-bold uppercase tracking-widest shadow-lg shadow-indigo-600/20 hover:bg-indigo-700 transition">Confirm Payment</button>
                                                </div>
                                            </form>
                                        </x-modal>

                                        <x-modal name="extend-subscription-{{ $subscription->id }}" :show="false" focusable>
                                            <form method="post" action="{{ route('subscriptions.extend', $subscription) }}" class="p-8 text-left">
                                                @csrf @method('PATCH')
                                                <h2 class="text-xl font-bold text-gray-900 leading-tight">Extend Subscription</h2>
                                                <p class="mt-2 text-xs text-gray-500 font-medium border-b border-gray-50 pb-4">Enter extension details and record the payment amount.</p>
                                                
                                                <div class="mt-8 space-y-6">
                                                    <div>
                                                        <x-input-label for="days" value="Extension Duration (Days)" class="text-xs font-semibold uppercase tracking-wider text-gray-500 mb-2" />
                                                        <x-text-input id="days" name="days" type="number" class="mt-1 block w-full py-3 px-4 text-sm font-bold bg-gray-50/50 border-gray-100 rounded-xl" placeholder="e.g. 30" required />
                                                    </div>
                                                    <div>
                                                        <x-input-label for="amount" value="Amount Received ({{ $currency }})" class="text-xs font-semibold uppercase tracking-wider text-gray-500 mb-2" />
                                                        <x-text-input id="amount" name="amount" type="number" step="0.01" class="mt-1 block w-full py-3 px-4 text-sm font-bold bg-gray-50/50 border-gray-100 rounded-xl" placeholder="0.00" required />
                                                    </div>
                                                </div>
                                                
                                                <div class="mt-8 flex justify-end gap-3">
                                                    <button type="button" x-on:click="$dispatch('close')" class="text-xs font-bold text-gray-400 uppercase tracking-widest px-4">Cancel</button>
                                                    <button type="submit" class="bg-emerald-600 text-white px-6 py-2.5 rounded-xl text-xs font-bold uppercase tracking-widest shadow-lg shadow-emerald-600/20 hover:bg-emerald-700 transition">Extend Validity</button>
                                                </div>
                                            </form>
                                        </x-modal>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-12 text-center text-gray-400 font-bold uppercase tracking-widest opacity-50">No subscriptions found</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                @if($subscriptions->hasPages())
                    <div class="px-6 py-4 bg-gray-50/50 border-t border-gray-100">
                        {{ $subscriptions->appends(request()->query())->links() }}
                    </div>
                @endif
            </div>

            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    const searchInput = document.getElementById('search-input');
                    const searchForm = document.getElementById('search-form');
                    let timeout = null;

                    if (searchInput) {
                        // To maintain focus and put cursor at the end
                        if (searchInput.value !== "") {
                            searchInput.focus();
                            const val = searchInput.value;
                            searchInput.value = '';
                            searchInput.value = val;
                        }

                        searchInput.addEventListener('input', function() {
                            clearTimeout(timeout);
                            timeout = setTimeout(() => {
                                searchForm.submit();
                            }, 500); // 500ms debounce
                        });
                    }
                });
            </script>
        </div>
    </div>

    <!-- Global Assign Plan Modal -->
    <x-modal name="assign-plan" :show="false" focusable>
        <form method="post" action="{{ route('subscriptions.store') }}" class="p-8">
            @csrf
            <h2 class="text-xl font-bold text-gray-900 leading-tight">Assign New Subscription</h2>
            <p class="mt-2 text-xs text-gray-500 font-medium border-b border-gray-50 pb-4">Onboard an institute to a new billing cycle.</p>

            <div class="mt-8 space-y-6">
                <div>
                    <x-input-label for="institute_id" value="Select Institute" class="text-xs font-semibold uppercase tracking-wider text-gray-500 mb-2" />
                    <select name="institute_id" id="institute_id" class="mt-1 block w-full border-gray-100 bg-gray-50/50 focus:border-indigo-500 focus:ring-indigo-500 rounded-xl shadow-sm text-sm font-bold py-3 px-4 transition appearance-none bg-[url('data:image/svg+xml;charset=utf-8,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20fill%3D%22none%22%20viewBox%3D%220%200%2024%2024%22%20stroke%3D%22currentColor%22%3E%3Cpath%20stroke-linecap%3D%22round%22%20stroke-linejoin%3D%22round%22%20stroke-width%3D%222%22%20d%3D%22M19%209l-7%207-7-7%22%2F%3E%3C%2Fsvg%3E')] bg-[size:1.25em_1.25em] bg-[position:right_1rem_center] bg-no-repeat pr-10">
                        @foreach($institutes as $institute)
                            <option value="{{ $institute->id }}">{{ $institute->institute_name }} ({{ $institute->name }})</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <x-input-label for="plan_id" value="Select Billing Plan" class="text-xs font-semibold uppercase tracking-wider text-gray-500 mb-2" />
                    <select name="plan_id" id="plan_id" class="mt-1 block w-full border-gray-100 bg-gray-50/50 focus:border-indigo-500 focus:ring-indigo-500 rounded-xl shadow-sm text-sm font-bold py-3 px-4 transition appearance-none bg-[url('data:image/svg+xml;charset=utf-8,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20fill%3D%22none%22%20viewBox%3D%220%200%2024%2024%22%20stroke%3D%22currentColor%22%3E%3Cpath%20stroke-linecap%3D%22round%22%20stroke-linejoin%3D%22round%22%20stroke-width%3D%222%22%20d%3D%22M19%209l-7%207-7-7%22%2F%3E%3C%2Fsvg%3E')] bg-[size:1.25em_1.25em] bg-[position:right_1rem_center] bg-no-repeat pr-10">
                        @foreach($plans as $plan)
                            <option value="{{ $plan->id }}">{{ $plan->name }} - {{ $currency }}{{ number_format($plan->price, 0) }} ({{ $plan->duration_days }} days)</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <x-input-label for="start_date" value="Commencement Date" class="text-xs font-semibold uppercase tracking-wider text-gray-500 mb-2" />
                    <x-text-input id="start_date" name="start_date" type="date" class="mt-1 block w-full py-3 px-4 text-sm font-bold bg-gray-50/50 border-gray-100 rounded-xl" value="{{ date('Y-m-d') }}" required />
                </div>
            </div>

            <div class="mt-8 flex justify-end gap-3">
                <button type="button" x-on:click="$dispatch('close')" class="text-xs font-bold text-gray-400 uppercase tracking-widest px-4">Cancel</button>
                <button type="submit" class="bg-indigo-600 text-white px-6 py-2.5 rounded-xl text-xs font-bold uppercase tracking-widest shadow-lg shadow-indigo-600/20 hover:bg-indigo-700 transition">Activate Plan</button>
            </div>
        </form>
    </x-modal>
</x-admin-layout>