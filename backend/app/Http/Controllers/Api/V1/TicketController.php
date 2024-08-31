<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Ticket;
use App\Models\Booking;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Resources\TicketResource;
use App\Http\Requests\StoreTicketRequest;

class TicketController extends Controller
{
    public function index()
    {
        return TicketResource::collection(Ticket::all());
    }

    public function store(StoreTicketRequest $request)
    {
        DB::beginTransaction();

        try {
            $booking = Booking::with('tour')->where('id', $request->booking_id)->firstOrFail();
            $requestedSlots = $request->validated('slots');
            $tickets = [];

            for ($i = 0; $i < $requestedSlots; $i++) {
                $tickets[] = Ticket::create([
                    'booking_id' => $request->validated('booking_id'),
                    'ticket_number' => Ticket::generateUniqueTicketNumber()
                ]);
            }

            $booking->update(['status' => Booking::STATUS_CONFIRMED]);
            $booking->tour->decrement('slots', $requestedSlots);

            DB::commit();

            return response()->json(TicketResource::collection($tickets), Response::HTTP_CREATED);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'message' => __('errors.something_went_wrong'),
                'errors' => ['general' => [__('errors.something_went_wrong')]],
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
