<?php

namespace App\Http\Requests\Task;

use App\Rules\CronExpression;
use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'CRON_SCHEDULE' => ['required', 'string', new CronExpression]
        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array
     */
    public function attributes()
    {
        return [
            'CRON_SCHEDULE' => __('validation.attributes.cron_schedule'),
        ];
    }
}
