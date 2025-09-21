<?php

namespace App\Http\Requests\HeadOfFamily;

use Illuminate\Foundation\Http\FormRequest;

class HeadOfFamilyStoreRequest extends FormRequest
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
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'password_confirmation' => 'required|string|min:8',
            'profile_picture' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'identity_number' => 'required|string|max:16|min:16|unique:head_of_families,identity_number',
            'gender' => 'required|string|in:male,female',
            'date_of_birth' => 'required|date',
            'phone_number' => 'required|string|max:16|unique:head_of_families,phone_number',
            'occupation' => 'required|string|max:255',
            'marital_status' => 'required|string|in:single,married',
        ];
    }

    public function attributes()
    {
        return [
            'name' => 'nama',
            'email' => 'email',
            'password' => 'kata sandi',
            'password_confirmation' => 'konfirmasi kata sandi',
            'profile_picture' => 'foto profil',
            'identity_number' => 'nomor identitas',
            'gender' => 'jenis kelamin',
            'date_of_birth' => 'tanggal lahir',
            'phone_number' => 'nomor telepon',
            'occupation' => 'pekerjaan',
            'marital_status' => 'status pernikahan',
        ];
    }
}
