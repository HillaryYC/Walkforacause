@php
    $user = auth()->user();
    $initial = strtoupper(substr($user?->name ?? '', 0, 1)) ?: 'U';
@endphp

<div class="flex h-full flex-col">
    <div class="border-b border-[var(--app-border)] px-6 py-6">
        <img
            src="{{ asset('images/me-logo.png') }}"
            alt="Mentorship For Excellence International Botswana"
            class="h-auto w-full max-w-[230px]"
        />
    </div>

    <div class="overflow-y-auto px-4 py-5">
        <nav class="space-y-1.5 text-sm font-medium">
            <a href="{{ route('dashboard') }}" class="flex items-center gap-3 rounded-xl px-3 py-2 transition {{ request()->routeIs('dashboard') ? 'bg-slate-900 text-white shadow-sm' : 'text-slate-600 hover:bg-slate-100' }}">
                <span class="inline-flex h-8 w-8 items-center justify-center rounded-lg {{ request()->routeIs('dashboard') ? 'bg-white/15' : 'bg-slate-100' }}">
                    <svg class="h-4 w-4 {{ request()->routeIs('dashboard') ? 'text-white' : 'text-slate-600' }}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M3 10.5L12 3l9 7.5"></path>
                        <path d="M5 10v9a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2v-9"></path>
                    </svg>
                </span>
                Dashboard
            </a>

            <a href="{{ route('causes.index') }}" class="flex items-center gap-3 rounded-xl px-3 py-2 transition {{ request()->routeIs('causes.*') ? 'bg-slate-900 text-white shadow-sm' : 'text-slate-600 hover:bg-slate-100' }}">
                <span class="inline-flex h-8 w-8 items-center justify-center rounded-lg {{ request()->routeIs('causes.*') ? 'bg-white/15' : 'bg-slate-100' }}">
                    <svg class="h-4 w-4 {{ request()->routeIs('causes.*') ? 'text-white' : 'text-slate-600' }}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M12 21s-7-4.4-7-10a4 4 0 0 1 7-2.6A4 4 0 0 1 19 11c0 5.6-7 10-7 10z"></path>
                    </svg>
                </span>
                Causes
            </a>

            <a href="{{ route('leaderboards.index') }}" class="flex items-center gap-3 rounded-xl px-3 py-2 transition {{ request()->routeIs('leaderboards.*') ? 'bg-slate-900 text-white shadow-sm' : 'text-slate-600 hover:bg-slate-100' }}">
                <span class="inline-flex h-8 w-8 items-center justify-center rounded-lg {{ request()->routeIs('leaderboards.*') ? 'bg-white/15' : 'bg-slate-100' }}">
                    <svg class="h-4 w-4 {{ request()->routeIs('leaderboards.*') ? 'text-white' : 'text-slate-600' }}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M4 19V9"></path>
                        <path d="M10 19V5"></path>
                        <path d="M16 19v-7"></path>
                        <path d="M22 19V11"></path>
                    </svg>
                </span>
                Leaderboard
            </a>

        </nav>

        @if (!request()->routeIs('profile.*'))
            <button
                type="button"
                class="mt-6 inline-flex w-full items-center justify-center gap-2 rounded-xl bg-slate-900 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-slate-800"
                x-data
                x-on:click="$dispatch('open-modal', 'log-walk')"
            >
                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <path d="M12 5v14"></path>
                    <path d="M5 12h14"></path>
                </svg>
                Submit Walk
            </button>
        @endif

        <div class="mt-20 border-t border-[var(--app-border)] pt-4">
            <a href="{{ route('profile.edit') }}" class="group mb-2 flex items-center gap-3 rounded-xl px-3 py-2 transition hover:bg-slate-100">
                <div class="flex h-9 w-9 items-center justify-center rounded-full bg-slate-900 text-xs font-semibold text-white">
                    {{ $initial }}
                </div>
                <div class="min-w-0">
                    <p class="truncate text-sm font-semibold text-slate-900">{{ $user?->name }}</p>
                    <p class="text-xs text-slate-500">Profile settings</p>
                </div>
            </a>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="flex w-full items-center gap-3 rounded-xl px-3 py-2 text-sm font-medium text-slate-600 transition hover:bg-slate-100">
                    <span class="inline-flex h-8 w-8 items-center justify-center rounded-lg bg-slate-100">
                        <svg class="h-4 w-4 text-slate-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                            <path d="M16 17l5-5-5-5"></path>
                            <path d="M21 12H9"></path>
                        </svg>
                    </span>
                    Log out
                </button>
            </form>
        </div>
    </div>
</div>
