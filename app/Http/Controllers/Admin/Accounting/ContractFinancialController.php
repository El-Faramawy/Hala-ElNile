<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\Accounting;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Accounting\StoreInstallmentRequest;
use App\Http\Requests\Admin\Accounting\UpdateContractPriceRequest;
use App\Http\Requests\Admin\Accounting\UpdateInstallmentRequest;
use App\Models\ContractTracker;
use App\Models\Installment;
use App\Services\Accounting\AccountingDashboardService;
use App\Services\Accounting\InstallmentService;
use App\Services\Accounting\SupplierService;
use App\Services\Accounting\TreasuryService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ContractFinancialController extends Controller
{
    public function __construct(
        protected AccountingDashboardService $dashboardService,
        protected InstallmentService $installmentService,
        protected TreasuryService $treasuryService,
        protected SupplierService $supplierService
    ) {}

    public function index(Request $request): View|JsonResponse
    {
        if ($request->ajax()) {
            return $this->dashboardService->getContractsFinancialDataTable();
        }

        $treasuries = $this->treasuryService->getActiveTreasuries();
        $suppliers = $this->supplierService->getActiveSuppliers();

        return view('Admin.Accounting.Contract.index', compact('treasuries', 'suppliers'));
    }

    public function ledger(ContractTracker $contractTracker): View
    {
        $contractTracker->load([
            'supplier',
            'revenues.treasury',
            'expenses.treasury',
            'expenses.supplier',
            'expenses.admin',
            'expenses.expenseType.expenseCategory',
            'installments',
        ]);
        $treasuries = $this->treasuryService->getActiveTreasuries();
        $suppliers = $this->supplierService->getActiveSuppliers();

        return view('Admin.Accounting.Contract.ledger', compact('contractTracker', 'treasuries', 'suppliers'));
    }

    public function updatePrice(UpdateContractPriceRequest $request, ContractTracker $contractTracker): JsonResponse
    {
        $contractTracker->update($request->validated());

        return response()->json(['message' => 'تم تحديث قيمة العقد باليورو بنجاح.']);
    }

    public function storeInstallment(StoreInstallmentRequest $request): JsonResponse
    {
        $this->installmentService->store($request->validated());

        return response()->json(['message' => 'تمت إضافة الدفعة المجدولة بنجاح.']);
    }

    public function updateInstallment(UpdateInstallmentRequest $request, Installment $installment): JsonResponse
    {
        $this->installmentService->update($installment, $request->validated());

        return response()->json(['message' => 'تم تحديث بيانات الدفعة بنجاح.']);
    }

    public function deleteInstallment(Installment $installment): JsonResponse
    {
        $this->installmentService->delete($installment);

        return response()->json(['message' => 'تم حذف الدفعة بنجاح.']);
    }
}
