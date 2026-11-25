<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ContractTracker extends Model
{
    use HasFactory;

    protected $table = 'contract_trackers';

    /**
     * @var list<string>
     */
    protected $fillable = [
        'job_application_id',
        'contract_code',
        'passport_number',
        'applicant_name',
        'total_price_eur',
        'job_title',
        'city_name',
        'step',
        'status_text',
        'issue_date',
        'embassy_appointment',
        'flight_details',
        'notes',
        'stage_history',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'step' => 'integer',
            'total_price_eur' => 'decimal:2',
            'is_active' => 'boolean',
            'issue_date' => 'date',
            'stage_history' => 'array',
        ];
    }

    public function jobApplication(): BelongsTo
    {
        return $this->belongsTo(JobApplication::class);
    }

    public function installments(): HasMany
    {
        return $this->hasMany(Installment::class);
    }

    public function revenues(): HasMany
    {
        return $this->hasMany(Revenue::class);
    }

    public function expenses(): HasMany
    {
        return $this->hasMany(Expense::class);
    }
}
