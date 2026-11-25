<?php

declare(strict_types=1);

namespace App\Http\Requests\Admin\ItalyCity;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateItalyCityRequest extends FormRequest
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
        $cityId = $this->route('italy_city')?->id ?? $this->route('italy_city');

        return [
            'name' => ['required', 'string', 'max:255', Rule::unique('italy_cities', 'name')->ignore($cityId)],
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
