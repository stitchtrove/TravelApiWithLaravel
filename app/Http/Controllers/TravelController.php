<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Travel;
use App\Http\Resources\TravelResource;

class TravelController extends Controller
{
    public function index()
    {
        $entries = TravelResource::collection(Travel::where('is_public', '1')->orderBy('created_at', 'asc')->get());

        return response()->json($entries);
    }
}
