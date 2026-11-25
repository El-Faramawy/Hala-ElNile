<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ItalyCity extends Model
{
    use HasFactory;

    protected $table = 'italy_cities';

    /**
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'image',
        'sort_order',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'sort_order' => 'integer',
            'is_active' => 'boolean',
        ];
    }

    /**
     * @return HasMany<ItalyLandmark, $this>
     */
    public function landmarks(): HasMany
    {
        return $this->hasMany(ItalyLandmark::class, 'italy_city_id')->orderBy('sort_order', 'asc');
    }

    /**
     * @return HasMany<Traveler, $this>
     */
    public function travelers(): HasMany
    {
        return $this->hasMany(Traveler::class, 'italy_city_id');
    }
}
