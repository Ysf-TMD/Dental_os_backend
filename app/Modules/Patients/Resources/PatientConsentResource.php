<?php

namespace App\Modules\Patients\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PatientConsentResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'terms_of_use' => $this->terms_of_use,
            'data_processing' => $this->data_processing,
            'sms_notifications' => $this->sms_notifications,
            'email_notifications' => $this->email_notifications,
            'whatsapp_notifications' => $this->whatsapp_notifications,
            'cndp_consent' => $this->cndp_consent,
        ];
    }
}
