<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Common\MultiDeleteRequest;
use App\Http\Requests\Admin\JobCategory\StoreJobCategoryRequest;
use App\Http\Requests\Admin\JobCategory\UpdateJobCategoryRequest;
use App\Models\JobCategory;
use App\Services\JobCategory\JobCategoryService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class JobCategoryController extends Controller
{
    public function __construct(protected JobCategoryService $jobCategoryService) {}

    public function index(Request $request): View|JsonResponse
    {
        if ($request->ajax()) {
            return $this->jobCategoryService->getDataTable();
        }

        return view('Admin.JobCategory.index');
    }

    public function create(): View
    {
        return view('Admin.JobCategory.parts.create');
    }

    public function store(StoreJobCategoryRequest $request): JsonResponse
    {
        $iconFile = $request->file('icon_file') ?? $request->file('icon');

        $this->jobCategoryService->store(
            $request->validated(),
            $iconFile
        );

        return response()->json(['message' => __('messages.general.created_success')]);
    }

    public function edit(JobCategory $jobCategory): View
    {
        return view('Admin.JobCategory.parts.edit', compact('jobCategory'));
    }

    public function update(UpdateJobCategoryRequest $request, JobCategory $jobCategory): JsonResponse
    {
        $iconFile = $request->file('icon_file') ?? $request->file('icon');

        $this->jobCategoryService->update(
            $jobCategory,
            $request->validated(),
            $iconFile
        );

        return response()->json(['message' => __('messages.general.updated_success')]);
    }

    public function destroy(JobCategory $jobCategory): JsonResponse
    {
        $this->jobCategoryService->delete($jobCategory);

        return response()->json(['message' => __('messages.general.deleted_success')]);
    }

    public function multiDelete(MultiDeleteRequest $request): JsonResponse
    {
        $this->jobCategoryService->deleteMultiple($request->validated('ids'));

        return response()->json(['message' => __('messages.general.deleted_success')]);
    }
}
