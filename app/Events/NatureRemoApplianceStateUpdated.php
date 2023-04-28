<?php

namespace App\Events;

use App\Models\NatureRemoAppliance;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NatureRemoApplianceStateUpdated implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * The NatureRemoAppliance instance.
     * 
     * @var \App\NatureRemoAppliance
     */
    public $appliance;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(NatureRemoAppliance $appliance)
    {
        $this->appliance = $appliance;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new Channel('nature_remo_appliances');
    }
}
