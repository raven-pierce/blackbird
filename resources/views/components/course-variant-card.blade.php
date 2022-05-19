@props(['variant', 'loop'])

<div id="course-variant-{{ $loop->iteration }}" class="flex flex-col">
    <div class="flex items-center justify-between">
        <h6 class="angled-label bg-gray-900 px-4 py-1.5 font-sans text-sm font-bold uppercase text-gray-50">
            {{ $variant->delivery_method->name }}</h6>

        <div class="flex flex-col items-end">
            <h6 class="font-sans text-xs font-extralight uppercase text-gray-500">Group <span
                    class="font-sans text-sm font-medium text-gray-700">{{ NumberFormatter::create('en', NumberFormatter::SPELLOUT)->format($loop->iteration) }}</span>
            </h6>
            <h6 class="font-sans text-xs font-medium uppercase text-gray-500"><span
                    class="text-sm text-gray-700">{{ $variant->enrollments->count() }}</span><span
                    class="font-extralight">/{{ $variant->seats }}</span> Seats Available</h6>
        </div>
    </div>

    @foreach ($variant->variantLectures as $lecture)
        <div class="mt-16 flex flex-col space-y-2 border-l-4 border-gray-900 p-6">
            <h2 class="font-serif text-2xl font-medium text-gray-900">{{ $lecture->day->name }}</h2>
            <h3 class="font-sans text-base font-normal text-gray-500">{{ $lecture->start_time->format('h:i A') }}</h3>
        </div>
    @endforeach

    <p class="mt-8 font-sans text-base font-normal text-gray-700">This group meets <span class="font-semibold">twice
            weekly</span>,
        on <span class="font-semibold">Sunday</span> and <span class="font-semibold">Wednesday</span>, at <span
            class="font-semibold">10:30 PM</span> and <span class="font-semibold">7:30 PM</span> respectively.</p>
</div>
