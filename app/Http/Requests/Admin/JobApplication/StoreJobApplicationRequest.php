<?php

declare(strict_types=1);

namespace App\Http\Requests\Admin\JobApplication;

use Illuminate\Foundation\Http\FormRequest;

class StoreJobApplicationRequest extends FormRequest
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
            'name' => ['required', 'string', 'min:3', 'max:150'],
            'phone' => ['required', 'string', 'max:25'],
            'age' => ['nullable', 'integer', 'min:18', 'max:70'],
            'city_id' => ['nullable', 'integer', 'exists:cities,id'],
            'job_id' => ['nullable', 'integer', 'exists:jobs,id'],
            'job_title' => ['nullable', 'string', 'max:255'],
            'passport_situation' => ['nullable', 'string', 'max:255'],
            'passport_number' => ['nullable', 'string', 'max:50'],
            'notes' => ['nullable', 'string', 'max:2000'],
            'status' => ['required', 'string', 'in:new,stage_1,stage_2,stage_3,stage_4,stage_5,pending,contacted,accepted,rejected,cancelled'],
            'cancellation_reason' => ['required_if:status,cancelled,rejected', 'nullable', 'string', 'max:1000'],
            'admin_notes' => ['nullable', 'string', 'max:2000'],
            'code' => ['nullable', 'string', 'max:20'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'name' => 'اسم المتقدم',
            'phone' => 'رقم الهاتف',
            'age' => 'العمر',
            'city_id' => 'المحافظة',
            'job_id' => 'فرصة العمل',
            'job_title' => 'المسمى الوظيفي',
            'passport_situation' => 'موقف جواز السفر',
            'passport_number' => 'رقم جواز السفر',
            'notes' => 'ملاحظات المتقدم',
            'status' => 'حالة الطلب',
            'cancellation_reason' => 'سبب الإلغاء',
            'admin_notes' => 'ملاحظات الإدارة',
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'cancellation_reason.required_if' => 'سبب الإلغاء إجباري عند اختيار حالة الطلب كـ "ملغي".',
        ];
    }
}
