<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class GroupAdminRequest extends FormRequest
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
            'group_name' => 'string|unique:groups,group_name',
            'subject_id' => 'int|exists:subjects,id',
            'level_id' => 'int|exists:levels,id',
            'start_time' => 'time',
            'end_time' => 'time',
            'active' => 'boolean'
            
        ];
    }

    public function messages(): array
{
    return [
        'group_name.string' => 'A group_name must be string.',
        'start_time' => 'start_time invalid.',
        'end_time' => 'end_time invalid.',
        'active' => 'active invalid.'
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
