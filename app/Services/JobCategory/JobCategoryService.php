<?php

declare(strict_types=1);

namespace App\Services\JobCategory;

use App\Models\JobCategory;
use App\Repositories\JobCategoryRepository;
use App\Traits\UploadTrait;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\UploadedFile;
use Yajra\DataTables\DataTables;

class JobCategoryService
{
    use UploadTrait;

    public function __construct(protected JobCategoryRepository $jobCategoryRepository) {}

    public function getDataTable(): JsonResponse
    {
        $query = $this->jobCategoryRepository->getDataTableQuery();

        return DataTables::of($query)
            ->addColumn('checkbox', fn (JobCategory $jobCategory): string => tableCheckbox($jobCategory->id))
            ->addColumn('icon', fn (JobCategory $jobCategory): string => tableIcon($jobCategory->icon, 'fa-solid fa-briefcase'))
            ->addColumn('display_location_badge', function (JobCategory $jobCategory): string {
                return match ($jobCategory->display_location) {
                    'website' => '<span class="badge badge-info px-2 py-1">الموقع الإلكتروني فقط</span>',
                    'dashboard' => '<span class="badge badge-warning text-dark px-2 py-1">لوحة التحكم فقط</span>',
                    default => '<span class="badge badge-primary px-2 py-1">الموقع واللوحة معاً</span>',
                };
            })
            ->addColumn('status', fn (JobCategory $jobCategory): string => tableStatus((bool) $jobCategory->is_active))
            ->addColumn('action', fn (JobCategory $jobCategory): string => tableAction($jobCategory->id, true, true))
            ->escapeColumns([])
            ->make(true);
    }

    public function store(array $data, ?UploadedFile $icon = null): JobCategory
    {
        unset($data['icon_file']);

        if ($icon) {
            $data['icon'] = $this->uploadImage($icon, 'job_categories/icons');
        }

        return $this->jobCategoryRepository->create($data);
    }

    public function update(JobCategory $jobCategory, array $data, ?UploadedFile $icon = null): bool
    {
        unset($data['icon_file']);

        if ($icon) {
            $data['icon'] = $this->uploadImage($icon, 'job_categories/icons', $jobCategory->getRawOriginal('icon') ?: $jobCategory->icon);
        }

        return $this->jobCategoryRepository->update($jobCategory, $data);
    }

    public function delete(JobCategory $jobCategory): bool
    {
        return $this->jobCategoryRepository->delete($jobCategory);
    }

    public function deleteMultiple(array $ids): int
    {
        return $this->jobCategoryRepository->deleteMultiple($ids);
    }

    public function getJobCategories(): Collection
    {
        return $this->jobCategoryRepository->getAllActive();
    }

    public function getJobCategoriesForWebsite(): Collection
    {
        return $this->jobCategoryRepository->getActiveForWebsite();
    }

    public function getJobCategoriesForDashboard(): Collection
    {
        return $this->jobCategoryRepository->getActiveForDashboard();
    }
}
