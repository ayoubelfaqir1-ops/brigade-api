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
            'email.unique'        => 'This email is already taken.',
            'dietary_tags.array'  => 'Dietary tags must be a list.',
            'dietary_tags.*.in'   => 'One or more tags are not valid.',
        ];
    }
}
