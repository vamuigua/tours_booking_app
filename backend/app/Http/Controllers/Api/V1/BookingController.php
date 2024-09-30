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

        if ($search = request('search')) {
            $bookingQuery->whereHas('user', function ($query) use ($search) {
                $query->where('name', 'LIKE', "%{$search}%");
            })
                ->orWhereHas('tour', function ($query) use ($search) {
                    $query->where('name', 'LIKE', "%{$search}%");
                });
        }

        if ($status = request('status')) {
            $bookingQuery->where('status', $status);
        }

        if (auth()->user()->isAdmin()) {
            return BookingResource::collection($bookingQuery->paginate(5));
        }

        $userBookings = $bookingQuery->where('user_id', auth()->id())->paginate(5);

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

    public function showUserBooking($tour_id)
    {
        $booking = Booking::where('user_id', auth()->id())->where('tour_id', $tour_id)->first();

        if ($booking) {
            return BookingResource::make($booking);
        }

        return response()->json([]);
    }
}
