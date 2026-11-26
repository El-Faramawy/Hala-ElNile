<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\ScheduledItem;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;

class ScheduledItemRepository
{
    public function __construct(protected ScheduledItem $model) {}

    public function getDataTableQuery(array $filters = []): Builder
    {
        $query = $this->model->newQuery()
            ->with(['treasury', 'expenseType.expenseCategory', 'supplier'])
            ->orderByDesc('is_active')
            ->orderBy('next_due_date', 'asc');

        if (! empty($filters['type'])) {
            $query->where('type', $filters['type']);
        }

        if (! empty($filters['frequency'])) {
            $query->where('frequency', $filters['frequency']);
        }

        if (! empty($filters['due_status'])) {
            $weekAhead = now()->addDays(7)->toDateString();
            if ($filters['due_status'] === 'due_soon') {
                $query->where('is_active', true)->whereDate('next_due_date', '<=', $weekAhead);
            } elseif ($filters['due_status'] === 'completed') {
                $query->where('is_active', false);
            }
        }

        return $query;
    }

    public function findById(int $id): ?ScheduledItem
    {
        return $this->model->find($id);
    }

    public function create(array $data): ScheduledItem
    {
        return $this->model->create($data);
    }

    public function update(ScheduledItem $scheduledItem, array $data): bool
    {
        return $scheduledItem->update($data);
    }

    public function delete(ScheduledItem $scheduledItem): bool
    {
        return (bool) $scheduledItem->delete();
    }

    public function deleteMultiple(array $ids): int
    {
        return $this->model->whereIn('id', $ids)->delete();
    }

    /**
     * Get count of active scheduled items due within the next 7 days (including overdue) and the earliest due date.
     *
     * @return array{count: int, earliest_date: ?string}
     */
    public function getDueSoonSummary(int $daysAhead = 7): array
    {
        $cutoffDate = now()->addDays($daysAhead)->toDateString();
        $baseQuery = $this->model->newQuery()
            ->where('is_active', true)
            ->whereDate('next_due_date', '<=', $cutoffDate);

        $count = (clone $baseQuery)->count();
        $earliestItem = (clone $baseQuery)->orderBy('next_due_date', 'asc')->first();

        return [
            'count' => $count,
            'earliest_date' => $earliestItem?->next_due_date?->format('Y-m-d'),
        ];
    }

    public function markAsProcessed(int $scheduledItemId, string $transactionDate): void
    {
        $scheduledItem = $this->findById($scheduledItemId);
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

        $this->update($scheduledItem, $updatePayload);
    }
}
