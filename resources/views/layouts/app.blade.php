@props(['hideNav' => false, 'hideHeader' => true])
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

        <style>
            [x-cloak] { display: none !important; }
        </style>

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">
        <div
            x-data="{ sidebarOpen: false }"
            class="min-h-screen bg-[radial-gradient(900px_circle_at_15%_10%,#9fbbe1,transparent_55%),radial-gradient(900px_circle_at_85%_0%,#b2c8e8,transparent_50%),linear-gradient(180deg,#e7eef8_0%,#7da0ca_100%)]"
        >
            <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 sm:py-8">
                <div class="lg:hidden">
                    <div class="flex items-center justify-between rounded-2xl bg-white/80 px-4 py-3 shadow-sm backdrop-blur">
                        <div class="flex items-center gap-3">
                            <div class="flex h-9 w-9 items-center justify-center rounded-full bg-blue-900 text-xs font-semibold text-white">
                                {{ strtoupper(substr(auth()->user()?->name ?? '', 0, 1)) ?: 'U' }}
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-slate-900">Hi {{ auth()->user()?->name }}</p>
                            </div>
                        </div>
                        <button
                            type="button"
                            class="inline-flex h-10 w-10 items-center justify-center rounded-xl bg-blue-900 text-white shadow transition"
                            x-on:click="sidebarOpen = !sidebarOpen"
                            :aria-expanded="sidebarOpen.toString()"
                            aria-controls="mobile-sidebar"
                            aria-label="Toggle menu"
                        >
                            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round">
                                <path
                                    d="M4 7h16M4 12h16M4 17h16"
                                    :class="sidebarOpen ? 'opacity-0' : 'opacity-100'"
                                    class="transition-opacity duration-200"
                                ></path>
                                <path
                                    d="M6 6l12 12M18 6l-12 12"
                                    :class="sidebarOpen ? 'opacity-100' : 'opacity-0'"
                                    class="transition-opacity duration-200"
                                ></path>
                            </svg>
                        </button>
                    </div>
                </div>

                @unless ($hideNav)
                    <div class="lg:hidden">
                        <div
                            x-show="sidebarOpen"
                            x-transition.opacity
                            class="fixed inset-0 z-40 bg-blue-900/40 backdrop-blur-sm"
                            x-on:click="sidebarOpen = false"
                            x-cloak
                        ></div>
                        <div
                            x-show="sidebarOpen"
                            x-transition:enter="transition ease-out duration-300"
                            x-transition:enter-start="-translate-x-full opacity-0"
                            x-transition:enter-end="translate-x-0 opacity-100"
                            x-transition:leave="transition ease-in duration-200"
                            x-transition:leave-start="translate-x-0 opacity-100"
                            x-transition:leave-end="-translate-x-full opacity-0"
                            class="fixed inset-y-0 left-0 z-50 w-72 overflow-y-auto p-4 sm:p-6"
                            id="mobile-sidebar"
                            x-cloak
                        >
                            @include('layouts.sidebar')
                        </div>
                    </div>
                @endunless

                <div class="mt-6 grid gap-6 lg:mt-0 lg:grid-cols-[240px,1fr]">
                    @unless ($hideNav)
                        <div class="hidden lg:block">
                            @include('layouts.sidebar')
                        </div>
                    @endunless

                    <main>
                        @unless ($hideHeader)
                            @isset($header)
                                <div class="mb-6 rounded-2xl bg-white/80 px-6 py-5 shadow-sm">
                                    {{ $header }}
                                </div>
                            @endisset
                        @endunless

                        {{ $slot }}

                        @if (!request()->routeIs('profile.*') && isset($sidebarCauses))
                            <x-modal name="log-walk" maxWidth="lg">
                                <div class="px-6 py-5" x-data="{ selectedCauseId: @js($sidebarCauses->first()?->id) }">
                                    <div class="flex items-start justify-between">
                                        <div>
                                            <h2 class="text-lg font-semibold text-slate-900">Log Walk</h2>
                                            <p class="text-sm text-blue-500">Submit a new walk distance</p>
                                        </div>
                                        <button class="text-slate-400" x-on:click="$dispatch('close-modal', 'log-walk')">Close</button>
                                    </div>

                                    @if ($sidebarCauses->isEmpty())
                                        <div class="mt-5 rounded-xl bg-slate-50 px-4 py-3 text-sm text-blue-500">
                                            No causes available yet.
                                        </div>
                                    @else
                                        <form method="POST" class="mt-5 space-y-4" x-bind:action="selectedCauseId ? '{{ url('/causes') }}/' + selectedCauseId + '/walks' : '#'">
                                            @csrf

                                            <div>
                                                <label class="text-xs font-semibold uppercase tracking-wide text-blue-500">Cause</label>
                                                <select
                                                    class="mt-2 w-full rounded-xl border border-slate-200 bg-slate-50 px-3 py-2 text-sm text-slate-700"
                                                    name="cause_id"
                                                    x-model="selectedCauseId"
                                                >
                                                    @foreach ($sidebarCauses as $cause)
                                                        <option value="{{ $cause->id }}">{{ $cause->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div>
                                                <label class="text-xs font-semibold uppercase tracking-wide text-blue-500">Distance (km)</label>
                                                <input
                                                    type="number"
                                                    name="distance_km"
                                                    step="0.01"
                                                    min="0"
                                                    class="mt-2 w-full rounded-xl border border-slate-200 bg-slate-50 px-3 py-2 text-sm text-slate-700"
                                                    required
                                                />
                                                @error('distance_km')
                                                    <p class="mt-2 text-xs text-red-600">{{ $message }}</p>
                                                @enderror
                                            </div>

                                            <button type="submit" class="w-full rounded-xl bg-blue-900 py-2.5 text-sm font-semibold text-white shadow">Submit Walk</button>
                                        </form>
                                    @endif
                                </div>
                            </x-modal>
                        @endif
                    </main>
                </div>
            </div>
        </div>
    </body>
</html>
