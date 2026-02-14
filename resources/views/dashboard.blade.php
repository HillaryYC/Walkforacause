<x-app-layout :hide-nav="false" :hide-header="true">
    @php
        $formatDistance = function ($value) {
            return rtrim(rtrim(number_format($value, 2), '0'), '.');
        };
        $carouselCount = count($causeStats ?? []);
        $donutPalette = ['#0ea5e9', '#84cc16', '#fb7185', '#f59e0b', '#14b8a6', '#8b5cf6', '#ef4444', '#6366f1'];
        $donutStats = collect($causeStats ?? [])->filter(fn ($stat) => (float) ($stat['total'] ?? 0) > 0)->values();
        $donutCauseCount = $donutStats->count();
        if ($donutCauseCount >= 10) {
            $ringSizeCss = 'min(calc(100vw - 4rem), 30rem)';
        } elseif ($donutCauseCount >= 7) {
            $ringSizeCss = 'min(calc(100vw - 4rem), 28rem)';
        } elseif ($donutCauseCount >= 5) {
            $ringSizeCss = 'min(calc(100vw - 4rem), 26rem)';
        } else {
            $ringSizeCss = 'min(calc(100vw - 4rem), 24rem)';
        }
        $donutTotal = (float) $donutStats->sum('total');
        $donutSegments = [];
        $donutGradient = 'conic-gradient(#e2e8f0 0% 100%)';

        if ($donutTotal > 0) {
            $runningPercent = 0.0;
            foreach ($donutStats as $index => $stat) {
                $segmentTotal = (float) $stat['total'];
                $start = $runningPercent;
                $segmentPercent = ($segmentTotal / $donutTotal) * 100;
                $runningPercent = min($start + $segmentPercent, 100);
                $color = $donutPalette[$index % count($donutPalette)];

                $donutSegments[] = [
                    'cause' => $stat['cause'],
                    'name' => $stat['cause']->name,
                    'total' => $segmentTotal,
                    'color' => $color,
                    'from' => $start,
                    'to' => $runningPercent,
                    'percent' => $segmentPercent,
                ];
            }

            $gradientStops = array_map(fn ($segment) => sprintf('%s %.4f%% %.4f%%', $segment['color'], $segment['from'], $segment['to']), $donutSegments);
            if (!empty($gradientStops)) {
                $donutGradient = 'conic-gradient(' . implode(', ', $gradientStops) . ')';
            }
        }
    @endphp

    <div class="rounded-3xl border border-[var(--app-border)] bg-white p-4 shadow-sm sm:p-6 lg:p-8">
        <div class="grid gap-5 sm:gap-6 lg:grid-cols-[minmax(0,1fr),280px]">
            <section class="min-w-0 space-y-6">
                <div class="space-y-6">
                    <div>
                        @if ($carouselCount === 0)
                            <p class="text-sm text-blue-500">No walks yet</p>
                        @else
                            <div class="flex flex-col items-center gap-4">
                                <div class="w-full">
                                    <div
                                        class="relative mx-auto rounded-full border-4 border-white shadow-sm"
                                        style="background: {{ $donutGradient }}; width: {{ $ringSizeCss }}; height: {{ $ringSizeCss }};"
                                    >
                                        <div class="absolute rounded-full bg-white" style="inset: 26%;"></div>
                                        <svg class="absolute inset-0 z-[1] h-full w-full" viewBox="0 0 100 100" aria-hidden="true">
                                            @foreach ($donutSegments as $segmentIndex => $segment)
                                                @php
                                                    $midPercent = ($segment['from'] + $segment['to']) / 2;
                                                    $midAngle = ($midPercent / 100) * 360;
                                                    $midRadians = deg2rad($midAngle - 90);
                                                    $labelRadius = 37;
                                                    $labelX = 50 + cos($midRadians) * $labelRadius;
                                                    $labelY = 50 + sin($midRadians) * $labelRadius;
                                                    $nameText = trim($segment['name']);
                                                    $valueText = $formatDistance($segment['total']) . ' km';

                                                    // Font sizes scale with segment size
                                                    $nameFontSize = min(3.5, max(1.4, $segment['percent'] * 0.14));
                                                    $valueFontSize = min(3.0, max(1.2, $nameFontSize * 0.82));

                                                    // Chord width available at labelRadius for this segment's arc
                                                    $halfAngle = ($segment['percent'] / 100) * M_PI;
                                                    $chordWidth = 2 * $labelRadius * sin($halfAngle);
                                                    $textAvailWidth = $chordWidth * 0.78;

                                                    // Max chars that fit (bold text ≈ 0.55× fontSize per char)
                                                    $nameMaxChars = max(0, (int) floor($textAvailWidth / ($nameFontSize * 0.55)));
                                                    $valueMaxChars = max(0, (int) floor($textAvailWidth / ($valueFontSize * 0.55)));

                                                    // Truncate if needed
                                                    $nameDisplay = mb_strlen($nameText) <= $nameMaxChars
                                                        ? $nameText
                                                        : rtrim(mb_substr($nameText, 0, max(1, $nameMaxChars - 1))) . '…';
                                                    $valueDisplay = mb_strlen($valueText) <= $valueMaxChars
                                                        ? $valueText
                                                        : rtrim(mb_substr($valueText, 0, max(1, $valueMaxChars - 1))) . '…';

                                                    $nameY = $labelY - $nameFontSize * 0.65;
                                                    $valueY = $nameY + $nameFontSize * 1.25;

                                                    // Only show if at least 3 chars fit
                                                    $showLabel = $nameMaxChars >= 3;
                                                @endphp
                                                @if ($showLabel)
                                                    <a href="{{ route('causes.show', $segment['cause']) }}" style="cursor: pointer;">
                                                        <text
                                                            x="{{ number_format($labelX, 3, '.', '') }}"
                                                            y="{{ number_format($nameY, 3, '.', '') }}"
                                                            text-anchor="middle"
                                                            style="font-size: {{ number_format($nameFontSize, 2, '.', '') }}px; font-weight: 700; fill: #ffffff; paint-order: stroke; stroke: rgba(15, 23, 42, 0.72); stroke-width: 0.6;"
                                                        >{{ $nameDisplay }}</text>
                                                        <text
                                                            x="{{ number_format($labelX, 3, '.', '') }}"
                                                            y="{{ number_format($valueY, 3, '.', '') }}"
                                                            text-anchor="middle"
                                                            style="font-size: {{ number_format($valueFontSize, 2, '.', '') }}px; font-weight: 600; fill: #ffffff; paint-order: stroke; stroke: rgba(15, 23, 42, 0.72); stroke-width: 0.55;"
                                                        >{{ $valueDisplay }}</text>
                                                    </a>
                                                @endif
                                            @endforeach
                                        </svg>
                                        <div class="absolute inset-0 flex items-center justify-center">
                                            <div class="text-center">
                                                <p class="text-xs uppercase tracking-[0.2em] text-slate-400">Total Walked</p>
                                                <p class="mt-1 text-2xl font-semibold text-slate-900">{{ $formatDistance($donutTotal) }} km</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <button
                                    type="button"
                                    class="sm:hidden inline-flex items-center justify-center gap-2 rounded-full bg-blue-500 px-8 py-3 text-sm font-semibold text-white shadow-md transition-transform hover:bg-blue-600 active:scale-95"
                                    x-data
                                    x-on:click="$dispatch('open-modal', 'log-walk')"
                                    aria-label="Log walk"
                                >
                                    <span class="text-base leading-none">+</span>
                                    <span>Log Walk</span>
                                </button>
                            </div>
                        @endif
                    </div>

                    <div class="min-w-0 border-t border-blue-200 pt-6">
                        <h2 class="text-sm font-semibold text-slate-900">Recent Activity</h2>
                        <div class="mt-4 max-h-72 overflow-y-auto pr-1">
                            @if ($recentActivity->isEmpty())
                                <div class="rounded-xl bg-blue-50 px-4 py-3 text-sm text-blue-500">No activity yet</div>
                            @else
                                <div class="divide-y divide-blue-100">
                                    @foreach ($recentActivity as $walk)
                                        <div class="flex items-center justify-between py-3">
                                            <div class="min-w-0">
                                                <p class="text-sm font-semibold text-slate-900">{{ $formatDistance($walk->distance_km) }} km</p>
                                                <p class="text-xs text-blue-500">{{ $walk->cause->name }}</p>
                                            </div>
                                            <span class="shrink-0 text-xs text-slate-400">{{ $walk->walked_on->toFormattedDateString() }}</span>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </section>

            <aside class="min-w-0 border-t border-blue-200 pt-6 lg:border-l lg:border-t-0 lg:pl-6 lg:pt-0">
                <div>
                    <h2 class="text-lg font-semibold text-slate-900">Recently Added Causes</h2>
                    <div class="mt-4 space-y-3 max-h-72 overflow-y-auto pr-1">
                        @forelse ($causes->take(3) as $cause)
                            <a href="{{ route('causes.show', $cause) }}" class="block rounded-2xl border border-blue-100 bg-white px-4 py-3 shadow-sm">
                                <p class="text-sm font-semibold text-slate-900">{{ $cause->name }}</p>
                                <p class="mt-1 text-xs text-slate-900">{{ $cause->description ?: 'No description yet.' }}</p>
                            </a>
                        @empty
                            <div class="rounded-2xl border border-blue-100 bg-white px-4 py-3 text-sm text-slate-900">No causes available yet.</div>
                        @endforelse
                    </div>
                </div>

                <div class="mt-6 border-t border-blue-200 pt-6">
                <h2 class="text-sm font-semibold text-slate-900">Your Rank</h2>
                <div class="mt-4 space-y-3">
                    <div class="flex items-center justify-between rounded-2xl border border-blue-100 bg-white px-4 py-3">
                        <div>
                            <p class="text-sm font-semibold text-slate-800">
                                @if ($leaderboardRank)
                                    #{{ $leaderboardRank }}
                                @else
                                    --
                                @endif
                            </p>
                            <p class="text-xs text-blue-500">Rank</p>
                        </div>
                        <p class="text-xs font-semibold text-slate-600">
                            @if ($leaderboardTotal !== null)
                                {{ $formatDistance($leaderboardTotal) }} km
                            @else
                                --
                            @endif
                        </p>
                    </div>
                    <a href="{{ route('leaderboards.index') }}" class="inline-flex w-full items-center justify-center rounded-xl bg-blue-500 py-2 text-xs font-semibold uppercase tracking-wide text-white hover:bg-blue-600">Open Leaderboards</a>
                </div>
                </div>
            </aside>
        </div>
    </div>
</x-app-layout>
