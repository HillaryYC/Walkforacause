@php
    $user = auth()->user();
@endphp

<aside class="rounded-3xl bg-white/80 p-4 shadow-lg shadow-slate-200/60 backdrop-blur sm:p-6">
    <div class="flex items-center gap-3">
        <div class="flex h-10 w-10 items-center justify-center rounded-full bg-blue-900 text-sm font-semibold text-white">
            {{ strtoupper(substr($user?->name ?? '', 0, 1)) ?: 'U' }}
        </div>
        <div>
            <p class="text-xs uppercase tracking-[0.2em] text-slate-400">Welcome</p>
            <p class="text-sm font-semibold text-slate-900">{{ $user?->name }}</p>
        </div>
    </div>

    <nav class="mt-8 space-y-2 text-sm font-medium">
        <a href="{{ route('dashboard') }}" class="flex items-center gap-3 rounded-xl px-3 py-2 transition {{ request()->routeIs('dashboard') ? 'bg-blue-900 text-white shadow' : 'text-slate-600 hover:bg-slate-100' }}">
            <span class="inline-flex h-8 w-8 items-center justify-center rounded-lg {{ request()->routeIs('dashboard') ? 'bg-white/15' : 'bg-slate-100' }}">
                <svg class="h-4 w-4 {{ request()->routeIs('dashboard') ? 'text-white' : 'text-blue-500' }}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <path d="M3 10.5L12 3l9 7.5"></path>
                    <path d="M5 10v9a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2v-9"></path>
                </svg>
            </span>
            Dashboard
        </a>
        <a href="{{ route('causes.index') }}" class="flex items-center gap-3 rounded-xl px-3 py-2 text-slate-600 transition hover:bg-slate-100">
            <span class="inline-flex h-8 w-8 items-center justify-center rounded-lg bg-slate-100">
                <svg class="h-4 w-4 text-blue-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <path d="M12 21s-7-4.4-7-10a4 4 0 0 1 7-2.6A4 4 0 0 1 19 11c0 5.6-7 10-7 10z"></path>
                </svg>
            </span>
            Causes
        </a>
        <a href="{{ route('leaderboards.index') }}" class="flex items-center gap-3 rounded-xl px-3 py-2 text-slate-600 transition hover:bg-slate-100">
            <span class="inline-flex h-8 w-8 items-center justify-center rounded-lg bg-slate-100">
                <svg class="h-4 w-4 text-blue-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <path d="M4 19V9"></path>
                    <path d="M10 19V5"></path>
                    <path d="M16 19v-7"></path>
                    <path d="M22 19V11"></path>
                </svg>
            </span>
            Leaderboard
        </a>
        <button
            type="button"
            class="mx-auto flex items-center justify-center gap-2 rounded-xl bg-blue-900 px-4 py-2.5 text-sm font-semibold text-white shadow transition hover:bg-blue-800"
            x-data
            x-on:click="$dispatch('open-modal', 'log-walk')"
        >
            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <path d="M12 5v14"></path>
                <path d="M5 12h14"></path>
            </svg>
            Submit Walk
        </button>
    </nav>

    <div class="mt-6 border-t border-slate-100 pt-4">
        <a href="{{ route('profile.edit') }}" class="mb-2 flex items-center gap-3 rounded-xl px-3 py-2 text-slate-600 transition hover:bg-slate-100">
            <span class="inline-flex h-8 w-8 items-center justify-center rounded-lg bg-slate-100">
                <svg class="h-4 w-4 text-blue-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <path d="M20 21a8 8 0 0 0-16 0"></path>
                    <circle cx="12" cy="8" r="4"></circle>
                </svg>
            </span>
            Profile
        </a>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="flex w-full items-center gap-3 rounded-xl px-3 py-2 text-sm font-medium text-slate-600 transition hover:bg-slate-100">
                <span class="inline-flex h-8 w-8 items-center justify-center rounded-lg bg-slate-100">
                    <svg class="h-4 w-4 text-blue-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                        <path d="M16 17l5-5-5-5"></path>
                        <path d="M21 12H9"></path>
                    </svg>
                </span>
                Log out
            </button>
        </form>
    </div>
</aside>
