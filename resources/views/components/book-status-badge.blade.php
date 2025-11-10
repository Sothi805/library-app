@props(['status'])

@php
    $classes = match($status) {
        'Available' => 'bg-green-600 text-green-100',
        'Borrowed' => 'bg-yellow-600 text-yellow-100',
        'Out of Stock' => 'bg-red-600 text-red-100',
        default => 'bg-gray-600 text-gray-100'
    };
@endphp

<span {{ $attributes->merge(['class' => 'text-xs py-1 px-2 rounded-full font-bold ' . $classes]) }}>
    {{ $status }}
</span>
