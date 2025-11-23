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
        'status',
        'min_price',
        'max_price',
    ];
}
