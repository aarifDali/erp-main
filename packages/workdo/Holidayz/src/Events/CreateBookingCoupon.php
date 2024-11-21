<?php

namespace Workdo\Holidayz\Events;

use Illuminate\Queue\SerializesModels;

class CreateBookingCoupon
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $request;
    public $coupon;

    public function __construct($request ,$coupon)
    {
        $this->request = $request;
        $this->coupon = $coupon;
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
