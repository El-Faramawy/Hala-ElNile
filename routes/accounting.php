<?php

declare(strict_types=1);

use App\Http\Controllers\Admin\Accounting\ContractFinancialController;
use App\Http\Controllers\Admin\Accounting\ConversionController;
use App\Http\Controllers\Admin\Accounting\DashboardController;
use App\Http\Controllers\Admin\Accounting\ExpenseController;
use App\Http\Controllers\Admin\Accounting\ExpenseTypeController;
use App\Http\Controllers\Admin\Accounting\OverdueInstallmentController;
use App\Http\Controllers\Admin\Accounting\ReportController;
use App\Http\Controllers\Admin\Accounting\RevenueController;
use App\Http\Controllers\Admin\Accounting\SupplierController;
use App\Http\Controllers\Admin\Accounting\TreasuryController;
use App\Http\Controllers\Admin\AuthController;
use Illuminate\Support\Facades\Route;

Route::prefix('accounting')->middleware('web')->group(function (): void {
    // Independent Login for Accountants
    Route::get('login', function () {
        if (auth()->guard('admin')->check()) {
            return redirect()->route('accounting.dashboard');
        }

        return view('Admin.Accounting.auth.login');
    })->name('accounting.login');

    Route::post('post_login', [AuthController::class, 'login'])->name('accounting.post_login');

    // Authenticated Accounting Routes
    Route::middleware('admin')->group(function (): void {
        Route::get('/', [DashboardController::class, 'index'])->name('accounting.dashboard');
        Route::get('summary', [DashboardController::class, 'summary'])->name('accounting.summary');

        // Revenues (سندات القبض والتحصيل)
        Route::get('revenues/{revenue}/receipt', [RevenueController::class, 'receipt'])->name('revenues.receipt');
        Route::post('multi_delete_revenues', [RevenueController::class, 'multiDelete'])->name('revenues.multiDelete');
        Route::resource('revenues', RevenueController::class);

        // Conversions (تحويل وتصريف العملة المجمعة)
        Route::get('conversions', [ConversionController::class, 'index'])->name('conversions.index');
        Route::post('conversions', [ConversionController::class, 'store'])->name('conversions.store');
        Route::get('conversions/{conversion}', [ConversionController::class, 'show'])->name('conversions.show');

        // Expenses (سندات الصرف والمصروفات)
        Route::get('expenses/{expense}/voucher', [ExpenseController::class, 'voucher'])->name('expenses.voucher');
        Route::post('multi_delete_expenses', [ExpenseController::class, 'multiDelete'])->name('expenses.multiDelete');
        Route::resource('expenses', ExpenseController::class);

        // Contracts Financials (حسابات العقود والجدولة)
        Route::get('contracts', [ContractFinancialController::class, 'index'])->name('accounting.contracts.index');
        Route::get('contracts/{contractTracker}/ledger', [ContractFinancialController::class, 'ledger'])->name('accounting.contracts.ledger');
        Route::post('contracts/{contractTracker}/price', [ContractFinancialController::class, 'updatePrice'])->name('accounting.contracts.updatePrice');
        Route::post('contracts/installments', [ContractFinancialController::class, 'storeInstallment'])->name('accounting.installments.store');
        Route::post('contracts/installments/{installment}', [ContractFinancialController::class, 'updateInstallment'])->name('accounting.installments.update');
        Route::delete('contracts/installments/{installment}', [ContractFinancialController::class, 'deleteInstallment'])->name('accounting.installments.destroy');

        // Overdue Installments (الدفعات المتأخرة والتحصيل السريع)
        Route::get('overdue', [OverdueInstallmentController::class, 'index'])->name('accounting.overdue.index');
        Route::post('overdue/{installment}/mark-paid', [OverdueInstallmentController::class, 'markPaid'])->name('accounting.overdue.markPaid');

        // Suppliers (الموردين والوكلاء)
        Route::post('multi_delete_suppliers', [SupplierController::class, 'multiDelete'])->name('suppliers.multiDelete');
        Route::resource('suppliers', SupplierController::class);

        // Treasuries (الخزائن وحركة النقدية)
        Route::post('multi_delete_treasuries', [TreasuryController::class, 'multiDelete'])->name('treasuries.multiDelete');
        Route::resource('treasuries', TreasuryController::class);

        // Expense Types (أنواع المصروفات)
        Route::post('multi_delete_expense_types', [ExpenseTypeController::class, 'multiDelete'])->name('expense-types.multiDelete');
        Route::resource('expense-types', ExpenseTypeController::class);

        // Reports (التقارير المالية والأرباح والخسائر)
        Route::get('reports', [ReportController::class, 'index'])->name('accounting.reports.index');
    });
});
