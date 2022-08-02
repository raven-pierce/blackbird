<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center rounded-md border border-transparent bg-indigo-500 px-4 py-2 text-xs font-semibold uppercase tracking-widest text-white transition hover:bg-indigo-400 focus:border-indigo-600 focus:outline-none focus:ring focus:ring-indigo-300 active:bg-indigo-600 disabled:opacity-25']) }}>
    {{ $slot }}
</button>
