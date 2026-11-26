<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\Accounting;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Accounting\StoreExpenseTypeRequest;
use App\Http\Requests\Admin\Accounting\UpdateExpenseTypeRequest;
use App\Http\Requests\Admin\Common\MultiDeleteRequest;
use App\Models\ExpenseType;
use App\Services\Accounting\ExpenseCategoryService;
use App\Services\Accounting\ExpenseTypeService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ExpenseTypeController extends Controller
{
    public function __construct(
        protected ExpenseTypeService $expenseTypeService,
        protected ExpenseCategoryService $expenseCategoryService
    ) {}

    public function index(Request $request): View|JsonResponse
    {
        if ($request->ajax()) {
            return $this->expenseTypeService->getDataTable();
        }

        $groupedTypes = $this->expenseTypeService->getGroupedExpenseTypes();

        return view('Admin.Accounting.ExpenseType.index', compact('groupedTypes'));
    }

    public function create(Request $request): View
    {
        $expenseCategories = $this->expenseCategoryService->getActiveExpenseCategories();
        $selectedCategoryId = $request->query('expense_category_id');

        return view('Admin.Accounting.ExpenseType.parts.create', compact('expenseCategories', 'selectedCategoryId'));
    }

    public function store(StoreExpenseTypeRequest $request): JsonResponse
    {
        $this->expenseTypeService->store($request->validated());

        return response()->json(['message' => __('messages.general.created_success')]);
    }

    public function edit(ExpenseType $expenseType): View
    {
        $expenseCategories = $this->expenseCategoryService->getActiveExpenseCategories();

        return view('Admin.Accounting.ExpenseType.parts.edit', compact('expenseType', 'expenseCategories'));
    }

    public function update(UpdateExpenseTypeRequest $request, ExpenseType $expenseType): JsonResponse
    {
        $this->expenseTypeService->update($expenseType, $request->validated());

        return response()->json(['message' => __('messages.general.updated_success')]);
    }

    public function destroy(ExpenseType $expenseType): JsonResponse
    {
        if ($expenseType->is_other) {
            return response()->json(['message' => 'لا يمكن حذف النوع الافتراضي (أخرى).'], 422);
        }

        $this->expenseTypeService->delete($expenseType);

        return response()->json(['message' => __('messages.general.deleted_success')]);
    }

    public function multiDelete(MultiDeleteRequest $request): JsonResponse
    {
        $count = $this->expenseTypeService->deleteMultiple($request->validated()['ids']);

        return response()->json(['message' => __('messages.general.deleted_success'), 'count' => $count]);
    }
}
