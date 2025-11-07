<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center cursor-pointer bg-primary px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-800 active:bg-blue-950 focus:outline-none transition ease-in-out duration-150']) }}>
    {{ $slot }}
</button>
