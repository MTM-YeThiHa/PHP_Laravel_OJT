<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserSignUpRequest extends FormRequest
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
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max: 255', 'unique:users', 'regex:/^[^\s@]+@[^\s@]+\.[^\s@]+$/'],
            'password' => ['required', 'string', 'min:8'],
            'password-confirmation' => ['required', 'same:password']
        ];
    }

    public function messages(): array
    {
        return [
            'email.regex' => 'The email must be in a valid email format.',
            'password-confirmation.same' => 'The password confirmation does not match the password.',
        ];
    }
}
