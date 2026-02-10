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
        <div class="mx-auto flex min-h-screen w-full max-w-6xl items-center px-4 py-10 sm:px-6 lg:px-10">
            <div class="grid w-full overflow-hidden rounded-3xl border border-[var(--app-border)] bg-white shadow-[0_20px_70px_rgba(15,23,42,0.12)] lg:grid-cols-[1fr,460px]">
                <section class="relative hidden p-10 lg:block">
                    <div class="absolute inset-0 bg-[linear-gradient(135deg,#f8fafc_0%,#e2e8f0_100%)]"></div>
                    <div class="relative z-10">
                        <img
                            src="{{ asset('images/me-logo.png') }}"
                            alt="Mentorship For Excellence International Botswana"
                            class="h-auto w-full max-w-[320px]"
                        />
                        <h1 class="mt-4 text-3xl font-semibold text-slate-900">Track every walk with a focused dashboard layout.</h1>
                        <p class="mt-4 text-sm leading-6 text-slate-600">
                            Sign in to manage causes, log distances, and monitor leaderboards from a single workspace.
                        </p>
                    </div>
                </section>

                <section class="p-6 sm:p-8 lg:p-10">
                    {{ $slot }}
                </section>
            </div>
        </div>
    </body>
</html>
