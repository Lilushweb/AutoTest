<?php

namespace App\Http\Requests\Car;

use App\Models\Car;
use Illuminate\Foundation\Http\FormRequest;

class CreateCarBookingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'car_id' => [
                'required',
                'integer',
                'exists:cars,id',
                function (string $attribute, mixed $value, \Closure $fail): void {
                    $car = Car::with('carBookings')->find($value);
                    if (!$car) {
                        return;
                    }
                    $startTime = $this->input('start_time');
                    $endTime = $this->input('end_time');
                    $hasOverlap = $car->carBookings()->where('start_time', '<', $endTime)
                        ->where('end_time', '>', $startTime)
                        ->exists();
                    if ($hasOverlap) {
                        $fail('The selected car is already booked for this time.');
                    }
                },
            ],
            'start_time' => ['required', 'date', 'after_or_equal:now'],
            'end_time' => ['required', 'date', 'after:start_time'],
        ];
    }
}
