<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePefRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->isStudent();
    }

    public function rules(): array
    {
        return [
            'section_ids'   => ['required', 'array', 'min:1'],
            'section_ids.*' => ['required', 'exists:sections,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'section_ids.required' => 'Please select at least one section.',
            'section_ids.min'      => 'Please select at least one section.',
        ];
    }
}
