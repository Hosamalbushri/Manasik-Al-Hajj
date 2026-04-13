<?php

namespace Webkul\Web\Http\Requests\Hajj;

use Illuminate\Foundation\Http\FormRequest;

class DestroyHajjAccountRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->guard('hajj')->check();
    }

    public function rules(): array
    {
        return [
            'delete_password' => ['required', 'current_password:hajj'],
        ];
    }
}
