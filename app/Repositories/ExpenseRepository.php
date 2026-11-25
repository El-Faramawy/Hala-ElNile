<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Expense;
use Illuminate\Database\Eloquent\Builder;

class ExpenseRepository
{
    public function __construct(protected Expense $model) {}

    public function getDataTableQuery(array $filters = []): Builder
    {
        $query = $this->model->newQuery()
            ->with(['expenseType', 'treasury', 'contractTracker', 'supplier', 'admin', 'conversion'])
            ->latest('id');

        if (! empty($filters['expense_type_id'])) {
            $query->where('expense_type_id', (int) $filters['expense_type_id']);
        }

        if (! empty($filters['treasury_id'])) {
            $query->where('treasury_id', (int) $filters['treasury_id']);
        }

        if (! empty($filters['supplier_id'])) {
            $query->where('supplier_id', (int) $filters['supplier_id']);
        }

        if (! empty($filters['admin_id'])) {
            $query->where('admin_id', (int) $filters['admin_id']);
        }

        if (! empty($filters['currency'])) {
            $query->where('currency', $filters['currency']);
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
                    ->orWhere('other_details', 'like', "%{$searchText}%")
                    ->orWhere('notes', 'like', "%{$searchText}%")
                    ->orWhereHas('contractTracker', function (Builder $contractBuilder) use ($searchText): void {
                        $contractBuilder->where('applicant_name', 'like', "%{$searchText}%")
                            ->orWhere('contract_code', 'like', "%{$searchText}%");
                    })
                    ->orWhereHas('admin', function (Builder $adminBuilder) use ($searchText): void {
                        $adminBuilder->where('name', 'like', "%{$searchText}%");
                    });
            });
        }

        return $query;
    }

    public function findById(int $id): ?Expense
    {
        return $this->model->with(['expenseType', 'treasury', 'contractTracker', 'supplier', 'admin', 'conversion'])->find($id);
    }

    public function create(array $data): Expense
    {
        return $this->model->create($data);
    }

    public function update(Expense $expense, array $data): bool
    {
        return $expense->update($data);
    }

    public function delete(Expense $expense): bool
    {
        return (bool) $expense->delete();
    }

    public function deleteMultiple(array $ids): int
    {
        return $this->model->whereIn('id', $ids)->delete();
    }
}
