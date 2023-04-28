<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->user()->USER_TYPE === 1;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'username' => [
                'required',
                'alpha_dash',
                Rule::unique('M001_USERS', 'USERNAME')->ignore($this->user)
            ],
            'name' => 'required|string',
            'email' => 'required|email:rfc,dns',
            'phone_number' => 'required|string',
            'update_pin_flag' => 'required|boolean'
        ];
    }
}
