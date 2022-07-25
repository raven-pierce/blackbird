<x-app-layout>
    <x-slot name="header">
        Good Afternoon, <br /><span class="text-indigo-500">{{ auth()->user()->name }}</span>
    </x-slot>

    <x-slot name="description">
        <p class="mt-8 text-gray-700">
            It’s 29°C on a
            <span class="font-semibold text-indigo-500">Sunday</span>
            , and you have
            <span class="font-semibold text-indigo-500">two classes</span>
            ahead of you.
        </p>
    </x-slot>

    <div class="flex flex-wrap mt-16 items-center space-x-16">
        <div class="flex h-64 w-80 flex-col justify-center rounded bg-gray-900 p-6">
            <span class="font-medium text-indigo-100">One</span>

            <span class="mt-6 text-xl font-bold text-white">Year 10 Biology</span>

            <div class="mt-2 flex items-center justify-between">
                <span class="text-lg text-gray-100">8:30 PM</span>

                <span class="text-xs font-semibold uppercase text-indigo-500">In Person</span>
            </div>

            <span class="mt-4 text-sm font-light text-gray-500">Dr. John Doe</span>

            <span class="mt-6 text-xs font-semibold uppercase text-indigo-500">90 Minutes</span>
        </div>

        <div class="flex h-64 w-80 flex-col justify-center rounded bg-gray-900 p-6">
            <span class="font-medium text-indigo-100">One</span>

            <span class="mt-6 text-xl font-bold text-white">Year 10 Biology</span>

            <div class="mt-2 flex items-center justify-between">
                <span class="text-lg text-gray-100">8:30 PM</span>

                <span class="text-xs font-semibold uppercase text-indigo-500">In Person</span>
            </div>

            <span class="mt-4 text-sm font-light text-gray-500">Dr. John Doe</span>

            <span class="mt-6 text-xs font-semibold uppercase text-indigo-500">90 Minutes</span>
        </div>
    </div>
</x-app-layout>
