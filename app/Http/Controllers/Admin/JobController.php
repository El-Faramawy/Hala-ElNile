<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Common\MultiDeleteRequest;
use App\Http\Requests\Admin\Job\StoreJobRequest;
use App\Http\Requests\Admin\Job\UpdateJobRequest;
use App\Models\Job;
use App\Services\Job\JobService;
use App\Services\JobCategory\JobCategoryService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class JobController extends Controller
{
    public function __construct(
        protected JobService $jobService,
        protected JobCategoryService $jobCategoryService
    ) {}

    public function index(Request $request): View|JsonResponse
    {
        if ($request->ajax()) {
            return $this->jobService->getDataTable();
        }

        return view('Admin.Job.index');
    }

    public function create(): View
    {
        $categories = $this->jobCategoryService->getJobCategoriesForDashboard();

        return view('Admin.Job.parts.create', compact('categories'));
    }

    public function store(StoreJobRequest $request): JsonResponse
    {
        $this->jobService->store($request->validated(), $request->file('image'));

        return response()->json(['message' => 'تم إضافة الوظيفة بنجاح']);
    }

    public function edit(Job $job): View
    {
        $categories = $this->jobCategoryService->getJobCategoriesForDashboard();

        return view('Admin.Job.parts.edit', compact('job', 'categories'));
    }

    public function update(UpdateJobRequest $request, Job $job): JsonResponse
    {
        $this->jobService->update($job, $request->validated(), $request->file('image'));

        return response()->json(['message' => 'تم تحديث الوظيفة بنجاح']);
    }

    public function destroy(Job $job): JsonResponse
    {
        $this->jobService->delete($job);

        return response()->json(['message' => 'تم حذف الوظيفة بنجاح']);
    }

    public function multiDelete(MultiDeleteRequest $request): JsonResponse
    {
        $ids = (array) $request->input('id');
        $this->jobService->deleteMultiple($ids);

        return response()->json(['message' => 'تم حذف الوظائف المحددة بنجاح']);
    }
}
