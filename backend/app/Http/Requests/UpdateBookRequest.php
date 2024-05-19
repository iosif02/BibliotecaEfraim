<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UpdateBookRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
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
            'bookId' => 'required|integer',
        ];
    }
}
