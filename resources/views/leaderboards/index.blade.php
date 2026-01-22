<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Leaderboards') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if ($causes->isEmpty())
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <p class="text-sm text-gray-600">No causes available yet.</p>
                    </div>
                </div>
            @else
                <div class="space-y-6">
                    @foreach ($causes as $cause)
                        @php($entries = $leaderboards[$cause->id] ?? collect())
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                            <div class="p-6 text-gray-900">
                                <h3 class="text-lg font-semibold">{{ $cause->name }}</h3>

                                @if ($entries->isEmpty())
                                    <p class="mt-4 text-sm text-gray-600">No walks logged yet.</p>
                                @else
                                    <div class="mt-4 overflow-x-auto">
                                        <div class="min-w-full overflow-hidden rounded-lg border border-gray-200">
                                            <table class="min-w-full divide-y divide-gray-200 text-base">
                                                <thead class="bg-gray-50">
                                                <tr>
                                                    <th class="px-6 py-4 text-left font-semibold text-gray-700">Rank</th>
                                                    <th class="px-6 py-4 text-left font-semibold text-gray-700">Name</th>
                                                    <th class="px-6 py-4 text-right font-semibold text-gray-700">Distance</th>
                                                </tr>
                                            </thead>
                                            <tbody class="divide-y divide-gray-200">
                                                @foreach ($entries as $entry)
                                                    <tr>
                                                        <td class="px-6 py-4 text-gray-700">{{ $loop->iteration }}</td>
                                                        <td class="px-6 py-4 text-gray-900">{{ $entry->user->name }}</td>
                                                        <td class="px-6 py-4 text-right text-gray-900">{{ number_format($entry->total_distance, 2) }}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</x-app-layout>

