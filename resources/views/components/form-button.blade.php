<button
    {{ $attributes->merge(['type' => 'submit','class' =>'justify-self-end bg-gray-900 px-8 py-3 font-sans text-sm font-semibold text-gray-50 hover:cursor-pointer']) }}>{{ $slot }}</button>
