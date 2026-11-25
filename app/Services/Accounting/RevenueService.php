<?php

declare(strict_types=1);

namespace App\Services\Accounting;

use App\Models\Revenue;
use App\Repositories\RevenueRepository;
use App\Repositories\TreasuryRepository;
use App\Traits\UploadTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class RevenueService
{
    use UploadTrait;

    public function __construct(
        protected RevenueRepository $revenueRepository,
        protected TreasuryRepository $treasuryRepository
    ) {}

    public function getDataTable(array $filters = []): JsonResponse
    {
        $query = $this->revenueRepository->getDataTableQuery($filters);

        return DataTables::of($query)
            ->addColumn('checkbox', function (Revenue $revenue): string {
                $isEligible = ($revenue->received_currency === 'EGP' && empty($revenue->conversion_id));
                $eligibleClass = $isEligible ? 'convert-eligible-chk' : '';

                return '<input type="checkbox" class="sub_chk '.$eligibleClass.'" data-id="'.$revenue->id.'" data-egp="'.$revenue->amount.'" data-eur="'.($revenue->eur_equivalent ?? 0).'" data-currency="'.$revenue->received_currency.'" data-converted="'.($revenue->conversion_id ? '1' : '0').'">';
            })
            ->addColumn('voucher_no', fn (Revenue $revenue): string => '<span class="badge badge-default font-weight-bold fs-13 px-2 py-1">#'.$revenue->id.'</span>')
            ->addColumn('formatted_date', fn (Revenue $revenue): string => $revenue->transaction_date?->format('Y-m-d') ?? '—')
            ->addColumn('client_or_contract', function (Revenue $revenue): string {
                if ($revenue->contractTracker) {
                    $name = e($revenue->contractTracker->applicant_name);
                    $code = e($revenue->contractTracker->contract_code);

                    return '<div><span class="font-weight-bold text-dark">'.$name.'</span><br><span class="badge badge-light text-primary border" style="font-size:11px;">عقد: '.$code.'</span></div>';
                }

                if ($revenue->supplier) {
                    return '<span class="text-info font-weight-bold"><i class="fa fa-truck mr-1"></i>'.e($revenue->supplier->name).'</span>';
                }

                return '<span class="text-muted">إيراد عام</span>';
            })
            ->addColumn('amount_formatted', function (Revenue $revenue): string {
                $amount = number_format((float) $revenue->amount, 2);
                if ($revenue->received_currency === 'EUR') {
                    return '<span class="font-weight-bold text-primary fs-14">'.$amount.' €</span>';
                }

                return '<span class="font-weight-bold text-success fs-14">'.$amount.' ج.م</span>';
            })
            ->addColumn('eur_equivalent_formatted', function (Revenue $revenue): string {
                if ($revenue->eur_equivalent === null) {
                    return '<span class="text-muted">—</span>';
                }

                return '<span class="font-weight-bold text-dark fs-13">'.number_format((float) $revenue->eur_equivalent, 2).' €</span>';
            })
            ->addColumn('treasury_name', function (Revenue $revenue): string {
                $name = e($revenue->treasury?->name ?? '—');
                $isEur = $revenue->treasury?->currency === 'EUR';
                $badgeClass = $isEur ? 'badge-primary' : 'badge-info';

                return '<span class="badge '.$badgeClass.' px-2 py-1"><i class="fa fa-university mr-1"></i>'.$name.'</span>';
            })
            ->addColumn('image', function (Revenue $revenue): string {
                if ($revenue->image) {
                    return tableImage($revenue->image, 'وصل قبض #'.$revenue->id);
                }

                return '<span class="badge badge-light border text-muted" style="font-size:11px;">بدون إيصال</span>';
            })
            ->addColumn('conversion_status_badge', function (Revenue $revenue): string {
                if ($revenue->received_currency === 'EUR') {
                    return '<span class="badge badge-success px-2 py-1" style="font-size:11px;"><i class="fa fa-check-circle mr-1"></i> يورو مباشر</span>';
                }

                if ($revenue->conversion_id) {
                    return '<a href="'.route('conversions.index').'" class="badge badge-primary px-2 py-1" style="font-size:11px;"><i class="fa fa-exchange mr-1"></i> تم التحويل (#'.$revenue->conversion_id.')</a>';
                }

                return '<span class="badge badge-warning px-2 py-1" style="font-size:11px;"><i class="fa fa-clock-o mr-1"></i> بانتظار التحويل</span>';
            })
            ->addColumn('action', function (Revenue $revenue): string {
                $receiptUrl = route('revenues.receipt', $revenue->id);
                $printButton = '<a href="'.$receiptUrl.'" target="_blank" class="btn btn-sm mb-2 mb-xl-0" style="background-color: #f59e0b; border-color: #f59e0b; color: #ffffff;" data-toggle="tooltip" title="طباعة سند القبض"><i class="fa fa-print text-white"></i></a> ';

                return $printButton.tableAction($revenue->id, true, true);
            })
            ->escapeColumns([])
            ->make(true);
    }

    public function store(array $data): Revenue
    {
        return DB::transaction(function () use ($data): Revenue {
            if ($data['received_currency'] === 'EUR') {
                $data['eur_equivalent'] = $data['amount'];
            }

            if (isset($data['image']) && $data['image'] instanceof UploadedFile) {
                $data['image'] = $this->uploadImage($data['image'], 'revenues');
            }

            $revenue = $this->revenueRepository->create($data);

            $treasury = $this->treasuryRepository->findById((int) $data['treasury_id']);
            if ($treasury) {
                $this->treasuryRepository->incrementBalance($treasury, (float) $data['amount']);
            }

            return $revenue;
        });
    }

    public function update(Revenue $revenue, array $data): bool
    {
        return DB::transaction(function () use ($revenue, $data): bool {
            $oldTreasury = $this->treasuryRepository->findById((int) $revenue->treasury_id);
            if ($oldTreasury) {
                $this->treasuryRepository->decrementBalance($oldTreasury, (float) $revenue->amount);
            }

            if ($data['received_currency'] === 'EUR') {
                $data['eur_equivalent'] = $data['amount'];
            }

            if (isset($data['image']) && $data['image'] instanceof UploadedFile) {
                $data['image'] = $this->uploadImage($data['image'], 'revenues', $revenue->image);
            } else {
                unset($data['image']);
            }

            $updated = $this->revenueRepository->update($revenue, $data);

            $newTreasury = $this->treasuryRepository->findById((int) $data['treasury_id']);
            if ($newTreasury) {
                $this->treasuryRepository->incrementBalance($newTreasury, (float) $data['amount']);
            }

            return $updated;
        });
    }

    public function delete(Revenue $revenue): bool
    {
        return DB::transaction(function () use ($revenue): bool {
            $treasury = $this->treasuryRepository->findById((int) $revenue->treasury_id);
            if ($treasury) {
                $this->treasuryRepository->decrementBalance($treasury, (float) $revenue->amount);
            }

            if ($revenue->image) {
                $this->uploadImage(null, 'revenues', $revenue->image);
            }

            return $this->revenueRepository->delete($revenue);
        });
    }

    public function deleteMultiple(array $ids): int
    {
        return DB::transaction(function () use ($ids): int {
            $revenues = $this->revenueRepository->findManyByIds($ids);
            foreach ($revenues as $revenue) {
                $treasury = $this->treasuryRepository->findById((int) $revenue->treasury_id);
                if ($treasury) {
                    $this->treasuryRepository->decrementBalance($treasury, (float) $revenue->amount);
                }

                if ($revenue->image) {
                    $this->uploadImage(null, 'revenues', $revenue->image);
                }
            }

            return $this->revenueRepository->deleteMultiple($ids);
        });
    }

    public function findById(int $id): ?Revenue
    {
        return $this->revenueRepository->findById($id);
    }
}
