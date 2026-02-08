<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex justify-center items-center w-full px-4 py-3 bg-[#8B3A10] border border-transparent rounded-lg font-bold text-base text-white hover:bg-[#702E0C] active:bg-[#5a240a] focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 transition ease-in-out duration-300 shadow-md']) }}>
    {{ $slot }}
</button>
