<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ApprovePefRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->hasRole(['adviser', 'admission', 'registrar', 'cashier', 'admin']);
    }

    public function rules(): array
    {
        return [
            'remarks' => ['nullable', 'string', 'max:1000'],
        ];
    }
}
