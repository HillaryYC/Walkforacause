<?php

namespace Tests\Feature;

use App\Models\Cause;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminCauseAccessTest extends TestCase
{
    use RefreshDatabase;

    public function test_non_admin_cannot_access_admin_routes(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get(route('admin.causes.index'))
            ->assertStatus(403);

        $this->actingAs($user)
            ->post(route('admin.causes.store'), [
                'name' => 'Blocked',
                'start_date' => now()->toDateString(),
            ])
            ->assertStatus(403);
    }

    public function test_admin_can_create_cause(): void
    {
        $admin = User::factory()->admin()->create();

        $this->actingAs($admin)
            ->post(route('admin.causes.store'), [
                'name' => 'New Cause',
                'description' => 'Test',
                'start_date' => now()->toDateString(),
            ])
            ->assertRedirect(route('causes.index'));

        $this->assertDatabaseHas('causes', [
            'name' => 'New Cause',
        ]);
    }
}
