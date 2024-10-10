<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Tour;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use App\Http\Resources\TourResource;
use App\Http\Requests\StoreTourRequest;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\UpdateTourRequest;

class TourController extends Controller
{
    public function index()
    {
        $tours = Tour::with('destination')->orderBy('created_at', 'desc')->get();
        return TourResource::collection($tours);
    }

    public function store(StoreTourRequest $request)
    {
        $validatedData = $request->validated();

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('tours');
            $validatedData['image'] = $imagePath;
        }

        $tour = Tour::create($validatedData);

        return response()->json(TourResource::make($tour), Response::HTTP_CREATED);
    }

    public function show(Tour $tour)
    {
        return TourResource::make($tour);
    }

    public function update(UpdateTourRequest $request, Tour $tour)
    {
        $validatedData = $request->validated();

        if ($request->hasFile('image')) {
            Storage::delete($tour->image);
            $imagePath = $request->file('image')->store('tours');
            $validatedData['image'] = $imagePath;
        }

        $tour->update($validatedData);

        return TourResource::make($tour);
    }

    public function destroy(Tour $tour)
    {
        if (!auth()->user()->isAdmin()) {
            return response()->json(['message' => 'Unauthorized'], Response::HTTP_UNAUTHORIZED);
        }

        $tour->image ? Storage::delete($tour->image) : null;

        $tour->delete();

        return response()->noContent();
    }
}
