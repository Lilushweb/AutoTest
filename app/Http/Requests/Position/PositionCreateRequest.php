<?php

namespace App\Http\Requests\Position;

use Illuminate\Foundation\Http\FormRequest;

class PositionCreateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'comfort_category' => 'required|array',
            'comfort_category.*' => 'required|integer|exists:comfort_categories,id',
        ];
    }
}
