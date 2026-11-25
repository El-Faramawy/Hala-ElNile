<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Common\MultiDeleteRequest;
use App\Http\Requests\Admin\ItalyLandmark\StoreItalyLandmarkRequest;
use App\Http\Requests\Admin\ItalyLandmark\UpdateItalyLandmarkRequest;
use App\Models\ItalyCity;
use App\Models\ItalyLandmark;
use App\Services\ItalyCity\ItalyCityService;
use App\Services\ItalyLandmark\ItalyLandmarkService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ItalyLandmarkController extends Controller
{
    public function __construct(
        protected ItalyLandmarkService $service,
        protected ItalyCityService $cityService,
    ) {}

    public function index(Request $request): View|JsonResponse
    {
        if ($request->ajax()) {
            return $this->service->getDataTable();
        }

        return view('Admin.ItalyLandmark.index');
    }

    public function create(): View
    {
        $cities = $this->cityService->getAllActive();

        return view('Admin.ItalyLandmark.parts.create', compact('cities'));
    }

    public function store(StoreItalyLandmarkRequest $request): JsonResponse
    {
        $this->service->store($request->validated());

        return response()->json(['message' => 'تم إضافة المنطقة / المعلم بإيطاليا بنجاح']);
    }

    public function edit(ItalyLandmark $italyLandmark): View
    {
        $cities = $this->cityService->getAllActive();

        return view('Admin.ItalyLandmark.parts.edit', compact('italyLandmark', 'cities'));
    }

    public function update(UpdateItalyLandmarkRequest $request, ItalyLandmark $italyLandmark): JsonResponse
    {
        $this->service->update($italyLandmark, $request->validated());

        return response()->json(['message' => 'تم تحديث بيانات المعلم بنجاح']);
    }

    public function destroy(ItalyLandmark $italyLandmark): JsonResponse
    {
        $this->service->delete($italyLandmark);

        return response()->json(['message' => 'تم حذف المعلم بنجاح']);
    }

    public function multiDelete(MultiDeleteRequest $request): JsonResponse
    {
        $count = $this->service->deleteMultiple($request->validated('ids'));

        return response()->json(['message' => "تم حذف {$count} من المعالم بنجاح"]);
    }

    public function getByCity(ItalyCity $italyCity): JsonResponse
    {
        $landmarks = $this->service->getAllActive($italyCity->id);

        return response()->json($landmarks);
    }
}
