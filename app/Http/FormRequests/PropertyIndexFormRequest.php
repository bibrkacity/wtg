<?php

namespace App\Http\FormRequests;

use Illuminate\Foundation\Http\FormRequest;

class PropertyIndexFormRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'city' => 'sometimes|string|exists:properties,city',
            'check_in' => 'required|date',
            'check_out' => 'required|date',
            'guests' => 'required|integer|min:1',
            'page' => 'sometimes|integer|min:1',
            'per_page' => 'sometimes|integer|min:1',
        ];
    }

    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $this->mergeIfMissing([
            'page' => 1,
            'per_page' => 20,
        ]);
    }
}
