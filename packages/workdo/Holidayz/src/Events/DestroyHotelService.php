<?php

namespace Workdo\Holidayz\Events;

use Illuminate\Queue\SerializesModels;

class DestroyHotelService
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $service;

    public function __construct($service)
    {
        $this->service = $service;
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
