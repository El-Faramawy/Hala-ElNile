<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\Accounting;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Accounting\StoreTreasuryRequest;
use App\Http\Requests\Admin\Accounting\UpdateTreasuryRequest;
use App\Http\Requests\Admin\Common\MultiDeleteRequest;
use App\Models\Treasury;
use App\Services\Accounting\TreasuryService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TreasuryController extends Controller
{
    public function __construct(protected TreasuryService $treasuryService) {}

    public function index(Request $request): View|JsonResponse
    {
        if ($request->ajax()) {
            return $this->treasuryService->getDataTable();
        }

        return view('Admin.Accounting.Treasury.index');
    }

    public function create(): View
    {
        return view('Admin.Accounting.Treasury.parts.create');
    }

    public function store(StoreTreasuryRequest $request): JsonResponse
    {
        $this->treasuryService->store($request->validated());

        return response()->json(['message' => __('messages.general.created_success')]);
    }

    public function edit(Treasury $treasury): View
    {
        return view('Admin.Accounting.Treasury.parts.edit', compact('treasury'));
    }

    public function update(UpdateTreasuryRequest $request, Treasury $treasury): JsonResponse
    {
        $this->treasuryService->update($treasury, $request->validated());

        return response()->json(['message' => __('messages.general.updated_success')]);
    }

    public function destroy(Treasury $treasury): JsonResponse
    {
        $this->treasuryService->delete($treasury);

        return response()->json(['message' => __('messages.general.deleted_success')]);
    }

    public function multiDelete(MultiDeleteRequest $request): JsonResponse
    {
        $count = $this->treasuryService->deleteMultiple($request->validated()['ids']);

        return response()->json(['message' => __('messages.general.deleted_success'), 'count' => $count]);
    }
}
