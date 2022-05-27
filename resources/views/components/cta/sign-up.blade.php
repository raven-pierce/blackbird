<div
    {{ $attributes->merge(['class' =>'flex w-fit items-baseline justify-between space-x-16 border border-l-4 border-gray-900/25 border-l-gray-900 p-2 pl-4']) }}>
    <p class="font-sans text-sm font-light text-gray-500">Create an account to enroll.</p>

    <a href="{{ route('register') }}"
        class="justify-self-end bg-gray-900 px-6 py-1.5 font-sans text-sm font-semibold text-gray-50">Sign Up<i
            class="fa-solid fa-arrow-right ml-2 -rotate-45"></i></a>
</div>
