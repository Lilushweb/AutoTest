<?php

namespace App\Http\Requests\Car;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCarBookingRequest extends FormRequest
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
            'start_time' => ['sometimes', 'date', 'after_or_equal:now'],
            'end_time' => [
                'sometimes',
                'date',
                function (string $attribute, mixed $value, \Closure $fail): void {
                    $startTime = $this->input('start_time') ?? $this->route('carBooking')?->start_time;
                    if ($startTime && $value <= $startTime) {
                        $fail('The end time must be after the start time.');
                    }
                },
            ],
        ];
    }
}
