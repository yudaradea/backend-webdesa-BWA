<?php

namespace App\Http\Resources;

use App\Http\Resources\FamilyMemberResource;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class HeadOfFamilyResource extends JsonResource
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
            // memunculkan data dari user dengan relasi belongsTo
            'user' => new UserResource($this->whenLoaded('user')),
            'profile_picture' => $this->profile_picture,
            'identity_number' => $this->identity_number,
            'gender' => $this->gender,
            'date_of_birth' => $this->date_of_birth,
            'phone_number' => $this->phone_number,
            'occupation' => $this->occupation,
            'marital_status' => $this->marital_status,
            'family_members' => FamilyMemberResource::collection($this->whenLoaded('familyMembers')),
            'sosial_assistance' => SocialAssistanceRecipientResource::collection($this->whenLoaded('socialAssistanceRecipients'))
        ];
    }
}
