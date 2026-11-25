<?php

declare(strict_types=1);

namespace App\Services\Contact\DTO;

use Illuminate\Http\Request;

readonly class ContactMessageDTO
{
    public function __construct(
        public string $name,
        public string $phone,
        public string $message,
        public ?int $cityId = null,
        public ?string $subject = null,
    ) {}

    public static function fromRequest(Request $request): self
    {
        return new self(
            name: (string) $request->input('name'),
            phone: (string) $request->input('phone'),
            message: (string) $request->input('message'),
            cityId: $request->filled('city_id') ? (int) $request->input('city_id') : null,
            subject: $request->filled('subject') ? (string) $request->input('subject') : null,
        );
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'phone' => $this->phone,
            'message' => $this->message,
            'city_id' => $this->cityId,
            'subject' => $this->subject,
        ];
    }
}
