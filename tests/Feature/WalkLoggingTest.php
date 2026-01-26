<?php

namespace Tests\Feature;

use App\Models\Cause;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WalkLoggingTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_log_multiple_walks_per_day_with_any_positive_distance(): void
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
            'distance_km' => 5,
        ]);
        $response->assertSessionHas('status');

        $response = $this->post(route('walks.store', $cause), [
            'distance_km' => 4,
        ]);
        $response->assertSessionHas('status');

        $response = $this->post(route('walks.store', $cause), [
            'distance_km' => 6,
        ]);
        $response->assertSessionHas('status');
        $this->assertDatabaseCount('walks', 4);
    }
}
