<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Travel;
use App\Http\Resources\TravelResource;
use App\Http\Requests\TravelRequest;

class TravelController extends Controller
{
    public function index()
    {
        $entries = Travel::where('is_public', true)->orderBy('created_at', 'asc')->paginate();

        return TravelResource::collection($entries);
    }

    public function store(TravelRequest $request)
    {
        $travel = Travel::create($request->validated());
        $travel->save();

        return new TravelResource($travel);
    }

    public function update(Travel $travel, TravelRequest $request)
    {
        $travel->update($request->validated());

        return new TravelResource($travel);
    }
}
