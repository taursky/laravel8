@extends('layout.main')
@section('title', 'Заказ по Email')
@section('description', '')
@section('keywords', '')
@section('content')
    <a href="{{route('cart')}}" class="btn btn-warning">Вернуться в корзину</a>
    <h1>Заказ по Email</h1>
    @if(($sale_cart && count($sale_cart) > 0) || ($oem_cart && count($oem_cart) > 0) || ($catalog_cart && count($catalog_cart) > 0))
        @include('order.forms.send')
    @else
        <h1>У Вас нет товаров в корзине для заказа</h1>
    @endif
@endsection
