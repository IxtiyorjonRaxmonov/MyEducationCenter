<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class GroupRequest extends FormRequest
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
            'group_name' => 'required|unique:groups,group_name|max:255',
            'subject_id' => 'required|int|exists:subjects,id',
            'level_id' => 'required|int|exists:levels,id',
            "end_time" => "required|date_format:H:i|after:start_time",
            "start_time" => "required|date_format:H:i",
            "active" => "boolean"

        ];
    }

        public function messages()
    {
        return [
            "group_name.required" => "group_name is required",
            "group_name.unique" => "group_name has to unique",
            "date.required" => "date is required",
            "start_time.required" => "start_time is required",
            "end_time.required" => "end_time is required",
            "active" => "active is invalid"
        ];
    }

        protected function failedValidation(Validator $validator){
       throw new HttpResponseException(response()->json([
        "message" => "Validation failed",
        "errors" => $validator->errors(),
       ], 422)); 

    }
}
