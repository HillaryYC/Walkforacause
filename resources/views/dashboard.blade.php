<x-app-layout :hide-nav="false" :hide-header="true">
    @php
        $formatDistance = function ($value) {
            return rtrim(rtrim(number_format($value, 2), '0'), '.');
        };
        $carouselCount = count($causeStats ?? []);
        $donutPalette = ['#0B3C5D', '#145DA0', '#0A2F4E', '#1F4E79', '#0B3C5D', '#145DA0', '#0A2F4E', '#1F4E79'];
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
                                            {{-- Arc path definitions: name (r=39) and value (r=34) per segment --}}
                                            <defs>
                                                @foreach ($donutSegments as $segmentIndex => $segment)
                                                    @php
                                                        $sMidPercent   = ($segment['from'] + $segment['to']) / 2;
                                                        $sIsFullCircle = ($segment['percent'] >= 99.9);
                                                    @endphp
                                                    @if ($sIsFullCircle)
                                                        {{-- Full circle: arc path degenerates; use a circle element instead --}}
                                                        <circle id="arc-n-{{ $segmentIndex }}" cx="50" cy="50" r="39" fill="none"/>
                                                        <circle id="arc-v-{{ $segmentIndex }}" cx="50" cy="50" r="34" fill="none"/>
                                                    @else
                                                        @php
                                                            $sStartRad   = deg2rad($segment['from'] / 100 * 360 - 90);
                                                            $sEndRad     = deg2rad($segment['to']   / 100 * 360 - 90);
                                                            // Text reads right-to-left on a CW arc when midPercent ∈ (25,75)
                                                            $sBottomHalf = ($sMidPercent > 25 && $sMidPercent < 75);
                                                            $sLargeArc   = ($segment['percent'] > 50) ? 1 : 0;
                                                        @endphp
                                                        @foreach ([['arc-n', 39], ['arc-v', 34]] as [$pid, $r])
                                                            @php
                                                                if ($sBottomHalf) {
                                                                    $px1 = 50 + $r * cos($sEndRad);   $py1 = 50 + $r * sin($sEndRad);
                                                                    $px2 = 50 + $r * cos($sStartRad); $py2 = 50 + $r * sin($sStartRad);
                                                                    $sw  = 0;
                                                                } else {
                                                                    $px1 = 50 + $r * cos($sStartRad); $py1 = 50 + $r * sin($sStartRad);
                                                                    $px2 = 50 + $r * cos($sEndRad);   $py2 = 50 + $r * sin($sEndRad);
                                                                    $sw  = 1;
                                                                }
                                                                $sPathD = sprintf('M %.3f,%.3f A %s,%s 0 %d %d %.3f,%.3f',
                                                                    $px1, $py1, $r, $r, $sLargeArc, $sw, $px2, $py2);
                                                            @endphp
                                                            <path id="{{ $pid }}-{{ $segmentIndex }}" d="{{ $sPathD }}" fill="none"/>
                                                        @endforeach
                                                    @endif
                                                @endforeach
                                            </defs>

                                            {{-- Segment divider lines --}}
                                            @if (count($donutSegments) > 1)
                                                @foreach ($donutSegments as $segment)
                                                    @php
                                                        $divAngleRad = deg2rad($segment['from'] / 100 * 360 - 90);
                                                        $innerR = 24;
                                                        $outerR = 50;
                                                        $dx1 = 50 + $innerR * cos($divAngleRad);
                                                        $dy1 = 50 + $innerR * sin($divAngleRad);
                                                        $dx2 = 50 + $outerR * cos($divAngleRad);
                                                        $dy2 = 50 + $outerR * sin($divAngleRad);
                                                    @endphp
                                                    <line x1="{{ number_format($dx1, 3) }}" y1="{{ number_format($dy1, 3) }}"
                                                          x2="{{ number_format($dx2, 3) }}" y2="{{ number_format($dy2, 3) }}"
                                                          stroke="#ffffff" stroke-width="0.6" />
                                                @endforeach
                                            @endif

                                            {{-- Curved text labels --}}
                                            @foreach ($donutSegments as $segmentIndex => $segment)
                                                @php
                                                    $nameText  = trim($segment['name']);
                                                    $valueText = $formatDistance($segment['total']) . ' km';

                                                    $nameFontSize  = min(3.5, max(1.4, $segment['percent'] * 0.14));
                                                    $valueFontSize = min(3.0, max(1.2, $nameFontSize * 0.82));

                                                    // Arc-length budget at each label radius
                                                    $nameAvail  = ($segment['percent'] / 100) * 2 * M_PI * 39 * 0.80;
                                                    $valueAvail = ($segment['percent'] / 100) * 2 * M_PI * 34 * 0.80;

                                                    $nameMaxChars  = max(0, (int) floor($nameAvail  / ($nameFontSize  * 0.55)));
                                                    $valueMaxChars = max(0, (int) floor($valueAvail / ($valueFontSize * 0.55)));

                                                    $nameDisplay  = mb_strlen($nameText)  <= $nameMaxChars  ? $nameText  : rtrim(mb_substr($nameText,  0, max(1, $nameMaxChars  - 1))) . '…';
                                                    $valueDisplay = mb_strlen($valueText) <= $valueMaxChars ? $valueText : rtrim(mb_substr($valueText, 0, max(1, $valueMaxChars - 1))) . '…';

                                                    // For bottom-half segments (25–75%), the inner arc (r=34) is
                                                    // visually higher on screen, so swap name/value paths.
                                                    $tMidPercent   = ($segment['from'] + $segment['to']) / 2;
                                                    $tIsFullCircle = ($segment['percent'] >= 99.9);
                                                    $tBottomHalf   = !$tIsFullCircle && ($tMidPercent > 25 && $tMidPercent < 75);
                                                    $nameArcId     = $tBottomHalf ? 'arc-v-' . $segmentIndex : 'arc-n-' . $segmentIndex;
                                                    $valueArcId    = $tBottomHalf ? 'arc-n-' . $segmentIndex : 'arc-v-' . $segmentIndex;
                                                    // Full circle: SVG circles trace CW from 3 o'clock; 75% = 12 o'clock (top)
                                                    $labelOffset   = $tIsFullCircle ? '75%' : '50%';

                                                    $showLabel = $nameMaxChars >= 3;
                                                @endphp
                                                @if ($showLabel)
                                                    <a href="{{ route('causes.show', $segment['cause']) }}" style="cursor: pointer;">
                                                        <text text-anchor="middle" dominant-baseline="auto"
                                                            style="font-size: {{ number_format($nameFontSize, 2, '.', '') }}px; font-weight: 700; fill: #ffffff; paint-order: stroke; stroke: rgba(10,20,40,0.8); stroke-width: 0.6;">
                                                            <textPath href="#{{ $nameArcId }}" startOffset="{{ $labelOffset }}">{{ $nameDisplay }}</textPath>
                                                        </text>
                                                        <text text-anchor="middle" dominant-baseline="auto"
                                                            style="font-size: {{ number_format($valueFontSize, 2, '.', '') }}px; font-weight: 600; fill: #ffffff; paint-order: stroke; stroke: rgba(10,20,40,0.8); stroke-width: 0.5;">
                                                            <textPath href="#{{ $valueArcId }}" startOffset="{{ $labelOffset }}">{{ $valueDisplay }}</textPath>
                                                        </text>
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
