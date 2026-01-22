<?php

namespace Tests\Feature;

use App\Models\Cause;
use App\Models\User;
use App\Models\Walk;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WalkLoggingTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_log_walk_once_per_day_and_must_increase_distance(): void
    {
        $user = User::factory()->create();
        $cause = Cause::create([
            'name' => 'Test Cause',
            'description' => 'Test description',
            'start_date' => now()->toDateString(),
        ]);

        $this->actingAs($user);

        $response = $this->post(route('walks.store', $cause), [
            'distance_km' => 5,
        ]);
        $response->assertSessionHas('status');
        $this->assertDatabaseHas('walks', [
            'user_id' => $user->id,
            'cause_id' => $cause->id,
            'distance_km' => 5,
        ]);

        $response = $this->post(route('walks.store', $cause), [
            'distance_km' => 6,
        ]);
        $response->assertSessionHasErrors('distance_km');

        $firstWalk = Walk::first();
        $firstWalk->update([
            'walked_on' => now()->subDay()->toDateString(),
        ]);

        $response = $this->post(route('walks.store', $cause), [
            'distance_km' => 4,
        ]);
        $response->assertSessionHasErrors('distance_km');

        $response = $this->post(route('walks.store', $cause), [
            'distance_km' => 6,
        ]);
        $response->assertSessionHas('status');
        $this->assertDatabaseCount('walks', 2);
    }
}
