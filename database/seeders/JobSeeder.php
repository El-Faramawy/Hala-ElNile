<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Job;
use App\Models\JobCategory;
use Illuminate\Database\Seeder;

class JobSeeder extends Seeder
{
    public function run(): void
    {
        $catHospitality = JobCategory::where('name', 'مطاعم وفندقة')->first()?->id ?? 1;
        $catAgriculture = JobCategory::where('name', 'عمالة زراعية')->first()?->id ?? 2;
        $catConstruction = JobCategory::where('name', 'تشييد وبناء')->first()?->id ?? 3;
        $catLogistics = JobCategory::where('name', 'نقل ولوجستيات')->first()?->id ?? 4;
        $catTechnical = JobCategory::where('name', 'صيانة ومهن حرفية')->first()?->id ?? 5;

        $jobs = [
            [
                'job_category_id' => $catHospitality,
                'title'           => 'مساعد شيف وطهاة مطاعم إيطالية',
                'city'            => 'ميلانو - لومبارديا',
                'salary_min'      => 1550,
                'salary_max'      => 1900,
                'salary_text'     => '€1,550 - €1,900',
                'salary_period'   => 'صافي شهرياً + حوافز',
                'contract_type'   => 'عقد دائم (غير موسمي)',
                'badge'           => 'مطلوب عاجل - 14 مقعداً',
                'image'           => 'jobs/job-restaurant.jpg',
                'housing'         => 'سكن مؤثث مجاني شامل الفواتير',
                'meals'           => 'وجبات طعام يومية أثناء العمل',
                'insurance'       => 'تأمين صحي إيطالي شامل (SSN)',
                'working_hours'   => '8 ساعات يومياً - يوم ونصف راحة',
                'experience'      => 'خبرة لا تقل عن سنتين بمجال المطابخ',
                'available_seats' => 14,
                'description'     => 'العمل بسلسلة مطاعم إيطالية راقية في ضواحي ميلانو. يشمل تجهيز المكونات، المعكرونة، البيتزا، ومساعدة رئيس الطهاة. بيئة عمل ممتازة وتدريب لغوي متزامن.',
                'requirements'    => [
                    'جواز سفر ساري لمدة سنتين على الأقل',
                    'شهادة صحية معتمدة وخلو من الأمراض',
                    'شهادة قياس مهارة أو خبرة سابقة بمطاعم',
                    'الاستعداد لتعلم أساسيات اللغة الإيطالية',
                ],
                'is_featured'     => true,
                'is_active'       => true,
                'sort_order'      => 1,
            ],
            [
                'job_category_id' => $catAgriculture,
                'title'           => 'عمالة زراعية ومزارع زيتون وعنب',
                'city'            => 'إقليم توسكانا - فلورنسا',
                'salary_min'      => 1350,
                'salary_max'      => 1600,
                'salary_text'     => '€1,350 - €1,600',
                'salary_period'   => 'صافي شهرياً + ساعات إضافية',
                'contract_type'   => 'عقد موسمي (فلوتسي 9 أشهر قابل للتجديد)',
                'badge'           => 'متاح الآن - 30 مقعداً',
                'image'           => 'jobs/job-agriculture.jpg',
                'housing'         => 'سكن مزارع مجهز وموثق قانونياً',
                'meals'           => 'بدل وجبات شهري',
                'insurance'       => 'تأمين طبي زراعي وقائي معتمد',
                'working_hours'   => '7 ساعات ونصف يومياً - يومين راحة',
                'experience'      => 'لا يشترط خبرة سابقة (اللياقة البدنية أساسية)',
                'available_seats' => 30,
                'description'     => 'حصاد وجني الزيتون، والعناية بالكروم والبيوت المحمية في مزارع توسكانا العريقة. فرصة ممتازة لتثبيت الإقامة والتحويل لعقد عمل دائم بعد انتهاء الموسم وفق القانون الإيطالي.',
                'requirements'    => [
                    'السن بين 20 إلى 45 عاماً',
                    'خلو من الأمراض المزمنة وصحة بدنية جيدة',
                    'جواز سفر ساري لمدة لا تقل عن سنتين',
                    'الالتزام بقوانين العمل الزراعي الإيطالي',
                ],
                'is_featured'     => true,
                'is_active'       => true,
                'sort_order'      => 2,
            ],
            [
                'job_category_id' => $catConstruction,
                'title'           => 'فنيو تشييد وبناء ومساعدو مقاولات',
                'city'            => 'روما وضواحيها (لاتسيو)',
                'salary_min'      => 1650,
                'salary_max'      => 2100,
                'salary_text'     => '€1,650 - €2,100',
                'salary_period'   => 'صافي شهرياً + بدل مخاطر',
                'contract_type'   => 'عقد دائم رسمي',
                'badge'           => 'مطلوب 18 مقعداً',
                'image'           => 'jobs/job-construction.jpg',
                'housing'         => 'شقق سكنية مشتركة مجهزة',
                'meals'           => 'بدل نقدي إضافي للوجبات',
                'insurance'       => 'تأمين شامل ضد حوادث وإصابات العمل',
                'working_hours'   => '8 ساعات يومياً - يومان راحة (السبت والأحد)',
                'experience'      => 'خبرة لا تقل عن سنة في أعمال البناء أو المساعدين',
                'available_seats' => 18,
                'description'     => 'العمل بمشاريع البنية التحتية والترميم الإنشائي لدى كبرى شركات المقاولات بروما. توفير معدات السلامة المهنية الأوروبية وتغطية نقابية عمالية كاملة.',
                'requirements'    => [
                    'شهادة قياس مهارة فنية (إن وجدت)',
                    'لياقة صحية وبدنية ممتازة',
                    'جواز سفر ساري وموقف واضح من التجنيد',
                    'شهادة عدم محكومية (فيش وتشبيه خالي)',
                ],
                'is_featured'     => true,
                'is_active'       => true,
                'sort_order'      => 3,
            ],
            [
                'job_category_id' => $catLogistics,
                'title'           => 'سائقو شاحنات ونقل لوجستي (فئة C / CE)',
                'city'            => 'بولونيا - إميليا رومانيا',
                'salary_min'      => 1900,
                'salary_max'      => 2400,
                'salary_text'     => '€1,900 - €2,400',
                'salary_period'   => 'صافي شهرياً + بدلات نقل',
                'contract_type'   => 'عقد عمل رسمي مفتوح',
                'badge'           => 'مطلوب عاجل - 12 مقعداً',
                'image'           => 'jobs/job-logistics.jpg',
                'housing'         => 'سكن لوجستي مريح أو بدل سكن نقدي',
                'meals'           => 'بدل وجبات على الطرق السريعة',
                'insurance'       => 'تأمين طبي واجتماعي كامل',
                'working_hours'   => 'ساعات قيادة مقننة طبقاً للاتحاد الأوروبي',
                'experience'      => 'رخصة مهنية درجة أولى/ثانية خبرة 3 سنوات',
                'available_seats' => 12,
                'description'     => 'قيادة شاحنات النقل بين المراكز اللوجستية في بولونيا وميلانو. مساعدة الشركة في استخراج رخصة القيادة الأوروبية (CQC) فور الوصول للمستوفين للشروط.',
                'requirements'    => [
                    'رخصة قيادة مهنية مصرية سارية (أولى أو ثانية)',
                    'خبرة موثقة لا تقل عن 3 سنوات في النقل الثقيل',
                    'سجل مروري خالي من المخالفات الجسيمة',
                    'معرفة مبدئية بقواعد الطرق الأوروبية',
                ],
                'is_featured'     => false,
                'is_active'       => true,
                'sort_order'      => 4,
            ],
            [
                'job_category_id' => $catTechnical,
                'title'           => 'فنيو كهرباء وسباكة وصيانة منشآت',
                'city'            => 'تورينو - بييمونتي',
                'salary_min'      => 1600,
                'salary_max'      => 1950,
                'salary_text'     => '€1,600 - €1,950',
                'salary_period'   => 'صافي شهرياً + مكافأة إنتاج',
                'contract_type'   => 'عقود سنوية قابلة للتجديد لإقامة دائمة',
                'badge'           => 'مطلوب 10 فنيين',
                'image'           => 'jobs/job-technician.jpg',
                'housing'         => 'سكن عمالي مجهز وموفر بالكامل',
                'meals'           => 'متوفر مطبخ وسوبرماركت مدعوم',
                'insurance'       => 'تأمين طبي شامل ضد حوادث العمل',
                'working_hours'   => '8 ساعات يومياً - 5 أيام أسبوعياً',
                'experience'      => 'دبلوم صنايع أو خبرة مثبتة 3 سنوات',
                'available_seats' => 10,
                'description'     => 'صيانة وتمديدات شبكات الكهرباء والسباكة في المجمعات السكنية والصناعية بتورينو. بيئة عمل تحترم ساعات العمل والقوانين النقابية الإيطالية.',
                'requirements'    => [
                    'مؤهل فني متوسط أو شهادة مهنية معتمدة',
                    'معرفة بقراءة المخططات الهندسية الأساسية',
                    'جواز سفر ساري وصحيفة حالة جنائية',
                    'الالتزام بالانضباط ومواعيد العمل',
                ],
                'is_featured'     => false,
                'is_active'       => true,
                'sort_order'      => 5,
            ],
            [
                'job_category_id' => $catHospitality,
                'title'           => 'طاقم ضيافة واستقبال وفنادق سياحية',
                'city'            => 'فينيسيا (البندقية) - فيرونا',
                'salary_min'      => 1450,
                'salary_max'      => 1800,
                'salary_text'     => '€1,450 - €1,800',
                'salary_period'   => 'صافي شهرياً + إكراميات مجزية',
                'contract_type'   => 'عقد موسمي / سنوي',
                'badge'           => 'موسم السياحة - 20 مقعداً',
                'image'           => 'jobs/job-hospitality.jpg',
                'housing'         => 'إقامة فندقية أو شقق سكنية للموظفين',
                'meals'           => '3 وجبات فندقية يومية مجانية',
                'insurance'       => 'تأمين صحي إيطالي كامل',
                'working_hours'   => 'ورديات منتظمة (8 ساعات)',
                'experience'      => 'حسن المظهر ولباقة التعامل، لغة إنجليزية مقبولة',
                'available_seats' => 20,
                'description'     => 'العمل بفنادق 4 نجوم ومنتجعات سياحية في فينيسيا وفيرونا. فرص ممتازة للاحتكاك الدولي، تعلم الإيطالية بطلاقة، وبناء مسيرة مهنية أوروبية واعدة.',
                'requirements'    => [
                    'مؤهل متوسط أو جامعي',
                    'مبادئ اللغة الإنجليزية أو الإيطالية',
                    'شهادة صحية معتمدة',
                    'جواز سفر ساري الصلاحية',
                ],
                'is_featured'     => false,
                'is_active'       => true,
                'sort_order'      => 6,
            ],
        ];

        foreach ($jobs as $job) {
            Job::updateOrCreate(
                ['title' => $job['title']],
                $job
            );
        }
    }
}
