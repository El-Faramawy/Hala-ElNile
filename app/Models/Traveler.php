<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
            'rating' => 'integer',
            'sort_order' => 'integer',
            'is_active' => 'boolean',
        ];
    }
}
