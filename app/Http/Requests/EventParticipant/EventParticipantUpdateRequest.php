<?php

namespace App\Http\Requests\EventParticipant;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class EventParticipantUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        // Ambil ID dari record yang sedang di-update
        $participantId = $this->route('event_participant');
        return [
            'event_id' => [
                'required',
                'exists:events,id',

                // pastikan field event_id dan head_of_family_id unik, agar tidak ada duplikasi peserta event yang sama
                // KECUALI untuk record yang sedang di-update
                Rule::unique('event_participants')->where(function ($query) {
                    return $query->where('head_of_family_id', $this->input('head_of_family_id'))
                        ->whereNull('deleted_at');
                })->ignore($participantId),
            ],

            'head_of_family_id' => 'required|exists:head_of_families,id',
            'quantity' => 'required|integer|min:1',
            'total_price' => 'required|numeric|min:0',
            'payment_status' => 'required|in:pending,paid,cancelled',
        ];
    }

    public function attributes()
    {
        return [
            'event_id' => 'event',
            'head_of_family_id' => 'kepala keluarga',
            'quantity' => 'jumlah peserta',
            'total_price' => 'total harga',
            'payment_status' => 'status pembayaran',
        ];
    }

    public function messages()
    {
        return [
            'event_id.unique' => 'Kepala keluarga ini sudah terdaftar sebagai peserta event yang sama.',
        ];
    }
}
