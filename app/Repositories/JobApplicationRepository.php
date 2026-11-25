<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\ContractTracker;
use App\Models\JobApplication;
use Illuminate\Database\Eloquent\Builder;

class JobApplicationRepository
{
    public function __construct(
        protected JobApplication $model,
        protected ContractTrackerRepository $contractTrackerRepository
    ) {}

    public function getDataTableQuery(array $filters = []): Builder
    {
        $query = $this->model->newQuery()->with(['job', 'city', 'contractTracker'])->latest('id');

        if (! empty($filters['status'])) {
            $status = (string) $filters['status'];
            if ($status === 'stage_1') {
                $query->whereIn('status', ['stage_1', 'pending']);
            } elseif ($status === 'contracts') {
                $query->where(function (Builder $builder): void {
                    $builder->whereIn('status', ['stage_2', 'stage_3', 'stage_4', 'stage_5', 'accepted'])
                        ->orWhereNotNull('contract_tracker_id');
                });
            } elseif ($status === 'cancelled') {
                $query->whereIn('status', ['cancelled', 'rejected']);
            } else {
                $query->where('status', $status);
            }
        }

        if (! empty($filters['job_id'])) {
            $query->where('job_id', (int) $filters['job_id']);
        }

        return $query;
    }

    public function findById(int $id): ?JobApplication
    {
        return $this->model->with(['job', 'city', 'contractTracker'])->find($id);
    }

    public function generateUniqueNumericCode(): string
    {
        do {
            $code = (string) random_int(100000, 999999);
        } while ($this->model->where('code', $code)->exists());

        return $code;
    }

    public function getStageTitle(string $stage): string
    {
        return match ($stage) {
            'new' => 'طلب جديد',
            'stage_1', 'pending' => 'المرحلة 1: استلام وفحص أوراق الملف',
            'stage_2', 'accepted' => 'المرحلة 2: اعتماد العقد ونظام فلوتسي (Decreto Flussi)',
            'stage_3' => 'المرحلة 3: صدور تصريح العمل والموافقة الأمنية (Nulla Osta)',
            'stage_4' => 'المرحلة 4: حجز البصمة واستلام التأشيرة من السفارة',
            'stage_5' => 'المرحلة 5: السفر والاستقبال في إيطاليا واستلام العمل',
            'contacted' => 'تم التواصل مع المتقدم',
            'cancelled', 'rejected' => 'ملغي',
            default => 'قيد الانتظار',
        };
    }

    public function create(array $data): JobApplication
    {
        if (empty($data['code'])) {
            $data['code'] = $this->generateUniqueNumericCode();
        }

        $initialStatus = (string) ($data['status'] ?? 'new');
        $data['status'] = $initialStatus;

        if (empty($data['stage_history'])) {
            $data['stage_history'] = [
                [
                    'stage' => $initialStatus,
                    'title' => $this->getStageTitle($initialStatus),
                    'changed_at' => now()->format('Y-m-d H:i:s'),
                    'admin_name' => auth('admin')->user()?->name ?? 'الموقع الإلكتروني',
                    'notes' => $data['admin_notes'] ?? ($initialStatus === 'new' ? 'تسجيل طلب توظيف جديد عبر الموقع الإلكتروني' : 'تسجيل طلب التوظيف الجديد'),
                ],
            ];
        }

        $jobApplication = $this->model->create($data);

        if (in_array($initialStatus, ['stage_2', 'stage_3', 'stage_4', 'stage_5', 'accepted'], true)) {
            $this->convertToContract($jobApplication);
        }

        return $jobApplication;
    }

    public function update(JobApplication $jobApplication, array $data): bool
    {
        $newStatus = (string) ($data['status'] ?? $jobApplication->status);

        if ($newStatus !== $jobApplication->status) {
            $history = $jobApplication->stage_history ?? [];
            $history[] = [
                'stage' => $newStatus,
                'title' => $this->getStageTitle($newStatus),
                'changed_at' => now()->format('Y-m-d H:i:s'),
                'admin_name' => auth('admin')->user()?->name ?? 'لوحة التحكم',
                'notes' => $data['admin_notes'] ?? ($data['cancellation_reason'] ?? 'تحديث مرحلة الطلب'),
            ];
            $data['stage_history'] = $history;
        }

        $updated = (bool) $jobApplication->update($data);

        if ($newStatus === 'stage_2' && ! $jobApplication->contract_tracker_id) {
            $this->convertToContract($jobApplication);
        }

        return $updated;
    }

    public function convertToContract(JobApplication $jobApplication, array $extraContractData = []): ContractTracker
    {
        if ($jobApplication->contract_tracker_id && $jobApplication->contractTracker) {
            return $jobApplication->contractTracker;
        }

        $contractTracker = $this->contractTrackerRepository->createFromJobApplication($jobApplication, $extraContractData);

        $history = $jobApplication->stage_history ?? [];
        $history[] = [
            'stage' => 'stage_2',
            'title' => 'المرحلة 2: اعتماد العقد والتحويل لعقود العمل الموثقة',
            'changed_at' => now()->format('Y-m-d H:i:s'),
            'admin_name' => auth('admin')->user()?->name ?? 'لوحة التحكم',
            'notes' => 'تم تحويل الطلب إلى عقد ساري برقم '.$contractTracker->contract_code,
        ];

        $jobApplication->update([
            'contract_tracker_id' => $contractTracker->id,
            'converted_to_contract_at' => now(),
            'status' => 'stage_2',
            'stage_history' => $history,
        ]);

        return $contractTracker;
    }

    public function delete(JobApplication $jobApplication): bool
    {
        return (bool) $jobApplication->delete();
    }

    public function deleteMultiple(array $ids): int
    {
        return $this->model->whereIn('id', $ids)->delete();
    }
}
