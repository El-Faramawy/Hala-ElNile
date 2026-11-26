<?php

declare(strict_types=1);

namespace App\Services\Accounting;

use App\Models\ContractTracker;
use App\Models\Expense;
use App\Models\Installment;
use App\Models\Revenue;
use App\Repositories\ContractTrackerRepository;
use App\Repositories\TreasuryRepository;
use Illuminate\Http\JsonResponse;
use Yajra\DataTables\DataTables;

class AccountingDashboardService
{
    public function __construct(
        protected TreasuryRepository $treasuryRepository,
        protected ContractTrackerRepository $contractTrackerRepository
    ) {}

    /**
     * @return array<string, mixed>
     */
    public function getLiveStats(): array
    {
        $treasuries = $this->treasuryRepository->getAllActive();

        $egpTotal = (float) $treasuries->where('currency', 'EGP')->sum('balance');
        $eurTotal = (float) $treasuries->where('currency', 'EUR')->sum('balance');

        $overdueQuery = Installment::query()
            ->where('status', '!=', 'paid')
            ->whereDate('due_date', '<', now()->toDateString());

        $overdueCount = $overdueQuery->count();
        $overdueSumEur = (float) $overdueQuery->sum('amount_eur');

        $activeContractsCount = ContractTracker::query()->where('is_active', true)->count();

        return [
            'egp_total' => $egpTotal,
            'eur_total' => $eurTotal,
            'overdue_count' => $overdueCount,
            'overdue_sum_eur' => $overdueSumEur,
            'active_contracts_count' => $activeContractsCount,
            'treasuries' => $treasuries,
        ];
    }

    /**
     * @param  array<string, mixed>  $filters
     * @return array<string, mixed>
     */
    public function getFinancialSummary(array $filters = []): array
    {
        $period = $filters['period'] ?? 'month';
        $calculationMode = $filters['calculation_mode'] ?? 'collected_actual';

        $startDate = null;
        $endDate = null;

        if ($period === 'today') {
            $startDate = now()->startOfDay()->toDateString();
            $endDate = now()->endOfDay()->toDateString();
        } elseif ($period === 'week') {
            $startDate = now()->startOfWeek()->toDateString();
            $endDate = now()->endOfWeek()->toDateString();
        } elseif ($period === 'month') {
            $startDate = now()->startOfMonth()->toDateString();
            $endDate = now()->endOfMonth()->toDateString();
        } elseif ($period === 'year') {
            $startDate = now()->startOfYear()->toDateString();
            $endDate = now()->endOfYear()->toDateString();
        } elseif ($period === 'custom') {
            $startDate = $filters['date_from'] ?? null;
            $endDate = $filters['date_to'] ?? null;
        }

        // Revenues Query
        $revenueQuery = Revenue::query();
        if ($startDate) {
            $revenueQuery->whereDate('transaction_date', '>=', $startDate);
        }
        if ($endDate) {
            $revenueQuery->whereDate('transaction_date', '<=', $endDate);
        }

        // Unconverted EGP revenues (still remaining in EGP treasuries)
        $revenuesEgp = (float) (clone $revenueQuery)
            ->where('received_currency', 'EGP')
            ->whereNull('conversion_id')
            ->sum('amount');

        // Direct EUR revenues (including any auto-gain revenues from conversions)
        $directEurRevenues = (float) (clone $revenueQuery)
            ->where('received_currency', 'EUR')
            ->sum('amount');

        // Converted EGP revenues (using expected EUR; any gain/loss difference is settled by auto revenue/expense)
        $convertedEurFromEgp = (float) (clone $revenueQuery)
            ->where('received_currency', 'EGP')
            ->whereNotNull('conversion_id')
            ->sum('eur_equivalent');

        $revenuesEur = $directEurRevenues + $convertedEurFromEgp;
        $revenuesEurEquivalent = (float) (clone $revenueQuery)->sum('eur_equivalent');

        // Expenses Query
        $expenseQuery = Expense::query();
        if ($startDate) {
            $expenseQuery->whereDate('transaction_date', '>=', $startDate);
        }
        if ($endDate) {
            $expenseQuery->whereDate('transaction_date', '<=', $endDate);
        }

        $expensesEgp = (float) (clone $expenseQuery)->where('currency', 'EGP')->sum('amount');
        $expensesEur = (float) (clone $expenseQuery)->where('currency', 'EUR')->sum('amount');

        // Total Contract Values if calculation mode is total_contract_value
        $totalContractsEur = 0.0;
        if ($calculationMode === 'total_contract_value') {
            $contractQuery = ContractTracker::query();
            if ($startDate) {
                $contractQuery->whereDate('created_at', '>=', $startDate);
            }
            if ($endDate) {
                $contractQuery->whereDate('created_at', '<=', $endDate);
            }
            $totalContractsEur = (float) $contractQuery->sum('total_price_eur');
        }

        $netProfitEgp = $revenuesEgp - $expensesEgp;
        $netProfitEur = ($calculationMode === 'total_contract_value' ? $totalContractsEur : $revenuesEur) - $expensesEur;
        $netProfitEurEquivalent = ($calculationMode === 'total_contract_value' ? $totalContractsEur : $revenuesEurEquivalent) - $expensesEur;

        return [
            'period' => $period,
            'calculation_mode' => $calculationMode,
            'start_date' => $startDate,
            'end_date' => $endDate,
            'revenues_egp' => $revenuesEgp,
            'revenues_eur' => $revenuesEur,
            'revenues_eur_equivalent' => $revenuesEurEquivalent,
            'expenses_egp' => $expensesEgp,
            'expenses_eur' => $expensesEur,
            'total_contracts_eur' => $totalContractsEur,
            'net_profit_egp' => $netProfitEgp,
            'net_profit_eur' => $netProfitEur,
            'net_profit_eur_equivalent' => $netProfitEurEquivalent,
        ];
    }

    public function getContractsFinancialDataTable(): JsonResponse
    {
        $query = $this->contractTrackerRepository->getFinancialDataTableQuery();

        return DataTables::of($query)
            ->addColumn('code_badge', function (ContractTracker $contract): string {
                return '<span class="badge badge-primary px-2 py-1 font-weight-bold fs-12">'.e($contract->contract_code).'</span>';
            })
            ->addColumn('applicant_name', fn (ContractTracker $contract): string => '<span class="font-weight-bold text-dark fs-13">'.e($contract->applicant_name).'</span>')
            ->addColumn('job_city', function (ContractTracker $contract): string {
                $cityName = $contract->city_name ?? 'إيطاليا';
                $jobTitle = $contract->job_title ?? '';
                $jobDisplay = ($cityName !== '' && str_contains($jobTitle, $cityName))
                    ? $jobTitle
                    : trim($jobTitle.' - '.$cityName, ' -');

                return '<span class="text-muted small">'.e($jobDisplay).'</span>';
            })
            ->addColumn('total_price_formatted', function (ContractTracker $contract): string {
                return '<span class="font-weight-bold text-dark fs-14">'.number_format((float) $contract->total_price_eur, 2).' €</span>';
            })
            ->addColumn('total_collected_formatted', function (ContractTracker $contract): string {
                $collectedEur = (float) $contract->revenues->sum('eur_equivalent');

                return '<span class="font-weight-bold text-success fs-14">'.number_format($collectedEur, 2).' €</span>';
            })
            ->addColumn('remaining_formatted', function (ContractTracker $contract): string {
                $total = (float) $contract->total_price_eur;
                $collectedEur = (float) $contract->revenues->sum('eur_equivalent');
                $remaining = max(0.0, $total - $collectedEur);

                return '<span class="font-weight-bold text-danger fs-14">'.number_format($remaining, 2).' €</span>';
            })
            ->addColumn('progress_bar', function (ContractTracker $contract): string {
                $total = (float) $contract->total_price_eur;
                $collectedEur = (float) $contract->revenues->sum('eur_equivalent');
                $percent = $total > 0 ? min(100, (int) round(($collectedEur / $total) * 100)) : 0;
                $barColor = $percent >= 100 ? 'bg-success' : ($percent >= 50 ? 'bg-info' : 'bg-warning');

                return '<div class="d-flex align-items-center" style="gap:8px;">
                    <div class="progress flex-grow-1" style="height: 10px; border-radius: 5px;">
                        <div class="progress-bar '.$barColor.'" role="progressbar" style="width: '.$percent.'%;" aria-valuenow="'.$percent.'" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                    <span class="font-weight-bold small text-muted">'.$percent.'%</span>
                </div>';
            })
            ->addColumn('action', function (ContractTracker $contract): string {
                $contractId = $contract->id;
                $contractCode = e($contract->contract_code);
                $applicantName = e($contract->applicant_name);
                $totalPriceEur = (float) $contract->total_price_eur;
                $supplierId = $contract->supplier_id ?? '';
                $supplierCostEur = (float) ($contract->supplier_cost_eur ?? 0);

                return '<div class="btn-group btn-group-sm">
                    <button type="button" class="btn btn-outline-primary show-contract-ledger-btn" data-id="'.$contractId.'" data-code="'.$contractCode.'" data-name="'.$applicantName.'" data-total="'.$totalPriceEur.'" title="كشف الحساب وجدولة الدفعات">
                        <i class="fa fa-list-alt mr-1"></i> كشف الحساب
                    </button>
                    <button type="button" class="btn btn-outline-warning edit-contract-price-btn" data-id="'.$contractId.'" data-name="'.$applicantName.'" data-total="'.$totalPriceEur.'" data-supplier-id="'.$supplierId.'" data-supplier-cost="'.$supplierCostEur.'" title="تعديل سعر العقد والمورد">
                        <i class="fa fa-edit"></i> السعر
                    </button>
                    <button type="button" class="btn btn-outline-success quick-add-revenue-btn" data-contract-id="'.$contractId.'" data-name="'.$applicantName.'" title="تسجيل دفعة مسددة">
                        <i class="fa fa-plus"></i> قبض
                    </button>
                </div>';
            })
            ->escapeColumns([])
            ->make(true);
    }
}
