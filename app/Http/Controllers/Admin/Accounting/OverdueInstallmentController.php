<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\Accounting;

use App\Http\Controllers\Controller;
use App\Models\Installment;
use App\Services\Accounting\InstallmentService;
use App\Services\Accounting\TreasuryService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class OverdueInstallmentController extends Controller
{
    public function __construct(
        protected InstallmentService $installmentService,
        protected TreasuryService $treasuryService
    ) {}

    public function index(Request $request): View|JsonResponse
    {
        if ($request->ajax()) {
            return $this->installmentService->getOverdueDataTable();
        }

        $treasuries = $this->treasuryService->getActiveTreasuries();

        return view('Admin.Accounting.Overdue.index', compact('treasuries'));
    }

    public function markPaid(Installment $installment): JsonResponse
    {
        $this->installmentService->markAsPaid($installment);

        return response()->json(['message' => 'تم تسجيل سداد الدفعة بنجاح.']);
    }
}
