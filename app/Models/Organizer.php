<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Organizer extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'business_name',
        'email',
        'password',
        'phone',
        'status',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }

    public function events(): HasMany
    {
        return $this->hasMany(Event::class);
    }

    public function isActive(): bool
    {
        return $this->status === 'active';
    }
}
