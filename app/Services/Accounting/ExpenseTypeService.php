<?php

declare(strict_types=1);

namespace App\Services\Accounting;

use App\Models\ExpenseType;
use App\Repositories\ExpenseTypeRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Yajra\DataTables\DataTables;

class ExpenseTypeService
{
    public function __construct(protected ExpenseTypeRepository $expenseTypeRepository) {}

    public function getDataTable(): JsonResponse
    {
        $query = $this->expenseTypeRepository->getDataTableQuery();

        return DataTables::of($query)
            ->addColumn('checkbox', fn (ExpenseType $type): string => tableCheckbox($type->id))
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
}
