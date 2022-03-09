@extends('layouts.guest')

@section('content')
    @include('partials.__navigation')

    <main class="mt-32">
        @livewire('courses-grid')
    </main>

    @include('partials.__blob-background')
@endsection
