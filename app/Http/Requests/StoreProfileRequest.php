<?php

namespace App\Http\Requests;

use App\Enums\DietaryTag;
use Illuminate\Foundation\Http\FormRequest;

class StoreProfileRequest extends FormRequest
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
            'name'           => 'required|string|max:255',
            'email'          => 'required|email|unique:users',
            'password'       => 'required|min:8|confirmed',
            'dietary_tags'   => 'sometimes|array',
            'dietary_tags.*' => 'string|in:' . implode(',', DietaryTag::values()),
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Full name is required.',
            'name.string' => 'Full name must be text.',
            'name.max' => 'Full name cannot exceed 255 characters.',
            'email.required' => 'Email address is required.',
            'email.email' => 'Please provide a valid email address.',
            'email.unique' => 'This email address is already registered.',
            'password.required' => 'Password is required.',
            'password.min' => 'Password must be at least 8 characters long.',
            'password.confirmed' => 'Password confirmation does not match.',
            'dietary_tags.array' => 'Dietary preferences must be provided as a list.',
            'dietary_tags.*.string' => 'Each dietary preference must be text.',
            'dietary_tags.*.in' => 'One or more dietary preferences are invalid. Please select from the available options.',
        ];
    }

}
