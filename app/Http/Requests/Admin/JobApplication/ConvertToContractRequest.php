<?php

declare(strict_types=1);

namespace App\Http\Requests\Admin\JobApplication;

use Illuminate\Foundation\Http\FormRequest;

class ConvertToContractRequest extends FormRequest
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
            'passport_number' => ['nullable', 'string', 'max:50'],
            'contract_code' => ['nullable', 'string', 'max:50'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'passport_number' => 'رقم جواز السفر',
            'contract_code' => 'كود العقد',
        ];
    }
}
