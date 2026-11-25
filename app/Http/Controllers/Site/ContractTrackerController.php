<?php

declare(strict_types=1);

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Http\Requests\Site\ContractTracker\TrackContractRequest;
use App\Services\ContractTracker\ContractTrackerService;
use Illuminate\Http\JsonResponse;

class ContractTrackerController extends Controller
{
    public function __construct(protected ContractTrackerService $contractTrackerService) {}

    public function track(TrackContractRequest $request): JsonResponse
    {
        $identifier = (string) $request->validated('identifier');
        $tracker = $this->contractTrackerService->findByPassportOrCode($identifier);

        if (! $tracker) {
            return response()->json([
                'status' => false,
                'message' => 'عفواً، لا توجد معاملة نشطة مسجلة برقم الجواز أو كود العقد المدخل. يرجى مراجعة الرقم أو التواصل مباشرة مع إدارة هلا النيل عبر واتساب.',
            ], 404);
        }

        $stepTitles = [
            1 => 'استلام ومراجعة الملف والأوراق',
            2 => 'التسجيل في بوابة وزارة الداخلية الإيطالية (Decreto Flussi)',
            3 => 'صدور تصريح العمل والموافقة الأمنية (Nulla Osta)',
            4 => 'حجز البصمة البيومترية واستلام التأشيرة من السفارة',
            5 => 'تجهيز تذكرة السفر والاستقبال بمطار إيطاليا',
        ];

        return response()->json([
            'status' => true,
            'data' => [
                'contract_code' => $tracker->contract_code,
                'passport_number' => $tracker->passport_number,
                'applicant_name' => $tracker->applicant_name,
                'job_title' => $tracker->job_title,
                'city_name' => $tracker->city_name ?? 'إيطاليا',
                'current_step' => $tracker->step,
                'current_step_title' => $stepTitles[$tracker->step] ?? ('المرحلة '.$tracker->step),
                'status_text' => $tracker->status_text,
                'issue_date' => $tracker->issue_date?->format('Y-m-d'),
                'embassy_appointment' => $tracker->embassy_appointment,
                'flight_details' => $tracker->flight_details,
                'notes' => $tracker->notes,
            ],
        ]);
    }
}
