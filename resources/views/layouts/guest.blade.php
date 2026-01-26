<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-gray-900 antialiased bg-slate-50">
        <div class="min-h-screen flex flex-col sm:justify-center items-center px-4 pt-8 sm:pt-0 bg-gradient-to-b from-slate-50 via-white to-slate-100">
            <div></div>

            <div class="w-[92%] max-w-[420px] sm:w-full sm:max-w-md mt-6 px-5 sm:px-6 py-6 bg-white/90 shadow-lg ring-1 ring-black/5 backdrop-blur overflow-hidden rounded-2xl">
                {{ $slot }}
            </div>
        </div>
    </body>
</html>
