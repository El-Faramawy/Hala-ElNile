<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    /**
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'logo',
        'fav_icon',
        'phone',
        'whatsapp',
        'email',
        'address',
        'license_number',
        'commercial_record',
        'tax_card',
        'working_hours',
        'facebook',
        'twitter',
        'telegram',
        'youtube',
        'about_us',
        'terms_conditions',
        'privacy_policy',
        'stats_travelers',
        'stats_contracts',
        'stats_companies',
        'stats_experience',
    ];

    public function getLogoAttribute(?string $value): ?string
    {
        if (empty($value)) {
            return request()->is('admin*') ? null : get_file(null);
        }

        return get_file($value);
    }

    public function getFavIconAttribute(?string $value): ?string
    {
        if (empty($value)) {
            return request()->is('admin*') ? null : get_file(null);
        }

        return get_file($value);
    }
}
