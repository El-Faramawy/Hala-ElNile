<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Common\MultiDeleteRequest;
use App\Http\Requests\Admin\ContactMessage\UpdateContactMessageRequest;
use App\Models\ContactMessage;
use App\Services\Contact\ContactService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ContactMessageController extends Controller
{
    public function __construct(protected ContactService $service) {}

    public function index(Request $request): View|JsonResponse
    {
        if ($request->ajax()) {
            return $this->service->getDataTable();
        }

        return view('Admin.ContactMessage.index');
    }

    public function edit(ContactMessage $contactMessage): View
    {
        $message = $contactMessage->load('city');

        return view('Admin.ContactMessage.parts.edit', compact('message'));
    }

    public function update(UpdateContactMessageRequest $request, ContactMessage $contactMessage): JsonResponse
    {
        $this->service->update($contactMessage, $request->validated());

        return response()->json(['message' => 'تم تحديث حالة وملاحظات الرسالة بنجاح']);
    }

    public function toggleRead(ContactMessage $contactMessage): JsonResponse
    {
        $this->service->toggleRead($contactMessage);

        $statusText = $contactMessage->fresh()->is_read ? 'مقروءة' : 'جديدة';

        return response()->json([
            'message' => "تم تحديث حالة الرسالة بنجاح إلى: {$statusText}",
            'is_read' => $contactMessage->fresh()->is_read,
        ]);
    }

    public function destroy(ContactMessage $contactMessage): JsonResponse
    {
        $this->service->delete($contactMessage);

        return response()->json(['message' => 'تم حذف الرسالة بنجاح']);
    }

    public function multiDelete(MultiDeleteRequest $request): JsonResponse
    {
        $ids = (array) $request->input('id');
        $this->service->deleteMultiple($ids);

        return response()->json(['message' => 'تم حذف الرسائل المحددة بنجاح']);
    }
}
