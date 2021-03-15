@extends('layout.main')
@section('title', 'Оплата на карту Сбербанка')
@section('description', '')
@section('keywords', '')
@section('content')
    <a href="{{route('cart')}}" class="btn btn-warning">Вернуться в корзину</a>
    <h1>Оплата на карту Сбербанка</h1>
    <p>Оплачивая товары на карточку Сбербанка, Вы соглашаетесь с
        <a href="{{route('rules')}}" target="_blank">правилами и условиями</a> интернет магазина <b style="color:red;">"БАРС"</b>. </p>
    <p>В коментарии к платежу поставить или номер заказа или логин, или email.</p>
    <p><span style="font-size:15px;font-weight:bold;color:red;">Обязательно</span> сообщайте на email ( <span style="color:red;">abars@yandex.ru</span> ), или через вкладку <b>"Контакты"</b> : номер детали,количество и сумму которую вы оплатили!</p>
    <br><b>номер карты Сбербанка: 4276 5000 1056 9539</b> (Барсуков Александр Михайлович).
    <div class="clear_40"></div>

@endsection
