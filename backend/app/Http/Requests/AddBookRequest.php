<?php

namespace App\Http\Requests;


use Illuminate\Support\Facades\URL;
use Illuminate\Http\Request;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class AddBookRequest extends FormRequest
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
            'title' => 'required|string',
            'category_id' => 'required|integer',
            'quantity' => 'required|integer',
            'imageFile' => 'nullable|image|mimes:jpg,png,jpeg,gif,svg|max:2048',
            'price' => 'nullable|integer',
            'year' => 'required|integer',
            'is_marked' => 'nullable|string',
            'publisher_id' => 'required|integer',
            'authors' => 'required|array',
        ];
    }
}
