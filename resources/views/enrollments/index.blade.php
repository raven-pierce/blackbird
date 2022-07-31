<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-500 leading-tight">Enrollments</h2>

            <x-links.primary href="{{ route('enrollments.create') }}">
                New Enrollment
                <x-assets.icons.chevron-right class="h-5 w-5" />
            </x-links.primary>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 grid grid-cols-[repeat(auto-fill,minmax(20rem,1fr))] gap-16">
            @foreach ($enrollments as $enrollment)
                <div>
                    <x-enrollments.card :enrollment="$enrollment" :spellOutFormatter="$spellOutFormatter" :loop="$loop" />
                </div>
            @endforeach
        </div>
    </div>
</x-app-layout>
