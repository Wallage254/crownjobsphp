<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Job extends Model
{
    use HasUuids;
    
    protected $table = 'crown_jobs';

    protected $fillable = [
        'title',
        'company', 
        'category',
        'location',
        'description',
        'requirements',
        'salary_min',
        'salary_max',
        'job_type',
        'is_urgent',
        'visa_sponsored',
        'company_logo',
        'workplace_images'
    ];

    protected $casts = [
        'is_urgent' => 'boolean',
        'visa_sponsored' => 'boolean',
        'workplace_images' => 'array',
        'salary_min' => 'integer',
        'salary_max' => 'integer',
    ];

    public function applications(): HasMany
    {
        return $this->hasMany(Application::class);
    }
}
