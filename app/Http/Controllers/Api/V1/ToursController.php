<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Tour;
use App\Models\Travel;
use App\Http\Resources\TourResource;

class ToursController extends Controller
{
    public function index($slug)
    {
        $travel = Travel::where('slug', $slug)->first();
        $entries = Tour::where('travel_id', $travel->id)->orderBy('created_at', 'asc')->paginate();

        return TourResource::collection($entries);
    }
}
