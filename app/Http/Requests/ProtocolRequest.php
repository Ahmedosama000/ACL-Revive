<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProtocolRequest extends FormRequest
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
            'protocol_id' => ['required','integer','exists:protocols,id'],
            'injury_date' => ["required",'date_format:Y-m-d H:i:s'],
            'surgery_date' => ["required",'date_format:Y-m-d H:i:s'],
        ];
    }
}
