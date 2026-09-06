<?php

namespace App\Modules\Patients\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PatientHabitResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'smoker' => $this->smoker,
            'hookah' => $this->hookah,
            'alcohol' => $this->alcohol,
            'drugs' => $this->drugs,
            'notes' => $this->notes,
        ];
    }
}
