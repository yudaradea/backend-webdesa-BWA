<?php

namespace App\Http\Requests\DevelopmentApplicant;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class DevelopmentApplicantUpdateRequest extends FormRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $userId = $this->route('development_applicant');
        return [
            'development_id' => [
                'required',
                'exists:developments,id',
                // pastikan field development_id dan user_id unik, agar tidak ada duplikasi pelamar pada development yang sama
                // KECUALI untuk record yang sedang di-update
                Rule::unique('development_applicants')->where(function ($query) {
                    return $query->where('user_id', $this->input('user_id'))
                        ->whereNull('deleted_at');
                })->ignore($userId),
            ],
            'user_id' => 'required|exists:users,id',
            'status' => 'required|in:pending,approved,rejected',
        ];
    }

    public function attributes(): array
    {
        return [
            'development_id' => 'development',
            'user_id' => 'user',
            'status' => 'status'
        ];
    }

    public function messages(): array
    {
        return [
            'development_id.unique' => 'User ini sudah melamar pada development yang sama.',
        ];
    }
}
