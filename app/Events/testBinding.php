<?php
/*
* <System Name> iBMS
* <Program Name> testBinding.php
*
* <Create> 2018.07.27 TP 
* <Update> 
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
 * <Class Name> testBinding
 *
 * <Overview> Class that manipulates the broadcast events
 *            
 */
class testBinding implements ShouldBroadcast
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
        return ['test-binding'];
    }
}
