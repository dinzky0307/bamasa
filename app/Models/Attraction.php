<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Attraction extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'category',
        'description',
        'municipality',
        'address',
        'latitude',
        'longitude',
        'opening_hours',
        'entrance_fee',
        'thumbnail',
        'status',
    ];
}
