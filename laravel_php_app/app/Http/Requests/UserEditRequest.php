<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class UserEditRequest extends FormRequest
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
            'email' => ['required', 'string', 'email', 'max:255', 'regex:/^[^\s@]+@[^\s@]+\.[^\s@]+$/', Rule::unique('users')->ignore(Auth::user()->id)],
            'profile' => ['image', 'mimes:jpeg,png,jpg,gif,svg,webp', 'max:2048'],
            'type' => [],
            'phone' => ['regex:/^[0-9]+$/', 'min:9', 'max:11'],
            'address' => ['max:255'],
            'dob' => [],
        ];
    }

    public function messages(): array
    {
        return [
            'email' => "The email must be in a valid email format.",
            'phone.regex' => "only numbers are allowed.",
            'phone.min' => "phone number must be at least 9 digits.",
            'phone.max' => "phone number must be at most 11 digits.",
        ];
    }
}
