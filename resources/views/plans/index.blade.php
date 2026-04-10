<x-admin-layout title="Subscription Plans">

    <div class="">
        <div class="max-w-7xl mx-auto ">
            
            <!-- Standalone header removed for cleaner layout -->

          

            <!-- Management Card -->
            <div class="bg-white border border-gray-100 rounded-2xl shadow-sm overflow-hidden">
                <div class="px-6 py-5 border-b border-gray-50 flex flex-col sm:flex-row justify-between items-center bg-gray-50/75 gap-4">
                    <div>
                        <h2 class="text-lg font-medium text-gray-800 leading-none">Subscription Plans</h2>
                     
                    </div>
                    <a href="{{ route('plans.create') }}" class="inline-flex items-center px-8 py-3 bg-indigo-600 border border-transparent rounded-xl shadow-lg text-xs font-bold text-white uppercase tracking-widest hover:bg-indigo-700 transition transform active:scale-95 shadow-indigo-600/20 whitespace-nowrap">
                        <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                        Create New Plan
                    </a>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left divide-y divide-gray-100">
                        <thead>
                            <tr class="bg-gray-50/50">
                                <th class="px-2 py-2 text-xs font-semibold text-gray-500 uppercase tracking-wider">Plan Name</th>
                                <th class="px-2 py-2 text-xs font-semibold text-gray-500 uppercase tracking-wider text-center">Price / Duration</th>
                                <th class="px-2 py-2 text-xs font-semibold text-gray-500 uppercase tracking-wider text-center">Trial Period</th>
                                <th class="px-2 py-2 text-xs font-semibold text-gray-500 uppercase tracking-wider text-center">Status</th>
                                <th class="px-2 py-2 text-xs font-semibold text-gray-500 uppercase tracking-wider text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 bg-white">
                            @forelse($plans as $plan)
                                <tr class="hover:bg-gray-50/50 transition duration-150">
                                    <td class="px-2 py-2 whitespace-nowrap">
                                         <div class="text-sm font-bold text-gray-900 leading-tight">{{ $plan->name }}</div>
                                         <div class="text-[10px] text-gray-400 font-semibold uppercase tracking-widest mt-0.5">ID: #PLN-{{ $plan->id }}</div>
                                     </td>
                                     <td class="px-2 py-2 whitespace-nowrap text-center">
                                         <div class="text-sm font-bold text-emerald-600">₹{{ number_format($plan->price, 0) }}</div>
                                         <div class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mt-0.5">{{ $plan->duration_days }} Days</div>
                                     </td>
                                     <td class="px-2 py-2 whitespace-nowrap text-center">
                                         <div class="text-sm font-bold text-gray-900">{{ $plan->trial_days }} Days</div>
                                         @if($plan->trial_days > 0)
                                             <div class="text-[10px] text-indigo-500 font-bold uppercase tracking-wider mt-0.5">Free Access</div>
                                         @endif
                                     </td>
                                     <td class="px-2 py-2 whitespace-nowrap text-center">
                                         @if($plan->status)
                                             <span class="px-2.5 py-1 inline-flex text-[10px] font-bold rounded uppercase tracking-wider bg-emerald-50 text-emerald-700 border border-emerald-100">Active</span>
                                         @else
                                             <span class="px-2.5 py-1 inline-flex text-[10px] font-bold rounded uppercase tracking-wider bg-red-50 text-red-700 border border-red-100">Inactive</span>
                                         @endif
                                     </td>
                                     <td class="px-2 py-2 whitespace-nowrap text-right text-xs">
                                         <div class="flex justify-end gap-2">
                                             <a href="{{ route('plans.edit', $plan) }}" 
                                                 class="p-2 bg-indigo-50 text-indigo-600 rounded-lg hover:bg-indigo-100 transition shadow-sm" 
                                                 title="Edit Plan">
                                                 <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                     <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                 </svg>
                                             </a>
                                             <form action="{{ route('plans.destroy', $plan) }}" method="POST" class="inline" onsubmit="return confirm('Archive this plan permanently?')">
                                                 @csrf @method('DELETE')
                                                 <button type="submit" 
                                                     class="p-2 bg-red-50 text-red-600 rounded-lg hover:bg-red-100 transition shadow-sm" 
                                                     title="Delete Plan">
                                                     <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                         <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                     </svg>
                                                 </button>
                                             </form>
                                         </div>
                                     </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-12 text-center text-gray-500 italic">No subscription plans found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if($plans->hasPages())
                    <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/50">
                        {{ $plans->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-admin-layout>
