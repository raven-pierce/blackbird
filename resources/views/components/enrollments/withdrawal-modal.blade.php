@props(['enrollment'])

<div x-show="withdrawOpen" class="grid place-content-center absolute inset-0" style="display: none">
    <div class="absolute inset-0 bg-gray-700 bg-opacity-25 overflow-y-auto h-full w-full"></div>

    <div class="relative flex flex-col rounded bg-gray-50 p-4 text-base text-gray-500 overflow-hidden w-fit h-fit"
        @click.away="withdrawOpen = false">
        <span class="-m-4 bg-gray-900 p-4 font-semibold text-sm text-gray-300">Withdraw Enrollment?</span>

        <p class="mt-8">You’re about to <span class="font-semibold">withdraw</span> from <span
                class="font-semibold text-indigo-500">{{ $enrollment->section->course->subject->name }}</span> taught by
            <span class="font-semibold text-indigo-500">{{ $enrollment->section->course->tutor->name }}</span>.</p>

        <span class="mt-4">Please confirm this action.</span>

        <div class="mt-6 flex items-center space-x-8 justify-end">
            <x-links.tertiary @click="withdrawOpen = false">Cancel</x-links.tertiary>


            <form method="POST" action="{{ route('enrollments.destroy', $enrollment) }}">
                @csrf
                @method('DELETE')

                <input type="hidden" name="enrollment" value="{{ $enrollment->id }}">

                <x-forms.button>Withdraw</x-forms.button>
            </form>
        </div>
    </div>
</div>
