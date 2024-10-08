@props(['active'])

@php
    $classes =
        $active ?? false
            ? 'inline-flex items-center px-1 pt-1 border-b-2 border-[#B91C1C] text-sm font-medium leading-5 text-black focus:outline-none focus:border-[#B91C1C] transition duration-150 ease-in-out'
            : 'inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium leading-5 text-black hover:text-red-700 hover:border-[#B91C1C] focus:outline-none focus:text-red-400 focus:border-[#B91C1C] transition duration-150 ease-in-out';
@endphp


<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
