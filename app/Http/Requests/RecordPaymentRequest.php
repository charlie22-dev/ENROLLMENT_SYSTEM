<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RecordPaymentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->isCashier() || $this->user()->isAdmin();
    }

    public function rules(): array
    {
        return [
            'amount'    => ['required', 'numeric', 'min:0.01'],
            'or_number' => ['required', 'string', 'max:50'],
        ];
    }
}
