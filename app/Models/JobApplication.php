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
        'code',
        'job_id',
        'city_id',
        'job_title',
        'name',
        'phone',
        'age',
        'passport_situation',
        'passport_number',
        'notes',
        'status',
        'cancellation_reason',
        'stage_history',
        'contract_tracker_id',
        'converted_to_contract_at',
        'admin_notes',
    ];

    protected function casts(): array
    {
        return [
            'age' => 'integer',
            'stage_history' => 'array',
            'converted_to_contract_at' => 'datetime',
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

    public function contractTracker(): BelongsTo
    {
        return $this->belongsTo(ContractTracker::class, 'contract_tracker_id');
    }
}
