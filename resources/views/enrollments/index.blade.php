<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-xl font-semibold leading-tight text-gray-500">Enrollments</h2>

            <x-utilities.links.primary href="{{ route('enrollments.create') }}">
                New Enrollment
                <x-heroicon-s-chevron-right class="h-5 w-5" />
            </x-utilities.links.primary>
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
                <div class="col-span-full grid grid-cols-12">
                    <img src="{{ asset('images/library.jpg') }}" alt="A panormaic view of a circular library bookshelf with a vignette." class="col-span-4">

                    <div class="col-span-8 bg-gray-900 p-32">
                        <h1 class="text-5xl font-black leading-tight text-indigo-500">Nothin' Here!</h1>


                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
