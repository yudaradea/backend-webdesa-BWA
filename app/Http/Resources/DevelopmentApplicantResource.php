<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DevelopmentApplicantResource extends JsonResource
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
            'development_id' => new DevelopmentResource($this->whenLoaded('development')),
            'user_id' => new UserResource($this->whenLoaded('user')),
            'status' => $this->status,
        ];
    }
}
