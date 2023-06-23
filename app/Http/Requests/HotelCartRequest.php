<?php

namespace App\Http\Requests;

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Http\FormRequest;

class HotelCartRequest extends FormRequest
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
            "id" => $this->route('id') != null ? 'required|integer' : 'nullable',
            "hotelId" => $this->route('id') == null ? "numeric|required" : "nullable"
        ];
    }
    
    public function messages()
    {
        return [
            "hotelId.required" => "Please select an hotel",
            "hotelId.numeric" => "Please select an hotel",
        ];
    }
    
    public function prepareForValidation() {
        $this->merge([
            'id' => $this->route('id'),
        ]);
    }
}