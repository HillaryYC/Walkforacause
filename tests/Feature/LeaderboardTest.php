<?php

namespace Tests\Feature;

use App\Models\Cause;
use App\Models\User;
use App\Models\Walk;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LeaderboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_leaderboard_orders_by_distance(): void
    {
        $cause = Cause::create([
            'name' => 'Leaderboard Cause',
            'description' => 'Test',
            'start_date' => now()->toDateString(),
        ]);

        $userA = User::factory()->create();
        $userB = User::factory()->create();

        Walk::create([
            'user_id' => $userA->id,
            'cause_id' => $cause->id,
            'walked_on' => now()->subDay()->toDateString(),
            'distance_km' => 3,
        ]);

        Walk::create([
            'user_id' => $userB->id,
            'cause_id' => $cause->id,
            'walked_on' => now()->subDay()->toDateString(),
            'distance_km' => 8,
        ]);

        $response = $this->actingAs($userA)
            ->get(route('causes.show', $cause));

        $response->assertOk();
        $response->assertViewHas('leaderboard', function ($leaderboard) use ($userB, $userA) {
            return $leaderboard->first()->user_id === $userB->id
                && $leaderboard->last()->user_id === $userA->id;
        });
    }
}
