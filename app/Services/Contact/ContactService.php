<?php

declare(strict_types=1);

namespace App\Services\Contact;

use App\Models\ContactMessage;
use App\Repositories\ContactMessageRepository;
use App\Services\Contact\DTO\ContactMessageDTO;
use Illuminate\Http\JsonResponse;
use Yajra\DataTables\DataTables;

class ContactService
{
    public function __construct(protected ContactMessageRepository $contactMessageRepository)
    {
    }

    public function getDataTable(): JsonResponse
    {
        $query = $this->contactMessageRepository->getDataTableQuery();

        return DataTables::of($query)
            ->addColumn('checkbox', fn(ContactMessage $message): string => tableCheckbox($message->id))
            ->addColumn('city_name', fn(ContactMessage $message): string => $message->city?->name ?? '-')
            ->addColumn('read_status', function (ContactMessage $message): string {
                if ($message->is_read) {
                    return '<button type="button" class="btn btn-sm btn-outline-success toggle-read-btn" data-id="' . $message->id . '" data-toggle="tooltip" title="انقر للتحويل إلى جديدة">
                                <i class="fa fa-check-circle mr-1"></i> مقروءة
                            </button>';
                }

                return '<button type="button" class="btn btn-sm btn-warning text-dark font-weight-bold toggle-read-btn shadow-sm" data-id="' . $message->id . '" data-toggle="tooltip" title="انقر لتحديد الرسالة كمقروءة">
                            <i class="fa fa-envelope mr-1"></i> تحديد كمقروءة
                        </button>';
            })
            ->addColumn('created_at_formatted', fn(ContactMessage $message): string => $message->created_at?->format('Y-m-d H:i') ?? '-')
            ->addColumn('action', fn(ContactMessage $message): string => tableAction($message->id, true, true))
            ->escapeColumns([])
            ->make(true);
    }

    public function sendMessage(ContactMessageDTO $dto): ContactMessage
    {
        return $this->contactMessageRepository->create($dto->toArray());
    }

    public function update(ContactMessage $message, array $data): bool
    {
        return $this->contactMessageRepository->update($message, $data);
    }

    public function markAsRead(ContactMessage $message): bool
    {
        return $this->contactMessageRepository->markAsRead($message);
    }

    public function toggleRead(ContactMessage $message): bool
    {
        return $this->contactMessageRepository->update($message, [
            'is_read' => !$message->is_read,
        ]);
    }

    public function delete(ContactMessage $message): bool
    {
        return $this->contactMessageRepository->delete($message);
    }

    public function deleteMultiple(array $ids): int
    {
        return $this->contactMessageRepository->deleteMultiple($ids);
    }
}
