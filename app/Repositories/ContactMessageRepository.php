<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\ContactMessage;
use Illuminate\Database\Eloquent\Builder;

class ContactMessageRepository
{
    public function __construct(protected ContactMessage $model) {}

    public function getDataTableQuery(): Builder
    {
        return $this->model->newQuery()->with('city')->latest('id');
    }

    public function findById(int $id): ?ContactMessage
    {
        return $this->model->with('city')->find($id);
    }

    /**
     * @param  array<string, mixed>  $data
     */
    public function create(array $data): ContactMessage
    {
        return $this->model->create($data);
    }

    public function update(ContactMessage $message, array $data): bool
    {
        return $message->update($data);
    }

    public function markAsRead(ContactMessage $message): bool
    {
        return $message->update(['is_read' => true]);
    }

    public function delete(ContactMessage $message): bool
    {
        return (bool) $message->delete();
    }

    public function deleteMultiple(array $ids): int
    {
        return $this->model->whereIn('id', $ids)->delete();
    }
}
