<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Job extends Model
{
    use HasFactory;

    protected $table = 'jobs';

    /**
     * @var list<string>
     */
    protected $fillable = [
        'job_category_id',
        'title',
        'city',
        'salary_min',
        'salary_max',
        'salary_text',
        'salary_period',
        'contract_type',
        'badge',
        'image',
        'housing',
        'meals',
        'insurance',
        'working_hours',
        'experience',
        'available_seats',
        'description',
        'requirements',
        'sort_order',
        'is_featured',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'requirements' => 'array',
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
            'sort_order' => 'integer',
            'available_seats' => 'integer',
            'salary_min' => 'integer',
            'salary_max' => 'integer',
        ];
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(JobCategory::class, 'job_category_id');
    }

    public function applications(): HasMany
    {
        return $this->hasMany(JobApplication::class);
    }
}
