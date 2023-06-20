<?php

namespace Tests\Feature;

use Spatie\Permission\Models\Role;
use App\Models\User;
use App\Models\Travel;
use Tests\TestCase;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AdminTravelTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_user_cannot_access_adding_travel(): void
    {
        $response = $this->postJson('/api/v1/travel/create');

        $response->assertStatus(401);
    }

    public function test_non_admin_user_cannot_access_adding_travel(): void
    {
        $this->seed(RoleSeeder::class);
        $user = User::factory()->create();
        $user->roles()->attach(Role::where('name', 'editor')->value('id'));
        $response = $this->actingAs($user)->postJson('/api/v1/travel/create');

        $response->assertStatus(403);
    }

    public function test_saves_travel_successfully_with_valid_data(): void
    {
        $this->seed(RoleSeeder::class);
        $user = User::factory()->create();
        $user->roles()->attach(Role::where('name', 'admin')->value('id'));

        $response = $this->actingAs($user)->postJson('/api/v1/travel/create', [
            'name' => 'Travel name',
        ]);
        $response->assertStatus(422);

        $response = $this->actingAs($user)->postJson('/api/v1/travel/create', [
            'name' => 'Travel name',
            'is_public' => 1,
            'description' => 'Some description',
            'no_of_days' => 5,
        ]);

        $response->assertStatus(201);

        $response = $this->get('/api/v1/travel');
        $response->assertJsonFragment(['name' => 'Travel name']);
    }

    public function test_updates_travel_successfully_with_valid_data(): void
    {
        $this->seed(RoleSeeder::class);
        $user = User::factory()->create();
        $user->roles()->attach(Role::where('name', 'admin')->value('id'));
        $travel = Travel::factory()->create(['name' => 'Travel name']);

        $response = $this->actingAs($user)->putJson('/api/v1/travel/' . $travel->id, [
            'name' => 'Travel name',
        ]);
        $response->assertStatus(422);

        $response = $this->actingAs($user)->putJson('/api/v1/travel/' . $travel->id, [
            'name' => 'Travel name updated',
            'is_public' => 1,
            'description' => 'Some description',
            'no_of_days' => 5,
        ]);

        $response->assertStatus(200);

        $response = $this->get('/api/v1/travel');
        $response->assertJsonFragment(['name' => 'Travel name updated']);
    }
}
