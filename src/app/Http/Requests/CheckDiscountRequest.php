<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CheckDiscountRequest extends FormRequest
{
    public function rules(): array
    {
        return ['code' => ['required', 'string', 'max:64']];
    }

    public function messages(): array
    {
        return ['code.required' => 'Введите код скидки'];
    }
}
