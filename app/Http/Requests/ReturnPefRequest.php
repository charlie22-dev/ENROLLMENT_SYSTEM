<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ReturnPefRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->hasRole(['adviser', 'admission', 'registrar', 'cashier', 'admin']);
    }

    public function rules(): array
    {
        return [
            'remarks' => ['required', 'string', 'min:10', 'max:1000'],
        ];
    }

    public function messages(): array
    {
        return [
            'remarks.required' => 'You must provide remarks when returning or rejecting a PEF.',
            'remarks.min'      => 'Remarks must be at least 10 characters.',
        ];
    }
}
