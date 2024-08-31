<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Tour;
use App\Models\User;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use App\Http\Resources\TourResource;
use App\Http\Requests\StoreTourRequest;
use App\Http\Requests\UpdateTourRequest;

class TourController extends Controller
{
    public function index()
    {
        return TourResource::collection(Tour::with('destination')->get());
    }

    public function store(StoreTourRequest $request)
    {
        $tour = Tour::create($request->validated());

        return response()->json(TourResource::make($tour), Response::HTTP_CREATED);
    }

    public function show(Tour $tour)
    {
        return TourResource::make($tour);
    }

    public function update(UpdateTourRequest $request, Tour $tour)
    {
        $tour->update($request->validated());

        return TourResource::make($tour);
    }

    public function destroy(Tour $tour)
    {
        if (!auth()->user()->isAdmin()) {
            return response()->json(['message' => 'Unauthorized'], Response::HTTP_UNAUTHORIZED);
        }

        $tour->delete();

        return response()->noContent();
    }
}
