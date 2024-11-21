<?php

namespace Workdo\Holidayz\Events;

use Illuminate\Queue\SerializesModels;

class CreateRoom
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $request;
    public $room;

    public function __construct($request ,$room)
    {
        $this->request = $request;
        $this->room = $room;
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
