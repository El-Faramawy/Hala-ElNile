<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\Accounting;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Accounting\ExecuteConversionRequest;
use App\Models\Conversion;
use App\Services\Accounting\ConversionService;
use App\Services\Accounting\TreasuryService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ConversionController extends Controller
{
    public function __construct(
        protected ConversionService $conversionService,
        protected TreasuryService $treasuryService
    ) {}

    public function index(Request $request): View|JsonResponse
    {
        if ($request->ajax()) {
            return $this->conversionService->getDataTable();
        }

        $eurTreasuries = $this->treasuryService->getActiveTreasuries()->where('currency', 'EUR');

        return view('Admin.Accounting.Conversion.index', compact('eurTreasuries'));
    }

    public function store(ExecuteConversionRequest $request): JsonResponse
    {
        $conversion = $this->conversionService->executeConversion($request->validated());

        return response()->json([
            'message' => 'تمت عملية تحويل وتصريف العملة بنجاح، وتحديث الخزائن المعنية.',
            'conversion_id' => $conversion->id,
            'diff_type' => $conversion->diff_type,
            'diff_eur' => $conversion->diff_eur,
        ]);
    }

    public function show(Conversion $conversion): View
    {
        $conversion->load(['toTreasury', 'revenues.contractTracker', 'expenses']);

        return view('Admin.Accounting.Conversion.show', compact('conversion'));
    }
}
