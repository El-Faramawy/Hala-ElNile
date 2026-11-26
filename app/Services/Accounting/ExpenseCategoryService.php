<?php

declare(strict_types=1);

namespace App\Services\Accounting;

use App\Models\ExpenseCategory;
use App\Repositories\ExpenseCategoryRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Yajra\DataTables\DataTables;

class ExpenseCategoryService
{
    public function __construct(protected ExpenseCategoryRepository $expenseCategoryRepository) {}

    public function getDataTable(): JsonResponse
    {
        $query = $this->expenseCategoryRepository->getDataTableQuery();

        return DataTables::of($query)
            ->addColumn('checkbox', fn (ExpenseCategory $category): string => tableCheckbox($category->id))
            ->addColumn('types_count', function (ExpenseCategory $category): string {
                return '<span class="badge badge-info px-2 py-1">'.$category->expense_types_count.' بنود</span>';
            })
            ->addColumn('status', fn (ExpenseCategory $category): string => tableStatus((bool) $category->is_active))
            ->addColumn('action', fn (ExpenseCategory $category): string => tableAction($category->id, true, true))
            ->escapeColumns([])
            ->make(true);
    }

    public function store(array $data): ExpenseCategory
    {
        return $this->expenseCategoryRepository->create($data);
    }

    public function update(ExpenseCategory $expenseCategory, array $data): bool
    {
        return $this->expenseCategoryRepository->update($expenseCategory, $data);
    }

    public function delete(ExpenseCategory $expenseCategory): bool
    {
        return $this->expenseCategoryRepository->delete($expenseCategory);
    }

    public function deleteMultiple(array $ids): int
    {
        return $this->expenseCategoryRepository->deleteMultiple($ids);
    }

    /**
     * @return Collection<int, ExpenseCategory>
     */
    public function getActiveExpenseCategories(): Collection
    {
        return $this->expenseCategoryRepository->getAllActive();
    }
}
