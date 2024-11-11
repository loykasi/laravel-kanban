<?php

namespace App\Http\Requests\CardList;

use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
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
            'name' => ['required'],
            'projectId' => ['required'],
        ];
    }

    // public function messages(): array
    // {
    //     return [
    //         'name.required'=>'Name is required',
    //         'projectId.required'=>'ProjectId is required',
    //     ];
    // }
}
