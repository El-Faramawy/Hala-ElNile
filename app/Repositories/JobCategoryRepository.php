<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\JobCategory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

class JobCategoryRepository
{
    public function __construct(protected JobCategory $model) {}

    public function getDataTableQuery(): Builder
    {
        return $this->model->newQuery()->latest('id');
    }

    public function findById(int $id): ?JobCategory
    {
        return $this->model->find($id);
    }

    public function create(array $data): JobCategory
    {
        return $this->model->create($data);
    }

    public function update(JobCategory $jobCategory, array $data): bool
    {
        return $jobCategory->update($data);
    }

    public function delete(JobCategory $jobCategory): bool
    {
        return (bool) $jobCategory->delete();
    }

    public function deleteMultiple(array $ids): int
    {
        return $this->model->whereIn('id', $ids)->delete();
    }

    /**
     * @return Collection<int, JobCategory>
     */
    public function getAllActive(): Collection
    {
        return $this->model
            ->where('is_active', true)
            ->orderBy('sort_order', 'asc')
            ->get();
    }
}
