<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class HotelReserveRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            "check_in_date" => "date|required",
            "check_out_date" => "date|required"
        ];
    }
    
    public function messages()
    {
        return [
            "check_in_date.required" => "Guest check in date is required",
            "check_out_date.required" => "Guest check out date is required"
        ];
    }
}