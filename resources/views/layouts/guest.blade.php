<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=plus-jakarta-sans:400,500,600,700&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="app-shell-bg font-sans text-slate-900 antialiased">
        <div class="mx-auto flex min-h-screen w-full max-w-xl items-center px-4 py-10 sm:px-6">
            <div class="w-full overflow-hidden rounded-3xl border border-[var(--app-border)] bg-white shadow-[0_20px_70px_rgba(15,23,42,0.12)]">
                <section class="p-6 sm:p-8 lg:p-10">
                    {{ $slot }}
                </section>
            </div>
        </div>
    </body>
</html>
