<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\ContractTracker;
use App\Models\JobApplication;
use App\Models\Traveler;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

class TravelerRepository
{
    public function __construct(protected Traveler $model) {}

    public function getDataTableQuery(): Builder
    {
        return $this->model->newQuery()->with(['italyCity', 'italyLandmark'])->latest('id');
    }

    public function findById(int $id): ?Traveler
    {
        return $this->model->find($id);
    }

    public function create(array $data): Traveler
    {
        return $this->model->create($data);
    }

    public function update(Traveler $traveler, array $data): bool
    {
        return $traveler->update($data);
    }

    public function delete(Traveler $traveler): bool
    {
        return (bool) $traveler->delete();
    }

    public function deleteMultiple(array $ids): int
    {
        return $this->model->whereIn('id', $ids)->delete();
    }

    /**
     * @return Collection<int, Traveler>
     */
    public function getAllActive(): Collection
    {
        return $this->model->where('is_active', true)->with(['italyCity', 'italyLandmark'])->orderBy('sort_order', 'asc')->get();
    }

    /**
     * Search applicants from ContractTracker and JobApplication by name, code or phone.
     *
     * @return list<array<string, mixed>>
     */
    public function searchApplicants(string $term): array
    {
        $term = trim($term);
        if ($term === '') {
            return [];
        }

        $results = [];
        $seenKeys = [];

        // 1. Search in ContractTracker
        $trackers = ContractTracker::with('jobApplication.city')
            ->where(function (Builder $builder) use ($term): void {
                $builder->where('applicant_name', 'like', "%{$term}%")
                    ->orWhere('contract_code', 'like', "%{$term}%")
                    ->orWhere('passport_number', 'like', "%{$term}%");
            })
            ->take(8)
            ->get();

        foreach ($trackers as $tracker) {
            $name = $tracker->applicant_name;
            $phone = $tracker->jobApplication?->phone ?? '';
            $gov = $tracker->jobApplication?->city?->name ?? '';
            $jobTitle = $tracker->job_title;
            $code = $tracker->contract_code;

            $key = $code ?: $name;
            if (! isset($seenKeys[$key])) {
                $seenKeys[$key] = true;
                $results[] = [
                    'source' => 'عقد ساري',
                    'name' => $name,
                    'phone' => $phone,
                    'governorate' => $gov,
                    'job_title' => $jobTitle,
                    'contract_code' => $code,
                    'label' => "{$name} (عقد: {$code}) - {$jobTitle}",
                ];
            }
        }

        // 2. Search in JobApplication
        $applications = JobApplication::with('city', 'contractTracker')
            ->where(function (Builder $builder) use ($term): void {
                $builder->where('name', 'like', "%{$term}%")
                    ->orWhere('phone', 'like', "%{$term}%")
                    ->orWhere('code', 'like', "%{$term}%")
                    ->orWhere('passport_number', 'like', "%{$term}%");
            })
            ->take(8)
            ->get();

        foreach ($applications as $app) {
            $name = $app->name;
            $phone = $app->phone;
            $gov = $app->city?->name ?? '';
            $jobTitle = $app->job_title ?? ($app->job?->title ?? '');
            $code = $app->contractTracker?->contract_code ?? $app->code ?? '';

            $key = $code ?: ($phone ?: $name);
            if (! isset($seenKeys[$key])) {
                $seenKeys[$key] = true;
                $results[] = [
                    'source' => 'طلب توظيف',
                    'name' => $name,
                    'phone' => $phone,
                    'governorate' => $gov,
                    'job_title' => $jobTitle,
                    'contract_code' => $code,
                    'label' => "{$name} (هاتف: {$phone}) - {$jobTitle}",
                ];
            }
        }

        return $results;
    }
}
