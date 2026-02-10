<?php

namespace App\Http\Controllers;

use App\Models\Cause;
use App\Models\Walk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function __invoke(Request $request)
    {
        $user = $request->user();
        $causes = Cause::orderByDesc('created_at')->get();

        $activeCause = null;
        $leaderboardRank = null;
        $leaderboardTotal = null;
        $activeCauseTotal = null;
        $weeklySparkline = [];
        $weekLabels = [];
        $causeStats = [];
        $initialCarouselIndex = 0;

        $weekStart = now()->startOfWeek(\Illuminate\Support\Carbon::MONDAY)->startOfDay();
        $weekEnd = now()->endOfWeek(\Illuminate\Support\Carbon::SUNDAY)->endOfDay();
        for ($i = 0; $i < 7; $i++) {
            $day = $weekStart->copy()->addDays($i);
            $weekLabels[] = $day->format('D');
        }

        $causeOrder = Walk::select('cause_id', DB::raw('MAX(created_at) as last_logged_at'))
            ->where('user_id', $user->id)
            ->groupBy('cause_id')
            ->orderByDesc('last_logged_at')
            ->pluck('cause_id')
            ->values();

        if ($causeOrder->isNotEmpty()) {
            $orderedCauses = Cause::whereIn('id', $causeOrder)->get()->sortBy(function ($cause) use ($causeOrder) {
                return $causeOrder->search($cause->id);
            })->values();

            foreach ($orderedCauses as $cause) {
                $totalDistance = Walk::where('user_id', $user->id)
                    ->where('cause_id', $cause->id)
                    ->sum('distance_km');

                $weeklyRaw = Walk::where('user_id', $user->id)
                    ->where('cause_id', $cause->id)
                    ->whereBetween(DB::raw('DATE(walked_on)'), [$weekStart->toDateString(), $weekEnd->toDateString()])
                    ->select(DB::raw('DATE(walked_on) as walked_on_date'), DB::raw('SUM(distance_km) as total_distance'))
                    ->groupBy(DB::raw('DATE(walked_on)'))
                    ->get()
                    ->keyBy('walked_on_date');

                $sparkline = [];
                for ($i = 0; $i < 7; $i++) {
                    $day = $weekStart->copy()->addDays($i);
                    $dateKey = $day->toDateString();
                    $sparkline[] = $weeklyRaw->has($dateKey)
                        ? (float) $weeklyRaw->get($dateKey)->total_distance
                        : 0.0;
                }

                $recentCauseWalks = Walk::where('user_id', $user->id)
                    ->where('cause_id', $cause->id)
                    ->orderByDesc('walked_on')
                    ->orderByDesc('created_at')
                    ->limit(5)
                    ->get();

                $hasTodayWalk = Walk::where('user_id', $user->id)
                    ->where('cause_id', $cause->id)
                    ->whereDate('walked_on', now()->toDateString())
                    ->exists();

                $causeStats[] = [
                    'cause' => $cause,
                    'total' => $totalDistance,
                    'sparkline' => $sparkline,
                    'maxDaily' => empty($sparkline) ? 0 : max($sparkline),
                    'recentWalks' => $recentCauseWalks,
                    'hasTodayWalk' => $hasTodayWalk,
                ];
            }

            $focusedCauseId = session('focus_cause_id');
            if ($focusedCauseId) {
                $focusedIndex = collect($causeStats)->search(fn ($stat) => (int) $stat['cause']->id === (int) $focusedCauseId);
                if ($focusedIndex !== false) {
                    $initialCarouselIndex = (int) $focusedIndex;
                }
            }

            $activeCause = $causeStats[$initialCarouselIndex]['cause'] ?? null;
            $activeCauseTotal = $causeStats[$initialCarouselIndex]['total'] ?? null;
            $weeklySparkline = $causeStats[$initialCarouselIndex]['sparkline'] ?? array_fill(0, 7, 0.0);

            if ($activeCause) {
                $leaderboard = Walk::select('user_id', DB::raw('SUM(distance_km) as total_distance'))
                    ->where('cause_id', $activeCause->id)
                    ->groupBy('user_id')
                    ->orderByDesc('total_distance')
                    ->get();

                $userEntry = $leaderboard->firstWhere('user_id', $user->id);
                if ($userEntry) {
                    $leaderboardTotal = $userEntry->total_distance;
                    $leaderboardRank = $leaderboard->search(fn ($entry) => $entry->user_id === $user->id);
                    $leaderboardRank = $leaderboardRank === false ? null : $leaderboardRank + 1;
                }
            }
        } else {
            $weeklySparkline = array_fill(0, 7, 0.0);
        }

        return view('dashboard', [
            'user' => $user,
            'causes' => $causes,
            'causeStats' => $causeStats,
            'activeCause' => $activeCause,
            'leaderboardRank' => $leaderboardRank,
            'leaderboardTotal' => $leaderboardTotal,
            'activeCauseTotal' => $activeCauseTotal,
            'weeklySparkline' => $weeklySparkline,
            'weekLabels' => $weekLabels,
            'initialCarouselIndex' => $initialCarouselIndex,
        ]);
    }
}
