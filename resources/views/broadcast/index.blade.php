<x-admin-layout title="Broadcast Center">
    <div class=" min-h-screen bg-[#f8fafc]" 
        x-data="{ 
            search: '{{ request('search') }}', 
            loading: false,
            fetchResults() {
                this.loading = true;
                const url = new URL(window.location.href);
                url.searchParams.set('search', this.search);
                
                fetch(url.toString(), {
                    headers: { 'X-Requested-With': 'XMLHttpRequest' }
                })
                .then(res => res.text())
                .then(html => {
                    document.getElementById('broadcast-rows').innerHTML = html;
                    window.history.replaceState({}, '', url.toString());
                })
                .finally(() => { this.loading = false; });
            }
        }">
        <div class="max-w-7xl mx-auto">
            <!-- Toast Notification Pop-up -->
            @if(session('success'))
                <div x-data="{ show: true }" 
                     x-init="setTimeout(() => show = false, 4000)"
                     x-show="show" 
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0 transform translate-y-[-20px]"
                     x-transition:enter-end="opacity-100 transform translate-y-0"
                     x-transition:leave="transition ease-in duration-300"
                     x-transition:leave-start="opacity-100 transform translate-y-0"
                     x-transition:leave-end="opacity-0 transform translate-y-[-20px]"
                     class="fixed top-6 right-6 z-[9999]">
                    <div class="bg-emerald-600 text-white px-6 py-4 rounded-2xl shadow-2xl flex items-center gap-4 min-w-[300px]">
                        <div class="bg-white/20 p-2 rounded-xl">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-xs font-black uppercase tracking-widest opacity-80">Success</p>
                            <p class="text-sm font-bold">{{ session('success') }}</p>
                        </div>
                        <button @click="show = false" class="ml-auto text-white/50 hover:text-white transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </button>
                    </div>
                </div>
            @endif

            <!-- Header Stats -->
            <!-- <div class="flex items-center gap-3 mb-4 ml-1">
                <h2 class="text-xl font-black text-gray-900 tracking-tight">Broadcast Center</h2>
                <span class="w-1 h-1 rounded-full bg-gray-300"></span>
                <p class="text-[10px] font-black text-indigo-600 uppercase tracking-widest">{{ $totalInstitutes }} Total Institutes</p>
            </div> -->

            <!-- Filters & Search -->
            <div class="mb-3">
                <form action="{{ route('broadcast.index') }}" method="GET" class="flex flex-col md:flex-row gap-4" @submit.prevent="fetchResults()">
                    <div class="flex-1 relative group">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                            <svg class="h-4 w-4 text-gray-400 group-focus-within:text-indigo-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </div>
                        <input type="text" x-model="search" placeholder="Search through recent broadcasts..." 
                            class="block w-full pl-10 pr-24 py-3 text-sm font-medium text-gray-900 bg-white border border-gray-100 rounded-2xl focus:bg-white focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all duration-300 placeholder:text-gray-400">
                        
                        <div class="absolute inset-y-1.5 right-1.5 flex items-center gap-2">
                            <button x-show="search.length > 0" type="button" 
                                class="p-1.5 text-gray-400 hover:text-red-500 transition-colors" title="Clear">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                            </button>
                            <button type="submit" 
                                class="inline-flex items-center px-4 py-1.5 bg-indigo-600 text-white rounded-xl font-medium text-[10px] uppercase tracking-widest hover:bg-indigo-700 transition shadow-lg shadow-indigo-600/20 active:scale-95 no-loader">
                                Search
                            </button>
                        </div>
                    </div>

                    <div class="flex items-center">
                        <button type="button" @click="$dispatch('open-modal', 'compose-broadcast')"
                            class="inline-flex items-center px-8 py-3 bg-indigo-600 border border-transparent rounded-xl shadow-lg text-xs font-bold text-white uppercase tracking-widest text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none transition transform active:scale-95 shadow-indigo-600/20 whitespace-nowrap">
                        <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                        ADD MESSAGE
                        </button>
                    </div>
                </form>
            </div>

            <!-- History Table -->
            <div class="relative bg-white border border-gray-100 rounded-2xl shadow-sm overflow-hidden mb-8">
                <!-- Table Loading Overlay -->
                <div x-show="loading" class="absolute inset-0 bg-white/70 backdrop-blur-[2px] z-50 flex items-center justify-center transition-all duration-300" style="display: none;">
                    <div class="flex flex-col items-center gap-3">
                        <div class="relative">
                            <div class="w-12 h-12 rounded-full border-4 border-indigo-50"></div>
                            <div class="absolute inset-0 w-12 h-12 rounded-full border-4 border-indigo-600 border-t-transparent animate-spin"></div>
                        </div>
                        <span class="text-[10px] font-black text-indigo-600 uppercase tracking-[0.2em] animate-pulse">Loading...</span>
                    </div>
                </div>
               
                <div class="overflow-x-auto">
                    <table class="w-full text-left divide-y divide-gray-100">
                        <thead>
                            <tr class="bg-gray-50/50">
                                <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Sent Date</th>
                                <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Announcement Details</th>
                                <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider text-center">Audience</th>
                                <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider text-center">Media</th>
                                <th class="px-6 py-4 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 bg-white transition-opacity duration-300" id="broadcast-rows" :class="loading ? 'opacity-50' : 'opacity-100'">
                            @include('broadcast.table_rows')
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Compose Modal -->
            <x-modal name="compose-broadcast" :show="$errors->any()" focusable>
                <div class="p-5 sm:p-6">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-8 h-8 rounded-lg bg-indigo-50 flex items-center justify-center">
                            <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z">
                                </path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-base font-black text-gray-900 leading-tight">Compose Dispatch</h3>
                            <p class="text-[9px] font-bold text-gray-400 uppercase tracking-widest mt-0.5">Quick message</p>
                        </div>
                    </div>

                    <form action="{{ route('broadcast.send') }}" method="POST" enctype="multipart/form-data" class="space-y-3">
                        @csrf
                        <input type="hidden" name="channels[]" value="dashboard">

                        @if($errors->any())
                            <div class="p-2 bg-red-50 border border-red-110 rounded-lg">
                                <ul class="list-disc list-inside text-xs font-bold text-red-600 space-y-1">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <div>
                            <label for="title" class="block text-[9px] font-black text-gray-400 uppercase tracking-widest mb-1 ml-1">Title</label>
                            <input type="text" id="title" name="title" value="{{ old('title') }}" required
                                class="block w-full px-4 py-2.5 text-sm font-bold text-gray-900 bg-gray-50 border-transparent rounded-xl focus:bg-white focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all duration-300 placeholder:text-gray-300"
                                placeholder="e.g. Scheduled System Upgrade">
                        </div>

                        <div>
                            <label for="message" class="block text-[9px] font-black text-gray-400 uppercase tracking-widest mb-1 ml-1">Message</label>
                            <textarea name="message" id="message" rows="3" required
                                class="block w-full px-4 py-2.5 text-sm font-medium text-gray-700 bg-gray-50 border-transparent rounded-xl focus:bg-white focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all duration-300 placeholder:text-gray-300 leading-relaxed"
                                placeholder="Type your announcement here...">{{ old('message') }}</textarea>
                        </div>

                        <div x-data="{ imagePreview: null }">
                            <label class="block text-[9px] font-black text-gray-400 uppercase tracking-widest mb-1 ml-1">Media (Optional)</label>
                            <div class="relative group">
                                <input type="file" name="image" id="image" accept="image/*" class="hidden"
                                    @change="const file = $event.target.files[0]; if (file) { const reader = new FileReader(); reader.onload = (e) => { imagePreview = e.target.result; }; reader.readAsDataURL(file); }">
                                <label for="image" 
                                    class="flex items-center gap-3 px-4 py-3 bg-gray-50 border-2 border-dashed border-gray-100 rounded-xl cursor-pointer hover:bg-indigo-50 hover:border-indigo-200 transition-all duration-300 group">
                                    <template x-if="!imagePreview">
                                        <div class="flex items-center gap-3">
                                            <div class="w-7 h-7 bg-white rounded-lg flex items-center justify-center shadow-sm group-hover:scale-110 transition-transform">
                                                <svg class="w-4 h-4 text-gray-400 group-hover:text-indigo-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                </svg>
                                            </div>
                                            <p class="text-[9px] font-black text-gray-500 uppercase">Click to attach photo</p>
                                        </div>
                                    </template>
                                    <template x-if="imagePreview">
                                        <div class="relative flex items-center gap-3">
                                            <img :src="imagePreview" class="w-10 h-10 object-cover rounded-lg shadow-sm">
                                            <p class="text-[9px] font-black text-indigo-600 uppercase">Photo Selected</p>
                                            <button type="button" @click.prevent="imagePreview = null; document.getElementById('image').value = ''" 
                                                class="ml-2 bg-red-50 text-red-600 rounded-lg p-1 hover:bg-red-100 transition">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"></path></svg>
                                            </button>
                                        </div>
                                    </template>
                                </label>
                            </div>
                        </div>

                        <div x-data="{ target: '{{ old('target', 'all') }}' }">
                            <input type="hidden" name="target" :value="target">
                            <label class="block text-[9px] font-black text-gray-400 uppercase tracking-widest mb-2 ml-1">Target Audience</label>
                            <div class="grid grid-cols-2 gap-3">
                                <div @click="target = 'all'"
                                    :class="target === 'all' ? 'border-indigo-600 bg-indigo-50/50 ring-4 ring-indigo-500/5' : 'border-gray-100 bg-white hover:border-indigo-100'"
                                    class="cursor-pointer p-2.5 rounded-xl border-2 transition-all duration-300 group">
                                    <div class="flex items-center gap-2">
                                        <div :class="target === 'all' ? 'bg-indigo-600 text-white' : 'bg-gray-100 text-gray-400 group-hover:bg-indigo-100 group-hover:text-indigo-600'"
                                            class="w-6 h-6 rounded-lg flex items-center justify-center transition-colors">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                            </svg>
                                        </div>
                                        <h4 class="text-[10px] font-black text-gray-900 leading-none">All ({{ $totalInstitutes }})</h4>
                                    </div>
                                </div>
                                <div @click="target = 'subscribed'"
                                    :class="target === 'subscribed' ? 'border-indigo-600 bg-indigo-50/50 ring-4 ring-indigo-500/5' : 'border-gray-100 bg-white hover:border-indigo-100'"
                                    class="cursor-pointer p-2.5 rounded-xl border-2 transition-all duration-300 group">
                                    <div class="flex items-center gap-2">
                                        <div :class="target === 'subscribed' ? 'bg-indigo-600 text-white' : 'bg-gray-100 text-gray-400 group-hover:bg-indigo-100 group-hover:text-indigo-600'"
                                            class="w-6 h-6 rounded-lg flex items-center justify-center transition-colors">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138z"></path>
                                            </svg>
                                        </div>
                                        <h4 class="text-[10px] font-black text-gray-900 leading-none">Subscribed ({{ $subscribedInstitutes }})</h4>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center justify-end gap-3 pt-1">
                            <button type="button" @click="$dispatch('close')" class="px-3 py-1.5 text-[9px] font-black text-gray-400 uppercase tracking-widest hover:text-gray-600 transition">Cancel</button>
                            <button type="submit"
                                class="inline-flex items-center gap-2 px-5 py-2.5 bg-indigo-600 text-white rounded-lg font-black text-[9px] uppercase tracking-widest hover:bg-indigo-700 transition shadow-lg shadow-indigo-600/20 active:scale-95">
                                <span>Dispatch Now</span>
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                            </button>
                        </div>
                    </form>
                </div>
            </x-modal>
        </div>
    </div>
</x-admin-layout>