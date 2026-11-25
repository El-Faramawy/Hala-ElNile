<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Installment;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

class InstallmentRepository
{
    public function __construct(protected Installment $model) {}

    public function getDataTableQuery(?int $contractTrackerId = null): Builder
    {
        $query = $this->model->newQuery()->with('contractTracker')->latest('due_date');

        if ($contractTrackerId !== null) {
            $query->where('contract_tracker_id', $contractTrackerId);
        }

        return $query;
    }

    public function getOverdueQuery(): Builder
    {
        return $this->model->newQuery()
            ->with('contractTracker')
            ->where('status', '!=', 'paid')
            ->whereDate('due_date', '<', now()->toDateString())
            ->orderBy('due_date', 'asc');
    }

    public function findById(int $id): ?Installment
    {
        return $this->model->find($id);
    }

    public function create(array $data): Installment
    {
        return $this->model->create($data);
    }

    public function update(Installment $installment, array $data): bool
    {
        return $installment->update($data);
    }

    public function delete(Installment $installment): bool
    {
        return (bool) $installment->delete();
    }

    public function deleteMultiple(array $ids): int
    {
        return $this->model->whereIn('id', $ids)->delete();
    }

    public function markAsPaid(Installment $installment): bool
    {
        return $installment->update([
            'status' => 'paid',
            'paid_at' => now()->toDateString(),
        ]);
    }

    /**
     * @return Collection<int, Installment>
     */
    public function getByContractTrackerId(int $contractTrackerId): Collection
    {
        return $this->model->where('contract_tracker_id', $contractTrackerId)
            ->orderBy('due_date', 'asc')
            ->get();
    }
}
