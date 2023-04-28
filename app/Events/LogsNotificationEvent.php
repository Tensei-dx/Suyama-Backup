<?php
/*
* <System Name> iBMS
* <Program Name> LogsNotificationEvent.php
*
* <Create> 2021.12.21 TP Russell
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
 * <Class Name> LogsNotificationEvent
 *
 * <Overview> Class that manipulates the broadcast events
 *
 */
class LogsNotificationEvent implements ShouldBroadcast
{
    /*******************************************************************/
    /* Processing Hierarchy                                            */
    /*******************************************************************/
    // __construct               (1.0) Create a new event instance.
    // broadcastOn               (2.0) Get the channels the event should broadcast on.
    // broadcastAs               (3.0) The event's broadcast name.

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
        // return new Channel('notification-event');
        return ['logsNotification-event'];
    }
}
