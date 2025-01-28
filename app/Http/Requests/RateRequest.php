<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class RateRequest extends FormRequest
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
            'grade' => 'required|numeric|max:100',
            'user_id' => 'required|int',
            'group_date_id' => 'required|int|exists:group_dates,id',
            'active' => 'boolean'
        ];
    }

    public function messages(): array
{
    return [
        'grade.required' => 'A grade required.',
        'user_id.required' => 'A user_id required.',
        'group_date_id.required' => 'A group_date_id required.',
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
