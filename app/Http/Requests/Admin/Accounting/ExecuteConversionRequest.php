<?php

declare(strict_types=1);

namespace App\Http\Requests\Admin\Accounting;

use Illuminate\Foundation\Http\FormRequest;

class ExecuteConversionRequest extends FormRequest
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
            'revenue_ids' => ['required', 'array', 'min:1'],
            'revenue_ids.*' => ['required', 'integer', 'exists:revenues,id'],
            'actual_eur' => ['required', 'numeric', 'min:0'],
            'to_treasury_id' => ['required', 'integer', 'exists:treasuries,id'],
            'conversion_date' => ['nullable', 'date'],
            'notes' => ['nullable', 'string'],
        ];
    }
}
