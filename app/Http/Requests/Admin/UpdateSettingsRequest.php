<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSettingsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'platform_name' => ['required', 'string', 'max:255'],
            'support_email' => ['required', 'email', 'max:255'],
            'support_phone' => ['required', 'string', 'max:30'],
            'platform_fee_percent' => ['required', 'integer', 'min:0', 'max:100'],
            'currency' => ['required', 'string', 'max:3'],
            'payment_driver' => ['required', 'in:manual,mpesa'],
        ];
    }
}
