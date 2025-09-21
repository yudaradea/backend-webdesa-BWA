<?php

namespace App\Http\Requests\SocialAssistance;

use Illuminate\Foundation\Http\FormRequest;

class SocialAssistanceUpdateRequest extends FormRequest
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
            'category' => 'required|string|in:staple,cash,subsidized fuel,health',
            'amount' => 'required|numeric|min:0',
            'provider' => 'required|string|max:255',
            'description' => 'required|string',
            'is_available' => 'required|boolean',
        ];
    }

    public function attributes()
    {
        return [
            'thumbnail' => 'Thumbnail',
            'name' => 'Nama',
            'category' => 'Kategori',
            'amount' => 'Jumlah',
            'provider' => 'Penyedia',
            'description' => 'Deskripsi',
            'is_available' => 'Status Ketersediaan',
        ];
    }
}
