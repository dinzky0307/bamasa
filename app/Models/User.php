<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

// use Laravel\Sanctum\HasApiTokens; // if using API tokens

class User extends Authenticatable
{
    use Notifiable; // , HasApiTokens; // if needed
    use HasFactory; // ← add HasFactory here
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'municipality',
        'lgu_logo',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    // Relationships
    public function business()
    {
        return $this->hasOne(Business::class);
    }

    public function bookings()
    {
    return $this->hasMany(\App\Models\Booking::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    // Role helpers
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isBusiness(): bool
    {
        return $this->role === 'business';
    }

    public function isTourist(): bool
    {
        return $this->role === 'tourist';
    }
    
}
