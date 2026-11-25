<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AccountingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $treasuries = [
            ['name' => 'خزينة كاش (جنيه)', 'currency' => 'EGP', 'balance' => 0.00, 'is_active' => true],
            ['name' => 'إنستاباي (InstaPay)', 'currency' => 'EGP', 'balance' => 0.00, 'is_active' => true],
            ['name' => 'فودافون كاش', 'currency' => 'EGP', 'balance' => 0.00, 'is_active' => true],
            ['name' => 'الحساب البنكي (جنيه)', 'currency' => 'EGP', 'balance' => 0.00, 'is_active' => true],
            ['name' => 'خزينة يورو (€)', 'currency' => 'EUR', 'balance' => 0.00, 'is_active' => true],
        ];

        foreach ($treasuries as $treasury) {
            DB::table('treasuries')->updateOrInsert(
                ['name' => $treasury['name']],
                array_merge($treasury, ['created_at' => now(), 'updated_at' => now()])
            );
        }

        $expenseTypes = [
            ['name' => 'رواتب وسلف موظفين', 'is_other' => false],
            ['name' => 'إيجار ومرافق', 'is_other' => false],
            ['name' => 'تذاكر طيران', 'is_other' => false],
            ['name' => 'رسوم سفارة وتأشيرات', 'is_other' => false],
            ['name' => 'ترجمة وتوثيق أوراق', 'is_other' => false],
            ['name' => 'مصاريف إدارية / فرق تحويل', 'is_other' => false],
            ['name' => 'دعاية وإعلانات', 'is_other' => false],
            ['name' => 'أخرى', 'is_other' => true],
        ];

        foreach ($expenseTypes as $type) {
            DB::table('expense_types')->updateOrInsert(
                ['name' => $type['name']],
                array_merge($type, ['is_active' => true, 'created_at' => now(), 'updated_at' => now()])
            );
        }
    }
}
