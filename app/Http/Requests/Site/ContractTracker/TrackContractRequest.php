<?php

declare(strict_types=1);

namespace App\Http\Requests\Site\ContractTracker;

use Illuminate\Foundation\Http\FormRequest;

class TrackContractRequest extends FormRequest
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
            'identifier' => ['required', 'string', 'min:3', 'max:100'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'identifier.required' => 'يرجى إدخال رقم جواز السفر أو كود الطلب للاستعلام.',
            'identifier.min' => 'رقم جواز السفر أو كود الطلب يجب ألا يقل عن 3 أحرف/أرقام.',
            'identifier.max' => 'رقم جواز السفر أو كود الطلب غير صحيح (طويل جداً).',
        ];
    }

    /**
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'identifier' => 'رقم جواز السفر أو كود العقد',
        ];
    }
}
