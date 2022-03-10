<div>
    <div class="grid grid-cols-4 gap-x-32 justify-between items-center">
        @livewire('awarding-body-dropdown')
        @livewire('course-level-dropdown')
        @livewire('exam-session-dropdown')
        @livewire('course-status-dropdown')
    </div>

    <div class="grid grid-cols-3 gap-32 justify-between items-center mt-32">
        @foreach($courses as $course)
            <div class="flex w-full rounded-2xl shadow-sm transition-all bg-slate-50 hover:shadow-lg hover:cursor-pointer hover:border hover:border-slate-100">
                <img src="{{ $course->thumbnail_url }}" alt="John Doe's profile picture." class="object-cover w-1/3 rounded-tl-2xl rounded-bl-2xl">

                <div class="flex flex-col py-16 pl-16 w-2/3">
                    <h2 class="font-sans text-sm font-light uppercase text-slate-400">{{ $course->subject }}</h2>
                    <h1 class="font-sans text-2xl font-semibold text-slate-900">{{ $course->tutor->name }}</h1>

                    <h3 class="mt-8 font-sans text-lg font-medium text-slate-900">{{ $course->examSession->session->format('F') }} <span class="ml-4 text-base font-light text-slate-400">{{ $course->examSession->session->format('Y') }}</span></h3>
                    <h2 class="mt-4 font-sans text-base font-normal text-orange-500"><span class="mr-4 fas fa-circle-check"></span>{{ $course->status->name }}</h2>
                </div>
            </div>
        @endforeach
    </div>
</div>
