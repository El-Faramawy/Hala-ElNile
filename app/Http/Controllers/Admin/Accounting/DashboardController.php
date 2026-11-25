<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\Accounting;

use App\Http\Controllers\Controller;
use App\Services\Accounting\AccountingDashboardService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __construct(protected AccountingDashboardService $dashboardService) {}

    public function index(Request $request): View
    {
        $stats = $this->dashboardService->getLiveStats();
        $summary = $this->dashboardService->getFinancialSummary($request->all());

        return view('Admin.Accounting.Dashboard.index', compact('stats', 'summary'));
    }

    public function summary(Request $request): JsonResponse
    {
        $summary = $this->dashboardService->getFinancialSummary($request->all());

        return response()->json($summary);
    }
}
