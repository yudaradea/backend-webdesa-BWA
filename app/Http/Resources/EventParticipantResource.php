<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EventParticipantResource extends JsonResource
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
            'event_id' => new EventResource($this->whenLoaded('event')),
            'head_of_family_id' => new HeadOfFamilyResource($this->whenLoaded('headOfFamily')),
            'quantity' => $this->quantity,
            'total_price' => $this->total_price,
            'payment_status' => $this->payment_status,
        ];
    }
}
