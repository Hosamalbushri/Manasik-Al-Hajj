<?php

namespace Webkul\Web\Http\Requests\Hajj;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;

class ToggleHajjDuaFavoriteRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->guard('hajj')->check();
    }

    public function rules(): array
    {
        return [
            'dua_id' => ['required', 'integer', Rule::exists('manasik_duas', 'id')],
        ];
    }

    protected function failedValidation(Validator $validator): void
    {
        if ($this->expectsJson() || $this->ajax()) {
            throw new HttpResponseException(response()->json(['errors' => $validator->errors()], 422));
        }

        parent::failedValidation($validator);
    }
}
