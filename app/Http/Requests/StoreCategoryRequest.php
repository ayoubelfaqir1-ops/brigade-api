<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCategoryRequest extends FormRequest
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
        return [
            "name"=>"required|string|min:3|max:20|unique:categories",
            "description"=>"required|string|min:10|max:40",
            "color"=>["required","string","regex:/^#[0-9A-Fa-f]{6}$/"],
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
            'name.max' => 'Category name cannot exceed 20 characters.',
            'name.unique' => 'A category with this name already exists.',
            'description.required' => 'Category description is required.',
            'description.string' => 'Category description must be text.',
            'description.min' => 'Category description must be at least 10 characters.',
            'description.max' => 'Category description cannot exceed 40 characters.',
            'color.required' => 'Category color is required.',
            'color.string' => 'Category color must be text.',
            'color.regex' => 'Category color must be a valid hex color (e.g., #FF5733).',
        ];
    }
}
