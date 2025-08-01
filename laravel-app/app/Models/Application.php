<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Application extends Model
{
    use HasUuids;
    
    protected $table = 'crown_applications';

    protected $fillable = [
        'job_id',
        'first_name',
        'last_name',
        'email',
        'phone',
        'current_location',
        'profile_photo',
        'cv_file',
        'cover_letter',
        'experience',
        'previous_role',
        'status'
    ];

    public function job(): BelongsTo
    {
        return $this->belongsTo(Job::class);
    }
}
