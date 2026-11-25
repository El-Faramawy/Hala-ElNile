<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\ItalyLandmark;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

class ItalyLandmarkRepository
{
    public function __construct(protected ItalyLandmark $model) {}

    public function getDataTableQuery(): Builder
    {
        return $this->model->newQuery()->with('city')->orderBy('sort_order', 'asc')->latest('id');
    }

    public function findById(int $id): ?ItalyLandmark
    {
        return $this->model->find($id);
    }

    public function create(array $data): ItalyLandmark
    {
        return $this->model->create($data);
    }

    public function update(ItalyLandmark $italyLandmark, array $data): bool
    {
        return $italyLandmark->update($data);
    }

    public function delete(ItalyLandmark $italyLandmark): bool
    {
        return (bool) $italyLandmark->delete();
    }

    public function deleteMultiple(array $ids): int
    {
        return $this->model->whereIn('id', $ids)->delete();
    }

    /**
     * @return Collection<int, ItalyLandmark>
     */
    public function getAllActive(?int $cityId = null): Collection
    {
        $query = $this->model->where('is_active', true)->orderBy('sort_order', 'asc');

        if ($cityId !== null) {
            $query->where('italy_city_id', $cityId);
        }

        return $query->get();
    }
}
