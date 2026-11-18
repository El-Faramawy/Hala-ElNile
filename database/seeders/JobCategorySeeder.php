<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\JobCategory;
use Illuminate\Database\Seeder;

class JobCategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'name'       => 'مطاعم وفندقة',
                'icon'       => 'fa-solid fa-utensils',
                'sort_order' => 1,
            ],
            [
                'name'       => 'عمالة زراعية',
                'icon'       => 'fa-solid fa-seedling',
                'sort_order' => 2,
            ],
            [
                'name'       => 'تشييد وبناء',
                'icon'       => 'fa-solid fa-trowel-bricks',
                'sort_order' => 3,
            ],
            [
                'name'       => 'نقل ولوجستيات',
                'icon'       => 'fa-solid fa-truck-fast',
                'sort_order' => 4,
            ],
            [
                'name'       => 'صيانة ومهن حرفية',
                'icon'       => 'fa-solid fa-wrench',
                'sort_order' => 5,
            ],
        ];

        foreach ($categories as $categoryData) {
            JobCategory::updateOrCreate(
                ['name' => $categoryData['name']],
                [
                    'icon'       => $categoryData['icon'],
                    'sort_order' => $categoryData['sort_order'],
                    'is_active'  => true,
                ]
            );
        }
    }
}
