<?php

namespace App\Http\Requests\SocialAssistanceRecipient;

use App\Models\SocialAssistanceRecipient;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SocialAssistanceRecipientUpdateRequest extends FormRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        // Ambil ID dari record yang sedang di-update
        $recipientId = $this->route('social_assistance_recipient');
        return [
            'social_assistance_id' => [
                'required',
                'exists:social_assistances,id',
                // Pastikan field social_assistance_id dan head_of_family_id unik, agar tidak ada duplikasi penerima bantuan sosial yang sama
                // KECUALI untuk record yang sedang di-update
                Rule::unique('social_assistance_recipients')->where(function ($query) {
                    return $query->where('head_of_family_id', $this->input('head_of_family_id'))
                        ->whereNull('deleted_at');
                })->ignore($recipientId),
            ],
            'head_of_family_id' => 'required|exists:head_of_families,id',
            'amount' => 'required|numeric',
            'reason' => 'required|string',
            'bank' => 'required|string|in:bri,bni,bca,mandiri',
            'account_number' => 'required|integer',
            'proof' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'status' => 'nullable|in:pending,approved,rejected',
        ];
    }

    public function attributes()
    {
        return [
            'social_assistance_id' => 'bantuan sosial',
            'head_of_family_id' => 'kepala keluarga',
            'amount' => 'jumlah bantuan yang diterima',
            'reason' => 'alasan bantuan sosial diberikan',
            'bank' => 'bank yang digunakan',
            'acount_number' => 'nomor rekening',
            'proof' => 'bukti Pembayaran',
            'status' => 'status',
        ];
    }

    public function messages()
    {
        return [
            'social_assistance_id.unique' => 'Kepala keluarga ini sudah pernah menerima bantuan sosial yang sama.',
        ];
    }
}
