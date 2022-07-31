<x-app-layout>
    <x-slot name="header">
        <x-links.tertiary href="{{ route('enrollments.index') }}">
            <x-assets.icons.chevron-left class="h-5 w-5" />
            Back To Enrollments
        </x-links.tertiary>

        <span class="mt-4">{{ $enrollment->section->course->subject->name }}</span>
        <span class="text-indigo-500">{{ $enrollment->section->course->tutor->name }}</span>
    </x-slot>

    <x-slot name="description">
        <p class="mt-8 text-gray-700">
            This course has <span
                class="font-semibold text-indigo-500 lowercase">{{ $spellOutFormatter->format($enrollment->section->getLecturesThisWeek()->isNotEmpty() ? $enrollment->section->getLecturesThisWeek()->count() : $enrollment->section->getLecturesInWeeks()->count()) }}
                lectures</span>
            {{ $enrollment->section->getLecturesThisWeek()->isNotEmpty() ? 'this week' : 'next week' }}, both of which
            are <span
                class="font-semibold text-indigo-500 lowercase">{{ $enrollment->section->delivery_method->name }}</span>
            and last an average of <span
                class="font-semibold text-indigo-500">{{ $enrollment->section->lectures->avg('duration') }}
                minutes</span>. You can contact the TA to swap sections.
        </p>

        <!--TODO: UPDATE section swapping method-->
        <div class="mt-8 flex items-center" x-data="{ withdrawOpen: false }">
            <x-links.tertiary @click="withdrawOpen = true">
                Withdraw Enrollment
            </x-links.tertiary>

            <x-enrollments.withdrawal-modal :enrollment="$enrollment" />
        </div>
    </x-slot>

    <div class="mt-16 grid grid-cols-[repeat(auto-fill,minmax(20rem,1fr))] gap-16">
        @foreach ($enrollment->section->getLecturesThisWeek()->isNotEmpty() ? $enrollment->section->getLecturesThisWeek() : $enrollment->section->getLecturesInWeeks() as $lecture)
            <div>
                <x-enrollments.lecture-min :lecture="$lecture" :spellOutFormatter="$spellOutFormatter" :loop="$loop" />
            </div>
        @endforeach
    </div>
</x-app-layout>
