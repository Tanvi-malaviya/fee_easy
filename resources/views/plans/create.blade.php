<x-admin-layout title="Create New Plan">
    <style>
        /* Hide spin buttons for number inputs */
        input::-webkit-outer-spin-button,
        input::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        input[type=number] {
            -moz-appearance: textfield;
        }
    </style>

    <div class="max-w-7xl mx-auto">

     <div class="flex items-center gap-4 mb-2">
            <a href="{{ route('plans.index') }}" onclick="showBtnLoader(this)" class="relative inline-flex items-center justify-center gap-2 px-4 py-2 bg-indigo-600 text-white rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-indigo-700 transition active:scale-95 shadow-lg shadow-indigo-100 min-w-[120px]">
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
        <form action="{{ route('plans.store') }}" method="POST">
            @csrf
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">


                <div class="p-4 grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700">Plan Name <span
                                class="text-red-500">*</span></label>
                        <input type="text" name="name" id="name" required
                            class="mt-1 block w-full rounded-xl border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 transition px-4 py-2 border text-gray-900 bg-gray-50 focus:bg-white"
                            placeholder="e.g. Basic Server">
                    </div>

                    <div>
                        <label for="price" class="block text-sm font-medium text-gray-700">Price ({{ $currency }}) <span
                                class="text-red-500">*</span></label>
                        <input type="number" step="1" min="0" name="price" id="price" required
                            class="mt-1 block w-full rounded-xl border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 transition px-4 py-2 border text-gray-900 bg-gray-50 focus:bg-white"
                            placeholder="100">
                    </div>

                    <div>
                        <label for="duration_days" class="block text-sm font-medium text-gray-700">Duration (Days) <span
                                class="text-red-500">*</span></label>
                        <input type="number" name="duration_days" id="duration_days" min="1" max="365" required
                            class="mt-1 block w-full rounded-xl border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 transition px-4 py-2 border text-gray-900 bg-gray-50 focus:bg-white"
                            placeholder="365">
                    </div>

                    <div>
                        <label for="trial_days" class="block text-sm font-medium text-gray-700">Trial Period
                            (Days)</label>
                        <input type="number" name="trial_days" id="trial_days" value="{{ $default_trial }}"
                            class="mt-1 block w-full rounded-xl border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 transition px-4 py-2 border text-gray-900 bg-gray-50 focus:bg-white"
                            placeholder="{{ $default_trial }}">
                    </div>

                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700">Status <span
                                class="text-red-500">*</span></label>
                        <select name="status" id="status" required
                            class="mt-1 block w-full rounded-xl border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 transition px-4 py-2 border text-gray-900 bg-gray-50 focus:bg-white">
                            <option value="1">Active</option>
                            <option value="0">Inactive</option>
                        </select>
                    </div>
                </div>

                <div class="bg-gray-50 px-8 py-5 border-t border-gray-200 flex justify-end gap-3 rounded-b-2xl">
                    <a href="{{ route('plans.index') }}" onclick="showBtnLoader(this)"
                        class="relative inline-flex items-center justify-center px-8 py-3 border border-gray-200 rounded-xl text-xs font-bold uppercase tracking-widest text-gray-500 hover:bg-gray-100 transition shadow-sm bg-white active:scale-95 min-w-[140px]">
                        <span class="btn-content">Cancel</span>
                        <span class="hidden btn-loader">
                            <svg class="animate-spin h-4 w-4 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                        </span>
                    </a>
                    <button type="submit" onclick="showBtnLoader(this)"
                        class="relative inline-flex items-center justify-center px-10 py-3 bg-indigo-600 text-white rounded-xl shadow-lg shadow-indigo-600/30 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition font-bold text-xs uppercase tracking-widest active:scale-95 min-w-[180px]">
                        <span class="btn-content">Save Plan</span>
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

    <script>
        function showBtnLoader(btn) {
            btn.querySelector('.btn-content').classList.add('invisible');
            btn.querySelector('.btn-loader').classList.remove('hidden');
            btn.querySelector('.btn-loader').classList.add('absolute', 'flex', 'inset-0', 'items-center', 'justify-center');
            btn.classList.add('opacity-90', 'cursor-not-allowed');
            btn.style.pointerEvents = 'none';
        }

        function clearError(el) {
            if (!el) return;
            el.classList.remove('border-red-500');
            const parent = el.closest('div');
            if (!parent) return;
            const errorMsg = parent.querySelector('p.text-red-500');
            if (errorMsg) {
                errorMsg.classList.add('hidden');
            }
        }
    </script>
</x-admin-layout>