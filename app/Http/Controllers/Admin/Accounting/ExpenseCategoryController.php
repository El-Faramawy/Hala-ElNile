<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\Accounting;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Accounting\StoreExpenseCategoryRequest;
use App\Http\Requests\Admin\Accounting\UpdateExpenseCategoryRequest;
use App\Http\Requests\Admin\Common\MultiDeleteRequest;
use App\Models\ExpenseCategory;
use App\Services\Accounting\ExpenseCategoryService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ExpenseCategoryController extends Controller
{
    public function __construct(protected ExpenseCategoryService $expenseCategoryService) {}

    public function index(Request $request): View|JsonResponse
    {
        if ($request->ajax()) {
            return $this->expenseCategoryService->getDataTable();
        }

        return view('Admin.Accounting.ExpenseCategory.index');
    }

    public function create(): View
    {
        return view('Admin.Accounting.ExpenseCategory.parts.create');
    }

    public function store(StoreExpenseCategoryRequest $request): JsonResponse
    {
        $this->expenseCategoryService->store($request->validated());

        return response()->json(['message' => __('messages.general.created_success')]);
    }

    public function edit(ExpenseCategory $expenseCategory): View
    {
        return view('Admin.Accounting.ExpenseCategory.parts.edit', compact('expenseCategory'));
    }

    public function update(UpdateExpenseCategoryRequest $request, ExpenseCategory $expenseCategory): JsonResponse
    {
        $this->expenseCategoryService->update($expenseCategory, $request->validated());

        return response()->json(['message' => __('messages.general.updated_success')]);
    }

    public function destroy(ExpenseCategory $expenseCategory): JsonResponse
    {
        $this->expenseCategoryService->delete($expenseCategory);

        return response()->json(['message' => __('messages.general.deleted_success')]);
    }

    public function multiDelete(MultiDeleteRequest $request): JsonResponse
    {
        $count = $this->expenseCategoryService->deleteMultiple($request->validated()['ids']);

        return response()->json(['message' => __('messages.general.deleted_success'), 'count' => $count]);
    }
}
