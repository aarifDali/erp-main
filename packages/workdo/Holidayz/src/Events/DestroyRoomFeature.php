<?php

namespace Workdo\Holidayz\Events;

use Illuminate\Queue\SerializesModels;

class DestroyRoomFeature
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $feature;

    public function __construct($feature)
    {
        $this->feature = $feature;
    }

    /**
     * Get the channels the event should be broadcast on.
     *
     * @return array
     */
    public function broadcastOn()
    {
        return [];
    }
}
