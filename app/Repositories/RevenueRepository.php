<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Revenue;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

class RevenueRepository
{
    public function __construct(protected Revenue $model) {}

    public function getDataTableQuery(array $filters = []): Builder
    {
        $query = $this->model->newQuery()
            ->with(['treasury', 'contractTracker', 'supplier', 'conversion'])
            ->latest('id');

        if (! empty($filters['treasury_id'])) {
            $query->where('treasury_id', (int) $filters['treasury_id']);
        }

        if (! empty($filters['supplier_id'])) {
            $query->where('supplier_id', (int) $filters['supplier_id']);
        }

        if (! empty($filters['received_currency'])) {
            $query->where('received_currency', $filters['received_currency']);
        }

        if (isset($filters['conversion_status'])) {
            if ($filters['conversion_status'] === 'converted') {
                $query->whereNotNull('conversion_id');
            } elseif ($filters['conversion_status'] === 'pending') {
                $query->where('received_currency', 'EGP')->whereNull('conversion_id');
            } elseif ($filters['conversion_status'] === 'direct_eur') {
                $query->where('received_currency', 'EUR');
            }
        }

        if (! empty($filters['date_from'])) {
            $query->whereDate('transaction_date', '>=', $filters['date_from']);
        }

        if (! empty($filters['date_to'])) {
            $query->whereDate('transaction_date', '<=', $filters['date_to']);
        }

        if (! empty($filters['search_text'])) {
            $searchText = trim((string) $filters['search_text']);
            $query->where(function (Builder $builder) use ($searchText): void {
                $builder->where('id', $searchText)
                    ->orWhereHas('contractTracker', function (Builder $contractBuilder) use ($searchText): void {
                        $contractBuilder->where('applicant_name', 'like', "%{$searchText}%")
                            ->orWhere('contract_code', 'like', "%{$searchText}%")
                            ->orWhere('passport_number', 'like', "%{$searchText}%");
                    });
            });
        }

        return $query;
    }

    public function findById(int $id): ?Revenue
    {
        return $this->model->with(['treasury', 'contractTracker', 'supplier', 'conversion'])->find($id);
    }

    /**
     * @param  list<int>  $ids
     * @return Collection<int, Revenue>
     */
    public function findManyByIds(array $ids): Collection
    {
        return $this->model->whereIn('id', $ids)->get();
    }

    public function create(array $data): Revenue
    {
        return $this->model->create($data);
    }

    public function update(Revenue $revenue, array $data): bool
    {
        return $revenue->update($data);
    }

    public function delete(Revenue $revenue): bool
    {
        return (bool) $revenue->delete();
    }

    public function deleteMultiple(array $ids): int
    {
        return $this->model->whereIn('id', $ids)->delete();
    }

    public function markAsConverted(array $ids, int $conversionId): int
    {
        return $this->model->whereIn('id', $ids)->update(['conversion_id' => $conversionId]);
    }
}
