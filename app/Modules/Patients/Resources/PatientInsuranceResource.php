<?php

namespace App\Modules\Patients\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PatientInsuranceResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'type' => $this->type,
            'company' => $this->company,
            'policy_number' => $this->policy_number,
            'expiration_date' => $this->expiration_date?->format('Y-m-d'),
        ];
    }
}
