<x-admin-layout title="Edit Staff Department">
    <div class="py-0">
        <div class="max-w-2xl mx-auto">
            
            <!-- Header & Back Link -->
            <div class="flex items-center justify-between mb-4 px-2">
                <a href="{{ route('departments.index') }}"
                    class="inline-flex items-center text-gray-400 hover:text-primary transition-colors group">
                    <div class="p-2 bg-white border border-gray-100 rounded-xl shadow-sm group-hover:border-primary/20 transition-all active:scale-90">
                        <svg class="w-4 h-4 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                    </div>
                    <span class="ml-3 text-[10px] font-black uppercase tracking-widest text-slate-500 group-hover:text-primary transition-colors">
                        Back to Departments
                    </span>
                </a>
            </div>

            <!-- Form Card -->
            <div class="bg-white border border-gray-100 rounded-2xl shadow-sm overflow-hidden p-6">
                <div class="border-b border-gray-100 pb-4 mb-5">
                    <h2 class="text-lg font-bold text-gray-900 leading-tight">Edit Staff Department</h2>
                    <p class="text-[10px] font-bold text-primary uppercase tracking-widest mt-1">Modify global department name</p>
                </div>

                <form method="POST" action="{{ route('departments.update', $department->id) }}" class="space-y-4">
                    @csrf
                    @method('PUT')

                    <!-- Department Name Input -->
                    <div>
                        <x-input-label for="name" value="Department Name" class="text-xs font-semibold text-gray-700" />
                        <x-text-input id="name" name="name" type="text"
                            class="mt-1.5 p-2.5 block w-full bg-gray-50 border border-gray-200 focus:bg-white text-sm rounded-xl focus:ring-primary focus:border-primary transition"
                            placeholder="e.g. Science, Commerce, Administration"
                            value="{{ old('name', $department->name) }}"
                            required 
                            autofocus />
                        <x-input-error class="mt-1 text-xs" :messages="$errors->get('name')" />
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-100 mt-6">
                        <a href="{{ route('departments.index') }}" 
                            class="px-4 py-2.5 border border-gray-200 text-gray-500 rounded-xl font-bold text-[11px] uppercase tracking-widest hover:bg-gray-50 transition-all text-center">
                            Cancel
                        </a>
                        <button type="submit"
                            class="px-5 py-2.5 bg-primary text-white rounded-xl font-bold text-[11px] uppercase tracking-widest hover:opacity-90 shadow-lg shadow-primary/20 transition-all">
                            Update Department
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-admin-layout>
