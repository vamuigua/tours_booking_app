<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Booking;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use App\Http\Resources\BookingResource;
use App\Http\Requests\StoreBookingRequest;

class BookingController extends Controller
{
    public function index()
    {
        $bookingQuery = Booking::with('user', 'tour', 'tickets');

        if (auth()->user()->isAdmin()) {
            return BookingResource::collection($bookingQuery->get());
        }

        $userBookings = $bookingQuery->where('user_id', auth()->id())->get();

        return BookingResource::collection($userBookings);
    }

    public function store(StoreBookingRequest $request)
    {
        $booking = Booking::create($request->validated());

        $booking->load('user', 'tour', 'tickets');

        return response()->json(BookingResource::make($booking), Response::HTTP_CREATED);
    }

    public function show(Booking $booking)
    {
        $booking->loadMissing('user', 'tour', 'tickets');

        return BookingResource::make($booking);
    }
}
