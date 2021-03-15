@extends('layout.main')
@section('title', 'Оплата на Яндекс деньги')
@section('description', '')
@section('keywords', '')
@section('content')
    <a href="{{route('cart')}}" class="btn btn-warning">Вернуться в корзину</a>
    <h1> Оплата на Яндекс деньги </h1>
    <p>Оплачивая товары на кошелек Yandex, Вы соглашаетесь с
        <a href="{{route('rules')}}" target="_blank">правилами и условиями</a>
        интернет магазина <b style="color:red;">"БАРС"</b>. </p>

    {{--TODO вставить форму яндекс --}}
    <b>номер кошелька Yandex: 4100 1385 2684 4772</b> (Барсуков Александр Михайлович).<div class="clear_40"></div>

@endsection
