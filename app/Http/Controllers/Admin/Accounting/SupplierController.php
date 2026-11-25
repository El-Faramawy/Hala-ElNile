<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\Accounting;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Accounting\StoreSupplierRequest;
use App\Http\Requests\Admin\Accounting\UpdateSupplierRequest;
use App\Http\Requests\Admin\Common\MultiDeleteRequest;
use App\Models\Supplier;
use App\Services\Accounting\SupplierService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SupplierController extends Controller
{
    public function __construct(protected SupplierService $supplierService) {}

    public function index(Request $request): View|JsonResponse
    {
        if ($request->ajax()) {
            return $this->supplierService->getDataTable();
        }

        return view('Admin.Accounting.Supplier.index');
    }

    public function create(): View
    {
        return view('Admin.Accounting.Supplier.parts.create');
    }

    public function store(StoreSupplierRequest $request): JsonResponse
    {
        $this->supplierService->store($request->validated());

        return response()->json(['message' => __('messages.general.created_success')]);
    }

    public function edit(Supplier $supplier): View
    {
        return view('Admin.Accounting.Supplier.parts.edit', compact('supplier'));
    }

    public function update(UpdateSupplierRequest $request, Supplier $supplier): JsonResponse
    {
        $this->supplierService->update($supplier, $request->validated());

        return response()->json(['message' => __('messages.general.updated_success')]);
    }

    public function destroy(Supplier $supplier): JsonResponse
    {
        $this->supplierService->delete($supplier);

        return response()->json(['message' => __('messages.general.deleted_success')]);
    }

    public function multiDelete(MultiDeleteRequest $request): JsonResponse
    {
        $count = $this->supplierService->deleteMultiple($request->validated()['ids']);

        return response()->json(['message' => __('messages.general.deleted_success'), 'count' => $count]);
    }
}
