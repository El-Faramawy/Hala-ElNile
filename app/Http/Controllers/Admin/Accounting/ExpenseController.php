<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\Accounting;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Accounting\StoreExpenseRequest;
use App\Http\Requests\Admin\Accounting\UpdateExpenseRequest;
use App\Http\Requests\Admin\Common\MultiDeleteRequest;
use App\Models\Expense;
use App\Services\Accounting\ExpenseService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ExpenseController extends Controller
{
    public function __construct(protected ExpenseService $expenseService) {}

    public function index(Request $request): View|JsonResponse
    {
        if ($request->ajax()) {
            return $this->expenseService->getDataTable($request->all());
        }

        return view('Admin.Accounting.Expense.index', $this->expenseService->getFormDependencies());
    }

    public function create(Request $request): View
    {
        $scheduledItemId = $request->filled('scheduled_item_id') ? (int) $request->query('scheduled_item_id') : null;

        return view('Admin.Accounting.Expense.parts.create', $this->expenseService->getFormDependencies($scheduledItemId));
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
        return view('Admin.Accounting.Expense.parts.edit', array_merge(
            ['expense' => $expense],
            $this->expenseService->getFormDependencies()
        ));
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
