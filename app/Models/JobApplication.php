<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class JobApplication extends Model
{
    use HasFactory;

    protected $table = 'job_applications';

    /**
     * @var list<string>
     */
    protected $fillable = [
        'job_id',
        'city_id',
        'job_title',
        'name',
        'phone',
        'age',
        'passport_situation',
        'notes',
        'status',
        'admin_notes',
    ];

    protected function casts(): array
    {
        return [
            'age' => 'integer',
        ];
    }

    public function job(): BelongsTo
    {
        return $this->belongsTo(Job::class);
    }

    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class);
    }
}
