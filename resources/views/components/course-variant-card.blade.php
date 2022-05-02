@props(['variant', 'loop'])

<div
    class="flex flex-col rounded bg-gray-100 p-4 sm:flex-row sm:items-center sm:justify-between sm:px-8 sm:py-6 xl:px-10 xl:py-8 2xl:px-12 2xl:py-10">
    <div class="flex flex-col">
        <h3 class="font-sans text-xs font-semibold uppercase text-gray-900 lg:text-sm xl:text-base 2xl:text-lg">
            Group
            {{ NumberFormatter::create('en', NumberFormatter::SPELLOUT)->format($loop->iteration) }}
            <span class="ml-2 font-mono font-light text-gray-500">{{ $variant->delivery_method->name }}</span>
        </h3>

        <div class="mt-4 grid w-fit grid-cols-2 items-baseline gap-y-1">
            @foreach ($variant->variantLectures as $lecture)
                <h5 class="mr-2 font-mono text-xs font-light text-gray-500 lg:text-sm xl:text-base 2xl:text-lg">
                    {{ $lecture->start_time->format('h:i A') }}</h5>
                <h4 class="font-sans text-sm font-medium text-gray-700 lg:text-base xl:text-lg 2xl:text-xl">
                    {{ $lecture->day->name }}</h4>
            @endforeach
        </div>
    </div>

    @auth()
        <form method="POST" action="{{ route('enrollments.store') }}">
            @csrf

            <input type="hidden" name="courseVariant" value="{{ $variant->id }}">
            <x-form-button class="mt-4 sm:mt-0">Enroll</x-form-button>
        </form>
    @else
        <x-link-button href="{{ route('register') }}" class="mt-4 sm:mt-0">Get Started</x-link-button>
    @endauth
</div>
