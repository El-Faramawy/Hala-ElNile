<?php

declare(strict_types=1);

namespace App\Http\Requests\Admin\ContractTracker;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateContractTrackerRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, list<mixed>>
     */
    public function rules(): array
    {
        $id = $this->route('contract_tracker')?->id ?? $this->route('contract_tracker');

        return [
            'contract_code' => ['required', 'string', 'max:50', Rule::unique('contract_trackers', 'contract_code')->ignore($id)],
            'passport_number' => ['required', 'string', 'max:50'],
            'applicant_name' => ['required', 'string', 'max:255'],
            'job_title' => ['required', 'string', 'max:255'],
            'city_name' => ['nullable', 'string', 'max:100'],
            'step' => ['required', 'integer', 'between:1,5'],
            'status_text' => ['required', 'string', 'max:500'],
            'issue_date' => ['nullable', 'date'],
            'embassy_appointment' => ['nullable', 'string', 'max:255'],
            'flight_details' => ['nullable', 'string'],
            'notes' => ['nullable', 'string'],
            'is_active' => ['required', 'boolean'],
        ];
    }
}
