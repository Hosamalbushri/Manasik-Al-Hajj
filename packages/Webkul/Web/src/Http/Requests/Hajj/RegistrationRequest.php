<?php

namespace Webkul\Web\Http\Requests\Hajj;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;
use Webkul\Web\Support\HajjAuthRegisterSettings;

class RegistrationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $gdprAgreement = HajjAuthRegisterSettings::gdprAgreementActive();

        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:191', 'unique:manasik_hajj_users,email'],
            'phone' => ['nullable', 'string', 'max:32'],
            'password' => ['required', 'confirmed', Password::defaults()],
        ];

        if ($gdprAgreement) {
            $rules['agreement'] = ['accepted'];
        } else {
            $rules['terms'] = ['accepted'];
        }

        return $rules;
    }
}
