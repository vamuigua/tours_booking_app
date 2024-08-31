<?php

namespace App\Models;

use App\Models\Booking;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Ticket extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }

    protected static function generateUniqueTicketNumber()
    {
        /**
         * METHOD 1:
         * Generate a unique ticket number using a prefix, timestamp, and random number
         * Advantage: Simple code to understand 
         * Disadvantage: The numbers aren't as human-readable or sortable
         */

        // do {
        //     $ticketNumber = 'TKT-' . time() . '-' . rand(1000, 9999);
        // } while (self::where('ticket_number', $ticketNumber)->exists());

        /**
         * METHOD 2:
         * Generate a unique ticket number using a prefix, date, and incrementing number
         * Advantage: Better for Readability & Sortability
         */

        $prefix = 'TKT-' . date('Ymd');
        $lastTicket = Ticket::where('ticket_number', 'like', $prefix . '%')
            ->orderBy('ticket_number', 'desc')
            ->first();

        if ($lastTicket) {
            $lastNumber = intval(substr($lastTicket->number, -5));
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }

        $ticketNumber = $prefix . '-' . str_pad($newNumber, 5, '0', STR_PAD_LEFT);

        while (Ticket::where('ticket_number', $ticketNumber)->exists()) {
            $newNumber++;
            $ticketNumber = $prefix . '-' . str_pad($newNumber, 5, '0', STR_PAD_LEFT);
        }

        return $ticketNumber;
    }
}
