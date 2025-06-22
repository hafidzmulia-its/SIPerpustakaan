@props([
    'active' => false,
    'tag' => 'a',        // default renders <a>; if 'button', renders <button>
    'href' => null,      // only used if tag == 'a'
])

@php
    $base = 'flex w-full items-center  text-sm font-medium transition-colors';
    $activeClasses = 'bg-[#EDE5DA]  shadow-lg';
    $inactiveClasses = 'text-gray-800 hover:bg-[#EDE5DA]';
    $classes = $base . ' ' . ($active ? $activeClasses : $inactiveClasses);
@endphp

@if($tag === 'button')
    <button {{ $attributes->merge(['class' => $classes]) }}>
        {{ $slot }}
    </button>
@else
    {{-- default <a> --}}
    <a href="{{ $href }}" {{ $attributes->merge(['class' => $classes]) }}>
        {{ $slot }}
    </a>
@endif
