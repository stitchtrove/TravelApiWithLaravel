<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Travel;
use App\Http\Resources\TravelResource;
use App\Http\Requests\TravelRequest;

class TravelController extends Controller
{
    /**
     * GET Travels
     *
     * Returns paginated list of travels.
     *
     * @queryParam page integer Page number. Example: 1
     *
     * @response {"data":[{"id":"9958e389-5edf-48eb-8ecd-e058985cf3ce","name":"First travel", ...}}
     */
    public function index()
    {
        $entries = Travel::where('is_public', true)->orderBy('created_at', 'asc')->paginate();

        return TravelResource::collection($entries);
    }

    /**
     * POST Travel
     *
     * Creates a new Travel record.
     *
     * @authenticated
     *
     * @response {"data":{"id":"996a36ca-2693-4901-9c55-7136e68d81d5","name":"My new travel 234","slug":"my-new-travel-234", ...}
     * @response 422 {"message":"The name has already been taken.","errors":{"name":["The name has already been taken."]}}
     */
    public function store(TravelRequest $request)
    {
        $travel = Travel::create($request->validated());
        $travel->save();

        return new TravelResource($travel);
    }

    /**
     * PUT Travel
     *
     * Updates new Travel record.
     *
     * @authenticated
     *
     * @response {"data":{"id":"996a36ca-2693-4901-9c55-7136e68d81d5","name":"My new travel 234", ...}
     * @response 422 {"message":"The name has already been taken.","errors":{"name":["The name has already been taken."]}}
     */
    public function update(Travel $travel, TravelRequest $request)
    {
        $travel->update($request->validated());

        return new TravelResource($travel);
    }
}
