<?php

namespace App\Http\Requests;

use App\Models\Booking;
use Illuminate\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class StoreBookingRequest extends FormRequest
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
            'tour_id' => ['required', 'exists:tours,id'],
        ];
    }

    public function after(): array
    {
        return [
            function (Validator $validator) {
                $this->validateExistingBooking($validator);
            }
        ];
    }

    protected function validateExistingBooking(Validator $validator)
    {
        if (Booking::where('user_id', auth()->id())->where('tour_id', $this->tour_id)->exists()) {
            $validator->errors()->add('general', __('errors.duplicate_booking'));
        }
    }
}
