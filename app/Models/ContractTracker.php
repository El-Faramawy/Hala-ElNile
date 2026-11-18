<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContractTracker extends Model
{
    use HasFactory;

    protected $table = 'contract_trackers';

    /**
     * @var list<string>
     */
    protected $fillable = [
        'contract_code',
        'passport_number',
        'applicant_name',
        'job_title',
        'city_name',
        'step',
        'status_text',
        'issue_date',
        'embassy_appointment',
        'flight_details',
        'notes',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'step' => 'integer',
            'is_active' => 'boolean',
            'issue_date' => 'date',
        ];
    }
}
