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

        Walk::create([
            'user_id' => $user->id,
            'cause_id' => $cause->id,
            'walked_on' => $today,
            'distance_km' => $validated['distance_km'],
        ]);

        return back()->with('status', 'Walk logged successfully.');
    }
}
