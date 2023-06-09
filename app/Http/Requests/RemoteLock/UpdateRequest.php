<?php

namespace App\Http\Requests\RemoteLock;

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
            'room_id' => 'required|integer|exists:App\Models\Room,ROOM_ID',
            'device_name' => 'required|string|max:50'
        ];
    }
}
