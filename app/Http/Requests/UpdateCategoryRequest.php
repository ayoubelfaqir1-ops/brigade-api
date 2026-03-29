<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCategoryRequest extends FormRequest
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
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            "name"=>"sometimes|required|string|min:3|max:25",
            "description"=>"sometimes|required|string|min:3|max:50",
            "color"=>["sometimes","string","regex:/^#[0-9A-Fa-f]{6}$/"],
        ];
    }

    /**
     * Get custom validation messages.
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Category name is required.',
            'name.string' => 'Category name must be text.',
            'name.min' => 'Category name must be at least 3 characters.',
            'name.max' => 'Category name cannot exceed 25 characters.',
            'description.required' => 'Category description is required.',
            'description.string' => 'Category description must be text.',
            'description.min' => 'Category description must be at least 3 characters.',
            'description.max' => 'Category description cannot exceed 50 characters.',
            'color.string' => 'Category color must be text.',
            'color.regex' => 'Category color must be a valid hex color (e.g., #FF5733).',
        ];
    }
}
