<?php

namespace App\Http\Requests\Development;

use Illuminate\Foundation\Http\FormRequest;

class DevelopmentUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'person_in_charge' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'amount' => 'required|numeric|min:0',
            'status' => 'required|in:ongoing,completed'
        ];
    }

    public function attributes()
    {
        return [
            'thumbnail' => 'Gambar Proyek',
            'name' => 'Nama Proyek',
            'description' => 'Deskripsi Proyek',
            'person_in_charge' => 'Penanggung Jawab',
            'start_date' => 'Tanggal Mulai',
            'end_date' => 'Tanggal Selesai',
            'amount' => 'Jumlah Dana',
            'status' => 'Status Proyek'
        ];
    }
}
