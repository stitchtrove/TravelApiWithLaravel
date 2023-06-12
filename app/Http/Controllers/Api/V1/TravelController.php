<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Travel;
use App\Http\Resources\TravelResource;

class TravelController extends Controller
{
    public function index()
    {
        $entries = Travel::where('is_public', true)->orderBy('created_at', 'asc')->paginate();

        return TravelResource::collection($entries);
    }
}
