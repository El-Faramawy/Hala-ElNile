<?php

declare(strict_types=1);

namespace App\Services\Accounting;

use App\Models\Conversion;
use App\Repositories\ConversionRepository;
use App\Repositories\ExpenseRepository;
use App\Repositories\ExpenseTypeRepository;
use App\Repositories\RevenueRepository;
use App\Repositories\TreasuryRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;
use Yajra\DataTables\DataTables;

class ConversionService
{
    public function __construct(
        protected ConversionRepository $conversionRepository,
        protected RevenueRepository $revenueRepository,
        protected TreasuryRepository $treasuryRepository,
        protected ExpenseRepository $expenseRepository,
        protected ExpenseTypeRepository $expenseTypeRepository
    ) {}

    public function getDataTable(): JsonResponse
    {
        $query = $this->conversionRepository->getDataTableQuery();

        return DataTables::of($query)
            ->addColumn('batch_no', fn (Conversion $conversion): string => '<span class="badge badge-default font-weight-bold fs-13 px-2 py-1">#'.$conversion->id.'</span>')
            ->addColumn('formatted_date', fn (Conversion $conversion): string => $conversion->conversion_date?->format('Y-m-d') ?? '—')
            ->addColumn('total_egp_formatted', fn (Conversion $conversion): string => '<span class="font-weight-bold text-dark fs-13">'.number_format((float) $conversion->total_egp, 2).' ج.م</span>')
            ->addColumn('expected_eur_formatted', fn (Conversion $conversion): string => '<span class="font-weight-bold text-info fs-13">'.number_format((float) $conversion->expected_eur, 2).' €</span>')
            ->addColumn('actual_eur_formatted', fn (Conversion $conversion): string => '<span class="font-weight-bold text-success fs-14">'.number_format((float) $conversion->actual_eur, 2).' €</span>')
            ->addColumn('diff_badge', function (Conversion $conversion): string {
                $diff = (float) $conversion->diff_eur;
                if ($conversion->diff_type === 'loss') {
                    return '<span class="badge badge-danger px-2 py-1 font-weight-bold"><i class="fa fa-arrow-down mr-1"></i> نقص: '.number_format(abs($diff), 2).' €</span>';
                }

                if ($conversion->diff_type === 'gain') {
                    return '<span class="badge badge-success px-2 py-1 font-weight-bold"><i class="fa fa-arrow-up mr-1"></i> زيادة: +'.number_format($diff, 2).' €</span>';
                }

                return '<span class="badge badge-light border text-muted px-2 py-1"><i class="fa fa-check mr-1"></i> مطابق تماماً</span>';
            })
            ->addColumn('result_details', function (Conversion $conversion): string {
                if ($conversion->diff_type === 'loss' && $conversion->diff_ref_id) {
                    return '<span class="text-danger small font-weight-bold"><i class="fa fa-file-text-o mr-1"></i> تم إنشاء سند صرف تلقائي #'.$conversion->diff_ref_id.'</span>';
                }

                if ($conversion->diff_type === 'gain' && $conversion->diff_ref_id) {
                    return '<span class="text-success small font-weight-bold"><i class="fa fa-file-text-o mr-1"></i> تم إنشاء سند إيراد تلقائي #'.$conversion->diff_ref_id.'</span>';
                }

                return '<span class="text-muted small">تم الإيداع بالكامل</span>';
            })
            ->addColumn('treasury_name', fn (Conversion $conversion): string => '<span class="badge badge-primary px-2 py-1"><i class="fa fa-university mr-1"></i>'.e($conversion->toTreasury?->name ?? 'خزينة يورو').'</span>')
            ->escapeColumns([])
            ->make(true);
    }

    /**
     * @param array{
     *     revenue_ids: list<int>,
     *     actual_eur: float|numeric-string,
     *     to_treasury_id: int,
     *     conversion_date?: string,
     *     notes?: string|null
     * } $data
     */
    public function executeConversion(array $data): Conversion
    {
        return DB::transaction(function () use ($data): Conversion {
            $revenueIds = array_map('intval', (array) $data['revenue_ids']);
            if (empty($revenueIds)) {
                throw new InvalidArgumentException('يجب تحديد إيراد واحد على الأقل للتحويل.');
            }

            $revenues = $this->revenueRepository->findManyByIds($revenueIds);
            if ($revenues->isEmpty()) {
                throw new InvalidArgumentException('لم يتم العثور على الإيرادات المحددة.');
            }

            $totalEgp = 0.0;
            $expectedEur = 0.0;

            foreach ($revenues as $revenue) {
                if ($revenue->received_currency !== 'EGP') {
                    throw new InvalidArgumentException("الإيراد رقم #{$revenue->id} ليس بالجنيه المصري.");
                }
                if ($revenue->conversion_id !== null) {
                    throw new InvalidArgumentException("الإيراد رقم #{$revenue->id} تم تحويله مسبقاً.");
                }

                $totalEgp += (float) $revenue->amount;
                $expectedEur += (float) ($revenue->eur_equivalent ?? 0.0);

                // Deduct EGP from source treasury
                $sourceTreasury = $this->treasuryRepository->findById((int) $revenue->treasury_id);
                if ($sourceTreasury) {
                    $this->treasuryRepository->decrementBalance($sourceTreasury, (float) $revenue->amount);
                }
            }

            $actualEur = (float) $data['actual_eur'];
            $diffEur = round($actualEur - $expectedEur, 2);

            $diffType = 'matched';
            if ($diffEur < 0) {
                $diffType = 'loss';
            } elseif ($diffEur > 0) {
                $diffType = 'gain';
            }

            $conversionDate = $data['conversion_date'] ?? now()->toDateString();
            $toTreasuryId = (int) $data['to_treasury_id'];

            // 1. Create Conversion record
            $conversion = $this->conversionRepository->create([
                'total_egp' => $totalEgp,
                'expected_eur' => $expectedEur,
                'actual_eur' => $actualEur,
                'diff_eur' => $diffEur,
                'diff_type' => $diffType,
                'to_treasury_id' => $toTreasuryId,
                'conversion_date' => $conversionDate,
                'notes' => $data['notes'] ?? null,
            ]);

            // 2. Mark revenues as converted
            $this->revenueRepository->markAsConverted($revenueIds, $conversion->id);

            // 3. Deposit actual_eur into destination treasury
            $toTreasury = $this->treasuryRepository->findById($toTreasuryId);
            if ($toTreasury) {
                $this->treasuryRepository->incrementBalance($toTreasury, $actualEur);
            }

            // 4. Handle difference
            if ($diffType === 'loss') {
                $lossAmount = abs($diffEur);
                $lossType = $this->expenseTypeRepository->getConversionLossType();

                $expense = $this->expenseRepository->create([
                    'expense_type_id' => $lossType->id,
                    'treasury_id' => $toTreasuryId,
                    'conversion_id' => $conversion->id,
                    'currency' => 'EUR',
                    'amount' => $lossAmount,
                    'is_auto' => true,
                    'transaction_date' => $conversionDate,
                    'notes' => "فارق تحويل عملة تلقائي للعملية #{$conversion->id} (تم استلام {$actualEur} € بدلاً من {$expectedEur} €)",
                ]);

                $conversion->update(['diff_ref_id' => $expense->id]);
            } elseif ($diffType === 'gain') {
                $gainAmount = $diffEur;

                $revenue = $this->revenueRepository->create([
                    'treasury_id' => $toTreasuryId,
                    'conversion_id' => $conversion->id,
                    'received_currency' => 'EUR',
                    'amount' => $gainAmount,
                    'eur_equivalent' => $gainAmount,
                    'transaction_date' => $conversionDate,
                    'notes' => "باقي تحويل عملة تلقائي للعملية #{$conversion->id} (زيادة {$gainAmount} €)",
                ]);

                $conversion->update(['diff_ref_id' => $revenue->id]);
            }

            return $conversion;
        });
    }

    public function findById(int $id): ?Conversion
    {
        return $this->conversionRepository->findById($id);
    }
}
