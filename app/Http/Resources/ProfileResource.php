<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProfileResource extends JsonResource
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
            'thumbnail' => $this->thumbnail,
            'name' => $this->name,
            'about' => $this->about,
            'headman' => $this->headman,
            'people' => $this->people,
            'agricultural_area' => $this->agricultural_area,
            'total_area' => $this->total_area,
            'image' => profileImageResource::collection($this->whenLoaded('profileImages')),

        ];
    }
}
