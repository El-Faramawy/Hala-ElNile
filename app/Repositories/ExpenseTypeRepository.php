<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\ExpenseType;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

class ExpenseTypeRepository
{
    public function __construct(protected ExpenseType $model) {}

    public function getDataTableQuery(): Builder
    {
        return $this->model->newQuery()->orderBy('id', 'asc');
    }

    public function findById(int $id): ?ExpenseType
    {
        return $this->model->find($id);
    }

    public function create(array $data): ExpenseType
    {
        return $this->model->create($data);
    }

    public function update(ExpenseType $expenseType, array $data): bool
    {
        return $expenseType->update($data);
    }

    public function delete(ExpenseType $expenseType): bool
    {
        return (bool) $expenseType->delete();
    }

    public function deleteMultiple(array $ids): int
    {
        return $this->model->whereIn('id', $ids)->delete();
    }

    /**
     * @return Collection<int, ExpenseType>
     */
    public function getAllActive(): Collection
    {
        return $this->model->where('is_active', true)->orderBy('id', 'asc')->get();
    }

    public function getOtherType(): ?ExpenseType
    {
        return $this->model->where('is_other', true)->first();
    }

    public function getConversionLossType(): ExpenseType
    {
        return $this->model->firstOrCreate(
            ['name' => 'مصاريف إدارية / فرق تحويل'],
            ['is_other' => false, 'is_active' => true]
        );
    }
}
