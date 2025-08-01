<?php

namespace Workdo\AssistNow\Listeners;

use Illuminate\Support\Facades\Auth;
use App\Events\CompanyMenuEvent;

class CompanyMenuListener
{
    /**
     * Handle the event.
     */
    public function handle(CompanyMenuEvent $event): void
    {
        
        $user = Auth::user();

        if (!$user || $user->type !== 'company') {
            return;
        }
        
        $module = 'AssistNow';
        $menu = $event->menu;
        $menu->add([
            'category' => 'General',
            'title' => __('Saeda Dashboard'),
            'icon' => '',
            'name' => 'assistnow-dashboard',
            'parent' => 'dashboard',
            'order' => 100,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'assistnow.dashboard',
            'module' => $module,
            // 'permission' => 'holidayz dashboard manage'
        ]);
        $menu->add([
            'category' => 'Operations',
            'title' => __('Saeda'),
            'icon' => 'book',
            'name' => 'assistnow',
            'parent' => null,
            'order' => 700,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => '',
            'module' => $module,
            // 'permission' => 'holidayz manage'
        ]);
        $menu->add([
            'category' => 'Operations',
            'title' => __('Task Assignment'),
            'icon' => '',
            'name' => 'task-assignment',
            'parent' => 'assistnow',
            'order' => 10,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'task-assignments.index',
            'module' => $module,
            // 'permission' => 'hotel management manage'
        ]);
        $menu->add([
            'category' => 'Operations',
            'title' => __('Service Management'),
            'icon' => '',
            'name' => 'service-management',
            'parent' => 'assistnow',
            'order' => 10,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => '',
            'module' => $module,
            // 'permission' => 'hotel management manage'
        ]);
        $menu->add([
            'category' => 'Operations',
            'title' => __('Services'),
            'icon' => '',
            'name' => 'assistnow_services',
            'parent' => 'service-management',
            'order' => 10,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'assistnow-services.index',
            'module' => $module,
            // 'permission' => 'services manage'
        ]);
        $menu->add([
            'category' => 'Operations',
            'title' => __('Client Management'),
            'icon' => '',
            'name' => 'client-management',
            'parent' => 'assistnow',
            'order' => 15,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => '',
            'module' => $module,
            // 'permission' => 'rooms manage'
        ]);
        $menu->add([
            'category' => 'Operations',
            'title' => __('Clients'),
            'icon' => '',
            'name' => 'assistnow_clients',
            'parent' => 'client-management',
            'order' => 10,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'assistnow-clients.index',
            'module' => $module,
            // 'permission' => 'services manage'
        ]);
        $menu->add([
            'category' => 'Operations',
            'title' => __('Debtors'),
            'icon' => '',
            'name' => 'assistnow_debtors',
            'parent' => 'client-management',
            'order' => 15,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'assistnow-debtors.index',
            'module' => $module,
            // 'permission' => 'services manage'
        ]);
        $menu->add([
            'category' => 'Operations',
            'title' => __('Reports'),
            'icon' => '',
            'name' => 'reports',
            'parent' => 'assistnow',
            'order' => 20,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => '',
            'module' => $module,
            // 'permission' => 'hotel management manage'
        ]);
        $menu->add([
            'category' => 'Operations',
            'title' => __('Staff Report'),
            'icon' => '',
            'name' => 'staff_report',
            'parent' => 'reports',
            'order' => 10,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'staff-reports.index',
            'module' => $module,
            // 'permission' => 'services manage'
        ]);
    }
}