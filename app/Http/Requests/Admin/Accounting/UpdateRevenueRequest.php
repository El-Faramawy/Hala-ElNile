<?php

declare(strict_types=1);

namespace App\Http\Requests\Admin\Accounting;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRevenueRequest extends FormRequest
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
            'treasury_id' => ['required', 'exists:treasuries,id'],
            'contract_tracker_id' => ['nullable', 'exists:contract_trackers,id'],
            'supplier_id' => ['nullable', 'exists:suppliers,id'],
            'received_currency' => ['required', 'in:EGP,EUR'],
            'amount' => ['required', 'numeric', 'min:0.01'],
            'eur_equivalent' => ['nullable', 'numeric', 'min:0'],
            'transaction_date' => ['required', 'date'],
            'notes' => ['nullable', 'string'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:5120'],
        ];
    }
}
