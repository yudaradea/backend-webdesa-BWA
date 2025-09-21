<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class UserUpdatePasswordRequest extends FormRequest
{


    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'old_password' => 'required|string|current_password',
            'password' => 'required|string|min:8|confirmed|different:old_password',
            'password_confirmation' => 'required|string|min:8|same:password',
        ];
    }

    public function attributes()
    {
        return [
            'old_password' => 'Password Lama',
            'password' => 'Password Baru',
            'password_confirmation' => 'Konfirmasi Password Baru',
        ];
    }

    public function messages()
    {
        return [
            'old_password.current_password' => 'Password lama tidak sesuai',
            'password.different' => 'Password baru tidak boleh sama dengan password lama',
            'password.confirmed' => 'Konfirmasi password baru tidak sesuai',
            'password_confirmation.same' => 'Konfirmasi password baru tidak sesuai',
        ];
    }
}
