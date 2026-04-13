<?php

namespace Webkul\Web\Http\Requests\Hajj;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class UpdateHajjPasswordRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->guard('hajj')->check();
    }

    public function rules(): array
    {
        return [
            'current_password' => ['required', 'current_password:hajj'],
            'password' => ['required', 'confirmed', Password::defaults()],
        ];
    }
}
