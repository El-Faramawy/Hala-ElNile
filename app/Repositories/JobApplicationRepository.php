<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\JobApplication;
use Illuminate\Database\Eloquent\Builder;

class JobApplicationRepository
{
    public function __construct(protected JobApplication $model) {}

    public function getDataTableQuery(): Builder
    {
        return $this->model->newQuery()->with(['job', 'city'])->latest('id');
    }

    public function findById(int $id): ?JobApplication
    {
        return $this->model->with(['job', 'city'])->find($id);
    }

    public function updateStatus(JobApplication $application, string $status, ?string $adminNotes = null): bool
    {
        return $application->update([
            'status' => $status,
            'admin_notes' => $adminNotes,
        ]);
    }

    public function delete(JobApplication $application): bool
    {
        return (bool) $application->delete();
    }

    public function deleteMultiple(array $ids): int
    {
        return $this->model->whereIn('id', $ids)->delete();
    }
}
