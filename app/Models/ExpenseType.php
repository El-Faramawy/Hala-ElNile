<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ExpenseType extends Model
{
    use HasFactory;

    protected $table = 'expense_types';

    /**
     * @var list<string>
     */
    protected $fillable = [
        'expense_category_id',
        'name',
        'is_other',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_other' => 'boolean',
            'is_active' => 'boolean',
        ];
    }

    public function expenseCategory(): BelongsTo
    {
        return $this->belongsTo(ExpenseCategory::class, 'expense_category_id');
    }

    public function expenses(): HasMany
    {
        return $this->hasMany(Expense::class);
    }
}
