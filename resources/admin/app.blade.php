<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name') }}</title>
    @routes
    @translations

    @env ('local')
        @vite('admin', 'app.ts', 3200)
    @else
        {{ admin_vite() }}
    @endenv
</head>

<body class="antialiased">
    @inertia
</body>

</html>
