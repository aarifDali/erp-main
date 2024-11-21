<?php

namespace Workdo\Holidayz\Listeners;

use App\Events\CompanySettingMenuEvent;

class CompanySettingMenuListener
{
    /**
     * Handle the event.
     */
    public function handle(CompanySettingMenuEvent $event): void
    {
        $module = 'Holidayz';
        $menu = $event->menu;
        $menu->add([
            'title' => __('Hotel Settings'),
            'name' => 'hotel-settings',
            'order' => 290,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => '',
            'navigation' => 'holidayz-sidenav',
            'module' => $module,
            'permission' => 'holidayz manage'
        ]);
        $hotel_settings = \Workdo\Holidayz\Entities\Hotels::where('created_by',  creatorId())->where('workspace', getActiveWorkSpace())->pluck('id')->first();
        if(isset($hotel_settings)){
            $menu->add([
                'title' => __('Hotel Theme And Domain Settings'),
                'name' => 'hotel-theme-and-domain-settings',
                'order' => 300,
                'ignore_if' => [],
                'depend_on' => [],
                'route' => '',
                'navigation' => 'themes-settings',
                'module' => $module,
                'permission' => 'holidayz manage'
            ]);
        }
    }
}
