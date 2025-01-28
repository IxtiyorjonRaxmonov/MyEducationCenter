<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class UserRequest extends FormRequest
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
            'name' => 'required|max:100',
            'surname' => 'required|max:100',
            'username' => 'required|unique:users,username|max:255',
            'password' => 'required|min:5|max:255',
            'role_id' => 'required|exists:roles,id',
            'active' => 'boolean',
        ];
    }

    public function messages(): array
{
    return [
        'name.required' => 'A name is required.',
        'surname.required' => 'A surname is required.',
        'username.required' => 'A username is required.',
        'username.unique' => 'The username has already been taken.',
        'password.required' => 'A password is required.',
        'password.min' => 'The password must be at least 5 characters long.',
        'role_id.required' => 'A role ID is required.',
        'role_id.integer' => 'The role ID must be an integer.',
        'role_id.exists' => 'The selected role ID does not exist.',
        'active.boolean' => 'active must be boolean'
    ];
}


   
        protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'message' => 'Validation failed',
            'errors' => $validator->errors(),
        ], 422));
    }

}
