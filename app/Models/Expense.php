<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Expense extends Model
{
    use HasFactory;

    protected $table = 'expenses';

    /**
     * @var list<string>
     */
    protected $fillable = [
        'expense_type_id',
        'treasury_id',
        'contract_tracker_id',
        'supplier_id',
        'admin_id',
        'conversion_id',
        'currency',
        'amount',
        'other_details',
        'is_auto',
        'transaction_date',
        'notes',
        'image',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'is_auto' => 'boolean',
            'transaction_date' => 'date',
        ];
    }

    public function expenseType(): BelongsTo
    {
        return $this->belongsTo(ExpenseType::class);
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

    public function admin(): BelongsTo
    {
        return $this->belongsTo(Admin::class);
    }

    public function conversion(): BelongsTo
    {
        return $this->belongsTo(Conversion::class);
    }
}
