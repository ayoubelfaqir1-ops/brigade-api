<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePlatRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->role === 'admin';
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'name'         => 'required|string|max:255|unique:plats',
            'description'  => 'nullable|string|max:1000',
            'price'        => 'required|numeric|min:0|max:9999.99',
            'category_id'  => 'required|integer|exists:categories,id',
            'is_available' => 'boolean',
            'ingredients'  => 'sometimes|array',
            'ingredients.*'    => 'integer|exists:ingredients,id',
        ];
    }

    /**
     * Get custom validation messages.
     */
    public function messages(): array
    {
        return [
            'name.unique' => 'You already have a plat with this name.',
            'price.max' => 'Price cannot exceed 9999.99.',
            'image.image' => 'The file must be an image.',
            'image.mimes' => 'Only jpg, jpeg, png and webp are allowed.',
            'image.max'   => 'Image size must not exceed 2MB.',
        ];
    }
}
