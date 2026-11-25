<?php

declare(strict_types=1);

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Http\Requests\Site\Contact\StoreContactMessageRequest;
use App\Services\Contact\ContactService;
use App\Services\Contact\DTO\ContactMessageDTO;
use Illuminate\Http\JsonResponse;

class ContactController extends Controller
{
    public function __construct(protected ContactService $contactService) {}

    public function store(StoreContactMessageRequest $request): JsonResponse
    {
        $contactMessageDto = ContactMessageDTO::fromRequest($request);
        $this->contactService->sendMessage($contactMessageDto);

        return response()->json([
            'status' => true,
            'message' => 'تم إرسال استفسارك بنجاح! سيقوم فريق مستشاري إيطاليا بالتواصل معك هاتفياً أو عبر واتساب بأسرع وقت.',
        ]);
    }
}
