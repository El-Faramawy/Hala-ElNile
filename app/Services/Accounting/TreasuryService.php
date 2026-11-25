<?php

declare(strict_types=1);

namespace App\Services\Accounting;

use App\Models\Treasury;
use App\Repositories\TreasuryRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Yajra\DataTables\DataTables;

class TreasuryService
{
    public function __construct(protected TreasuryRepository $treasuryRepository) {}

    public function getDataTable(): JsonResponse
    {
        $query = $this->treasuryRepository->getDataTableQuery();

        return DataTables::of($query)
            ->addColumn('checkbox', fn (Treasury $treasury): string => tableCheckbox($treasury->id))
            ->addColumn('currency_badge', function (Treasury $treasury): string {
                if ($treasury->currency === 'EUR') {
                    return '<span class="badge badge-primary px-3 py-1 font-weight-bold" style="font-size: 12px;">€ يورو</span>';
                }

                return '<span class="badge badge-info px-3 py-1 font-weight-bold" style="font-size: 12px;">ج.م جنيه</span>';
            })
            ->addColumn('formatted_balance', function (Treasury $treasury): string {
                $currencySymbol = $treasury->currency === 'EUR' ? '€' : 'ج.م';
                $balance = number_format((float) $treasury->balance, 2);

                return '<span class="font-weight-bold text-dark fs-14">'.$balance.' '.$currencySymbol.'</span>';
            })
            ->addColumn('status', fn (Treasury $treasury): string => tableStatus((bool) $treasury->is_active))
            ->addColumn('action', fn (Treasury $treasury): string => tableAction($treasury->id, true, true))
            ->escapeColumns([])
            ->make(true);
    }

    public function store(array $data): Treasury
    {
        return $this->treasuryRepository->create($data);
    }

    public function update(Treasury $treasury, array $data): bool
    {
        return $this->treasuryRepository->update($treasury, $data);
    }

    public function delete(Treasury $treasury): bool
    {
        return $this->treasuryRepository->delete($treasury);
    }

    public function deleteMultiple(array $ids): int
    {
        return $this->treasuryRepository->deleteMultiple($ids);
    }

    /**
     * @return Collection<int, Treasury>
     */
    public function getActiveTreasuries(): Collection
    {
        return $this->treasuryRepository->getAllActive();
    }
}
