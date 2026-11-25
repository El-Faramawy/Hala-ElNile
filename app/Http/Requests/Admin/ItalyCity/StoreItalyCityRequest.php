<?php

declare(strict_types=1);

namespace App\Http\Requests\Admin\ItalyCity;

use Illuminate\Foundation\Http\FormRequest;

class StoreItalyCityRequest extends FormRequest
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
            'name' => ['required', 'string', 'max:255', 'unique:italy_cities,name'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:4096'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['required', 'boolean'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'name' => 'اسم المدينة بإيطاليا',
            'image' => 'صورة المدينة',
            'sort_order' => 'الترتيب',
            'is_active' => 'الحالة',
        ];
    }
}
