<?php

namespace Workdo\Holidayz\Events;

use Illuminate\Queue\SerializesModels;

class DestroyRoomFacility
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $facility;

    public function __construct($facility)
    {
        $this->facility = $facility;
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
