<x-admin-layout title="Create New Institute">
    <!-- 🔥 Fix dropdown UI -->
    <style>
        .ss-content {
            z-index: 9999 !important;
            display: flex !important;
            flex-direction: column !important;
        }
        .ss-search {
            order: -1 !important; /* Force search to the top */
            padding: 8px 10px !important;
        }
        .ss-list {
            max-height: 200px !important;
        }
        .ss-main {
            border-radius: 0.75rem !important;
            border: 1px solid #d1d5db !important;
            padding: 4px 6px !important;
            background-color: #f9fafb !important;
        }
        .ss-option {
            padding: 6px 10px !important;
            font-size: 13px !important;
        }
    </style>

    <div class="max-w-5xl mx-auto">
        <div class="flex items-center gap-4 mb-2">
            <a href="{{ route('institutes.index') }}" onclick="showBtnLoader(this)" class="relative inline-flex items-center justify-center gap-2 px-4 py-2 bg-indigo-600 text-white rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-indigo-700 transition active:scale-95 shadow-lg shadow-indigo-100 min-w-[120px]">
                <span class="flex items-center btn-content">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    Back to List
                </span>
                <span class="hidden btn-loader">
                    <svg class="animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                </span>
            </a>
        </div>

        <form action="{{ route('institutes.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-visible">
                <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Institute Name -->
                    <div>
                        <label for="institute_name" class="block text-sm font-medium text-gray-700">Institute Name <span class="text-red-500">*</span></label>
                        <input type="text" name="institute_name" id="institute_name" value="{{ old('institute_name') }}" required 
                            oninput="clearError(this)"
                            class="mt-1 block w-full rounded-xl border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 transition px-4 py-2 border text-gray-900 bg-gray-50 focus:bg-white @error('institute_name') border-red-500 @enderror" placeholder="e.g. Acme Academy">
                        @error('institute_name')
                            <p class="mt-1 text-xs text-red-500 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Owner Name -->
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700">Owner Full Name <span class="text-red-500">*</span></label>
                        <input type="text" name="name" id="name" value="{{ old('name') }}" required 
                            oninput="clearError(this)"
                            class="mt-1 block w-full rounded-xl border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 transition px-4 py-2 border text-gray-900 bg-gray-50 focus:bg-white @error('name') border-red-500 @enderror" placeholder="John Doe">
                        @error('name')
                            <p class="mt-1 text-xs text-red-500 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Email Address -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700">Contact Email <span class="text-red-500">*</span></label>
                        <input type="email" name="email" id="email" value="{{ old('email') }}" required 
                            oninput="clearError(this)"
                            class="mt-1 block w-full rounded-xl border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 transition px-4 py-2 border text-gray-900 bg-gray-50 focus:bg-white @error('email') border-red-500 @enderror" placeholder="contact@acme.edu">
                        @error('email')
                            <p class="mt-1 text-xs text-red-500 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Phone Number -->
                    <div>
                        <label for="phone" class="block text-sm font-medium text-gray-700">Phone Number <span class="text-red-500">*</span></label>
                        <input type="tel" name="phone" id="phone" value="{{ old('phone') }}" required 
                            oninput="clearError(this)"
                            class="mt-1 block w-full rounded-xl border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 transition px-4 py-2 border text-gray-900 bg-gray-50 focus:bg-white @error('phone') border-red-500 @enderror" placeholder="+1 (555) 000-0000">
                        @error('phone')
                            <p class="mt-1 text-xs text-red-500 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Logo Upload -->
                    <div>
                        <label for="logo" class="block text-sm font-medium text-gray-700">Institute Logo</label>
                        <input type="hidden" name="logo_base64" id="logo_base64" value="{{ old('logo_base64') }}">
                        <div class="mt-1 flex items-center gap-4">
                            <div class="flex-shrink-0">
                                <div id="logo-preview" class="w-12 h-12 rounded-xl bg-gray-50 border border-gray-100 flex items-center justify-center overflow-hidden">
                                     @if(old('logo_base64'))
                                        <img src="{{ old('logo_base64') }}" class="w-full h-full object-cover">
                                     @else
                                        <span class="text-xl text-gray-300">🏢</span>
                                     @endif
                                </div>
                            </div>
                            <input type="file" name="logo" id="logo" accept="image/*" 
                                onchange="previewImage(this)"
                                class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 transition @error('logo') border-red-500 @enderror">
                        </div>
                        @error('logo')
                            <p class="mt-1 text-xs text-red-500 font-medium">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-xs text-gray-500">PNG, JPG or WebP. Max 2MB.</p>
                    </div>

                    <!-- Status -->
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700">Status <span class="text-red-500">*</span></label>
                        <select name="status" id="status" required 
                            onchange="clearError(this)"
                            class="mt-1 block w-full rounded-xl border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 transition px-4 py-2 border text-gray-900 bg-gray-50 focus:bg-white @error('status') border-red-500 @enderror">
                            <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                            <option value="suspended" {{ old('status') == 'suspended' ? 'selected' : '' }}>Suspended</option>
                        </select>
                        @error('status')
                            <p class="mt-1 text-xs text-red-500 font-medium">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="px-8 pb-8 pt-4">
                    <hr class="border-gray-100 mb-8">
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold text-gray-900">Location Details</h3>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="md:col-span-2">
                            <label for="address" class="block text-sm font-medium text-gray-700">Full Address</label>
                            <input type="text" name="address" id="address" value="{{ old('address') }}" 
                                oninput="clearError(this)"
                                class="mt-1 block w-full rounded-xl border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 transition px-4 py-2 border text-gray-900 bg-gray-50 focus:bg-white @error('address') border-red-500 @enderror" placeholder="123 Education Lane">
                            @error('address')
                                <p class="mt-1 text-xs text-red-500 font-medium">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label for="state" class="block text-sm font-medium text-gray-700">State / Province</label>
                            <select name="state" id="state" onchange="clearError(document.getElementById('state'))"></select>
                        </div>

                        <div>
                            <label for="city" class="block text-sm font-medium text-gray-700">City</label>
                            <select name="city" id="city" onchange="clearError(document.getElementById('city'))"></select>
                        </div>

                        <div>
                            <label for="pincode" class="block text-sm font-medium text-gray-700">Pin Code / Zip</label>
                            <input type="text" name="pincode" id="pincode" value="{{ old('pincode') }}" 
                                oninput="clearError(this)"
                                class="mt-1 block w-full rounded-xl border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 transition px-4 py-2 border text-gray-900 bg-gray-50 focus:bg-white @error('pincode') border-red-500 @enderror">
                            @error('pincode')
                                <p class="mt-1 text-xs text-red-500 font-medium">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="px-8 pb-8 pt-4">
                    <hr class="border-gray-100 mb-8">
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold text-gray-900">Social & Website</h3>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label for="website" class="block text-sm font-medium text-gray-700">Website URL</label>
                            <input type="url" name="website" id="website" value="{{ old('website') }}" 
                                oninput="clearError(this)"
                                class="mt-1 block w-full rounded-xl border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 transition px-4 py-2 border text-gray-900 bg-gray-50 focus:bg-white @error('website') border-red-500 @enderror" placeholder="https://www.acme.edu">
                            @error('website')
                                <p class="mt-1 text-xs text-red-500 font-medium">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="youtube" class="block text-sm font-medium text-gray-700">YouTube Channel</label>
                            <input type="url" name="youtube" id="youtube" value="{{ old('youtube') }}" 
                                oninput="clearError(this)"
                                class="mt-1 block w-full rounded-xl border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 transition px-4 py-2 border text-gray-900 bg-gray-50 focus:bg-white @error('youtube') border-red-500 @enderror" placeholder="https://youtube.com/c/acme">
                            @error('youtube')
                                <p class="mt-1 text-xs text-red-500 font-medium">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="instagram" class="block text-sm font-medium text-gray-700">Instagram Profile</label>
                            <input type="url" name="instagram" id="instagram" value="{{ old('instagram') }}" 
                                oninput="clearError(this)"
                                class="mt-1 block w-full rounded-xl border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 transition px-4 py-2 border text-gray-900 bg-gray-50 focus:bg-white @error('instagram') border-red-500 @enderror" placeholder="https://instagram.com/acme">
                            @error('instagram')
                                <p class="mt-1 text-xs text-red-500 font-medium">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
                
                <div class="bg-gray-50 px-8 py-5 border-t border-gray-200 flex justify-end gap-3 rounded-b-3xl">
                    <a href="{{ route('institutes.index') }}" onclick="showBtnLoader(this)" class="relative inline-flex items-center justify-center px-8 py-3 border border-gray-200 rounded-xl text-xs font-bold uppercase tracking-widest text-gray-500 hover:bg-gray-100 transition shadow-sm bg-white active:scale-95 min-w-[120px]">
                        <span class="btn-content">Cancel</span>
                        <span class="hidden btn-loader">
                            <svg class="animate-spin h-4 w-4 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                        </span>
                    </a>
                    <button type="submit" onclick="showBtnLoader(this)" class="relative inline-flex items-center justify-center px-8 py-3 bg-indigo-600 text-white rounded-xl shadow-lg shadow-indigo-600/30 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition font-bold text-xs uppercase tracking-widest active:scale-95 min-w-[180px]">
                        <span class="btn-content">Create Institute</span>
                        <span class="hidden btn-loader">
                            <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                        </span>
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- ✅ SlimSelect Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/slim-select@2.8.0/dist/slimselect.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/slim-select@2.8.0/dist/slimselect.css" rel="stylesheet"/>
    <script src="{{ asset('js/location_data.js') }}"></script>

    <script>
        let stateSelect;
        let citySelect;

        window.addEventListener('load', function () {
            if (typeof SlimSelect === 'undefined' || !window.indiaLocations) {
                console.error('SlimSelect or Location data NOT loaded');
                return;
            }

            const oldState = "{{ old('state') }}";
            const oldCity = "{{ old('city') }}";

            // ✅ City Select Initialization
            citySelect = new SlimSelect({
                select: '#city',
                contentLocation: document.body,
                settings: {
                    placeholderText: 'Select City',
                    showSearch: false
                },
                data: [{ text: 'Select City', value: '' }]
            });

            // ✅ State Select Initialization
            stateSelect = new SlimSelect({
                select: '#state',
                contentLocation: document.body,
                settings: {
                    placeholderText: 'Select State',
                    showSearch: false
                },
                data: [
                    { text: 'Select State', value: '' },
                    ...Object.keys(window.indiaLocations).sort().map(state => ({
                        text: state,
                        value: state
                    }))
                ],
                events: {
                    afterChange: (newVal) => {
                        const state = newVal[0]?.value;
                        updateCities(state);
                        clearError(document.getElementById('state'));
                    }
                }
            });

            // ✅ Handle Pre-filling old values
            if (oldState) {
                stateSelect.setSelected(oldState);
                updateCities(oldState);
                
                if (oldCity) {
                    setTimeout(() => {
                        citySelect.setSelected(oldCity);
                    }, 150);
                }
            }
        });

        function updateCities(stateName) {
            if (!stateName) {
                citySelect.setData([{ text: 'Select City', value: '' }]);
                return;
            }

            const cities = window.indiaLocations[stateName] || [];
            citySelect.setData([
                { text: 'Select City', value: '' },
                ...cities.map(city => ({
                    text: city,
                    value: city
                }))
            ]);
        }

        function previewImage(input) {
            const preview = document.getElementById('logo-preview');
            const hidden = document.getElementById('logo_base64');
            const file = input.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const base64 = e.target.result;
                    preview.innerHTML = `<img src="${base64}" class="w-full h-full object-cover">`;
                    hidden.value = base64;
                }
                reader.readAsDataURL(file);
            }
            clearError(input);
        }

        function clearError(el) {
            if (!el) return;
            el.classList.remove('border-red-500');
            const ssMain = el.nextElementSibling;
            if (ssMain && ssMain.classList.contains('ss-main')) {
                ssMain.style.borderColor = '#d1d5db';
            }

            const parent = el.closest('div');
            const errorMsg = parent.querySelector('p.text-red-500');
            if (errorMsg) {
                errorMsg.classList.add('hidden');
            }
        }

        function showBtnLoader(btn) {
            btn.querySelector('.btn-content').classList.add('invisible');
            btn.querySelector('.btn-loader').classList.remove('hidden');
            btn.querySelector('.btn-loader').classList.add('absolute', 'flex', 'inset-0', 'items-center', 'justify-center');
            btn.classList.add('opacity-90', 'cursor-not-allowed');
            // For links, pointer-events: none is enough. For buttons, it helps too.
            btn.style.pointerEvents = 'none';
        }
    </script>
</x-admin-layout>