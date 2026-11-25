<?php

declare(strict_types=1);

namespace App\Http\Requests\Site\Contact;

use Illuminate\Foundation\Http\FormRequest;

class StoreContactMessageRequest extends FormRequest
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
            'city_id' => ['nullable', 'integer', 'exists:cities,id'],
            'subject' => ['nullable', 'string', 'max:255'],
            'message' => ['required', 'string', 'min:5', 'max:3000'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'name.required' => 'يرجى إدخال الاسم بالكامل.',
            'name.min' => 'يجب ألا يقل الاسم عن 3 أحرف.',
            'name.max' => 'الاسم طويل جداً (الحد الأقصى 150 حرفاً).',
            'phone.required' => 'يرجى إدخال رقم الهاتف للتواصل.',
            'phone.max' => 'رقم الهاتف يجب ألا يتجاوز 25 رقماً.',
            'city_id.integer' => 'المحافظة المختارة غير صالحة.',
            'city_id.exists' => 'المحافظة المختارة غير موجودة بقاعدة البيانات.',
            'subject.max' => 'موضوع الرسالة يجب ألا يتجاوز 255 حرفاً.',
            'message.required' => 'يرجى كتابة نص الرسالة أو الاستفسار.',
            'message.min' => 'يجب ألا يقل نص الرسالة عن 5 أحرف.',
            'message.max' => 'نص الرسالة طويل جداً (الحد الأقصى 3000 حرف).',
        ];
    }

    /**
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'name' => 'الاسم',
            'phone' => 'رقم الهاتف',
            'city_id' => 'المحافظة',
            'subject' => 'الموضوع',
            'message' => 'الرسالة',
        ];
    }
}
