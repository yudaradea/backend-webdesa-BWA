<?php

namespace App\Http\Requests\HeadOfFamily;

use App\Models\HeadOfFamily;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;

class HeadOfFamilyUpdateRequest extends FormRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {

        return [
            'name' => 'required|string|max:255',
            'email' => 'nullable|string|email|max:255|unique:users,email,' . HeadOfFamily::find($this->route('head_of_family'))->user_id,
            'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'identity_number' => 'required|string|max:16|min:16|unique:head_of_families,identity_number,' . $this->route('head_of_family'),
            'gender' => 'required|string|in:male,female',
            'date_of_birth' => 'required|date',
            'phone_number' => 'required|string|max:16|unique:head_of_families,phone_number,' . $this->route('head_of_family'),
            'occupation' => 'required|string|max:255',
            'marital_status' => 'required|string|in:single,married',
        ];
    }

    public function attributes()
    {
        return [
            'name' => 'Nama',
            'email' => 'Email',
            'profile_picture' => 'Foto Profil',
            'identity_number' => 'Nomor Identitas',
            'gender' => 'Jenis Kelamin',
            'date_of_birth' => 'Tanggal Lahir',
            'phone_number' => 'Nomor Telepon',
            'occupation' => 'Pekerjaan',
            'marital_status' => 'Status Perkawinan',
        ];
    }
}
