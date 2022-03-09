<div class="flex w-full rounded-2xl shadow-sm transition-all bg-slate-50 hover:shadow-lg hover:cursor-pointer hover:border hover:border-slate-100">
    <img src="{{ asset('images/john-doe.png') }}" alt="John Doe's profile picture." class="object-cover w-1/3 rounded-tl-2xl rounded-bl-2xl">

    <div class="flex flex-col py-16 pl-16 w-2/3">
        <h2 class="font-sans text-sm font-light uppercase text-slate-400">Biology</h2>
        <h1 class="font-sans text-2xl font-semibold text-slate-900">{{ $course->tutor->name }}</h1>

        <h3 class="mt-8 font-sans text-lg font-medium text-slate-900">{{ $course->session }} <span class="ml-4 text-base font-light text-slate-400">2022</span></h3>
        <h2 class="mt-4 font-sans text-base font-normal text-orange-500"><span class="mr-4 fas fa-circle-check"></span>{{ $course->status->title }}</h2>
    </div>
</div>
