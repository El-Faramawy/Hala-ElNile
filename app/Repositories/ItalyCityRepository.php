<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\ItalyCity;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

class ItalyCityRepository
{
    public function __construct(protected ItalyCity $model) {}

    public function getDataTableQuery(): Builder
    {
        return $this->model->newQuery()->withCount('landmarks')->orderBy('sort_order', 'asc')->latest('id');
    }

    public function findById(int $id): ?ItalyCity
    {
        return $this->model->find($id);
    }

    public function create(array $data): ItalyCity
    {
        return $this->model->create($data);
    }

    public function update(ItalyCity $italyCity, array $data): bool
    {
        return $italyCity->update($data);
    }

    public function delete(ItalyCity $italyCity): bool
    {
        return (bool) $italyCity->delete();
    }

    public function deleteMultiple(array $ids): int
    {
        return $this->model->whereIn('id', $ids)->delete();
    }

    /**
     * @return Collection<int, ItalyCity>
     */
    public function getAllActive(): Collection
    {
        return $this->model->where('is_active', true)->orderBy('sort_order', 'asc')->get();
    }
}
