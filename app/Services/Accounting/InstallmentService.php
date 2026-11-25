<?php

declare(strict_types=1);

namespace App\Services\Accounting;

use App\Models\Installment;
use App\Repositories\InstallmentRepository;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Yajra\DataTables\DataTables;

class InstallmentService
{
    public function __construct(protected InstallmentRepository $installmentRepository) {}

    public function getDataTable(?int $contractTrackerId = null): JsonResponse
    {
        $query = $this->installmentRepository->getDataTableQuery($contractTrackerId);

        return DataTables::of($query)
            ->addColumn('checkbox', fn (Installment $installment): string => tableCheckbox($installment->id))
            ->addColumn('applicant_name', fn (Installment $installment): string => $installment->contractTracker?->applicant_name ?? '—')
            ->addColumn('contract_code', function (Installment $installment): string {
                $code = $installment->contractTracker?->contract_code ?? '—';

                return '<span class="badge badge-primary px-2 py-1 font-weight-bold">'.$code.'</span>';
            })
            ->addColumn('formatted_amount', fn (Installment $installment): string => '<span class="font-weight-bold text-success fs-14">'.number_format((float) $installment->amount_eur, 2).' €</span>')
            ->addColumn('formatted_due_date', fn (Installment $installment): string => $installment->due_date?->format('Y-m-d') ?? '—')
            ->addColumn('status_badge', function (Installment $installment): string {
                if ($installment->status === 'paid') {
                    return '<span class="badge badge-success px-3 py-1 font-weight-bold"><i class="fa fa-check mr-1"></i> مسدد</span>';
                }

                $dueDate = Carbon::parse($installment->due_date);
                if ($dueDate->isPast() && ! $dueDate->isToday()) {
                    $daysLate = (int) $dueDate->diffInDays(now());

                    return '<span class="badge badge-danger px-2 py-1 font-weight-bold"><i class="fa fa-clock-o mr-1"></i> متأخر ('.$daysLate.' يوم)</span>';
                }

                return '<span class="badge badge-warning px-2 py-1 font-weight-bold"><i class="fa fa-hourglass-half mr-1"></i> بانتظار الاستحقاق</span>';
            })
            ->addColumn('action', fn (Installment $installment): string => tableAction($installment->id, true, true))
            ->escapeColumns([])
            ->make(true);
    }

    public function getOverdueDataTable(): JsonResponse
    {
        $query = $this->installmentRepository->getOverdueQuery();

        return DataTables::of($query)
            ->addColumn('contract_code', function (Installment $installment): string {
                $code = $installment->contractTracker?->contract_code ?? '—';

                return '<span class="badge badge-primary px-2 py-1 font-weight-bold">'.$code.'</span>';
            })
            ->addColumn('applicant_name', fn (Installment $installment): string => '<span class="font-weight-bold text-dark fs-13">'.e($installment->contractTracker?->applicant_name ?? '—').'</span>')
            ->addColumn('phone_action', function (Installment $installment): string {
                $phone = $installment->contractTracker?->jobApplication?->phone ?? '';
                if (! $phone) {
                    return '<span class="text-muted">—</span>';
                }

                $applicantName = $installment->contractTracker?->applicant_name ?? 'العميل';
                $code = $installment->contractTracker?->contract_code ?? '';
                $amount = number_format((float) $installment->amount_eur, 2);
                $cleanPhone = preg_replace('/[^0-9]/', '', $phone);
                if (! str_starts_with($cleanPhone, '20') && str_starts_with($cleanPhone, '01')) {
                    $cleanPhone = '2'.$cleanPhone;
                }

                $message = urlencode("السلام عليكم أستاذ {$applicantName}، نود تذكيركم بموعد سداد الدفعة المستحقة ({$installment->title}) على عقدكم رقم ({$code}) بقيمة ({$amount} يورو)، برجاء التكرم بالتواصل معنا لترتيب السداد.");
                $whatsappUrl = "https://wa.me/{$cleanPhone}?text={$message}";

                return '<div class="d-flex align-items-center gap-1">
                    <a href="tel:'.$phone.'" class="btn btn-sm btn-outline-info" title="اتصال"><i class="fa fa-phone"></i></a>
                    <a href="'.$whatsappUrl.'" target="_blank" class="btn btn-sm btn-success" title="مراسلة واتساب"><i class="fa fa-whatsapp"></i> واتساب</a>
                </div>';
            })
            ->addColumn('formatted_amount', fn (Installment $installment): string => '<span class="font-weight-bold text-danger fs-14">'.number_format((float) $installment->amount_eur, 2).' €</span>')
            ->addColumn('formatted_due_date', fn (Installment $installment): string => $installment->due_date?->format('Y-m-d') ?? '—')
            ->addColumn('overdue_badge', function (Installment $installment): string {
                $dueDate = Carbon::parse($installment->due_date);
                $days = (int) $dueDate->diffInDays(now());

                return '<span class="badge badge-danger px-2 py-1 font-weight-bold" style="font-size: 11px;"><i class="fa fa-exclamation-triangle mr-1"></i> متأخر منذ '.$days.' يوم</span>';
            })
            ->addColumn('action', function (Installment $installment): string {
                $installmentId = $installment->id;
                $contractId = $installment->contract_tracker_id;
                $amountEur = (float) $installment->amount_eur;

                return '<button type="button" class="btn btn-sm btn-primary collect-installment-btn" data-id="'.$installmentId.'" data-contract-id="'.$contractId.'" data-amount-eur="'.$amountEur.'">
                    <i class="fa fa-money mr-1"></i> تسجيل سند قبض
                </button>';
            })
            ->escapeColumns([])
            ->make(true);
    }

    public function store(array $data): Installment
    {
        return $this->installmentRepository->create($data);
    }

    public function update(Installment $installment, array $data): bool
    {
        return $this->installmentRepository->update($installment, $data);
    }

    public function delete(Installment $installment): bool
    {
        return $this->installmentRepository->delete($installment);
    }

    public function deleteMultiple(array $ids): int
    {
        return $this->installmentRepository->deleteMultiple($ids);
    }

    public function markAsPaid(Installment $installment): bool
    {
        return $this->installmentRepository->markAsPaid($installment);
    }

    /**
     * @return Collection<int, Installment>
     */
    public function getByContractTrackerId(int $contractTrackerId): Collection
    {
        return $this->installmentRepository->getByContractTrackerId($contractTrackerId);
    }
}
