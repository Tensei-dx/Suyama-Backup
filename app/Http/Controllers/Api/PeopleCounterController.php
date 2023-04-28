<?php

namespace App\Http\Controllers\Api;

use App\Events\NewDeviceDataEvent;
use App\Http\Controllers\Controller;
use App\Http\Requests\PeopleCounter\StoreRequest;
use App\Models\Device;
use App\Models\Manufacturer;

class PeopleCounterController extends Controller
{
    /**
     * <Layer number> (1.0)
     * 
     * <Processing name> store
     * <Function> Receives the people counter data and store it in the database.
     * 
     * @param  \App\Http\Requests\PeopleCounter\StoreRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreRequest $request)
    {
        $cameras = $request->input('attributes.cameras');

        $manufacturer = Manufacturer::firstOrCreate(['MANUFACTURER_NAME' => 'Axis']);

        foreach ($cameras as $camera) {

            $device = Device::firstOrCreate(
                [
                    'MANUFACTURER_ID' => $manufacturer->MANUFACTURER_ID,
                    'DEVICE_TYPE' => 'camera',
                    'DEVICE_SERIAL_NO' => $camera['device']['mac_address']
                ],
                [
                    'FLOOR_ID' => null,
                    'ROOM_ID' => null,
                    'GATEWAY_ID' => null,
                    'DEVICE_CATEGORY' => 1,
                    'DATA' => null,
                    'DEVICE_NAME' => null,
                    'DEVICE_MAP_NAME' => null,
                    'EMERGENCY_DEVICE' => null,
                    'REG_FLAG' => false,
                    'ONLINE_FLAG' => true
                ]
            );

            $device->load('latestYesterdayData');

            $data = [
                'peopleIn' => $camera['people_counter']['in'],
                'peopleOut' => $camera['people_counter']['out'],
                'yesterdayIn' => $device->latestYesterdayData->DATA['peopleIn'] ?? 0,
                'yesterdayOut' => $device->latestYesterdayData->DATA['peopleOut'] ?? 0
            ];

            $device->update([
                'DATA' => $data,
                'ONLINE_FLAG' => true,
            ]);

            $processedData = $device->processedData()->create([
                'DATA' => $data,
                'SEND_FLAG' => false
            ]);

            broadcast(new NewDeviceDataEvent($processedData));
        }

        return response()->noContent();
    }

    /**
     * <Layer number> (2.0)
     * 
     * <Processing name> show
     * <Function> Show the Device model with the computed 'people_count' attribute.
     * 
     * @param  \App\Device  $device
     * @return \Illuminate\Http\Response
     */
    public function show(Device $device)
    {
        $device->append('people_count');

        return $device;
    }
}
