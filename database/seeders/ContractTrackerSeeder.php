<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\ContractTracker;
use Illuminate\Database\Seeder;

class ContractTrackerSeeder extends Seeder
{
    public function run(): void
    {
        $contracts = [
            [
                'contract_code'       => 'HS-78921',
                'passport_number'     => 'A12345678',
                'applicant_name'      => 'أحمد محمود السيد خليل',
                'job_title'           => 'مساعد شيف - ميلانو',
                'city_name'           => 'ميلانو',
                'step'                => 3,
                'status_text'         => 'تم إصدار الموافقة الأمنية (Nulla Osta) بنجاح',
                'issue_date'          => '2026-10-18',
                'embassy_appointment' => '15 نوفمبر 2026 - السفارة الإيطالية بجاردن سيتي',
                'flight_details'      => null,
                'notes'               => 'يرجى إحضار أصل المؤهل والفيش الجنائي المصدق من الخارجية في موعد السفارة المحدد.',
                'is_active'           => true,
            ],
            [
                'contract_code'       => 'HS-99881',
                'passport_number'     => 'B87654321',
                'applicant_name'      => 'محمود عبد الرحمن إبراهيم',
                'job_title'           => 'فني تشييد وبناء - روما',
                'city_name'           => 'روما',
                'step'                => 4,
                'status_text'         => 'تم استلام التأشيرة الإيطالية وتحديد موعد السفر',
                'issue_date'          => '2026-10-25',
                'embassy_appointment' => 'تم إنهاء المقابلة واستلام التأشيرة',
                'flight_details'      => 'رحلة مصر للطيران MS703 إلى مطار روما فيوميتشينو - 4 نوفمبر 2026',
                'notes'               => 'مندوب شركة هلا النيل سيكون باستقبالك في صالة الوصول بمطار روما مع عقد العمل وبطاقة السكن.',
                'is_active'           => true,
            ],
            [
                'contract_code'       => 'HS-5120',
                'passport_number'     => 'C33445566',
                'applicant_name'      => 'أحمد صابر عبد السلام',
                'job_title'           => 'فني تشييد وبناء بروما',
                'city_name'           => 'روما',
                'step'                => 5,
                'status_text'         => 'تم السفر والاستقرار واستلام تصريح الإقامة وعقد العمل',
                'issue_date'          => '2024-03-10',
                'embassy_appointment' => 'تم استلام التأشيرة رسمياً',
                'flight_details'      => 'وصل إلى روما وتم استلام العمل',
                'notes'               => 'تم الانتهاء من كافة الإجراءات بنجاح والمسافر مستقر في مقر عمله.',
                'is_active'           => true,
            ],
            [
                'contract_code'       => 'HS-6430',
                'passport_number'     => 'D99887766',
                'applicant_name'      => 'محمود السيد رجب',
                'job_title'           => 'مساعد شيف بميلانو',
                'city_name'           => 'ميلانو',
                'step'                => 5,
                'status_text'         => 'تم السفر واستلام بطاقة الإقامة القانونية',
                'issue_date'          => '2024-05-20',
                'embassy_appointment' => 'تم الاستلام بنجاح',
                'flight_details'      => 'وصل إلى ميلانو - السكن موفر',
                'notes'               => 'المعاملة مكتملة بنجاح.',
                'is_active'           => true,
            ],
        ];

        foreach ($contracts as $contract) {
            ContractTracker::updateOrCreate(
                ['contract_code' => $contract['contract_code']],
                $contract
            );
        }
    }
}
