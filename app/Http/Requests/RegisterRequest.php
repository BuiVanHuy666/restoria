<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules;

class RegisterRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'min:3', 'max:100'],
            'email' => ['required', 'string', 'email', 'lowercase', 'max:255', 'unique:users'],
            'phone_number' => ['nullable', 'regex:/^(0|84)(3|5|7|8|9)([0-9]{8})$/'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()]
        ];
    }
}
