<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Common\MultiDeleteRequest;
use App\Http\Requests\Admin\JobApplication\UpdateJobApplicationRequest;
use App\Models\JobApplication;
use App\Services\JobApplication\JobApplicationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class JobApplicationController extends Controller
{
    public function __construct(protected JobApplicationService $service) {}

    public function index(Request $request): View|JsonResponse
    {
        if ($request->ajax()) {
            return $this->service->getDataTable();
        }

        return view('Admin.JobApplication.index');
    }

    public function edit(JobApplication $jobApplication): View
    {
        $application = $jobApplication->load(['job', 'city']);

        return view('Admin.JobApplication.parts.edit', compact('application'));
    }

    public function update(UpdateJobApplicationRequest $request, JobApplication $jobApplication): JsonResponse
    {
        $this->service->updateStatus(
            $jobApplication,
            (string) $request->validated('status'),
            $request->validated('admin_notes')
        );

        return response()->json(['message' => 'تم تحديث حالة الطلب بنجاح']);
    }

    public function destroy(JobApplication $jobApplication): JsonResponse
    {
        $this->service->delete($jobApplication);

        return response()->json(['message' => 'تم حذف طلب التوظيف بنجاح']);
    }

    public function multiDelete(MultiDeleteRequest $request): JsonResponse
    {
        $ids = (array) $request->input('id');
        $this->service->deleteMultiple($ids);

        return response()->json(['message' => 'تم حذف الطلبات المحددة بنجاح']);
    }
}
