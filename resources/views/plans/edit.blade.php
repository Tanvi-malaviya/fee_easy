<x-admin-layout title="Edit Plan">

    <div class="max-w-7xl mx-auto">
        <form action="{{ route('plans.update', $plan) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-4 border-b border-gray-100 bg-gray-50/50 flex items-center gap-4">
                    <a href="{{ route('plans.index') }}" class="p-2 bg-white rounded-xl shadow-sm border border-gray-100 text-gray-400 hover:text-indigo-600 transition transform active:scale-95">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    </a>
                    <div>
                        <h3 class="text-lg font-bold text-gray-900 leading-none">Edit Plan Details</h3>
                        <p class="text-xs text-gray-500 font-medium mt-1">Modifying: <span class="text-indigo-600">{{ $plan->name }}</span></p>
                    </div>
                </div>
                
                <div class="p-4 grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700">Plan Name <span class="text-red-500">*</span></label>
                        <input type="text" name="name" id="name" value="{{ old('name', $plan->name) }}" required class="mt-1 block w-full rounded-xl border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 transition px-4 py-2 border text-gray-900 bg-gray-50 focus:bg-white">
                    </div>

                    <div>
                        <label for="price" class="block text-sm font-medium text-gray-700">Price ($) <span class="text-red-500">*</span></label>
                        <input type="number" step="0.01" name="price" id="price" value="{{ old('price', $plan->price) }}" required class="mt-1 block w-full rounded-xl border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 transition px-4 py-2 border text-gray-900 bg-gray-50 focus:bg-white">
                    </div>

                    <div>
                        <label for="duration_days" class="block text-sm font-medium text-gray-700">Duration (Days) <span class="text-red-500">*</span></label>
                        <input type="number" name="duration_days" id="duration_days" value="{{ old('duration_days', $plan->duration_days) }}" required class="mt-1 block w-full rounded-xl border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 transition px-4 py-2 border text-gray-900 bg-gray-50 focus:bg-white">
                    </div>

                    <div>
                        <label for="trial_days" class="block text-sm font-medium text-gray-700">Trial Period (Days) <span class="text-red-500">*</span></label>
                        <input type="number" name="trial_days" id="trial_days" value="{{ old('trial_days', $plan->trial_days) }}" required class="mt-1 block w-full rounded-xl border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 transition px-4 py-2 border text-gray-900 bg-gray-50 focus:bg-white">
                    </div>

                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700">Status <span class="text-red-500">*</span></label>
                        <select name="status" id="status" class="mt-1 block w-full rounded-xl border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 transition px-4 py-2 border text-gray-900 bg-gray-50 focus:bg-white">
                            <option value="1" {{ $plan->status == 1 ? 'selected' : '' }}>Active</option>
                            <option value="0" {{ $plan->status == 0 ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div>
                </div>

                <div class="bg-gray-50 px-8 py-5 border-t border-gray-200 flex justify-end gap-3 rounded-b-2xl">
                    <a href="{{ route('plans.index') }}" class="px-8 py-3 border border-gray-200 rounded-xl text-xs font-bold uppercase tracking-widest text-gray-500 hover:bg-gray-100 transition shadow-sm bg-white active:scale-95">Cancel</a>
                    <button type="submit" class="px-8 py-3 bg-indigo-600 text-white rounded-xl shadow-lg shadow-indigo-600/30 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition font-bold text-xs uppercase tracking-widest active:scale-95">Update Plan</button>
                </div>
            </div>
        </form>
    </div>
</x-admin-layout>
