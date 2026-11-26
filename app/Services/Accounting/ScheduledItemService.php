<?php

declare(strict_types=1);

namespace App\Services\Accounting;

use App\Models\ScheduledItem;
use App\Repositories\ExpenseCategoryRepository;
use App\Repositories\ExpenseTypeRepository;
use App\Repositories\ScheduledItemRepository;
use App\Repositories\SupplierRepository;
use App\Repositories\TreasuryRepository;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class ScheduledItemService
{
    public function __construct(
        protected ScheduledItemRepository $scheduledItemRepository,
        protected TreasuryRepository $treasuryRepository,
        protected ExpenseCategoryRepository $expenseCategoryRepository,
        protected ExpenseTypeRepository $expenseTypeRepository,
        protected SupplierRepository $supplierRepository,
        protected ExpenseService $expenseService,
        protected RevenueService $revenueService
    ) {}

    /**
     * @return array<string, mixed>
     */
    public function getFormDependencies(): array
    {
        return [
            'treasuries' => $this->treasuryRepository->getAllActive(),
            'expenseCategories' => $this->expenseCategoryRepository->getAllActive(),
            'expenseTypes' => $this->expenseTypeRepository->getAllActive(),
            'suppliers' => $this->supplierRepository->getAllActive(),
            'dueSoonSummary' => $this->scheduledItemRepository->getDueSoonSummary(7),
        ];
    }

    public function getDataTable(array $filters = []): JsonResponse
    {
        $query = $this->scheduledItemRepository->getDataTableQuery($filters);

        return DataTables::of($query)
            ->addColumn('checkbox', fn (ScheduledItem $item): string => tableCheckbox($item->id))
            ->addColumn('type_badge', function (ScheduledItem $item): string {
                if ($item->type === 'revenue') {
                    return '<span class="badge badge-success px-2 py-1"><i class="fa fa-arrow-down ml-1"></i>إيراد مجدول</span>';
                }

                return '<span class="badge badge-danger px-2 py-1"><i class="fa fa-arrow-up ml-1"></i>مصروف مجدول</span>';
            })
            ->addColumn('frequency_badge', function (ScheduledItem $item): string {
                return match ($item->frequency) {
                    'monthly' => '<span class="badge badge-primary px-2 py-1">شهري</span>',
                    'yearly' => '<span class="badge badge-info px-2 py-1">سنوي</span>',
                    default => '<span class="badge badge-secondary px-2 py-1">تاريخ محدد</span>',
                };
            })
            ->addColumn('amount_formatted', function (ScheduledItem $item): string {
                $amount = number_format((float) $item->amount, 2);
                $symbol = $item->currency === 'EUR' ? '€' : 'ج.م';
                $colorClass = $item->type === 'revenue' ? 'text-success' : 'text-danger';

                return '<span class="font-weight-bold '.$colorClass.' fs-14">'.$amount.' '.$symbol.'</span>';
            })
            ->addColumn('due_date_badge', fn (ScheduledItem $item): string => $this->formatDueDateBadge($item))
            ->addColumn('party_details', function (ScheduledItem $item): string {
                $details = [];
                if ($item->expenseType) {
                    $cat = $item->expenseType->expenseCategory?->name;
                    $details[] = '<span class="badge badge-light border text-dark">'.e($cat ? $cat.' — '.$item->expenseType->name : $item->expenseType->name).'</span>';
                }
                if ($item->supplier) {
                    $details[] = '<span class="badge badge-secondary">مورد: '.e($item->supplier->name).'</span>';
                }
                if ($item->treasury) {
                    $details[] = '<span class="badge badge-light border text-primary">'.e($item->treasury->name).'</span>';
                }

                return ! empty($details) ? '<div class="d-flex flex-wrap" style="gap:4px;">'.implode('', $details).'</div>' : '<span class="text-muted">—</span>';
            })
            ->addColumn('action', function (ScheduledItem $item): string {
                $processButton = '';
                if ($item->is_active) {
                    $btnLabel = $item->type === 'revenue' ? 'تحصيل' : 'سداد';
                    $processButton = '<button type="button" class="btn btn-success btn-sm mb-2 mb-xl-0 process-scheduled-btn" '
                        .'data-id="'.$item->id.'" '
                        .'data-title="'.e($item->title).'" '
                        .'data-type="'.$item->type.'" '
                        .'data-amount="'.$item->amount.'" '
                        .'data-currency="'.$item->currency.'" '
                        .'data-treasury-id="'.($item->treasury_id ?? '').'" '
                        .'data-toggle="tooltip" title="تنفيذ '.$btnLabel.' وتجديد الموعد">'
                        .'<i class="fa fa-check ml-1"></i>'.$btnLabel.'</button> ';
                }

                return $processButton.tableAction($item->id, true, true);
            })
            ->escapeColumns([])
            ->make(true);
    }

    public function store(array $data): ScheduledItem
    {
        return $this->scheduledItemRepository->create($data);
    }

    public function update(ScheduledItem $scheduledItem, array $data): bool
    {
        return $this->scheduledItemRepository->update($scheduledItem, $data);
    }

    public function delete(ScheduledItem $scheduledItem): bool
    {
        return $this->scheduledItemRepository->delete($scheduledItem);
    }

    public function deleteMultiple(array $ids): int
    {
        return $this->scheduledItemRepository->deleteMultiple($ids);
    }

    public function processPayment(ScheduledItem $scheduledItem, array $data): void
    {
        DB::transaction(function () use ($scheduledItem, $data): void {
            $notes = trim((string) ($data['notes'] ?? ''));
            $defaultNote = ($scheduledItem->type === 'revenue' ? 'تحصيل مجدول: ' : 'مصروف مجدول: ').$scheduledItem->title;
            $finalNotes = $notes !== '' ? $notes : $defaultNote;

            if ($scheduledItem->type === 'expense') {
                $expenseTypeId = $scheduledItem->expense_type_id
                    ?? $this->expenseTypeRepository->getOtherType()?->id
                    ?? $this->expenseTypeRepository->getAllActive()->first()?->id;

                $this->expenseService->store([
                    'expense_type_id' => $expenseTypeId,
                    'treasury_id' => (int) $data['treasury_id'],
                    'supplier_id' => $scheduledItem->supplier_id,
                    'currency' => $scheduledItem->currency,
                    'amount' => $data['amount'],
                    'other_details' => $scheduledItem->title,
                    'transaction_date' => $data['transaction_date'],
                    'notes' => $finalNotes,
                ]);
            } else {
                $this->revenueService->store([
                    'treasury_id' => (int) $data['treasury_id'],
                    'supplier_id' => $scheduledItem->supplier_id,
                    'received_currency' => $scheduledItem->currency,
                    'amount' => $data['amount'],
                    'eur_equivalent' => $scheduledItem->currency === 'EUR'
                        ? $data['amount']
                        : ($data['eur_equivalent'] ?? null),
                    'transaction_date' => $data['transaction_date'],
                    'notes' => $finalNotes,
                ]);
            }

            $updatePayload = [
                'last_paid_at' => $data['transaction_date'],
            ];

            if ($scheduledItem->frequency === 'monthly') {
                $updatePayload['next_due_date'] = Carbon::parse($scheduledItem->next_due_date)
                    ->addMonthNoOverflow()
                    ->toDateString();
            } elseif ($scheduledItem->frequency === 'yearly') {
                $updatePayload['next_due_date'] = Carbon::parse($scheduledItem->next_due_date)
                    ->addYearNoOverflow()
                    ->toDateString();
            } else {
                $updatePayload['is_active'] = false;
            }

            $this->scheduledItemRepository->update($scheduledItem, $updatePayload);
        });
    }

    public function markAsProcessed(int $scheduledItemId, string $transactionDate): void
    {
        $scheduledItem = $this->scheduledItemRepository->findById($scheduledItemId);
        if (! $scheduledItem) {
            return;
        }

        $updatePayload = [
            'last_paid_at' => $transactionDate,
        ];

        if ($scheduledItem->frequency === 'monthly') {
            $updatePayload['next_due_date'] = Carbon::parse($scheduledItem->next_due_date)
                ->addMonthNoOverflow()
                ->toDateString();
        } elseif ($scheduledItem->frequency === 'yearly') {
            $updatePayload['next_due_date'] = Carbon::parse($scheduledItem->next_due_date)
                ->addYearNoOverflow()
                ->toDateString();
        } else {
            $updatePayload['is_active'] = false;
        }

        $this->scheduledItemRepository->update($scheduledItem, $updatePayload);
    }

    private function formatDueDateBadge(ScheduledItem $item): string
    {
        if (! $item->is_active) {
            return '<span class="badge badge-light border text-muted px-2 py-1">مكتمل / متوقف</span>';
        }

        $dueDate = $item->next_due_date;
        if (! $dueDate) {
            return '—';
        }

        $today = now()->startOfDay();
        $daysLeft = (int) $today->diffInDays($dueDate->copy()->startOfDay(), false);

        if ($daysLeft < 0) {
            return '<div><span class="badge badge-danger px-2 py-1">متأخر ('.abs($daysLeft).' يوم)</span><div class="small text-danger font-weight-bold mt-1">'.$dueDate->format('Y-m-d').'</div></div>';
        }

        if ($daysLeft <= 7) {
            $label = $daysLeft === 0 ? 'مستحق اليوم' : 'خلال '.$daysLeft.' يوم';

            return '<div><span class="badge badge-warning text-dark px-2 py-1">'.$label.'</span><div class="small text-dark font-weight-bold mt-1">'.$dueDate->format('Y-m-d').'</div></div>';
        }

        return '<span class="badge badge-light border text-dark px-2 py-1 font-weight-bold">'.$dueDate->format('Y-m-d').'</span>';
    }
}
