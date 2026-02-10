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

    public function test_user_can_see_all_cause_leaderboards_even_without_participation(): void
    {
        $viewer = User::factory()->create();
        $participant = User::factory()->create();

        $participatedCause = Cause::create([
            'name' => 'Participated Cause',
            'description' => 'Has walks',
            'start_date' => now()->toDateString(),
        ]);

        $unparticipatedCause = Cause::create([
            'name' => 'Unparticipated Cause',
            'description' => 'Viewer has no walks here',
            'start_date' => now()->toDateString(),
        ]);

        Walk::create([
            'user_id' => $participant->id,
            'cause_id' => $participatedCause->id,
            'walked_on' => now()->toDateString(),
            'distance_km' => 5,
        ]);

        Walk::create([
            'user_id' => $participant->id,
            'cause_id' => $unparticipatedCause->id,
            'walked_on' => now()->toDateString(),
            'distance_km' => 7,
        ]);

        $response = $this->actingAs($viewer)->get(route('leaderboards.index'));

        $response->assertOk();
        $response->assertViewHas('causes', function ($causes) use ($participatedCause, $unparticipatedCause) {
            return $causes->contains('id', $participatedCause->id)
                && $causes->contains('id', $unparticipatedCause->id);
        });
    }
}
