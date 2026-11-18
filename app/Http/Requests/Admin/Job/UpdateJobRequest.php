<?php

declare(strict_types=1);

namespace App\Http\Requests\Admin\Job;

use Illuminate\Foundation\Http\FormRequest;

class UpdateJobRequest extends FormRequest
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
            'job_category_id' => ['required', 'exists:job_categories,id'],
            'title' => ['required', 'string', 'max:255'],
            'city' => ['nullable', 'string', 'max:255'],
            'salary_min' => ['nullable', 'numeric'],
            'salary_max' => ['nullable', 'numeric'],
            'salary_text' => ['nullable', 'string', 'max:100'],
            'salary_period' => ['nullable', 'string', 'max:100'],
            'contract_type' => ['nullable', 'string', 'max:100'],
            'badge' => ['nullable', 'string', 'max:100'],
            'image' => ['nullable', 'image', 'max:2048'],
            'housing' => ['nullable', 'string', 'max:255'],
            'meals' => ['nullable', 'string', 'max:255'],
            'insurance' => ['nullable', 'string', 'max:255'],
            'working_hours' => ['nullable', 'string', 'max:255'],
            'experience' => ['nullable', 'string', 'max:255'],
            'available_seats' => ['nullable', 'integer', 'min:1'],
            'description' => ['nullable', 'string'],
            'requirements' => ['nullable'],
            'sort_order' => ['nullable', 'integer'],
            'is_featured' => ['nullable', 'boolean'],
            'is_active' => ['required', 'boolean'],
        ];
    }
}
