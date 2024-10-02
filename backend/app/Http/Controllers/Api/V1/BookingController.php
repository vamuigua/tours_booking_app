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
        $bookingQuery = $this->buildBaseBookingQuery();

        if ($search = request('search')) {
            $this->applySearchFilter($bookingQuery, $search);
        }

        if ($status = request('status')) {
            $bookingQuery->where('status', $status);
        }

        $this->applyOrdering($bookingQuery);

        if (auth()->user()->isAdmin()) {
            return BookingResource::collection($bookingQuery->paginate(5));
        }

        $userBookings = $bookingQuery->where('user_id', auth()->id())->paginate(5);

        return BookingResource::collection($userBookings);
    }

    private function buildBaseBookingQuery()
    {
        return Booking::with('user', 'tour', 'tickets')
            ->leftJoin('users', 'bookings.user_id', '=', 'users.id')
            ->leftJoin('tours', 'bookings.tour_id', '=', 'tours.id')
            ->select('bookings.*');
    }

    private function applySearchFilter($bookingQuery, $search)
    {
        $bookingQuery->whereHas('user', function ($query) use ($search) {
            $query->where('name', 'LIKE', "%{$search}%");
        })->orWhereHas('tour', function ($query) use ($search) {
            $query->where('name', 'LIKE', "%{$search}%");
        });
    }

    private function applyOrdering($bookingQuery)
    {
        $orderColumn = request('order_column', 'created_at');
        $orderDirection = request('order_direction', 'desc');

        $validColumns = ['user_name', 'tour_name', 'status', 'created_at'];
        $validDirections = ['asc', 'desc'];

        $orderColumn = in_array($orderColumn, $validColumns) ? $orderColumn : 'created_at';
        $orderDirection = in_array($orderDirection, $validDirections) ? $orderDirection : 'desc';

        switch ($orderColumn) {
            case 'user_name':
                $bookingQuery->orderBy('users.name', $orderDirection);
                break;
            case 'tour_name':
                $bookingQuery->orderBy('tours.name', $orderDirection);
                break;
            default:
                $bookingQuery->orderBy("bookings.$orderColumn", $orderDirection);
                break;
        }
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
