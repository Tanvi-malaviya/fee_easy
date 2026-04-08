<x-admin-layout>
    <x-slot name="header">
        <div class="flex items-center">
            <a href="{{ route('institutes.index') }}" class="text-indigo-600 hover:text-indigo-800 mr-4 transition">
                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            </a>
            <div>
                <h2 class="font-bold text-2xl text-gray-800 leading-tight tracking-tight">
                    {{ __('Edit Institute:') }} {{ $institute->name }}
                </h2>
                <p class="text-sm text-gray-500 mt-1">Update the profile and location data for this client.</p>
            </div>
        </div>
    </x-slot>

    <div class="mt-6 max-w-5xl mx-auto">
        <form action="{{ route('institutes.update', $institute) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-8 border-b border-gray-100 bg-gray-50/50">
                    <h3 class="text-lg font-semibold text-gray-900">Institute Profile</h3>
                </div>
                
                <div class="p-8 grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div>
                        <label for="institute_name" class="block text-sm font-medium text-gray-700">Institute Name <span class="text-red-500">*</span></label>
                        <input type="text" name="institute_name" id="institute_name" value="{{ old('institute_name', $institute->institute_name) }}" required class="mt-1 block w-full rounded-xl border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 transition px-4 py-2 border text-gray-900 bg-gray-50 focus:bg-white transition px-4 py-2 border text-gray-900 bg-gray-50 focus:bg-white">
                    </div>

                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700">Owner Full Name <span class="text-red-500">*</span></label>
                        <input type="text" name="name" id="name" value="{{ old('name', $institute->name) }}" required class="mt-1 block w-full rounded-xl border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 transition px-4 py-2 border text-gray-900 bg-gray-50 focus:bg-white transition px-4 py-2 border text-gray-900 bg-gray-50 focus:bg-white">
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700">Contact Email <span class="text-red-500">*</span></label>
                        <input type="email" name="email" id="email" value="{{ old('email', $institute->email) }}" required class="mt-1 block w-full rounded-xl border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 transition px-4 py-2 border text-gray-900 bg-gray-50 focus:bg-white transition px-4 py-2 border text-gray-900 bg-gray-50 focus:bg-white">
                    </div>

                    <div>
                        <label for="phone" class="block text-sm font-medium text-gray-700">Phone Number <span class="text-red-500">*</span></label>
                        <input type="tel" name="phone" id="phone" value="{{ old('phone', $institute->phone) }}" required class="mt-1 block w-full rounded-xl border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 transition px-4 py-2 border text-gray-900 bg-gray-50 focus:bg-white transition px-4 py-2 border text-gray-900 bg-gray-50 focus:bg-white">
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
                        <select name="status" id="status" required class="mt-1 block w-full rounded-xl border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 transition px-4 py-2 border text-gray-900 bg-gray-50 focus:bg-white transition px-4 py-2 border text-gray-900 bg-gray-50 focus:bg-white">
                            <option value="active" {{ $institute->status == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ $institute->status == 'inactive' ? 'selected' : '' }}>Inactive</option>
                            <option value="suspended" {{ $institute->status == 'suspended' ? 'selected' : '' }}>Suspended</option>
                        </select>
                    </div>
                </div>

                <div class="px-8 pb-8 pt-4">
                    <hr class="border-gray-100 mb-8">
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold text-gray-900">Location Details</h3>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-6">
                        <div class="md:col-span-2">
                            <label for="address" class="block text-sm font-medium text-gray-700">Full Address</label>
                            <input type="text" name="address" id="address" value="{{ old('address', $institute->address) }}" class="mt-1 block w-full rounded-xl border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 transition px-4 py-2 border text-gray-900 bg-gray-50 focus:bg-white">
                        </div>
                        
                        <div>
                            <label for="city" class="block text-sm font-medium text-gray-700">City</label>
                            <input type="text" name="city" id="city" value="{{ old('city', $institute->city) }}" class="mt-1 block w-full rounded-xl border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 transition px-4 py-2 border text-gray-900 bg-gray-50 focus:bg-white">
                        </div>

                        <div>
                            <label for="state" class="block text-sm font-medium text-gray-700">State / Province</label>
                            <input type="text" name="state" id="state" value="{{ old('state', $institute->state) }}" class="mt-1 block w-full rounded-xl border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 transition px-4 py-2 border text-gray-900 bg-gray-50 focus:bg-white">
                        </div>

                        <div>
                            <label for="pincode" class="block text-sm font-medium text-gray-700">Pin Code / Zip</label>
                            <input type="text" name="pincode" id="pincode" value="{{ old('pincode', $institute->pincode) }}" class="mt-1 block w-full rounded-xl border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 transition px-4 py-2 border text-gray-900 bg-gray-50 focus:bg-white">
                        </div>
                    </div>
                </div>
                
                <div class="bg-gray-50 px-8 py-5 border-t border-gray-200 flex justify-end gap-3 rounded-b-2xl">
                    <a href="{{ route('institutes.index') }}" class="px-6 py-2.5 border border-gray-300 rounded-xl text-sm font-medium text-gray-700 hover:bg-gray-100 transition shadow-sm bg-white">
                        Cancel
                    </a>
                    <button type="submit" class="px-6 py-2.5 bg-indigo-600 text-white rounded-xl shadow-lg shadow-indigo-600/30 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition font-medium text-sm">
                        Save Changes
                    </button>
                </div>
            </div>
        </form>
    </div>
</x-admin-layout>
