<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Common\MultiDeleteRequest;
use App\Http\Requests\Admin\ItalyCity\StoreItalyCityRequest;
use App\Http\Requests\Admin\ItalyCity\UpdateItalyCityRequest;
use App\Models\ItalyCity;
use App\Services\ItalyCity\ItalyCityService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ItalyCityController extends Controller
{
    public function __construct(protected ItalyCityService $service) {}

    public function index(Request $request): View|JsonResponse
    {
        if ($request->ajax()) {
            return $this->service->getDataTable();
        }

        return view('Admin.ItalyCity.index');
    }

    public function create(): View
    {
        return view('Admin.ItalyCity.parts.create');
    }

    public function store(StoreItalyCityRequest $request): JsonResponse
    {
        $this->service->store($request->validated());

        return response()->json(['message' => 'تم إضافة المدينة الإيطالية بنجاح']);
    }

    public function edit(ItalyCity $italyCity): View
    {
        return view('Admin.ItalyCity.parts.edit', compact('italyCity'));
    }

    public function update(UpdateItalyCityRequest $request, ItalyCity $italyCity): JsonResponse
    {
        $this->service->update($italyCity, $request->validated());

        return response()->json(['message' => 'تم تحديث بيانات المدينة بنجاح']);
    }

    public function destroy(ItalyCity $italyCity): JsonResponse
    {
        $this->service->delete($italyCity);

        return response()->json(['message' => 'تم حذف المدينة بنجاح']);
    }

    public function multiDelete(MultiDeleteRequest $request): JsonResponse
    {
        $count = $this->service->deleteMultiple($request->validated('ids'));

        return response()->json(['message' => "تم حذف {$count} من المدن بنجاح"]);
    }
}
