<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Admin;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

class AdminRepository
{
    public function __construct(protected Admin $model) {}

    /**
     * @return Collection<int, Admin>
     */
    public function getAllOrdered(): Collection
    {
        return $this->model->newQuery()->orderBy('name', 'asc')->get();
    }

    public function getDataTableQuery(int $currentAdminId): Builder
    {
        return $this->model->newQuery()
            ->where('id', '!=', $currentAdminId)
            ->latest('id');
    }

    public function findById(int $id): ?Admin
    {
        return $this->model->find($id);
    }

    public function create(array $data): Admin
    {
        return $this->model->create($data);
    }

    public function update(Admin $admin, array $data): bool
    {
        return $admin->update($data);
    }

    public function delete(Admin $admin): bool
    {
        return (bool) $admin->delete();
    }

    public function deleteMultiple(array $ids): int
    {
        return $this->model->whereIn('id', $ids)->delete();
    }
}
