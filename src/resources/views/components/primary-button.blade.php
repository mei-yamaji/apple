<button {{ $attributes->merge(['class' => 'inline-flex items-center px-6 py-3 bg-orange-400 border border-transparent rounded-full font-semibold text-white hover:bg-orange-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-300 transition']) }}>
    {{ $slot }}
</button>
