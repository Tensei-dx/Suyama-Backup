<?php

namespace App\Http\Requests\NatureRemoDevice;

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
            'ROOM_ID' => 'required|integer|exists:App\Models\Room,ROOM_ID',
            'DEVICE_NAME' => 'required|string|max:50'
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
            'ROOM_ID' => __('validation.attributes.room'),
            'DEVICE_NAME' => __('validation.attributes.device_name')
        ];
    }
}
