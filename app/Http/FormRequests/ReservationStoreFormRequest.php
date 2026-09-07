<?php

namespace App\Http\FormRequests;

use Illuminate\Foundation\Http\FormRequest;

class ReservationStoreFormRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            "client_reference" => "required|string",
            "customer_name" => "required|string",
            "customer_email" =>  "required|email"
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
