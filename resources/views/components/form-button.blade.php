<button
    {{ $attributes->merge(['type' => 'submit','class' =>'px-10 py-2 lg:px-12 xl:px-16 2xl:px-18 bg-gray-900 border border-transparent rounded font-sans font-semibold text-xs lg:text-sm xl:text-base 2xl:text-lg text-gray-50 text-center tracking-widest uppercase hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring focus:ring-gray-300 transition']) }}>
    {{ $slot }}
</button>
