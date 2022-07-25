<x-app-layout>
    <x-slot name="header">
        {{ $enrollment->courseVariant->course->subject->name }}<br /><span class="text-indigo-500">{{ $enrollment->courseVariant->course->tutor->name }}</span>
    </x-slot>

    <x-slot name="description">
        <p class="mt-8 text-gray-700">
            This course has
            <span
                class="font-semibold text-indigo-500 lowercase">{{ $enrollment->courseVariant->variantLectures->count() }}
                lectures</span>
            per week, both of which are
            <span
                class="font-semibold text-indigo-500 lowercase">{{ $enrollment->courseVariant->delivery_method->name }}</span>
            and last
            <span
                class="font-semibold text-indigo-500">{{ $enrollment->courseVariant->variantLectures->first()->duration }}
                minutes</span>.
            You can transfer without prior approval.
        </p>
    </x-slot>

    {{-- <div class="flex flex-col justify-center">
        <span
            class="text-5xl font-black leading-tight"></span>

        <div class="flex items-center justify-between mt-12">
            <MutedLink :href="route('enrollments.index')" :active="route().current('enrollments.index')">
                <ChevronLeftIcon
                    class="mr-2 h-5 w-5 text-gray-300 group-hover:text-gray-200 group-focus:text-gray-200" />
                Back
            </MutedLink>

            <div class="flex items-center space-x-8">
                <TertiaryLink :href="route('enrollments.destroy', enrollment.data)" as="button" method="delete">
                    Withdraw</TertiaryLink>

                <SecondaryLink :href="route('enrollments.edit', enrollment.data)">Transfer</SecondaryLink>
            </div>
        </div>
    </div> --}}

    <!--TODO: dynamic data + position -->
    <div class="flex flex-wrap mt-16 items-center space-x-16">
        @foreach ($enrollment->courseVariant->variantLectures as $lecture)
            <x-enrollments.lecture-min :lecture="$lecture" :loop="$loop" />
        @endforeach
    </div>
</x-app-layout>
