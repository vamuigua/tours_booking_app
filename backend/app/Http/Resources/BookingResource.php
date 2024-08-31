<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use App\Http\Resources\TicketResource;
use Illuminate\Http\Resources\Json\JsonResource;

class BookingResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {        
        return [
            'id' => $this->id,
            'status' => $this->status,
            'user' => UserResource::make($this->user),
            'tour' => TourResource::make($this->tour),
            'total_price' => $this->totalPrice,
            'tickets' => TicketResource::collection($this->tickets),
            'created_at' => $this->created_at->toDateTimeString(),
        ];
    }
}
