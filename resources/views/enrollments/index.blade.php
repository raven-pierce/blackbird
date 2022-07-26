<x-app-layout>
    <x-slot name="header">Enrollments</x-slot>

    <x-slot name="description">
        <p class="mt-8 text-gray-700">
            You’ll be able to find your course <span class="font-semibold text-indigo-500">enrollments</span> on this
            page, as well as make any <span class="font-semibold text-indigo-500">transfers</span> or <span
                class="font-semibold text-indigo-500">withdrawals</span>.
        </p>
    </x-slot>

    <!--TODO: visual stuff-->
    <div class="mt-16 grid grid-cols-[repeat(auto-fill,minmax(20rem,1fr))] gap-16">
        @foreach ($enrollments as $enrollment)
            <div>
                <x-enrollments.card :enrollment="$enrollment" :loop="$loop" />
            </div>
        @endforeach
    </div>
</x-app-layout>
