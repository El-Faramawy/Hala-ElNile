<?php

declare(strict_types=1);

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Http\Requests\Site\JobApplication\StoreJobApplicationRequest;
use App\Services\JobApplication\JobApplicationService;
use Illuminate\Http\JsonResponse;

class JobApplicationController extends Controller
{
    public function __construct(protected JobApplicationService $jobApplicationService) {}

    public function store(StoreJobApplicationRequest $request): JsonResponse
    {
        $jobApplication = $this->jobApplicationService->store($request->validated());

        return response()->json([
            'status' => true,
            'message' => 'تم استلام طلب التقديم بنجاح! كود المعاملة الخاص بك هو: '.$jobApplication->code.'. سيتواصل معك أحد خبرائنا لشرح كافة التفاصيل والإجراءات.',
            'code' => $jobApplication->code,
        ]);
    }
}
