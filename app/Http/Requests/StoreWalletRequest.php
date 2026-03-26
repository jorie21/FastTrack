<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreWalletRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'type' => 'required|in:digital,bank,cash,credit,others',
            'balance' => 'required|numeric',
            'icon' => 'nullable|string|max:255',
            'color' => 'nullable|string|max:7',
        ];
    }
}
