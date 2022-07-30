<a {{ $attributes->merge(['href' => '#', 'class' => 'w-fit inline-flex items-center pl-2 -ml-2 py-1 text-indigo-500 border-l-2 border-transparent font-semibold text-sm hover:text-indigo-400 hover:border-l-4 hover:border-indigo-500 active:text-indigo-600 focus:text-indigo-600 focus:outline-none focus:border-l-4 focus:border-indigo-500 transition ease-in-out duration-200']) }}>
    {{ $slot }}
</a>
