<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Testimonial extends Model
{
    use HasUuids;
    
    protected $table = 'crown_testimonials';

    protected $fillable = [
        'name',
        'country',
        'rating',
        'comment',
        'photo',
        'video_url',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'rating' => 'integer',
    ];
}
