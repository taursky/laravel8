<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta name="description" content=""/>
    <meta name="Keywords" content=""/>

    {{--    CSRF Token --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>"Барс-Авто" Админка</title>
    {{--    Styles --}}
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/main.css') }}" rel="stylesheet">
    {{--    Scripts --}}
    <script src="{{ asset('js/app.js') }}"></script>

</head>
<body>
<div class="container" style="min-height: 40px">
    <div class="admin-head">
        <a href="/admin" class="navbar-brand" style="float: left">
            <h3 class="red">Вернуться на панель админки</h3>
        </a>
        <a class="navbar-brand" href="{{ url('/') }}" style="float: right">
            <h3>Вернуться на "{{ config('app.name', 'Laravel') }}"</h3>
        </a>
    </div>
    <div class="clear clear_5"></div>
</div>
<div class="container">

    @yield('content')

</div>
</body>
</html>
