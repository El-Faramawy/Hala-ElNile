<?php

declare(strict_types=1);

namespace App\Http\Requests\Admin\Setting;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSettingRequest extends FormRequest
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
            'name' => ['required', 'string', 'max:255'],
            'logo' => ['nullable', 'image', 'mimes:jpeg,png,jpg,svg,webp', 'max:2048'],
            'fav_icon' => ['nullable', 'image', 'mimes:jpeg,png,jpg,svg,ico,webp', 'max:1024'],
            'phone' => ['nullable', 'string', 'max:50'],
            'whatsapp' => ['nullable', 'string', 'max:50'],
            'email' => ['nullable', 'email', 'max:255'],
            'address' => ['nullable', 'string'],
            'license_number' => ['nullable', 'string', 'max:100'],
            'commercial_record' => ['nullable', 'string', 'max:100'],
            'tax_card' => ['nullable', 'string', 'max:100'],
            'working_hours' => ['nullable', 'string', 'max:255'],
            'facebook' => ['nullable', 'string', 'max:255'],
            'twitter' => ['nullable', 'string', 'max:255'],
            'telegram' => ['nullable', 'string', 'max:255'],
            'youtube' => ['nullable', 'string', 'max:255'],
            'about_us' => ['nullable', 'string'],
            'terms_conditions' => ['nullable', 'string'],
            'privacy_policy' => ['nullable', 'string'],
            'stats_travelers' => ['nullable', 'string', 'max:50'],
            'stats_contracts' => ['nullable', 'string', 'max:50'],
            'stats_companies' => ['nullable', 'string', 'max:50'],
            'stats_experience' => ['nullable', 'string', 'max:50'],
        ];
    }
}
