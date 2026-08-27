@extends('layouts.institute')

@section('content')
<div class="space-y-3.5 pb-12" x-data="batchTimetableManager()" x-init="init()">
    <div id="toast-container" class="fixed top-24 right-8 z-[1000] space-y-4"></div>

    <!-- Breadcrumb & Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
        <div>
            <nav class="flex flex-wrap items-center pt-1 gap-y-1 gap-x-2 text-[10px] sm:text-[11px] font-bold text-slate-400 uppercase tracking-[0.1em] sm:tracking-[0.2em] mb-1">
                <a href="{{ route('institute.batches.index') }}" class="hover:text-blue-600 transition-colors">Batches</a>
                <svg class="w-2.5 h-2.5 sm:w-3 sm:h-3 text-slate-300 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M9 5l7 7-7 7" />
                </svg>
                <a href="{{ route('institute.batches.show', $id) }}" class="hover:text-primary transition-colors">Batch Details</a>
                <svg class="w-2.5 h-2.5 sm:w-3 sm:h-3 text-slate-300 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M9 5l7 7-7 7" />
                </svg>
                <span class="text-primary">TimeTable</span>
            </nav>

            <div class="flex items-center gap-2.5">
                <div class="h-9 w-9 rounded-xl bg-orange-500/10 text-primary flex items-center justify-center shadow-xs">
                    <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                </div>
                <div>
                    <h1 class="text-xl font-bold text-slate-800 tracking-tight">{{ $batch->name }} — Class TimeTable</h1>
                    <p class="text-[11px] text-slate-500 font-medium">Daily lecture cards & real-time active lecture tracking</p>
                </div>
            </div>
        </div>

        <div class="flex items-center gap-2">
            <!-- <a href="{{ route('institute.batches.show', $id) }}"
                class="px-3 py-1.5 bg-white border border-slate-200 hover:bg-slate-50 rounded-xl text-xs font-bold text-slate-600 transition flex items-center gap-1.5 shadow-xs">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
                <span>Back to Batch</span>
            </a> -->

            <!-- Add Schedule Slot Button -->
            <button type="button" @click="openCreateModal()"
                class="px-3.5 py-1.5 bg-primary hover:bg-primaryHover text-white rounded-xl font-bold text-xs shadow-sm hover:scale-[1.02] active:scale-95 transition flex items-center gap-1.5 cursor-pointer">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4" />
                </svg>
                <span>Add Class</span>
            </button>
        </div>
    </div>

   

    <!-- Small Compact Cards Grid -->
    <div>
        <template x-if="filteredSlots.length > 0">
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-2.5">
                <template x-for="(slot, idx) in filteredSlots" :key="slot.id">
                    <div class="bg-white rounded-xl border shadow-2xs hover:shadow-xs transition-all p-3 flex flex-col justify-between"
                        :class="slot.status === 'cancelled' ? 'border-rose-200 bg-rose-50/10 opacity-80 hover:border-rose-300' : (isLive(slot) ? 'border-emerald-300 bg-emerald-50/20 shadow-emerald-500/5' : 'border-slate-100 hover:border-orange-200')">
                        
                        <div>
                            <!-- Header: Day + Live/Cancelled Tag + Actions -->
                            <div class="flex items-center justify-between gap-1 mb-2">
                                <div class="flex items-center gap-1">
                                    <!-- Day Badge -->
                                    <span class="px-1.5 py-0.5 rounded text-[9px] font-black uppercase tracking-wider"
                                        :class="slot.status === 'cancelled' ? 'bg-rose-100 text-rose-700' : (isLive(slot) ? 'bg-emerald-600 text-white' : 'bg-slate-100 text-slate-600')"
                                        x-text="capitalize(slot.day_of_week).substring(0, 3)">
                                    </span>

                                    <!-- Cancelled Badge -->
                                    <template x-if="slot.status === 'cancelled'">
                                        <span class="inline-flex items-center gap-1 px-1.5 py-0.5 rounded text-[9px] font-black uppercase tracking-wider bg-rose-100 text-rose-700 border border-rose-200">
                                            <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>
                                            CANCELLED
                                        </span>
                                    </template>

                                    <!-- Live Pill when Active Now -->
                                    <template x-if="slot.status !== 'cancelled' && isLive(slot)">
                                        <span class="inline-flex items-center gap-1 px-1.5 py-0.5 rounded text-[9px] font-black uppercase tracking-wider bg-emerald-100 text-emerald-700 border border-emerald-200 animate-pulse">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-ping"></span>
                                            LIVE NOW
                                        </span>
                                    </template>
                                </div>

                                <!-- Actions -->
                                <div class="flex items-center gap-0.5">
                                    <button type="button" @click="openEditModal(slot)"
                                        class="h-6 w-6 rounded-md text-slate-400 hover:text-primary hover:bg-slate-100 flex items-center justify-center transition cursor-pointer"
                                        title="Edit">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    </button>
                                    <button type="button" @click="openDeleteModal(slot.id, slot.subject)"
                                        class="h-6 w-6 rounded-md text-slate-400 hover:text-rose-600 hover:bg-rose-50 flex items-center justify-center transition cursor-pointer"
                                        title="Delete">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </div>
                            </div>

                            <!-- Timing Pill -->
                            <div class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md text-[10.5px] font-bold mb-1.5"
                                :class="slot.status === 'cancelled' ? 'bg-rose-50 text-rose-800 border border-rose-100' : (isLive(slot) ? 'bg-emerald-100/70 text-emerald-900 border border-emerald-200' : 'bg-slate-50 text-slate-700 border border-slate-100')">
                                <svg class="w-3 h-3" :class="slot.status === 'cancelled' ? 'text-rose-500' : (isLive(slot) ? 'text-emerald-600' : 'text-primary')" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                <span x-text="(slot.formatted_start_time || slot.start_time) + ' - ' + (slot.formatted_end_time || slot.end_time)"></span>
                            </div>

                            <!-- Subject Title -->
                            <h4 class="text-xs font-bold leading-tight truncate mb-2"
                                :class="slot.status === 'cancelled' ? 'text-slate-500 line-through' : 'text-slate-900'"
                                :title="slot.subject"
                                x-text="slot.subject"></h4>
                        </div>

                        <!-- Card Footer: Faculty Info & Room -->
                        <div class="pt-2 border-t border-slate-100 space-y-1">
                            <!-- Faculty -->
                            <div class="flex items-center gap-1.5 truncate text-[11px] text-slate-700">
                                <svg class="w-3.5 h-3.5 text-slate-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                <span class="font-semibold truncate" x-text="slot.staff ? slot.staff.full_name : 'No Teacher'"></span>
                            </div>

                            <!-- Room Number -->
                            <template x-if="slot.room_no">
                                <div class="flex items-center gap-1 text-[10px] text-slate-400">
                                    <svg class="w-2.5 h-2.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                                    <span class="truncate">Room: <strong class="text-slate-600" x-text="slot.room_no"></strong></span>
                                </div>
                            </template>
                        </div>
                    </div>
                </template>
            </div>
        </template>

        <template x-if="filteredSlots.length === 0">
            <div class="bg-white rounded-xl border border-slate-100 shadow-xs p-8 text-center">
                <div class="h-10 w-10 rounded-xl bg-orange-50 text-primary mx-auto flex items-center justify-center mb-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                </div>
                <h3 class="text-xs font-bold text-slate-800">No Lectures Found</h3>
                <p class="text-[11px] text-slate-400 mt-0.5 max-w-sm mx-auto">No class schedules matching the selected filter.</p>
                <div class="mt-3">
                    <button type="button" @click="openCreateModal(selectedDay !== 'all' ? selectedDay : 'monday')"
                        class="px-3 py-1.5 bg-primary hover:bg-primaryHover text-white text-xs font-bold rounded-lg transition shadow-xs">
                        + Add Class Schedule
                    </button>
                </div>
            </div>
        </template>
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
            <div class="px-5 py-3.5 bg-gradient-to-r from-orange-500 to-amber-500 flex items-center justify-between text-white shrink-0">
                <h3 class="text-sm font-bold tracking-tight" x-text="isEdit ? 'Edit Class Schedule' : 'Add Class Schedule'"></h3>
                <button type="button" @click="closeModal()" class="h-6 w-6 rounded-full flex items-center justify-center hover:bg-white/10 text-white/80 hover:text-white transition cursor-pointer">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <!-- Modal Body -->
            <form @submit.prevent="submitForm()" class="p-4 space-y-3 overflow-y-auto custom-scrollbar">
                <!-- Batch Info (Locked to this batch) -->
                <div>
                    <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1">Batch</label>
                    <input type="text" value="{{ $batch->name }}" disabled
                        class="w-full px-3 py-2 bg-slate-100 border border-slate-200 rounded-lg text-xs font-bold text-slate-700 cursor-not-allowed">
                </div>

                <!-- Day of Week & Subject -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5">
                    <div>
                        <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1">Day of Week <span class="text-rose-500">*</span></label>
                        <select x-model="form.day_of_week" required
                            class="w-full px-2.5 py-1.5 bg-slate-50 border border-slate-200 rounded-lg text-xs font-semibold text-slate-700 focus:bg-white focus:border-primary focus:ring-1 focus:ring-primary transition">
                            @foreach($daysOfWeek as $k => $label)
                                <option value="{{ $k }}">{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1">Subject <span class="text-rose-500">*</span></label>
                        <input type="text" x-model="form.subject" required placeholder="e.g. Physics"
                            class="w-full px-2.5 py-1.5 bg-slate-50 border border-slate-200 rounded-lg text-xs font-semibold text-slate-700 focus:bg-white focus:border-primary focus:ring-1 focus:ring-primary transition placeholder:text-slate-300">
                    </div>
                </div>

                <!-- Teacher / Faculty -->
                <div>
                    <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1">Faculty / Teacher</label>
                    <select x-model="form.staff_id"
                        class="w-full px-2.5 py-1.5 bg-slate-50 border border-slate-200 rounded-lg text-xs font-semibold text-slate-700 focus:bg-white focus:border-primary focus:ring-1 focus:ring-primary transition">
                        <option value="">Select Faculty (Optional)</option>
                        @foreach($facultyList as $f)
                            <option value="{{ $f->id }}">{{ $f->full_name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Time Slots -->
                <div class="grid grid-cols-2 gap-2.5">
                    <div>
                        <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1">Start Time <span class="text-rose-500">*</span></label>
                        <input type="time" x-model="form.start_time" required
                            class="w-full px-2.5 py-1.5 bg-slate-50 border border-slate-200 rounded-lg text-xs font-semibold text-slate-700 focus:bg-white focus:border-primary focus:ring-1 focus:ring-primary transition">
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1">End Time <span class="text-rose-500">*</span></label>
                        <input type="time" x-model="form.end_time" required
                            class="w-full px-2.5 py-1.5 bg-slate-50 border border-slate-200 rounded-lg text-xs font-semibold text-slate-700 focus:bg-white focus:border-primary focus:ring-1 focus:ring-primary transition">
                    </div>
                </div>

                <!-- Status (Active / Cancelled) -->
                <div>
                    <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1">Status</label>
                    <div class="grid grid-cols-2 gap-2">
                        <label class="flex items-center justify-center gap-2 py-1.5 px-3 rounded-lg border text-xs font-bold cursor-pointer transition"
                            :class="form.status === 'active' ? 'bg-emerald-50 border-emerald-300 text-emerald-700' : 'bg-slate-50 border-slate-200 text-slate-500 hover:bg-slate-100'">
                            <input type="radio" x-model="form.status" value="active" class="sr-only">
                            <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                            <span>Active</span>
                        </label>

                        <label class="flex items-center justify-center gap-2 py-1.5 px-3 rounded-lg border text-xs font-bold cursor-pointer transition"
                            :class="form.status === 'cancelled' ? 'bg-rose-50 border-rose-300 text-rose-700' : 'bg-slate-50 border-slate-200 text-slate-500 hover:bg-slate-100'">
                            <input type="radio" x-model="form.status" value="cancelled" class="sr-only">
                            <span class="w-2 h-2 rounded-full bg-rose-500"></span>
                            <span>Cancelled</span>
                        </label>
                    </div>
                </div>

                <!-- Room No -->
                <div>
                    <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1">Room / Class No</label>
                    <input type="text" x-model="form.room_no" placeholder="e.g. Lab 2"
                        class="w-full px-2.5 py-1.5 bg-slate-50 border border-slate-200 rounded-lg text-xs font-semibold text-slate-700 focus:bg-white focus:border-primary focus:ring-1 focus:ring-primary transition placeholder:text-slate-300">
                </div>

                <!-- Submit & Cancel Buttons -->
                <div class="pt-2.5 border-t border-slate-100 flex items-center justify-end gap-2 shrink-0">
                    <button type="button" @click="closeModal()"
                        class="px-3.5 py-1.5 text-xs font-bold text-slate-500 hover:text-slate-700 hover:bg-slate-50 rounded-lg transition cursor-pointer">
                        Cancel
                    </button>
                    <button type="submit" :disabled="loading"
                        class="px-4 py-1.5 bg-primary hover:bg-primaryHover text-white rounded-lg text-xs font-bold shadow-xs hover:scale-[1.02] active:scale-95 transition disabled:opacity-50 cursor-pointer">
                        <span x-text="loading ? 'Saving...' : (isEdit ? 'Update Schedule' : 'Save Schedule')"></span>
                    </button>
                </div>
            </form>
        </div>
    </div>
    <!-- Delete Confirmation Modal Popup -->
    <div x-show="showDeleteModal"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-[120] overflow-y-auto"
         style="display: none;">
        <div class="fixed inset-0 bg-slate-900/40 backdrop-blur-xs transition-opacity" @click="closeDeleteModal()"></div>
        <div class="relative min-h-screen flex items-center justify-center p-4">
            <div class="relative w-full max-w-sm bg-white rounded-2xl shadow-2xl border border-slate-100 p-5 text-center overflow-hidden"
                 @click.away="closeDeleteModal()">
                
                <!-- Danger Trash Icon -->
                <div class="h-12 w-12 rounded-2xl bg-rose-50 text-rose-600 mx-auto flex items-center justify-center mb-3">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                </div>

                <h3 class="text-base font-bold text-slate-800 tracking-tight">Delete Lecture Schedule?</h3>
                <p class="text-xs text-slate-500 mt-1.5 leading-relaxed">
                    Are you sure you want to delete <strong class="text-slate-800" x-text="deleteSubject"></strong>? This action cannot be undone.
                </p>

                <div class="mt-5 flex items-center justify-center gap-2.5">
                    <button type="button" @click="closeDeleteModal()"
                        class="px-4 py-2 text-xs font-bold text-slate-500 hover:text-slate-700 hover:bg-slate-100 rounded-xl transition cursor-pointer">
                        Cancel
                    </button>
                    <button type="button" @click="executeDelete()" :disabled="deleteLoading"
                        class="px-4 py-2 bg-rose-600 hover:bg-rose-700 text-white rounded-xl text-xs font-bold shadow-md shadow-rose-600/20 hover:scale-[1.02] active:scale-95 transition disabled:opacity-50 flex items-center gap-1.5 cursor-pointer">
                        <span x-text="deleteLoading ? 'Deleting...' : 'Yes, Delete'"></span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

@php
    $allSlotsFlat = [];
    foreach($daysOfWeek as $dK => $dL) {
        $slots = $timetables->get($dK, collect());
        foreach($slots as $s) {
            $allSlotsFlat[] = $s;
        }
    }
@endphp

<script>
function batchTimetableManager() {
    return {
        showModal: false,
        isEdit: false,
        loading: false,
        editId: null,
        batchId: "{{ $id }}",
        searchInput: '',
        searchQuery: '',
        allSlots: @json($allSlotsFlat),
        nowDay: '',
        nowMinutes: 0,
        showDeleteModal: false,
        deleteId: null,
        deleteSubject: '',
        deleteLoading: false,

        executeSearch() {
            this.searchQuery = this.searchInput.trim();
        },

        form: {
            day_of_week: '{{ array_key_first($daysOfWeek) ?? "monday" }}',
            subject: '',
            staff_id: '',
            start_time: '09:00',
            end_time: '10:30',
            room_no: '',
            status: 'active'
        },

        init() {
            this.updateCurrentTime();
            // Automatically recheck live lectures every 10 seconds
            setInterval(() => {
                this.updateCurrentTime();
            }, 10000);
        },

        updateCurrentTime() {
            const now = new Date();
            const days = ['sunday', 'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday'];
            this.nowDay = days[now.getDay()];
            this.nowMinutes = now.getHours() * 60 + now.getMinutes();
        },

        isLive(slot) {
            if (!slot || !slot.day_of_week || !slot.start_time || !slot.end_time) return false;
            if (slot.day_of_week.toLowerCase() !== this.nowDay) return false;

            const parseToMin = (t) => {
                const parts = t.split(':');
                return parseInt(parts[0], 10) * 60 + parseInt(parts[1], 10);
            };

            const startM = parseToMin(slot.start_time);
            const endM = parseToMin(slot.end_time);

            return this.nowMinutes >= startM && this.nowMinutes <= endM;
        },

        get hasLiveSlot() {
            return this.allSlots.some(s => this.isLive(s));
        },

        get filteredSlots() {
            let list = this.allSlots;
            if (this.searchQuery.trim() !== '') {
                const q = this.searchQuery.toLowerCase();
                list = list.filter(s => {
                    const sub = (s.subject || '').toLowerCase();
                    const staffName = (s.staff && s.staff.full_name ? s.staff.full_name : '').toLowerCase();
                    const room = (s.room_no || '').toLowerCase();
                    const day = (s.day_of_week || '').toLowerCase();
                    return sub.includes(q) || staffName.includes(q) || room.includes(q) || day.includes(q);
                });
            }
            return list;
        },

        capitalize(str) {
            if (!str) return '';
            return str.charAt(0).toUpperCase() + str.slice(1);
        },

        openCreateModal(day = null) {
            this.isEdit = false;
            this.editId = null;
            this.form = {
                day_of_week: day || '{{ array_key_first($daysOfWeek) ?? "monday" }}',
                subject: '',
                staff_id: '',
                start_time: '09:00',
                end_time: '10:30',
                room_no: '',
                status: 'active'
            };
            this.showModal = true;
        },

        openEditModal(slot) {
            this.isEdit = true;
            this.editId = slot.id;
            this.form = {
                day_of_week: slot.day_of_week || 'monday',
                subject: slot.subject || '',
                staff_id: slot.staff_id ? String(slot.staff_id) : '',
                start_time: (slot.start_time || '').substring(0, 5),
                end_time: (slot.end_time || '').substring(0, 5),
                room_no: slot.room_no || '',
                status: slot.status || 'active'
            };
            this.showModal = true;
        },

        closeModal() {
            this.showModal = false;
        },

        openDeleteModal(id, subject) {
            this.deleteId = id;
            this.deleteSubject = subject || 'this lecture';
            this.showDeleteModal = true;
        },

        closeDeleteModal() {
            this.showDeleteModal = false;
            this.deleteId = null;
            this.deleteSubject = '';
        },

        async executeDelete() {
            if (!this.deleteId) return;
            this.deleteLoading = true;
            try {
                const res = await fetch(`/institute/timetable/${this.deleteId}`, {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                });

                const data = await res.json();
                if (res.ok) {
                    window.location.reload();
                } else {
                    alert(data.message || 'Failed to delete schedule.');
                }
            } catch (err) {
                console.error(err);
                alert('Something went wrong. Please try again.');
            } finally {
                this.deleteLoading = false;
            }
        },

        async submitForm() {
            this.loading = true;
            const payload = {
                batch_id: this.batchId,
                day_of_week: this.form.day_of_week,
                subject: this.form.subject,
                staff_id: this.form.staff_id || null,
                start_time: this.form.start_time,
                end_time: this.form.end_time,
                room_no: this.form.room_no || null,
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
