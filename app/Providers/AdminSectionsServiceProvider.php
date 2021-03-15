<?php

namespace App\Providers;

use SleepingOwl\Admin\Providers\AdminSectionsServiceProvider as ServiceProvider;
use SleepingOwl\Admin\Contracts\Widgets\WidgetsRegistryInterface;

class AdminSectionsServiceProvider extends ServiceProvider
{
    protected $widgets = [
        \App\Admin\Widgets\DashboardMap::class,
        //\App\Admin\Widgets\NavigationNotifications::class,
        \App\Admin\Widgets\NavigationUserBlock::class,
    ];

    /**
     * @var array
     */
    protected $sections = [
        \App\UserCompany::class => 'App\Http\Sections\UserCompanyes',
        \App\Role::class => 'App\Http\Sections\Roles',
        \App\Product::class => 'App\Http\Sections\Products',
        \App\Slider::class => 'App\Http\Sections\Sliders',
        \App\TypeTextPage::class => 'App\Http\Sections\TypeTextPages',
        \App\Sale::class => 'App\Http\Sections\Sales',
        \App\ChangeArticl::class => 'App\Http\Sections\ChangeArticls',
        \App\Margin::class => 'App\Http\Sections\Margins',
        \App\Kurs::class => 'App\Http\Sections\Kurses',
        \App\Brand::class => 'App\Http\Sections\Brands',
        \App\Order::class => 'App\Http\Sections\Orders',
        \App\MailOrder::class => 'App\Http\Sections\MailOrders',
        \App\OrderOwn::class => 'App\Http\Sections\OrderOwns',
        \App\Counter::class => 'App\Http\Sections\Counters',
        \App\TicketCategory::class => 'App\Http\Sections\TicketCategoryes',
        \App\Ticket::class => 'App\Http\Sections\Tickets',
        \App\TicketMes::class => 'App\Http\Sections\TicketMeses',
        \App\Delivery::class => 'App\Http\Sections\Deliveryes',
        \App\Cart::class => 'App\Http\Sections\Carts',
        \App\Detal::class => 'App\Http\Sections\Detals',
        \App\Seacher::class => 'App\Http\Sections\Seachers',
        \App\User::class => 'App\Http\Sections\Users',
    ];

    /**
     * Register sections.
     *
     * @param \SleepingOwl\Admin\Admin $admin
     * @return void
     */
    public function boot(\SleepingOwl\Admin\Admin $admin)
    {
        $this->loadViewsFrom(base_path("resources/views/admin"), 'admin');

        parent::boot($admin);
        $this->app->call([$this, 'registerViews']);
    }

    /**
     * @param WidgetsRegistryInterface $widgetsRegistry
     */
    public function registerViews(WidgetsRegistryInterface $widgetsRegistry)
    {
        foreach ($this->widgets as $widget) {
            $widgetsRegistry->registerWidget($widget);
        }
    }
}
