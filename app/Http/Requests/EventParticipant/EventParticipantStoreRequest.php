<?php

namespace App\Http\Requests\EventParticipant;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class EventParticipantStoreRequest extends FormRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'event_id' => [
                'required',
                'exists:events,id',

                // Cek keunikan di tabel 'event_participants'
                Rule::unique('event_participants')->where(function ($query) {
                    // dimana 'head_of_family_id' nya adalah ID yang sedang diinput
                    return $query->where('head_of_family_id', $this->input('head_of_family_id'))
                        ->whereNull('deleted_at');
                }),
            ],
            'head_of_family_id' => 'required|exists:head_of_families,id',
            'quantity' => 'required|integer|min:1',
            'total_price' => 'required|numeric|min:0',
            'payment_status' => 'required|string|in:pending,paid,cancelled',
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
            'event_id.unique' => 'Kepala keluarga ini sudah terdaftar sebagai peserta pada event yang sama.',
        ];
    }
}
