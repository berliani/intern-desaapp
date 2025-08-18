@props(['active'])

@php
$classes = ($active ?? false)
            ? 'inline-flex items-center px-1 py-2 border-b-2 border-emerald-600 text-emerald-600 font-semibold focus:outline-none focus:border-emerald-700 transition duration-150 ease-in-out'
            : 'inline-flex items-center px-1 py-2 border-b-2 border-transparent text-gray-700 hover:text-emerald-600 hover:border-emerald-500 focus:outline-none focus:text-emerald-600 focus:border-emerald-500 transition duration-150 ease-in-out';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>