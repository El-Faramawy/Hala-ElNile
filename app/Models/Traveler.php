<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Traveler extends Model
{
    use HasFactory;

    protected $table = 'travelers';

    /**
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'governorate',
        'job_title',
        'location',
        'italy_city_id',
        'italy_landmark_id',
        'badge',
        'landmark_image',
        'avatar',
        'story',
        'contract_code',
        'rating',
        'sort_order',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'italy_city_id' => 'integer',
            'italy_landmark_id' => 'integer',
            'rating' => 'integer',
            'sort_order' => 'integer',
            'is_active' => 'boolean',
        ];
    }

    /**
     * @return BelongsTo<ItalyCity, $this>
     */
    public function italyCity(): BelongsTo
    {
        return $this->belongsTo(ItalyCity::class, 'italy_city_id');
    }

    /**
     * @return BelongsTo<ItalyLandmark, $this>
     */
    public function italyLandmark(): BelongsTo
    {
        return $this->belongsTo(ItalyLandmark::class, 'italy_landmark_id');
    }

    public function getEffectiveLandmarkImageAttribute(): string
    {
        if (! empty($this->landmark_image)) {
            return $this->landmark_image;
        }

        if ($this->italyLandmark?->image) {
            return $this->italyLandmark->image;
        }

        if ($this->italyCity?->image) {
            return $this->italyCity->image;
        }

        return 'Site/assets/images/hero/slide-travelers-italy.jpg';
    }

    public function getEffectiveLocationAttribute(): string
    {
        if ($this->italyLandmark) {
            $cityName = $this->italyCity?->name ?? '';

            return $cityName !== '' ? $cityName.' - '.$this->italyLandmark->name : $this->italyLandmark->name;
        }

        if ($this->italyCity) {
            return $this->italyCity->name;
        }

        return $this->location ?? 'إيطاليا';
    }
}
