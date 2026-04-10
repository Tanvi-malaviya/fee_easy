<x-admin-layout title="Edit Institute">


    <div class="max-w-5xl mx-auto">
        <form action="{{ route('institutes.update', $institute) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-4 border-b border-gray-100 bg-gray-50/50 flex items-center gap-4">
                    <a href="{{ route('institutes.index') }}" class="p-2 bg-white rounded-xl shadow-sm border border-gray-100 text-gray-400 hover:text-indigo-600 transition transform active:scale-95">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    </a>
                    <div>
                        <h3 class="text-lg font-bold text-gray-900 leading-none">Institute Profile</h3>
                        <p class="text-xs text-gray-500 font-medium mt-1">Modifying: <span class="text-indigo-600 font-bold">{{ $institute->institute_name }}</span></p>
                    </div>
                </div>
                
                <div class="p-4 grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div>
                        <label for="institute_name" class="block text-sm font-medium text-gray-700">Institute Name <span class="text-red-500">*</span></label>
                        <input type="text" name="institute_name" id="institute_name" value="{{ old('institute_name', $institute->institute_name) }}" required class="mt-1 block w-full rounded-xl border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 transition px-4 py-2 border text-gray-900 bg-gray-50 focus:bg-white @error('institute_name') border-red-500 @enderror">
                        @error('institute_name')
                            <p class="mt-1 text-xs text-red-500 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700">Owner Full Name <span class="text-red-500">*</span></label>
                        <input type="text" name="name" id="name" value="{{ old('name', $institute->name) }}" required class="mt-1 block w-full rounded-xl border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 transition px-4 py-2 border text-gray-900 bg-gray-50 focus:bg-white @error('name') border-red-500 @enderror">
                        @error('name')
                            <p class="mt-1 text-xs text-red-500 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700">Contact Email <span class="text-red-500">*</span></label>
                        <input type="email" name="email" id="email" value="{{ old('email', $institute->email) }}" required class="mt-1 block w-full rounded-xl border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 transition px-4 py-2 border text-gray-900 bg-gray-50 focus:bg-white @error('email') border-red-500 @enderror">
                        @error('email')
                            <p class="mt-1 text-xs text-red-500 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="phone" class="block text-sm font-medium text-gray-700">Phone Number <span class="text-red-500">*</span></label>
                        <input type="tel" name="phone" id="phone" value="{{ old('phone', $institute->phone) }}" required class="mt-1 block w-full rounded-xl border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 transition px-4 py-2 border text-gray-900 bg-gray-50 focus:bg-white @error('phone') border-red-500 @enderror" placeholder="10-digit number">
                        @error('phone')
                            <p class="mt-1 text-xs text-red-500 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Logo Upload -->
                    <div class="flex items-start space-x-4">
                        <div class="flex-1">
                            <label for="logo" class="block text-sm font-medium text-gray-700">Update Logo</label>
                            <input type="file" name="logo" id="logo" accept="image/*" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 transition">
                            <p class="mt-1 text-xs text-gray-500">Keep empty to retain current logo.</p>
                        </div>
                        @if($institute->logo)
                            <div class="mt-6">
                                <img src="{{ asset('storage/' . $institute->logo) }}" class="h-12 w-12 rounded-lg object-cover border border-gray-200 shadow-sm">
                            </div>
                        @endif
                    </div>

                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700">Status <span class="text-red-500">*</span></label>
                        <select name="status" id="status" required class="mt-1 block w-full rounded-xl border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 transition px-4 py-2 border text-gray-900 bg-gray-50 focus:bg-white @error('status') border-red-500 @enderror">
                            @php $s = old('status', $institute->status); @endphp
                            <option value="active" {{ $s == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ $s == 'inactive' ? 'selected' : '' }}>Inactive</option>
                            <option value="suspended" {{ $s == 'suspended' ? 'selected' : '' }}>Suspended</option>
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

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-12">
                        <div class="md:col-span-2">
                            <label for="address" class="block text-sm font-medium text-gray-700">Full Address</label>
                            <input type="text" name="address" id="address" value="{{ old('address', $institute->address) }}" class="mt-1 block w-full rounded-xl border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 transition px-4 py-2 border text-gray-900 bg-gray-50 focus:bg-white @error('address') border-red-500 @enderror">
                            @error('address')
                                <p class="mt-1 text-xs text-red-500 font-medium">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label for="state" class="block text-sm font-medium text-gray-700">State / Province</label>
                            <select name="state" id="state" class="mt-1 block w-full"></select>
                        </div>

                        <div>
                            <label for="city" class="block text-sm font-medium text-gray-700">City</label>
                            <select name="city" id="city" class="mt-1 block w-full"></select>
                        </div>

                        <div>
                            <label for="pincode" class="block text-sm font-medium text-gray-700">Pin Code / Zip</label>
                            <input type="text" name="pincode" id="pincode" value="{{ old('pincode', $institute->pincode) }}" placeholder="6-digit pincode" class="mt-1 block w-full rounded-xl border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 transition px-4 py-2 border text-gray-900 bg-gray-50 focus:bg-white @error('pincode') border-red-500 @enderror">
                            @error('pincode')
                                <p class="mt-1 text-xs text-red-500 font-medium">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
                
                <div class="bg-gray-50 px-8 py-5 border-t border-gray-200 flex justify-end gap-3 rounded-b-2xl">
                    <a href="{{ route('institutes.index') }}" class="px-8 py-3 border border-gray-200 rounded-xl text-xs font-bold uppercase tracking-widest text-gray-500 hover:bg-gray-100 transition shadow-sm bg-white active:scale-95">
                        Cancel
                    </a>
                    <button type="submit" class="px-8 py-3 bg-indigo-600 text-white rounded-xl shadow-lg shadow-indigo-600/30 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition font-bold text-xs uppercase tracking-widest active:scale-95">
                        Save Changes
                    </button>
                </div>
            </div>
        </form>
    </div>
    <script src="{{ asset('js/location_data.js') }}"></script>
    <script>
        let stateSelect;
        let citySelect;

        window.addEventListener('load', function() {
            if (typeof SlimSelect === 'undefined' || !window.indiaLocations) {
                console.error('SlimSelect or Location data NOT loaded');
                return;
            }

            const currentState = "{{ old('state', $institute->state) }}";
            const currentCity = "{{ old('city', $institute->city) }}";

            // Initialize City Select First (to ensure it's ready for any updates)
            citySelect = new SlimSelect({
                select: '#city',
                contentLocation: document.querySelector('body'),
                data: [{
                    text: 'Select City',
                    value: '',
                    placeholder: true
                }]
            });

            // Initialize State Select
            const stateOptions = [{
                text: 'Select State',
                value: '',
                placeholder: true
            }, ...Object.keys(window.indiaLocations).sort().map(state => ({
                text: state,
                value: state
            }))];

            stateSelect = new SlimSelect({
                select: '#state',
                data: stateOptions,
                contentLocation: document.querySelector('body'),
                events: {
                    afterChange: (newVal) => {
                        updateCities(newVal[0].value);
                    }
                }
            });

            // Handle Pre-filling
            if (currentState) {
                stateSelect.setSelected(currentState);
                updateCities(currentState);
                
                if (currentCity) {
                    setTimeout(() => {
                        citySelect.setSelected(currentCity);
                    }, 100);
                }
            }
        });

        function updateCities(stateName) {
            if (!stateName) {
                citySelect.setData([{
                    text: 'Select a state first',
                    value: '',
                    placeholder: true
                }]);
                return;
            }

            const cities = window.indiaLocations[stateName] || [];
            const cityOptions = [{
                text: 'Select City',
                value: '',
                placeholder: true
            }, ...cities.sort().map(city => ({
                text: city,
                value: city
            }))];

            citySelect.setData(cityOptions);
        }
    </script>
</x-admin-layout>
