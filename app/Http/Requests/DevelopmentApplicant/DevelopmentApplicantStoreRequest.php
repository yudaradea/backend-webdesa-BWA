<?php

namespace App\Http\Requests\DevelopmentApplicant;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class DevelopmentApplicantStoreRequest extends FormRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'development_id' => [
                'required',
                'exists:developments,id',

                // Cek keunikan di tabel 'development_applicants' agar tidak ada duplikasi data user yang bergabung dalam development yang sama
                Rule::unique('development_applicants')->where(function ($query) {
                    // dimana 'user_id' nya adalah ID yang sedang diinput
                    return $query->where('user_id', $this->input('user_id'))
                        ->whereNull('deleted_at');
                }),
            ],
            'user_id' => 'required|exists:users,id',
        ];
    }

    public function attributes()
    {
        return [
            'development_id' => 'development',
            'user_id' => 'user',

        ];
    }

    public function messages()
    {
        return [
            'development_id.unique' => 'User ini sudah terdaftar sebagai pelamar pada development yang sama.',
        ];
    }
}
