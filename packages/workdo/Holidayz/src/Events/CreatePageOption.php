<?php

namespace Workdo\Holidayz\Events;

use Illuminate\Queue\SerializesModels;

class CreatePageOption
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $request;
    public $custom_page;

    public function __construct($request ,$custom_page)
    {
        $this->request = $request;
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
