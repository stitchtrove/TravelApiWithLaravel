<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Tour;
use Illuminate\Validation\Rule;
use App\Models\Travel;
use App\Http\Resources\TourResource;
use App\Http\Requests\ToursListRequest;

class ToursController extends Controller
{
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
}
