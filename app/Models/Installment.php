<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Installment extends Model
{
    use HasFactory;

    protected $table = 'installments';

    /**
     * @var list<string>
     */
    protected $fillable = [
        'contract_tracker_id',
        'title',
        'amount_eur',
        'due_date',
        'paid_at',
        'status',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'amount_eur' => 'decimal:2',
            'due_date' => 'date',
            'paid_at' => 'date',
        ];
    }

    public function contractTracker(): BelongsTo
    {
        return $this->belongsTo(ContractTracker::class);
    }
}
