<?php

declare(strict_types=1);

namespace App\Http\Requests\Site\JobApplication;

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
            'age' => ['nullable', 'integer', 'min:18', 'max:65'],
            'city_id' => ['nullable', 'integer', 'exists:cities,id'],
            'job_id' => ['nullable', 'integer', 'exists:jobs,id'],
            'job_title' => ['nullable', 'string', 'max:255'],
            'passport_situation' => ['nullable', 'string', 'max:255'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'name.required' => 'يرجى إدخال الاسم بالكامل رباعي كما في بطاقة الرقم القومي أو الجواز.',
            'name.min' => 'يجب ألا يقل الاسم عن 3 أحرف.',
            'name.max' => 'الاسم طويل جداً (الحد الأقصى 150 حرفاً).',
            'phone.required' => 'يرجى إدخال رقم الهاتف/الواتساب للمتابعة.',
            'phone.max' => 'رقم الهاتف يجب ألا يتجاوز 25 رقماً.',
            'age.integer' => 'يرجى إدخال العمر بالأرقام بشكل صحيح.',
            'age.min' => 'يجب أن يكون العمر 18 عاماً على الأقل للتقديم على تأشيرة العمل.',
            'age.max' => 'العمر الأقصى للتقديم هو 65 عاماً وفقاً للوائح العمل الإيطالية.',
            'city_id.integer' => 'المحافظة المختارة غير صالحة.',
            'city_id.exists' => 'المحافظة المختارة غير موجودة.',
            'job_id.integer' => 'معرف فرصة العمل غير صالح.',
            'job_id.exists' => 'فرصة العمل المطلوبة غير متوفرة حالياً.',
            'notes.max' => 'حقل الملاحظات يجب ألا يتجاوز 1000 حرف.',
        ];
    }

    /**
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'name' => 'الاسم رباعي',
            'phone' => 'رقم الهاتف',
            'age' => 'العمر',
            'city_id' => 'المحافظة',
            'job_id' => 'فرصة العمل',
            'job_title' => 'المسمى الوظيفي',
            'passport_situation' => 'موقف جواز السفر',
            'notes' => 'الملاحظات',
        ];
    }
}
