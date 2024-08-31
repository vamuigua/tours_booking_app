<?php

namespace App\Observers;

use App\Models\Booking;

class BookingObserver
{
    public function creating(Booking $booking): void
    {
        if (auth()->check()) {
            $booking->user_id = auth()->id();
            $booking->status = Booking::STATUS_PENDING;
        }
    }
}
