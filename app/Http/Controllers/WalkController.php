<?php

namespace App\Http\Controllers;

use App\Models\Cause;
use App\Models\Walk;
use Illuminate\Http\Request;

class WalkController extends Controller
{
    public function store(Request $request, Cause $cause)
    {
        $user = $request->user();
        $today = now()->toDateString();

        $validated = $request->validate([
            'distance_km' => ['required', 'numeric', 'gt:0'],
        ]);

        $alreadyLogged = Walk::where('user_id', $user->id)
            ->where('cause_id', $cause->id)
            ->whereDate('walked_on', $today)
            ->exists();

        if ($alreadyLogged) {
            return back()->withErrors([
                'distance_km' => 'You have already logged a walk for today.',
            ]);
        }

        $lastWalk = Walk::where('user_id', $user->id)
            ->where('cause_id', $cause->id)
            ->orderByDesc('walked_on')
            ->first();

        if ($lastWalk && (float) $validated['distance_km'] <= (float) $lastWalk->distance_km) {
            return back()->withErrors([
                'distance_km' => 'Distance must be greater than your previous entry of '.$lastWalk->distance_km.' km.',
            ]);
        }

        Walk::create([
            'user_id' => $user->id,
            'cause_id' => $cause->id,
            'walked_on' => $today,
            'distance_km' => $validated['distance_km'],
        ]);

        return back()->with('status', 'Walk logged successfully.');
    }
}
