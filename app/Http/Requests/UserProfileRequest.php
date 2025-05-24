<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserProfileRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'gender' => 'required|string',
            'date_of_birth' => 'required|date',
            'address' => 'nullable|string',
            'visit_reason' => 'nullable|string',
            'emergency_contact_name' => 'nullable|string',
            'emergency_contact_phone' => 'nullable|string',
            'emergency_contact_relationship' => 'nullable|string',
            'private_health_fund_provider' => 'nullable|string',
            'areas_of_soreness' => 'nullable|string',
            'medical_history' => 'nullable|string',
            'conditions' => 'nullable|string',
            'others' => 'nullable|string',
            'attachment_path' => 'nullable|string',
            'notes' => 'nullable|string',
        ];
    }
}
