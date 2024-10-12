<?php

namespace App\Http\Requests\Member;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
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
            'id' => 'required|numeric',
            'name'=>'required',
            'email'=>'required|email',
        ];
    }

    public function messages(): array
    {
        return [
            'id.required'=>'UserId is required',
            'name.required'=>'Name is required',
            'email.required'=>'Email is required'
        ];
    }
}
