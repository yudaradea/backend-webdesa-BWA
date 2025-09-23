<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Traits\UUID;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, UUID, SoftDeletes, HasRoles, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // digunakan untuk melakukan search, yang ada di user repository dengan parameter query $search
    public function scopeSearch($query, $search)
    {
        return $query
            ->where('name', 'like', '%' . $search . '%')
            ->orWhere('email', 'like', '%' . $search . '%');
    }

    public function headOfFamily()
    {
        return $this->hasOne(HeadOfFamily::class);
    }

    public function familyMember()
    {
        return $this->hasOne(FamilyMember::class);
    }

    public function developmentApplicants()
    {
        return $this->hasMany(DevelopmentApplicant::class);
    }
}
