@extends('layouts.guest')

@section('content')
    <header class="grid grid-cols-2 gap-64 justify-center items-center">
        @include('partials.__navigation')

        <div class="flex flex-col space-y-32">
            <h1 class="font-sans font-black text-slate-900 text-8xl">Tutoring, done right.</h1>

            <a href="{{ route('dashboard') }}" class="font-sans font-semibold text-orange-500 text-xl">Get Started<span class="ml-8 fas fa-arrow-right-long"></span></a>
        </div>

        <img src="{{ asset('images/online-learning.svg') }}" alt="A group of students using laptops and instant messaging apps to learn online." class="w-full">
    </header>

    @include('partials.__blob-background')
@endsection
