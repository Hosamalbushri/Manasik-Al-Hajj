<?php

namespace Webkul\Web\Http\Requests\Hajj;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class SaveManasikProgressRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->guard('hajj')->check();
    }

    public function rules(): array
    {
        return [
            'step_count' => ['required', 'integer', 'min:1', 'max:200'],
            'active_index' => ['required', 'integer', 'min:0'],
            'completed' => ['required', 'array', 'min:1'],
            'completed.*' => ['boolean'],
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $v): void {
            $n = (int) $this->input('step_count');
            $completed = $this->input('completed');
            $ai = (int) $this->input('active_index');
            if (! is_array($completed) || count($completed) !== $n) {
                $v->errors()->add('completed', trans('web::app.manasik.errors.completed_length', ['count' => $n]));
            }
            if ($n > 0 && ($ai < 0 || $ai >= $n)) {
                $v->errors()->add('active_index', trans('web::app.manasik.errors.active_index_invalid'));
            }
        });
    }
}
