<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\ExpenseCategory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

class ExpenseCategoryRepository
{
    public function __construct(protected ExpenseCategory $model) {}

    public function getDataTableQuery(): Builder
    {
        return $this->model->newQuery()->withCount('expenseTypes')->orderBy('id', 'asc');
    }

    public function findById(int $id): ?ExpenseCategory
    {
        return $this->model->find($id);
    }

    public function create(array $data): ExpenseCategory
    {
        return $this->model->create($data);
    }

    public function update(ExpenseCategory $expenseCategory, array $data): bool
    {
        return $expenseCategory->update($data);
    }

    public function delete(ExpenseCategory $expenseCategory): bool
    {
        return (bool) $expenseCategory->delete();
    }

    public function deleteMultiple(array $ids): int
    {
        return $this->model->whereIn('id', $ids)->delete();
    }

    /**
     * @return Collection<int, ExpenseCategory>
     */
    public function getAllActive(): Collection
    {
        return $this->model->where('is_active', true)->orderBy('id', 'asc')->get();
    }
}
