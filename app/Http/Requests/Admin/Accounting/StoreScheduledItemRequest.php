<?php

declare(strict_types=1);

namespace App\Http\Requests\Admin\Accounting;

use Illuminate\Foundation\Http\FormRequest;

class StoreScheduledItemRequest extends FormRequest
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
            'title' => ['required', 'string', 'max:255'],
            'type' => ['required', 'in:expense,revenue'],
            'frequency' => ['required', 'in:monthly,yearly,once'],
            'amount' => ['required', 'numeric', 'min:0.01'],
            'currency' => ['required', 'in:EGP,EUR'],
            'next_due_date' => ['required', 'date'],
            'treasury_id' => ['nullable', 'exists:treasuries,id'],
            'expense_type_id' => ['nullable', 'exists:expense_types,id'],
            'supplier_id' => ['nullable', 'exists:suppliers,id'],
            'notes' => ['nullable', 'string'],
            'is_active' => ['required', 'boolean'],
        ];
    }
}
