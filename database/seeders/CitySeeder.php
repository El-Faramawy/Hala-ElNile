<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\City;
use Illuminate\Database\Seeder;

class CitySeeder extends Seeder
{
    public function run(): void
    {
        $egyptianCities = [
            'القاهرة',
            'الجيزة',
            'الإسكندرية',
            'الدقهلية',
            'الشرقية',
            'المنوفية',
            'القليوبية',
            'الغربية',
            'البحيرة',
            'كفر الشيخ',
            'دمياط',
            'بورسعيد',
            'الإسماعيلية',
            'السويس',
            'الفيوم',
            'بني سويف',
            'المنيا',
            'أسيوط',
            'سوهاج',
            'قنا',
            'الأقصر',
            'أسوان',
            'البحر الأحمر',
            'الوادي الجديد',
            'مطروح',
            'شمال سيناء',
            'جنوب سيناء',
        ];

        $order = 1;
        foreach ($egyptianCities as $cityName) {
            City::updateOrCreate(
                ['name' => $cityName],
                [
                    'sort_order' => $order++,
                    'is_active'  => true,
                ]
            );
        }
    }
}
