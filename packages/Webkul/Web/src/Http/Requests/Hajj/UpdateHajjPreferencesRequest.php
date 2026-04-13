<?php

namespace Webkul\Web\Http\Requests\Hajj;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;

class UpdateHajjPreferencesRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->guard('hajj')->check();
    }

    public function rules(): array
    {
        $allowedLocales = collect(core()->storeLocales())
            ->pluck('value')
            ->map(fn (string $c): string => strtolower($c))
            ->all();

        return [
            'locale' => ['required', 'string', 'max:20', Rule::in($allowedLocales)],
        ];
    }

    protected function failedValidation(Validator $validator): void
    {
        throw new HttpResponseException(
            redirect()
                ->route('hajj.account.index', ['tab' => 'preferences'])
                ->withErrors($validator)
                ->withInput()
        );
    }
}
