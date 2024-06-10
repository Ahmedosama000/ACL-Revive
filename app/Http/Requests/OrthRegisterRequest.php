<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OrthRegisterRequest extends FormRequest
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
            'email' => ['required','email','unique:users,email'],
            'phone' => ['required','numeric'],
            'username' => ['required','string','unique:users,username'],
            'password' => ['required','min:8'],
            'nation' => ['required','max:5000','mimes:png,jpg,pdf'],
            'union' => ['required','max:5000','mimes:png,jpg,pdf'],
        ];
    }
}
