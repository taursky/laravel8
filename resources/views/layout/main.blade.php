<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ env('APP_NAME') }} | @yield('title')</title>
    <meta name="description" content="@yield('description')">
    <meta name="Keywords" content="@yield('keywords')">
    {{-- Styles --}}
    <link href="{{ asset('css/font-awesome.css') }}" rel="stylesheet">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/main.css') }}" rel="stylesheet">
    <link href="{{ asset('css/mobile.css') }}" rel="stylesheet">
    {{-- Scripts --}}
    <script src="{{ asset('js/app.js') }}" defer></script>

    {{--    @if(Route::current()->uri == '/')--}}
    {{--        <script src="{{ asset('js/slick/slick.min.js') }}" defer></script>--}}
    {{--    @endif--}}
</head>
<body>
{{-- MOBILE MENU --}}
<div class="mobile-menu ">{{-- display-mobile --}}
    <div class="mobile-login">
        <a href="/">
            <img src="{{ asset('images/logo.svg') }}">
        </a>
        <button id="close_mobile_menu" class="mobile-menu__close">Закрыть</button>
    </div>
    <div class="wrapper">

        <div class="mobile-menu__inner-wrapper">
            <nav class="mobile-navigation-menu mobile-menu__mobile-navigation-menu">
                <ul class="mobile-navigation-menu__list">
                    <li class="mobile-navigation-menu__item">
                        <a class="nav-link" href="{{route('catalog')}}">НА СКЛАДЕ </a>
                    </li>

                    <li class="mobile-navigation-menu__item">
                        <a class="nav-link" href="{{route('reserve.price')}}">ЗАГРУЗИТЬ ПРАЙС</a>
                    </li>
                    @guest
                    @else
                        <li class="mobile-navigation-menu__item">
                            <a class="nav-link" href="{{route('ticket')}}">ТЕХ. ПОДДЕРЖКА </a>
                        </li>
                    @endguest
                    <li class="mobile-navigation-menu__item ">
                        <a class="nav-link" href="{{route('delivery')}}">ДОСТАВКА </a>
                    </li>
                    <li class="mobile-navigation-menu__item">
                        <a class="nav-link teh-menu" href="{{route('sale')}}">РАСПРОДАЖА</a>
                    </li>
                    <li class="mobile-navigation-menu__item">
                        <a class="nav-link contact" href="{{route('contact')}}">КОНТАКТЫ </a>
                    </li>
                    <li class="mobile-navigation-menu__item">
                        <a href="{{route('asks.avto')}}">Заказать запчасти для авто</a>
                    </li>
                    @guest
                        <li class="mobile-navigation-menu__item">
                            <a href="{{ route('login') }}" class="nav-link"><i class="fa fa-sign-in"
                                                                               aria-hidden="true"></i>&nbsp;
                                Вход
                            </a>
                        </li>
                        <li class="mobile-navigation-menu__item">
                            <a href="{{ route('register') }}" class="nav-link"><i class="fa fa-user-secret"
                                                                                  aria-hidden="true"></i>&nbsp;Регистрация</a>
                        </li>
                    @else
                        <li class="mobile-navigation-menu__item">
                            <a href="{{route('personal')}}" class="nav-link news" title="Редактировать профиль"><i
                                    class="fa fa-user" aria-hidden="true"></i>&nbsp; {{Auth::user()->name}}</a>
                        </li>
                        <li class="mobile-navigation-menu__item">
                            <a href="{{ route('logout') }}" class="nav-link"
                               onclick="event.preventDefault();document.getElementById('out-form').submit();">
                                &nbsp;<i class="fa fa-sign-out " aria-hidden="true"></i>
                            </a>
                            <form id="out-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                {{ csrf_field() }}
                            </form>
                        </li>
                    @endguest
                </ul>
            </nav>

            <p class="copyright mobile-menu__copyright hidden-small-mobile">© Барс-авто, {{date('Y')}}</p>
        </div>
    </div>
</div>
{{-- END MOBILE MENU --}}
<div class="wrapper">
    <button id="hamburger" class="btn btn-outline-info  display-mobile">Меню</button>
    <div class="container-fluid">
        <div class="header">
            <div class="row">
                <div class="col-sm-12 col-md-3">
                    <div class="logo">
                        <a href="{{ URL::to('/') }}">
                            <img src="{{ asset('images/logo.svg') }}">
                        </a>
                    </div>
                </div>
                {{--надпись рядом с логотипом --}}
                <div class="col-sm-12 col-md-6">
                    <div class="header-name">
                        <div class="header-name-image">
                            <a href="{{ URL::to('/') }}">
                                <img src="{{ asset('images/BARS_TEXT-03.svg') }}">
                            </a>
                        </div>
                        <div class="header-name-text">
                            интернет магазин автозапчастей
                        </div>
                    </div>
                </div>
                <div class="col-sm-12 col-md-3">
                    <div class="smalcart">
                        <a href="{{route('cart')}}">
                            <div class="smalcart-logo">
                            </div>

                            <div class="smalcart-count">
				<span class="smalcart-count-text">
				@if (Auth::guest())
                        0
                    @else
                        @php
                            $cart_count = \App\Cart::where('user_id', Auth::user()->id)->sum('count');
                        @endphp
                        {{$cart_count}}
                    @endif
				</span>
                            </div>
                            <div class="korzina">корзина</div>
                            <div class="smalcart-count-zakaz">Оформить заказ</div>
                        </a>
                    </div>
                </div>
                {{-- cdek form --}}
            </div>
            <div class="logo_avto">
                <img src="{{ asset('images/avto_icons/Toyota.png') }}" height="60px">
                <img src="{{ asset('images/avto_icons/Nissan.png') }}" height="60px">
                <img src="{{ asset('images/avto_icons/Mitsubishi.png') }}" height="60px">
                <img src="{{ asset('images/avto_icons/Mazda.png') }}" height="60px">
                <img src="{{ asset('images/avto_icons/Lexus.png') }}" height="60px">
                <img src="{{ asset('images/avto_icons/Honda.png') }}" height="60px">
                <img src="{{ asset('images/avto_icons/Subaru.png') }}" height="60px">
                <img src="{{ asset('images/avto_icons/Suzuki.png') }}" height="60px">
                <img src="{{ asset('images/avto_icons/Acura.png') }}" height="60px">
            </div>
        </div>
    </div>
</div>
{{-- NEW MENU --}}
<div class="clear_10"></div>

<nav class="navbar navbar-expand ju justify-content-center   header-nav main-menu">

    {{-- Left Side Of Navbar --}}
    <ul class="nav navbar-nav">
        <li class="nav-item">
            <a class="nav-link" href="{{route('catalog')}}">НА СКЛАДЕ </a>
        </li>
        <li>
            <img src="{{ asset('images/style/separator.png') }}" style="height: 48px;width: 2px">
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{route('reserve.price')}}">ЗАГРУЗИТЬ ПРАЙС</a>
        </li>
        <li>
            <img src="/images/style/separator.png" style="height: 48px;width: 2px">
        </li>
        @if (Auth::guest())
        @else
            <li class="nav-item">
                <a class="nav-link" href="{{route('ticket')}}">ТЕХ. ПОДДЕРЖКА </a>
            </li>
            <li>
                <img src="/images/style/separator.png" style="height: 48px;width: 2px">
            </li>
        @endif
        <li class="nav-item ">
            <a class="nav-link" href="{{route('delivery')}}">ДОСТАВКА </a>
        </li>
        <li><img src="/images/style/separator.png" style="height: 48px;width: 2px"></li>
        <li class="nav-item ">
            <a class="nav-link teh-menu" href="/sale">РАСПРОДАЖА</a>
        </li>
        <li><img src="/images/style/separator.png" style="height: 48px;width: 2px"></li>
        <li class="nav-item ">
            <a class="nav-link contact" href="{{route('contact')}}">КОНТАКТЫ </a>
        </li>
        @if(Auth::check() && Auth::user()->role == 1)
            <li><img src="/images/style/separator.png" style="height: 48px;width: 2px"></li>
            <li class="nav-item ">
                <a href="/admin" class="nav-link" style="color:#af0000;">ADMIN</a>
            </li>
        @endif
        {{--<li><img src="/images/style/separator.png" style="height: 48px;width: 2px"></li>
        <li class="nav-item ">
            <a class="nav-link" href="http://old.bars-avto.com" target="_blank"><span style="font-size:10px;color: #e8e627">Старый сайт</span></a>
        </li>
        --}}
    </ul>

    {{-- Right Side Of Navbar --}}
    <ul class="nav navbar-nav navbar-right">
        {{-- Authentication Links --}}
        @if (Auth::guest())
            &nbsp;&nbsp;&nbsp;&nbsp;
            <li class="nav-item disabled">
                <a class="nav-link" href="#">&nbsp;&nbsp;</a>
            </li>
            <li class="nav-item">
                <a href="{{ route('login') }}" class="nav-link"><i class="fa fa-sign-in" aria-hidden="true"></i>&nbsp;
                    Вход
                </a>
            </li>
            <li><img src="/images/style/separator.png" style="height: 48px;width: 2px"></li>
            <li class="nav-item">
                <a href="{{ route('register') }}" class="nav-link"><i class="fa fa-user-secret" aria-hidden="true"></i>&nbsp;Регистрация</a>
            </li>
            <li><img src="/images/style/separator.png" style="height: 48px;width: 2px"></li>
        @else
            <li class="nav-item">
                <a class="nav-link disabled" href="#">&nbsp;</a>
            </li>
            <li class="nav-item"><a href="{{route('personal.balance')}}" class="nav-link news"><i
                        class="fa fa-money"></i> {{ number_format(Auth::user()->account, 0, '.', ' ') }}</a></li>
            <li><img src="/images/style/separator.png" style="height: 48px;width: 2px"></li>
            <li class="nav-item">
                <a href="/personal" class="nav-link news" title="Редактировать профиль"><i class="fa fa-user"
                                                                                           aria-hidden="true"></i>&nbsp; {{Auth::user()->name}}
                </a>
            </li>
            <li><img src="/images/style/separator.png" style="height: 48px;width: 2px"></li>
            <li class="nav-item">
                <a class="nav-link"
                   onclick="document.getElementById('logout-form').submit();return false;">{{-- event.preventDefault(); --}}
                    &nbsp;<i class="fa fa-sign-out fa-lg" aria-hidden="true"></i>
                </a>

                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    {{ csrf_field() }}
                </form>
            </li>
        @endif
    </ul>
</nav>

{{-- END NEW MENU --}}

<div class="container">
    <div class="clear_5"></div>
    <div class="row">
        @if(Route::currentRouteName() == '/' || Route::currentRouteName() == 'home' || Route::currentRouteName() == 'catalog' || Route::currentRouteName() == 'search.storage')
            <div class="col-xl-12">
                @else
                    @include('sidebar.first')
                    <div class="col-lg-9">
                        @endif
                        @if (Route::currentRouteName() == 'enter' || Route::currentRouteName() == 'registration' || Route::currentRouteName() == 'contact')
                        @else
                            <div class="rezOemPoisk">
                                @include('form.poisk_oem')
                            </div>
                        @endif
                        @yield('content')
                    </div>
            </div>{{-- END col-12--}}

    </div>{{-- end row --}}

    <div id="footer">
        <div id="center-footer">
            <div class="bottom-menu">
                <ul>
                    {{--                    <li class="footerMenu" style="color:#032d26;"> Карта сайта</li>--}}
                    <li class="footerMenu"><a href="/">Главная</a></li>

                    @if (Auth::guest())
                    @else
                        <li class="footerMenu"><a href="{{route('ticket')}}" id="footerMenu"> Тех. поддержка </a></li>
                    @endif
                    <li class="footerMenu"><a href="/catalog" id="footerMenu"> На складе </a></li>
                    <li class="footerMenu"><a href="/delivery" id="footerMenu"> Доставка </a></li>
                    <li class="footerMenu"><a href="/contact" id="footerMenu">Контакты</a></li>
                </ul>
            </div>

            <br>
            <em>
                <span
                    style="font-size:18px;width:250px;height:28px;float:right;background: url({{asset('/images/icons/mobile_32.png')}})0 50% no-repeat;">тел: {{config('app.phone')}}</span>
            </em>
            <br><br>
            {{--   <div class="footer-email">--}}
            <em>
                <span
                    style="background: url({{asset('/images/icons/email_32.png')}})0 50% no-repeat;vertical-align:text-bottom;font-size:16px;width:245px;height:25px;float:right;">
                    e-mail: <a href="mailto:{{ config('app.email') }}">{{ config('app.email') }}</a>
                </span>
            </em>
            {{--</div>--}}
            <br><br>
            <em>
                <span
                    style="background: url({{asset('/images/icons/gear_32.png')}})0 50% no-repeat;vertical-align:text-bottom;font-size:16px;width:245px;height:25px;float:right;">
                    <a href="https://taursky.ru" target="_blank">Разработка сайта</a>
                </span>
            </em>
            <br><br>
            <em>© "{{ config('app.name') }}" 2014-{{date('Y')}} г.</em>
        </div>
        <div class="freeKass" style="position:absolute;left:0;bottom:0;">
        </div>
    </div>
</div>
@include('template.send_form_screen')
<script src="{{ asset('js/manifest.js') }}" defer></script>
<script src="{{ asset('js/vendor.js') }}" defer></script>
@if(Route::currentRouteName() == 'contact')
    <script src='https://www.google.com/recaptcha/api.js'></script>
@endif

</body>
</html>
