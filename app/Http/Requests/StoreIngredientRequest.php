<?php

namespace App\Http\Requests;

use App\Enums\DietaryTag;
use Illuminate\Foundation\Http\FormRequest;

class StoreIngredientRequest extends FormRequest
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
            'name'=>'required|string|min:3|unique:ingredients',
            'tags'=>'required|array',
            'tags.*.name'=>'required|string|in:'.implode(',', DietaryTag::values()),
        ];
    }
}
