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
            'name.required' => 'Plate name is required.',
            'name.string' => 'Plate name must be text.',
            'name.max' => 'Plate name cannot exceed 255 characters.',
            'name.unique' => 'A plate with this name already exists.',
            'description.string' => 'Plate description must be text.',
            'description.max' => 'Plate description cannot exceed 1000 characters.',
            'price.required' => 'Plate price is required.',
            'price.numeric' => 'Plate price must be a number.',
            'price.min' => 'Plate price cannot be negative.',
            'price.max' => 'Plate price cannot exceed 9999.99.',
            'category_id.required' => 'Please select a category for this plate.',
            'category_id.integer' => 'Category selection is invalid.',
            'category_id.exists' => 'The selected category does not exist.',
            'is_available.boolean' => 'Availability status must be true or false.',
            'ingredients.array' => 'Ingredients must be provided as a list.',
            'ingredients.*.integer' => 'Each ingredient ID must be a number.',
            'ingredients.*.exists' => 'One or more selected ingredients do not exist.',
        ];
    }
}
