<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-xl font-semibold leading-tight text-gray-500">Enrollments</h2>

            @if($enrollments->isNotEmpty())
                <x-utilities.links.primary href="{{ route('enrollments.create') }}">
                    New Enrollment
                    <x-heroicon-s-chevron-right class="h-5 w-5" />
                </x-utilities.links.primary>
            @endif
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 grid grid-cols-[repeat(auto-fill,minmax(20rem,1fr))] gap-16">
            @if($enrollments->isNotEmpty())
                @foreach ($enrollments as $enrollment)
                    <div>
                        <x-enrollments.card :enrollment="$enrollment" :spellOutFormatter="$spellOutFormatter" :loop="$loop" />
                    </div>
                @endforeach
            @else
                <div class="justify-self-center flex items-center justify-center col-span-full max-w-5xl overflow-hidden shadow-lg rounded-lg">
                    <img src="{{ asset('images/stock/library.jpg') }}" alt="A panormaic view of a circular library bookshelf with a vignette." class="w-2/5 object-cover">

                    <div class="p-8 w-3/5 flex flex-col justify-center bg-white h-full">
                        <span class="text-4xl font-bold text-gray-700">No Enrollments Yet</span>

                        <p class="mt-8 text-lg font-semibold text-gray-500">
                            You have not enrolled in any courses yet. Once you do, you'll be able to see them here.
                        </p>

                        <x-utilities.links.buttons.primary href="{{ route('enrollments.create') }}" class="mt-16 w-fit">
                            New Enrollment
                            <x-heroicon-s-chevron-right class="h-5 w-5" />
                        </x-utilities.links.buttons.primary>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
