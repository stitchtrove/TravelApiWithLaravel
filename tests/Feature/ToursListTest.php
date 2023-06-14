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

    public function test_tour_price_is_shown_correctly(): void
    {
        $travel = Travel::factory()->create();
        Tour::factory()->create([
            'travel_id' => $travel->id,
            'price' => 123.45,
        ]);

        $response = $this->get('/api/v1/travel/' . $travel->slug . '/tours');

        $response->assertStatus(200);
        $response->assertJsonCount(1, 'data');
        $response->assertJsonFragment(['price' => '123.45']);
    }

    public function test_tours_list_filters_by_price_correctly(): void
    {
        $travel = Travel::factory()->create();
        $expensiveTour = Tour::factory()->create([
            'travel_id' => $travel->id,
            'price' => 200,
        ]);
        $cheapTour = Tour::factory()->create([
            'travel_id' => $travel->id,
            'price' => 100,
        ]);

        $endpoint = '/api/v1/travel/' . $travel->slug . '/tours';

        $response = $this->get($endpoint . '?priceFrom=100');
        $response->assertJsonCount(2, 'data');
        $response->assertJsonFragment(['id' => $cheapTour->id]);
        $response->assertJsonFragment(['id' => $expensiveTour->id]);

        $response = $this->get($endpoint . '?priceFrom=150');
        $response->assertJsonCount(1, 'data');
        $response->assertJsonMissing(['id' => $cheapTour->id]);
        $response->assertJsonFragment(['id' => $expensiveTour->id]);

        $response = $this->get($endpoint . '?priceFrom=250');
        $response->assertJsonCount(0, 'data');

        $response = $this->get($endpoint . '?priceTo=200');
        $response->assertJsonCount(2, 'data');
        $response->assertJsonFragment(['id' => $cheapTour->id]);
        $response->assertJsonFragment(['id' => $expensiveTour->id]);

        $response = $this->get($endpoint . '?priceTo=150');
        $response->assertJsonCount(1, 'data');
        $response->assertJsonMissing(['id' => $expensiveTour->id]);
        $response->assertJsonFragment(['id' => $cheapTour->id]);

        $response = $this->get($endpoint . '?priceTo=50');
        $response->assertJsonCount(0, 'data');

        $response = $this->get($endpoint . '?priceFrom=150&priceTo=250');
        $response->assertJsonCount(1, 'data');
        $response->assertJsonMissing(['id' => $cheapTour->id]);
        $response->assertJsonFragment(['id' => $expensiveTour->id]);
    }

    public function test_tours_list_filters_by_start_date_correctly(): void
    {
        $travel = Travel::factory()->create();
        $laterTour = Tour::factory()->create([
            'travel_id' => $travel->id,
            'start_date' => now()->addDays(2),
            'end_date' => now()->addDays(3),
        ]);
        $earlierTour = Tour::factory()->create([
            'travel_id' => $travel->id,
            'start_date' => now(),
            'end_date' => now()->addDays(1),
        ]);

        $endpoint = '/api/v1/travel/' . $travel->slug . '/tours';

        $response = $this->get($endpoint . '?dateFrom=' . now());
        $response->assertJsonCount(2, 'data');
        $response->assertJsonFragment(['id' => $earlierTour->id]);
        $response->assertJsonFragment(['id' => $laterTour->id]);

        $response = $this->get($endpoint . '?dateFrom=' . now()->addDay());
        $response->assertJsonCount(1, 'data');
        $response->assertJsonMissing(['id' => $earlierTour->id]);
        $response->assertJsonFragment(['id' => $laterTour->id]);

        $response = $this->get($endpoint . '?dateFrom=' . now()->addDays(5));
        $response->assertJsonCount(0, 'data');

        $response = $this->get($endpoint . '?dateTo=' . now()->addDays(5));
        $response->assertJsonCount(2, 'data');
        $response->assertJsonFragment(['id' => $earlierTour->id]);
        $response->assertJsonFragment(['id' => $laterTour->id]);

        $response = $this->get($endpoint . '?dateTo=' . now()->addDay());
        $response->assertJsonCount(1, 'data');
        $response->assertJsonMissing(['id' => $laterTour->id]);
        $response->assertJsonFragment(['id' => $earlierTour->id]);

        $response = $this->get($endpoint . '?dateTo=' . now()->subDay());
        $response->assertJsonCount(0, 'data');

        $response = $this->get($endpoint . '?dateFrom=' . now()->addDay() . '&dateTo=' . now()->addDays(5));
        $response->assertJsonCount(1, 'data');
        $response->assertJsonMissing(['id' => $earlierTour->id]);
        $response->assertJsonFragment(['id' => $laterTour->id]);
    }

    public function test_tour_list_returns_validation_errors(): void
    {
        $travel = Travel::factory()->create();

        $response = $this->getJson('/api/v1/travel/' . $travel->slug . '/tours?dateFrom=abcde');
        $response->assertStatus(422);

        $response = $this->getJson('/api/v1/travel/' . $travel->slug . '/tours?priceFrom=abcde');
        $response->assertStatus(422);
    }
}
