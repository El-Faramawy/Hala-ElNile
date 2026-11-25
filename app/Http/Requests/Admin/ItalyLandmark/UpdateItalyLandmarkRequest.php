<?php

declare(strict_types=1);

namespace App\Http\Requests\Admin\ItalyLandmark;

use Illuminate\Foundation\Http\FormRequest;

class UpdateItalyLandmarkRequest extends FormRequest
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
            'italy_city_id' => ['required', 'integer', 'exists:italy_cities,id'],
            'name' => ['required', 'string', 'max:255'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:4096'],
            'description' => ['nullable', 'string', 'max:500'],
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
            'italy_city_id' => 'المدينة التابع لها بإيطاليا',
            'name' => 'اسم المنطقة أو المعلم',
            'image' => 'صورة المعلم',
            'description' => 'الوصف أو النبذة',
            'sort_order' => 'الترتيب',
            'is_active' => 'الحالة',
        ];
    }
}
