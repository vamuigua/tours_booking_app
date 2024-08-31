<?php

namespace App\Http\Requests;

use App\Models\Booking;
use Illuminate\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class StoreTicketRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'booking_id' => ['required', 'exists:bookings,id'],
            'slots' => ['required', 'integer', 'min:1'],
        ];
    }

    public function after(): array
    {
        return [
            function (Validator $validator) {
                $this->validateAvailableSlots($validator);
            }
        ];
    }

    protected function validateAvailableSlots(Validator $validator)
    {
        $booking = Booking::with('tour')->where('id', $this->booking_id)->first();

        if (!$booking) {
            return;
        }

        $availableSlots = $booking->tour->slots;
        $requestedSlots = $this->slots;

        if ($availableSlots <= 0) {
            $validator->errors()->add('general', __('errors.tour_fully_booked'));
        } elseif ($requestedSlots > $availableSlots) {
            $validator->errors()->add('general', trans_choice('errors.not_enough_slots', $availableSlots));
        }
    }
}
