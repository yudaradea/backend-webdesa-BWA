<?php

namespace App\Http\Requests\Event;

use Illuminate\Foundation\Http\FormRequest;

class EventUpdateRequest extends FormRequest
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
            'price' => 'required|numeric|min:0',
            'date' => 'required|date',
            'time' => 'required|date_format:H:i',
            'is_active' => 'required|boolean'
        ];
    }

    public function attributes()
    {
        return [
            'thumbnail' => 'Gambar Event',
            'name' => 'Nama Event',
            'description' => 'Deskripsi Event',
            'price' => 'Total Hadiah Event',
            'date' => 'Tanggal Event',
            'time' => 'Waktu Event',
            'is_active' => 'Status Event'
        ];
    }
}
