<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Company extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'nom',
        'slug',
        'email',
        'telephone',
        'pays',
        'devise',
        'timezone',
        'status',
    ];

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(
            User::class,
            'company_users'
        )->withPivot('role', 'status', 'joined_at')
         ->withTimestamps();
    }
}