<x-admin-layout title="{{ $institute->institute_name }} - Details">
<div class="max-w-7xl mx-auto" x-data="{ activeTab: 'subscriptions', deleteModal: false, deleteUrl: '', deleteName: '', deleteType: 'Student', deleteElement: null, deleteScopeEl: null }">

    {{-- Header --}}
    <div class="flex items-center justify-between mb-2 px-2">
        <a href="{{ route('institutes.index') }}" class="inline-flex items-center text-gray-400 hover:text-primary transition-colors group">
            <div class="p-2 bg-white border border-gray-100 rounded-xl shadow-sm group-hover:border-primary/20 transition-all active:scale-90">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            </div>
            <span class="ml-3 text-[10px] font-bold uppercase tracking-widest">Back to Institutes</span>
        </a>
        <a href="{{ route('institutes.edit', [$institute, 'from' => 'show']) }}" class="inline-flex items-center px-5 py-2 bg-primary text-white rounded-xl text-[10px] font-bold uppercase tracking-widest shadow-lg shadow-primary/20 hover:opacity-90 transition active:scale-95">
            <svg class="w-3.5 h-3.5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
            Edit Details
        </a>
    </div>

    {{-- Profile Card --}}
    <div class="bg-white border border-gray-100 rounded-xl shadow-sm p-4 mb-1.5">
        <div class="flex flex-col md:flex-row gap-5 items-start">
            <div class="w-20 h-20 rounded-2xl bg-gray-50 border border-gray-100 flex items-center justify-center overflow-hidden shadow-sm flex-shrink-0">
                @if($institute->logo)
                    <img src="{{ asset('storage/' . $institute->logo) }}" class="w-full h-full object-cover">
                @else
                    <div class="text-2xl font-bold text-gray-200">🏢</div>
                @endif
            </div>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-y-3.5 gap-x-6 flex-grow">
                <div><span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Institute</span><div class="flex items-center gap-2 mt-1"><span class="text-sm font-bold text-gray-900">{{ $institute->institute_name }}</span><span class="px-1.5 py-0.5 rounded-full text-[8px] font-black uppercase tracking-wider @if($institute->status=='active') bg-emerald-50 text-emerald-600 border border-emerald-100 @elseif($institute->status=='suspended') bg-amber-50 text-amber-600 border border-amber-100 @else bg-red-50 text-red-600 border border-red-100 @endif">● {{ $institute->status }}</span></div></div>
                <div><span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Owner</span><p class="text-sm font-bold text-gray-900 mt-1">{{ $institute->name }}</p></div>
                <div><span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Email</span><p class="text-sm font-bold text-gray-700 mt-1 break-all">{{ $institute->email }}</p></div>
                <div><span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Phone</span><p class="text-sm font-bold text-gray-700 mt-1">{{ $institute->phone }}</p></div>
                <div><span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Address</span><p class="text-sm font-bold text-gray-700 mt-1">{{ $institute->address ?? 'N/A' }}</p></div>
                <div><span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">City</span><p class="text-sm font-bold text-gray-700 mt-1">{{ $institute->city ?? 'N/A' }}</p></div>
                <div><span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">State</span><p class="text-sm font-bold text-gray-700 mt-1">{{ $institute->state ?? 'N/A' }}</p></div>
                <div><span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Pincode</span><p class="text-sm font-bold text-gray-700 mt-1">{{ $institute->pincode ?? 'N/A' }}</p></div>
            </div>
        </div>
    </div>

    {{-- Tab Nav --}}
    <div class="bg-white border border-gray-100 rounded-xl shadow-sm mb-1.5 p-1">
        <div class="flex overflow-x-auto no-scrollbar gap-1 px-1">
            @foreach(['subscriptions'=>'Subscriptions','students'=>'Students','staff'=>'Staff','batches'=>'Batches','financials'=>'Financials','leads'=>'Leads','updates'=>'Updates','notes'=>'Notes','chats'=>'Chats'] as $tab => $label)
            <button @click="activeTab = '{{ $tab }}'" :class="activeTab === '{{ $tab }}' ? 'bg-white text-primary border-primary shadow-sm' : 'text-gray-400 border-transparent hover:text-gray-600 hover:bg-gray-50/50'" class="px-5 py-2 border-2 rounded-2xl font-black text-[9px] uppercase tracking-widest transition-all whitespace-nowrap">{{ $label }}</button>
            @endforeach
        </div>
    </div>

    {{-- Tab Content --}}
    <div class="space-y-1 pb-12">

        {{-- Subscriptions --}}
        <div x-show="activeTab === 'subscriptions'" style="display:none">
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
                <div class="p-4 border-b border-gray-50 flex items-center justify-between">
                    <h4 class="text-sm font-black text-gray-900 uppercase tracking-widest">Subscription History</h4>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead class="bg-gray-50/50"><tr>
                            <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">Plan</th>
                            <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">Amount</th>
                            <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">Period</th>
                            <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">Status</th>
                        </tr></thead>
                        <tbody class="divide-y divide-gray-50">
                            @forelse($institute->subscriptions as $sub)
                            <tr class="hover:bg-gray-50/50 transition">
                                <td class="px-6 py-4"><span class="text-sm font-black text-gray-900">{{ $sub->plan_name }}</span></td>
                                <td class="px-6 py-4"><span class="text-sm font-bold text-gray-700">₹{{ number_format($sub->amount) }}</span></td>
                                <td class="px-6 py-4"><div class="text-[11px] font-medium text-gray-500">{{ \Carbon\Carbon::parse($sub->start_date)->format('M d, Y') }} – {{ \Carbon\Carbon::parse($sub->end_date)->format('M d, Y') }}</div></td>
                                <td class="px-6 py-4"><span class="px-2 py-0.5 rounded-full text-[9px] font-black uppercase tracking-wider @if($sub->status=='active') bg-emerald-50 text-emerald-600 border border-emerald-100 @elseif($sub->status=='trial') bg-blue-50 text-blue-600 border border-blue-100 @elseif($sub->status=='expired') bg-red-50 text-red-600 border border-red-100 @else bg-gray-50 text-gray-600 border border-gray-100 @endif">{{ $sub->status }}</span></td>
                            </tr>
                            @empty
                            <tr><td colspan="4" class="px-6 py-12 text-center text-gray-400 font-bold text-sm">No subscription records found.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- Students --}}
        <div x-show="activeTab === 'students'" style="display:none">
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden"
                 x-data="{ page:1, perPage:10, total:{{ $stats['students_count'] }}, get totalPages(){return Math.ceil(this.total/this.perPage);}, get from(){return (this.page-1)*this.perPage+1;}, get to(){return Math.min(this.page*this.perPage,this.total);}, show(i){return i>=(this.page-1)*this.perPage&&i<this.page*this.perPage;} }">
                <div class="p-4 border-b border-gray-50 flex items-center justify-between">
                    <h4 class="text-sm font-black text-gray-900 uppercase tracking-widest">Student Admissions</h4>
                    <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Total: {{ $stats['students_count'] }} Students</span>
                </div>
                @if($institute->students->isEmpty())
                    <div class="flex flex-col items-center justify-center py-16 text-gray-300">
                        <svg class="w-16 h-16 mb-3 opacity-40" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                        <p class="text-sm font-bold uppercase tracking-widest text-gray-300">No student records found</p>
                    </div>
                @else
                    <div class="p-4 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-5 gap-3">
                        @foreach($institute->students as $idx => $student)
                            @php $isActive = $student->status=='active'||$student->status==1||$student->status===true; @endphp
                            <div class="group bg-white rounded-xl border border-gray-100 hover:shadow-lg hover:shadow-primary/5 transition-all duration-300 flex flex-col overflow-hidden delete-item" x-show="show({{ $idx }})">
                                <div class="flex items-center justify-between px-3 pt-3 pb-1">
                                    <span class="px-2 py-0.5 bg-gray-50 text-gray-400 text-[9px] font-black rounded-md uppercase tracking-tight">{{ $student->batch?->name ?? 'Unassigned' }}</span>
                                    <span class="text-[9px] font-bold text-gray-300">{{ $student->created_at->format('M d') }}</span>
                                </div>
                                <div class="flex flex-col items-start px-3 pb-3 pt-2 flex-1">
                                    <div class="h-14 w-14 rounded-full border-2 border-gray-100 overflow-hidden mb-2 shadow-sm">
                                        @if($student->profile_image)
                                            <img src="{{ asset('storage/'.$student->profile_image) }}" class="w-full h-full object-cover">
                                        @else
                                            <img src="https://ui-avatars.com/api/?name={{ urlencode($student->name) }}&color=7F9CF5&background=EBF4FF" class="w-full h-full object-cover">
                                        @endif
                                    </div>
                                    <h4 class="text-sm font-black text-gray-800 tracking-tight leading-tight">{{ $student->name }}</h4>
                                    <p class="text-[10px] font-medium text-gray-400 truncate w-full mt-0.5">{{ $student->email ?: 'No email' }}</p>
                                </div>
                                <div class="px-3 pb-3 space-y-1.5">
                                    <div class="flex items-center justify-between"><span class="text-[9px] font-black text-gray-400 uppercase tracking-wider">Phone</span><span class="text-[10px] font-bold text-gray-600">{{ $student->phone ?: '—' }}</span></div>
                                    <div class="flex items-center justify-between"><span class="text-[9px] font-black text-gray-400 uppercase tracking-wider">Monthly Fee</span><span class="text-[10px] font-black text-primary">₹{{ number_format($student->monthly_fee??0) }}</span></div>
                                    <div class="flex items-center justify-between"><span class="text-[9px] font-black text-gray-400 uppercase tracking-wider">Standard</span><span class="text-[10px] font-bold text-gray-600">{{ $student->standard ?: '—' }}</span></div>
                                </div>
                                <div class="flex items-center justify-between px-3 py-2 bg-gray-50/80 border-t border-gray-100">
                                    <span class="text-[9px] text-gray-400 font-medium">Joined {{ $student->created_at->format('M d, Y') }}</span>
                                    <button type="button"
                                            @click="deleteUrl='{{ route('institutes.students.destroy', [$institute, $student]) }}'; deleteName='{{ addslashes($student->name) }}'; deleteType='Student'; deleteElement=$el.closest('.delete-item'); deleteScopeEl=$el.closest('[x-data]'); deleteModal=true"
                                            class="text-red-500 hover:text-red-700 transition-colors duration-200 p-1.5 rounded-lg hover:bg-red-100">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <x-admin.tab-pagination :total="$stats['students_count']" />
                @endif
            </div>
        </div>

        {{-- Staff --}}
        <div x-show="activeTab === 'staff'" style="display:none">
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden"
                 x-data="{ page:1, perPage:10, total:{{ $stats['staff_count'] }}, get totalPages(){return Math.ceil(this.total/this.perPage);}, get from(){return (this.page-1)*this.perPage+1;}, get to(){return Math.min(this.page*this.perPage,this.total);}, show(i){return i>=(this.page-1)*this.perPage&&i<this.page*this.perPage;} }">
                <div class="p-4 border-b border-gray-50 flex items-center justify-between">
                    <h4 class="text-sm font-black text-gray-900 uppercase tracking-widest">Staff Management</h4>
                    <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Total: {{ $stats['staff_count'] }} Staff</span>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead class="bg-gray-50/50"><tr>
                            <th class="px-4 py-3 text-[10px] font-black text-gray-400 uppercase tracking-widest">Name</th>
                            <th class="px-4 py-3 text-[10px] font-black text-gray-400 uppercase tracking-widest">Role/Dept</th>
                            <th class="px-4 py-3 text-[10px] font-black text-gray-400 uppercase tracking-widest">Email</th>
                            <th class="px-4 py-3 text-[10px] font-black text-gray-400 uppercase tracking-widest">Contact</th>
                            <th class="px-4 py-3 text-[10px] font-black text-gray-400 uppercase tracking-widest">Salary</th>
                            <th class="px-4 py-3 text-[10px] font-black text-gray-400 uppercase tracking-widest"></th>
                        </tr></thead>
                        <tbody class="divide-y divide-gray-50">
                            @forelse($institute->staff as $i => $member)
                            <tr class="hover:bg-gray-50/50 transition delete-item" x-show="show({{ $i }})">
                                <td class="px-4 py-2.5"><span class="text-sm font-bold text-gray-900">{{ $member->full_name }}</span></td>
                                <td class="px-4 py-2.5"><div class="text-[11px] font-black text-primary uppercase tracking-wider">{{ $member->role->name ?? 'Staff' }}</div><div class="text-[10px] font-bold text-gray-400 uppercase">{{ $member->department->name ?? 'General' }}</div></td>
                                <td class="px-4 py-2.5"><span class="text-[11px] text-gray-500 font-medium">{{ $member->email ?: '—' }}</span></td>
                                <td class="px-4 py-2.5 text-xs text-gray-700 font-bold">{{ $member->phone }}</td>
                                <td class="px-4 py-2.5"><span class="text-sm font-black text-gray-900">₹{{ number_format($member->base_salary??0) }}</span></td>
                                <td class="px-4 py-2.5 text-right">
                                    <button type="button"
                                            @click="deleteUrl='{{ route('institutes.staff.destroy', [$institute, $member]) }}'; deleteName='{{ addslashes($member->full_name) }}'; deleteType='Staff'; deleteElement=$el.closest('.delete-item'); deleteScopeEl=$el.closest('[x-data]'); deleteModal=true"
                                            class="text-red-500 hover:text-red-700 transition-colors duration-200 p-1.5 rounded-lg hover:bg-red-100">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="6" class="px-4 py-12 text-center text-gray-400 font-bold text-sm">No staff records found.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <x-admin.tab-pagination :total="$stats['staff_count']" />
            </div>
        </div>

        {{-- Batches --}}
        <div x-show="activeTab === 'batches'" style="display:none">
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden"
                 x-data="{ page:1, perPage:10, total:{{ $stats['batches_count'] }}, get totalPages(){return Math.ceil(this.total/this.perPage);}, get from(){return (this.page-1)*this.perPage+1;}, get to(){return Math.min(this.page*this.perPage,this.total);}, show(i){return i>=(this.page-1)*this.perPage&&i<this.page*this.perPage;} }">
                <div class="p-4 border-b border-gray-50 flex items-center justify-between">
                    <h4 class="text-sm font-black text-gray-900 uppercase tracking-widest">Batch Management</h4>
                    <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Total: {{ $stats['batches_count'] }} Batches</span>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead class="bg-gray-50/50"><tr>
                            <th class="px-4 py-3 text-[10px] font-black text-gray-400 uppercase tracking-widest">Batch Name</th>
                            <th class="px-4 py-3 text-[10px] font-black text-gray-400 uppercase tracking-widest">Subject</th>
                            <th class="px-4 py-3 text-[10px] font-black text-gray-400 uppercase tracking-widest">Timing</th>
                            <th class="px-4 py-3 text-[10px] font-black text-gray-400 uppercase tracking-widest">Fees</th>
                            <th class="px-4 py-3 text-[10px] font-black text-gray-400 uppercase tracking-widest">Students</th>
                            <th class="px-4 py-3 text-[10px] font-black text-gray-400 uppercase tracking-widest"></th>
                        </tr></thead>
                        <tbody class="divide-y divide-gray-50">
                            @forelse($institute->batches as $i => $batch)
                            <tr class="hover:bg-gray-50/50 transition delete-item" x-show="show({{ $i }})">
                                <td class="px-4 py-2.5">
                                    <a href="{{ route('institutes.batches.show', [$institute, $batch]) }}"
                                       class="text-sm font-black text-gray-900 hover:text-primary transition-colors">
                                        {{ $batch->name }}
                                    </a>
                                </td>
                                <td class="px-4 py-2.5"><span class="text-[11px] font-bold text-gray-500">{{ $batch->subject ?? '—' }}</span></td>
                                <td class="px-4 py-2.5">
                                    @if($batch->start_time || $batch->end_time)
                                        <span class="text-[11px] font-bold text-primary bg-orange-50 px-2 py-1 rounded-lg border border-orange-100">{{ $batch->start_time ?? '?' }} – {{ $batch->end_time ?? '?' }}</span>
                                    @else
                                        <span class="text-[11px] text-gray-300 font-bold">—</span>
                                    @endif
                                </td>
                                <td class="px-4 py-2.5"><span class="text-[11px] font-black text-gray-700">₹{{ number_format($batch->fees ?? 0) }}</span></td>
                                <td class="px-4 py-2.5"><span class="text-sm font-black text-gray-900">{{ $batch->students()->count() }}</span></td>
                                <td class="px-4 py-2.5 text-right">
                                    <button type="button"
                                            @click="deleteUrl='{{ route('institutes.batches.destroy', [$institute, $batch]) }}'; deleteName='{{ addslashes($batch->name) }}'; deleteType='Batch'; deleteElement=$el.closest('.delete-item'); deleteScopeEl=$el.closest('[x-data]'); deleteModal=true"
                                            class="text-red-500 hover:text-red-700 transition-colors duration-200 p-1.5 rounded-lg hover:bg-red-100">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="6" class="px-4 py-12 text-center text-gray-400 font-bold text-sm">No batch records found.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <x-admin.tab-pagination :total="$stats['batches_count']" />
            </div>
        </div>

        {{-- Financials --}}
        <div x-show="activeTab === 'financials'" style="display:none">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
                    <div class="p-4 border-b border-gray-50 bg-emerald-50/30"><h4 class="text-sm font-black text-emerald-600 uppercase tracking-widest">Recent Fee Collections</h4></div>
                    <div class="overflow-x-auto"><table class="w-full text-left"><tbody class="divide-y divide-gray-50">
                        @forelse($institute->fees as $fee)
                        <tr class="hover:bg-gray-50/50 transition">
                            <td class="px-6 py-4"><div class="text-sm font-bold text-gray-900">{{ $fee->student->name ?? 'Student' }}</div><div class="text-[10px] text-gray-400">{{ $fee->created_at->format('M d, Y') }}</div></td>
                            <td class="px-6 py-4 text-right"><div class="text-sm font-black text-emerald-600">+₹{{ number_format($fee->paid_amount) }}</div><div class="text-[10px] font-bold text-gray-400 uppercase">{{ $fee->payment_method ?? 'Offline' }}</div></td>
                        </tr>
                        @empty
                        <tr><td colspan="2" class="px-6 py-8 text-center text-gray-400 font-bold text-xs uppercase">No fees collected yet</td></tr>
                        @endforelse
                    </tbody></table></div>
                </div>
                <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
                    <div class="p-4 border-b border-gray-50 bg-rose-50/30"><h4 class="text-sm font-black text-rose-600 uppercase tracking-widest">Recent Expenses</h4></div>
                    <div class="overflow-x-auto"><table class="w-full text-left"><tbody class="divide-y divide-gray-50">
                        @forelse($institute->expenses as $expense)
                        <tr class="hover:bg-gray-50/50 transition">
                            <td class="px-6 py-4"><div class="text-sm font-bold text-gray-900">{{ $expense->title }}</div><div class="text-[10px] text-gray-400">{{ $expense->created_at->format('M d, Y') }}</div></td>
                            <td class="px-6 py-4 text-right"><div class="text-sm font-black text-rose-600">-₹{{ number_format($expense->amount) }}</div><div class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">{{ $expense->category->name ?? 'General' }}</div></td>
                        </tr>
                        @empty
                        <tr><td colspan="2" class="px-6 py-8 text-center text-gray-400 font-bold text-xs uppercase tracking-widest">No expenses recorded</td></tr>
                        @endforelse
                    </tbody></table></div>
                </div>
            </div>
        </div>

        {{-- Leads --}}
        <div x-show="activeTab === 'leads'" style="display:none">
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden"
                 x-data="{ page:1, perPage:10, total:{{ $stats['leads_count'] }}, get totalPages(){return Math.ceil(this.total/this.perPage);}, get from(){return (this.page-1)*this.perPage+1;}, get to(){return Math.min(this.page*this.perPage,this.total);}, show(i){return i>=(this.page-1)*this.perPage&&i<this.page*this.perPage;} }">
                <div class="p-4 border-b border-gray-50 flex items-center justify-between">
                    <h4 class="text-sm font-black text-gray-900 uppercase tracking-widest">Lead Management</h4>
                    <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Total: {{ $stats['leads_count'] }} Leads</span>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead class="bg-gray-50/50"><tr>
                            <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">Lead Name</th>
                            <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">Interest</th>
                            <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">Status</th>
                        </tr></thead>
                        <tbody class="divide-y divide-gray-50">
                            @forelse($institute->leads as $i => $lead)
                            <tr class="hover:bg-gray-50/50 transition" x-show="show({{ $i }})">
                                <td class="px-6 py-4"><div class="text-sm font-bold text-gray-900">{{ $lead->name }}</div><div class="text-[10px] text-gray-500 font-bold">{{ $lead->phone }}</div></td>
                                <td class="px-6 py-4 text-xs font-bold text-gray-700">{{ $lead->interest ?? 'N/A' }}</td>
                                <td class="px-6 py-4"><span class="px-2 py-0.5 rounded-full text-[9px] font-black uppercase tracking-wider @if($lead->status=='hot') bg-rose-50 text-rose-600 border border-rose-100 @elseif($lead->status=='warm') bg-amber-50 text-amber-600 border border-amber-100 @else bg-blue-50 text-blue-600 border border-blue-100 @endif">{{ $lead->status }}</span></td>
                            </tr>
                            @empty
                            <tr><td colspan="3" class="px-6 py-12 text-center text-gray-400 font-bold text-sm">No leads found.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <x-admin.tab-pagination :total="$stats['leads_count']" />
            </div>
        </div>

        {{-- Updates --}}
        <div x-show="activeTab === 'updates'" style="display:none">
            @php $updatesCount = $institute->dailyUpdates->count(); @endphp
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden"
                 x-data="{ page:1, perPage:10, total:{{ $updatesCount }}, get totalPages(){return Math.ceil(this.total/this.perPage);}, get from(){return (this.page-1)*this.perPage+1;}, get to(){return Math.min(this.page*this.perPage,this.total);}, show(i){return i>=(this.page-1)*this.perPage&&i<this.page*this.perPage;} }">
                <div class="p-4 border-b border-gray-50 flex items-center justify-between">
                    <h4 class="text-sm font-black text-gray-900 uppercase tracking-widest">Daily News & Updates</h4>
                    <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Total: {{ $updatesCount }} Updates</span>
                </div>
                <div class="p-4 space-y-3">
                    @forelse($institute->dailyUpdates as $i => $update)
                    <div class="p-4 rounded-xl bg-gray-50 border border-gray-100 hover:shadow-md transition" x-show="show({{ $i }})">
                        <div class="flex justify-between items-start">
                            <h5 class="text-sm font-black text-gray-900">{{ $update->title }}</h5>
                            <span class="text-[10px] font-bold text-gray-400 uppercase">{{ $update->created_at->format('M d, Y') }}</span>
                        </div>
                        <p class="text-xs text-gray-600 mt-2 line-clamp-2">{{ $update->description }}</p>
                    </div>
                    @empty
                    <div class="text-center py-12 text-gray-400 font-bold text-sm uppercase tracking-widest">No updates published yet</div>
                    @endforelse
                </div>
                <x-admin.tab-pagination :total="$updatesCount" />
            </div>
        </div>

        {{-- Notes --}}
        <div x-show="activeTab === 'notes'" style="display:none">
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden"
                 x-data="{ page:1, perPage:10, total:{{ $stats['notes_count'] }}, get totalPages(){return Math.ceil(this.total/this.perPage);}, get from(){return (this.page-1)*this.perPage+1;}, get to(){return Math.min(this.page*this.perPage,this.total);}, show(i){return i>=(this.page-1)*this.perPage&&i<this.page*this.perPage;} }">
                <div class="p-4 border-b border-gray-50 flex items-center justify-between">
                    <h4 class="text-sm font-black text-gray-900 uppercase tracking-widest">Study Materials & Notes</h4>
                    <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Total: {{ $stats['notes_count'] }} Notes</span>
                </div>
                <div class="p-4 grid grid-cols-1 md:grid-cols-2 gap-3">
                    @forelse($institute->notes as $i => $note)
                    <div class="p-4 rounded-xl bg-white border border-gray-100 shadow-sm hover:border-primary transition group" x-show="show({{ $i }})">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-orange-50 text-primary flex items-center justify-center text-lg">📄</div>
                            <div>
                                <h5 class="text-sm font-black text-gray-900 group-hover:text-primary transition">{{ $note->title }}</h5>
                                <div class="text-[10px] font-bold text-gray-400 uppercase mt-0.5">{{ $note->category->name ?? 'General' }} • {{ $note->created_at->diffForHumans() }}</div>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="col-span-full text-center py-12 text-gray-400 font-bold text-sm uppercase tracking-widest">No notes uploaded yet</div>
                    @endforelse
                </div>
                <x-admin.tab-pagination :total="$stats['notes_count']" />
            </div>
        </div>

        {{-- Chats --}}
        <div x-show="activeTab === 'chats'" style="display:none">
            @php $logsCount = $institute->whatsappLogs->count(); @endphp
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden"
                 x-data="{ page:1, perPage:10, total:{{ $logsCount }}, get totalPages(){return Math.ceil(this.total/this.perPage);}, get from(){return (this.page-1)*this.perPage+1;}, get to(){return Math.min(this.page*this.perPage,this.total);}, show(i){return i>=(this.page-1)*this.perPage&&i<this.page*this.perPage;} }">
                <div class="p-4 border-b border-gray-50 flex items-center justify-between">
                    <h4 class="text-sm font-black text-gray-900 uppercase tracking-widest">Messaging & WhatsApp Logs</h4>
                    <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Total: {{ $logsCount }} Logs</span>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead class="bg-gray-50/50"><tr>
                            <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">Recipient</th>
                            <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">Message</th>
                            <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">Type</th>
                            <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">Status</th>
                        </tr></thead>
                        <tbody class="divide-y divide-gray-50">
                            @forelse($institute->whatsappLogs as $i => $log)
                            <tr class="hover:bg-gray-50/50 transition" x-show="show({{ $i }})">
                                <td class="px-6 py-4"><div class="text-sm font-bold text-gray-900">{{ $log->recipient_name ?? $log->recipient_phone }}</div><div class="text-[10px] text-gray-400">{{ $log->created_at->format('M d, H:i') }}</div></td>
                                <td class="px-6 py-4"><p class="text-xs text-gray-600 line-clamp-1 max-w-xs">{{ $log->message }}</p></td>
                                <td class="px-6 py-4"><span class="text-[10px] font-black text-gray-400 uppercase tracking-widest">{{ $log->type }}</span></td>
                                <td class="px-6 py-4"><span class="px-2 py-0.5 rounded-full text-[9px] font-black uppercase tracking-wider @if($log->status=='sent') bg-emerald-50 text-emerald-600 border border-emerald-100 @elseif($log->status=='failed') bg-red-50 text-red-600 border border-red-100 @else bg-blue-50 text-blue-600 border border-blue-100 @endif">{{ $log->status }}</span></td>
                            </tr>
                            @empty
                            <tr><td colspan="4" class="px-6 py-12 text-center text-gray-400 font-bold text-sm uppercase tracking-widest">No chat logs available</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <x-admin.tab-pagination :total="$logsCount" />
            </div>
        </div>

    </div>

    {{-- Delete Confirmation Modal --}}
    <div x-show="deleteModal"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-50 flex items-center justify-center p-4"
         style="display:none">

        {{-- Backdrop --}}
        <div class="absolute inset-0 bg-gray-900/50 backdrop-blur-sm" @click="deleteModal = false"></div>

        {{-- Modal Box --}}
        <div x-show="deleteModal"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 scale-95 translate-y-4"
             x-transition:enter-end="opacity-100 scale-100 translate-y-0"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 scale-100 translate-y-0"
             x-transition:leave-end="opacity-0 scale-95 translate-y-4"
             class="relative bg-white rounded-2xl shadow-2xl w-full max-w-sm p-6 border border-gray-100">

            {{-- Icon --}}
            <div class="flex items-center justify-center w-14 h-14 bg-orange-50 rounded-2xl mx-auto mb-4 border border-orange-100">
                <svg class="w-7 h-7 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
            </div>

            {{-- Title --}}
            <h3 class="text-base font-black text-gray-900 text-center tracking-tight">Delete <span x-text="deleteType"></span>?</h3>
            <p class="text-sm text-gray-500 text-center mt-1">
                Are you sure you want to remove
                <span class="font-bold text-gray-800" x-text="deleteName"></span>?
                <br><span class="text-[11px] text-primary font-bold">This action cannot be undone.</span>
            </p>

            {{-- Actions --}}
            <div class="flex gap-3 mt-6">
                <button type="button" @click="deleteModal = false"
                        class="flex-1 px-4 py-2.5 bg-primary/10 hover:bg-primary/20 text-primary font-black text-xs uppercase tracking-widest rounded-xl transition-all active:scale-95">
                    Cancel
                </button>
                <button type="button"
                        @click="
                            fetch(deleteUrl, {
                                method: 'DELETE',
                                credentials: 'same-origin',
                                headers: {
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                    'Accept': 'application/json',
                                    'X-Requested-With': 'XMLHttpRequest'
                                }
                            }).then(res => {
                                if (res.ok || res.redirected) {
                                    deleteModal = false;
                                    if (deleteElement) {
                                        deleteElement.style.transition = 'opacity 0.35s ease, transform 0.35s ease';
                                        deleteElement.style.opacity = '0';
                                        deleteElement.style.transform = 'scale(0.92)';
                                        setTimeout(() => {
                                            deleteElement.remove();
                                            if (deleteScopeEl) Alpine.$data(deleteScopeEl).total--;
                                        }, 380);
                                    }
                                }
                            }).catch(err => console.error('Delete failed:', err))
                        "
                        class="flex-1 px-4 py-2.5 bg-primary hover:bg-primary/80 text-white font-black text-xs uppercase tracking-widest rounded-xl shadow-lg shadow-primary/25 transition-all active:scale-95">
                    Delete
                </button>
            </div>
        </div>
    </div>

</div>
<style>.no-scrollbar::-webkit-scrollbar{display:none}.no-scrollbar{-ms-overflow-style:none;scrollbar-width:none}</style>
</x-admin-layout>