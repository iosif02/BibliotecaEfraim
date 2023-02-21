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
//            'image' => 'required|image|mimes:png,jpg,jpeg|max:2048',
            'image' => 'nullable',
            'price' => 'string|nullable',
            'year' => 'string|nullable',
            'publisher_id' => 'nullable|integer',
            'authors' => 'required|array',
            'bookId' => 'required|integer',
        ];
    }
}