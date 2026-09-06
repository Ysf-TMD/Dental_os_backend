<?php

namespace App\Modules\Patients\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PatientDetailResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'code' => $this->code,
            'title' => $this->title,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'first_name_ar' => $this->first_name_ar,
            'last_name_ar' => $this->last_name_ar,
            'phone' => $this->phone,
            'phone_secondary' => $this->phone_secondary,
            'email' => $this->email,
            'gender' => $this->gender,
            'birth_date' => $this->birth_date?->format('Y-m-d'),
            'birth_place' => $this->birth_place,
            'nationality' => $this->nationality,
            'cin' => $this->cin,
            'passport' => $this->passport,
            'photo' => $this->photo,
            'address' => $this->address,
            'city' => $this->city,
            'region' => $this->region,
            'postal_code' => $this->postal_code,
            'blood_group' => $this->blood_group,
            'referring_dentist' => $this->referring_dentist,
            'first_visit_date' => $this->first_visit_date?->format('Y-m-d'),
            'consultation_reason' => $this->consultation_reason,
            'oral_health_status' => $this->oral_health_status,
            'last_visit' => $this->last_visit?->format('Y-m-d'),
            'status' => $this->status,
            'balance' => (float) $this->balance,
            'credit_limit' => (float) $this->credit_limit,
            'discount_rate' => (float) $this->discount_rate,
            'preferred_payment_method' => $this->preferred_payment_method,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
            // Nested relations
            'emergency_contacts' => PatientEmergencyContactResource::collection($this->whenLoaded('emergencyContacts')),
            'medical_history' => PatientMedicalHistoryResource::make($this->whenLoaded('medicalHistory')->first()),
            'allergies' => PatientAllergyResource::make($this->whenLoaded('allergies')->first()),
            'medications' => PatientMedicationResource::collection($this->whenLoaded('medications')),
            'habits' => PatientHabitResource::make($this->whenLoaded('habits')->first()),
            'insurances' => PatientInsuranceResource::collection($this->whenLoaded('insurances')),
            'consents' => PatientConsentResource::make($this->whenLoaded('consents')->first()),
            'documents' => PatientDocumentResource::collection($this->whenLoaded('documents')),
            'notes' => PatientNoteResource::collection($this->whenLoaded('notes')),
        ];
    }
}
