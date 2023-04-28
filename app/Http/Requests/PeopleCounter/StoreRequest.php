<?php

namespace App\Http\Requests\PeopleCounter;

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
            'attributes.system.id' => 'required|string',
            'attributes.system.timezone' => 'required|timezone',
            'attributes.system.name' => 'required|string',
            'attributes.system.server_version' => 'required|string',
            'attributes.cameras.*.device.ip_address' => 'required|ip',
            'attributes.cameras.*.device.mac_address' => 'required|string',
            'attributes.cameras.*.people_counter.in' => 'required|integer',
            'attributes.cameras.*.people_counter.out' => 'required|integer',
            'attributes.cameras.*.people_counter.timestamp' => 'required',
            'attributes.cameras.*.people_counter.name' => 'string',
            'attributes.cameras.*.people_counter.serial' => 'required|string'
        ];
    }
}
