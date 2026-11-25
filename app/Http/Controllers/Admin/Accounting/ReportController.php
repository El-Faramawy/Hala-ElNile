<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\Accounting;

use App\Http\Controllers\Controller;
use App\Models\ContractTracker;
use App\Services\Accounting\AccountingDashboardService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ReportController extends Controller
{
    public function __construct(protected AccountingDashboardService $dashboardService) {}

    public function index(Request $request): View
    {
        $summary = $this->dashboardService->getFinancialSummary($request->all());
        $contracts = ContractTracker::query()
            ->with(['revenues', 'expenses'])
            ->where('is_active', true)
            ->get();

        return view('Admin.Accounting.Report.index', compact('summary', 'contracts'));
    }
}
