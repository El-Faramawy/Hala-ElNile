<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Conversion extends Model
{
    use HasFactory;

    protected $table = 'conversions';

    /**
     * @var list<string>
     */
    protected $fillable = [
        'total_egp',
        'expected_eur',
        'actual_eur',
        'diff_eur',
        'diff_type',
        'diff_ref_id',
        'to_treasury_id',
        'conversion_date',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'total_egp' => 'decimal:2',
            'expected_eur' => 'decimal:2',
            'actual_eur' => 'decimal:2',
            'diff_eur' => 'decimal:2',
            'conversion_date' => 'date',
        ];
    }

    public function toTreasury(): BelongsTo
    {
        return $this->belongsTo(Treasury::class, 'to_treasury_id');
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
