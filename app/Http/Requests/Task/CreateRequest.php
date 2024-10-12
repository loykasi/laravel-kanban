<?php

namespace App\Http\Requests\Task;

use Illuminate\Foundation\Http\FormRequest;

class CreateRequest extends FormRequest
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
            'name'=>'required',
            'projectId'=>'required|numeric',
            'memberIds'=>'array',
            'memberIds.*' => 'numeric'
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'=>'Name is required',
            'projectId.required'=>'ProjectId is required',
            'memberIds.required'=>'MemberIds need to be an array',
            'memberIds.*.numeric' => 'MemberId need to be numeric'
        ];
    }
}
