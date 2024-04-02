<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SessionRequest extends FormRequest
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
            "title" => ["required",'string'],
            "duration" => ["required",'numeric'],
            "notes" => ["required",'string'],
            "platform" => ["required",'string','max:30'],
            "price" => ["required",'numeric'],
            "started_at" => ["required",'date_format:Y-m-d H:i:s'],
        ];
    }
}
