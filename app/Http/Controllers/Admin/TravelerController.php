<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Common\MultiDeleteRequest;
use App\Http\Requests\Admin\Traveler\StoreTravelerRequest;
use App\Http\Requests\Admin\Traveler\UpdateTravelerRequest;
use App\Models\Traveler;
use App\Services\Traveler\TravelerService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TravelerController extends Controller
{
    public function __construct(protected TravelerService $service) {}

    public function index(Request $request): View|JsonResponse
    {
        if ($request->ajax()) {
            return $this->service->getDataTable();
        }

        return view('Admin.Traveler.index');
    }

    public function create(): View
    {
        return view('Admin.Traveler.parts.create');
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
        return view('Admin.Traveler.parts.edit', compact('traveler'));
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
        $ids = (array) $request->input('id');
        $this->service->deleteMultiple($ids);

        return response()->json(['message' => 'تم حذف المسافرين المحددين بنجاح']);
    }
}
