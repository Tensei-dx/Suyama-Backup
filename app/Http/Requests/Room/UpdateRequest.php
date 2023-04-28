<?php

namespace App\Http\Requests\Room;

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
            'FLOOR_ID' => 'sometimes|exists:M003_FLOOR,FLOOR_ID',
            'ROOM_NAME' => 'sometimes|string|min:3|max:255',
            'MAX_OCCUPANCY' => 'sometimes|numeric',
            'STATUS_ID' => 'sometimes|numeric',
            'EMERGENCY_FLAG' => 'sometimes|boolean'
        ];
    }
}
