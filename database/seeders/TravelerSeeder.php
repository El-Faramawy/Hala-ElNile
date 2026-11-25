<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Traveler;
use Illuminate\Database\Seeder;

class TravelerSeeder extends Seeder
{
    public function run(): void
    {
        $travelers = [
            [
                'name' => 'أحمد صابر عبد السلام',
                'governorate' => 'محافظة الغربية',
                'job_title' => 'فني تشييد وبناء بروما',
                'location' => 'روما - الكولوسيوم',
                'badge' => 'عقد دائم موثق',
                'landmark_image' => 'travelers/travelers-colosseum.jpg',
                'avatar' => 'travelers/person1.jpg',
                'story' => 'الحمد لله سافرت عن طريق شركة هلا النيل في مارس 2024. كل كلمة قالوهالي في المكتب بالقاهرة لقيتها في روما حرفياً: السكن محترم، الراتب 1,850 يورو بينزل في البنك، والمندوب استقبلني من المطار.',
                'contract_code' => 'HS-5120',
                'rating' => 5,
                'sort_order' => 1,
                'is_active' => true,
            ],
            [
                'name' => 'محمود السيد رجب',
                'governorate' => 'محافظة الشرقية',
                'job_title' => 'مساعد شيف بميلانو',
                'location' => 'ميلانو - ساحة الدومو',
                'badge' => 'شيف مطعم إيطالي',
                'landmark_image' => 'travelers/travelers-milan.jpg',
                'avatar' => 'travelers/person2.jpg',
                'story' => 'الموضوع كان بالنسبة لي حلم، وكنت خايف من السماسرة. هلا النيل شركة محترمة جداً ومرخصة، استلمت النولا أوستا في يدي وبصمت في السفارة بجاردن سيتي وطلعت التأشيرة رسمية 100%.',
                'contract_code' => 'HS-6430',
                'rating' => 5,
                'sort_order' => 2,
                'is_active' => true,
            ],
            [
                'name' => 'إسلام عبد العزيز المنياوي',
                'governorate' => 'محافظة المنيا',
                'job_title' => 'مزارع عنب وزيتون',
                'location' => 'توسكانا - فلورنسا',
                'badge' => 'عمالة زراعية فلوتسي',
                'landmark_image' => 'travelers/travelers-florence.jpg',
                'avatar' => 'travelers/person3.jpg',
                'story' => 'سافرت ضمن كوتة الفلوتسي الزراعية لمزارع توسكانا، المعاملة ممتازة، وساعات العمل 8 ساعات مع سكن المزرعة شامل، وفرصة تجديد العقد لإقامة دائمة متاحة وسهلة بعد السنة الأولى.',
                'contract_code' => 'HS-7110',
                'rating' => 5,
                'sort_order' => 3,
                'is_active' => true,
            ],
            [
                'name' => 'هاني فوزي القليوبي',
                'governorate' => 'محافظة القليوبية',
                'job_title' => 'سائق شاحنات لوجستية ببولونيا',
                'location' => 'بولونيا - المركز اللوجستي',
                'badge' => 'سائق نقل ثقيل معتمد',
                'landmark_image' => 'travelers/travelers-bologna.jpg',
                'avatar' => 'travelers/person4.jpg',
                'story' => 'حصلت على رخصة القيادة المهنية وساعدتني شركة هلا النيل في معادلتها هناك بعد اجتياز دورة تدريبية سريعة، الدخل ممتاز جداً والتعامل راقي جداً من أصحاب العمل الإيطاليين.',
                'contract_code' => 'HS-7730',
                'rating' => 5,
                'sort_order' => 4,
                'is_active' => true,
            ],
            [
                'name' => 'كريم عادل الدسوقي',
                'governorate' => 'محافظة الدقهلية',
                'job_title' => 'ضيافة فندقية بفينيسيا',
                'location' => 'فينيسيا - سان ماركو',
                'badge' => 'منتجعات سياحية بفينيسيا',
                'landmark_image' => 'travelers/travelers-venice.jpg',
                'avatar' => 'travelers/person5.jpg',
                'story' => 'العمل في قطاع الفندقة في فينيسيا كان تجربة غيرت مجرى حياتي. الشركة وفرت لي الإقامة والوجبات كاملة داخل الفندق، واستلمت بطاقة الإقامة والتأمين الصحي في أول أسبوعين.',
                'contract_code' => 'HS-8055',
                'rating' => 5,
                'sort_order' => 5,
                'is_active' => true,
            ],
            [
                'name' => 'محمد حسن عشري',
                'governorate' => 'محافظة الجيزة',
                'job_title' => 'فني كهرباء بتورينو',
                'location' => 'تورينو - بييمونتي',
                'badge' => 'فني تمديدات كهربائية',
                'landmark_image' => 'travelers/travelers-turin.jpg',
                'avatar' => 'travelers/person6.jpg',
                'story' => 'عقد عمل غير موسمي رسمي ومصدق، الراتب في ميعاده ومقر السكن مجهز بكل ما يحتاجه الفرد. هلا النيل أفضل شركة تعاملت معها، التزام بالمواعيد ومصداقية كاملة من أول مقابلة.',
                'contract_code' => 'HS-9140',
                'rating' => 5,
                'sort_order' => 6,
                'is_active' => true,
            ],
        ];

        foreach ($travelers as $t) {
            Traveler::updateOrCreate(
                ['name' => $t['name']],
                $t
            );
        }
    }
}
