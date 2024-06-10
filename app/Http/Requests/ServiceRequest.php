<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ServiceRequest extends FormRequest
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
            'doctor_mail'  =>  ['email','exists:users,email'],
            'doctor_phone' =>  ['numeric'],
            'patient_name' =>  ['required','string','max:64'],
            'patient_mail' =>  ['email'],
            'patient_phone'=>  ['required','numeric'],
            'patient_age'  =>  ['required','integer'],
            'result'       =>  ['string'],
            'mri'          =>  ['string'],
            'report'       =>  ['max:8000','mimes:pdf'],
        ];
    }
}
