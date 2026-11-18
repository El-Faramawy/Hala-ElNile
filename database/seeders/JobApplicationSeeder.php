<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\City;
use App\Models\Job;
use App\Models\JobApplication;
use Illuminate\Database\Seeder;

class JobApplicationSeeder extends Seeder
{
    public function run(): void
    {
        $job1 = Job::first();
        $job2 = Job::skip(1)->first();
        $cairo = City::where('name', 'القاهرة')->first()?->id;
        $giza = City::where('name', 'الجيزة')->first()?->id;
        $gharbia = City::where('name', 'الغربية')->first()?->id;

        $applications = [
            [
                'job_id'             => $job1?->id,
                'city_id'            => $cairo,
                'job_title'          => $job1?->title ?? 'مساعد شيف وطهاة مطاعم إيطالية',
                'name'               => 'إبراهيم حسن علي الشربيني',
                'phone'              => '01023456789',
                'age'                => 29,
                'passport_situation' => 'يوجد جواز ساري لمدة أكثر من سنتين',
                'notes'              => 'لدي خبرة سابقة 3 سنوات بمطاعم المشويات والبيتزا، ومستعد للسفر فوراً.',
                'status'             => 'pending',
                'admin_notes'        => null,
            ],
            [
                'job_id'             => $job2?->id,
                'city_id'            => $gharbia,
                'job_title'          => $job2?->title ?? 'عمالة زراعية ومزارع زيتون وعنب',
                'name'               => 'أحمد ربيع حسنين محمد',
                'phone'              => '01198765432',
                'age'                => 26,
                'passport_situation' => 'يوجد جواز ساري أقل من سنتين',
                'notes'              => 'صحة بدنية جيدة ولياقة عالية، أرغب في العمل بمزارع العنب في توسكانا.',
                'status'             => 'contacted',
                'admin_notes'        => 'تم التواصل معه هاتفياً وموعد المقابلة بالمقر يوم السبت القادم.',
            ],
            [
                'job_id'             => null,
                'city_id'            => $giza,
                'job_title'          => 'طلب توظيف عام في إيطاليا',
                'name'               => 'سيد عبد العظيم مصطفى',
                'phone'              => '01287654321',
                'age'                => 34,
                'passport_situation' => 'قيد الاستخراج من مصلحة الجوازات',
                'notes'              => 'فني كهرباء سيارات ومعدات ثقيلة.',
                'status'             => 'pending',
                'admin_notes'        => null,
            ],
        ];

        foreach ($applications as $app) {
            JobApplication::updateOrCreate(
                ['phone' => $app['phone']],
                $app
            );
        }
    }
}
