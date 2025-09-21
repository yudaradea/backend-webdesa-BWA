<?php

namespace App\Http\Resources;

use App\Http\Resources\HeadOfFamilyResource;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FamilyMemberResource extends JsonResource
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
            // memunculkan data dari user dengan relasi belongsTo, dan hanya ketika relasi user di load
            'user' => new UserResource($this->user),
            'profile_picture' => $this->profile_picture,
            'identity_number' => $this->identity_number,
            'gender' => $this->gender,
            'date_of_birth' => $this->date_of_birth,
            'phone_number' => $this->phone_number,
            'occupation' => $this->occupation,
            'marital_status' => $this->marital_status,
            'relation' => $this->relation,
            // memunculkan data dari headOfFamily dengan relasi belongsTo, dan hanya ketika relasi headOfFamily di load
            'head_of_family' => new HeadOfFamilyResource($this->whenLoaded('headOfFamily')),
        ];
    }
}
