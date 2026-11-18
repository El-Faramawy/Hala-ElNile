<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Job;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

class JobRepository
{
    public function __construct(protected Job $model) {}

    public function getDataTableQuery(): Builder
    {
        return $this->model->newQuery()->with('category')->latest('id');
    }

    public function findById(int $id): ?Job
    {
        return $this->model->find($id);
    }

    public function create(array $data): Job
    {
        return $this->model->create($data);
    }

    public function update(Job $job, array $data): bool
    {
        return $job->update($data);
    }

    public function delete(Job $job): bool
    {
        return (bool) $job->delete();
    }

    public function deleteMultiple(array $ids): int
    {
        return $this->model->whereIn('id', $ids)->delete();
    }

    /**
     * @return Collection<int, Job>
     */
    public function getAllActive(): Collection
    {
        return $this->model->where('is_active', true)->orderBy('sort_order', 'asc')->get();
    }
}
