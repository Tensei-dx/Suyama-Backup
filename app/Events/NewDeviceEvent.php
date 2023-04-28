<?php
/*
* <System Name> iBMS
* <Program Name> NewDeviceEvent.php
*
* <Create> 2018.07.27 TP 
* <Update> 2021.09.29 TP Shannie
*
*/

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

/**
 * <Class Name> NewDeviceEvent
 *
 * <Overview> Class that manipulates the broadcast events
 *            
 */
class NewDeviceEvent implements ShouldBroadcast
{
    /*******************************************************************/
    /* Processing Hierarchy                                            */
    /*******************************************************************/
    // __construct               (1.0) Create a new event instance.
    // broadcastOn               (2.0) Get the channels the event should broadcast on.

    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $data;

    /**
     * <Layer number> (1.0) Create a new event instance.
     * <Processing name> __construct
     * <Function>
     *
     * @param $data
     * @return void
     */
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * <Layer number> (2.0) Get the channels the event should broadcast on.
     * <Processing name> broadcastOn
     * <Function>
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        // return new Channel('new-device');
        return ['new-device'];
    }
}
