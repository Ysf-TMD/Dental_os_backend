<?php

namespace App\Modules\Patients\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PatientAllergyResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'penicillin' => $this->penicillin,
            'latex' => $this->latex,
            'anesthetics' => $this->anesthetics,
            'iodine' => $this->iodine,
            'other_allergies' => $this->other_allergies,
        ];
    }
}
