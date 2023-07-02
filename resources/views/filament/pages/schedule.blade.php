@php use App\Filament\Resources\LectureResource; @endphp
<x-filament::page>
    @if($lectures->isNotEmpty())
        <span class="mt-4 text-lg font-medium text-gray-500 dark:text-gray-400">Here's what you have planned for today.</span>

        <div class="mt-32 grid sm:grid-cols-2 lg:grid-cols-3 2xl:grid-cols-4 gap-16">
            @foreach($lectures as $lecture)
                <div class="flex flex-col justify-center rounded-md bg-white dark:bg-gray-800 shadow-md p-6">
                    <span class="font-semibold text-xs uppercase text-gray-500 dark:text-gray-400">{{ $lecture->section->course->tutor->name }}</span>

                    <span class="mt-6 text-xl font-bold text-gray-700 dark:text-white">{{ $lecture->start_date->format('h:i A') }}</span>

                    <div class="mt-2 flex items-center justify-between">
                        <span class="text-lg text-gray-500 dark:text-gray-200">{{ $lecture->section->course->subject }}</span>
                        <span class="text-xs font-semibold uppercase text-indigo-500 dark:text-indigo-400">{{ $lecture->section->delivery_method }}</span>
                    </div>

                    <div class="mt-4 flex flex-col text-sm text-gray-500 dark:text-gray-400">{{ $lecture->section->course->course_level }}</div>

                    <span class="mt-6 text-xs font-semibold uppercase text-indigo-500 dark:text-indigo-400">{{ $lecture->duration }} Minutes</span>

                    @if($lecture->section->delivery_method !== 'Online' && auth()->user()->hasAnyRole(['icarus', 'tutor']))
                        <x-filament-support::button class="mt-4" tag="a" href="{{ LectureResource::getUrl('attendance', $lecture->id) }}">Take Attendance</x-filament-support::button>
                    @endif
                </div>
            @endforeach
        </div>
    @else
        <div class="flex items-center mx-auto overflow-hidden shadow-lg rounded-lg bg-white dark:bg-gray-800">
            <img src="{{ asset('images/stock/coffee.jpg') }}" alt="A tea cup filled with coffee next to some flowers and herbs." class="w-2/5 object-cover">

            <div class="p-8 w-3/5 flex flex-col justify-center h-full">
                <span class="text-4xl font-bold text-gray-700 dark:text-white">No Lectures Today</span>

                <p class="mt-8 text-lg font-semibold text-gray-500 dark:text-gray-400">
                    You've got no lectures left today. Have a cup of coffee and enjoy the rest of your day!
                </p>
            </div>
        </div>
    @endif
</x-filament::page>
