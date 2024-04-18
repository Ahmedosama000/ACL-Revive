<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ExerciseRequest extends FormRequest
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
            'name' => ['required','string'],
            'link' => ['required','string'],
            'img' => ['required','string'],
            'instruction' => ['required','array'],
            'type' => ['required',Rule::in(['Primary','Optional','Alternate'])],
            'phase' => ['required',Rule::in([0,1,2,3])],
            'protocol_id' => ['required','exists:protocols,id']
        ];
    }
}
