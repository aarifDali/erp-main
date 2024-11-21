<?php

namespace Workdo\Holidayz\Events;

use Illuminate\Queue\SerializesModels;

class UpdateHotelTheme
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $request;
    public $hotel_theme;

    public function __construct($request ,$hotel_theme)
    {
        $this->request = $request;
        $this->hotel_theme = $hotel_theme;
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
