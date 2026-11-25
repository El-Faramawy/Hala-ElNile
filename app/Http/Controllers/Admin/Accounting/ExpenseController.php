<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\Accounting;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Accounting\StoreExpenseRequest;
use App\Http\Requests\Admin\Accounting\UpdateExpenseRequest;
use App\Http\Requests\Admin\Common\MultiDeleteRequest;
use App\Models\Admin;
use App\Models\ContractTracker;
use App\Models\Expense;
use App\Services\Accounting\ExpenseService;
use App\Services\Accounting\ExpenseTypeService;
use App\Services\Accounting\SupplierService;
use App\Services\Accounting\TreasuryService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ExpenseController extends Controller
{
    public function __construct(
        protected ExpenseService $expenseService,
        protected ExpenseTypeService $expenseTypeService,
        protected TreasuryService $treasuryService,
        protected SupplierService $supplierService
    ) {}

    public function index(Request $request): View|JsonResponse
    {
        if ($request->ajax()) {
            return $this->expenseService->getDataTable($request->all());
        }

        $expenseTypes = $this->expenseTypeService->getActiveExpenseTypes();
        $treasuries = $this->treasuryService->getActiveTreasuries();
        $suppliers = $this->supplierService->getActiveSuppliers();
        $admins = Admin::query()->orderBy('name', 'asc')->get();
        $contracts = ContractTracker::query()->where('is_active', true)->orderBy('applicant_name', 'asc')->get();

        return view('Admin.Accounting.Expense.index', compact('expenseTypes', 'treasuries', 'suppliers', 'admins', 'contracts'));
    }

    public function create(): View
    {
        $expenseTypes = $this->expenseTypeService->getActiveExpenseTypes();
        $treasuries = $this->treasuryService->getActiveTreasuries();
        $suppliers = $this->supplierService->getActiveSuppliers();
        $admins = Admin::query()->orderBy('name', 'asc')->get();
        $contracts = ContractTracker::query()->where('is_active', true)->orderBy('applicant_name', 'asc')->get();

        return view('Admin.Accounting.Expense.parts.create', compact('expenseTypes', 'treasuries', 'suppliers', 'admins', 'contracts'));
    }

    public function store(StoreExpenseRequest $request): JsonResponse
    {
        $expense = $this->expenseService->store($request->validated());

        return response()->json([
            'message' => __('messages.general.created_success'),
            'id' => $expense->id,
            'voucher_url' => route('expenses.voucher', $expense->id),
        ]);
    }

    public function edit(Expense $expense): View
    {
        $expenseTypes = $this->expenseTypeService->getActiveExpenseTypes();
        $treasuries = $this->treasuryService->getActiveTreasuries();
        $suppliers = $this->supplierService->getActiveSuppliers();
        $admins = Admin::query()->orderBy('name', 'asc')->get();
        $contracts = ContractTracker::query()->where('is_active', true)->orderBy('applicant_name', 'asc')->get();

        return view('Admin.Accounting.Expense.parts.edit', compact('expense', 'expenseTypes', 'treasuries', 'suppliers', 'admins', 'contracts'));
    }

    public function update(UpdateExpenseRequest $request, Expense $expense): JsonResponse
    {
        $this->expenseService->update($expense, $request->validated());

        return response()->json([
            'message' => __('messages.general.updated_success'),
            'id' => $expense->id,
            'voucher_url' => route('expenses.voucher', $expense->id),
        ]);
    }

    public function destroy(Expense $expense): JsonResponse
    {
        $this->expenseService->delete($expense);

        return response()->json(['message' => __('messages.general.deleted_success')]);
    }

    public function multiDelete(MultiDeleteRequest $request): JsonResponse
    {
        $count = $this->expenseService->deleteMultiple($request->validated()['ids']);

        return response()->json(['message' => __('messages.general.deleted_success'), 'count' => $count]);
    }

    public function voucher(Expense $expense): View
    {
        return view('Admin.Accounting.Expense.voucher', compact('expense'));
    }
}
