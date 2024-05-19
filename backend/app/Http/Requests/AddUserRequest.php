<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class AddUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'email' => 'required|string|unique:users,email',
            'password' => 'required|string|confirmed',
            'identity_number' => 'nullable|regex:/^(\d{13})$/',
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'address' => 'nullable|string',
            'phone' => 'required|regex:/^(\d{10})$/',
            'occupation' => 'nullable|string',
            'birth_date' => 'required|date',
            'roles' => 'required|array',
        ];
    }

    public function messages()
    {
        return [
            'identity_number.regex' => 'Identity number must contain exactly 13 digits.',
            'phone.regex' => 'Phone number must contain exactly 10 digits.',
        ];
    }
}
