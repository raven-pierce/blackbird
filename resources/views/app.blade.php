<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title inertia>{{ config('app.name', 'Blackbird') }}</title>

    <!-- Fonts -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Public+Sans">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Bitter">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Roboto+Mono">

    <!-- Styles -->
    <link rel="stylesheet" href="{{ mix('css/app.css') }}">

    <!-- Scripts -->
    <script src="{{ mix('js/app.js') }}" defer></script>
    @inertiaHead
    @routes
</head>

<body class="font-sans antialiased">
@inertia
</body>
</html>
