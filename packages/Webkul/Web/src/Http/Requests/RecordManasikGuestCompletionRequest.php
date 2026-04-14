<?php

namespace Webkul\Web\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class RecordManasikGuestCompletionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'step_count'  => ['required', 'integer', 'min:1', 'max:200'],
            'completed'   => ['required', 'array', 'min:1'],
            'completed.*' => ['boolean'],
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $v): void {
            $n = (int) $this->input('step_count');
            $completed = $this->input('completed');
            if (! is_array($completed) || count($completed) !== $n) {
                $v->errors()->add('completed', trans('web::app.manasik.errors.completed_length', ['count' => $n]));
            }
        });
    }
}
