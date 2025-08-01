<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Message extends Model
{
    use HasUuids;
    
    protected $table = 'crown_messages';

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'subject',
        'message',
        'is_read'
    ];

    protected $casts = [
        'is_read' => 'boolean',
    ];
}
