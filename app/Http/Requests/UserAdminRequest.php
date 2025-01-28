<?php

namespace App\Http\Requests;

use Dotenv\Validator;
use Illuminate\Contracts\Validation\Validator as ValidationValidator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class UserAdminRequest extends FormRequest
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
    public function rules()
    {
        return [
            'name' => 'string|max:100',
            'surname' => 'string|max:100',
            'username' => 'string|max:255',
            'password' => 'min:5|max:255',
            'role_id' => 'int|exists:roles,id',
        ];
    }

    public function messages(): array
{
    return [
        'name.string' => 'A name required string.',
        'surname.string' => 'A surname required string.',
        'username.string' => 'A username required string.',
        'password' => 'A password is required.',
        'password.min' => 'The password must be at least 5 characters long.',
        'role_id.integer' => 'The role ID must be an integer.',
        'role_id.exists' => 'The selected role ID does not exist.',
    ];
}


   
        protected function failedValidation(ValidationValidator $validator)
    {
        throw new HttpResponseException(response()->json([
            'message' => 'Validation failed',
            'errors' => $validator->errors(),
        ], 422));
    }
}
