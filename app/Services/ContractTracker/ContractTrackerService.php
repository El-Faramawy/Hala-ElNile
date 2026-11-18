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

        return DataTables::of($query)
            ->addColumn('checkbox', fn (ContractTracker $tracker): string => tableCheckbox($tracker->id))
            ->addColumn('contract_code_badge', fn (ContractTracker $tracker): string => '<span class="badge badge-light border text-primary font-monospace fs-12 px-2 py-1">'.htmlspecialchars($tracker->contract_code).'</span>')
            ->addColumn('passport_badge', fn (ContractTracker $tracker): string => '<span class="font-monospace text-dark font-weight-bold">'.htmlspecialchars($tracker->passport_number).'</span>')
            ->addColumn('step_badge', function (ContractTracker $tracker): string {
                $labels = [
                    1 => '1. استلام وفحص الملف',
                    2 => '2. التقديم بنظام فلوتسي',
                    3 => '3. صدور الـ Nulla Osta',
                    4 => '4. استلام التأشيرة الإيطالية',
                    5 => '5. السفر والاستقرار بإيطاليا',
                ];
                $label = $labels[$tracker->step] ?? 'المرحلة '.$tracker->step;

                return '<span class="badge badge-info px-2 py-1">'.$label.'</span>';
            })
            ->addColumn('status', fn (ContractTracker $tracker): string => tableStatus((bool) $tracker->is_active))
            ->addColumn('action', fn (ContractTracker $tracker): string => tableAction($tracker->id, true, true))
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
}
