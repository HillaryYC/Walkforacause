<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ $cause->name }}
            </h2>
            <a href="{{ route('causes.index') }}" class="text-sm font-semibold text-indigo-600 hover:text-indigo-500">
                Back to causes
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-30 lg:px-8">
            <div class="rounded-3xl border border-slate-100 bg-white p-6 shadow-sm lg:-mt-10 lg:min-h-[calc(100vh-10rem)] lg:flex lg:flex-col">
                <div class="grid gap-6 lg:grid-cols-3 lg:flex-1">
                    <div class="lg:col-span-2 space-y-6">
                        <div>
                            <h3 class="text-lg font-semibold text-slate-900">{{ $cause->name }}</h3>
                            <p class="mt-2 text-sm font-semibold text-slate-900">About this cause</p>
                            <p class="mt-2 text-sm text-gray-600">{{ $cause->description ?: 'No description yet.' }}</p>
                        </div>

                        <div class="rounded-3xl border border-slate-100 bg-white p-6 shadow-sm">
                            <div class="flex items-center justify-between">
                                <h3 class="text-lg font-semibold text-slate-900">Leaderboard</h3>
                                <span class="text-sm text-blue-500">Distance in km</span>
                            </div>

                            @if ($leaderboard->isEmpty())
                                <p class="mt-4 text-sm text-blue-500">No walks logged yet.</p>
                            @else
                                <div class="mt-4 max-h-80 space-y-3 overflow-y-auto pr-2">
                                    @foreach ($leaderboard as $entry)
                                        @php($initial = strtoupper(substr($entry->user->name ?? '', 0, 1)) ?: 'U')
                                        <div class="flex items-center justify-between gap-2 rounded-2xl border border-slate-100 bg-slate-50 px-2 py-2 sm:px-3">
                                            <div class="flex min-w-0 items-center gap-2">
                                                <span class="w-5 text-xs font-semibold text-blue-500">{{ $loop->iteration }}.</span>
                                                <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-blue-900 text-[11px] font-semibold text-white">
                                                    {{ $initial }}
                                                </div>
                                                <div class="min-w-0">
                                                    <p class="truncate text-sm font-semibold text-slate-900">{{ $entry->user->name }}</p>
                                                </div>
                                            </div>
                                            <p class="shrink-0 text-xs font-semibold text-slate-700">{{ rtrim(rtrim(number_format($entry->total_distance, 2), '0'), '.') }} km</p>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="space-y-6">
                        <div class="rounded-3xl border border-slate-100 bg-white p-6 shadow-sm">
                            <h3 class="text-lg font-semibold text-slate-900">Log your walk</h3>

                            @if (session('status'))
                                <div class="mt-4 rounded-md bg-green-50 px-4 py-3 text-sm text-green-700">
                                    {{ session('status') }}
                                </div>
                            @endif

                            @if ($errors->any())
                                <div class="mt-4 rounded-md bg-red-50 px-4 py-3 text-sm text-red-700">
                                    <ul class="list-disc pl-5">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            @if ($latestWalk)
                                <p class="mt-4 text-sm text-gray-600">
                                    Your latest distance: <span class="font-semibold">{{ rtrim(rtrim(number_format($latestWalk->distance_km, 2), '0'), '.') }} km</span>
                                    on {{ $latestWalk->walked_on->format('M j, Y') }}.
                                </p>
                            @else
                                <p class="mt-4 text-sm text-gray-600">
                                    You have not logged a walk for this cause yet.
                                </p>
                            @endif

                            <form method="POST" action="{{ route('walks.store', $cause) }}" class="mt-4 space-y-4">
                                @csrf
                                <div>
                                    <x-input-label for="distance_km" value="Distance (km)" />
                                    <x-text-input id="distance_km" name="distance_km" type="number" step="0.01" min="0.01" class="mt-1 block w-full" value="{{ old('distance_km') }}" required />
                                    <x-input-error :messages="$errors->get('distance_km')" class="mt-2" />
                                </div>

                                <x-primary-button>
                                    Log walk
                                </x-primary-button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
