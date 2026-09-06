<?php

namespace App\Modules\Patients\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PatientMedicalHistoryResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'diabetes' => $this->diabetes,
            'hypertension' => $this->hypertension,
            'asthma' => $this->asthma,
            'epilepsy' => $this->epilepsy,
            'heart_disease' => $this->heart_disease,
            'hepatitis' => $this->hepatitis,
            'hiv' => $this->hiv,
            'pregnancy' => $this->pregnancy,
            'cancer' => $this->cancer,
            'bleeding_disorder' => $this->bleeding_disorder,
            'other_conditions' => $this->other_conditions,
        ];
    }
}
