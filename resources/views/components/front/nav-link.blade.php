@props(['active'])

@php
$classes = 'inline-flex items-center text-sm font-medium transition duration-200 ease-in-out space-x-1.5';
$textClasses = ($active ?? false)
    ? 'border-b-2 border-emerald-500 text-emerald-600 font-semibold'
    : 'border-b-2 border-transparent text-gray-600 hover:text-emerald-500 hover:border-emerald-300';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    <span class="flex-shrink-0 text-emerald-500">
        {{ $icon ?? '' }}
    </span>
    <span class="py-2 px-1 {{ $textClasses }}">
        {{ $slot }}
    </span>
</a> 