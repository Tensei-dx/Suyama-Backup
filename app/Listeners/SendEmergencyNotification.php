<?php

namespace App\Listeners;

use App\Events\NewDeviceDataEvent;
use App\Events\RoomEmergency;
use App\Models\Room;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendEmergencyNotification
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  NewDeviceDataEvent  $event
     * @return void
     */
    public function handle(NewDeviceDataEvent $event)
    {
        $device = $event->data;

        if ($device->DEVICE_TYPE === 'emergency_button' && $device->DATA['status']) {

            $room = Room::findOrFail($device->ROOM_ID);
            $room->EMERGENCY_FLAG = true;
            $room->save();

            broadcast(new RoomEmergency($room));
        }
    }
}
