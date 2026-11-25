<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ItalyLandmark extends Model
{
    use HasFactory;

    protected $table = 'italy_landmarks';

    /**
     * @var list<string>
     */
    protected $fillable = [
        'italy_city_id',
        'name',
        'image',
        'description',
        'sort_order',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'italy_city_id' => 'integer',
            'sort_order' => 'integer',
            'is_active' => 'boolean',
        ];
    }

    /**
     * @return BelongsTo<ItalyCity, $this>
     */
    public function city(): BelongsTo
    {
        return $this->belongsTo(ItalyCity::class, 'italy_city_id');
    }

    /**
     * @return HasMany<Traveler, $this>
     */
    public function travelers(): HasMany
    {
        return $this->hasMany(Traveler::class, 'italy_landmark_id');
    }
}
