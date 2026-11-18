<?php

declare(strict_types=1);

namespace App\Services\Job;

use App\Models\Job;
use App\Repositories\JobRepository;
use App\Traits\UploadTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\UploadedFile;
use Yajra\DataTables\DataTables;

class JobService
{
    use UploadTrait;

    public function __construct(protected JobRepository $jobRepository) {}

    public function getDataTable(): JsonResponse
    {
        $query = $this->jobRepository->getDataTableQuery();

        return DataTables::of($query)
            ->addColumn('checkbox', fn (Job $job): string => tableCheckbox($job->id))
            ->addColumn('image', fn (Job $job): string => tableImage($job->image, $job->title))
            ->addColumn('category', fn (Job $job): string => $job->category?->name ?? '-')
            ->addColumn('status', fn (Job $job): string => tableStatus((bool) $job->is_active))
            ->addColumn('action', fn (Job $job): string => tableAction($job->id, true, true))
            ->escapeColumns([])
            ->make(true);
    }

    public function store(array $data, ?UploadedFile $image = null): Job
    {
        if ($image) {
            $data['image'] = $this->uploadImage($image, 'jobs');
        }

        if (isset($data['requirements']) && is_string($data['requirements'])) {
            $data['requirements'] = array_values(array_filter(array_map('trim', explode("\n", $data['requirements']))));
        }

        return $this->jobRepository->create($data);
    }

    public function update(Job $job, array $data, ?UploadedFile $image = null): bool
    {
        if ($image) {
            $data['image'] = $this->uploadImage($image, 'jobs', $job->getRawOriginal('image') ?: $job->image);
        }

        if (isset($data['requirements']) && is_string($data['requirements'])) {
            $data['requirements'] = array_values(array_filter(array_map('trim', explode("\n", $data['requirements']))));
        }

        return $this->jobRepository->update($job, $data);
    }

    public function delete(Job $job): bool
    {
        return $this->jobRepository->delete($job);
    }

    public function deleteMultiple(array $ids): int
    {
        return $this->jobRepository->deleteMultiple($ids);
    }
}
