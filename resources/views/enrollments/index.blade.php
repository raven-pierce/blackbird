<x-app-layout>
    <x-slot name="header">Enrollments</x-slot>

    <x-slot name="description">
        <p class="mt-8 text-gray-700">
            You’ll be able to find your course
            <span class="font-semibold text-indigo-500">enrollments</span>
            on this page, as well as make any
            <span class="font-semibold text-indigo-500">transfers</span>
            or
            <span class="font-semibold text-indigo-500">withdrawals</span>.
        </p>
    </x-slot>

    <!--TODO: visual stuff-->
    <div class="flex flex-wrap mt-16 items-center space-x-16">
        @foreach ($enrollments as $enrollment)
            <x-enrollments.card :enrollment="$enrollment" :loop="$loop" />
        @endforeach
    </div>
</x-app-layout>
