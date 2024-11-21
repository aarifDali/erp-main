<?php

namespace Workdo\Holidayz\Events;

use Illuminate\Queue\SerializesModels;

class DestroyBookingCoupon
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $coupon;

    public function __construct($coupon)
    {
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
