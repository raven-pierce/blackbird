<x-app-layout>
    <x-slot name="header">
        {{ $enrollment->section->course->subject->name }}<br /><span
            class="text-indigo-500">{{ $enrollment->section->course->tutor->name }}</span>
    </x-slot>

    <x-slot name="description">
        <p class="mt-8 text-gray-700">
            This course has <span
                class="font-semibold text-indigo-500 lowercase">{{ $enrollment->section->lectures->count() }}
                lectures</span> per week, both of which are <span
                class="font-semibold text-indigo-500 lowercase">{{ $enrollment->section->delivery_method->name }}</span>
            and last <span
                class="font-semibold text-indigo-500">{{ $enrollment->section->lectures->first()->duration }}
                minutes</span>. You can transfer without prior approval.
        </p>
    </x-slot>

    <!--TODO: UPDATE method for enrollment transfers-->
    <!--TODO: confirmation modals-->
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

    <!--TODO: visual stuff-->
    <div class="mt-16 grid grid-cols-[repeat(auto-fill,minmax(20rem,1fr))] gap-16">
        @foreach ($enrollment->section->lectures as $lecture)
            <div>
                <x-enrollments.lecture-min :lecture="$lecture" :loop="$loop" />
            </div>
        @endforeach
    </div>
</x-app-layout>
