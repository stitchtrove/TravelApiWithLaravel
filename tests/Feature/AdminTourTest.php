<?php

namespace Tests\Feature;

use Spatie\Permission\Models\Role;
use App\Models\User;
use Tests\TestCase;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Travel;

class AdminTourTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_user_cannot_access_adding_tour(): void
    {
        $travel = Travel::factory()->create(['is_public' => true]);

        $response = $this->postJson('/api/v1/travel/' . $travel->id . '/tours/create');

        $response->assertStatus(401);
    }

    public function test_non_admin_user_cannot_access_adding_tour(): void
    {
        $this->seed(RoleSeeder::class);
        $user = User::factory()->create();
        $user->roles()->attach(Role::where('name', 'editor')->value('id'));
        $travel = Travel::factory()->create(['is_public' => true]);

        $response = $this->actingAs($user)->postJson('/api/v1/travel/' . $travel->id . '/tours/create');

        $response->assertStatus(403);
    }

    public function test_saves_tour_successfully_with_valid_data(): void
    {
        $this->seed(RoleSeeder::class);
        $user = User::factory()->create();
        $user->roles()->attach(Role::where('name', 'admin')->value('id'));
        $travel = Travel::factory()->create(['name' => 'Travel name', 'is_public' => true]);

        $response = $this->actingAs($user)->postJson('/api/v1/travel/' . $travel->id . '/tours/create', [
            'travel_id' => $travel->id,
            'name' => 'Tour name',
            'start_date' => now()->addDays(2),
            'end_date' => now()->addDays(3),
            'price' => 500,
        ]);

        $response->assertStatus(200);

        $response = $this->get('/api/v1/travel/' . $travel->slug . '/tours');
        $response->assertJsonFragment(['name' => 'Tour name']);
    }
}
