<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CheckoutRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'customer_name' => ['required', 'string', 'max:255'],
            'customer_whatsapp' => ['required', 'string', 'regex:/^\+?[0-9]{9,15}$/'],
            'customer_email' => ['required', 'email', 'max:255'],
            'items' => ['required', 'array'],
            'items.*' => ['integer', 'min:0'],
        ];
    }

    public function messages(): array
    {
        return [
            'customer_whatsapp.regex' => 'Enter a valid WhatsApp phone number.',
        ];
    }
}
