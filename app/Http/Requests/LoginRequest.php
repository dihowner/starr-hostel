<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
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
            "username" => "required|string",
            "password" => "required"
        ];
    }
    
    public function messages()
    {
        return [
            "username.required" => "Username is required",
            "username.string" => "User credential must be a string",
            "password.required" => "Password is required"
        ];
    }
}