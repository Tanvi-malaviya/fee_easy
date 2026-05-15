<x-admin-layout title="{{ $batch->name }} - Batch Details">
<div class="max-w-7xl mx-auto" x-data="{ activeTab: 'students' }">

    {{-- Back + Header --}}
    <div class="flex items-center justify-between mb-4 px-1">
        <div class="flex items-center gap-3">
            <a href="{{ route('institutes.show', $institute) }}"
               class="flex items-center gap-1.5 text-[10px] font-black text-gray-400 uppercase tracking-widest hover:text-primary transition-colors">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/></svg>
                {{ $institute->institute_name }}
            </a>
            <span class="text-gray-200">/</span>
            <span class="text-[10px] font-black text-primary uppercase tracking-widest">{{ $batch->name }}</span>
        </div>
        <div class="flex items-center gap-3 text-[10px] font-black text-gray-400 uppercase tracking-widest">
            @if($batch->start_time || $batch->end_time)
                <span class="bg-orange-50 text-primary px-2.5 py-1 rounded-lg border border-orange-100">
                    {{ $batch->start_time ?? '?' }} – {{ $batch->end_time ?? '?' }}
                </span>
            @endif
            @if($batch->subject)
                <span class="bg-gray-50 text-gray-500 px-2.5 py-1 rounded-lg border border-gray-100">{{ $batch->subject }}</span>
            @endif
        </div>
    </div>

    {{-- Batch Info Card --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 mb-4">
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
            <div class="text-center">
                <div class="text-2xl font-black text-primary">{{ $stats['students_count'] }}</div>
                <div class="text-[10px] font-black text-gray-400 uppercase tracking-widest mt-0.5">Students</div>
            </div>
            <div class="text-center">
                <div class="text-2xl font-black text-gray-900">{{ $stats['homework_count'] }}</div>
                <div class="text-[10px] font-black text-gray-400 uppercase tracking-widest mt-0.5">Homeworks</div>
            </div>
            <div class="text-center">
                <div class="text-2xl font-black text-gray-900">{{ $stats['attendance_count'] }}</div>
                <div class="text-[10px] font-black text-gray-400 uppercase tracking-widest mt-0.5">Attendance Days</div>
            </div>
            <div class="text-center">
                <div class="text-2xl font-black text-gray-900">{{ $stats['resources_count'] }}</div>
                <div class="text-[10px] font-black text-gray-400 uppercase tracking-widest mt-0.5">Resources</div>
            </div>
        </div>
    </div>

    {{-- Tabs --}}
    <div class="bg-white border border-gray-100 rounded-xl shadow-sm mb-1.5 p-1">
        <div class="flex overflow-x-auto no-scrollbar gap-1 px-1">
            @foreach(['students' => 'Students', 'homework' => 'Homework', 'attendance' => 'Attendance', 'resources' => 'Resources'] as $tab => $label)
            <button @click="activeTab = '{{ $tab }}'"
                    :class="activeTab === '{{ $tab }}' ? 'bg-white text-primary border-primary shadow-sm' : 'text-gray-400 border-transparent hover:text-gray-600 hover:bg-gray-50/50'"
                    class="px-5 py-2 border-2 rounded-2xl font-black text-[9px] uppercase tracking-widest transition-all whitespace-nowrap">
                {{ $label }}
            </button>
            @endforeach
        </div>
    </div>

    {{-- Students Tab --}}
    <div x-show="activeTab === 'students'" style="display:none"
         x-data="{ page:1, perPage:10, total:{{ $stats['students_count'] }}, get totalPages(){return Math.ceil(this.total/this.perPage);}, get from(){return (this.page-1)*this.perPage+1;}, get to(){return Math.min(this.page*this.perPage,this.total);}, show(i){return i>=(this.page-1)*this.perPage&&i<this.page*this.perPage;} }">
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="p-4 border-b border-gray-50 flex items-center justify-between">
                <h4 class="text-sm font-black text-gray-900 uppercase tracking-widest">Enrolled Students</h4>
                <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">{{ $stats['students_count'] }} Students</span>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead class="bg-gray-50/50"><tr>
                        <th class="px-4 py-3 text-[10px] font-black text-gray-400 uppercase tracking-widest">Name</th>
                        <th class="px-4 py-3 text-[10px] font-black text-gray-400 uppercase tracking-widest">Email</th>
                        <th class="px-4 py-3 text-[10px] font-black text-gray-400 uppercase tracking-widest">Phone</th>
                        <th class="px-4 py-3 text-[10px] font-black text-gray-400 uppercase tracking-widest">Standard</th>
                        <th class="px-4 py-3 text-[10px] font-black text-gray-400 uppercase tracking-widest">Monthly Fee</th>
                        <th class="px-4 py-3 text-[10px] font-black text-gray-400 uppercase tracking-widest">Joined</th>
                    </tr></thead>
                    <tbody class="divide-y divide-gray-50">
                        @forelse($batch->students as $i => $student)
                        <tr class="hover:bg-gray-50/50 transition" x-show="show({{ $i }})">
                            <td class="px-4 py-2.5">
                                <div class="flex items-center gap-2.5">
                                    <div class="w-7 h-7 rounded-full overflow-hidden border border-gray-100 flex-shrink-0">
                                        @if($student->profile_image)
                                            <img src="{{ asset('storage/'.$student->profile_image) }}" class="w-full h-full object-cover">
                                        @else
                                            <img src="https://ui-avatars.com/api/?name={{ urlencode($student->name) }}&color=f97316&background=fff7ed&size=28" class="w-full h-full object-cover">
                                        @endif
                                    </div>
                                    <span class="text-sm font-bold text-gray-900">{{ $student->name }}</span>
                                </div>
                            </td>
                            <td class="px-4 py-2.5 text-[11px] text-gray-500 font-medium">{{ $student->email ?: '—' }}</td>
                            <td class="px-4 py-2.5 text-xs text-gray-700 font-bold">{{ $student->phone ?: '—' }}</td>
                            <td class="px-4 py-2.5 text-[11px] text-gray-600 font-bold">{{ $student->standard ?: '—' }}</td>
                            <td class="px-4 py-2.5"><span class="text-[11px] font-black text-primary">₹{{ number_format($student->monthly_fee ?? 0) }}</span></td>
                            <td class="px-4 py-2.5 text-[10px] text-gray-400 font-bold">{{ $student->created_at->format('M d, Y') }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="6" class="px-4 py-12 text-center text-gray-400 font-bold text-sm">No students enrolled in this batch.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <x-admin.tab-pagination :total="$stats['students_count']" />
        </div>
    </div>

    {{-- Homework Tab --}}
    <div x-show="activeTab === 'homework'" style="display:none"
         x-data="{ page:1, perPage:10, total:{{ $stats['homework_count'] }}, get totalPages(){return Math.ceil(this.total/this.perPage);}, get from(){return (this.page-1)*this.perPage+1;}, get to(){return Math.min(this.page*this.perPage,this.total);}, show(i){return i>=(this.page-1)*this.perPage&&i<this.page*this.perPage;} }">
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="p-4 border-b border-gray-50 flex items-center justify-between">
                <h4 class="text-sm font-black text-gray-900 uppercase tracking-widest">Homework Assignments</h4>
                <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">{{ $stats['homework_count'] }} Assignments</span>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead class="bg-gray-50/50"><tr>
                        <th class="px-4 py-3 text-[10px] font-black text-gray-400 uppercase tracking-widest">Title</th>
                        <th class="px-4 py-3 text-[10px] font-black text-gray-400 uppercase tracking-widest">Description</th>
                        <th class="px-4 py-3 text-[10px] font-black text-gray-400 uppercase tracking-widest">Due Date</th>
                        <th class="px-4 py-3 text-[10px] font-black text-gray-400 uppercase tracking-widest">Submissions</th>
                        <th class="px-4 py-3 text-[10px] font-black text-gray-400 uppercase tracking-widest">Attachment</th>
                    </tr></thead>
                    <tbody class="divide-y divide-gray-50">
                        @forelse($batch->homeworks as $i => $hw)
                        <tr class="hover:bg-gray-50/50 transition" x-show="show({{ $i }})">
                            <td class="px-4 py-2.5"><span class="text-sm font-bold text-gray-900">{{ $hw->title }}</span></td>
                            <td class="px-4 py-2.5"><span class="text-[11px] text-gray-500 font-medium max-w-xs truncate block">{{ $hw->description ?: '—' }}</span></td>
                            <td class="px-4 py-2.5">
                                @if($hw->due_date)
                                    @php $due = \Carbon\Carbon::parse($hw->due_date); @endphp
                                    <span class="text-[11px] font-bold {{ $due->isPast() ? 'text-red-500' : 'text-primary' }}">
                                        {{ $due->format('M d, Y') }}
                                    </span>
                                @else
                                    <span class="text-[11px] text-gray-300 font-bold">—</span>
                                @endif
                            </td>
                            <td class="px-4 py-2.5">
                                <span class="text-sm font-black text-gray-900">{{ $hw->submissions->count() }}</span>
                                <span class="text-[10px] text-gray-400 font-bold"> / {{ $stats['students_count'] }}</span>
                            </td>
                            <td class="px-4 py-2.5">
                                @if($hw->attachment)
                                    <a href="{{ $hw->attachment }}" target="_blank"
                                       class="text-[10px] font-black text-primary uppercase tracking-widest bg-orange-50 px-2 py-1 rounded-lg border border-orange-100 hover:bg-orange-100 transition">
                                        View File
                                    </a>
                                @else
                                    <span class="text-[11px] text-gray-300 font-bold">—</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="5" class="px-4 py-12 text-center text-gray-400 font-bold text-sm">No homework assigned to this batch.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <x-admin.tab-pagination :total="$stats['homework_count']" />
        </div>
    </div>

    {{-- Attendance Tab --}}
    <div x-show="activeTab === 'attendance'" style="display:none">
        @php
            $attendanceByDate = $batch->attendance->sortByDesc('date')->groupBy('date');
        @endphp
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="p-4 border-b border-gray-50 flex items-center justify-between">
                <h4 class="text-sm font-black text-gray-900 uppercase tracking-widest">Attendance Records</h4>
                <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">{{ $attendanceByDate->count() }} Days Recorded</span>
            </div>
            @if($attendanceByDate->isEmpty())
                <div class="px-4 py-12 text-center text-gray-400 font-bold text-sm">No attendance records found.</div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead class="bg-gray-50/50"><tr>
                            <th class="px-4 py-3 text-[10px] font-black text-gray-400 uppercase tracking-widest w-36">Date</th>
                            <th class="px-4 py-3 text-[10px] font-black text-gray-400 uppercase tracking-widest">Students</th>
                            <th class="px-4 py-3 text-[10px] font-black text-gray-400 uppercase tracking-widest w-32 text-right">Attendance</th>
                        </tr></thead>
                        <tbody class="divide-y divide-gray-50">
                            @foreach($attendanceByDate as $date => $records)
                            @php
                                $present = $records->where('status', 'present')->count();
                                $absent  = $records->where('status', 'absent')->count();
                                $total   = $records->count();
                                $pct     = $total > 0 ? round(($present / $total) * 100) : 0;
                            @endphp
                            <tr class="hover:bg-gray-50/30 transition align-middle">
                                <td class="px-4 py-2">
                                    <span class="text-xs font-black text-gray-900">{{ \Carbon\Carbon::parse($date)->format('D, M d') }}</span>
                                    <span class="text-[10px] text-gray-400 font-bold ml-1">{{ \Carbon\Carbon::parse($date)->format('Y') }}</span>
                                </td>
                                <td class="px-4 py-2">
                                    <div class="flex flex-wrap gap-1">
                                        @foreach($records as $rec)
                                        <span class="inline-flex items-center gap-1 text-[10px] font-bold px-2 py-0.5 rounded-md
                                            {{ $rec->status === 'present' ? 'bg-green-50 text-green-700' : 'bg-red-50 text-red-500' }}">
                                            <svg class="w-2.5 h-2.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                                @if($rec->status === 'present')
                                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                                @else
                                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                                @endif
                                            </svg>
                                            {{ $rec->student?->name ?? 'Unknown' }}
                                        </span>
                                        @endforeach
                                    </div>
                                </td>
                                <td class="px-4 py-2 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <div class="w-16 bg-gray-100 rounded-full h-1.5">
                                            <div class="bg-primary h-1.5 rounded-full" style="width: {{ $pct }}%"></div>
                                        </div>
                                        <span class="text-[10px] font-black text-primary w-7 text-right">{{ $pct }}%</span>
                                        <span class="text-[9px] font-black text-green-600 bg-green-50 px-1.5 py-0.5 rounded-full">{{ $present }}P</span>
                                        @if($absent > 0)
                                        <span class="text-[9px] font-black text-red-500 bg-red-50 px-1.5 py-0.5 rounded-full">{{ $absent }}A</span>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>


    {{-- Resources Tab --}}
    <div x-show="activeTab === 'resources'" style="display:none"
         x-data="{ page:1, perPage:10, total:{{ $stats['resources_count'] }}, get totalPages(){return Math.ceil(this.total/this.perPage);}, get from(){return (this.page-1)*this.perPage+1;}, get to(){return Math.min(this.page*this.perPage,this.total);}, show(i){return i>=(this.page-1)*this.perPage&&i<this.page*this.perPage;} }">
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="p-4 border-b border-gray-50 flex items-center justify-between">
                <h4 class="text-sm font-black text-gray-900 uppercase tracking-widest">Batch Resources</h4>
                <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">{{ $stats['resources_count'] }} Files</span>
            </div>
            @if($batch->resources->isEmpty())
                <div class="px-4 py-12 text-center text-gray-400 font-bold text-sm">No resources uploaded for this batch.</div>
            @else
            <div class="p-4 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-3">
                @foreach($batch->resources as $i => $res)
                <div class="group border border-gray-100 rounded-xl p-3.5 hover:shadow-md hover:shadow-primary/5 hover:border-orange-100 transition-all duration-200" x-show="show({{ $i }})">
                    <div class="flex items-start gap-3">
                        {{-- Icon --}}
                        <div class="w-10 h-10 rounded-xl flex-shrink-0 flex items-center justify-center
                            {{ $res->file_type === 'video' ? 'bg-purple-50' : ($res->file_type === 'image' ? 'bg-blue-50' : 'bg-orange-50') }}">
                            @if($res->file_type === 'video')
                                <svg class="w-5 h-5 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.723v6.554a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                            @elseif($res->file_type === 'image')
                                <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            @else
                                <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                            @endif
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-xs font-black text-gray-900 truncate">{{ $res->title }}</p>
                            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mt-0.5">{{ strtoupper($res->file_type ?? 'file') }}</p>
                            @if($res->file_size && is_numeric($res->file_size))
                                <p class="text-[10px] text-gray-400 font-medium mt-0.5">{{ round((float)$res->file_size / 1024, 1) }} KB</p>
                            @endif
                        </div>
                    </div>
                    @if($res->description)
                        <p class="text-[10px] text-gray-400 font-medium mt-2 line-clamp-2">{{ $res->description }}</p>
                    @endif
                    @if($res->file_url)
                        <a href="{{ $res->file_url }}" target="_blank"
                           class="mt-3 flex items-center justify-center gap-1.5 w-full text-[10px] font-black uppercase tracking-widest text-primary bg-orange-50 hover:bg-orange-100 border border-orange-100 py-1.5 rounded-lg transition-all">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                            Download
                        </a>
                    @endif
                </div>
                @endforeach
            </div>
            <x-admin.tab-pagination :total="$stats['resources_count']" />
            @endif
        </div>
    </div>

</div>
<style>.no-scrollbar::-webkit-scrollbar{display:none}.no-scrollbar{-ms-overflow-style:none;scrollbar-width:none}</style>
</x-admin-layout>
