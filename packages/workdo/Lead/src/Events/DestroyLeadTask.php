<?php

namespace Workdo\Lead\Events;

use Illuminate\Queue\SerializesModels;

class DestroyLeadTask
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $lead;

    public function __construct($lead)
    {
        $this->lead = $lead;
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
