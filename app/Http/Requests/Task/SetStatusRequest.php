<?php

namespace App\Http\Requests\Task;

use Illuminate\Foundation\Http\FormRequest;

class SetStatusRequest extends FormRequest
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
            'taskId'=>'required',
            'projectId'=>'required',
            'status'=>'required',
        ];
    }

    public function messages(): array
    {
        return [
            'taskId.required'=>'TaskId is required',
            'projectId.required'=>'ProjectId is required',
            'status.required'=>'Status is required',
        ];
    }
}
