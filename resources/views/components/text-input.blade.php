@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'w-full px-4 py-3 bg-gray-100 border-transparent focus:border-emerald-500 focus:bg-white focus:ring-0 rounded-lg shadow-sm placeholder-gray-400 text-gray-700 transition duration-200']) }}>
