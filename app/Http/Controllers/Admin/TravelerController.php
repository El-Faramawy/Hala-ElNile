<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Common\MultiDeleteRequest;
use App\Http\Requests\Admin\Traveler\StoreTravelerRequest;
use App\Http\Requests\Admin\Traveler\UpdateTravelerRequest;
use App\Models\Traveler;
use App\Services\ItalyCity\ItalyCityService;
use App\Services\ItalyLandmark\ItalyLandmarkService;
use App\Services\Traveler\TravelerService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TravelerController extends Controller
{
    public function __construct(
        protected TravelerService $service,
        protected ItalyCityService $italyCityService,
        protected ItalyLandmarkService $italyLandmarkService,
    ) {}

    public function index(Request $request): View|JsonResponse
    {
        if ($request->ajax()) {
            return $this->service->getDataTable();
        }

        return view('Admin.Traveler.index');
    }

    public function create(): View
    {
        $italyCities = $this->italyCityService->getAllActive();
        $italyLandmarks = $this->italyLandmarkService->getAllActive();

        return view('Admin.Traveler.parts.create', compact('italyCities', 'italyLandmarks'));
    }

    public function store(StoreTravelerRequest $request): JsonResponse
    {
        $this->service->store(
            $request->validated(),
            $request->file('landmark_image'),
            $request->file('avatar')
        );

        return response()->json(['message' => 'تم إضافة المسافر بنجاح']);
    }

    public function edit(Traveler $traveler): View
    {
        $italyCities = $this->italyCityService->getAllActive();
        $italyLandmarks = $this->italyLandmarkService->getAllActive($traveler->italy_city_id);

        return view('Admin.Traveler.parts.edit', compact('traveler', 'italyCities', 'italyLandmarks'));
    }

    public function update(UpdateTravelerRequest $request, Traveler $traveler): JsonResponse
    {
        $this->service->update(
            $traveler,
            $request->validated(),
            $request->file('landmark_image'),
            $request->file('avatar')
        );

        return response()->json(['message' => 'تم تحديث بيانات المسافر بنجاح']);
    }

    public function destroy(Traveler $traveler): JsonResponse
    {
        $this->service->delete($traveler);

        return response()->json(['message' => 'تم حذف المسافر بنجاح']);
    }

    public function multiDelete(MultiDeleteRequest $request): JsonResponse
    {
        $count = $this->service->deleteMultiple($request->validated('ids'));

        return response()->json(['message' => "تم حذف {$count} من المسافرين المحددين بنجاح"]);
    }

    public function searchApplicants(Request $request): JsonResponse
    {
        $query = (string) $request->input('q', '');
        $results = $this->service->searchApplicants($query);

        return response()->json($results);
    }
}
