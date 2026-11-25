<?php

declare(strict_types=1);

namespace App\Services\JobApplication;

use App\Models\ContractTracker;
use App\Models\JobApplication;
use App\Repositories\JobApplicationRepository;
use Illuminate\Http\JsonResponse;
use Yajra\DataTables\DataTables;

class JobApplicationService
{
    public function __construct(protected JobApplicationRepository $repository) {}

    public function getDataTable(array $filters = []): JsonResponse
    {
        $query = $this->repository->getDataTableQuery($filters);

        return DataTables::of($query)
            ->addColumn('checkbox', fn (JobApplication $jobApplication): string => tableCheckbox($jobApplication->id))
            ->addColumn('code_badge', function (JobApplication $jobApplication): string {
                $code = htmlspecialchars($jobApplication->code ?? (string) $jobApplication->id, ENT_QUOTES, 'UTF-8');

                return '<span class="badge badge-light border text-primary font-monospace fs-12 px-2 py-1">'.$code.'</span>';
            })
            ->addColumn('phone_formatted', function (JobApplication $jobApplication): string {
                $cleanedPhone = preg_replace('/[^0-9]/', '', $jobApplication->phone);

                return '<a href="https://wa.me/'.$cleanedPhone.'" target="_blank" class="fw-bold text-success font-monospace dir-ltr" title="مراسلة واتساب"><i class="fa fa-whatsapp mr-1"></i>'.htmlspecialchars($jobApplication->phone).'</a>';
            })
            ->addColumn('age_formatted', fn (JobApplication $jobApplication): string => $jobApplication->age ? $jobApplication->age.' سنة' : '-')
            ->addColumn('job_name', fn (JobApplication $jobApplication): string => $jobApplication->job_title ?? ($jobApplication->job?->title ?? '-'))
            ->addColumn('city_name', fn (JobApplication $jobApplication): string => $jobApplication->city?->name ?? '-')
            ->addColumn('passport_badge', function (JobApplication $jobApplication): string {
                $situation = htmlspecialchars($jobApplication->passport_situation ?? 'غير محدد');
                $number = $jobApplication->passport_number ? '<br><small class="font-monospace text-muted font-weight-bold">'.htmlspecialchars($jobApplication->passport_number).'</small>' : '';

                return '<span class="badge badge-light border text-dark px-2 py-1">'.$situation.'</span>'.$number;
            })
            ->addColumn('status_badge', function (JobApplication $jobApplication): string {
                return match ($jobApplication->status) {
                    'new' => '<span class="badge badge-primary px-2 py-1"><i class="fa fa-envelope-open mr-1"></i> طلب جديد</span>',
                    'stage_1', 'pending' => '<span class="badge badge-warning text-dark px-2 py-1"><i class="fa fa-clock-o mr-1"></i> مرحلة 1: فحص الأوراق</span>',
                    'stage_2', 'accepted' => '<span class="badge badge-primary px-2 py-1"><i class="fa fa-file-text mr-1"></i> مرحلة 2: اعتماد العقد</span>',
                    'stage_3' => '<span class="badge badge-info px-2 py-1"><i class="fa fa-shield mr-1"></i> مرحلة 3: Nulla Osta</span>',
                    'stage_4' => '<span class="badge badge-secondary px-2 py-1"><i class="fa fa-id-card mr-1"></i> مرحلة 4: تأشيرة السفارة</span>',
                    'stage_5' => '<span class="badge badge-success px-2 py-1"><i class="fa fa-plane mr-1"></i> مرحلة 5: السفر والاستلام</span>',
                    'contacted' => '<span class="badge badge-info px-2 py-1"><i class="fa fa-phone mr-1"></i> تم التواصل</span>',
                    'cancelled', 'rejected' => '<span class="badge badge-danger px-2 py-1" data-toggle="tooltip" title="'.htmlspecialchars($jobApplication->cancellation_reason ?? 'تم الإلغاء').'"><i class="fa fa-ban mr-1"></i> ملغي</span>',
                    default => '<span class="badge badge-warning text-dark px-2 py-1">قيد الانتظار</span>',
                };
            })
            ->addColumn('contract_badge', function (JobApplication $jobApplication): string {
                if ($jobApplication->contract_tracker_id) {
                    $code = htmlspecialchars($jobApplication->contractTracker?->contract_code ?? $jobApplication->code ?? '');

                    return '<span class="badge badge-success px-2 py-1"><i class="fa fa-check-circle mr-1"></i> عقد ساري (#'.$code.')</span>';
                }

                return '<span class="badge badge-light border text-muted px-2 py-1">طلب أولي</span>';
            })
            ->addColumn('created_at_formatted', fn (JobApplication $jobApplication): string => $jobApplication->created_at?->format('Y-m-d H:i') ?? '-')
            ->addColumn('action', function (JobApplication $jobApplication): string {
                $actions = tableAction($jobApplication->id, true, true);

                if (! $jobApplication->contract_tracker_id && ! in_array($jobApplication->status, ['cancelled', 'rejected'], true)) {
                    $escapedId = htmlspecialchars((string) $jobApplication->id, ENT_QUOTES, 'UTF-8');
                    $escapedName = htmlspecialchars($jobApplication->name, ENT_QUOTES, 'UTF-8');
                    $escapedPassport = htmlspecialchars($jobApplication->passport_number ?? '', ENT_QUOTES, 'UTF-8');

                    $actions .= ' <button type="button" class="btn btn-default btn-success btn-sm convertContractBtn" data-toggle="tooltip" title="تحويل الطلب إلى عقد معتمد" data-id="'.$escapedId.'" data-name="'.$escapedName.'" data-passport="'.$escapedPassport.'">
                        <i class="fa fa-file-text-o text-white"></i>
                    </button>';
                }

                return $actions;
            })
            ->escapeColumns([])
            ->make(true);
    }

    public function store(array $data): JobApplication
    {
        return $this->repository->create($data);
    }

    public function update(JobApplication $jobApplication, array $data): bool
    {
        return $this->repository->update($jobApplication, $data);
    }

    public function convertToContract(JobApplication $jobApplication, array $extraContractData = []): ContractTracker
    {
        return $this->repository->convertToContract($jobApplication, $extraContractData);
    }

    public function delete(JobApplication $jobApplication): bool
    {
        return $this->repository->delete($jobApplication);
    }

    public function deleteMultiple(array $ids): int
    {
        return $this->repository->deleteMultiple($ids);
    }
}
