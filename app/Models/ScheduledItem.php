<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ScheduledItem extends Model
{
    use HasFactory;

    protected $table = 'scheduled_items';

    /**
     * @var list<string>
     */
    protected $fillable = [
        'title',
        'type',
        'frequency',
        'amount',
        'currency',
        'next_due_date',
        'last_paid_at',
        'treasury_id',
        'expense_type_id',
        'supplier_id',
        'notes',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'next_due_date' => 'date',
            'last_paid_at' => 'date',
            'is_active' => 'boolean',
        ];
    }

    public function treasury(): BelongsTo
    {
        return $this->belongsTo(Treasury::class);
    }

    public function expenseType(): BelongsTo
    {
        return $this->belongsTo(ExpenseType::class);
    }

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }
}
