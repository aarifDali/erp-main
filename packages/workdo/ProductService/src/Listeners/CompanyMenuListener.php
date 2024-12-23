<?php

namespace Workdo\ProductService\Listeners;

use App\Events\CompanyMenuEvent;

class CompanyMenuListener
{
    /**
     * Handle the event.
     */
    public function handle(CompanyMenuEvent $event): void
    {
        $module = 'ProductService';
        $menu = $event->menu;

        // Parent Item: Items
        $menu->add([
            'category' => 'General',
            'title' => __('Inventory'),
            'icon' => 'shopping-cart',
            'name' => 'product-service',
            'parent' => null,
            'order' => 100,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => '',
            'module' => $module,
            'permission' => 'product&service manage'
        ]);

        // Child Item 1: Items (view all)
        $menu->add([
            'category' => 'General',
            'title' => __('Items'),
            'icon' => '',
            'name' => 'view-all-items',
            'parent' => 'product-service',
            'order' => 10,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'product-service.index',
            'module' => $module,
            'permission' => 'product&service manage'
        ]);

        // Child Item 2: Shortage Items
        $menu->add([
            'category' => 'General',
            'title' => __('Shortage Items'),
            'icon' => '',
            'name' => 'shortage-items',
            'parent' => 'product-service',
            'order' => 20,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'shortage-product.index',
            'module' => $module,
            'permission' => 'product&service manage'
        ]);
    }
}
