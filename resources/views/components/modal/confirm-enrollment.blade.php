@props(['variant', 'loop'])

<div x-show="showModal" style="display: none;"
    class="absolute bottom-0 left-0 z-50 flex w-full items-center justify-between bg-gray-50 py-8 px-16">
    <p class="w-3/5 font-sans text-base font-normal text-gray-700">You’re enrolling in <span
            class="font-semibold">{{ $variant->course->subject->name }}</span> by <span
            class="font-semibold">{{ $variant->course->tutor->name }}</span>. These classes will be taught <span
            class="font-semibold">{{ $variant->delivery_method->name }}</span> on <span
            class="font-semibold">Sunday</span> and <span class="font-semibold">Wednesday</span>, at <span
            class="font-semibold">10:30 PM</span> and <span class="font-semibold">8:30 PM</span>, respectively. Would
        you like to continue?
    </p>

    <div class="flex h-fit w-1/5 items-center justify-between space-x-8">
        <a href="#" @click="showModal = false" class="font-sans text-sm font-normal text-gray-700">Cancel</a>

        <form method="POST" action="{{ route('enrollments.store') }}">
            @csrf

            <input type="hidden" name="courseVariant" value="{{ $variant->id }}">

            <x-form-button>Confirm<i class="fa-solid fa-arrow-right ml-2"></i></x-form-button>
        </form>
    </div>
</div>
