<?php

namespace App\Rules;

use Cron\CronExpression as Expression;
use Illuminate\Contracts\Validation\Rule;

class CronExpression implements Rule
{
    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        return Expression::isValidExpression($value);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return __('validation.attributes.cron_expression');
    }
}
