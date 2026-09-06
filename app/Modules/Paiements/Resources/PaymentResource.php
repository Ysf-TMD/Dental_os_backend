<?php

namespace App\Modules\Paiements\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PaymentResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'invoice_id' => $this->invoice_id,
            'invoice' => $this->whenLoaded('invoice', fn () => [
                'id' => $this->invoice->id,
                'number' => $this->invoice->number,
                'patient' => $this->invoice->relationLoaded('patient') && $this->invoice->patient
                    ? $this->invoice->patient->first_name.' '.$this->invoice->patient->last_name
                    : null,
            ]),
            'amount' => (float) $this->amount,
            'method' => $this->method,
            'paid_at' => $this->paid_at?->format('Y-m-d'),
            'reference' => $this->reference,
            'created_at' => $this->created_at,
        ];
    }
}
