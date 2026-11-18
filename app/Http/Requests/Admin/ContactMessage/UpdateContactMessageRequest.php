<?php

declare(strict_types=1);

namespace App\Http\Requests\Admin\ContactMessage;

use Illuminate\Foundation\Http\FormRequest;

class UpdateContactMessageRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, list<string>>
     */
    public function rules(): array
    {
        return [
            'is_read' => ['required', 'boolean'],
            'notes' => ['nullable', 'string'],
        ];
    }
}
