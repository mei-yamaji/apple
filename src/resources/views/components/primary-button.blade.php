<button {{ $attributes->merge(['class' => 'flex items-center gap-2 px-5 py-3 text-sm font-semibold text-white bg-orange-400 rounded-full shadow-md hover:bg-orange-500 hover:scale-105 transition-all duration-200 focus:outline-none']) }}>
    {{ $slot }}
</button>

