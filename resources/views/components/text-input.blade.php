@props(['disabled' => false])

<input {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge(['class' => 'border border-gray-200 bg-gray-50/50 focus:bg-white focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 rounded-xl px-4 py-2 transition-all duration-300 outline-none w-full']) !!}>
