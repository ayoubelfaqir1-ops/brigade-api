<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePlatRequest extends FormRequest
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
     */
    public function rules(): array
    {
        return [
            'name' => 'sometimes|string|max:255',
            'description' => 'nullable|string',
            'price' => 'sometimes|numeric|min:0',
            'category_id' => 'sometimes|exists:categories,id',
            'ingredients'  => 'sometimes|array',
            'ingredients.*'  => 'integer|exists:ingredients,id',
        ];
    }

    /**
     * Get custom validation messages.
     */
    public function messages(): array
    {
        return [
            'name.string' => 'Plate name must be text.',
            'name.max' => 'Plate name cannot exceed 255 characters.',
            'description.string' => 'Plate description must be text.',
            'price.numeric' => 'Plate price must be a number.',
            'price.min' => 'Plate price cannot be negative.',
            'category_id.exists' => 'The selected category does not exist.',
            'ingredients.array' => 'Ingredients must be provided as a list.',
            'ingredients.*.integer' => 'Each ingredient ID must be a number.',
            'ingredients.*.exists' => 'One or more selected ingredients do not exist.',
        ];
    }
}