<?php

namespace App\Http\Requests\NatureRemoAccount;

use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
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
            'ACCOUNT_NAME' => 'required|unique:M027_NATURE_REMO_ACCOUNTS,ACCOUNT_NAME|string',
            'ACCESS_TOKEN' => 'required|unique:M027_NATURE_REMO_ACCOUNTS,ACCESS_TOKEN|string'
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
            'ACCOUNT_NAME' => __('validation.attributes.account_name'),
            'ACCESS_TOKEN' => __('validation.attributes.access_token'),
        ];
    }
}
