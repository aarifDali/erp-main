<?php

namespace Workdo\Holidayz\Events;

use Illuminate\Queue\SerializesModels;

class UpdateHotel
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $request;
    public $hotel;

    public function __construct($request ,$hotel)
    {
        $this->request = $request;
        $this->hotel = $hotel;
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
