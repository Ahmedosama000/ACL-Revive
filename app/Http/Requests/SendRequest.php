<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SendRequest extends FormRequest
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
            'doctor_name'  =>  ['string','max:64'],
            'doctor_mail'  =>  ['email','exists:users,email','max:64'],
            'doctor_phone' =>  ['numeric','max_digits:20'],
            'patient_name' =>  ['required','string','max:64'],
            'patient_mail' =>  ['required','email','max:64'],
            'patient_phone'=>  ['required','numeric','max_digits:20'],
            'patient_age'  =>  ['required','integer'],
            'result'       =>  ['required','string','max:254'],
            'mri'          =>  ['string','max:254'],
            'report'       =>  ['required','max:8000','mimes:pdf'],
        ];
    }
}
