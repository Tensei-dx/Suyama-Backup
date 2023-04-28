<?php

/**
 * <System Name> iBMS
 * <File Name> ReservationSyncedEvent.php
 * <Create> 2021.08.09 TP Uddin
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
 * <Class Name> ReservationSyncedEvent
 *
 * Create : 2021.08.09 TP Uddin
 * Update :
 *
 * <Overview> This event will be emitted after syncing the reservation data.
 * @package
 * @author TP Uddin <u-almujeer@tenseiph.com>
 * @version 1.0
 * @copyright
 */
class ReservationSyncedEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct()
    {
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        // return new Channel('reservation-synced');
        return ['reservation-synced'];
    }
}
