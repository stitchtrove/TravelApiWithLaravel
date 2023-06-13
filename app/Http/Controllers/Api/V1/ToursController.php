<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Tour;
use App\Models\Travel;
use App\Http\Resources\TourResource;

class ToursController extends Controller
{
    public function index(Travel $travel)
    {
        $entries = $travel->tours()->orderBy('start_date')->paginate();

        return TourResource::collection($entries);
    }
}
