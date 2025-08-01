<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Category extends Model
{
    use HasUuids;
    
    protected $table = 'crown_categories';

    protected $fillable = [
        'name',
        'description',
        'gif_url',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];
}
