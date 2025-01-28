<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class GroupDateRequest extends FormRequest
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
            'group_id' => 'required|int|exists:groups,id',
            "date" => "required|date|date_format:Y-m-d|after_or_equal:today"

        ];
    }

        public function messages()
    {
        return [
           

            "group_id.required" => "group_id is required",
            "group_id.exists" => "group_id not found",
            "date.required" => "date is required"
        ];
    }

        protected function failedValidation(Validator $validator){
       throw new HttpResponseException(response()->json([
        "message" => "Validation failed",
        "errors" => $validator->errors(),
       ], 422)); 

    }
}
