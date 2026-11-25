<?php

declare(strict_types=1);

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\Job;
use App\Services\Job\JobService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class JobController extends Controller
{
    public function __construct(protected JobService $jobService) {}

    public function filter(Request $request): JsonResponse
    {
        $categoryId = $request->filled('category_id') ? (int) $request->input('category_id') : null;
        $search = $request->filled('search') ? (string) $request->input('search') : null;

        $jobs = $this->jobService->getActiveFiltered($categoryId, $search);

        $formattedJobs = $jobs->map(fn (Job $job): array => [
            'id' => $job->id,
            'title' => $job->title,
            'category_id' => $job->job_category_id,
            'category_name' => $job->category?->name ?? 'عام',
            'city' => $job->city ?? 'إيطاليا',
            'salary_text' => $job->salary_text ?? 'راتب مجزي',
            'salary_period' => $job->salary_period ?? 'صافي شهرياً',
            'contract_type' => $job->contract_type ?? 'عقد دائم',
            'badge' => $job->badge ?? 'متاح للتقديم',
            'image' => $job->image ? asset('storage/'.$job->image) : asset('Site/assets/images/hero/slide-jobs-italy.jpg'),
            'housing' => $job->housing ?? 'سكن متوفر',
            'meals' => $job->meals ?? 'وجبات متوفرة',
            'insurance' => $job->insurance ?? 'تأمين طبي شامل',
            'working_hours' => $job->working_hours ?? '8 ساعات يومياً',
            'experience' => $job->experience ?? 'خبرة أو بدون خبرة',
            'available_seats' => $job->available_seats,
            'description' => $job->description,
            'requirements' => is_array($job->requirements) ? $job->requirements : [],
        ]);

        return response()->json([
            'status' => true,
            'count' => $formattedJobs->count(),
            'data' => $formattedJobs,
        ]);
    }

    public function show(int $id): JsonResponse
    {
        $job = $this->jobService->findById($id);

        if (! $job || ! $job->is_active) {
            return response()->json([
                'status' => false,
                'message' => 'عفواً، فرصة العمل المطلوبة غير متوفرة حالياً.',
            ], 404);
        }

        return response()->json([
            'status' => true,
            'data' => [
                'id' => $job->id,
                'title' => $job->title,
                'category_name' => $job->category?->name ?? 'عام',
                'city' => $job->city,
                'salary_text' => $job->salary_text,
                'salary_period' => $job->salary_period,
                'contract_type' => $job->contract_type,
                'badge' => $job->badge,
                'image' => $job->image ? asset('storage/'.$job->image) : asset('Site/assets/images/hero/slide-jobs-italy.jpg'),
                'housing' => $job->housing,
                'meals' => $job->meals,
                'insurance' => $job->insurance,
                'working_hours' => $job->working_hours,
                'experience' => $job->experience,
                'available_seats' => $job->available_seats,
                'description' => $job->description,
                'requirements' => is_array($job->requirements) ? $job->requirements : [],
            ],
        ]);
    }
}
