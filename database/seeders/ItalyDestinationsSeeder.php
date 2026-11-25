<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\ItalyCity;
use App\Models\ItalyLandmark;
use App\Models\Traveler;
use Illuminate\Database\Seeder;

class ItalyDestinationsSeeder extends Seeder
{
    public function run(): void
    {
        $destinations = [
            [
                'name' => 'ميلانو (Milano)',
                'image' => 'Site/assets/images/travelers/travelers-milan.jpg',
                'sort_order' => 1,
                'landmarks' => [
                    [
                        'name' => 'ساحة وكاتدرائية الدومو (Duomo di Milano)',
                        'image' => 'Site/assets/images/travelers/travelers-milan.jpg',
                        'description' => 'القلب التاريخي النابض لعاصمة الاقتصاد الإيطالي ميلانو.',
                    ],
                    [
                        'name' => 'غاليريا فيتوريو إيمانويل الثاني (Galleria Vittorio Emanuele)',
                        'image' => 'Site/assets/images/travelers/travelers-milan.jpg',
                        'description' => 'أقدم مجمع تسوق نشط في العالم وأحد أبرز معالم لومبارديا.',
                    ],
                ],
            ],
            [
                'name' => 'روما (Roma)',
                'image' => 'Site/assets/images/travelers/travelers-colosseum.jpg',
                'sort_order' => 2,
                'landmarks' => [
                    [
                        'name' => 'مدرج الكولوسيوم التاريخي (Colosseo)',
                        'image' => 'Site/assets/images/travelers/travelers-colosseum.jpg',
                        'description' => 'رمز العاصمة الإيطالية روما وأحد عجائب الدنيا الشهيرة.',
                    ],
                    [
                        'name' => 'نافورة تريفي والبانثيون (Fontana di Trevi)',
                        'image' => 'Site/assets/images/travelers/travelers-colosseum.jpg',
                        'description' => 'أشهر نافورة كلاسيكية في وسط العاصمة روما.',
                    ],
                ],
            ],
            [
                'name' => 'بريشيا ولومبارديا (Brescia)',
                'image' => 'Site/assets/images/travelers/travelers-bologna.jpg',
                'sort_order' => 3,
                'landmarks' => [
                    [
                        'name' => 'ساحة ديلا لوجيا وقلعة بريشيا (Piazza della Loggia)',
                        'image' => 'Site/assets/images/travelers/travelers-bologna.jpg',
                        'description' => 'مركز النشاط الصناعي والزراعي في شمال إيطاليا.',
                    ],
                ],
            ],
            [
                'name' => 'فلورنسا وتوسكانا (Firenze)',
                'image' => 'Site/assets/images/travelers/travelers-florence.jpg',
                'sort_order' => 4,
                'landmarks' => [
                    [
                        'name' => 'جسر بونتي فيكيو التاريخي (Ponte Vecchio)',
                        'image' => 'Site/assets/images/travelers/travelers-florence.jpg',
                        'description' => 'أشهر جسور نهر أرنو في إقليم توسكانا الساحر.',
                    ],
                ],
            ],
            [
                'name' => 'تورينو وبييمونتي (Torino)',
                'image' => 'Site/assets/images/travelers/travelers-turin.jpg',
                'sort_order' => 5,
                'landmarks' => [
                    [
                        'name' => 'برج ومتحف كاستيلو (Mole Antonelliana)',
                        'image' => 'Site/assets/images/travelers/travelers-turin.jpg',
                        'description' => 'المعلم المعماري الأبرز في مدينة تورينو الصناعية.',
                    ],
                ],
            ],
            [
                'name' => 'فينيسيا والبندقية (Venezia)',
                'image' => 'Site/assets/images/travelers/travelers-venice.jpg',
                'sort_order' => 6,
                'landmarks' => [
                    [
                        'name' => 'جسر ريالتو وساحة سان ماركو (Piazza San Marco)',
                        'image' => 'Site/assets/images/travelers/travelers-venice.jpg',
                        'description' => 'قلب مدينة البندقية التاريخية ومركز الجولات والضيافة العالمية.',
                    ],
                ],
            ],
        ];

        foreach ($destinations as $destData) {
            $city = ItalyCity::updateOrCreate(
                ['name' => $destData['name']],
                [
                    'image' => $destData['image'],
                    'sort_order' => $destData['sort_order'],
                    'is_active' => true,
                ]
            );

            foreach ($destData['landmarks'] as $index => $landmarkData) {
                ItalyLandmark::updateOrCreate(
                    [
                        'italy_city_id' => $city->id,
                        'name' => $landmarkData['name'],
                    ],
                    [
                        'image' => $landmarkData['image'],
                        'description' => $landmarkData['description'],
                        'sort_order' => $index + 1,
                        'is_active' => true,
                    ]
                );
            }
        }

        // Link existing travelers with Italy cities/landmarks if matching
        $milanCity = ItalyCity::where('name', 'like', '%ميلانو%')->first();
        $milanLandmark = $milanCity?->landmarks()->first();
        $romeCity = ItalyCity::where('name', 'like', '%روما%')->first();
        $romeLandmark = $romeCity?->landmarks()->first();
        $bresciaCity = ItalyCity::where('name', 'like', '%بريشيا%')->first();
        $bresciaLandmark = $bresciaCity?->landmarks()->first();
        $florenceCity = ItalyCity::where('name', 'like', '%فلورنسا%')->first();
        $florenceLandmark = $florenceCity?->landmarks()->first();
        $turinCity = ItalyCity::where('name', 'like', '%تورينو%')->first();
        $turinLandmark = $turinCity?->landmarks()->first();
        $veniceCity = ItalyCity::where('name', 'like', '%فينيسيا%')->first();
        $veniceLandmark = $veniceCity?->landmarks()->first();

        $travelers = Traveler::all();
        foreach ($travelers as $traveler) {
            $loc = (string) $traveler->location;
            if (str_contains($loc, 'ميلانو') || str_contains($loc, 'دومو')) {
                $traveler->update(['italy_city_id' => $milanCity?->id, 'italy_landmark_id' => $milanLandmark?->id]);
            } elseif (str_contains($loc, 'روما') || str_contains($loc, 'كولوسيوم')) {
                $traveler->update(['italy_city_id' => $romeCity?->id, 'italy_landmark_id' => $romeLandmark?->id]);
            } elseif (str_contains($loc, 'بريشيا') || str_contains($loc, 'بولونيا')) {
                $traveler->update(['italy_city_id' => $bresciaCity?->id, 'italy_landmark_id' => $bresciaLandmark?->id]);
            } elseif (str_contains($loc, 'فلورنسا') || str_contains($loc, 'توسكانا')) {
                $traveler->update(['italy_city_id' => $florenceCity?->id, 'italy_landmark_id' => $florenceLandmark?->id]);
            } elseif (str_contains($loc, 'تورينو') || str_contains($loc, 'بييمونتي')) {
                $traveler->update(['italy_city_id' => $turinCity?->id, 'italy_landmark_id' => $turinLandmark?->id]);
            } elseif (str_contains($loc, 'فينيسيا') || str_contains($loc, 'البندقية')) {
                $traveler->update(['italy_city_id' => $veniceCity?->id, 'italy_landmark_id' => $veniceLandmark?->id]);
            } else {
                $traveler->update(['italy_city_id' => $romeCity?->id, 'italy_landmark_id' => $romeLandmark?->id]);
            }
        }
    }
}
