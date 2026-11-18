<?php

declare(strict_types=1);

namespace App\Services\JobApplication;

use App\Models\JobApplication;
use App\Repositories\JobApplicationRepository;
use Illuminate\Http\JsonResponse;
use Yajra\DataTables\DataTables;

class JobApplicationService
{
    public function __construct(protected JobApplicationRepository $repository) {}

    public function getDataTable(): JsonResponse
    {
        $query = $this->repository->getDataTableQuery();

        return DataTables::of($query)
            ->addColumn('checkbox', fn (JobApplication $app): string => tableCheckbox($app->id))
            ->addColumn('job_name', fn (JobApplication $app): string => $app->job_title ?? ($app->job?->title ?? '-'))
            ->addColumn('city_name', fn (JobApplication $app): string => $app->city?->name ?? '-')
            ->addColumn('status_badge', function (JobApplication $app): string {
                return match ($app->status) {
                    'accepted' => '<span class="badge badge-success px-2 py-1">مقبول</span>',
                    'contacted' => '<span class="badge badge-info px-2 py-1">تم التواصل</span>',
                    'rejected' => '<span class="badge badge-danger px-2 py-1">مرفوض</span>',
                    default => '<span class="badge badge-warning text-dark px-2 py-1">قيد الانتظار</span>',
                };
            })
            ->addColumn('created_at_formatted', fn (JobApplication $app): string => $app->created_at?->format('Y-m-d H:i') ?? '-')
            ->addColumn('action', fn (JobApplication $app): string => tableAction($app->id, true, true))
            ->escapeColumns([])
            ->make(true);
    }

    public function updateStatus(JobApplication $application, string $status, ?string $adminNotes = null): bool
    {
        return $this->repository->updateStatus($application, $status, $adminNotes);
    }

    public function delete(JobApplication $application): bool
    {
        return $this->repository->delete($application);
    }

    public function deleteMultiple(array $ids): int
    {
        return $this->repository->deleteMultiple($ids);
    }
}
