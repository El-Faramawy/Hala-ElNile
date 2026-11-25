<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\City;
use App\Models\ContactMessage;
use Illuminate\Database\Seeder;

class ContactMessageSeeder extends Seeder
{
    public function run(): void
    {
        $cairo = City::where('name', 'القاهرة')->first()?->id;
        $alex = City::where('name', 'الإسكندرية')->first()?->id;

        $messages = [
            [
                'city_id' => $cairo,
                'name' => 'خالد عبد الوهاب النجار',
                'phone' => '01234567890',
                'subject' => 'استفسار عن عقود المطاعم والفندقة (ميلانو)',
                'message' => 'السلام عليكم ورحمة الله، أود الاستفسار عن تفاصيل عقد مساعد الشيف في ميلانو، وهل المقابلة في مقر الشركة بالقاهرة أم أونلاين؟',
                'is_read' => false,
                'notes' => null,
            ],
            [
                'city_id' => $alex,
                'name' => 'سامح إمام طه السيد',
                'phone' => '01011223344',
                'subject' => 'استفسار عن عقود الزراعة الموسمية (توسكانا)',
                'message' => 'مرحباً، أود معرفة موعد فتح كوتة الفلوتسي الزراعية القادمة والأوراق اللازم تصديقها من الخارجية.',
                'is_read' => true,
                'notes' => 'تم الرد هاتفياً وشرح الإجراءات.',
            ],
        ];

        foreach ($messages as $msg) {
            ContactMessage::updateOrCreate(
                ['phone' => $msg['phone']],
                $msg
            );
        }
    }
}
