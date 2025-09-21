<?php

namespace App\Http\Requests\FamilyMember;

use App\Models\FamilyMember;
use Illuminate\Foundation\Http\FormRequest;

class FamilyMemberUpdateRequest extends FormRequest
{

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . FamilyMember::find($this->route('family_member'))->user->id,
            'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'identity_number' => 'nullable|string|max:16|min:16|unique:family_members,identity_number,' . $this->route('family_member'),
            'gender' => 'required|string|in:male,female',
            'date_of_birth' => 'required|date',
            'phone_number' => 'nullable|string|max:16|unique:family_members,phone_number,' . $this->route('family_member'),
            'occupation' => 'required|string|max:255',
            'marital_status' => 'required|string|in:single,married',
            'relation' => 'required|string|in:wife,child,husband',
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
            'relation' => 'Hubungan dengan Kepala Keluarga',
        ];
    }
}
