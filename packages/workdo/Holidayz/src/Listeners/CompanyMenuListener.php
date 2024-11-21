<?php

namespace Workdo\Holidayz\Listeners;

use App\Events\CompanyMenuEvent;

class CompanyMenuListener
{
    /**
     * Handle the event.
     */
    public function handle(CompanyMenuEvent $event): void
    {
        $module = 'Holidayz';
        $menu = $event->menu;
        $menu->add([
            'category' => 'General',
            'title' => __('Hotel Dashboard'),
            'icon' => '',
            'name' => 'hotel-dashboard',
            'parent' => 'dashboard',
            'order' => 90,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'holidayz.dashboard',
            'module' => $module,
            'permission' => 'holidayz dashboard manage'
        ]);
        $menu->add([
            'category' => 'Operations',
            'title' => __('Hotel&Apartment Management'),
            'icon' => 'building',
            'name' => 'hotel-room',
            'parent' => null,
            'order' => 600,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => '',
            'module' => $module,
            'permission' => 'holidayz manage'
        ]);
        $menu->add([
            'category' => 'Operations',
            'title' => __('Hotel Management'),
            'icon' => '',
            'name' => 'hotel-management',
            'parent' => 'hotel-room',
            'order' => 10,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => '',
            'module' => $module,
            'permission' => 'hotel management manage'
        ]);
        $menu->add([
            'category' => 'Operations',
            'title' => __('Amenities'),
            'icon' => '',
            'name' => 'amenities',
            'parent' => 'hotel-management',
            'order' => 10,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'hotel-services.index',
            'module' => $module,
            'permission' => 'services manage'
        ]);
        $menu->add([
            'category' => 'Operations',
            'title' => __('Rooms'),
            'icon' => '',
            'name' => 'rooms',
            'parent' => 'hotel-room',
            'order' => 15,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => '',
            'module' => $module,
            'permission' => 'rooms manage'
        ]);
        $menu->add([
            'category' => 'Operations',
            'title' => __('Room Types'),
            'icon' => '',
            'name' => 'room-types',
            'parent' => 'rooms',
            'order' => 10,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'hotel-rooms.index',
            'module' => $module,
            'permission' => 'type manage'
        ]);
        $menu->add([
            'category' => 'Operations',
            'title' => __('Room Features'),
            'icon' => '',
            'name' => 'room-features',
            'parent' => 'rooms',
            'order' => 15,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'hotel-room-features.index',
            'module' => $module,
            'permission' => 'feature manage'
        ]);
        $menu->add([
            'category' => 'Operations',
            'title' => __('Facilities'),
            'icon' => '',
            'name' => 'facilities',
            'parent' => 'hotel-room',
            'order' => 20,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'hotel-room-facilities.index',
            'module' => $module,
            'permission' => 'facilities manage'
        ]);
        $menu->add([
            'category' => 'Operations',
            'title' => __('Booking'),
            'icon' => '',
            'name' => 'booking',
            'parent' => 'hotel-room',
            'order' => 25,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'hotel-room-booking.index',
            'module' => $module,
            'permission' => 'rooms booking manage'
        ]);
        $menu->add([
            'category' => 'Operations',
            'title' => __('Coupons'),
            'icon' => '',
            'name' => 'coupons',
            'parent' => 'hotel-room',
            'order' => 30,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'room-booking-coupon.index',
            'module' => $module,
            'permission' => 'customer coupon manage'
        ]);
        $menu->add([
            'category' => 'Operations',
            'title' => __('Custom Page'),
            'icon' => '',
            'name' => 'custom-page',
            'parent' => 'hotel-room',
            'order' => 35,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'hotel-custom-page.index',
            'module' => $module,
            'permission' => 'custom pages manage'
        ]);
        $menu->add([
            'category' => 'Operations',
            'title' => __('Hotel Customer'),
            'icon' => '',
            'name' => 'hotel-customer',
            'parent' => 'hotel-room',
            'order' => 40,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'hotel-customer.index',
            'module' => $module,
            'permission' => 'hotel customer manage'
        ]);
        $menu->add([
            'category' => 'Operations',
            'title' => __('Bank Transfer Request'),
            'icon' => '',
            'name' => 'hotel-customer',
            'parent' => 'hotel-room',
            'order' => 45,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'room-booking-bank-transfer.index',
            'module' => $module,
            'permission' => 'rooms booking manage'
        ]);
    }
}
