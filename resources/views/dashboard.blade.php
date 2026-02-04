<x-app-layout :hide-nav="false" :hide-header="true">
    @php
        $progressPercent = 0;
        $hasActiveWalks = $activeCause && $activeCauseTotal && $activeCauseTotal > 0;
        $formatDistance = function ($value) {
            return rtrim(rtrim(number_format($value, 2), '0'), '.');
        };
        $carouselCount = count($causeStats ?? []);
    @endphp

    <div class="grid gap-6 lg:grid-cols-[1fr,280px]">
        <section class="space-y-6">
            <div class="relative rounded-3xl border border-white/60 bg-white/70 p-6 shadow-xl shadow-slate-200/70 backdrop-blur lg:p-8">
                <div
                    class="grid gap-6 lg:grid-cols-[1fr,320px]"
                    x-data="{
                        index: 0,
                        total: {{ $carouselCount }},
                        touchStartX: null,
                        touchStartY: null,
                        next() { this.index = (this.index + 1) % this.total; },
                        prev() { this.index = (this.index - 1 + this.total) % this.total; },
                        onTouchStart(event) {
                            this.touchStartX = event.touches[0].clientX;
                            this.touchStartY = event.touches[0].clientY;
                        },
                        onTouchEnd(event) {
                            if (this.touchStartX === null) return;
                            const deltaX = event.changedTouches[0].clientX - this.touchStartX;
                            const deltaY = event.changedTouches[0].clientY - this.touchStartY;
                            if (Math.abs(deltaX) > 40 && Math.abs(deltaX) > Math.abs(deltaY)) {
                                deltaX < 0 ? this.next() : this.prev();
                            }
                            this.touchStartX = null;
                            this.touchStartY = null;
                        }
                    }"
                    x-on:touchstart.passive="onTouchStart($event)"
                    x-on:touchend.passive="onTouchEnd($event)"
                >
                    <div class="space-y-6">
                        <div class="rounded-2xl border border-slate-100 bg-white p-6 shadow-sm">
                            @if ($carouselCount === 0)
                                <p class="text-sm text-blue-500">No walks yet</p>
                            @else
                                <div class="relative min-h-[420px] overflow-hidden">
                                    @foreach ($causeStats as $slideIndex => $stat)
                                        @php
                                            $slideMaxDaily = $stat['maxDaily'] ?? 0;
                                        @endphp
                                        <div
                                            x-show="index === {{ $slideIndex }}"
                                            x-cloak
                                            class="absolute inset-0"
                                            x-transition:enter="transition ease-out duration-700"
                                            x-transition:enter-start="opacity-0 translate-x-10"
                                            x-transition:enter-end="opacity-100 translate-x-0"
                                            x-transition:leave="transition ease-in duration-500"
                                            x-transition:leave-start="opacity-100 translate-x-0"
                                            x-transition:leave-end="opacity-0 -translate-x-10"
                                        >
                                            <div class="flex flex-wrap items-center justify-between gap-6">
                                            <div>
                                                <p class="text-xs uppercase tracking-[0.2em] text-slate-400">Total Distance</p>
                                                <p class="mt-2 text-3xl font-semibold text-slate-900" @if ($slideIndex === 0) id="ring-total-text" @endif>{{ $formatDistance($stat['total']) }} km</p>
                                            </div>
                                            @php
                                                $ringFill = $stat['hasTodayWalk'] ? 100 : 0;
                                            @endphp
                                            <div
                                                @if ($slideIndex === 0) id="ring-indicator" @endif
                                                class="relative h-40 w-40 rounded-full sm:mx-0 mx-auto"
                                                style="background: conic-gradient(#1d4ed8 {{ $ringFill }}%, #e2e8f0 0);"
                                                data-target="{{ $stat['total'] ?? 0 }}"
                                                data-previous="{{ $slideIndex === 0 ? session('previous_total', 0) : 0 }}"
                                                data-animate="{{ $slideIndex === 0 && session('walk_logged') ? 'true' : 'false' }}"
                                                data-ring-fill="{{ $ringFill }}"
                                            >
                                                    <div class="absolute inset-3 rounded-full bg-white"></div>
                                                    <div class="absolute inset-0 flex items-center justify-center">
                                                        <div class="text-center">
                                                            <p class="text-2xl font-semibold text-slate-900" @if ($slideIndex === 0) id="ring-center-text" @endif>{{ $formatDistance($stat['total']) }} km</p>
                                                            <a
                                                                href="{{ route('causes.show', $stat['cause']) }}"
                                                                class="text-xs font-semibold text-blue-500 hover:underline"
                                                                @if ($slideIndex === 0) id="ring-cause-name" @endif
                                                            >
                                                                {{ $stat['cause']->name }}
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="mt-6">
                                                <div class="flex items-end gap-2" @if ($slideIndex === 0) data-sparkline-max="{{ $slideMaxDaily }}" @endif>
                                                    @foreach ($stat['sparkline'] as $sparkIndex => $value)
                                                        @php
                                                            $heightPercent = $slideMaxDaily > 0 ? round(($value / $slideMaxDaily) * 100) : 0;
                                                        @endphp
                                                        <div class="flex w-full flex-col items-center gap-2">
                                                            <div class="flex h-20 w-full items-end rounded-full bg-slate-100 px-1">
                                                                <div
                                                                    class="w-full rounded-full bg-blue-900"
                                                                    style="height: {{ $heightPercent }}%;"
                                                                    @if ($slideIndex === 0) data-sparkline-bar data-target="{{ $value }}" @endif
                                                                ></div>
                                                            </div>
                                                            <span class="text-[10px] uppercase tracking-wide text-slate-400">{{ $weekLabels[$sparkIndex] ?? '' }}</span>
                                                        </div>
                                                    @endforeach
                                                </div>
                                                @if ($slideMaxDaily == 0)
                                                    <p class="mt-3 text-xs text-blue-500">No walks yet this week</p>
                                                @else
                                                    <p class="mt-3 text-xs text-slate-400">This week (Mon-Sun)</p>
                                                @endif
                                                @if ($carouselCount > 1)
                                                    <div class="mt-4 flex items-center justify-between text-xs text-slate-400">
                                                        <button type="button" class="rounded-full px-2 py-1 hover:text-slate-700" x-on:click="prev()">Prev</button>
                                                        <div class="flex items-center gap-2">
                                                            @for ($i = 0; $i < $carouselCount; $i++)
                                                                <button
                                                                    type="button"
                                                                    class="h-2 w-2 rounded-full"
                                                                    :class="index === {{ $i }} ? 'bg-blue-900' : 'bg-slate-200'"
                                                                    x-on:click="index = {{ $i }}"
                                                                ></button>
                                                            @endfor
                                                        </div>
                                                        <button type="button" class="rounded-full px-2 py-1 hover:text-slate-700" x-on:click="next()">Next</button>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>

                    </div>

                    <div class="rounded-2xl border border-slate-100 bg-white px-5 py-6 shadow-lg">
                        <div class="flex items-center justify-between">
                            <h2 class="text-sm font-semibold text-slate-900">Recent Activity</h2>
                        </div>
                        <div class="mt-4">
                            @if ($carouselCount === 0)
                                <div class="rounded-xl bg-slate-50 px-4 py-3 text-sm text-blue-500">No activity yet</div>
                            @else
                                <div class="relative min-h-[360px] overflow-hidden">
                                    @foreach ($causeStats as $slideIndex => $stat)
                                        <div
                                            x-show="index === {{ $slideIndex }}"
                                            x-cloak
                                            class="absolute inset-0"
                                            x-transition:enter="transition ease-out duration-700"
                                            x-transition:enter-start="opacity-0 translate-x-10"
                                            x-transition:enter-end="opacity-100 translate-x-0"
                                            x-transition:leave="transition ease-in duration-500"
                                            x-transition:leave-start="opacity-100 translate-x-0"
                                            x-transition:leave-end="opacity-0 -translate-x-10"
                                        >
                                            @if ($stat['recentWalks']->isEmpty())
                                                <div class="rounded-xl bg-slate-50 px-4 py-3 text-sm text-blue-500">No activity yet</div>
                                            @else
                                                <div class="divide-y divide-slate-100">
                                                    @foreach ($stat['recentWalks'] as $walk)
                                                        <div class="flex items-center justify-between py-3">
                                                            <div>
                                                                <p class="text-sm font-semibold text-slate-900">{{ $formatDistance($walk->distance_km) }} km</p>
                                                                <p class="text-xs text-blue-500">{{ $stat['cause']->name }}</p>
                                                            </div>
                                                            <span class="text-xs text-blue-500">{{ \Illuminate\Support\Carbon::parse($walk->walked_on)->toFormattedDateString() }}</span>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                            @if ($carouselCount > 1)
                                <div class="mt-4 flex items-center justify-between text-xs text-slate-400">
                                    <button type="button" class="rounded-full px-2 py-1 hover:text-slate-700" x-on:click="prev()">Prev</button>
                                    <div class="flex items-center gap-2">
                                        @for ($i = 0; $i < $carouselCount; $i++)
                                            <button
                                                type="button"
                                                class="h-2 w-2 rounded-full"
                                                :class="index === {{ $i }} ? 'bg-blue-900' : 'bg-slate-200'"
                                                x-on:click="index = {{ $i }}"
                                            ></button>
                                        @endfor
                                    </div>
                                    <button type="button" class="rounded-full px-2 py-1 hover:text-slate-700" x-on:click="next()">Next</button>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <aside class="space-y-5">
            <div
                class="rounded-3xl bg-white/75 p-5 shadow-lg shadow-slate-200/60 backdrop-blur"
                x-data="{ panel: 0 }"
            >
                <div class="flex items-center justify-between">
                    <p class="text-xs uppercase tracking-[0.2em] text-slate-400">Quick Actions &amp; Insights</p>
                    <div class="flex items-center gap-2 text-xs text-blue-500">
                        <button type="button" x-on:click="panel = 0" :class="panel === 0 ? 'font-semibold' : ''">Active</button>
                        <span class="text-slate-300">•</span>
                        <button type="button" x-on:click="panel = 1" :class="panel === 1 ? 'font-semibold' : ''">Recent</button>
                    </div>
                </div>

                <div class="mt-4">
                    <div
                        x-show="panel === 0"
                        x-cloak
                        x-transition:enter="transition ease-out duration-500"
                        x-transition:enter-start="opacity-0 translate-y-2"
                        x-transition:enter-end="opacity-100 translate-y-0"
                        x-transition:leave="transition ease-in duration-400"
                        x-transition:leave-start="opacity-100 translate-y-0"
                        x-transition:leave-end="opacity-0 -translate-y-2"
                    >
                        <h2 class="text-lg font-semibold text-slate-900">Active Cause</h2>
                        <div class="mt-4 rounded-2xl border border-slate-100 bg-white px-4 py-4 shadow-sm">
                            @if ($activeCause)
                                <p class="text-sm font-semibold text-slate-800">{{ $activeCause->name }}</p>
                                @if ($activeCause->description)
                                    <p class="mt-1 text-xs text-blue-500">{{ $activeCause->description }}</p>
                                @endif
                                <a href="{{ route('causes.show', $activeCause) }}" class="mt-4 inline-flex w-full items-center justify-center rounded-xl bg-blue-900 py-2 text-xs font-semibold uppercase tracking-wide text-white">View Cause</a>
                            @else
                                <p class="text-sm text-blue-500">No active cause selected</p>
                            @endif
                        </div>
                    </div>

                    <div
                        x-show="panel === 1"
                        x-cloak
                        x-transition:enter="transition ease-out duration-500"
                        x-transition:enter-start="opacity-0 translate-y-2"
                        x-transition:enter-end="opacity-100 translate-y-0"
                        x-transition:leave="transition ease-in duration-400"
                        x-transition:leave-start="opacity-100 translate-y-0"
                        x-transition:leave-end="opacity-0 -translate-y-2"
                    >
                        <h2 class="text-lg font-semibold text-slate-900">Recently Added</h2>
                        <div class="mt-4 space-y-3 max-h-72 overflow-y-auto pr-1">
                            @forelse ($causes->take(3) as $cause)
                                <a href="{{ route('causes.show', $cause) }}" class="block rounded-2xl border border-slate-100 bg-white px-4 py-3 shadow-sm">
                                    <p class="text-sm font-semibold text-slate-900">{{ $cause->name }}</p>
                                    <p class="mt-1 text-xs text-blue-500">{{ $cause->description ?: 'No description yet.' }}</p>
                                </a>
                            @empty
                                <div class="rounded-2xl border border-slate-100 bg-white px-4 py-3 text-sm text-blue-500">No causes available yet.</div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>

            <div class="rounded-3xl bg-white/75 p-5 shadow-lg shadow-slate-200/60 backdrop-blur">
                <h2 class="text-sm font-semibold text-slate-900">Your Rank</h2>
                <div class="mt-4 space-y-3">
                    <div class="flex items-center justify-between rounded-2xl border border-slate-100 bg-white px-4 py-3">
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
                    <a href="{{ route('leaderboards.index') }}" class="inline-flex w-full items-center justify-center rounded-xl border border-slate-200 py-2 text-xs font-semibold uppercase tracking-wide text-slate-600">Open Leaderboards</a>
                </div>
            </div>
        </aside>
    </div>

    @if (session('walk_logged'))
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                const ring = document.getElementById('ring-indicator');
                if (!ring) return;

                const target = Number(ring.dataset.target || 0);
                const previous = Number(ring.dataset.previous || 0);
                const shouldAnimate = ring.dataset.animate === 'true';

                if (!shouldAnimate || target <= 0) return;

                const ringTotalText = document.getElementById('ring-total-text');
                const ringCenterText = document.getElementById('ring-center-text');
                const duration = 900;
                const startValue = Math.max(0, Math.min(previous, target));
                const startTime = performance.now();
                const ringFillTarget = Number(ring.dataset.ringFill || 0);

                const formatDistance = (value) => {
                    const fixed = value.toFixed(2);
                    return fixed.replace(/\.00$/, '').replace(/(\.\d)0$/, '$1');
                };

                const easeOut = (t) => 1 - Math.pow(1 - t, 3);

                const updateRing = (now) => {
                    const elapsed = Math.min((now - startTime) / duration, 1);
                    const progress = easeOut(elapsed);
                    const current = startValue + (target - startValue) * progress;
                    const currentText = `${formatDistance(current)} km`;

                    const fill = Math.min(progress * ringFillTarget, ringFillTarget);
                    ring.style.background = `conic-gradient(#1d4ed8 ${fill}%, #e2e8f0 0)`;
                    if (ringTotalText) ringTotalText.textContent = currentText;
                    if (ringCenterText) ringCenterText.textContent = currentText;

                    if (elapsed < 1) {
                        requestAnimationFrame(updateRing);
                    }
                };

                requestAnimationFrame(updateRing);

                const sparkline = document.querySelector('[data-sparkline-max]');
                const maxDaily = sparkline ? Number(sparkline.dataset.sparklineMax || 0) : 0;
                const bars = document.querySelectorAll('[data-sparkline-bar]');
                bars.forEach((bar) => {
                    const value = Number(bar.dataset.target || 0);
                    bar.style.height = '0%';
                    const barStart = performance.now();
                    const barDuration = 700;

                    const animateBar = (now) => {
                        const elapsed = Math.min((now - barStart) / barDuration, 1);
                        const progress = easeOut(elapsed);
                        const targetHeight = maxDaily > 0 ? (value / maxDaily) * 100 : 0;
                        const height = Math.min(100, progress * targetHeight);
                        bar.style.height = `${height}%`;
                        if (elapsed < 1) requestAnimationFrame(animateBar);
                    };

                    requestAnimationFrame(animateBar);
                });
            });
        </script>
    @endif
</x-app-layout>
