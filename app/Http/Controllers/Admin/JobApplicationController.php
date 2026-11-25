<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Common\MultiDeleteRequest;
use App\Http\Requests\Admin\JobApplication\ConvertToContractRequest;
use App\Http\Requests\Admin\JobApplication\StoreJobApplicationRequest;
use App\Http\Requests\Admin\JobApplication\UpdateJobApplicationRequest;
use App\Models\JobApplication;
use App\Services\City\CityService;
use App\Services\Job\JobService;
use App\Services\JobApplication\JobApplicationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class JobApplicationController extends Controller
{
    public function __construct(
        protected JobApplicationService $service,
        protected JobService $jobService,
        protected CityService $cityService
    ) {}

    public function index(Request $request): View|JsonResponse
    {
        if ($request->ajax()) {
            $filters = [
                'status' => $request->get('status'),
                'job_id' => $request->get('job_id'),
            ];

            return $this->service->getDataTable($filters);
        }

        $jobs = $this->jobService->getAllActive();

        return view('Admin.JobApplication.index', compact('jobs'));
    }

    public function create(): View
    {
        $cities = $this->cityService->getCities();
        $jobs = $this->jobService->getAllActive();

        return view('Admin.JobApplication.parts.create', compact('cities', 'jobs'));
    }

    public function store(StoreJobApplicationRequest $request): JsonResponse
    {
        $this->service->store($request->validated());

        return response()->json(['message' => 'تم إضافة طلب التوظيف بنجاح']);
    }

    public function edit(JobApplication $jobApplication): View
    {
        $application = $jobApplication->load(['job', 'city', 'contractTracker']);
        $cities = $this->cityService->getCities();
        $jobs = $this->jobService->getAllActive();

        return view('Admin.JobApplication.parts.edit', compact('application', 'cities', 'jobs'));
    }

    public function update(UpdateJobApplicationRequest $request, JobApplication $jobApplication): JsonResponse
    {
        $this->service->update($jobApplication, $request->validated());

        return response()->json(['message' => 'تم تحديث بيانات ومرحلة الطلب بنجاح']);
    }

    public function convertToContract(ConvertToContractRequest $request, JobApplication $jobApplication): JsonResponse
    {
        $this->service->convertToContract($jobApplication, $request->validated());

        return response()->json(['message' => 'تم تحويل الطلب إلى عقد عمل موثق بنجاح']);
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
