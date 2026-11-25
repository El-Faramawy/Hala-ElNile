<?php

declare(strict_types=1);

namespace App\Services\Accounting;

use App\Models\Supplier;
use App\Repositories\SupplierRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Yajra\DataTables\DataTables;

class SupplierService
{
    public function __construct(protected SupplierRepository $supplierRepository) {}

    public function getDataTable(): JsonResponse
    {
        $query = $this->supplierRepository->getDataTableQuery();

        return DataTables::of($query)
            ->addColumn('checkbox', fn (Supplier $supplier): string => tableCheckbox($supplier->id))
            ->addColumn('phone_formatted', function (Supplier $supplier): string {
                if (! $supplier->phone) {
                    return '<span class="text-muted">—</span>';
                }

                return '<a href="tel:'.$supplier->phone.'" class="text-primary font-weight-bold" dir="ltr"><i class="fa fa-phone mr-1"></i>'.$supplier->phone.'</a>';
            })
            ->addColumn('status', fn (Supplier $supplier): string => tableStatus((bool) $supplier->is_active))
            ->addColumn('action', fn (Supplier $supplier): string => tableAction($supplier->id, true, true))
            ->escapeColumns([])
            ->make(true);
    }

    public function store(array $data): Supplier
    {
        return $this->supplierRepository->create($data);
    }

    public function update(Supplier $supplier, array $data): bool
    {
        return $this->supplierRepository->update($supplier, $data);
    }

    public function delete(Supplier $supplier): bool
    {
        return $this->supplierRepository->delete($supplier);
    }

    public function deleteMultiple(array $ids): int
    {
        return $this->supplierRepository->deleteMultiple($ids);
    }

    /**
     * @return Collection<int, Supplier>
     */
    public function getActiveSuppliers(): Collection
    {
        return $this->supplierRepository->getAllActive();
    }
}
