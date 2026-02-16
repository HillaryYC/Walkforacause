<x-app-layout :hide-header="true">
    @php
        $requestedCauseId = (int) request('cause');
        $activeCauseId = $causes->contains('id', $requestedCauseId)
            ? $requestedCauseId
            : $causes->first()?->id;
    @endphp

    <div class="rounded-3xl bg-white/80 p-4 shadow-xl shadow-slate-200/70 backdrop-blur sm:p-8" x-data="{ activeTab: {{ $activeCauseId ?? 'null' }} }">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div>
                <p class="text-xs uppercase tracking-[0.3em] text-slate-400">Leaderboard</p>
            </div>
        </div>

        @if ($causes->isEmpty())
            <div class="mt-6 rounded-2xl bg-white px-4 py-3 text-sm text-blue-500 shadow-sm">
                No causes available yet.
            </div>
        @else
            <div class="mt-6">
                <div class="sm:hidden">
                    <label class="text-xs font-semibold uppercase tracking-wide text-slate-600">Causes</label>
                    <select
                        class="mt-2 w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-700"
                        x-model.number="activeTab"
                    >
                        @foreach ($causes as $cause)
                            <option value="{{ $cause->id }}">{{ $cause->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mt-4 hidden flex-wrap gap-2 rounded-2xl bg-blue-100 p-2 sm:flex sm:mt-0">
                    @foreach ($causes as $cause)
                        <button
                            type="button"
                            class="rounded-full px-4 py-2 text-sm font-semibold transition"
                            :class="activeTab === {{ $cause->id }} ? 'bg-blue-500 text-white shadow' : 'text-slate-600 hover:bg-white'"
                            x-on:click="activeTab = {{ $cause->id }}"
                        >
                            {{ $cause->name }}
                        </button>
                    @endforeach
                </div>
            </div>

            @foreach ($causes as $cause)
                @php($entries = $leaderboards[$cause->id] ?? collect())
                <div class="mt-6" x-show="activeTab === {{ $cause->id }}" x-cloak>
                    <div class="rounded-3xl border border-blue-100 bg-white p-4 shadow-sm sm:p-6">
                        @if ($entries->isEmpty())
                            <p class="text-sm text-blue-500">No walks logged yet.</p>
                        @else
                            <div class="overflow-x-auto">
                                <table class="w-full text-left text-sm">
                                    <thead>
                                        <tr class="border-b border-blue-100 text-xs font-semibold uppercase tracking-wide text-slate-400">
                                            <th class="pb-3 pr-4 font-semibold">#</th>
                                            <th class="pb-3 pr-4 font-semibold">Name</th>
                                            <th class="pb-3 text-right font-semibold">Distance</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-blue-50">
                                        @foreach ($entries as $entry)
                                            @php($initial = strtoupper(substr($entry->user->name ?? '', 0, 1)) ?: 'U')
                                            <tr class="transition hover:bg-blue-50/50">
                                                <td class="py-3.5 pr-4 text-sm font-semibold text-blue-500">{{ $loop->iteration }}</td>
                                                <td class="py-3.5 pr-4">
                                                    <div class="flex items-center gap-3">
                                                        <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-blue-500 text-xs font-semibold text-white">
                                                            {{ $initial }}
                                                        </div>
                                                        <span class="truncate font-semibold text-slate-900">{{ $entry->user->name }}</span>
                                                    </div>
                                                </td>
                                                <td class="py-3.5 text-right font-semibold text-slate-700">{{ rtrim(rtrim(number_format($entry->total_distance, 2), '0'), '.') }} km</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach
        @endif
    </div>
</x-app-layout>
