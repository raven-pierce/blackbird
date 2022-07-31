<a {{ $attributes->merge(['href' => '#', 'class' => 'inline-flex items-center px-4 py-2 bg-indigo-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-400 active:bg-indigo-600 focus:bg-indigo-600 focus:outline-none focus:ring focus:ring-indigo-300 transition']) }}>
    {{ $slot }}
</a>
