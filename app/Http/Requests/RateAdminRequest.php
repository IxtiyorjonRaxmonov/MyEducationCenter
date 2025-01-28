<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class RateAdminRequest extends FormRequest
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
            'grade' => 'int|max:100',
            'gr_lev_sub_id' => 'int|exists:group_subject_levels,id|max:100',
            'active' => 'boolean'
        ];
    }

    public function messages()
    {
        return [
            "grade.int" => "grade must be an integer",
            'gr_lev_sub_id.exists' => 'The selected group_subject_levels ID does not exist.',
            "active.boolean" => "active must be boolean"
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
