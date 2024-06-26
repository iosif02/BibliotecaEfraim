<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UpdateUserRequest extends FormRequest
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
            'imageFile' => 'nullable|image|mimes:jpg,png,jpeg,gif,svg|max:2048',
            'email' => 'required|string|unique:users,email,' .request('userId'),
            'identity_number' => 'required|regex:/^(\d{13})$/',
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'address' => 'required|string',
            'phone' => 'required|regex:/^(\d{10})$/',
            'occupation' => 'required|string',
            'birth_date' => 'required|date',
            'roles' => 'required|array',
            'userId' => 'required|integer',
        ];
    }
}
