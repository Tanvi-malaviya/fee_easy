@forelse($recentNotifications as $notification)
    <tr class="hover:bg-gray-50/50 transition duration-150 group">
        <td class="px-6 py-4 whitespace-nowrap">
            <div class="text-sm font-bold text-gray-900 leading-tight">{{ $notification->created_at->format('M d, Y') }}</div>
            <div class="text-[10px] text-gray-400 uppercase font-semibold">{{ $notification->created_at->format('h:i A') }}</div>
        </td>
        <td class="px-6 py-4 max-w-md">
            <div class="flex flex-col">
                <h4 class="text-sm font-bold text-gray-900 leading-tight group-hover:text-indigo-600 transition-colors">{{ $notification->title }}</h4>
                <p class="text-xs text-gray-500 line-clamp-2 mt-1">{{ $notification->message }}</p>
            </div>
        </td>
        <td class="px-6 py-4 text-center">
            @if($notification->image)
                <div class="flex justify-center">
                    <div class="w-10 h-10 rounded-lg overflow-hidden border border-gray-100 shadow-sm transition-transform hover:scale-110 cursor-zoom-in" 
                         @click="$dispatch('open-modal', 'view-image-{{ $loop->index }}')">
                        <img src="{{ asset('storage/' . $notification->image) }}" class="w-full h-full object-cover">
                    </div>
                </div>
                
                <x-modal name="view-image-{{ $loop->index }}" :show="false" focusable>
                    <div class="p-4">
                        <img src="{{ asset('storage/' . $notification->image) }}" class="w-full h-auto rounded-2xl shadow-2xl">
                    </div>
                </x-modal>
            @else
                <span class="text-[10px] font-bold text-gray-300 uppercase italic">No Media</span>
            @endif
        </td>
        <td class="px-6 py-4 text-right whitespace-nowrap">
            <span class="px-2.5 py-1 inline-flex text-[10px] font-bold rounded uppercase tracking-wider bg-emerald-50 text-emerald-700 border border-emerald-100">
                Sent
            </span>
        </td>
    </tr>
@empty
    <tr>
        <td colspan="4" class="px-6 py-12 text-center text-gray-500 italic">No broadcasts sent yet.</td>
    </tr>
@endforelse
