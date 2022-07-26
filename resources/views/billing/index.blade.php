<x-app-layout>
    <x-slot name="header">Billing</x-slot>

    <x-slot name="description">
        <p class="mt-8 text-gray-700">
            Here, you'll be able to find your <span class="font-semibold text-indigo-500">past receipts</span>, as well
            as your <span class="font-semibold text-indigo-500">unpaid classes</span>.
        </p>
    </x-slot>

    <div class="mt-16 grid grid-cols-[repeat(auto-fill,minmax(20rem,1fr))] gap-16">
        @foreach ($enrollments as $enrollment)
            <div class="flex h-60 w-80 flex-col justify-center rounded bg-gray-900 p-6">
                <div class="flex justify-between text-xs uppercase">
                    <span class="font-medium text-indigo-100">{{ NumberFormatter::create('en', NumberFormatter::SPELLOUT)->format($loop->iteration) }}</span>

                    <div class="flex flex-col font-semibold text-gray-500 text-right">
                        <span>{{ $enrollment->section->course->subject->name }}</span>
                        <span>{{ $enrollment->section->course->tutor->name }}</span>
                    </div>
                </div>

                <span class="mt-6 text-xl font-bold text-indigo-500">${{ $enrollment->unitPricing() * $enrollment->unpaidAttendances->count() }}</span>

                <div class="mt-4 flex justify-between text-xs font-semibold uppercase">
                    <div class="flex flex-col text-gray-500">
                        <span>{{ $enrollment->section->course->level->name }}</span>
                        <span>${{ $enrollment->unitPricing() }} per lecture</span>
                    </div>

                    <span class="text-right text-indigo-500">{{ NumberFormatter::create('en', NumberFormatter::SPELLOUT)->format($enrollment->unpaidAttendances->count()) }} {{ $enrollment->unpaidAttendances->count() > 1 ? 'Lectures' : 'Lecture' }}</span>
                </div>

                <form method="POST" action="{{ route('checkout') }}">
                    @csrf

                    <input type="hidden" name="enrollment" value="{{ $enrollment->id }}">

                    <button type="submit" class="mt-6 w-full px-4 py-2 bg-indigo-500 border border-transparent rounded font-semibold text-xs text-center text-white uppercase tracking-widest hover:bg-indigo-400 active:bg-indigo-600 focus:bg-indigo-600 focus:outline-none focus:ring focus:ring-indigo-300 disabled:opacity-25 transition ease-in-out duration-150">Pay Now</button>
                </form>
            </div>
        @endforeach
    </div>
</x-app-layout>
