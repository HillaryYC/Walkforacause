<?php

namespace App\Http\Controllers;

use App\Models\Cause;
use App\Models\Walk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CauseController extends Controller
{
    public function index()
    {
        $causes = Cause::orderByDesc('created_at')->get();

        return view('causes.index', [
            'causes' => $causes,
        ]);
    }

    public function show(Request $request, Cause $cause)
    {
        $user = $request->user();
        $latestWalk = null;
        $hasLoggedToday = false;

        if ($user) {
            $latestWalk = Walk::where('user_id', $user->id)
                ->where('cause_id', $cause->id)
                ->orderByDesc('walked_on')
                ->first();

            $hasLoggedToday = Walk::where('user_id', $user->id)
                ->where('cause_id', $cause->id)
                ->whereDate('walked_on', now()->toDateString())
                ->exists();
        }

        $leaderboard = Walk::select('user_id', DB::raw('MAX(distance_km) as total_distance'))
            ->where('cause_id', $cause->id)
            ->groupBy('user_id')
            ->orderByDesc('total_distance')
            ->with('user')
            ->get();

        return view('causes.show', [
            'cause' => $cause,
            'leaderboard' => $leaderboard,
            'latestWalk' => $latestWalk,
            'hasLoggedToday' => $hasLoggedToday,
        ]);
    }

    public function leaderboards()
    {
        $causes = Cause::orderByDesc('created_at')->get();
        $leaderboards = [];

        foreach ($causes as $cause) {
            $leaderboards[$cause->id] = Walk::select('user_id', DB::raw('MAX(distance_km) as total_distance'))
                ->where('cause_id', $cause->id)
                ->groupBy('user_id')
                ->orderByDesc('total_distance')
                ->with('user')
                ->limit(10)
                ->get();
        }

        return view('leaderboards.index', [
            'causes' => $causes,
            'leaderboards' => $leaderboards,
        ]);
    }
}
