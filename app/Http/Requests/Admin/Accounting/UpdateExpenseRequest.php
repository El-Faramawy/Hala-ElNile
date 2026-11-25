<?php

declare(strict_types=1);

namespace App\Http\Requests\Admin\Accounting;

use App\Models\ExpenseType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class UpdateExpenseRequest extends FormRequest
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
            'expense_type_id' => ['required', 'exists:expense_types,id'],
            'treasury_id' => ['required', 'exists:treasuries,id'],
            'contract_tracker_id' => ['nullable', 'exists:contract_trackers,id'],
            'supplier_id' => ['nullable', 'exists:suppliers,id'],
            'admin_id' => ['nullable', 'exists:admins,id'],
            'currency' => ['required', 'in:EGP,EUR'],
            'amount' => ['required', 'numeric', 'min:0.01'],
            'other_details' => ['nullable', 'string', 'max:1000'],
            'transaction_date' => ['required', 'date'],
            'notes' => ['nullable', 'string'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:5120'],
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->sometimes('other_details', ['required', 'string', 'min:3'], function ($input): bool {
            $type = ExpenseType::find((int) ($input->expense_type_id ?? 0));

            return (bool) ($type?->is_other ?? false);
        });
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'other_details.required' => 'عند اختيار "أخرى" كبند مصروف، يجب توضيح تفاصيل ما تم صرفه بالكامل.',
        ];
    }
}
