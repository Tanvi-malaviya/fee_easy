<x-admin-layout title="{{ $institute->institute_name }} - Details">
    <div class="max-w-7xl mx-auto"
        x-data="{ activeTab: 'subscriptions', deleteModal: false, deleteUrl: '', deleteName: '', deleteType: 'Student', deleteElement: null, deleteScopeEl: null }">

        {{-- Header --}}
        <div class="flex items-center justify-between mb-2 px-2">
            <a href="{{ route('institutes.index') }}"
                class="inline-flex items-center text-gray-400 hover:text-primary transition-colors group">
                <div
                    class="p-2 bg-white border border-gray-100 rounded-xl shadow-sm group-hover:border-primary/20 transition-all active:scale-90">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                </div>
                <span class="ml-3 text-[10px] font-bold uppercase tracking-widest">Back to Institutes</span>
            </a>
            <a href="{{ route('institutes.edit', [$institute, 'from' => 'show']) }}"
                class="inline-flex items-center px-5 py-2 bg-primary text-white rounded-xl text-[10px] font-bold uppercase tracking-widest shadow-lg shadow-primary/20 hover:opacity-90 transition active:scale-95">
                <svg class="w-3.5 h-3.5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                </svg>
                Edit Details
            </a>
        </div>

        {{-- Profile Card --}}
        <div class="bg-white border border-gray-100 rounded-xl shadow-sm p-4 mb-1.5">
            <div class="flex flex-col md:flex-row gap-5 items-start">
                <div
                    class="w-20 h-20 rounded-2xl bg-gray-50 border border-gray-100 flex items-center justify-center overflow-hidden shadow-sm flex-shrink-0">
                    @if($institute->logo)
                        <img src="{{ asset('storage/' . $institute->logo) }}" class="w-full h-full object-cover">
                    @else
                        <div class="text-2xl font-bold text-gray-200">🏢</div>
                    @endif
                </div>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-y-3.5 gap-x-6 flex-grow">
                    <div><span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Institute</span>
                        <div class="flex items-center gap-2 mt-1 flex-wrap">
                            <span class="text-sm font-bold text-gray-900">{{ $institute->institute_name }}</span>
                            <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-full text-[8px] font-black uppercase tracking-wider whitespace-nowrap @if($institute->status == 'active') bg-emerald-50 text-emerald-600 border border-emerald-100 @elseif($institute->status == 'suspended') bg-amber-50 text-amber-600 border border-amber-100 @else bg-red-50 text-red-600 border border-red-100 @endif">
                                <span class="w-1 h-1 rounded-full @if($institute->status == 'active') bg-emerald-500 @elseif($institute->status == 'suspended') bg-amber-500 @else bg-red-500 @endif"></span>
                                {{ $institute->status }}
                            </span>
                        </div>
                    </div>
                    <div><span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Owner</span>
                        <p class="text-sm font-bold text-gray-900 mt-1">{{ $institute->name }}</p>
                    </div>
                    <div><span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Email</span>
                        <p class="text-sm font-bold text-gray-700 mt-1 break-all">{{ $institute->email }}</p>
                    </div>
                    <div><span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Phone</span>
                        <p class="text-sm font-bold text-gray-700 mt-1">{{ $institute->phone }}</p>
                    </div>
                    <div><span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Address</span>
                        <p class="text-sm font-bold text-gray-700 mt-1">{{ $institute->address ?? 'N/A' }}</p>
                    </div>
                    <div><span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">City</span>
                        <p class="text-sm font-bold text-gray-700 mt-1">{{ $institute->city ?? 'N/A' }}</p>
                    </div>
                    <div><span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">State</span>
                        <p class="text-sm font-bold text-gray-700 mt-1">{{ $institute->state ?? 'N/A' }}</p>
                    </div>
                    <div><span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Pincode</span>
                        <p class="text-sm font-bold text-gray-700 mt-1">{{ $institute->pincode ?? 'N/A' }}</p>
                    </div>
                </div>
            </div>
        </div>
 
        {{-- Tab Nav --}}
        <div class="bg-white border border-gray-100 rounded-xl shadow-sm mb-1.5 p-1">
            <div class="flex overflow-x-auto no-scrollbar gap-1 px-1">
                @foreach(['subscriptions' => 'Subscriptions', 'students' => 'Students', 'staff' => 'Staff', 'batches' => 'Batches', 'financials' => 'Financials', 'leads' => 'Leads', 'updates' => 'Updates', 'notes' => 'Notes'] as $tab => $label)
                    <button @click="activeTab = '{{ $tab }}'"
                        :class="activeTab === '{{ $tab }}' ? 'bg-primary text-white shadow-sm' : 'text-gray-400 hover:text-gray-600 hover:bg-gray-50/50'"
                        class="px-5 py-2 border-0 rounded-xl font-black text-[9px] uppercase tracking-widest transition-all whitespace-nowrap focus:outline-none">{{ $label }}</button>
                @endforeach
            </div>
        </div>

        {{-- Tab Content --}}
        <div class="space-y-1 ">

            {{-- Subscriptions --}}
            <div x-show="activeTab === 'subscriptions'" style="display:none">
                <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
                    <div class="p-4 border-b border-gray-50 flex items-center justify-between">
                        <h4 class="text-sm font-black text-gray-900 uppercase tracking-widest">Subscription History</h4>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left">
                            <thead class="bg-gray-50/50">
                                <tr>
                                    <th
                                        class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">
                                        Plan</th>
                                    <th
                                        class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">
                                        Amount</th>
                                    <th
                                        class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">
                                        Period</th>
                                    <th
                                        class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">
                                        Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-50">
                                @forelse($institute->subscriptions as $sub)
                                    <tr class="hover:bg-gray-50/50 transition">
                                        <td class="px-6 py-4"><span
                                                class="text-sm font-black text-gray-900">{{ $sub->plan_name }}</span></td>
                                        <td class="px-6 py-4"><span
                                                class="text-sm font-bold text-gray-700">₹{{ number_format($sub->amount) }}</span>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="text-[11px] font-medium text-gray-500">
                                                {{ \Carbon\Carbon::parse($sub->start_date)->format('M d, Y') }} –
                                                {{ \Carbon\Carbon::parse($sub->end_date)->format('M d, Y') }}</div>
                                        </td>
                                        <td class="px-6 py-4"><span
                                                class="px-2 py-0.5 rounded-full text-[9px] font-black uppercase tracking-wider @if($sub->status == 'active') bg-emerald-50 text-emerald-600 border border-emerald-100 @elseif($sub->status == 'expired') bg-red-50 text-red-600 border border-red-100 @else bg-gray-50 text-gray-600 border border-gray-100 @endif">{{ $sub->status }}</span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="p-0">
                                            <x-empty-state 
                                                title="No subscriptions found" 
                                                subtitle="No subscription records found for this institute." 
                                                icon="fees"
                                                plain="true"
                                                class="py-12"
                                            />
                                        </td>
                                    </tr>
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
                        <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Total:
                            {{ $stats['students_count'] }} Students</span>
                    </div>
                    @if($institute->students->isEmpty())
                        <x-empty-state 
                            title="No students found" 
                            subtitle="No student admissions records found for this institute." 
                            icon="students"
                            plain="true"
                            class="py-16"
                        />
                    @else
                        <div class="p-4 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-5 gap-3">
                            @foreach($institute->students as $idx => $student)
                                @php $isActive = $student->status == 'active' || $student->status == 1 || $student->status === true; @endphp
                                <div class="group bg-white rounded-xl border border-gray-100 hover:shadow-lg hover:shadow-primary/5 transition-all duration-300 flex flex-col overflow-hidden delete-item"
                                    x-show="show({{ $idx }})">
                                    <div class="flex items-center justify-between px-3 pt-3 pb-1">
                                        <span
                                            class="px-2 py-0.5 bg-gray-50 text-gray-400 text-[9px] font-black rounded-md uppercase tracking-tight">{{ $student->batch?->name ?? 'Unassigned' }}</span>
                                        <span
                                            class="text-[9px] font-bold text-gray-300">{{ $student->created_at->format('M d') }}</span>
                                    </div>
                                    <div class="flex flex-col items-start px-3 pb-3 pt-2 flex-1">
                                        <div
                                            class="h-14 w-14 rounded-full border-2 border-gray-100 overflow-hidden mb-2 shadow-sm">
                                            @if($student->profile_image)
                                                <img src="{{ asset('storage/' . $student->profile_image) }}"
                                                    class="w-full h-full object-cover">
                                            @else
                                                <img src="https://ui-avatars.com/api/?name={{ urlencode($student->name) }}&color=7F9CF5&background=EBF4FF"
                                                    class="w-full h-full object-cover">
                                            @endif
                                        </div>
                                        <h4 class="text-sm font-black text-gray-800 tracking-tight leading-tight">
                                            {{ $student->name }}</h4>
                                        <p class="text-[10px] font-medium text-gray-400 truncate w-full mt-0.5">
                                            {{ $student->email ?: 'No email' }}</p>
                                    </div>
                                    <div class="px-3 pb-3 space-y-1.5">
                                        <div class="flex items-center justify-between"><span
                                                class="text-[9px] font-black text-gray-400 uppercase tracking-wider">Phone</span><span
                                                class="text-[10px] font-bold text-gray-600">{{ $student->phone ?: '—' }}</span>
                                        </div>
                                        <div class="flex items-center justify-between"><span
                                                class="text-[9px] font-black text-gray-400 uppercase tracking-wider">Monthly
                                                Fee</span><span
                                                class="text-[10px] font-black text-primary">₹{{ number_format($student->monthly_fee ?? 0) }}</span>
                                        </div>
                                        <div class="flex items-center justify-between"><span
                                                class="text-[9px] font-black text-gray-400 uppercase tracking-wider">Standard</span><span
                                                class="text-[10px] font-bold text-gray-600">{{ $student->standard ?: '—' }}</span>
                                        </div>
                                    </div>
                                    <div
                                        class="flex items-center justify-between px-3 py-2 bg-gray-50/80 border-t border-gray-100">
                                        <span class="text-[9px] text-gray-400 font-medium">Joined
                                            {{ $student->created_at->format('M d, Y') }}</span>
                                        <button type="button"
                                            @click="deleteUrl='{{ route('institutes.students.destroy', [$institute, $student]) }}'; deleteName='{{ addslashes($student->name) }}'; deleteType='Student'; deleteElement=$el.closest('.delete-item'); deleteScopeEl=$el.closest('[x-data]'); deleteModal=true"
                                            class="text-red-500 hover:text-red-700 transition-colors duration-200 p-1.5 rounded-lg hover:bg-red-100">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
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
            <div x-show="activeTab === 'staff'" style="display:none" x-data="{ staffTab: 'staff' }">
                {{-- Staff Sub Navigation --}}
                <div
                    class="flex items-center gap-2 p-1 mb-2 bg-white border border-gray-100 shadow-sm rounded-2xl w-fit">
                    <button @click="staffTab = 'staff'"
                        :class="staffTab === 'staff' ? 'bg-primary text-white shadow-lg shadow-primary/20 font-bold' : 'text-gray-500 font-semibold hover:text-gray-800 hover:bg-gray-50'"
                        class="px-5 py-2 rounded-xl text-xs transition-all">
                        Staffs Management
                    </button>
                    <button @click="staffTab = 'attendance'"
                        :class="staffTab === 'attendance' ? 'bg-primary text-white shadow-lg shadow-primary/20 font-bold' : 'text-gray-500 font-semibold hover:text-gray-800 hover:bg-gray-50'"
                        class="px-5 py-2 rounded-xl text-xs transition-all">
                        Attendance Management
                    </button>
                    <button @click="staffTab = 'salary'"
                        :class="staffTab === 'salary' ? 'bg-primary text-white shadow-lg shadow-primary/20 font-bold' : 'text-gray-500 font-semibold hover:text-gray-800 hover:bg-gray-50'"
                        class="px-5 py-2 rounded-xl text-xs transition-all">
                        Salary Management
                    </button>
                </div>

                {{-- Staffs Management List --}}
                <div x-show="staffTab === 'staff'">
                    <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden"
                        x-data="{ page:1, perPage:10, total:{{ $stats['staff_count'] }}, get totalPages(){return Math.ceil(this.total/this.perPage);}, get from(){return (this.page-1)*this.perPage+1;}, get to(){return Math.min(this.page*this.perPage,this.total);}, show(i){return i>=(this.page-1)*this.perPage&&i<this.page*this.perPage;} }">
                        <div class="p-4 border-b border-gray-50 flex items-center justify-between">
                            <h4 class="text-sm font-black text-gray-900 uppercase tracking-widest">Staff Management</h4>
                            <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Total:
                                {{ $stats['staff_count'] }} Staff</span>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="w-full text-left">
                                <thead class="bg-gray-50/50">
                                    <tr>
                                        <th
                                            class="px-4 py-3 text-[10px] font-black text-gray-400 uppercase tracking-widest">
                                            Name</th>
                                        <th
                                            class="px-4 py-3 text-[10px] font-black text-gray-400 uppercase tracking-widest">
                                            Role/Dept</th>
                                        <th
                                            class="px-4 py-3 text-[10px] font-black text-gray-400 uppercase tracking-widest">
                                            Email</th>
                                        <th
                                            class="px-4 py-3 text-[10px] font-black text-gray-400 uppercase tracking-widest">
                                            Contact</th>
                                        <th
                                            class="px-4 py-3 text-[10px] font-black text-gray-400 uppercase tracking-widest">
                                            Salary</th>
                                        <th
                                            class="px-4 py-3 text-[10px] font-black text-gray-400 uppercase tracking-widest">
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-50">
                                    @forelse($institute->staff as $i => $member)
                                        <tr class="hover:bg-gray-50/50 transition delete-item" x-show="show({{ $i }})">
                                            <td class="px-4 py-2.5"><span
                                                    class="text-sm font-bold text-gray-900">{{ $member->full_name }}</span>
                                            </td>
                                            <td class="px-4 py-2.5">
                                                <div class="text-[11px] font-black text-primary uppercase tracking-wider">
                                                    {{ $member->role->name ?? 'Staff' }}</div>
                                                <div class="text-[10px] font-bold text-gray-400 uppercase">
                                                    {{ $member->department->name ?? 'General' }}</div>
                                            </td>
                                            <td class="px-4 py-2.5"><span
                                                    class="text-[11px] text-gray-500 font-medium">{{ $member->email ?: '—' }}</span>
                                            </td>
                                            <td class="px-4 py-2.5 text-xs text-gray-700 font-bold">{{ $member->phone }}
                                            </td>
                                            <td class="px-4 py-2.5"><span
                                                    class="text-sm font-black text-gray-900">₹{{ number_format($member->base_salary ?? 0) }}</span>
                                            </td>
                                            <td class="px-4 py-2.5 text-right">
                                                <button type="button"
                                                    @click="deleteUrl='{{ route('institutes.staff.destroy', [$institute, $member]) }}'; deleteName='{{ addslashes($member->full_name) }}'; deleteType='Staff'; deleteElement=$el.closest('.delete-item'); deleteScopeEl=$el.closest('[x-data]'); deleteModal=true"
                                                    class="text-red-500 hover:text-red-700 transition-colors duration-200 p-1.5 rounded-lg hover:bg-red-100">
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2.5"
                                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                    </svg>
                                                </button>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="p-0">
                                                <x-empty-state 
                                                    title="No staff found" 
                                                    subtitle="No staff records found for this institute." 
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
                        <x-admin.tab-pagination :total="$stats['staff_count']" />
                    </div>
                </div>

                {{-- Attendance Management List --}}
                <div x-show="staffTab === 'attendance'" style="display:none">
                    <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden"
                        x-data="{ page:1, perPage:10, total:{{ $institute->staffAttendances->count() }}, get totalPages(){return Math.ceil(this.total/this.perPage);}, get from(){return (this.page-1)*this.perPage+1;}, get to(){return Math.min(this.page*this.perPage,this.total);}, show(i){return i>=(this.page-1)*this.perPage&&i<this.page*this.perPage;} }">
                        <div class="p-4 border-b border-gray-50 flex items-center justify-between">
                            <h4 class="text-sm font-black text-gray-900 uppercase tracking-widest">Attendance Management
                            </h4>
                            <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Total:
                                {{ $institute->staffAttendances->count() }} Records</span>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="w-full text-left">
                                <thead class="bg-gray-50/50">
                                    <tr>
                                        <th
                                            class="px-4 py-3 text-[10px] font-black text-gray-400 uppercase tracking-widest">
                                            Staff Name</th>
                                        <th
                                            class="px-4 py-3 text-[10px] font-black text-gray-400 uppercase tracking-widest">
                                            Department</th>
                                        <th
                                            class="px-4 py-3 text-[10px] font-black text-gray-400 uppercase tracking-widest">
                                            Date</th>
                                        <th
                                            class="px-4 py-3 text-[10px] font-black text-gray-400 uppercase tracking-widest">
                                            Status</th>
                                        <th
                                            class="px-4 py-3 text-[10px] font-black text-gray-400 uppercase tracking-widest">
                                            Notes</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-50">
                                    @forelse($institute->staffAttendances as $i => $att)
                                        <tr class="hover:bg-gray-50/50 transition" x-show="show({{ $i }})">
                                            <td class="px-4 py-2.5"><span
                                                    class="text-sm font-bold text-gray-900">{{ $att->staff->full_name ?? 'N/A' }}</span>
                                            </td>
                                            <td class="px-4 py-2.5"><span
                                                    class="text-xs font-semibold text-gray-500 uppercase">{{ $att->staff->department->name ?? 'General' }}</span>
                                            </td>
                                            <td class="px-4 py-2.5"><span
                                                    class="text-xs font-bold text-gray-700">{{ \Carbon\Carbon::parse($att->date)->format('d M, Y') }}</span>
                                            </td>
                                            <td class="px-4 py-2.5">
                                                <span
                                                    class="px-2.5 py-1 rounded-full text-[10px] font-bold @if($att->status == 'Present') bg-emerald-50 text-emerald-600 border border-emerald-100 @else bg-red-50 text-red-600 border border-red-100 @endif">
                                                    {{ $att->status }}
                                                </span>
                                            </td>
                                            <td class="px-4 py-2.5"><span
                                                    class="text-xs text-gray-500">{{ $att->note ?: '—' }}</span></td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="p-0">
                                                <x-empty-state 
                                                    title="No attendance records" 
                                                    subtitle="No attendance records found for this institute's staff." 
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
                        <x-admin.tab-pagination :total="$institute->staffAttendances->count()" />
                    </div>
                </div>

                {{-- Salary Management List --}}
                <div x-show="staffTab === 'salary'" style="display:none">
                    <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden"
                        x-data="{ page:1, perPage:10, total:{{ $institute->staffSalaries->count() }}, get totalPages(){return Math.ceil(this.total/this.perPage);}, get from(){return (this.page-1)*this.perPage+1;}, get to(){return Math.min(this.page*this.perPage,this.total);}, show(i){return i>=(this.page-1)*this.perPage&&i<this.page*this.perPage;} }">
                        <div class="p-4 border-b border-gray-50 flex items-center justify-between">
                            <h4 class="text-sm font-black text-gray-900 uppercase tracking-widest">Salary Management
                            </h4>
                            <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Total:
                                {{ $institute->staffSalaries->count() }} Records</span>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="w-full text-left">
                                <thead class="bg-gray-50/50">
                                    <tr>
                                        <th
                                            class="px-4 py-3 text-[10px] font-black text-gray-400 uppercase tracking-widest">
                                            Staff Name</th>
                                        <th
                                            class="px-4 py-3 text-[10px] font-black text-gray-400 uppercase tracking-widest">
                                            Employee ID</th>
                                        <th
                                            class="px-4 py-3 text-[10px] font-black text-gray-400 uppercase tracking-widest">
                                            Date</th>
                                        <th
                                            class="px-4 py-3 text-[10px] font-black text-gray-400 uppercase tracking-widest">
                                            Payment Mode</th>
                                        <th
                                            class="px-4 py-3 text-[10px] font-black text-gray-400 uppercase tracking-widest">
                                            Amount</th>
                                        <!-- <th class="px-4 py-3 text-[10px] font-black text-gray-400 uppercase tracking-widest">Status</th> -->
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-50">
                                    @forelse($institute->staffSalaries as $i => $sal)
                                        <tr class="hover:bg-gray-50/50 transition" x-show="show({{ $i }})">
                                            <td class="px-4 py-2.5"><span
                                                    class="text-sm font-bold text-gray-900">{{ $sal->staff->full_name ?? 'N/A' }}</span>
                                            </td>
                                            <td class="px-4 py-2.5"><span
                                                    class="text-xs font-mono font-bold text-gray-500">{{ !empty($sal->staff->employee_id) ? $sal->staff->employee_id : ('STF-' . ($sal->staff->id ?? '—')) }}</span>
                                            </td>
                                            <td class="px-4 py-2.5"><span
                                                    class="text-xs font-bold text-gray-700">{{ \Carbon\Carbon::parse($sal->payment_date)->format('d M, Y') }}</span>
                                            </td>
                                            <td class="px-4 py-2.5"><span
                                                    class="text-xs font-semibold text-gray-600">{{ $sal->payment_method ?? 'Cash' }}</span>
                                            </td>
                                            <td class="px-4 py-2.5"><span
                                                    class="text-sm font-black text-primary">₹{{ number_format($sal->base_salary ?? 0) }}</span>
                                            </td>
                                            <!-- <td class="px-4 py-2.5">
                                            <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-600 border border-emerald-100">
                                                {{ $sal->status ?? 'Paid' }}
                                            </span>
                                        </td> -->
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="p-0">
                                                <x-empty-state 
                                                    title="No salary records" 
                                                    subtitle="No salary payout records found for this institute's staff." 
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
                        <x-admin.tab-pagination :total="$institute->staffSalaries->count()" />
                    </div>
                </div>
            </div>

            {{-- Batches --}}
            <div x-show="activeTab === 'batches'" style="display:none" >
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
                                        <span class="text-sm font-black text-gray-900">{{ $batch->name }}</span>
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
                                    <td class="px-4 py-2.5 text-right whitespace-nowrap">
                                        <a href="{{ route('institutes.batches.show', [$institute, $batch]) }}" title="View Batch Details" class="text-primary hover:text-orange-700 transition-colors duration-200 p-1.5 rounded-lg hover:bg-orange-100 inline-block align-middle mr-1">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                        </a>
                                        <button type="button"
                                                @click="deleteUrl='{{ route('institutes.batches.destroy', [$institute, $batch]) }}'; deleteName='{{ addslashes($batch->name) }}'; deleteType='Batch'; deleteElement=$el.closest('.delete-item'); deleteScopeEl=$el.closest('[x-data]'); deleteModal=true"
                                                class="text-red-500 hover:text-red-700 transition-colors duration-200 p-1.5 rounded-lg hover:bg-red-100 align-middle">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="p-0">
                                        <x-empty-state 
                                            title="No batches found" 
                                            subtitle="No batch records found for this institute." 
                                            icon="notes"
                                            plain="true"
                                            class="py-12"
                                        />
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <x-admin.tab-pagination :total="$stats['batches_count']" />
            </div>
        </div>

        {{-- Financials --}}
        <div x-show="activeTab === 'financials'" style="display:none">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 items-start">
                
                {{-- Recent Fee Collections --}}
                <div class="bg-white rounded-xl border border-gray-100 shadow-sm flex flex-col overflow-hidden"
                     x-data="{ page:1, perPage:10, total:{{ $institute->fees->count() }}, get totalPages(){return Math.ceil(this.total/this.perPage);}, get from(){return (this.page-1)*this.perPage+1;}, get to(){return Math.min(this.page*this.perPage,this.total);}, show(i){return i>=(this.page-1)*this.perPage&&i<this.page*this.perPage;} }">
                    <div class="p-4 border-b border-gray-50 bg-emerald-50/30 shrink-0">
                        <h4 class="text-sm font-black text-emerald-600 uppercase tracking-widest">Recent Fee Collections</h4>
                    </div>
                    <div class="overflow-x-auto flex-1">
                        <table class="w-full text-left"><tbody class="divide-y divide-gray-50">
                        @forelse($institute->fees as $i => $fee)
                            <tr class="hover:bg-gray-50/50 transition" x-show="show({{ $i }})">
                                <td class="px-6 py-4"><div class="text-sm font-bold text-gray-900">{{ $fee->student->name ?? 'Student' }}</div><div class="text-[10px] text-gray-400">{{ $fee->created_at->format('M d, Y') }}</div></td>
                                <td class="px-6 py-4 text-right"><div class="text-sm font-black text-emerald-600">+₹{{ number_format($fee->paid_amount) }}</div><div class="text-[10px] font-bold text-gray-400 uppercase">{{ $fee->payment_method ?? 'Offline' }}</div></td>
                            </tr>
                        @empty
                            <tr><td colspan="2" class="px-6 py-8 text-center text-gray-400 font-bold text-xs uppercase">No fees collected yet</td></tr>
                        @endforelse
                        </tbody></table>
                    </div>
                    @if($institute->fees->count() > 10)
                        <div class="shrink-0 border-t border-gray-50">
                            <x-admin.tab-pagination :total="$institute->fees->count()" />
                        </div>
                    @endif
                </div>

                {{-- Recent Expenses --}}
                <div class="bg-white rounded-xl border border-gray-100 shadow-sm flex flex-col overflow-hidden"
                     x-data="{ page:1, perPage:10, total:{{ $institute->expenses->count() }}, get totalPages(){return Math.ceil(this.total/this.perPage);}, get from(){return (this.page-1)*this.perPage+1;}, get to(){return Math.min(this.page*this.perPage,this.total);}, show(i){return i>=(this.page-1)*this.perPage&&i<this.page*this.perPage;} }">
                    <div class="p-4 border-b border-gray-50 bg-rose-50/30 shrink-0">
                        <h4 class="text-sm font-black text-primary uppercase tracking-widest">Recent Expenses</h4>
                    </div>
                    <div class="overflow-x-auto flex-1">
                        <table class="w-full text-left"><tbody class="divide-y divide-gray-50">
                        @forelse($institute->expenses as $i => $expense)
                            <tr class="hover:bg-gray-50/50 transition" x-show="show({{ $i }})">
                                <td class="px-6 py-4"><div class="text-sm font-bold text-gray-900">{{ $expense->title }}</div><div class="text-[10px] text-gray-400">{{ $expense->created_at->format('M d, Y') }}</div></td>
                                <td class="px-6 py-4 text-right"><div class="text-sm font-black text-primary">-₹{{ number_format($expense->amount) }}</div><div class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">{{ $expense->category->name ?? 'General' }}</div></td>
                            </tr>
                        @empty
                            <tr><td colspan="2" class="px-6 py-8 text-center text-gray-400 font-bold text-xs uppercase tracking-widest">No expenses recorded</td></tr>
                        @endforelse
                        </tbody></table>
                    </div>
                    @if($institute->expenses->count() > 10)
                        <div class="shrink-0 border-t border-gray-50">
                            <x-admin.tab-pagination :total="$institute->expenses->count()" />
                        </div>
                    @endif
                </div>

            </div>
        </div>

        {{-- Leads --}}
        <div x-show="activeTab === 'leads'" style="display:none">
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden"
                 x-data="{ page:1, perPage:9, total:{{ $stats['leads_count'] }}, get totalPages(){return Math.ceil(this.total/this.perPage);}, get from(){return (this.page-1)*this.perPage+1;}, get to(){return Math.min(this.page*this.perPage,this.total);}, show(i){return i>=(this.page-1)*this.perPage&&i<this.page*this.perPage;} }">
                <div class="p-2 pl-4 border-b border-gray-50 flex items-center justify-between">
                    <h4 class="text-sm font-black text-gray-900 uppercase tracking-widest">Lead Management</h4>
                    <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Total: {{ $stats['leads_count'] }} Leads</span>
                </div>
                
                <div class="p-2 bg-gray-50/30">
                    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-2">
                        @forelse($institute->leads as $i => $lead)
                            <div class="bg-white rounded-xl border border-gray-200 p-3.5 shadow-sm relative transition hover:shadow-md" x-show="show({{ $i }})">


                                <!-- Header -->
                                <div class="flex items-center gap-3 mb-3 pr-2">
                                    <div class="h-10 w-10 bg-orange-50 rounded-xl flex items-center justify-center text-primary font-black text-sm border border-orange-100 shrink-0">
                                        {{ substr(strtoupper(preg_replace('/[^a-zA-Z]/', '', $lead->full_name)), 0, 2) ?: 'LD' }}
                                    </div>
                                    <div class="overflow-hidden">
                                        <h4 class="text-sm font-bold text-gray-900 truncate" title="{{ $lead->full_name }}">{{ $lead->full_name }}</h4>
                                        <p class="text-[10px] font-bold text-gray-400 truncate">{{ $lead->course_selection ?? 'General Inquiry' }}</p>
                                    </div>
                                </div>

                                <!-- Details Grid -->
                                <div class="grid grid-cols-2 gap-y-2 gap-x-2 text-xs">
                                    <div>
                                        <span class="flex items-center gap-1 text-[9px] font-black text-gray-400 uppercase tracking-widest mb-1">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                                            Phone
                                        </span>
                                        <span class="font-bold text-gray-700">{{ $lead->phone ?? 'N/A' }}</span>
                                    </div>
                                    <div>
                                        <span class="flex items-center gap-1 text-[9px] font-black text-gray-400 uppercase tracking-widest mb-1">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                                            Email
                                        </span>
                                        <span class="font-bold text-gray-700 break-all block">{{ $lead->email ?? 'N/A' }}</span>
                                    </div>
                                    <div>
                                        <span class="flex items-center gap-1 text-[9px] font-black text-gray-400 uppercase tracking-widest mb-1">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/></svg>
                                            Source
                                        </span>
                                        <span class="font-bold text-gray-700">{{ $lead->reference ?? 'N/A' }}</span>
                                    </div>
                                    <div class="col-span-2">
                                        <span class="flex items-center gap-1 text-[9px] font-black text-gray-400 uppercase tracking-widest mb-1">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                            Address
                                        </span>
                                        <span class="font-medium text-gray-600 leading-relaxed">{{ $lead->address ?? 'N/A' }}</span>
                                    </div>
                                    <div class="col-span-2 mt-1 pt-2 border-t border-gray-100 flex justify-between items-center">
                                        <span class="text-[10px] font-bold text-gray-400 flex items-center gap-1">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                            Added: {{ $lead->created_at->format('M d, Y') }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="col-span-full p-0">
                                <x-empty-state 
                                    title="No leads found" 
                                    subtitle="No leads found for this institute." 
                                    icon="users"
                                    plain="true"
                                    class="py-12"
                                />
                            </div>
                        @endforelse
                    </div>
                </div>
                
                @if($institute->leads->count() > 9)
                    <div class="border-t border-gray-100">
                        <x-admin.tab-pagination :total="$stats['leads_count']" />
                    </div>
                @endif
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
                <div class="p-3 bg-gray-50/30">
                    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-1">
                        @forelse($institute->dailyUpdates as $i => $update)
                            @php
                                $cat = $update->category === 'Administrative' ? 'Fee Reminder' : ($update->category ?? 'Other');
                                $colorClasses = match ($update->category) {
                                    'Emergency' => 'bg-rose-50 text-rose-600 border-rose-100',
                                    'Academic', 'Administrative' => 'bg-orange-50 text-[#ff6c00] border-orange-100',
                                    'Event' => 'bg-sky-50 text-sky-600 border-sky-100',
                                    'Holiday' => 'bg-indigo-50 text-indigo-600 border-indigo-100',
                                    default => 'bg-slate-50 text-slate-600 border-slate-100'
                                };
                                $iconColor = match ($update->category) {
                                    'Emergency' => 'bg-rose-500 text-white shadow-rose-500/30',
                                    'Academic', 'Administrative' => 'bg-[#ff6c00] text-white shadow-orange-500/30',
                                    'Event' => 'bg-sky-500 text-white shadow-sky-500/30',
                                    'Holiday' => 'bg-indigo-500 text-white shadow-indigo-500/30',
                                    default => 'bg-slate-500 text-white shadow-slate-500/30'
                                };

                                $targetValue = 'Everyone';
                                if ($update->target_type === 'all') {
                                    $targetValue = 'All Students';
                                } elseif ($update->target_type === 'batch') {
                                    $targetValue = $update->batch ? $update->batch->name : 'Batch';
                                } elseif ($update->target_type === 'standard') {
                                    $targetValue = $update->standard ? $update->standard . ' Std' : 'Standard';
                                }
                            @endphp
                            <div class="bg-white rounded-xl border border-gray-200 p-3 shadow-sm hover:shadow-md transition relative flex flex-col" x-show="show({{ $i }})">
                                <div class="flex items-start justify-between gap-2 mb-2">
                                    <div class="flex items-center gap-2 overflow-hidden">
                                        <div class="h-8 w-8 {{ $iconColor }} rounded-lg shadow-sm flex items-center justify-center shrink-0">
                                            @if($update->category == 'Emergency')
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                                            @elseif($update->category == 'Event')
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                            @elseif($update->category == 'Holiday')
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/></svg>
                                            @elseif($update->category == 'Administrative')
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                                            @else
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477-4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                                            @endif
                                        </div>
                                        <div class="overflow-hidden">
                                            <h4 class="text-sm font-bold text-gray-900 truncate" title="{{ $update->topic ?? $update->category }}">{{ $update->topic ?? $update->category ?? 'Update' }}</h4>
                                            <span class="text-[9px] font-black {{ $colorClasses }} border px-1.5 py-0.5 rounded uppercase tracking-widest mt-0.5 inline-block">{{ strtoupper($cat) }}</span>
                                        </div>
                                    </div>
                                </div>

                                <p class="text-[11px] text-gray-500 leading-relaxed font-medium mb-2 flex-1 line-clamp-2 break-words">{{ $update->description }}</p>

                                <div class="mt-auto pt-2 border-t border-gray-50 flex items-center justify-between">
                                    <div class="flex items-center gap-1.5 text-[9px] font-bold text-gray-400 uppercase tracking-widest">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                                        Target: <span class="text-gray-600 truncate max-w-[80px]" title="{{ $targetValue }}">{{ $targetValue }}</span>
                                    </div>
                                    <div class="flex items-center gap-1.5 text-[9px] font-bold text-gray-400 uppercase tracking-widest">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        {{ str_replace([' seconds', ' minutes', ' hours', ' days', ' weeks', ' months', ' years'], ['s', 'm', 'h', 'd', 'w', 'mo', 'y'], $update->created_at->diffForHumans(null, true)) }}
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="col-span-full p-0">
                                <x-empty-state 
                                    title="No updates published yet" 
                                    subtitle="No daily updates or announcements found for this institute." 
                                    icon="notes"
                                    plain="true"
                                    class="py-12"
                                />
                            </div>
                        @endforelse
                    </div>
                </div>
                
                @if($updatesCount > 10)
                    <div class="border-t border-gray-50">
                        <x-admin.tab-pagination :total="$updatesCount" />
                    </div>
                @endif
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
                <div class="p-4 bg-gray-50/30">
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 xl:grid-cols-4 gap-4">
                        @forelse($institute->notes as $i => $note)
                            @php
                                $catName = $note->category_relation->name ?? $note->category ?? 'Uncategorized';
                                $catColors = match (strtolower($catName)) {
                                    'work' => 'bg-rose-50 text-rose-500',
                                    'personal' => 'bg-blue-50 text-blue-500',
                                    'ideas' => 'bg-emerald-50 text-emerald-500',
                                    'meeting notes' => 'bg-amber-50 text-amber-500',
                                    'family' => 'bg-purple-50 text-purple-500',
                                    'important' => 'bg-orange-50 text-orange-500',
                                    default => 'bg-slate-50 text-slate-400'
                                };
                                $cleanPreview = strip_tags($note->content ?: 'No content provided.');
                            @endphp
                            <div class="group bg-white rounded-2xl border border-slate-100 shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 relative overflow-hidden flex flex-col h-[320px]" x-show="show({{ $i }})">
                                @if($note->image_url)
                                    <div class="w-full h-32 overflow-hidden relative shrink-0">
                                        <img src="{{ $note->image_url }}" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110" alt="{{ $note->title }}">
                                        <div class="absolute inset-0 bg-gradient-to-t from-black/10 to-transparent"></div>
                                    </div>
                                @else
                                    <div class="w-full h-32 bg-gradient-to-br from-slate-50 to-slate-100/50 flex items-center justify-center relative overflow-hidden shrink-0 border-b border-slate-50">
                                        <div class="absolute inset-0 opacity-[0.03]" style="background-image: radial-gradient(#000 1px, transparent 1px); background-size: 20px 20px;"></div>
                                        <svg class="w-8 h-8 text-slate-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                    </div>
                                @endif

                                <div class="absolute top-3 right-3 p-2 rounded-full bg-white/90 backdrop-blur-sm shadow-sm z-20 {{ $note->is_bookmarked ? 'text-[#ff6c00]' : 'text-slate-300' }}">
                                    <svg class="w-4 h-4" fill="{{ $note->is_bookmarked ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z" />
                                    </svg>
                                </div>

                                <div class="p-4 flex flex-col flex-1">
                                    <div class="mb-2">
                                        <span class="px-2 py-0.5 {{ $catColors }} rounded-md text-[8px] font-black uppercase tracking-widest inline-block">
                                            {{ $catName }}
                                        </span>
                                    </div>

                                    <h3 class="text-base font-bold text-slate-800 mb-1 break-words line-clamp-2">
                                        {{ $note->title }}
                                    </h3>

                                    <div class="text-[13px] text-slate-500 font-medium leading-relaxed break-words line-clamp-4 mb-4">
                                        {{ $cleanPreview }}
                                    </div>

                                    <div class="flex items-center justify-between pt-3 border-t border-slate-50 mt-auto">
                                        <span class="text-[9px] font-bold text-slate-400 uppercase tracking-widest">{{ strtoupper(str_replace([' seconds', ' minutes', ' hours', ' days', ' weeks', ' months', ' years'], ['s', 'm', 'h', 'd', 'w', 'mo', 'y'], $note->created_at->diffForHumans(null, true))) }} AGO</span>
                                        <div class="flex items-center gap-1">
                                            <div class="p-1.5 text-slate-300 rounded-lg">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="col-span-full p-0">
                                <x-empty-state 
                                    title="No notes uploaded yet" 
                                    subtitle="No notes or study materials found for this institute." 
                                    icon="notes"
                                    plain="true"
                                    class="py-12"
                                />
                            </div>
                        @endforelse
                    </div>
                </div>
                
                @if($stats['notes_count'] > 10)
                    <div class="border-t border-gray-50">
                        <x-admin.tab-pagination :total="$stats['notes_count']" />
                    </div>
                @endif
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
                                    <td class="px-6 py-4"><span class="px-2 py-0.5 rounded-full text-[9px] font-black uppercase tracking-wider @if($log->status == 'sent') bg-emerald-50 text-emerald-600 border border-emerald-100 @elseif($log->status == 'failed') bg-red-50 text-red-600 border border-red-100 @else bg-blue-50 text-blue-600 border border-blue-100 @endif">{{ $log->status }}</span></td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="p-0">
                                        <x-empty-state 
                                            title="No chat logs available" 
                                            subtitle="No messaging or WhatsApp log history found for this institute." 
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