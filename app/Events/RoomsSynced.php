<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

/**
 * <Class Name> RoomsSynced
 *
 * Create : 2021.08.09 TP Uddin
 * Update : 2021.12.01 TP Uddin Refactor class to RoomsSynced
 *
 * <Overview> This event will be emitted after the room data syncing is done.
 * @package
 * @author TP Uddin <u-almujeer@tenseiph.com>
 * @version 1.0
 * @copyright
 */
class RoomsSynced
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct()
    {
        // 
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new Channel('staysee');
    }

    /**
     * The event's broadcast name.
     *
     * @return string
     */
    public function broadcastAs()
    {
        return 'rooms.synced';
    }
}
