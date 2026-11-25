<?php

declare(strict_types=1);

namespace App\Services\Accounting;

use App\Models\Expense;
use App\Repositories\ExpenseRepository;
use App\Repositories\ExpenseTypeRepository;
use App\Repositories\TreasuryRepository;
use App\Traits\UploadTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class ExpenseService
{
    use UploadTrait;

    public function __construct(
        protected ExpenseRepository $expenseRepository,
        protected ExpenseTypeRepository $expenseTypeRepository,
        protected TreasuryRepository $treasuryRepository
    ) {}

    public function getDataTable(array $filters = []): JsonResponse
    {
        $query = $this->expenseRepository->getDataTableQuery($filters);

        return DataTables::of($query)
            ->addColumn('checkbox', fn (Expense $expense): string => tableCheckbox($expense->id))
            ->addColumn('voucher_no', fn (Expense $expense): string => '<span class="badge badge-default font-weight-bold fs-13 px-2 py-1">#'.$expense->id.'</span>')
            ->addColumn('formatted_date', fn (Expense $expense): string => $expense->transaction_date?->format('Y-m-d') ?? '—')
            ->addColumn('type_badge', function (Expense $expense): string {
                $typeName = e($expense->expenseType?->name ?? 'مصروف عام');
                if ($expense->is_auto) {
                    return '<span class="badge badge-danger px-2 py-1" style="font-size:11px;"><i class="fa fa-cogs mr-1"></i>'.$typeName.' (فارق تحويل)</span>';
                }

                return '<span class="badge badge-light border text-dark font-weight-bold px-2 py-1" style="font-size:12px;">'.$typeName.'</span>';
            })
            ->addColumn('amount_formatted', function (Expense $expense): string {
                $amount = number_format((float) $expense->amount, 2);
                if ($expense->currency === 'EUR') {
                    return '<span class="font-weight-bold text-danger fs-14">'.$amount.' €</span>';
                }

                return '<span class="font-weight-bold text-danger fs-14">'.$amount.' ج.م</span>';
            })
            ->addColumn('beneficiary', function (Expense $expense): string {
                $badges = [];

                if ($expense->admin) {
                    $badges[] = '<span class="badge badge-info px-2 py-1"><i class="fa fa-user mr-1"></i>موظف: '.e($expense->admin->name).'</span>';
                }

                if ($expense->supplier) {
                    $badges[] = '<span class="badge badge-secondary px-2 py-1"><i class="fa fa-truck mr-1"></i>مورد: '.e($expense->supplier->name).'</span>';
                }

                if ($expense->contractTracker) {
                    $badges[] = '<span class="badge badge-light border text-primary px-2 py-1"><i class="fa fa-file-text-o mr-1"></i>عقد: '.e($expense->contractTracker->applicant_name).'</span>';
                }

                if (! empty($badges)) {
                    return '<div class="d-flex flex-column align-items-start" style="gap: 4px;">'.implode('', $badges).'</div>';
                }

                if ($expense->other_details) {
                    return '<span class="text-dark small font-weight-bold">'.e($expense->other_details).'</span>';
                }

                return '<span class="text-muted">مصروف عام</span>';
            })
            ->addColumn('treasury_name', function (Expense $expense): string {
                $name = e($expense->treasury?->name ?? '—');
                $isEur = $expense->treasury?->currency === 'EUR';
                $badgeClass = $isEur ? 'badge-primary' : 'badge-info';

                return '<span class="badge '.$badgeClass.' px-2 py-1"><i class="fa fa-university mr-1"></i>'.$name.'</span>';
            })
            ->addColumn('image', function (Expense $expense): string {
                if ($expense->image) {
                    return tableImage($expense->image, 'إيصال صرف #'.$expense->id);
                }

                return '<span class="badge badge-light border text-muted" style="font-size:11px;">بدون إيصال</span>';
            })
            ->addColumn('action', function (Expense $expense): string {
                $voucherUrl = route('expenses.voucher', $expense->id);
                $printButton = '<a href="'.$voucherUrl.'" target="_blank" class="btn btn-sm mb-2 mb-xl-0" style="background-color: #f59e0b; border-color: #f59e0b; color: #ffffff;" data-toggle="tooltip" title="طباعة سند الصرف"><i class="fa fa-print text-white"></i></a> ';

                return $printButton.tableAction($expense->id, true, true);
            })
            ->escapeColumns([])
            ->make(true);
    }

    public function store(array $data): Expense
    {
        return DB::transaction(function () use ($data): Expense {
            if (isset($data['image']) && $data['image'] instanceof UploadedFile) {
                $data['image'] = $this->uploadImage($data['image'], 'expenses');
            }

            $expense = $this->expenseRepository->create($data);

            $treasury = $this->treasuryRepository->findById((int) $data['treasury_id']);
            if ($treasury) {
                $this->treasuryRepository->decrementBalance($treasury, (float) $data['amount']);
            }

            return $expense;
        });
    }

    public function update(Expense $expense, array $data): bool
    {
        return DB::transaction(function () use ($expense, $data): bool {
            $oldTreasury = $this->treasuryRepository->findById((int) $expense->treasury_id);
            if ($oldTreasury) {
                $this->treasuryRepository->incrementBalance($oldTreasury, (float) $expense->amount);
            }

            if (isset($data['image']) && $data['image'] instanceof UploadedFile) {
                $data['image'] = $this->uploadImage($data['image'], 'expenses', $expense->image);
            } else {
                unset($data['image']);
            }

            $updated = $this->expenseRepository->update($expense, $data);

            $newTreasury = $this->treasuryRepository->findById((int) $data['treasury_id']);
            if ($newTreasury) {
                $this->treasuryRepository->decrementBalance($newTreasury, (float) $data['amount']);
            }

            return $updated;
        });
    }

    public function delete(Expense $expense): bool
    {
        return DB::transaction(function () use ($expense): bool {
            $treasury = $this->treasuryRepository->findById((int) $expense->treasury_id);
            if ($treasury) {
                $this->treasuryRepository->incrementBalance($treasury, (float) $expense->amount);
            }

            if ($expense->image) {
                $this->uploadImage(null, 'expenses', $expense->image);
            }

            return $this->expenseRepository->delete($expense);
        });
    }

    public function deleteMultiple(array $ids): int
    {
        return DB::transaction(function () use ($ids): int {
            $expenses = $this->expenseRepository->getDataTableQuery()->whereIn('id', $ids)->get();
            foreach ($expenses as $expense) {
                $treasury = $this->treasuryRepository->findById((int) $expense->treasury_id);
                if ($treasury) {
                    $this->treasuryRepository->incrementBalance($treasury, (float) $expense->amount);
                }

                if ($expense->image) {
                    $this->uploadImage(null, 'expenses', $expense->image);
                }
            }

            return $this->expenseRepository->deleteMultiple($ids);
        });
    }

    public function findById(int $id): ?Expense
    {
        return $this->expenseRepository->findById($id);
    }
}
