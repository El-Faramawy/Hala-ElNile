<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    public function run(): void
    {
        Setting::updateOrCreate(
            ['id' => 1],
            [
                'name' => 'شركة هلا النيل لإلحاق العمالة المصرية بالخارج',
                'logo' => 'setting/logo.svg',
                'fav_icon' => 'setting/fav_icon.svg',
                'phone' => '0101 889 9770',
                'whatsapp' => '201018899770',
                'email' => 'info@halanile.com',
                'address' => 'مصر - القاهرة - مدينة نصر',
                'license_number' => '912 شركات (فئة أ)',
                'commercial_record' => '104522 مكتب استثمار القاهرة',
                'tax_card' => '429-881-209',
                'working_hours' => 'السبت - الخميس: 9:00 ص - 6:00 م',
                'facebook' => 'https://facebook.com',
                'twitter' => 'https://twitter.com',
                'telegram' => 'https://t.me',
                'youtube' => 'https://youtube.com',
                'stats_travelers' => '+1,450',
                'stats_contracts' => '100%',
                'stats_companies' => '+68',
                'stats_experience' => '14 عاماً',
                'about_us' => '<p>تأسست <strong>شركة هلا النيل لإلحاق العمالة المصرية بالخارج</strong> بموجب ترخيص رسمي صادر من وزارة القوى العاملة والهجرة المصرية برقم <strong>(912 شركات)</strong> فئة (أ)، وسجل تجاري رقم <strong>104522</strong> وبطاقة ضريبية رقم <strong>429-881-209</strong>.</p><p>نحن متخصصون حصرياً في تأمين وتنظيم فرص العمل الرسمية والشرعية داخل دولة إيطاليا، بالشراكة مع كبرى الاتحادات العمالية والغرف التجارية الإيطالية، من خلال استصدار تصاريح العمل الرسمية (Nulla Osta) من وزارة الداخلية الإيطالية بنظام الحصص السنوية (Decreto Flussi) والتوظيف المباشر.</p>',
                'terms_conditions' => '<p>كافة تعاملات شركة هلا النيل تخضع لرقابة وزارة العمل المصرية والقوانين المنظمة لإلحاق العمالة بالخارج (قانون العمل رقم 12 لسنة 2003). جميع العقود موثقة ومعتمدة من القنصلية الإيطالية بالقاهرة، والرسوم مقننة بإيصالات رسمية وحسابات بنكية معتمدة للشركة.</p>',
                'privacy_policy' => '<p>تلتزم شركة هلا النيل بالحفاظ التام على خصوصية وسرية بيانات عملائها ومقدمي طلبات التوظيف، ولا يتم استخدام بيانات جوازات السفر أو المؤهلات أو المستندات إلا في الأغراض الرسمية المعتمدة لإنهاء إجراءات التأشيرة وتصريح العمل لدى الجهات الحكومية المصرية والإيطالية المعنية.</p>',
            ]
        );
    }
}
