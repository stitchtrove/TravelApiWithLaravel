<?php

namespace Tests\Feature;

use App\Models\Travel;
use App\Models\Tour;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Support\Str;
use Database\Factories\ToursFactory;

class ToursListTest extends TestCase
{
    use RefreshDatabase;

    public function test_tours_list_returns_pagination(): void
    {
        // We need to create 15 + 1 records
        $travel = Travel::factory()->create();

        Tour::factory(16)->create(['travel_id' => $travel->id]);

        $response = $this->get('/api/v1/travel/' . $travel->slug . '/tours/');

        $response->assertStatus(200);

        // We check if data returns 15 records and not 16
        $response->assertJsonCount(15, 'data');

        // We also check there are 2 pages in total
        $response->assertJsonPath('meta.last_page', 2);
    }

    public function test_travels_list_shows_only_related_tours()
    {
        // We create two Travels and a tour each
        $confirmTravel = Travel::factory()->create(['is_public' => true]);
        $confirmTour = Tour::factory()->create(['travel_id' => $confirmTravel->id]);
        $denyTravel = Travel::factory()->create(['is_public' => true]);
        $denyTour = Tour::factory()->create(['travel_id' => $denyTravel->id]);

        // We only want to see one tour for the travel
        $response = $this->get('/api/v1/travel/' . $confirmTravel->slug . '/tours/');

        $response->assertStatus(200);

        // We check that only the related record is returned
        $response->assertJsonCount(1, 'data');
        $response->assertJsonFragment(['id' => $confirmTour->id]);
        $response->assertJsonMissing(['id' => $denyTour->id]);
    }
}
