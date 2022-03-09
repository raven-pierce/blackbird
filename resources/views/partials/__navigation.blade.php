<nav class="flex col-span-2 justify-between items-center">
    <a href="{{ route('landing') }}"><img src="{{ asset('images/logo.svg')}}" alt="The Blackbird logo; featuring a minimal, low-poly flame." class="w-16 h-16"></a>

    <div class="flex justify-between items-center space-x-32">
        <a href="{{ route('materials') }}" class="font-sans text-lg font-medium text-slate-900">Materials</a>
        <a href="{{ route('tutors') }}" class="font-sans text-lg font-medium text-slate-900">Tutors</a>
        <a href="{{ route('courses') }}" class="font-sans text-lg font-medium text-slate-900">Courses</a>
    </div>

    <a href="{{ route('dashboard') }}" class="px-12 py-3 font-sans text-lg font-medium bg-orange-500 rounded-lg text-slate-50">Get Started</a>
</nav>
