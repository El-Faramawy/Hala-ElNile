<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Common\MultiDeleteRequest;
use App\Http\Requests\Admin\ContractTracker\StoreContractTrackerRequest;
use App\Http\Requests\Admin\ContractTracker\UpdateContractTrackerRequest;
use App\Models\ContractTracker;
use App\Services\ContractTracker\ContractTrackerService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ContractTrackerController extends Controller
{
    public function __construct(protected ContractTrackerService $service) {}

    public function index(Request $request): View|JsonResponse
    {
        if ($request->ajax()) {
            return $this->service->getDataTable();
        }

        return view('Admin.ContractTracker.index');
    }

    public function create(): View
    {
        return view('Admin.ContractTracker.parts.create');
    }

    public function store(StoreContractTrackerRequest $request): JsonResponse
    {
        $this->service->store($request->validated());

        return response()->json(['message' => 'تم إضافة معاملة العقد بنجاح']);
    }

    public function edit(ContractTracker $contractTracker): View
    {
        return view('Admin.ContractTracker.parts.edit', compact('contractTracker'));
    }

    public function update(UpdateContractTrackerRequest $request, ContractTracker $contractTracker): JsonResponse
    {
        $this->service->update($contractTracker, $request->validated());

        return response()->json(['message' => 'تم تحديث بيانات المعاملة بنجاح']);
    }

    public function destroy(ContractTracker $contractTracker): JsonResponse
    {
        $this->service->delete($contractTracker);

        return response()->json(['message' => 'تم حذف المعاملة بنجاح']);
    }

    public function multiDelete(MultiDeleteRequest $request): JsonResponse
    {
        $ids = (array) $request->input('id');
        $this->service->deleteMultiple($ids);

        return response()->json(['message' => 'تم حذف المعاملات المحددة بنجاح']);
    }
}
