<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Tour;
use Illuminate\Validation\Rule;
use App\Models\Travel;
use App\Http\Resources\TourResource;
use App\Http\Requests\ToursListRequest;
use App\Http\Requests\TourRequest;

class ToursController extends Controller
{
    /**
     * GET Travel Tours
     *
     * Returns paginated list of tours by travel slug.
     *
     * @urlParam travel_slug string Travel slug. Example: "first-travel"
     *
     * @bodyParam priceFrom number. Example: "123.45"
     * @bodyParam priceTo number. Example: "234.56"
     * @bodyParam dateFrom date. Example: "2023-06-01"
     * @bodyParam dateTo date. Example: "2023-07-01"
     * @bodyParam sortBy string. Example: "price"
     * @bodyParam sortOrder string. Example: "asc" or "desc"
     *
     * @response {"data":[{"id":"9958e389-5edf-48eb-8ecd-e058985cf3ce","name":"Tour on Sunday","starting_date":"2023-06-11","ending_date":"2023-06-16", ...}
     *
     */
    public function index(Travel $travel, ToursListRequest $request)
    {
        $request->validate([
            'priceFrom' => 'numeric',
            'priceTo' => 'numeric',
            'dateFrom' => 'date',
            'dateTo' => 'date',
            'sortBy' => Rule::in(['price']),
            'sortOrder' => Rule::in(['asc', 'desc']),
        ], [
            'sortBy' => "The 'sortBy' parameter accepts only 'price' value",
            'sortOrder' => "The 'sortOrder' parameter accepts only 'asc' and 'desc' values",
        ]);

        $entries = $travel->tours()
            ->when($request->priceFrom, function ($query) use ($request) {
                $query->where('price', '>=', $request->priceFrom * 100);
            })
            ->when($request->priceTo, function ($query) use ($request) {
                $query->where('price', '<=', $request->priceTo * 100);
            })
            ->when($request->dateFrom, function ($query) use ($request) {
                $query->where('start_date', '>=', $request->dateFrom);
            })
            ->when($request->dateTo, function ($query) use ($request) {
                $query->where('start_date', '<=', $request->dateTo);
            })
            ->when($request->sortBy, function ($query) use ($request) {
                $query->orderBy($request->sortBy, $request->sortOrder);
            })
            ->orderBy('start_date', 'asc')
            ->paginate();

        return TourResource::collection($entries);
    }

    public function store(Travel $travel, TourRequest $request)
    {
        $travel->tours()->create($request->validated());

        return new TourResource($travel);
    }
}
