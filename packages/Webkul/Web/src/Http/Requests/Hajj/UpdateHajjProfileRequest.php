<?php

namespace Webkul\Web\Http\Requests\Hajj;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateHajjProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->guard('hajj')->check();
    }

    public function rules(): array
    {
        $id = auth()->guard('hajj')->id();

        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:191', Rule::unique('manasik_hajj_users', 'email')->ignore($id)],
            'phone' => ['nullable', 'string', 'max:32'],
            'birth_date' => ['nullable', 'date'],
            'address' => ['nullable', 'string', 'max:1000'],
        ];
    }
}
