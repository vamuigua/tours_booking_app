<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\User;
use App\Models\Destination;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use App\Http\Resources\DestinationResource;
use App\Http\Requests\StoreDestinationRequest;
use App\Http\Requests\UpdateDestinationRequest;

class DestinationController extends Controller
{
    public function index()
    {
        return DestinationResource::collection(Destination::all());
    }

    public function store(StoreDestinationRequest $request)
    {
        $destination = Destination::create($request->validated());

        return response()->json(DestinationResource::make($destination), Response::HTTP_CREATED);
    }

    public function show(Destination $destination)
    {
        return DestinationResource::make($destination);
    }

    public function update(UpdateDestinationRequest $request, Destination $destination)
    {
        $destination->update($request->validated());

        return response()->json(DestinationResource::make($destination), Response::HTTP_OK);
    }

    public function destroy(Destination $destination)
    {
        if (auth()->user()->role !== User::ROLE_ADMIN) {
            return response()->json(['message' => 'Unauthorized'], Response::HTTP_UNAUTHORIZED);
        }

        $destination->delete();

        return response()->noContent();
    }
}
