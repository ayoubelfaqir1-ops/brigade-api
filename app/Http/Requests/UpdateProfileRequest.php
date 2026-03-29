<?php

namespace App\Http\Requests;

use App\Enums\DietaryTag;
use Illuminate\Foundation\Http\FormRequest;

class UpdateProfileRequest extends FormRequest
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
            'name'           => 'sometimes|string|max:255',
            'email'          => 'sometimes|email|unique:users,email,' . $this->user()->id,
            'dietary_tags'   => 'sometimes|array',
            'dietary_tags.*' => 'string|in:' . implode(',', DietaryTag::values()),
        ];
    }

    public function messages(): array
    {
        return [
            'name.string' => 'Full name must be text.',
            'name.max' => 'Full name cannot exceed 255 characters.',
            'email.email' => 'Please provide a valid email address.',
            'email.unique' => 'This email address is already registered.',
            'dietary_tags.array' => 'Dietary preferences must be provided as a list.',
            'dietary_tags.*.string' => 'Each dietary preference must be text.',
            'dietary_tags.*.in' => 'One or more dietary preferences are invalid. Please select from the available options.',
        ];
    }
}
