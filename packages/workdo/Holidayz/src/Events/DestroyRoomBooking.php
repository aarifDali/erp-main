<?php

namespace Workdo\Holidayz\Events;

use Illuminate\Queue\SerializesModels;

class DestroyRoomBooking
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $booking;

    public function __construct($booking)
    {
        $this->booking = $booking;
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
