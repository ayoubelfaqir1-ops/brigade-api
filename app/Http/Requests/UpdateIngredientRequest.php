<?php

namespace App\Http\Requests;

use App\Enums\DietaryTag;
use Illuminate\Foundation\Http\FormRequest;

class UpdateIngredientRequest extends FormRequest
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
            'name'=>'sometimes|string|min:3',
            'tags'=>'sometimes|array',
            'tags.*.name'=>'required|string|in:'.implode(',', DietaryTag::values()),
        ];
    }

    /**
     * Get custom validation messages.
     */
    public function messages(): array
    {
        return [
            'name.string' => 'Ingredient name must be text.',
            'name.min' => 'Ingredient name must be at least 3 characters.',
            'tags.array' => 'Dietary tags must be provided as a list.',
            'tags.*.name.required' => 'Each dietary tag must have a name.',
            'tags.*.name.string' => 'Each dietary tag name must be text.',
            'tags.*.name.in' => 'One or more dietary tags are invalid. Please use valid dietary tags.',
        ];
    }
}
