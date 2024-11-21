<?php

namespace Workdo\Holidayz\Events;

use Illuminate\Queue\SerializesModels;

class DestroyPageOption
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $custom_page;

    public function __construct($custom_page)
    {
        $this->custom_page = $custom_page;
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
