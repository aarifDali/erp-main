<?php

namespace Workdo\Holidayz\Events;

use Illuminate\Queue\SerializesModels;

class UpdateHotelCustomer
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $request;
    public $customer;
    public $hotel;

    public function __construct($request ,$customer,$hotel)
    {
        $this->request = $request;
        $this->customer = $customer;
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
