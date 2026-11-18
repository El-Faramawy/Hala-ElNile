<?php

declare(strict_types=1);

namespace App\Http\Requests\Admin\JobApplication;

use Illuminate\Foundation\Http\FormRequest;

class UpdateJobApplicationRequest extends FormRequest
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
            'status' => ['required', 'string', 'in:pending,contacted,accepted,rejected'],
            'admin_notes' => ['nullable', 'string'],
        ];
    }
}
