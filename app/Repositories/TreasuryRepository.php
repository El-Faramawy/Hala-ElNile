<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Treasury;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

class TreasuryRepository
{
    public function __construct(protected Treasury $model) {}

    public function getDataTableQuery(): Builder
    {
        return $this->model->newQuery()->latest('id');
    }

    public function findById(int $id): ?Treasury
    {
        return $this->model->find($id);
    }

    public function create(array $data): Treasury
    {
        return $this->model->create($data);
    }

    public function update(Treasury $treasury, array $data): bool
    {
        return $treasury->update($data);
    }

    public function delete(Treasury $treasury): bool
    {
        return (bool) $treasury->delete();
    }

    public function deleteMultiple(array $ids): int
    {
        return $this->model->whereIn('id', $ids)->delete();
    }

    /**
     * @return Collection<int, Treasury>
     */
    public function getAllActive(): Collection
    {
        return $this->model->where('is_active', true)->orderBy('id', 'asc')->get();
    }

    public function incrementBalance(Treasury $treasury, float $amount): bool
    {
        return (bool) $treasury->increment('balance', $amount);
    }

    public function decrementBalance(Treasury $treasury, float $amount): bool
    {
        return (bool) $treasury->decrement('balance', $amount);
    }
}
