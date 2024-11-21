<?php

namespace Workdo\Holidayz\Events;

use Illuminate\Queue\SerializesModels;

class UpdateRoomFacility
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $request;
    public $facility;

    public function __construct($request ,$facility)
    {
        $this->request = $request;
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
