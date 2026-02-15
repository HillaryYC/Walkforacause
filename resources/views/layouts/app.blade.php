@props(['hideNav' => false, 'hideHeader' => true])
@php
    $user = auth()->user();
    $initial = strtoupper(substr($user?->name ?? '', 0, 1)) ?: 'U';
    $routeName = request()->route()?->getName() ?? 'dashboard';
    $routeLabel = request()->routeIs('causes.show')
        ? 'Causes'
        : str($routeName)
            ->replace('.', ' ')
            ->replace('index', '')
            ->headline()
            ->trim()
            ->value();
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="overflow-x-hidden">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=plus-jakarta-sans:400,500,600,700&display=swap" rel="stylesheet" />

        <style>
            [x-cloak] { display: none !important; }
        </style>

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="app-shell-bg overflow-x-hidden font-sans antialiased text-slate-900">
        <div x-data="{ sidebarOpen: false }" class="min-h-screen">
            <div @class(['min-h-screen', 'lg:grid lg:grid-cols-[250px,minmax(0,1fr)]' => ! $hideNav])>
                @unless ($hideNav)
                    <aside class="hidden border-r border-[var(--app-border)] bg-[var(--app-panel)] lg:flex lg:min-h-screen lg:flex-col">
                        @include('layouts.sidebar')
                    </aside>
                @endunless

                <div class="min-w-0 flex min-h-screen flex-col">
                    <header class="sticky top-0 z-30 border-b border-[var(--app-border)] bg-white/95 backdrop-blur">
                        <div class="flex h-20 items-center px-4 sm:px-6 lg:px-8">
                            <div class="min-w-0 flex items-center gap-3">
                                @unless ($hideNav)
                                    <button
                                        type="button"
                                        class="inline-flex h-10 w-10 items-center justify-center rounded-xl border border-[var(--app-border)] bg-white text-slate-700 lg:hidden"
                                        x-on:click="sidebarOpen = !sidebarOpen"
                                        :aria-expanded="sidebarOpen.toString()"
                                        aria-controls="mobile-sidebar"
                                        aria-label="Toggle menu"
                                    >
                                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round">
                                            <path d="M4 7h16M4 12h16M4 17h16" :class="sidebarOpen ? 'opacity-0' : 'opacity-100'" class="transition-opacity duration-200"></path>
                                            <path d="M6 6l12 12M18 6l-12 12" :class="sidebarOpen ? 'opacity-100' : 'opacity-0'" class="transition-opacity duration-200"></path>
                                        </svg>
                                    </button>
                                @endunless
                                <div class="min-w-0">
                                    <p class="truncate text-lg font-semibold text-slate-900">{{ $routeLabel ?: 'Dashboard' }}</p>
                                </div>
                            </div>

                        </div>
                    </header>

                    @unless ($hideNav)
                        <div class="lg:hidden">
                            <div
                                x-show="sidebarOpen"
                                x-transition.opacity
                                class="fixed inset-0 z-40 bg-slate-900/35"
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
                                class="fixed inset-y-0 left-0 z-50 w-[min(18rem,85vw)] border-r border-[var(--app-border)] bg-[var(--app-panel)]"
                                id="mobile-sidebar"
                                x-cloak
                            >
                                @include('layouts.sidebar')
                            </div>
                        </div>
                    @endunless

                    <main class="min-w-0 flex-1 px-4 py-6 sm:px-6 lg:px-8 lg:py-8">
                        @unless ($hideHeader)
                            @isset($header)
                                <div class="mb-6 rounded-2xl border border-[var(--app-border)] bg-white p-5 shadow-sm">
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
                                            <p class="text-sm text-slate-500">Submit a new walk distance</p>
                                        </div>
                                        <button class="text-slate-400" x-on:click="$dispatch('close-modal', 'log-walk')">Close</button>
                                    </div>

                                    @if ($sidebarCauses->isEmpty())
                                        <div class="mt-5 rounded-xl bg-slate-50 px-4 py-3 text-sm text-slate-600">
                                            No causes available yet.
                                        </div>
                                    @else
                                        <form method="POST" class="mt-5 space-y-4" x-bind:action="selectedCauseId ? '{{ url('/causes') }}/' + selectedCauseId + '/walks' : '#'">
                                            @csrf

                                            <div>
                                                <label class="text-xs font-semibold uppercase tracking-wide text-slate-500">Cause</label>
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
                                                <label class="text-xs font-semibold uppercase tracking-wide text-slate-500">Distance (km)</label>
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

                                            <button type="submit" class="w-full rounded-xl bg-blue-500 py-2.5 text-sm font-semibold text-white shadow">Submit Walk</button>
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
