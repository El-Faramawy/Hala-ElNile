<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\ContractTracker;
use App\Models\JobApplication;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

class ContractTrackerRepository
{
    public function __construct(protected ContractTracker $model) {}

    /**
     * @return Collection<int, ContractTracker>
     */
    public function getAllActiveOrdered(): Collection
    {
        return $this->model->newQuery()
            ->where('is_active', true)
            ->orderBy('applicant_name', 'asc')
            ->get();
    }

    public function getDataTableQuery(): Builder
    {
        return $this->model->newQuery()->latest('id');
    }

    public function getActiveDataTableQuery(): Builder
    {
        return $this->model->newQuery()->where('is_active', true)->latest('id');
    }

    public function getFinancialDataTableQuery(): Builder
    {
        return $this->model->newQuery()
            ->with(['revenues', 'expenses', 'installments'])
            ->latest('id');
    }

    public function findById(int $id): ?ContractTracker
    {
        return $this->model->find($id);
    }

    public function create(array $data): ContractTracker
    {
        return $this->model->create($data);
    }

    public function createFromJobApplication(JobApplication $jobApplication, array $attributes = []): ContractTracker
    {
        $contractCode = $attributes['contract_code'] ?? ($jobApplication->code ?? (string) random_int(100000, 999999));
        $passportNumber = $attributes['passport_number'] ?? ($jobApplication->passport_number ?? 'قيد الاستخراج');

        return $this->model->create([
            'job_application_id' => $jobApplication->id,
            'contract_code' => $contractCode,
            'passport_number' => $passportNumber,
            'applicant_name' => $jobApplication->name,
            'job_title' => $jobApplication->job_title ?? ($jobApplication->job?->title ?? 'عقد عمل بإيطاليا'),
            'city_name' => $jobApplication->city?->name ?? 'إيطاليا',
            'step' => (int) ($attributes['step'] ?? 2),
            'status_text' => $attributes['status_text'] ?? 'تم اعتماد العقد والموافقة وبدء إجراءات التسجيل بنظام فلوتسي (Decreto Flussi)',
            'issue_date' => $attributes['issue_date'] ?? now()->toDateString(),
            'notes' => $jobApplication->admin_notes,
            'stage_history' => $jobApplication->stage_history,
            'is_active' => true,
        ]);
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

    public function findByPassportOrCode(string $identifier): ?ContractTracker
    {
        return $this->model->newQuery()
            ->where('is_active', true)
            ->where(function (Builder $builder) use ($identifier): void {
                $builder->where('passport_number', $identifier)
                    ->orWhere('contract_code', $identifier);
            })
            ->first();
    }
}
