<?php

declare(strict_types=1);

namespace App\Http\Requests\Admin\Common;

use Illuminate\Foundation\Http\FormRequest;

class MultiDeleteRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Normalize inputs so both 'id' and 'ids' are accepted as array or comma-separated string.
     */
    protected function prepareForValidation(): void
    {
        $raw = $this->input('ids') ?? $this->input('id');

        $ids = [];
        if (is_string($raw)) {
            $parts = explode(',', $raw);
            foreach ($parts as $part) {
                $trimmed = trim($part);
                if ($trimmed !== '' && is_numeric($trimmed)) {
                    $ids[] = (int) $trimmed;
                }
            }
        } elseif (is_array($raw)) {
            foreach ($raw as $val) {
                if (is_numeric($val)) {
                    $ids[] = (int) $val;
                }
            }
        }

        $this->merge([
            'id' => $ids,
            'ids' => $ids,
        ]);
    }

    /**
     * @return array<string, list<string>>
     */
    public function rules(): array
    {
        return [
            'id' => ['required', 'array', 'min:1'],
            'id.*' => ['required', 'integer'],
            'ids' => ['required', 'array', 'min:1'],
            'ids.*' => ['required', 'integer'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'id.required' => 'يجب تحديد عنصر واحد على الأقل للحذف.',
            'id.min' => 'يجب تحديد عنصر واحد على الأقل للحذف.',
            'ids.required' => 'يجب تحديد عنصر واحد على الأقل للحذف.',
            'ids.min' => 'يجب تحديد عنصر واحد على الأقل للحذف.',
        ];
    }
}
