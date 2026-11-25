<?php

declare(strict_types=1);

namespace App\Http\Requests\Admin\Traveler;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTravelerRequest extends FormRequest
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
            'governorate' => ['nullable', 'string', 'max:100'],
            'job_title' => ['required', 'string', 'max:255'],
            'location' => ['nullable', 'string', 'max:255'],
            'italy_city_id' => ['nullable', 'integer', 'exists:italy_cities,id'],
            'italy_landmark_id' => ['nullable', 'integer', 'exists:italy_landmarks,id'],
            'badge' => ['nullable', 'string', 'max:100'],
            'landmark_image' => ['nullable', 'image', 'max:2048'],
            'avatar' => ['nullable', 'image', 'max:2048'],
            'story' => ['required', 'string'],
            'contract_code' => ['nullable', 'string', 'max:50'],
            'rating' => ['nullable', 'integer', 'between:1,5'],
            'sort_order' => ['nullable', 'integer'],
            'is_active' => ['required', 'boolean'],
        ];
    }
}
