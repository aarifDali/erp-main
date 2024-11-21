<?php

namespace Workdo\Holidayz\Events;

use Illuminate\Queue\SerializesModels;

class UpdateRoomFeature
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $request;
    public $feature;

    public function __construct($request ,$feature)
    {
        $this->request = $request;
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
