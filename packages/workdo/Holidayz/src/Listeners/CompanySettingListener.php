<?php

namespace Workdo\Holidayz\Listeners;
use App\Events\CompanySettingEvent;

class CompanySettingListener
{
    /**
     * Handle the event.
     */
    public function handle(CompanySettingEvent $event): void
    {
        $module = 'Holidayz';
        $methodName = 'index';
        $controllerClass = "Workdo\\Holidayz\\Http\\Controllers\\Company\\SettingsController";
        if (class_exists($controllerClass)) {
            $controller = \App::make($controllerClass);
            if (method_exists($controller, $methodName)) {
                $html = $event->html;
                $settings = $html->getSettings();
                $output =  $controller->{$methodName}($settings);
                $html->add([
                    'html' => $output->toHtml(),
                    'order' => 290,
                    'module' => $module,
                    'permission' => 'holidayz manage'
                ]);
            }
        }
    }
}
