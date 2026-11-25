<?php

declare(strict_types=1);

namespace App\Services\ContractTracker;

use App\Models\ContractTracker;
use App\Repositories\ContractTrackerRepository;
use Illuminate\Http\JsonResponse;
use Yajra\DataTables\DataTables;

class ContractTrackerService
{
    public function __construct(protected ContractTrackerRepository $repository) {}

    public function getDataTable(): JsonResponse
    {
        $query = $this->repository->getDataTableQuery();

        return $this->buildDataTable($query);
    }

    public function getActiveContractsDataTable(): JsonResponse
    {
        $query = $this->repository->getActiveDataTableQuery();

        return $this->buildDataTable($query);
    }

    protected function buildDataTable($query): JsonResponse
    {
        return DataTables::of($query)
            ->addColumn('checkbox', fn (ContractTracker $contractTracker): string => tableCheckbox($contractTracker->id))
            ->addColumn('contract_code_badge', fn (ContractTracker $contractTracker): string => '<span class="badge badge-light border text-primary font-monospace fs-12 px-2 py-1">'.htmlspecialchars($contractTracker->contract_code).'</span>')
            ->addColumn('passport_badge', fn (ContractTracker $contractTracker): string => '<span class="font-monospace text-dark font-weight-bold">'.htmlspecialchars($contractTracker->passport_number).'</span>')
            ->addColumn('step_badge', function (ContractTracker $contractTracker): string {
                $labels = [
                    1 => '1. استلام وفحص الملف',
                    2 => '2. التقديم بنظام فلوتسي',
                    3 => '3. صدور الـ Nulla Osta',
                    4 => '4. استلام التأشيرة الإيطالية',
                    5 => '5. السفر والاستقرار بإيطاليا',
                ];
                $label = $labels[$contractTracker->step] ?? 'المرحلة '.$contractTracker->step;

                return '<span class="badge badge-info px-2 py-1">'.$label.'</span>';
            })
            ->addColumn('status', fn (ContractTracker $contractTracker): string => tableStatus((bool) $contractTracker->is_active))
            ->addColumn('action', fn (ContractTracker $contractTracker): string => tableAction($contractTracker->id, true, true))
            ->escapeColumns([])
            ->make(true);
    }

    public function store(array $data): ContractTracker
    {
        return $this->repository->create($data);
    }

    public function update(ContractTracker $tracker, array $data): bool
    {
        return $this->repository->update($tracker, $data);
    }

    public function delete(ContractTracker $tracker): bool
    {
        return $this->repository->delete($tracker);
    }

    public function deleteMultiple(array $ids): int
    {
        return $this->repository->deleteMultiple($ids);
    }

    public function findByPassportOrCode(string $identifier): ?ContractTracker
    {
        return $this->repository->findByPassportOrCode(trim($identifier));
    }
}
