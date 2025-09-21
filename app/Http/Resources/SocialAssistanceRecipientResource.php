<?php

namespace App\Http\Resources;


use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SocialAssistanceRecipientResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            // Memunculkan social assistance id dengan relasi belongsTo
            'social_assistance_id' => new SocialAssistanceResource($this->whenLoaded('socialAssistance')),
            // Memunculkan head of family id dengan relasi belongsTo
            'head_of_family_id' => new HeadOfFamilyResource($this->whenLoaded('headOfFamily')),
            'amount' => $this->amount,
            'reason' => $this->reason,
            'bank' => $this->bank,
            'account_number' => $this->account_number,
            'proof' => $this->proof,
            'status' => $this->status,
        ];
    }
}
