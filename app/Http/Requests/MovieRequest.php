<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MovieRequest extends FormRequest
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

        if($this->method() == 'PATCH'){
            return [
            'title' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'director_id' => 'nullable|exists:directors,id',
            'category_id' => 'nullable|exists:categories,id',
            'cover_image' => 'nullable|image|max:2048', // max 2MB

            ];
        } else {
            return [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'director_id' => 'required|exists:directors,id',
            'category_id' => 'required|exists:categories,id',
            'cover_image' => 'nullable|image|max:2048', // max 2MB
            ];

        }
    }
}
