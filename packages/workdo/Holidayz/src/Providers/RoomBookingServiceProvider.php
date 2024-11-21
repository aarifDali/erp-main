<?php

namespace Workdo\Holidayz\Providers;

use Illuminate\Support\ServiceProvider;

class RoomBookingServiceProvider extends ServiceProvider
{

    public $views;

    public function boot(){

        $this->views = ['roomBooking' => 'tour-travel-management::tour_booking.index'];
        view()->composer(array_values($this->views), function ($view) {

            $module = array_search($view->getName(), $this->views);
            $tour_id = $view->tour_inquiries[0][0]['tour_id'];
            if(\Auth::check())
            {
                $active_module = ActivatedModule();
                $dependency = explode(',', 'TourTravelManagement');
                if (!empty(array_intersect($dependency, $active_module))) {
                    $view->getFactory()->startPush('createRoomBookingbutton', view('holidayz::tour_hotel_booking.stack_create_room_booking',compact('module','tour_id')));
                }
            }
        });
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [];
    }
}
