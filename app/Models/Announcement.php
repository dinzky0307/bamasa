<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Announcement extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'slug',
        'excerpt',
        'body',
        'municipality_scope',
        'status',
        'published_at',
        'image_path',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
