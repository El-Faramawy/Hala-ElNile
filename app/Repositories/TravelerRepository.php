<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Traveler;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

class TravelerRepository
{
    public function __construct(protected Traveler $model) {}

    public function getDataTableQuery(): Builder
    {
        return $this->model->newQuery()->latest('id');
    }

    public function findById(int $id): ?Traveler
    {
        return $this->model->find($id);
    }

    public function create(array $data): Traveler
    {
        return $this->model->create($data);
    }

    public function update(Traveler $traveler, array $data): bool
    {
        return $traveler->update($data);
    }

    public function delete(Traveler $traveler): bool
    {
        return (bool) $traveler->delete();
    }

    public function deleteMultiple(array $ids): int
    {
        return $this->model->whereIn('id', $ids)->delete();
    }

    /**
     * @return Collection<int, Traveler>
     */
    public function getAllActive(): Collection
    {
        return $this->model->where('is_active', true)->orderBy('sort_order', 'asc')->get();
    }
}
