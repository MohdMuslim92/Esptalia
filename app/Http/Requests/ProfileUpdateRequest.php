<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        $user = $this->user();

        return [
            'name' => ['string', 'max:255'],
            'email' => ['email', 'max:255', Rule::unique(User::class)->ignore($user->id)],
            'phone_number' => ['string', 'max:255'],
            'date_of_birth' => ['string', 'max:255'],
            'address' => ['string', 'max:255'],
            'gender' => ['string', 'max:255'],

            // Provider fields
            'provider.specialization' => ['string', 'max:255'],
            'provider.type' => ['string', 'max:255'],

            // Medical Center fields
            'medicalCenter.state' => ['string', 'max:255'],
            'medicalCenter.city' => ['string', 'max:255'],
            'medicalCenter.description' => ['string', 'max:255'],
            'medicalCenter.working_days' => ['string', 'max:255'],
            'medicalCenter.working_hours' => ['string', 'max:255'],
            'medicalCenter.availability' => ['string', 'max:255'],
            'medicalCenter.license_number' => ['string', 'max:255'],

            // Hospital fields
            'hospital.state' => ['string', 'max:255'],
            'hospital.city' => ['string', 'max:255'],
            'hospital.description' => ['string', 'max:255'],
            'hospital.working_days' => ['string', 'max:255'],
            'hospital.working_hours' => ['string', 'max:255'],
            'hospital.availability' => ['string', 'max:255'],
            'hospital.license_number' => ['string', 'max:255'],

            // Clinic fields
            'clinic.state' => ['string', 'max:255'],
            'clinic.city' => ['string', 'max:255'],
            'clinic.description' => ['string', 'max:255'],
            'clinic.working_days' => ['string', 'max:255'],
            'clinic.working_hours' => ['string', 'max:255'],
            'clinic.availability' => ['string', 'max:255'],
            'clinic.license_number' => ['string', 'max:255'],
            ];
    }
}
