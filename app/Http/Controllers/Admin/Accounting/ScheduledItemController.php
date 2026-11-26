<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\Accounting;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Accounting\ProcessScheduledItemRequest;
use App\Http\Requests\Admin\Accounting\StoreScheduledItemRequest;
use App\Http\Requests\Admin\Accounting\UpdateScheduledItemRequest;
use App\Http\Requests\Admin\Common\MultiDeleteRequest;
use App\Models\ScheduledItem;
use App\Services\Accounting\ScheduledItemService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ScheduledItemController extends Controller
{
    public function __construct(protected ScheduledItemService $scheduledItemService) {}

    public function index(Request $request): View|JsonResponse
    {
        if ($request->ajax()) {
            return $this->scheduledItemService->getDataTable($request->all());
        }

        return view('Admin.Accounting.ScheduledItem.index', $this->scheduledItemService->getFormDependencies());
    }

    public function create(): View
    {
        return view('Admin.Accounting.ScheduledItem.parts.create', $this->scheduledItemService->getFormDependencies());
    }

    public function store(StoreScheduledItemRequest $request): JsonResponse
    {
        $this->scheduledItemService->store($request->validated());

        return response()->json(['message' => __('messages.general.created_success')]);
    }

    public function edit(ScheduledItem $scheduledItem): View
    {
        return view('Admin.Accounting.ScheduledItem.parts.edit', array_merge(
            ['scheduledItem' => $scheduledItem],
            $this->scheduledItemService->getFormDependencies()
        ));
    }

    public function update(UpdateScheduledItemRequest $request, ScheduledItem $scheduledItem): JsonResponse
    {
        $this->scheduledItemService->update($scheduledItem, $request->validated());

        return response()->json(['message' => __('messages.general.updated_success')]);
    }

    public function destroy(ScheduledItem $scheduledItem): JsonResponse
    {
        $this->scheduledItemService->delete($scheduledItem);

        return response()->json(['message' => __('messages.general.deleted_success')]);
    }

    public function multiDelete(MultiDeleteRequest $request): JsonResponse
    {
        $count = $this->scheduledItemService->deleteMultiple($request->validated()['ids']);

        return response()->json(['message' => __('messages.general.deleted_success'), 'count' => $count]);
    }

    public function process(ProcessScheduledItemRequest $request, ScheduledItem $scheduledItem): JsonResponse
    {
        $this->scheduledItemService->processPayment($scheduledItem, $request->validated());

        return response()->json(['message' => 'تم تسجيل العملية في الخزينة وتحديث موعد الاستحقاق بنجاح.']);
    }
}
