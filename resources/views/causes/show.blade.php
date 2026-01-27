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
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid gap-6 lg:grid-cols-3">
                <div class="lg:col-span-2 space-y-6">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6 text-gray-900">
                            <h3 class="text-lg font-semibold">About this cause</h3>
                            <p class="mt-2 text-sm text-gray-600">{{ $cause->description ?: 'No description yet.' }}</p>
                        </div>
                    </div>

                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6 text-gray-900">
                            <div class="flex items-center justify-between">
                                <h3 class="text-lg font-semibold">Leaderboard</h3>
                                <span class="text-sm text-gray-500">Distance in km</span>
                            </div>

                            @if ($leaderboard->isEmpty())
                                <p class="mt-4 text-sm text-gray-600">No walks logged yet.</p>
                            @else
                                <div class="mt-4 overflow-x-auto">
                                    <table class="min-w-full divide-y divide-gray-200 text-sm">
                                        <thead class="bg-gray-50">
                                            <tr>
                                                <th class="px-4 py-2 text-left font-semibold text-gray-700">Rank</th>
                                                <th class="px-4 py-2 text-left font-semibold text-gray-700">Walker</th>
                                                <th class="px-4 py-2 text-right font-semibold text-gray-700">Distance</th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-gray-200">
                                            @foreach ($leaderboard as $entry)
                                                <tr>
                                                    <td class="px-4 py-2 text-gray-700">{{ $loop->iteration }}</td>
                                                    <td class="px-4 py-2 text-gray-900">{{ $entry->user->name }}</td>
                                                    <td class="px-4 py-2 text-right text-gray-900">{{ number_format($entry->total_distance, 2) }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="space-y-6">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6 text-gray-900">
                            <h3 class="text-lg font-semibold">Log your walk</h3>

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
                                    Your latest distance: <span class="font-semibold">{{ number_format($latestWalk->distance_km, 2) }} km</span>
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
