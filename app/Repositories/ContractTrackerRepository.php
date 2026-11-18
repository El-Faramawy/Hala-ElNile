<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\ContractTracker;
use Illuminate\Database\Eloquent\Builder;

class ContractTrackerRepository
{
    public function __construct(protected ContractTracker $model) {}

    public function getDataTableQuery(): Builder
    {
        return $this->model->newQuery()->latest('id');
    }

    public function findById(int $id): ?ContractTracker
    {
        return $this->model->find($id);
    }

    public function create(array $data): ContractTracker
    {
        return $this->model->create($data);
    }

    public function update(ContractTracker $tracker, array $data): bool
    {
        return $tracker->update($data);
    }

    public function delete(ContractTracker $tracker): bool
    {
        return (bool) $tracker->delete();
    }

    public function deleteMultiple(array $ids): int
    {
        return $this->model->whereIn('id', $ids)->delete();
    }
}
