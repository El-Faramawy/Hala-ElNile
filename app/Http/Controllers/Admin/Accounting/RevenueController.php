<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\Accounting;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Accounting\StoreRevenueRequest;
use App\Http\Requests\Admin\Accounting\UpdateRevenueRequest;
use App\Http\Requests\Admin\Common\MultiDeleteRequest;
use App\Models\ContractTracker;
use App\Models\Revenue;
use App\Services\Accounting\RevenueService;
use App\Services\Accounting\SupplierService;
use App\Services\Accounting\TreasuryService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class RevenueController extends Controller
{
    public function __construct(
        protected RevenueService $revenueService,
        protected TreasuryService $treasuryService,
        protected SupplierService $supplierService
    ) {}

    public function index(Request $request): View|JsonResponse
    {
        if ($request->ajax()) {
            return $this->revenueService->getDataTable($request->all());
        }

        $treasuries = $this->treasuryService->getActiveTreasuries();
        $suppliers = $this->supplierService->getActiveSuppliers();
        $contracts = ContractTracker::query()->where('is_active', true)->orderBy('applicant_name', 'asc')->get();

        return view('Admin.Accounting.Revenue.index', compact('treasuries', 'suppliers', 'contracts'));
    }

    public function create(Request $request): View
    {
        $treasuries = $this->treasuryService->getActiveTreasuries();
        $suppliers = $this->supplierService->getActiveSuppliers();
        $contracts = ContractTracker::query()->where('is_active', true)->orderBy('applicant_name', 'asc')->get();
        $scheduledItemId = $request->filled('scheduled_item_id') ? (int) $request->query('scheduled_item_id') : null;
        $scheduledItem = $this->revenueService->findScheduledItem($scheduledItemId);

        return view('Admin.Accounting.Revenue.parts.create', compact('treasuries', 'suppliers', 'contracts', 'scheduledItem'));
    }

    public function store(StoreRevenueRequest $request): JsonResponse
    {
        $revenue = $this->revenueService->store($request->validated());

        return response()->json([
            'message' => __('messages.general.created_success'),
            'id' => $revenue->id,
            'receipt_url' => route('revenues.receipt', $revenue->id),
        ]);
    }

    public function edit(Revenue $revenue): View
    {
        $treasuries = $this->treasuryService->getActiveTreasuries();
        $suppliers = $this->supplierService->getActiveSuppliers();
        $contracts = ContractTracker::query()->where('is_active', true)->orderBy('applicant_name', 'asc')->get();

        return view('Admin.Accounting.Revenue.parts.edit', compact('revenue', 'treasuries', 'suppliers', 'contracts'));
    }

    public function update(UpdateRevenueRequest $request, Revenue $revenue): JsonResponse
    {
        $this->revenueService->update($revenue, $request->validated());

        return response()->json([
            'message' => __('messages.general.updated_success'),
            'id' => $revenue->id,
            'receipt_url' => route('revenues.receipt', $revenue->id),
        ]);
    }

    public function destroy(Revenue $revenue): JsonResponse
    {
        $this->revenueService->delete($revenue);

        return response()->json(['message' => __('messages.general.deleted_success')]);
    }

    public function multiDelete(MultiDeleteRequest $request): JsonResponse
    {
        $count = $this->revenueService->deleteMultiple($request->validated()['ids']);

        return response()->json(['message' => __('messages.general.deleted_success'), 'count' => $count]);
    }

    public function receipt(Revenue $revenue): View
    {
        return view('Admin.Accounting.Revenue.receipt', compact('revenue'));
    }
}
