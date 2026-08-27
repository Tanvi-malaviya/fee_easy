@extends('layouts.institute')

@section('content')
<div class="space-y-4 pb-8" x-data="timetableManager()">
    <!-- Top Action & Title Bar -->
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-4 sm:p-5 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
        <div class="flex items-center gap-3">
            <div class="h-10 w-10 rounded-xl bg-orange-50 text-primary flex items-center justify-center font-bold">
                <!-- Excel / Timetable Matrix Icon -->
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M3 14h18M3 18h18M9 4v16M15 4v16M4 4h16a1 1 0 011 1v14a1 1 0 01-1 1H4a1 1 0 01-1-1V5a1 1 0 011-1z" />
                </svg>
            </div>
            <div>
                <h1 class="text-lg sm:text-xl font-bold text-slate-800 tracking-tight"> Timetable </h1>
                <p class="text-xs text-slate-400 font-medium">Academic schedule sheet across all days & batches</p>
            </div>
        </div>

        <div class="flex items-center gap-2.5">
            <!-- Add Class Schedule Button -->
            <button type="button" @click="openCreateModal()"
                class="px-4 py-2 bg-primary hover:bg-primaryHover text-white rounded-xl font-bold text-xs shadow-md shadow-primary/20 hover:scale-[1.02] active:scale-95 transition flex items-center gap-1.5 cursor-pointer shrink-0">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4" />
                </svg>
                <span>Add Class Schedule</span>
            </button>
        </div>
    </div>

    <!-- Excel-Type Timetable Sheet / Table Grid with Horizontal & Vertical Grid Lines -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto custom-scrollbar">
            @php
                $maxSlots = 1;
                foreach(['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'] as $dKey) {
                    $count = $allWeeklySlots->get($dKey, collect())->count();
                    if ($count > $maxSlots) {
                        $maxSlots = $count;
                    }
                }
            @endphp

            <table class="w-full border-collapse min-w-[980px] table-fixed">
                <!-- Table Header: Days of the Week (Excel Grid Columns) -->
                <thead>
                    <tr class="border-b border-slate-200">
                        @foreach(['monday' => 'Monday', 'tuesday' => 'Tuesday', 'wednesday' => 'Wednesday', 'thursday' => 'Thursday', 'friday' => 'Friday', 'saturday' => 'Saturday', 'sunday' => 'Sunday'] as $dayKey => $dayLabel)
                            @php
                                $isToday = ($dayKey === $today);
                            @endphp
                            <th class="py-2 px-2 text-center border-r border-slate-200 last:border-r-0 {{ $isToday ? 'bg-primary text-white font-extrabold shadow-inner' : 'bg-slate-100/90 text-slate-700 font-bold' }} text-[11px] uppercase tracking-wider select-none">
                                <div class="flex items-center justify-center gap-1">
                                    <span>{{ $dayLabel }}</span>
                                    @if($isToday)
                                        <span class="w-1.5 h-1.5 rounded-full bg-white animate-pulse"></span>
                                    @endif
                                </div>
                            </th>
                        @endforeach
                    </tr>
                </thead>

                <!-- Table Body: Timetable Rows with Horizontal Grid Lines -->
                <tbody class="divide-y divide-slate-200">
                    @for($rowIndex = 0; $rowIndex < $maxSlots; $rowIndex++)
                        <tr class="border-b border-slate-200 last:border-b-0 hover:bg-slate-50/30 transition">
                            @foreach(['monday' => 'Monday', 'tuesday' => 'Tuesday', 'wednesday' => 'Wednesday', 'thursday' => 'Thursday', 'friday' => 'Friday', 'saturday' => 'Saturday', 'sunday' => 'Sunday'] as $dayKey => $dayLabel)
                                @php
                                    $daySlots = $allWeeklySlots->get($dayKey, collect());
                                    $slot = $daySlots->values()->get($rowIndex);
                                    $isToday = ($dayKey === $today);
                                @endphp
                                <td class="p-2 align-top border-r border-slate-200 last:border-r-0 transition-colors {{ $isToday ? 'bg-orange-50/20' : 'bg-white hover:bg-slate-50/70' }}">
                                    @if($slot)
                                        <a href="{{ $slot->batch_id ? route('institute.batches.timetable', $slot->batch_id) : '#' }}"
                                            class="block h-full flex flex-col justify-between group text-left cursor-pointer"
                                            title="View {{ $slot->batch ? $slot->batch->name : '' }} Timetable">
                                            
                                            <div class="space-y-1">
                                                <!-- Time Badge & Cancelled Tag -->
                                                <div class="flex items-center justify-between gap-1 mb-0.5">
                                                    <div class="inline-flex items-center gap-1 px-1.5 py-0.5 rounded text-[9px] font-bold transition {{ $slot->status === 'cancelled' ? 'bg-rose-100 text-rose-700' : 'bg-orange-50 border border-orange-100 text-primary group-hover:bg-primary group-hover:text-white group-hover:border-primary' }}">
                                                        <svg class="w-2.5 h-2.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                                        <span>{{ $slot->formatted_start_time }} - {{ $slot->formatted_end_time }}</span>
                                                    </div>

                                                    @if($slot->status === 'cancelled')
                                                        <span class="px-1 py-0.2 rounded text-[7.5px] font-black uppercase tracking-wider bg-rose-100 text-rose-700 border border-rose-200 shrink-0">
                                                            Cancelled
                                                        </span>
                                                    @endif
                                                </div>

                                                <!-- Subject Title -->
                                                <h4 class="text-xs font-bold leading-tight truncate transition {{ $slot->status === 'cancelled' ? 'text-slate-400 line-through' : 'text-slate-900 group-hover:text-primary' }}" title="{{ $slot->subject }}">
                                                    {{ $slot->subject }}
                                                </h4>

                                                <!-- Batch Name -->
                                                <div class="flex items-center gap-1 text-[10px] text-slate-500 truncate">
                                                    <span class="px-1.5 py-0.5 rounded bg-slate-100 font-semibold text-slate-700 truncate max-w-full">
                                                        {{ $slot->batch ? $slot->batch->name : 'N/A' }}
                                                    </span>
                                                </div>
                                            </div>

                                            <!-- Teacher / Faculty Info -->
                                            <div class="mt-1.5 pt-1 border-t border-slate-100 flex items-center gap-1 text-[9.5px] text-slate-500 font-medium truncate">
                                                <svg class="w-2.5 h-2.5 shrink-0 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                                <span class="truncate">{{ $slot->staff ? $slot->staff->full_name : 'Unassigned' }}</span>
                                            </div>
                                        </a>
                                    @else
                                        <div class="h-full min-h-[48px] flex items-center justify-center text-slate-300 text-xs font-bold select-none">
                                            —
                                        </div>
                                    @endif
                                </td>
                            @endforeach
                        </tr>
                    @endfor
                </tbody>
            </table>
        </div>
    </div>

    <!-- Create / Edit Class Schedule Modal -->
    <div x-show="showModal"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-[100] flex items-center justify-center p-4"
         style="display: none;">
        <div class="fixed inset-0 bg-slate-900/40 backdrop-blur-xs transition-opacity" @click="closeModal()"></div>
        <div class="relative w-full max-w-[480px] bg-white rounded-2xl shadow-2xl border border-slate-100 overflow-hidden flex flex-col max-h-[92vh]"
             @click.away="closeModal()">
            <!-- Modal Header -->
            <div class="px-5 py-4 bg-gradient-to-r from-orange-500 to-amber-500 flex items-center justify-between text-white shrink-0">
                <h3 class="text-base font-bold tracking-tight" x-text="isEdit ? 'Edit Class Schedule' : 'Add Class Schedule'"></h3>
                <button type="button" @click="closeModal()" class="h-7 w-7 rounded-full flex items-center justify-center hover:bg-white/10 text-white/80 hover:text-white transition cursor-pointer">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <!-- Modal Body -->
            <form @submit.prevent="submitForm()" class="p-5 space-y-3.5 overflow-y-auto custom-scrollbar">
                <!-- Batch Selection -->
                <div>
                    <label class="block text-[11px] font-bold text-slate-600 uppercase tracking-wider mb-1">Batch / Class <span class="text-rose-500">*</span></label>
                    <select x-model="form.batch_id" @change="onBatchChange()" required
                        class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold text-slate-700 focus:bg-white focus:border-primary focus:ring-1 focus:ring-primary transition">
                        <option value="">Select Batch</option>
                        @foreach($batches as $b)
                            <option value="{{ $b->id }}">{{ $b->name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Day of Week & Subject -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label class="block text-[11px] font-bold text-slate-600 uppercase tracking-wider mb-1">Day of Week <span class="text-rose-500">*</span></label>
                        <select x-model="form.day_of_week" required
                            class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold text-slate-700 focus:bg-white focus:border-primary focus:ring-1 focus:ring-primary transition">
                            <template x-for="d in availableDays" :key="d.key">
                                <option :value="d.key" x-text="d.label" :selected="form.day_of_week === d.key"></option>
                            </template>
                        </select>
                    </div>

                    <div>
                        <label class="block text-[11px] font-bold text-slate-600 uppercase tracking-wider mb-1">Subject <span class="text-rose-500">*</span></label>
                        <input type="text" x-model="form.subject" required placeholder="e.g. Mathematics"
                            class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold text-slate-700 focus:bg-white focus:border-primary focus:ring-1 focus:ring-primary transition placeholder:text-slate-300">
                    </div>
                </div>

                <!-- Teacher / Faculty -->
                <div>
                    <label class="block text-[11px] font-bold text-slate-600 uppercase tracking-wider mb-1">Faculty / Teacher</label>
                    <select x-model="form.staff_id"
                        class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold text-slate-700 focus:bg-white focus:border-primary focus:ring-1 focus:ring-primary transition">
                        <option value="">Select Faculty (Optional)</option>
                        @foreach($facultyList as $f)
                            <option value="{{ $f->id }}">{{ $f->full_name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Time Slots -->
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-[11px] font-bold text-slate-600 uppercase tracking-wider mb-1">Start Time <span class="text-rose-500">*</span></label>
                        <input type="time" x-model="form.start_time" required
                            class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold text-slate-700 focus:bg-white focus:border-primary focus:ring-1 focus:ring-primary transition">
                    </div>
                    <div>
                        <label class="block text-[11px] font-bold text-slate-600 uppercase tracking-wider mb-1">End Time <span class="text-rose-500">*</span></label>
                        <input type="time" x-model="form.end_time" required
                            class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold text-slate-700 focus:bg-white focus:border-primary focus:ring-1 focus:ring-primary transition">
                    </div>
                </div>

                <!-- Status (Active / Cancelled) -->
                <div>
                    <label class="block text-[11px] font-bold text-slate-600 uppercase tracking-wider mb-1">Status</label>
                    <div class="grid grid-cols-2 gap-2">
                        <label class="flex items-center justify-center gap-2 py-2 px-3 rounded-xl border text-xs font-bold cursor-pointer transition"
                            :class="form.status === 'active' ? 'bg-emerald-50 border-emerald-300 text-emerald-700' : 'bg-slate-50 border-slate-200 text-slate-500 hover:bg-slate-100'">
                            <input type="radio" x-model="form.status" value="active" class="sr-only">
                            <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                            <span>Active</span>
                        </label>

                        <label class="flex items-center justify-center gap-2 py-2 px-3 rounded-xl border text-xs font-bold cursor-pointer transition"
                            :class="form.status === 'cancelled' ? 'bg-rose-50 border-rose-300 text-rose-700' : 'bg-slate-50 border-slate-200 text-slate-500 hover:bg-slate-100'">
                            <input type="radio" x-model="form.status" value="cancelled" class="sr-only">
                            <span class="w-2 h-2 rounded-full bg-rose-500"></span>
                            <span>Cancelled</span>
                        </label>
                    </div>
                </div>

                <!-- Submit & Cancel Buttons -->
                <div class="pt-3 border-t border-slate-100 flex items-center justify-end gap-2.5 shrink-0">
                    <button type="button" @click="closeModal()"
                        class="px-4 py-2 text-xs font-bold text-slate-500 hover:text-slate-700 hover:bg-slate-50 rounded-xl transition cursor-pointer">
                        Cancel
                    </button>
                    <button type="submit" :disabled="loading"
                        class="px-5 py-2 bg-primary hover:bg-primaryHover text-white rounded-xl text-xs font-bold shadow-md shadow-primary/20 hover:scale-[1.02] active:scale-95 transition disabled:opacity-50 cursor-pointer">
                        <span x-text="loading ? 'Saving...' : (isEdit ? 'Update Schedule' : 'Save Schedule')"></span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@php
    $batchesData = $batches->map(function($b) {
        return [
            'id' => (string) $b->id,
            'name' => $b->name,
            'days' => is_array($b->days) ? $b->days : []
        ];
    });
@endphp

<script>
function timetableManager() {
    return {
        showModal: false,
        isEdit: false,
        loading: false,
        editId: null,
        batchesData: @json($batchesData),
        form: {
            batch_id: '{{ $filterBatchId }}' || '',
            day_of_week: 'monday',
            subject: '',
            staff_id: '{{ $filterStaffId }}' || '',
            start_time: '09:00',
            end_time: '10:30',
            status: 'active'
        },

        get availableDays() {
            const dayMap = {
                'mon': { key: 'monday', label: 'Monday' },
                'monday': { key: 'monday', label: 'Monday' },
                'tue': { key: 'tuesday', label: 'Tuesday' },
                'tuesday': { key: 'tuesday', label: 'Tuesday' },
                'wed': { key: 'wednesday', label: 'Wednesday' },
                'wednesday': { key: 'wednesday', label: 'Wednesday' },
                'thu': { key: 'thursday', label: 'Thursday' },
                'thursday': { key: 'thursday', label: 'Thursday' },
                'fri': { key: 'friday', label: 'Friday' },
                'friday': { key: 'friday', label: 'Friday' },
                'sat': { key: 'saturday', label: 'Saturday' },
                'saturday': { key: 'saturday', label: 'Saturday' },
                'sun': { key: 'sunday', label: 'Sunday' },
                'sunday': { key: 'sunday', label: 'Sunday' }
            };

            if (this.form.batch_id) {
                const batch = this.batchesData.find(b => String(b.id) === String(this.form.batch_id));
                if (batch && Array.isArray(batch.days) && batch.days.length > 0) {
                    const result = [];
                    batch.days.forEach(d => {
                        const clean = String(d).toLowerCase().trim();
                        if (dayMap[clean]) {
                            result.push(dayMap[clean]);
                        }
                    });
                    if (result.length > 0) {
                        return result;
                    }
                }
            }

            return [
                { key: 'monday', label: 'Monday' },
                { key: 'tuesday', label: 'Tuesday' },
                { key: 'wednesday', label: 'Wednesday' },
                { key: 'thursday', label: 'Thursday' },
                { key: 'friday', label: 'Friday' },
                { key: 'saturday', label: 'Saturday' },
                { key: 'sunday', label: 'Sunday' }
            ];
        },

        onBatchChange() {
            const days = this.availableDays;
            if (days.length > 0) {
                const exists = days.some(d => d.key === this.form.day_of_week);
                if (!exists) {
                    this.form.day_of_week = days[0].key;
                }
            }
        },

        openCreateModal(day = null) {
            this.isEdit = false;
            this.editId = null;
            this.form = {
                batch_id: '{{ $filterBatchId }}' || '',
                day_of_week: day || 'monday',
                subject: '',
                staff_id: '{{ $filterStaffId }}' || '',
                start_time: '09:00',
                end_time: '10:30',
                status: 'active'
            };
            this.onBatchChange();
            this.showModal = true;
        },

        openEditModal(slot) {
            this.isEdit = true;
            this.editId = slot.id;
            this.form = {
                batch_id: slot.batch_id ? String(slot.batch_id) : '',
                day_of_week: slot.day_of_week || 'monday',
                subject: slot.subject || '',
                staff_id: slot.staff_id ? String(slot.staff_id) : '',
                start_time: (slot.start_time || '').substring(0, 5),
                end_time: (slot.end_time || '').substring(0, 5),
                status: slot.status || 'active'
            };
            this.showModal = true;
        },

        closeModal() {
            this.showModal = false;
        },

        async submitForm() {
            this.loading = true;
            const payload = {
                batch_id: this.form.batch_id,
                day_of_week: this.form.day_of_week,
                subject: this.form.subject,
                staff_id: this.form.staff_id || null,
                start_time: this.form.start_time,
                end_time: this.form.end_time,
                status: this.form.status || 'active',
                _token: '{{ csrf_token() }}'
            };

            const url = this.isEdit 
                ? `/institute/timetable/${this.editId}` 
                : `/institute/timetable`;
            const method = this.isEdit ? 'PUT' : 'POST';

            try {
                const res = await fetch(url, {
                    method: method,
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify(payload)
                });

                const data = await res.json();
                if (res.ok) {
                    window.location.reload();
                } else {
                    let errMsg = data.message || 'Error saving class schedule';
                    if (data.errors) {
                        const errs = Object.values(data.errors).flat();
                        if (errs.length > 0) errMsg = errs[0];
                    }
                    alert(errMsg);
                }
            } catch (err) {
                console.error(err);
                alert('Something went wrong. Please try again.');
            } finally {
                this.loading = false;
            }
        }
    };
}
</script>
@endsection
