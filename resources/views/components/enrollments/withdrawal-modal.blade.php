@props(['enrollment'])

<div x-show="withdrawOpen" class="absolute inset-0 grid place-content-center" style="display: none">
    <div class="absolute inset-0 h-full w-full overflow-y-auto bg-gray-700 bg-opacity-25"></div>

    <div class="relative flex h-fit w-fit flex-col overflow-hidden rounded-md bg-gray-50 p-4 text-base text-gray-500"
        @click.away="withdrawOpen = false">
        <span class="-m-4 bg-gray-900 p-4 text-sm font-semibold text-gray-300">Withdraw Enrollment?</span>

        <p class="mt-8">You’re about to <span class="font-semibold">withdraw</span> from <span
                class="font-semibold text-indigo-500">{{ $enrollment->section->course->subject->name }}</span> taught by
            <span class="font-semibold text-indigo-500">{{ $enrollment->section->course->tutor->name }}</span>.</p>

        <span class="mt-4">Please confirm this action.</span>

        <div class="mt-6 flex items-center justify-end space-x-8">
            <x-utilities.links.tertiary @click="withdrawOpen = false">Cancel</x-utilities.links.tertiary>


            <form method="POST" action="{{ route('enrollments.destroy', $enrollment) }}">
                @csrf
                @method('DELETE')

                <input type="hidden" name="enrollment" value="{{ $enrollment->id }}">

                <x-forms.button>Withdraw</x-forms.button>
            </form>
        </div>
    </div>
</div>
