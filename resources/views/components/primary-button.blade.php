<button {{ $attributes->merge([
    'type' => 'submit',
    'class' => '
        w-full
        justify-center
        text-base
        py-3
        bg-[#837471]
        hover:bg-[#9A816F]
        focus:bg-[#876e5e]
        active:bg-[#7a6354]
        focus:ring-offset-2
        focus:ring-[#9A816F]
        rounded-md
        font-black
        text-white
        transition
        ease-in-out
        duration-150
    '
]) }}>
    {{ $slot }}
</button>
