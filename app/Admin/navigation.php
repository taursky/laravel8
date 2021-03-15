<?php

use SleepingOwl\Admin\Navigation\Page;

// Default check access logic
// AdminNavigation::setAccessLogic(function(Page $page) {
// 	   return auth()->user()->isSuperAdmin();
// });
//
// AdminNavigation::addPage(\App\User::class)->setTitle('test')->setPages(function(Page $page) {
// 	  $page
//		  ->addPage()
//	  	  ->setTitle('Dashboard')
//		  ->setUrl(route('admin.dashboard'))
//		  ->setPriority(100);
//
//	  $page->addPage(\App\User::class);
// });
//
// // or
//
// AdminSection::addMenuPage(\App\User::class)
AdminNavigation::setFromArray([
    [
        'title' => 'Настройки',
        'icon' => 'fa fa-bars',
        'priority' => 10,
        'pages' => [

            (new Page(\App\TypeTextPage::class))
                ->setTitle('Тексты на страницах')
                ->setIcon('fa fa-newspaper')
                ->setPriority(80)
                ->setAccessLogic(function () {
                    if (Auth::user()->role == 1)
                        return true;
                    return false;
                }),
            (new Page(\App\Slider::class))
                ->setTitle('Слайдер на главной')
                ->setIcon('fa fa-image')
                ->setPriority(105)
                ->setAccessLogic(function () {
                    if (Auth::user()->role == 1)
                        return true;
                    return false;
                }),
            (new Page(\App\Sale::class))
                ->setTitle('Распродажа')
                ->setIcon('fa fa-credit-card')
                ->setPriority(91)
                ->setAccessLogic(function () {
                    if (Auth::user()->role == 1)
                        return true;
                    return false;
                }),
            (new Page(\App\ChangeArticl::class))
                ->setTitle('Замена Артикула')
                ->setIcon('fa fa-retweet')
                ->setPriority(92)
                ->setAccessLogic(function () {
                    if (Auth::user()->role == 1)
                        return true;
                    return false;
                }),
            (new Page(\App\Margin::class))
                ->setTitle('Наценки')
                ->setIcon('fa fa-magic')
                ->setPriority(93)
                ->setAccessLogic(function () {
                    if (Auth::user()->role == 1)
                        return true;
                    return false;
                }),
            (new Page(\App\Kurs::class))
                ->setTitle('Курс доллара')
                ->setIcon('fa fa-thumbs-down')
                ->setPriority(94)
                ->setAccessLogic(function () {
                    if (Auth::user()->role == 1)
                        return true;
                    return false;
                }),
            (new Page(\App\Counter::class))
                ->setTitle('Счетчики')
                ->setIcon('fa fa-cog')
                ->setPriority(95)
                ->setAccessLogic(function () {
                    if (Auth::user()->role == 1)
                        return true;
                    return false;
                }),
        ]
    ],
]);
AdminNavigation::setFromArray([
    [
        'title' => 'Склад',
        'icon' => 'fa fa-address-card',
        'priority' => 20,
        'pages' => [
            (new Page(\App\Product::class))
                ->setTitle('Список')
                ->setIcon('fa fa-database')
                ->setPriority(90)
                ->addBadge(function () {
                    return \App\Product::count();
                }, ['class' => 'label-warning'])
                ->setAccessLogic(function () {
                    if (Auth::user()->role == 1)
                        return true;
                    return false;
                }),
            [
                'title' => 'Добавить продукцию',
                'priority' => 100,
                'icon' => 'fa fa-address-card',
                'url' => route('admin.product.add'),
            ],
        ]
    ]
]);
AdminNavigation::setFromArray([
    [
        'title' => 'Пользователи',
        'icon' => 'fa fa-users',
        'priority' => 30,
        //'badge' => new \SleepingOwl\Admin\Navigation\Badge(function (){
        //    return \App\User::count();
        //}),
        'pages' => [
            (new Page(\App\User::class))
                ->setTitle('Пользователи')
                ->setIcon('fa fa-users')
                ->setPriority(100)
                ->addBadge(function () {
                    return \App\User::count();
                }, ['class' => 'label-info'])
                ->setAccessLogic(function () {
                    if (Auth::user()->role == 1)
                        return true;
                    return false;
                }),
            (new Page(\App\UserCompany::class))
                ->setTitle('Компании')
                ->setIcon('fa fa-university')
                ->setPriority(110)
                ->addBadge(function () {
                    return \App\UserCompany::count();
                }, ['class' => 'label-warning'])
                ->setAccessLogic(function () {
                    if (Auth::user()->role == 1)
                        return true;
                    return false;
                }),
            (new Page(\App\Role::class))
                ->setTitle('Роли')
                ->setIcon('fa fa-user-secret')
                ->setPriority(120)
                ->setAccessLogic(function () {
                    if (Auth::user()->role == 1)
                        return true;
                    return false;
                }),
        ]
    ]
]);
//Заказы
AdminNavigation::setFromArray([
    [
        'title' => 'Заказы',
        'icon' => 'fa fa-envelope-open',
        'priority' => 40,
        'pages' => [
            (new Page(\App\MailOrder::class))
                ->setTitle('Заказы на Email')
                ->setIcon('fa fa-envelope')
                ->setPriority(90)
                ->addBadge(function () {
                    return \App\Order::where('type', 1)->count();
                }, ['class' => 'label-success'])
                ->setAccessLogic(function () {
                    if (Auth::user()->role == 1)
                        return true;
                    return false;
                }),
            (new Page(\App\Order::class))
                ->setTitle('Заказы с оплатой')
                ->setIcon('fa fa-cart-plus')
                ->setPriority(100)
                ->addBadge(function () {
                    return \App\Order::where('type', 2)->count();
                }, ['class' => 'label-danger'])
                ->setAccessLogic(function () {
                    if (Auth::user()->role == 1)
                        return true;
                    return false;
                }),
            (new Page(\App\OrderOwn::class))
                ->setTitle('Заказы с сайта')
                ->setIcon('fa fa-shopping-basket')
                ->setPriority(110)
                ->addBadge(function () {
                    return \App\Order::where('type', 3)->count();
                }, ['class' => 'label-info'])
                ->setAccessLogic(function () {
                    if (Auth::user()->role == 1)
                        return true;
                    return false;
                }),
            (new Page(\App\Cart::class))
                ->setTitle('Корзины')
                ->setIcon('fa fa-cart-plus')
                ->setPriority(120)
                ->setAccessLogic(function () {
                    if (Auth::user()->role == 1)
                        return true;
                    return false;
                }),
            (new Page(\App\Delivery::class))
                ->setTitle('Доставка')
                ->setIcon('fa fa-truck')
                ->setPriority(130)
                ->setAccessLogic(function () {
                    if (Auth::user()->role == 1)
                        return true;
                    return false;
                }),
        ]
    ]
]);
//Техподдержка
AdminNavigation::setFromArray([
    [
        'title' => 'Тех поддержка',
        'icon' => 'fa fa-question-circle',
        'priority' => 50,
        'pages' => [

            (new Page(\App\Ticket::class))
                ->setTitle('Тикеты')
                ->setIcon('fa fa-file')
                ->setPriority(0)
                ->addBadge(function () {
                    return \App\Ticket::where('status', 0)->count();
                }, ['class' => 'label-danger'])
                ->setAccessLogic(function () {
                    if (Auth::user()->role == 1)
                        return true;
                    return false;
                }),
            (new Page(\App\TicketMes::class))
                ->setTitle('Сообщения')
                ->setIcon('fa fa-comment')
                ->setPriority(10)
                ->setAccessLogic(function () {
                    if (Auth::user()->role == 1)
                        return true;
                    return false;
                }),
            (new Page(\App\TicketCategory::class))
                ->setTitle('Категории вопросов')
                ->setIcon('fa fa-database')
                ->setPriority(20)
                ->setAccessLogic(function () {
                    if (Auth::user()->role == 1)
                        return true;
                    return false;
                }),
        ]
    ],
]);

return [
    [
        'title' => 'Панель управления',
        'priority' => 0,
        'icon' => 'fa fa-dashboard',
        'url' => route('admin.dashboard'),
    ],
    (new Page(\App\Detal::class))
        ->setTitle('Запчасти')
        ->setIcon('fa fa-cog')
        ->setPriority(190)
        ->addBadge(function () {
            return \App\Detal::count();
        }, ['class' => 'label-success'])
        ->setAccessLogic(function () {
            if (Auth::user()->role == 1)
                return true;
            return false;
        }),
    (new Page(\App\Brand::class))
        ->setTitle('Производители')
        ->setIcon('fa fa-cogs')
        ->setPriority(200)
        ->setAccessLogic(function () {
            if (Auth::user()->role == 1)
                return true;
            return false;
        }),
    [
        'title' => 'Запросы',
        'priority' => 195,
        'icon' => 'fa fa-eye',
        'badge' => new \SleepingOwl\Admin\Navigation\Badge(\App\Seacher::count()),
        'url' => route('admin.search.show')
    ],
];
//return [
//    [
//        'title' => 'Dashboard',
//        'icon'  => 'fas fa-tachometer-alt',
//        'url'   => route('admin.dashboard'),
//    ],
//
//    [
//        'title' => 'Information',
//        'icon'  => 'fas fa-info-circle',
//        'url'   => route('admin.information'),
//    ],

// Examples
// [
//    'title' => 'Content',
//    'pages' => [
//
//        \App\User::class,
//
//        // or
//
//        (new Page(\App\User::class))
//            ->setPriority(100)
//            ->setIcon('fas fa-users')
//            ->setUrl('users')
//            ->setAccessLogic(function (Page $page) {
//                return auth()->user()->isSuperAdmin();
//            }),
//
//        // or
//
//        new Page([
//            'title'    => 'News',
//            'priority' => 200,
//            'model'    => \App\News::class
//        ]),
//
//        // or
//        (new Page(/* ... */))->setPages(function (Page $page) {
//            $page->addPage([
//                'title'    => 'Blog',
//                'priority' => 100,
//                'model'    => \App\Blog::class
//		      ));
//
//		      $page->addPage(\App\Blog::class);
//	      }),
//
//        // or
//
//        [
//            'title'       => 'News',
//            'priority'    => 300,
//            'accessLogic' => function ($page) {
//                return $page->isActive();
//		      },
//            'pages'       => [
//
//                // ...
//
//            ]
//        ]
//    ]
// ]
//];
