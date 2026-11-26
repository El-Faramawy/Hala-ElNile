<?php

declare(strict_types=1);

namespace App\Services\Accounting;

use App\Models\ExpenseCategory;
use App\Models\ExpenseType;
use App\Repositories\ExpenseCategoryRepository;
use App\Repositories\ExpenseTypeRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Yajra\DataTables\DataTables;

class ExpenseTypeService
{
    public function __construct(
        protected ExpenseTypeRepository $expenseTypeRepository,
        protected ExpenseCategoryRepository $expenseCategoryRepository
    ) {}

    public function getDataTable(): JsonResponse
    {
        $query = $this->expenseTypeRepository->getDataTableQuery();

        return DataTables::of($query)
            ->addColumn('checkbox', fn (ExpenseType $type): string => tableCheckbox($type->id))
            ->addColumn('main_category_badge', function (ExpenseType $type): string {
                if ($type->expenseCategory) {
                    return '<span class="badge badge-primary px-2 py-1">'.e($type->expenseCategory->name).'</span>';
                }

                return '<span class="badge badge-light text-muted px-2 py-1">تصنيف عام</span>';
            })
            ->addColumn('is_other_badge', function (ExpenseType $type): string {
                if ($type->is_other) {
                    return '<span class="badge badge-warning px-2 py-1">يتطلب تفاصيل إضافية</span>';
                }

                return '<span class="badge badge-light text-muted px-2 py-1">نوع قياسي</span>';
            })
            ->addColumn('status', fn (ExpenseType $type): string => tableStatus((bool) $type->is_active))
            ->addColumn('action', fn (ExpenseType $type): string => tableAction($type->id, true, ! $type->is_other))
            ->escapeColumns([])
            ->make(true);
    }

    public function store(array $data): ExpenseType
    {
        return $this->expenseTypeRepository->create($data);
    }

    public function update(ExpenseType $expenseType, array $data): bool
    {
        return $this->expenseTypeRepository->update($expenseType, $data);
    }

    public function delete(ExpenseType $expenseType): bool
    {
        return $this->expenseTypeRepository->delete($expenseType);
    }

    public function deleteMultiple(array $ids): int
    {
        return $this->expenseTypeRepository->deleteMultiple($ids);
    }

    /**
     * @return Collection<int, ExpenseType>
     */
    public function getActiveExpenseTypes(): Collection
    {
        return $this->expenseTypeRepository->getAllActive();
    }

    /**
     * Group expense types under ExpenseCategory with hierarchical numbering (1, 1.1, 1.2, 2, 2.1...).
     *
     * @return array{
     *     groups: list<array{number: int, category_id: int, category: string, is_active: bool, items: list<array{code: string, model: ExpenseType}>}>,
     *     standalone: list<array{code: string, model: ExpenseType}>
     * }
     */
    public function getGroupedExpenseTypes(bool $activeOnly = false): array
    {
        $categories = $activeOnly
            ? $this->expenseCategoryRepository->getAllActive()
            : $this->expenseCategoryRepository->getDataTableQuery()->get();

        $items = $activeOnly
            ? $this->expenseTypeRepository->getAllActive()
            : $this->expenseTypeRepository->getAllOrdered();

        [$itemsByCategory, $standaloneModels] = $this->partitionByCategory($items);

        $groups = [];
        $categoryNumber = 1;

        /** @var ExpenseCategory $category */
        foreach ($categories as $category) {
            $categoryItems = $itemsByCategory[(int) $category->id] ?? [];
            $groups[] = [
                'number' => $categoryNumber,
                'category_id' => (int) $category->id,
                'category' => $category->name,
                'is_active' => (bool) $category->is_active,
                'items' => $this->numberItems($categoryItems, $categoryNumber),
            ];
            $categoryNumber++;
        }

        return [
            'groups' => $groups,
            'standalone' => $this->numberItems($standaloneModels, $categoryNumber),
        ];
    }

    /**
     * Partition expense types into category-indexed lists and uncategorized items.
     *
     * @param  Collection<int, ExpenseType>  $items
     * @return array{0: array<int, list<ExpenseType>>, 1: list<ExpenseType>}
     */
    private function partitionByCategory(Collection $items): array
    {
        $itemsByCategory = [];
        $standaloneModels = [];

        foreach ($items as $item) {
            if ($item->expense_category_id !== null) {
                $itemsByCategory[(int) $item->expense_category_id][] = $item;

                continue;
            }

            $standaloneModels[] = $item;
        }

        return [$itemsByCategory, $standaloneModels];
    }

    /**
     * Assign hierarchical codes (e.g. 1.1, 1.2) to a list of expense types.
     *
     * @param  list<ExpenseType>  $items
     * @return list<array{code: string, model: ExpenseType}>
     */
    private function numberItems(array $items, int $prefixNumber): array
    {
        $numberedItems = [];
        foreach (array_values($items) as $index => $item) {
            $numberedItems[] = [
                'code' => $prefixNumber.'.'.($index + 1),
                'model' => $item,
            ];
        }

        return $numberedItems;
    }
}
