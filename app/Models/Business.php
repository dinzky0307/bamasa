<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Business extends Model
{
    use HasFactory;

    protected $fillable = [
    'user_id',
    'name',
    'category',
    'description',
    'address',
    'municipality',
    'phone',
    'email',
    'website',
    'facebook_page',
    'min_price',
    'max_price',
    'status',
    'thumbnail',        // 👈 add this
    'wizard_completed', // 👈 add this if not there
];


    // 🔥 This is the missing relationship
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
