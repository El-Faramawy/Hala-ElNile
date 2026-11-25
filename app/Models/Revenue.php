<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Revenue extends Model
{
    use HasFactory;

    protected $table = 'revenues';

    /**
     * @var list<string>
     */
    protected $fillable = [
        'treasury_id',
        'contract_tracker_id',
        'supplier_id',
        'conversion_id',
        'received_currency',
        'amount',
        'eur_equivalent',
        'transaction_date',
        'notes',
        'image',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'eur_equivalent' => 'decimal:2',
            'transaction_date' => 'date',
        ];
    }

    public function treasury(): BelongsTo
    {
        return $this->belongsTo(Treasury::class);
    }

    public function contractTracker(): BelongsTo
    {
        return $this->belongsTo(ContractTracker::class);
    }

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }

    public function conversion(): BelongsTo
    {
        return $this->belongsTo(Conversion::class);
    }
}
