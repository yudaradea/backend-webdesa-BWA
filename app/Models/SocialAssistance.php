<?php

namespace App\Models;

use App\Traits\UUID;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class SocialAssistance extends Model
{
    use SoftDeletes, UUID;

    protected $fillable = [
        'thumbnail',
        'name',
        'category',
        'amount',
        'provider',
        'description',
        'is_available',
    ];

    protected $casts = [
        'is_available' => 'boolean',
    ];

    public function scopeSearch($query, $search)
    {
        $query->where('name', 'like', "%$search%")
            ->orWhere('category', 'like', "%$search%")
            ->orWhere('provider', 'like', "%$search%");
    }

    public function socialAssistanceRecipients()
    {
        return $this->hasMany(SocialAssistanceRecipient::class);
    }
}
