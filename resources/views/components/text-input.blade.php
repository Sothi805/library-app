@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'px-2 py-1 text-base bg-background-light focus:bg-white border-gray-300 focus:border-gray-300 focus:ring-gray-300 rounded inset-shadow-sm inset-shadow-gray-300']) }}>
